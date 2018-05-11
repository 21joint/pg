<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: Core.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
if( version_compare(PHP_VERSION, '7.0.0') >= 0 ) {
  include APPLICATION_PATH . '/application/modules/Sitebackup/mysqli.php';
}

class Sitebackup_Api_Core extends Core_Api_Abstract
{
//Here we getting how many result should be shown in history page for database backup.
  public function getSitebackupsPaginator($params = array())
  {
    $paginator = Zend_Paginator::factory($this->getSitebackupsSelect($params));
    if( !empty($params['page']) ) {
      $paginator->setCurrentPageNumber($params['page']);
    }
    if( !empty($params['limit']) ) {
      $paginator->setItemCountPerPage($params['limit']);
    }
    return $paginator;
  }

//Here weselecting the database backup.
  public function getSitebackupsSelect($params = array())
  {
    $table = Engine_Api::_()->getDbtable('backups', 'sitebackup');
    $select = $table->select()
      ->where('backup_codemethod= ?', 1)
      ->orwhere('backup_codemethod= ?', 3)
      ->where('backup_filesize1 = ?', '')
      ->order($params['orderby']);
    return $select;
  }

//Here we getting how many result should be shown in history page for code backup.
  public function getSitebackupsPaginatorCodebackup($params = array())
  {
    $paginator = Zend_Paginator::factory($this->getSitebackupsSelectCodebackup($params));
    if( !empty($params['page']) ) {
      $paginator->setCurrentPageNumber($params['page']);
    }
    if( !empty($params['limit']) ) {
      $paginator->setItemCountPerPage($params['limit']);
    }
    return $paginator;
  }

//Here we selecting the code backup.
  public function getSitebackupsSelectCodebackup($params = array())
  {
    $table = Engine_Api::_()->getDbtable('backups', 'sitebackup');
    $select = $table->select()
      ->where('backup_codemethod= ?', 0)
      ->orwhere('backup_codemethod= ?', 2)
      ->where('backup_filesize1!= ?', '')
      ->order($params['orderby']);
    return $select;
  }

  public function deletedatabasebackup($id)
  {
    $sitebackup = Engine_Api::_()->getItem('sitebackup_backup', $id);
    $table = Engine_Api::_()->getDbtable('backups', 'sitebackup');
    $select = $table->select()
      ->where('backup_id = ?', $id)
      ->limit(1);
    $row = $table->fetchAll($select);
    foreach( $row as $values ) {
      $backup_file = $values->backup_filename;
      $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
      $path = APPLICATION_PATH . '/public/' . $dir_name_temp . '/' . $backup_file;
      if( file_exists($path) ) {
        unlink($path);
      }
      if( $sitebackup )
        $sitebackup->delete();
    }
  }

  public function deletecodebackup($id)
  {
    $sitebackup = Engine_Api::_()->getItem('sitebackup_backup', $id);
    $table = Engine_Api::_()->getDbtable('backups', 'sitebackup');
    $select = $table->select()
      ->where('backup_id = ?', $id)
      ->limit(1);
    $row = $table->fetchAll($select);
    foreach( $row as $values )
      $backup_file1 = $values->backup_filename1;
    $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
    $path1 = APPLICATION_PATH . '/public/' . $dir_name_temp . '/' . $backup_file1;
    if( file_exists($path1) ) {
      unlink($path1);
    }
    if( $sitebackup )
      $sitebackup->delete();
  }

