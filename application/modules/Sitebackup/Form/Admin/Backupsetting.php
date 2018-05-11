<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: Backupsetting.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
class Sitebackup_Form_Admin_Backupsetting extends Engine_Form
{
  public function init()
  {
    $base = Zend_Controller_Front::getInstance()->getBaseUrl();

    $table = Engine_Api::_()->getDbtable('settings', 'sitebackup');
    $select = $table->select();
    $row = $table->fetchAll($select);

    if( !empty($row) ) {
      foreach( $row as $key => $value ) {
        $field_name[$value->name] = $value->value;
      }
      $backup_completecode = $field_name['backup_completecode'];
      $sitebackup_filename = $field_name['sitebackup_filename'];
      $sitebackupmaintenance_mode = $field_name['sitebackupmaintenance_mode'];
      $sitebackup_tablelock = $field_name['sitebackup_tablelock'];
      $backup_options = $field_name['backup_options'];
      $destination_id = $field_name['destination_id'];
      $backup_tables = $field_name['backup_tables'];
      $backup_optionsettings = $field_name['backup_optionsettings'];
      $backupfiles = $field_name['backup_files'];
      $backuprootfiles = $field_name['backup_rootfiles'];
      $backupmodulefiles = $field_name['backup_modulesfiles'];
      ;
    } else {
      $backup_completecode = 2;
      $sitebackup_filename = 'backup';
      $sitebackupmaintenance_mode = 0;
      $sitebackup_tablelock = 0;
      $backup_options = 0;
      $backup_tables = 1;
      $destination_id = 0;
      $backup_optionsettings = 0;
      $backupfiles = 1;
      $backuprootfiles = 1;
      $backupmodulefiles = 1;
    }
    // Get settings
    $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
    if( file_exists($global_settings_file) ) {
      $generalConfig = include $global_settings_file;
    } else {
      $generalConfig = array();
    }
    if( !empty($generalConfig['maintenance']['enabled']) && !empty($generalConfig['maintenance']['code']) ) {
      $maintenance_mode = 0;
    } else {
      $maintenance_mode = 1;
    }
    //GET DECORATORS
    $this->loadDefaultDecorators();

    //GET DESCRIPTION
    $description = sprintf(Zend_Registry::get('Zend_Translate')->_("Take the backup of your site's database and files after filling the form below.<br/>NOTE: Below are the settings for the Manual Backup. If you want to activate the \"Automatic Backup\" (which is for Database only), you can do so from the \"Global Settings\" section."));

    //HERE WE SETTING THE TITLE AND DESCRIPTION OF DATABASE BACKU
    $this
      ->setTitle('Take Backup')
      ->setDescription("$description");

    $this->getDecorator('Description')->setOption('escape', false);

    //HERE WE CREATE THE OPTIONS FOR BACKUP OF (DATABASE and CODE)
    $this
      ->addElement('Radio', 'backup_completecode', array(
        'label' => 'Backup Type',
        'description' => "Select the type of backup that you want to take of your site.",
        'multiOptions' => array(
          2 => 'Database and Files',
          1 => 'Database',
          0 => 'Files',
        ),
        'value' => $backup_completecode,
        'onclick' => 'showlocation(this.value)'
    ));

    //Here we attaching the filename with the backup genereted file.
    $this
      ->addElement('Text', 'sitebackup_filename', array(
        'label' => 'Backup Filename Prefix',
        'description' => 'Specify the prefix to be appended to the name of the backup file.',
        'value' => $sitebackup_filename,
        'required' => true,
    ));

    //HERE WE CREATE THE OPTIONS WHERE YOU WANT TO STORE THE BACKUP FILE
    if( !empty($maintenance_mode) )
      $this
        ->addElement('Radio', 'sitebackupmaintenance_mode', array(
          'label' => 'Activate Maintenance Mode',
          'description' => 'Do you want the site to be in Maintenance Mode during the backup process? (Note: This will prevent site visitors from accessing your website. You can customize the maintenance mode page by manually editing the file "/application/maintenance.html". The site will come back online once the backup process is complete.)',
          'required' => true,
          'multiOptions' => array(
            1 => 'Yes (Site is offline)',
            0 => 'No (Site is online)',
          ),
          'value' => $sitebackupmaintenance_mode
      ));

    //HERE WE CREATE THE OPTIONS WHERE YOU WANT TO STORE THE BACKUP FILE
    $this
      ->addElement('Radio', 'sitebackup_tablelock', array(
        'label' => 'Lock Tables during Database Backup',
        'description' => "Do you want to lock your site's database tables during the backup? (Note: This can help in keeping data consistency, but it will also make your site inaccessible during the backup process.)",
        'multiOptions' => array(
          1 => 'Yes',
          0 => 'No'
        ),
        'value' => $sitebackup_tablelock,
    ));

    $destination_ftp = array('0' => 'Download');
    $table = Engine_Api::_()->getDbtable('destinations', 'sitebackup');
    $select_query_ftp = $table->select()
      ->where('destination_mode = 2 and ftpmfile= 1')
      ->orwhere('destination_mode = 4 and ftpmfile = 1')
      ->orwhere('destination_mode = 5 and ftpmfile = 1')
      ->orwhere('destination_mode = 6 and ftpmfile = 1')
      ->orWhere('destination_mode = ?', 0)
      ->order('destinations_id DESC');
    $results_ftp = $table->fetchAll($select_query_ftp);

    foreach( $results_ftp as $itemftp ) {
      $destination_ftp[$itemftp->destinations_id] = $itemftp->destinationname;
    }

    $this
      ->addElement('Select', 'backup_options', array(
        'label' => 'Destination for Files Backup',
        'multiOptions' => $destination_ftp,
        'description' => 'Select a destination for the files backup of your site. (You can create a new destination from Destinations section.)',
        'value' => $backup_options
    ));

    $destination_prepared = array('0' => 'Download');
    $table = Engine_Api::_()->getDbtable('destinations', 'sitebackup');
    $select_query = $table->select()
      ->where('destination_mode <> ?', 2)
      ->orWhere('destination_mode = 2 and ftpmdb= 1')
      ->order('destinations_id DESC');
    $results = $table->fetchAll($select_query);
    foreach( $results as $item ) {
      $destination_prepared[$item->destinations_id] = $item->destinationname;
    }
    $this->addElement('Select', 'destination_id', array(
      'label' => 'Destination for Database Backup',
      'multiOptions' => $destination_prepared,
      'description' => 'Select a destination for the database backup of your site. (You can create a new destination from Destinations section.)',
      'value' => $destination_id
    ));

    //HERE WE CREATE THE OPTIONS WHERE YOU WANT TO STORE THE BACKUP FILE
    $this->addElement('Radio', 'backup_tables', array(
      'label' => 'Database Tables to Backup',
      'description' => "Select the database tables whose backup you want to take. (Note: We recommend you to select all the tables. Excluding some tables may lead to data inconsistency.)",
      'multiOptions' => array(
        1 => 'Select all tables.',
        0 => 'Include some tables.'
      ),
      'value' => $backup_tables,
      'onclick' => 'advanceOption(this.value)',
    ));

    //Add Dummy element for using the tables
    $this->addElement('Dummy', 'tables', array(
      'ignore' => true,
      'decorators' => array(array('ViewScript', array(
            'viewScript' => '_formCheckbox.tpl',
            'class' => 'form element'
          )))
    ));

    $this->addElement('Radio', 'backup_rootfiles', array(
      'label' => "File Directories to Backup in $base/",
      'description' => "Select the file directories in $base/ directory (this is root directory) whose backup you want to take.",
      'multiOptions' => array(
        1 => 'Select all directories.',
        0 => 'Include some directories.'
      ),
      'value' => $backuprootfiles,
      'onclick' => 'showrootfilesOption(this.value)',
    ));

    //Add Dummy element for using the tables
    $this->addElement('Dummy', 'rootfiles', array(
      'ignore' => true,
      'decorators' => array(array('ViewScript', array(
            'viewScript' => '_formRootFilesCheckbox.tpl',
            'class' => 'form element'
          )))
    ));

    $this->addElement('Radio', 'backup_files', array(
      'label' => "File Directories to Backup in $base/public/",
      'description' => "Select the file directories in /activity-feed/public/ directory whose backup you want to take.  (Note: This directory contains user uploaded files of your site like photos, music, etc in different sub-directories. Thus, these sub-directories can be very large in size. Thus, you may consider not to take backup of these heavy sub-directories.)",
      'multiOptions' => array(
        1 => 'Select all sub-directories.',
        0 => 'Include some sub-directories.'
      ),
      'value' => $backupfiles,
      'onclick' => 'showfilesOption(this.value)',
    ));

    //Add Dummy element for using the tables
    $this->addElement('Dummy', 'files', array(
      'ignore' => true,
      'decorators' => array(array('ViewScript', array(
            'viewScript' => '_formFilesCheckbox.tpl',
            'class' => 'form element'
          )))
    ));

    if( file_exists(APPLICATION_PATH . '/application/modules/Arcade/') ) {
      $this->addElement('Radio', 'backup_modulesfiles', array(
        'label' => "Files Directories to Backup in $base/application/modules/",
        'description' => "Select the files directories in $base/application/modules/ that you want to be backed-up.(Note : The '/oldplugin/application/modules/' directory contains the files of your site's modules/plugins like Blogs, Classified, Documents, Community Ads, Arcade, etc in their respective module directories. Some of these modules can be very large in size. You may thus consider not to back-up heavy modules. We recommend that if your site has Arcade module then this should be excluded from files backup of your site.)",
        'multiOptions' => array(
          1 => 'Select all directories.',
          0 => 'Include some directories.'
        ),
        'value' => $backupmodulefiles,
        'onclick' => 'showmodulesfilesOption(this.value)',
      ));

      //Add Dummy element for using the tables

      $this->addElement('Dummy', 'modulesfiles', array(
        'ignore' => true,
        'decorators' => array(array('ViewScript', array(
              'viewScript' => '_formmodulesfilesCheckbox.tpl',
              'class' => 'form element'
            )))
      ));
    }

    $this->addElement('Checkbox', 'backup_optionsettings', array(
      'label' => 'Save these settings for future use.',
      'multiOptions' => array(
        'yes' => 'Yes',
      ),
    ));

    // Add submit button
    $this->addElement('Button', 'submit', array(
      'label' => 'Backup Now!',
      'type' => 'submit',
      'value' => 'submit',
      'ignore' => true,
    ));
  }

}
