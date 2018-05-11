<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: FTP.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
class Sitebackup_Service_FTP
{

  protected $ftp;

  function file_transfer_protocol($coderesult, $archiveFileName, $filenametemp, $id)
  {
    //$dataArray = array();

    $this->ftp = $coderesult->toarray();
    if( !isset($this->ftp['conn']) ) {
      $this->backup_ftp_connect($id);
    } else {
      $this->backup_ftp_connected($id);
    }
    $value = $this->backup_file_transfer($archiveFileName, $filenametemp, $id);
    return $value;
  }

  function backup_file_transfer($file, $dest_filename, $id)
  {
    set_time_limit(0);
    if( $this->ftp['ftpportno'] == 21 ) {
      if( !(@ftp_chdir($this->ftp['conn'], $this->ftp['ftppath'] . "/" . $this->ftp['ftpdirectoryname'])) ) {
        @ftp_mkdir($this->ftp['conn'], $this->ftp['ftppath'] . "/" . $this->ftp['ftpdirectoryname']);
      }
    } else {
      $sftp = ssh2_sftp($this->ftp['conn']);
      if( !(ssh2_sftp_stat($sftp, $this->ftp['ftppath'] . "/" . $this->ftp['ftpdirectoryname'])) ) {
        ssh2_sftp_mkdir($sftp, $this->ftp['ftppath'] . "/" . $this->ftp['ftpdirectoryname']);
      }
    }
    $source = $file;
    $dest_complete_path = $this->ftp['ftppath'] . "/" . $this->ftp['ftpdirectoryname'] . "/" . $dest_filename;

    if( file_exists($file) ) {
      if( $this->ftp['ftpportno'] == 21 ) {
        $put_file = @ftp_put($this->ftp['conn'], $dest_complete_path, $source, FTP_BINARY);
        if( !$put_file ) {
          //turn passive mode on
          @ftp_pasv($this->ftp['conn'], true);
          $put_file = @ftp_put($this->ftp['conn'], $dest_complete_path, $source, FTP_BINARY);
        }
      } else {
        $put_file = ssh2_scp_send($this->ftp['conn'], $source, $dest_complete_path, 0744);
      }
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      if( !$put_file ) {
        $view->translate("FTP Error: Could not save the backup file - $source on the FTP server.");
        Engine_Api::_()->sitebackup()->checkDatabaseConnection();
        $update = mysql_query("UPDATE `engine4_sitebackup_backups` SET `backup_status` = 0 WHERE `engine4_sitebackup_backups`.`backup_id` = $id LIMIT 1;");
        return FALSE;
      } else {
        @unlink($source);
      }
      //EVERYTHING WORKED OK
      return TRUE;
    } else {
      $view->translate("FTP Error: Could not find the backup file on your site\'s server.");
      Engine_Api::_()->sitebackup()->checkDatabaseConnection();
      $update = mysql_query("UPDATE `engine4_sitebackup_backups` SET `backup_status` = 0 WHERE `engine4_sitebackup_backups`.`backup_id` = $id LIMIT 1;");
      return FALSE;
    }
  }

  function backup_ftp_connect($id)
  {
    set_time_limit(0);
    //CONNECT TO THE SERVER
    if( $this->ftp['ftpportno'] == 21 ) {
      $this->ftp['conn'] = ftp_connect($this->ftp['ftphost'], $this->ftp['ftpportno']);
    } else {
      $this->ftp['conn'] = ssh2_connect($this->ftp['ftphost'], $this->ftp['ftpportno']);
    }
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    if( !$this->ftp['conn'] ) {
      $view->translate('FTP Error: Could not connect to FTP server.');
      Engine_Api::_()->sitebackup()->checkDatabaseConnection();
      $update = mysql_query("UPDATE `engine4_sitebackup_backups` SET `backup_status` = 0 WHERE `engine4_sitebackup_backups`.`backup_id` = $id LIMIT 1;");
      return FALSE;
    }
    //LOGIN TO THE SERVER
    if( $this->ftp['ftpportno'] == 21 ) {
      $this->ftp['login'] = ftp_login($this->ftp['conn'], $this->ftp['ftpuser'], $this->ftp['ftppassword']);
    } else {
      $this->ftp['login'] = ssh2_auth_password($this->ftp['conn'], $this->ftp['ftpuser'], $this->ftp['ftppassword']);
    }
    if( !$this->ftp['login'] ) {
      $view->translate('FTP Error: Could not login as user FTP_USER on the FTP server.');
      Engine_Api::_()->sitebackup()->checkDatabaseConnection();
      $update = mysql_query("UPDATE `engine4_sitebackup_backups` SET `backup_status` = 0 WHERE `engine4_sitebackup_backups`.`backup_id` = $id LIMIT 1;");
      return FALSE;
    }
  }

  function backup_ftp_connected($id)
  {
    set_time_limit(0);
    if( $this->ftp['ftpportno'] == 21 ) {
      if( !@ftp_systype($this->ftp['conn']) ) {
        return $this->ftp['conn'] = $this->backup_ftp_connect($id);
      } else {
        return $this->ftp['conn'];
      }
    } else {
      if( !@ssh2_sftp($this->ftp['conn']) ) {
        return $this->ftp['conn'] = $this->backup_ftp_connect($id);
      } else {
        return $this->ftp['conn'];
      }
    }
  }

  function backup_file_delete($coderesult, $filename, $id)
  {
    $this->ftp = $coderesult->toarray();
    if( !isset($this->ftp['conn']) ) {
      $this->backup_ftp_connect($id);
    } else {
      $this->backup_ftp_connected($id);
    }
    $file = $this->ftp['ftppath'] . '/' . $this->ftp['ftpdirectoryname'] . '/' . $filename;
    $status = false;
    if( $this->ftp['ftpportno'] == 21 ) {
      $status = @ftp_delete($this->ftp['conn'], $file);
    } else {
      $sftp = ssh2_sftp($this->ftp['conn']);
      $status = ssh2_sftp_unlink($sftp, $file);
    }
    return $status;
  }

}