  public function getModuleSubject()
  {
    $sitebackup_menu = hash('ripemd160', 'sitebackup');
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sitebackup.menu', $sitebackup_menu);
    $db = Engine_Api::_()->getDbtable('menuitems', 'core');
    $db_name = $db->info('name');
    $db_select = $db->select()
      ->where('name =?', 'sitebackup_admin_main_destinationsettings');
    $setting_obj = $db->fetchAll($db_select)->toArray();
    if( empty($setting_obj) ) {
      $db->insert(array(
        'name' => 'sitebackup_admin_main_destinationsettings',
        'module' => 'sitebackup',
        'label' => 'Destinations',
        'plugin' => NULL,
        'params' => '{"route":"admin_default","module":"sitebackup","controller":"destinationsettings"}',
        'menu' => 'sitebackup_admin_main',
        'submenu' => '',
        'custom' => 0,
        'order' => 1,
      ));
    }
    $db_select = $db->select()
      ->where('name =?', 'sitebackup_admin_main_backupsettings');
    $setting_obj = $db->fetchAll($db_select)->toArray();
    if( empty($setting_obj) ) {
      $db->insert(array(
        'name' => 'sitebackup_admin_main_backupsettings',
        'module' => 'sitebackup',
        'label' => 'Take Backup',
        'plugin' => NULL,
        'params' => '{"route":"admin_default","module":"sitebackup","controller":"backupsettings"}',
        'menu' => 'sitebackup_admin_main',
        'submenu' => '',
        'custom' => 0,
        'order' => 4,
      ));
    }
    $db_select = $db->select()
      ->where('name =?', 'sitebackup_admin_main_restore');
    $setting_obj = $db->fetchAll($db_select)->toArray();
    if( empty($setting_obj) ) {
      $db->insert(array(
        'name' => 'sitebackup_admin_main_restore',
        'module' => 'sitebackup',
        'label' => 'Database Restore',
        'plugin' => NULL,
        'params' => '{"route":"admin_default","module":"sitebackup","controller":"manage","action":"upload"}',
        'menu' => 'sitebackup_admin_main',
        'submenu' => '',
        'custom' => 0,
        'order' => 5,
      ));
    }
    $db_select = $db->select()
      ->where('name =?', 'sitebackup_admin_main_manage');
    $setting_obj = $db->fetchAll($db_select)->toArray();
    if( empty($setting_obj) ) {
      $db->insert(array(
        'name' => 'sitebackup_admin_main_manage',
        'module' => 'sitebackup',
        'label' => 'Database Backups',
        'plugin' => NULL,
        'params' => '{"route":"admin_default","module":"sitebackup","controller":"manage"}',
        'menu' => 'sitebackup_admin_main',
        'submenu' => '',
        'custom' => 0,
        'order' => 6,
      ));
    }
    $db_select = $db->select()
      ->where('name =?', 'sitebackup_admin_main_codebackup');
    $setting_obj = $db->fetchAll($db_select)->toArray();
    if( empty($setting_obj) ) {
      $db->insert(array(
        'name' => 'sitebackup_admin_main_codebackup',
        'module' => 'sitebackup',
        'label' => 'Files Backups',
        'plugin' => NULL,
        'params' => '{"route":"admin_default","module":"sitebackup","controller":"codebackup"}',
        'menu' => 'sitebackup_admin_main',
        'submenu' => '',
        'custom' => 0,
        'order' => 7,
      ));
    }
  }

//Here we deleting the old backup files.
  function deletebackupfiles()
  {
    $autodeleteoption = Engine_Api::_()->getApi('settings', 'core')->sitebackup_deleteoptions;
    $autodeletelimit = Engine_Api::_()->getApi('settings', 'core')->sitebackup_deletelimit;
    if( $autodeleteoption == 1 ) {
      $table = Engine_Api::_()->getDbtable('backups', 'sitebackup');
      $select = $table->select()
        ->where('backup_codemethod = ?', 1)
        ->order('backup_time DESC');
      $row = $table->fetchAll($select);
      if( $row !== null ) {
        $i = 0;
        foreach( $row as $value ) {
          $i++;
          if( $i <= $autodeletelimit ) {
            continue;
          }
          $backup_id = $value->backup_id;
          $this->deletedatabasebackup($backup_id);
        }
      }
    }
    $autodeletecodeoption = Engine_Api::_()->getApi('settings', 'core')->sitebackup_deletecodeoptions;
    $autodeletecodelimit = Engine_Api::_()->getApi('settings', 'core')->sitebackup_deletecodelimit;
    if( $autodeletecodeoption == 1 ) {
      $table = Engine_Api::_()->getDbtable('backups', 'sitebackup');
      $select = $table->select()
        ->where('backup_codemethod = ?', 0)
        ->order('backup_time DESC');
      $row = $table->fetchAll($select);
      if( $row !== null ) {
        $i = 0;
        foreach( $row as $value ) {
          $i++;
          if( $i <= $autodeletecodelimit ) {
            continue;
          }
          $backup_id = $value->backup_id;
          $this->deletecodebackup($backup_id);
        }
      }
    }
  }

//Here we getting the database backup results for history page.
  public function getdatabasebackup()
  {
    $table = Engine_Api::_()->getDbtable('backups', 'sitebackup');
    $select = $table->select()
      ->where('backup_codemethod= ?', 1)
      ->orwhere('backup_codemethod= ?', 3)
      ->where('backup_filesize1= ?', '')
      ->where('backup_status= ?', 1)
      ->order('backup_time DESC')
      ->limit(1);
    $row = $table->fetchRow($select);
    return $row;
  }

//Here we getting the files backup results for history page.
  public function getcodebackup()
  {
    $table = Engine_Api::_()->getDbtable('backups', 'sitebackup');
    $select = $table->select()
      ->where('backup_codemethod= ?', 0)
      ->orwhere('backup_codemethod= ?', 2)
      ->where('backup_filesize1!= ?', '')
      ->where('backup_status= ?', 1)
      ->order('backup_time DESC')
      ->limit(1);
    $row = $table->fetchRow($select);
    return $row;
  }

