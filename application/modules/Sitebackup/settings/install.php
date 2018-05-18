<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: install.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
require_once realpath(dirname(__FILE__)) . '/seaocore_install.php';

class Sitebackup_Installer extends Seaocore_License_Installer
{

  protected $_installConfig = array(
    'sku' => 'sitebackup',
  );

  function onInstall()
  {
    parent::onInstall();
    $sitebackup_set_time = time();
    $db = $this->getDb();
    $this->createDirectory();

    $db->query("INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
    ('sitebackup.set.time', $sitebackup_set_time );");

    $db->query("DELETE FROM `engine4_core_settings` WHERE `engine4_core_settings`.`name` = 'backup.navi.auth' LIMIT 1;");

    $db->query("UPDATE  `engine4_core_menuitems` SET  `label` =  'Global Settings', `order` =  '1' WHERE  `engine4_core_menuitems`.`name` ='sitebackup_admin_main_autobackupsettings';");
    $db->query("UPDATE `engine4_core_menuitems` SET  `label` =  'Restore Database' WHERE  `engine4_core_menuitems`.`name` ='sitebackup_admin_main_restore';");
    $db->query("UPDATE `engine4_core_menuitems` SET  `order` =  '2' WHERE  `engine4_core_menuitems`.`name` ='sitebackup_admin_main_destinationsettings';");
    $db->query("UPDATE `engine4_core_menuitems` SET  `params` =  '{\"route\":\"admin_default\",\"module\":\"sitebackup\",\"controller\":\"autobackupsettings\"}' WHERE  `engine4_core_menuitems`.`name` ='core_admin_main_plugins_sitebackup';");
  }

  public function createDirectory()
  {
    $db = $this->getDb();
    $rand = mt_rand(1, 10000);
    $current_dir_nametemp = $_SERVER['HTTP_HOST'];
    $current_dir_name1 = explode(".", $current_dir_nametemp);
    $currentdirname = $current_dir_name1[0];
    $current_dir_name = $current_dir_name1[0] . 'backup' . $rand;

    $select = new Zend_Db_Select($db);
    $select
      ->from('engine4_core_settings')
      ->where('name = ?', 'sitebackup.directoryname');
    $sitebackup_is_directoryname = $select->query()->fetchObject();

    $file_path = APPLICATION_PATH . '/public/' . $current_dir_name;
    if( !is_dir($file_path) && !mkdir($file_path, 0777, true) && empty($sitebackup_is_directoryname) ) {
      mkdir(dirname($file_path));
      chmod(dirname($file_path), 0777);
      touch($file_path);
      chmod($file_path, 0777);
    }

    $hashstring = md5('sitebackup');
    $hashstring1 = md5('backup');

    $newtext = wordwrap($hashstring, 2, "\n", true);
    $newtext1 = wordwrap($hashstring1, 2, "\n", true);
    $rest1 = substr($newtext, 0, 2);
    $rest2 = substr($newtext1, 0, 2);
    $rest3 = $rest2;

    $completehashstring = $rest1 . $currentdirname . $rest3;
    $htusername = $current_dir_nametemp;
    $htpassword = $completehashstring;

    $select = new Zend_Db_Select($db);
    $select
      ->from('engine4_sitebackup_backupauthentications', array('backupauthentication_id'));
    $backups_settings_check = $select->query()->fetchObject();
    if( empty($backups_settings_check) ) {
      $db->insert('engine4_sitebackup_backupauthentications', array(
        'htpassword_username' => $htusername,
        'htpassword_password' => $htpassword,
        'htpasswd_enable' => 1,
      ));
    }

    if( empty($sitebackup_is_directoryname) ) {
      $select = new Zend_Db_Select($db);
      $select
        ->from('engine4_sitebackup_destinations')
        ->where('destinationname = ?', 'Server Backup Directory')
        ->where('destination_mode = ?', 0)
        ->where('sitebackup_directoryname = ?', $current_dir_name);
      $backups_set_check = $select->query()->fetchObject();
      if( empty($backups_set_check) ) {
        $db->insert('engine4_sitebackup_destinations', array(
          'destinationname' => 'Server Backup Directory',
          'destination_mode' => 0,
          'sitebackup_directoryname' => $current_dir_name,));
      }
    }

    $htpasswd_text = $htusername . ':' . crypt($htpassword) . '';
    $backup_filepath = APPLICATION_PATH . '/public/' . $current_dir_name . '/.htpasswd';
    $fp = fopen($backup_filepath, 'w');
    fwrite($fp, $htpasswd_text);
    fclose($fp);
    $authtication_name_store = $current_dir_name;
    $authtication_path = APPLICATION_PATH . '/public/' . $current_dir_name . '/.htpasswd';
    $define_content = "\n";
    $format = 'AuthType Basic ' . $define_content . ' AuthName  ' . $authtication_name_store . ' ' . $define_content . ' AuthUserFile ' . $authtication_path . ' ' . $define_content . ' Require valid-user';
    $backupfilepath = APPLICATION_PATH . '/public/' . $current_dir_name . '/.htaccess';
    $fp = fopen($backupfilepath, 'w');
    fwrite($fp, $format);
    fclose($fp);

    $define_syntax = "\n\n";
    $password_check_format = 'Congratulations! Your backup directory is PASSWORD PROTECTED.' . $define_syntax . 'Backups provide insurance for your site. In the event that something on your site goes wrong, you can restore your site\'s content with the most recent backup file.' . $define_syntax . ' **********   Website Backup and Restore Plugin by SocialEngineAddOns (http://www.socialengineaddons.com)   **********';
    $password_check_path = APPLICATION_PATH . '/public/' . $current_dir_name . '/password_check.txt';
    $fp = fopen($password_check_path, 'w');
    fwrite($fp, $password_check_format);
    fclose($fp);

    $select = new Zend_Db_Select($db);
    $select
      ->from('engine4_core_settings')
      ->where('name = ?', 'core.mail.from');
    $license_email = $select->query()->fetchObject();

    $array = array('sitebackup.directoryname' => $current_dir_name, 'sitebackup.mailsender' => $license_email->value);
    foreach( $array as $key => $value ) {
      $select = new Zend_Db_Select($db);
      $select
        ->from('engine4_core_settings')
        ->where('name = ?', $key);
      $backups_settings = $select->query()->fetchObject();
      if( empty($backups_settings) ) {
        $db->insert('engine4_core_settings', array(
          'name' => $key,
          'value' => $value
        ));
      }
    }
  }

}

?>
