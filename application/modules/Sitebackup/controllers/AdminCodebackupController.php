<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: AdminCodebackupController.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
if( version_compare(PHP_VERSION, '7.0.0') >= 0 ) {
  include APPLICATION_PATH . '/application/modules/Sitebackup/mysqli.php';
}

class Sitebackup_AdminCodebackupController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    $order = $this->_getParam('order');
    $id = $this->_getParam('id');
    //Here we deleteing the files according to selection of how many files to keep old files.
    $deletefiles = Engine_Api::_()->sitebackup()->deletebackupfiles();
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitebackup_admin_main', array(), 'sitebackup_admin_main_codebackup');

    $this->view->is_filebackup = 1;

    $page = $this->_getParam('page', 1);
    if( !empty($order) && $id == 'sitebackup' ) {
      $this->view->paginator = Engine_Api::_()->sitebackup()->getSitebackupsPaginatorCodebackup(array(
        'orderby' => 'backup_id ' . $order,));
    } else if( !empty($order) && $id == 'filesize' ) {
      $this->view->paginator = Engine_Api::_()->sitebackup()->getSitebackupsPaginatorCodebackup(array(
        'orderby' => 'backup_filesize ' . $order,));
    } else if( !empty($order) && $id == 'time' ) {
      $this->view->paginator = Engine_Api::_()->sitebackup()->getSitebackupsPaginatorCodebackup(array(
        'orderby' => 'backup_time ' . $order,));
    } else if( !empty($order) && $id == 'method' ) {
      $this->view->paginator = Engine_Api::_()->sitebackup()->getSitebackupsPaginatorCodebackup(array(
        'orderby' => 'backup_method ' . $order,));
    } else if( !empty($order) && $id == 'destinationname' ) {
      $this->view->paginator = Engine_Api::_()->sitebackup()->getSitebackupsPaginatorCodebackup(array(
        'orderby' => 'destination_name ' . $order,));
    } else if( !empty($order) && $id == 'status' ) {
      $this->view->paginator = Engine_Api::_()->sitebackup()->getSitebackupsPaginatorCodebackup(array(
        'orderby' => 'backup_status ' . $order,));
    } else {
      $this->view->paginator = Engine_Api::_()->sitebackup()->getSitebackupsPaginatorCodebackup(array(
        'orderby' => 'backup_id ' . 'DESC',));
    }

    $this->view->order = $order;
    $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
    $this->view->dir_name_temp = $dir_name_temp;
    $this->view->paginator->setItemCountPerPage(10);
    $this->view->paginator->setCurrentPageNumber($page);
    $latest_database_backup = Engine_Api::_()->sitebackup()->getcodebackup();
    if( !empty($latest_database_backup->backup_id) ) {
      $this->view->latesttime = Engine_Api::_()->sitebackup()->time_since($latest_database_backup->backup_time);
      $this->view->backup_id = $latest_database_backup->backup_id;
    }
    $values = array();
    $values['getlogid'] = 1;
    $this->view->logresults = Engine_Api::_()->getDbtable('backuplogs', 'sitebackup')->getLog($values);
  }

  public function deleteAction()
  {
    // In smoothbox
    $id = $this->_getParam('id');
    $this->view->backup_id = $id;
    // Check post
    if( $this->getRequest()->isPost() ) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $sitebackup = Engine_Api::_()->getItem('sitebackup_backup', $id);
        $table = Engine_Api::_()->getDbtable('backups', 'sitebackup');
        $select = $table->select()
          ->where('backup_id = ?', $id)
          ->limit(1);
        $row = $table->fetchRow($select);
        $backup_file = $row->backup_filename1;
        $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
        $path = APPLICATION_PATH . '/public/' . $dir_name_temp . '/' . $backup_file;
        if( !empty($row->destination_id) ) {
          if( file_exists($path) ) {
            @unlink($path);
          } else {
            if( $row->backup_method == 'Amazon S3' ) {
              // $destination = Engine_Api::_()->getItem('sitebackup_destinations', $row->destination_id);
              $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($row->destination_id);
              $aws = new Sitebackup_Service_S3($config);
              $aws->remove($backup_file, $row->file_id);
            } else if( $row->backup_method == 'Google Drive' ) {
              $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($row->destination_id);
              $drive = new Sitebackup_Service_Drive($config);
              $refreshToken = $drive->generateRefreshToken($config['refresh_token']);
              if( isset($refreshToken) ) {
                $drive->deleteFile($row->file_id);
              }
            } else if( $row->backup_method == 'Dropbox' ) {
              $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($row->destination_id);
              $dropbox = new Sitebackup_Service_Dropbox($config);
              $dropbox->deleteFile($row->file_id);
            } else if( $row->backup_method == 'FTP' ) {
              $config = Engine_Api::_()->getItem('sitebackup_destinations', $row->destination_id);
              $ftp = new Sitebackup_Service_FTP;
              $ftp->backup_file_delete($config, $backup_file, $row->backup_id);
            }
          }
        } else {
          if( file_exists($path) )
            @unlink($path);
        }
        $sitebackup->delete();
        $db->commit();
      } catch( Exception $e ) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('')
      ));
    }
    // Output
    $this->renderScript('admin-codebackup/delete.tpl');
  }

  public function deleteselectedAction()
  {
    $this->view->ids = $ids = $this->_getParam('ids');
    $count = explode(",", $ids);
    $this->view->count = count($count);
    if( $this->getRequest()->isPost() ) {
      $ids_array = explode(",", $ids);
      foreach( $ids_array as $id ) {
        $sitebackupid = Engine_Api::_()->getItem('sitebackup_backup', $id);
        $backup_file = $sitebackupid->backup_filename1;
        $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
        $path = APPLICATION_PATH . '/public/' . $dir_name_temp . '/' . $backup_file;
        if( !empty($sitebackupid->destination_id) ) {
          if( file_exists($path) ) {
            @unlink($path);
          } else {
            if( $sitebackupid->backup_method == 'Amazon S3' ) {
              $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($sitebackupid->destination_id);
              $aws = new Sitebackup_Service_S3($config);
              $aws->remove($backup_file, $sitebackupid->file_id);
            } else if( $sitebackupid->backup_method == 'Google Drive' ) {
              $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($sitebackupid->destination_id);
              $drive = new Sitebackup_Service_Drive($config);
              $refreshToken = $drive->generateRefreshToken($config['refresh_token']);
              if( isset($refreshToken) ) {
                $drive->deleteFile($sitebackupid->file_id);
              }
            } else if( $sitebackupid->backup_method == 'Dropbox' ) {
              $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($sitebackupid->destination_id);
              $dropbox = new Sitebackup_Service_Dropbox($config);
              $dropbox->deleteFile($sitebackupid->file_id);
            } else if( $sitebackupid->backup_method == 'FTP' ) {
              $config = Engine_Api::_()->getItem('sitebackup_destinations', $sitebackupid->destination_id);
              $ftp = new Sitebackup_Service_FTP;
              $ftp->backup_file_delete($config, $backup_file, $sitebackupid->backup_id);
            }
          }
        } else {
          if( file_exists($path) )
            @unlink($path);
        }
        if( $sitebackupid )
          $sitebackupid->delete();
      }
      $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('')
      ));
    }
  }

  public function confirmDeleteCodeBackupAction()
  {

    $table = Engine_Api::_()->getDbtable('backups', 'sitebackup');
    if( isset($_POST['clear']) ) {
      $tableName = $table->info('name');
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $select = $table->select()
          ->where('backup_codemethod= ?', 0)
          ->orwhere('backup_codemethod= ?', 2)
          ->where('backup_filesize = ?', '');
        $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
        $rows = $table->fetchAll($select);
        $rows_array = $rows->toarray();
        if( !empty($rows_array) ) {
          foreach( $rows_array as $values ) {
            $backup_file = $values['backup_filename1'];
            $path = APPLICATION_PATH . '/public/' . $dir_name_temp . '/' . $backup_file;
            if( !empty($values['destination_id']) ) {
              if( file_exists($path) ) {
                @unlink($path);
              } else {
                if( $values['backup_method'] == 'Amazon S3' ) {
                  $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($values['destination_id']);
                  $aws = new Sitebackup_Service_S3($config);
                  $aws->remove($backup_file, $values['file_id']);
                } else if( $values['backup_method'] == 'Google Drive' ) {
                  $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($values['destination_id']);
                  $drive = new Sitebackup_Service_Drive($config);
                  $refreshToken = $drive->generateRefreshToken($config['refresh_token']);
                  if( isset($refreshToken) ) {
                    $drive->deleteFile($values['file_id']);
                  }
                } else if( $values['backup_method'] == 'Dropbox' ) {
                  $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($values['destination_id']);
                  $dropbox = new Sitebackup_Service_Dropbox($config);
                  $dropbox->deleteFile($values['file_id']);
                } else if( $values['backup_method'] == 'FTP' ) {
                  $config = Engine_Api::_()->getItem('sitebackup_destinations', $values['destination_id']);
                  $ftp = new Sitebackup_Service_FTP;
                  $ftp->backup_file_delete($config, $backup_file, $values['backup_id']);
                }
              }
            } else {
              if( file_exists($path) )
                @unlink($path);
            }
            $table->delete(array(
              'backup_id = ?' => $values['backup_id'],
            ));
          }
        }
        $db->commit();
      } catch( Exception $e ) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('')
      ));
    }
  }

}