  public function time_since($time)
  {
    $now = time();
    $now_day = date("j", $now);
    $now_month = date("n", $now);
    $now_year = date("Y", $now);
    $time_day = date("j", $time);
    $time_month = date("n", $time);
    $time_year = date("Y", $time);
    $time_since = "";
    $lang_var = 0;
    switch( TRUE ) {
      case ($now - $time < 60):
// RETURNS SECONDS
        $seconds = $now - $time;
        $time_since = $seconds;
        $lang_var = $time_since . ' second(s) ';
        break;
      case ($now - $time < 3600):
// RETURNS MINUTES
        $minutes = round(($now - $time) / 60);
        $time_since = $minutes;
        $lang_var = $time_since . ' minute(s) ';
        break;
      case ($now - $time < 86400):
// RETURNS HOURS
        $hours = round(($now - $time) / 3600);
        $time_since = $hours;
        $lang_var = $time_since . ' hour(s) ';
        break;
      case ($now - $time < 1209600):
// RETURNS DAYS
        $days = round(($now - $time) / 86400);
        $time_since = $days;
        $lang_var = $time_since . ' day(s) ';
        break;
      case (mktime(0, 0, 0, $now_month - 1, $now_day, $now_year) < mktime(0, 0, 0, $time_month, $time_day, $time_year)):
// RETURNS WEEKS
        $weeks = round(($now - $time) / 604800);
        $time_since = $weeks;
        $lang_var = $time_since . ' week(s) ';
        break;
      case (mktime(0, 0, 0, $now_month, $now_day, $now_year - 1) < mktime(0, 0, 0, $time_month, $time_day, $time_year)):
// RETURNS MONTHS
        if( $now_year == $time_year ) {
          $subtract = 0;
        } else {
          $subtract = 12;
        }
        $months = round($now_month - $time_month + $subtract);
        $time_since = $months;
        $lang_var = $time_since . ' month(s) ';
        break;
      default:
// RETURNS YEARS
        if( $now_month < $time_month ) {
          $subtract = 1;
        } elseif( $now_month == $time_month ) {
          if( $now_day < $time_day ) {
            $subtract = 1;
          } else {
            $subtract = 0;
          }
        } else {
          $subtract = 0;
        }
        $years = $now_year - $time_year - $subtract;
        $time_since = $years;
        $lang_var = $time_since . ' year(s) ';
        if( $years == 0 ) {
          $time_since = "";
          $lang_var = 0;
        }
        break;
    }
    return $lang_var;
  }

  function getDurration($time)
  {
    $hr = 0;
    $min = 0;
    $sec = 0;
    if( $time >= 3600 ) {
      $hr = $time / 3600;
      $time = $time % 3600;
    }
    if( $time >= 60 ) {
      $min = $time / 60;
      $time = $time % 60;
    }
    $sec = $time;
    $duration = '';
    if( !empty($hr) ) {
      if( (int) $hr > 1 )
        $duration .= (int) $hr . " Hours ";
      else
        $duration .= (int) $hr . " Hour ";
    }
    if( !empty($hr) || !empty($min) ) {
      if( (int) $min > 1 )
        $duration .= (int) $min . " Minutes ";
      else
        $duration .= (int) $min . " Minute ";
    }
    if( !empty($hr) || !empty($min) || !empty($sec) ) {
      if( (int) $sec > 1 )
        $duration .= (int) $sec . " Seconds ";
      else
        $duration .= (int) $sec . " Second ";
    }
    if( empty($hr) && empty($min) && empty($sec) )
      $duration .= "0.5 Second ";
    return $duration;
  }

