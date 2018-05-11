<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: Autobackupsettings.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
class Sitebackup_Form_Admin_Autobackupsettings extends Engine_Form
{
  public function init()
  {
    //HERE WE SETTING THE TITLE AND DESCRIPTION OF DATABASE BACKUP
    $this
      ->setTitle('Global Settings')
      ->setDescription("Here, you can configure the settings for automatic backup of your site's database.");

    //HERE WE CREATE THE AUTOMATIC  DROPDOWN OF DATABASE BACKUP FILE OR SIMPLE FILE
    $this->addElement('Radio', 'sitebackup_backupoptions', array(
      'label' => 'Automatic Database Backup',
      'description' => "Do you want Automatic Database Backup to be activated on your site ? (If yes, then you will be able to choose more associated settings below. Note that automatic database backup is dependent on the activity going on your site. If your site is dormant, with no activity, then the backup for your site will not occur until there is at least one activity performed.)",
      'multiOptions' => array(
        0 => 'Yes',
        1 => 'No'
      ),
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_backupoptions,
      'onclick' => 'showautomaticblock(this.value);',
    ));

    //Here we attaching the filename with the backup genereted file.
    $this
      ->addElement('Text', 'sitebackup_autofilename', array(
        'label' => 'Automatic Database Backup Filename Prefix',
        'description' => 'Specify the prefix for the name of the automatic database backup file. (The prefix specified by you will be appended by the backup timestamp.)',
        'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_autofilename,
        'required' => true,
    ));

    $destination_prepared = array();
    $table = Engine_Api::_()->getDbtable('destinations', 'sitebackup');
    $select_query = $table->select()
      ->where('destination_mode <> ?', 2)
      ->orWhere('destination_mode = 2 and ftpadb= 1')
      ->order('destinations_id DESC');
    $results = $table->fetchAll($select_query);
    foreach( $results as $item ) {
      $destination_prepared[$item->destinations_id] = $item->destinationname;
    }
    //Destinations
    $this->addElement('Select', 'sitebackup_destinations', array(
      'label' => 'Destination for Automatic Database Backups',
      'description' => 'Select a destination for the automatic database backups of your site. (You may create a new destination from Destinations section.)',
      'multiOptions' => $destination_prepared,
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_destinations,
    ));

    //HERE WE CREATE THE AUTOMATIC  DROPDOWN FOR DATABASE BACKUP FILE
    $this->addElement('Select', 'sitebackup_dropdowntime', array(
      'label' => 'Time Interval between Database Backups',
      'description' => " Select the time intervals between successive database backups. A new backup process will start after this time interval from the previous backup. (Note: The time interval selected by you should be at least 5 times the time taken for a manual backup of your site. You can take a manual backup of your site to find the time duration. For example, if your site's manual backup takes 4 hours to complete, then the time interval chosen by you here must be equal or greater then than 20 hours.)",
      'multiOptions' => array(
        21600 => '6 Hours',
        43200 => '12 Hours',
        64800 => '18 Hours',
        86400 => '1 Day',
        172800 => '2 Days',
        259200 => '3 Days',
        608400 => '1 Week',
        1216800 => '2 Weeks',
        1825200 => '3 Weeks',
        2520000 => '1 Month',
        7560000 => '3 Months',
        15120000 => '6 Months',
        30240000 => '1 Year',
      ),
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_dropdowntime,
    ));

    //HERE WE SEND MAIL  FOR AUTOMATIC  BACKUP OPTION
    $this->addElement('Radio', 'sitebackup_mailoption', array(
      'label' => 'Email Notifications',
      'description' => "Do you want to enable email notifications to be sent on completion of automatic database backup?",
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No'
      ),
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption,
      'onclick' => 'showmailblock(this.value);',
    ));


    $this->addElement('Text', 'sitebackup_mailsender', array(
      'label' => 'Email Address',
      'description' => 'Specify the email address for receiving automatic backup notifications. (Separate multiple emails by commas.)',
      'value' => Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender,
    ));

    // Add submit button
    $this->addElement('Button', 'submit', array(
      'label' => 'Submit',
      'type' => 'submit',
      'value' => 'submit',
      'ignore' => true,
    ));
  }

}
