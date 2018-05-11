<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: Directorysettings.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
class Sitebackup_Form_Admin_Directorysettings extends Engine_Form
{
  public function init()
  {
    $table = Engine_Api::_()->getDbtable('backupauthentications', 'sitebackup');
    $select = $table->select()
      ->where('backupauthentication_id = ?', 1)
      ->limit();

    $row = $table->fetchRow($select);

    $htusername = $row['htpassword_username'];
    $htpassword = $row['htpassword_password'];
    $backup_enable = $row['htpasswd_enable'];

    //HERE WE SETTING THE TITLE AND DESCRIPTION OF DATABASE BACKUP
    $this
      ->setTitle('Backup Directory on your Server')
      ->setDescription('The backup directory on your server will be used for both database and file backups. Before starting the backup process, please ensure that this location has enough space to store the backups. Below, you can also set password protection for your backup directory.');

    //Add Dummy element for using the tables
    $this->addElement('Text', 'directory_path', array(
      'label' => 'Backup Directory Path',
      'value' => APPLICATION_PATH . '/public/' . Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname,
      'readonly' => true,
    ));

    $this->addElement('Text', 'sitebackup_directoryname', array(
      'label' => 'Backup Directory Name',
      'description' => 'Enter the name for the backup directory on your server. [Note: Name must contain both alphabets and numbers.]',
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname,
      'required' => true,
    ));
    //HERE WE CREATE THE AUTOMATIC  DROPDOWN OF DATABASE BACKUP FILE OR SIMPLE FILE
    $this->addElement('Radio', 'backup_enable', array(
      'label' => 'Enable Password Protection',
      'description' => "Do you want to enable password protection for the backup directory?",
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No',
      ),
      'value' => $backup_enable,
      'onclick' => 'showhide(this.value);',
    ));

    $this->addElement('Text', 'htusername', array(
      'label' => 'Username',
      'description' => "",
      'value' => $htusername,
      'ignore' => true,
      //'required' =>true,
    ));

    $this->addElement('Text', 'htpassword', array(
      'label' => 'Password',
      'description' => "",
      'value' => $htpassword,
      'ignore' => true,
      //'required' =>true,
    ));

    //HERE WE CREATE THE AUTOMATIC  DROPDOWN OF DELETE FILE
    $this->addElement('Radio', 'sitebackup_deleteoptions', array(
      'label' => 'Automatic Delete',
      'description' => "Please choose options you want to automatic delete backup files.",
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No'
      ),
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_deleteoptions,
      'onclick' => 'showdeleteblock(this.value);',
    ));

    //HERE WE CREATE THE AUTOMATIC  DROPDOWN OF DELETE FILE
    $this->addElement('Radio', 'sitebackup_deleteoptions', array(
      'label' => 'Automatic Delete',
      'description' => "Please choose options you want to automatic delete backup files.",
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No'
      ),
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_deleteoptions,
      'onclick' => 'showdeleteblock(this.value);',
    ));

    $this->addElement('Text', 'sitebackup_deletelimit', array(
      'label' => 'Maximum Database Backups saved on Server',
      'description' => "Specify the maximum number of database backup files to be kept in your server's backup directory before deleting old files. (The recent backups will be kept, and the older ones will be deleted. This prevents unnecessary space utilization on your server. The minimum value for this is 1.)",
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_deletelimit,
      'required' => true,
    ));
    //HERE WE CREATE THE AUTOMATIC  DROPDOWN OF DELETE FILE
    $this->addElement('Radio', 'sitebackup_deleteoptions', array(
      'label' => 'Automatically Delete Database Backups',
      'description' => "Do you want database backup files to be automatically deleted from the backup directory on your server ? (Choosing yes will allow you to specify the maximum number of database backups that should be saved on your server. The recent backups will be kept, and the older ones will be deleted. This prevents unnecessary space utilization on your server.)",
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No'
      ),
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_deleteoptions,
      'onclick' => 'showdeleteblock(this.value);',
    ));

    //HERE WE CREATE THE AUTOMATIC  DROPDOWN OF DELETE FILE
    $this->addElement('Radio', 'sitebackup_deletecodeoptions', array(
      'label' => "Automatically Delete Site's Files' Backups",
      'description' => "Do you want your site's file backups to be automatically deleted from the backup directory on your server ? (Choosing yes will allow you to specify the maximum number of file backups that should be saved on your server. The recent backups will be kept, and the older ones will be deleted. This prevents unnecessary space utilization on your server.)",
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No'
      ),
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_deletecodeoptions,
      'onclick' => 'showdeletecodeblock(this.value);',
    ));

    $this->addElement('Text', 'sitebackup_deletecodelimit', array(
      'label' => 'Maximum File Backups saved on Server',
      'description' => "Specify the maximum number of site's file backups to be kept in your server's backup directory before deleting old files. (The recent backups will be kept, and the older ones will be deleted. This prevents unnecessary space utilization on your server. The minimum value for this is 1.)",
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_deletecodelimit,
      'required' => true,
    ));

    // Add submit button
    $this->addElement('Button', 'submit', array(
      'label' => 'Save',
      'type' => 'submit',
      'value' => 'submit',
      'ignore' => true
    ));
  }

}