  // For SE4.1.1
  public function canRunTask($module, $taskPlugin, $old_started_last)
  {
    $taskTable = Engine_Api::_()->getDbtable('tasks', 'core');
    $task = $taskTable->fetchRow(array('module = ?' => $module, 'plugin = ?' => $taskPlugin));
    if( $task ) {
      if( time() >= ($task->timeout + $old_started_last) ) {
        return 1;
      }
      return 0;
    }
    return 0;
  }

  //Get the files which are selected during backup
  public function getFiles($archiveSourcePath, $params = array(), $filetype)
  {
    $it = new DirectoryIterator($archiveSourcePath);
    foreach( $it as $file ) {
      if( $filetype == 'public' ) {
        if( $file->getPathname() == $params[0] || $file->getPathname() == $params[1] || $file->getPathname() == $params[2] || $file->getPathname() == $params[3] || $file->getPathname() == $params[4] ) {
          continue;
        } else {
          $pathname[] = $file->getPathname();
        }
      } else {
        if( $file->getPathname() == $params[0] || $file->getPathname() == $params[1] || $file->getPathname() == $params[2] || $file->getPathname() == $params[3] || $file->getPathname() == $params[4] || $file->getPathname() == $params[5] || $file->getPathname() == $params[6] || $file->getPathname() == $params[7] || $file->getPathname() == $params[8] || $file->getPathname() == $params[9] ) {
          continue;
        } else {
          $pathname[] = $file->getPathname();
        }
      }
    }
    $filesizeFile = "";
    foreach( $pathname as $value ) {
      $filesizeFile = round(@filesize($value) / 1048576, 3) . 'MB';
      if( $filesizeFile == '0.004MB' ) {
        $filesizeFile = "";
      } else {
        $filesizeFile = round(@filesize($value) / 1048576, 3) . 'MB';
      }
      $skipped_value = str_replace($archiveSourcePath, "", $value);
      if( empty($filesizeFile) ) {
        $resultsfiles[$skipped_value] = str_replace($archiveSourcePath, "", $skipped_value);
      } else {
        $resultsfiles[$skipped_value] = str_replace($archiveSourcePath, "", $skipped_value) . " " . "( " . $filesizeFile . " )";
      }
    }
    sort($resultsfiles);
    return $resultsfiles;
  }

  // Get the folder which are selected during backup
  public function getFolderSelected($resultsfiles)
  {
    foreach( $resultsfiles as $value ) {
      $explodarray = explode(" ", $value);
      $value = str_replace(".", "_SITEBACKUP_DOT_", $explodarray[0]);
      if( !empty($_POST[$value]) ) {
        if( $_POST[$value] == 1 ) {
          $folder_selected[] = $value;
        }
      }
    }
    return $folder_selected;
  }

  // Backup of code 
  public function codeBackup($params = array())
  {
    $start_codeBackup = time();
    $data = array();
    if( !empty($params['backup_options']) ) {
      $coderesult = Engine_Api::_()->getItem('sitebackup_destinations', $params['backup_options']);
      if( $coderesult->destination_mode == 2 ) {
        $backup_options = 2;
        $method = 'FTP';
        $destination_name = $coderesult->destinationname;
      } else if( $coderesult->destination_mode == 4 ) {
        $backup_options = 3;
        $method = 'Amazon S3';
        $destination_name = $coderesult->destinationname;
      } else if( $coderesult->destination_mode == 5 ) {
        $backup_options = 4;
        $method = 'Google Drive';
        $destination_name = $coderesult->destinationname;
      } else if( $coderesult->destination_mode == 6 ) {
        $backup_options = 5;
        $method = 'Dropbox';
        $destination_name = $coderesult->destinationname;
      } else if( $coderesult->destination_mode == 0 ) {
        $backup_options = 1;
        $method = 'Server Backup Directory & Download';
        $destination_name = $coderesult->destinationname;
      }
    } else {
      $backup_options = $params['backup_options'];
      $method = 'Download';
      $destination_name = 'Download to computer';
    }
    if( $params['backup_completecode'] == 0 ) {
      $data['only_code'] = 1;
    }
    $this->_outputPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $params['dir_name_temp'];
    //MAKE FILENAME
    $archiveFileName = $params['backupfilename'] . '_' . 'code_' . date("Y_m_d_H_i_s", time()) . '.tar';
    $filename1 = $archiveFileName;
    $archiveFileName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $archiveFileName);
    if( strtolower(substr($archiveFileName, -4)) != '.tar' ) {
      $archiveFileName .= '.tar';
    }
    $log_values = array_merge($params['log_values'], array(
      'type' => 'File',
      'method' => 'Manual',
      'destination_name' => $destination_name,
      'destination_method' => $method,
      'filename' => $archiveFileName,
      'start_time' => date('Y-m-d H:i:s'),
      'status' => 'Progress'
    ));
    $backupLog_Table = Engine_Api::_()->getDbtable('backuplogs', 'sitebackup');
    $code_backuplog_id = $backupLog_Table->setLog($log_values);
    $archiveFileName = $this->_outputPath . DIRECTORY_SEPARATOR . $archiveFileName;
    //SETUP PATHS
    $directory_name = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
    $archiveSourcePath = APPLICATION_PATH;
    $public = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public';
    $tmpPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $excludedirectory = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $directory_name;
    $excludedirectoryTemporary = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'temporary';
    $excludedErrorLog = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'error_log';
    //MAKE ARCHIVE
    $archive = new Archive_Tar($archiveFileName);
    //ADD FILES
    $path = $archiveSourcePath;
    $lastfiles = array();
    if( $params['backup_files_temp'] == 1 && $params['backup_rootfiles_temp'] == 1 && $params['backup_modulefiles_temp'] == 1 ) {
      $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
      foreach( $it as $file ) {
        $pathname = $file->getPathname();
        if( is_file($pathname) ) {
          if( substr($pathname, 0, strlen($tmpPath)) == $tmpPath || substr($pathname, 0, strlen($excludedirectory)) == $excludedirectory || substr($pathname, 0, strlen($excludedirectoryTemporary)) == $excludedirectoryTemporary || substr($pathname, 0, strlen($excludedErrorLog)) == $excludedErrorLog ) {
            continue;
          } else {
            $lastfiles[] = $pathname;
          }
        }
      }
    } else {

      // For Public directory 
      $publicArray = array();
      if( $params['backup_files_temp'] == 1 ) {
        $publicArray[] = $public = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public';
      } else {
        foreach( $params['skippedfiles'] as $skippedfile ) {
          $skippedfile = str_replace("_SITEBACKUP_DOT_", ".", $skippedfile);
          $skippedFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $skippedfile;
          $publicArray[] = $skippedFile;
        }
      }
      // For Root Directory
      $rootArray = array();
      if( $params['backup_rootfiles_temp'] == 1 ) {
        $rootArray[] = $path;
        $checkFlage = 1;
      } else {
        foreach( $params['skippedrootfiles'] as $skippedfile ) {
          $skippedfile = str_replace("_SITEBACKUP_DOT_", ".", $skippedfile);
          $skippedFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . $skippedfile;
          $rootArray[] = $skippedFile;
        }
      }

      $dirArray = array_merge($rootArray, $publicArray);
      // For Module directory 
      $moduleArray = array();
      if( !empty($params['skippedmodulefiles']) ) {
        if( $params['backup_modulefiles_temp'] == 1 ) {
          $moduleArray[] = $module = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules';
        } else {
          foreach( $params['skippedmodulefiles'] as $skippedfile ) {
            $skippedfile = str_replace("_SITEBACKUP_DOT_", ".", $skippedfile);
            $skippedFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $skippedfile;
            $moduleArray[] = $skippedFile;
          }
        }
        $dirArray = array_merge($dirArray, $moduleArray);
      }

      // Select the selected directory data and inserted into an array
      foreach( $dirArray as $value ) {
        // Check the selected option is file or directory
        if( is_file($value) ) {
          $lastfiles[] = $value;
        } else {
          $its = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($value), RecursiveIteratorIterator::SELF_FIRST);
          // In case of directory, select the directories files 
          foreach( $its as $files ) {
            // Get the name of each file path
            $pathName = $files->getPathname();

            if( is_file($pathName) ) {
              // Remove the files from backup which are defined above
              if( substr($pathName, 0, strlen($tmpPath)) == $tmpPath || substr($pathName, 0, strlen($excludedirectory)) == $excludedirectory || substr($pathName, 0, strlen($excludedirectoryTemporary)) == $excludedirectoryTemporary || substr($pathName, 0, strlen($excludedErrorLog)) == $excludedErrorLog ) {
                continue;
              } else {
                // Check the selected directory is project directory
                if( $value == $path ) {
                  // Remove the public directory from project directory
                  if( substr($pathName, 0, strlen($public)) == $public ) {
                    continue;
                  }
                  // Remove the modules directory from project if select all directories of root directory.
                  if( !empty($params['skippedmodulefiles']) ) {
                    $skippedmoduleFiless = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules';
                    if( substr($pathName, 0, strlen($skippedmoduleFiless)) == $skippedmoduleFiless ) {
                      continue;
                    }
                  }
                  $lastfiles[] = $pathName;
                } else {
                  // Remove the modules directory from project if select application directory of root directory
                  if( !empty($params['skippedmodulefiles']) && $value == APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' ) {
                    $skippedmoduleFiless = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules';
                    if( substr($pathName, 0, strlen($skippedmoduleFiless)) == $skippedmoduleFiless ) {
                      continue;
                    }
                  }
                  $lastfiles[] = $pathName;
                }
              }
            }
          }
        }
      }
    }
    $lastfiless = array_merge($lastfiles, $params['files_array_merge']);
    $addDirectory = '';
    $ret = $archive->addModify($lastfiless, $addDirectory, $path);
    if( PEAR::isError($ret) ) {
      throw new Engine_Exception($ret->getMessage());
    }
    $filesize_temp = @filesize($archiveFileName);
    $filesize1 = round($filesize_temp / 1048576, 3) . ' Mb';
    if( $backup_options == 0 ) {
      $backup_options1 = 'Download';
      $codemethod = 0;
      $data['backup_options'] = 1;
      $destination_name = 'Download to computer';
    } elseif( $backup_options == 1 ) {
      $backup_options1 = 'Server Backup Directory & Download';
      $codemethod = 0;
      $data['backup_options'] = 0;
      $destination_name = $destination_name;
    } elseif( $backup_options == 2 ) {
      $backup_options1 = 'FTP';
      $codemethod = 2;
      $data['backup_options'] = 0;
      $destination_name = $destination_name;
    } elseif( $backup_options == 3 ) {
      $backup_options1 = 'Amazon S3';
      $codemethod = 2;
      $data['backup_options'] = 0;
      $destination_name = $destination_name;
    } elseif( $backup_options == 4 ) {
      $backup_options1 = 'Google Drive';
      $codemethod = 2;
      $data['backup_options'] = 0;
      $destination_name = $destination_name;
    } else {
      $backup_options1 = 'Dropbox';
      $codemethod = 2;
      $data['backup_options'] = 0;
      $destination_name = $destination_name;
    }

    if( $params['backup_completecode'] == 0 ) {
      $data['backup_completecode'] = 0;
    }
    $backup_time = time();
    $data['code_filesize'] = $filesize1;
    $data['code_filename'] = $filename1;
    $date = date('r');
    $this->checkDatabaseConnection();
    $query = "INSERT IGNORE INTO `engine4_sitebackup_backups` ( `destination_id`, `backup_method`, `backup_filename`, `backup_time`, `backup_codemethod`, `backup_filesize1`, `backup_filename1`, `backup_timedescription`, `destination_name`, `backup_status`, `backup_auto`) VALUES ( '$coderesult->destinations_id', '$backup_options1', '', '$backup_time', $codemethod, '$filesize1', '$filename1','$date', '$destination_name', '1', '0')";
    $insert = mysql_query($query);
    if( !$insert ) {
      die('Can\'t use : ' . mysql_error());
    }
    $id = mysql_insert_id();
    $endtime = date('Y-m-d H:i:s');
    if ($backup_options == 0 || $backup_options == 1) {
      $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `backuplog_id` = '$code_backuplog_id', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`backuplog_id` = $code_backuplog_id LIMIT 1;");
      if (!$update) {
        die('Can\'t use : ' . mysql_error());
      } 
     }
    $config = Engine_Api::_()->getDbTable('destinations', 'sitebackup')->getConfigData($params['backup_options']);
    $tableObject = Engine_Api::_()->getDbTable('backups', 'sitebackup');
    if( $backup_options == 2 ) {
      // Store the backup on FTP
      set_time_limit(0);
      $data['download'] = 0;
      $ftp_obj = new Sitebackup_Service_FTP;
      if ($ftp_obj->file_transfer_protocol($coderesult, $archiveFileName, $filename1, $id)) {
        $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename1', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename1' LIMIT 1;");
        if (!$update) {
          die('Can\'t use : ' . mysql_error());
        } 
      }
    } elseif( $backup_options == 3 ) {
      // Store the backup on Amazon S3 Bucket  
      ini_set("memory_limit", "-1");
      set_time_limit(0);
      $data['download'] = 0;
      // $config = Engine_Api::_()->getDbTable('destinations', 'sitebackup')->getConfigData($params['backup_options']);
      $aws_filedata = array();
      $aws_link = $archiveFileName;

      $aws_service = new Sitebackup_Service_S3($config);
      // FULL PATH OF FILE FOR S3 BUCKET AND NEW FILE WILL BE SAVED WITH THIS NAME
      $s3Path = 'Code_Backup' . '/' . basename($archiveFileName);
      // DATA FOR THE FILE THAT WOULD BE CREATED
      $aws_filedata = file_get_contents($aws_link);
      // CREATE A FILE WITH $s3Path NAME AND $aws_filedata Data
      if( $aws_service->write($s3Path, $aws_filedata) ) {
        $tableObject->update(array("file_id" => 'Code_Backup'), array("backup_filename1 =?" => $filename1));
        $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename1', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename1' LIMIT 1;");
        if (!$update) {
          die('Can\'t use : ' . mysql_error());
        } 
        @unlink($aws_link);
      }
    } elseif( $backup_options == 4 ) {
      // Store the backup on Google Drive  
      ini_set("memory_limit", "-1");
      set_time_limit(0);
      $data['download'] = 0;

      $drive_service = new Sitebackup_Service_Drive($config);
      $refreshToken = $drive_service->generateRefreshToken($config['refresh_token']);
      if( isset($refreshToken) ) {
        if( $file_id = $drive_service->uploadFile($archiveFileName) ) {
          $tableObject->update(array("file_id" => $file_id), array("backup_filename1 =?" => $filename1));
          $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename1', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename1' LIMIT 1;");
        if (!$update) {
          die('Can\'t use : ' . mysql_error());
        } 
          @unlink($archiveFileName);
        }
      }
    } elseif( $backup_options == 5 ) {
      ini_set("memory_limit", "-1");
      set_time_limit(0);
      $data['download'] = 0;
      // UPLOAD THE DATABASE BACKUP FILE TO Dropbox
      $dropbox_service = new Sitebackup_Service_Dropbox($config);
      if( $file_id = $dropbox_service->uploadFile($archiveFileName) ) {
        $tableObject->update(array("file_id" => $file_id), array("backup_filename1 =?" => $filename1));
        $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename1', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename1' LIMIT 1;");
        if (!$update) {
          die('Can\'t use : ' . mysql_error());
        } 
        @unlink($archiveFileName);
      }
    }
    $select = $backupLog_Table->select()
              ->where('filename= ?', $filename1)
              ->limit(1);
    $row = $backupLog_Table->fetchRow($select);
    if ($row->status == 'Progress') {
      $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename1', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Fail' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename1' LIMIT 1;");
      if (!$update) {
        die('Can\'t use : ' . mysql_error());
      }
    }
    return $data;
  }

  //THIS FUNCTION RETURN THE TABLES OF THE DATABASE START.
  public function fetchtables()
  {
    $db = Engine_Db_Table::getDefaultAdapter();
    $export = Engine_Db_Export::factory($db);
    return array_values($export->getAdapter()->fetchAll('SHOW TABLES'));
  }

  //THIS FUNCTION USE FOR LOCKING THE DATABASE.
  public function lockDatabse()
  {
    $session = new Zend_Session_Namespace('backup');
    $selectedTables = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . 'selectedTables.txt';
    $file = fopen($selectedTables, "r");
    $content = fread($file, filesize($selectedTables));
    $table_selected = Zend_Json_Decoder::decode($content);
    $fileAdapterinfo['adapter'] = $session->fileadapter;
    $num_selected_table = count($table_selected['tables']);
    $initial_code = 0;
    if( $num_selected_table > 0 ) {
      $table_name = array();
      $dbinfo = Engine_Db_Table::getDefaultAdapter()->getConfig();
      $dbname = $dbinfo['dbname'];
      while( ($num_selected_table - 1 >= $initial_code ) ) {
        $table_name[] = '`' . $table_selected['tables'][$initial_code]['Tables_in_' . $dbname] . '`  WRITE';
        $initial_code++;
      }
      $db = Engine_Db_Table::getDefaultAdapter();
      $export = Engine_Db_Export::factory($db);
      $connection = $export->getAdapter()->getConnection();
      if( $fileAdapterinfo['adapter'] == 'mysql' ) {
        if( !($result = mysql_query('LOCK TABLES ' . implode(', ', $table_name))) ) {
          throw new Engine_Db_Export_Exception('Unable to execute lock query.');
        }
      } else {
        if( !($result = $connection->query('LOCK TABLES ' . implode(', ', $table_name))) ) {
          throw new Engine_Db_Export_Exception('Unable to execute lock query.');
        }
      }
    }
  }

  //THIS FUNCTION USE FOR UNLOCKING THE DATABASE.
  public function unlockDatabse()
  {
    $session = new Zend_Session_Namespace('backup');
    $fileAdapterinfo['adapter'] = $session->fileadapter;
    $db = Engine_Db_Table::getDefaultAdapter();
    $export = Engine_Db_Export::factory($db);
    $connection = $export->getAdapter()->getConnection();
    if( $fileAdapterinfo['adapter'] == 'mysql' ) {
      if( !($result = mysql_query('UNLOCK TABLES')) ) {
        throw new Engine_Db_Export_Exception('Unable to execute unlock query.');
      }
    } else {
      if( !($result = $connection->query('UNLOCK TABLES')) ) {
        throw new Engine_Db_Export_Exception('Unable to execute unlock query.');
      }
    }
  }

  // Check the database connection
  public function checkDatabaseConnection()
  {
    //GET SETTINGS
    $database_settings_file = APPLICATION_PATH . '/application/settings/database.php';
    if( file_exists($database_settings_file) ) {
      $dbinfo = include $database_settings_file;
    } else {
      $dbinfo = array();
    }
    $link = 0;
    if( !empty($dbinfo) ) {
      $dbname = $dbinfo['params']['dbname'];
      $host = $dbinfo['params']['host'];
      $username = $dbinfo['params']['username'];
      $password = $dbinfo['params']['password'];
      $link = mysql_connect($host, $username, $password, '', MYSQL_CLIENT_INTERACTIVE);
    }
    if( !$link ) {
      die('Not connected : ' . mysql_error());
    }
    //MAKE THE CURRENT DB
    $db_selected = mysql_select_db($dbname, $link);
    if( !$db_selected ) {
      die('Can\'t use : ' . mysql_error());
    }
  }

  // RETURN MYSQL DIRECTORY PATH
  public function getMysqlDirectoryPath()
  {
    $mysqlPath = '';
    $db = Engine_Db_Table::getDefaultAdapter();
    $row = $db->fetchRow("SHOW VARIABLES LIKE 'basedir'");
    if( $row && $row['Value'] ) {
      $mysqlPath = $row['Value'] . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR;
    }
    return $mysqlPath;
  }

}
