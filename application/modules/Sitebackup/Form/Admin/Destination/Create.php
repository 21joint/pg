<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: Create.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
class Sitebackup_Form_Admin_Destination_Create extends Engine_Form
{
  public function init()
  {
    //HERE WE SETTING THE TITLE AND DESCRIPTION OF DATABASE BACKUP
    $this
      ->setTitle('Create a new Backup Destination')
      ->setDescription('Create your new backup destination by filling the form below.')
      ->setAttribs(array('id' => 'sitebackup_create'));

    $destination_prepared = array('1' => 'Email', '2' => 'FTP Directory', '3' => 'MySQL Database', '4' => 'Amazon S3', '5' => 'Google Drive', '6' => 'Dropbox');


    // category field
    $this->addElement('Select', 'destination_mode', array(
      'label' => 'Destination Type',
      'description' => 'This destination can be used only for database backups (manual and automatic).',
      'multiOptions' => $destination_prepared,
      'onchange' => 'javascript:fetchDestinationSettings(this.value);'
    ));

    $this->addElement('Text', 'destinationname', array(
      'label' => 'Destination Name *',
      'description' =>'Enter the destination for the selected type. This destination name will list in this plugin only for your convenience.',
    ));

    $this->addElement('Text', 'email', array(
      'label' => 'Email *',
      'description' => 'Specify the email address to which the database backup files should be sent as attachment. The file size of the database backups as email attachment should be less than or equal to 20 MB.  Also, please ensure that the email server and email account can handle large file attachments.'
    ));

    $this->addElement('Text', 'ftphost', array(
      'label' => 'Host *',
    ));

    $this->addElement('Text', 'ftpportno', array(
      'label' => 'Port Number *',
      'value' => 21,
    ));

    $this->addElement('Text', 'ftppath', array(
      'label' => 'Path *',
      'description' => 'Directory path on your FTP server.'
    ));

    $this->addElement('Text', 'ftpdirectoryname', array(
      'label' => 'Backup Directory *',
      'description' => 'If this directory does not already exist on the FTP server at the above path, then it will be created. Please ensure that this location has enough space for storing backups.',
    ));

    $this->addElement('Text', 'ftpuser', array(
      'label' => 'Username *',
      'description' => 'Please ensure that this user has write permission for the above backup directory.'
    ));

    $this->addElement('Text', 'ftppassword', array(
      'label' => 'Password ',
    ));

    $this->addElement('Dummy', 'ftpmsg', array(
      'label' => 'Backup Types',
      'description' => 'Select the backup types for which you want to use with this destination.',
    ));

    // ftp Manual database
    $this->addElement('Checkbox', 'ftpmdb', array(
      'label' => 'Manual Database Backup',
      'value' => 1,
    ));
    // ftp Manual file
    $this->addElement('Checkbox', 'ftpmfile', array(
      'label' => 'Manual Files Backup',
      'value' => 1,
    ));
    // ftp auto batabase
    $this->addElement('Checkbox', 'ftpadb', array(
      'label' => 'Automatic Database Backup',
      'value' => 0,
    ));

    $this->addElement('Text', 'dbhost', array(
      'label' => 'Host *',
      'value' => 'localhost',
    ));

    $this->addElement('Text', 'dbname', array(
      'label' => 'Database Name *',
      'description' => 'Specify the name of the database. [Note: This database must already exist otherwise connection to database will not form for the backup process.]'
    ));

    $this->addElement('Text', 'dbuser', array(
      'label' => 'Username *',
      'description' => 'Enter the username which has write permission to the database.'
    ));

    $this->addElement('Text', 'dbpassword', array(
      'label' => 'Password ',
    ));

    // Element: accessKey
    $this->addElement('Text', 'accesskey', array(
      'label' => 'Access Key *',
      'filters' => array(
        'StringTrim',
      ),
    ));

    // Element: secretKey
    $this->addElement('Text', 'secretkey', array(
      'label' => 'Secret Key *',
      'filters' => array(
        'StringTrim',
      ),
    ));

    // Element: region
    $this->addElement('Select', 'region', array(
      'label' => 'Region *',
      'multiOptions' => array(
        'us-west-1' => 'US West (N. California)',
        'us-west-2' => 'US West (Oregon)',
        'us-east-1' => 'US East (N. Virginia)',
        'eu-west-1' => 'EU (Ireland)',
        'ap-southeast-1' => 'Asia Pacific (Singapore)',
        'ap-southeast-2' => 'Asia Pacific (Sydney)',
        'ap-northeast-1' => 'Asia Pacific (Tokyo)',
        'sa-east-1' => 'South America (São Paulo)'
      ),
    ));

    // Element: bucket
    $this->addElement('Text', 'bucket', array(
      'label' => 'Bucket *',
      'description' => 'If the bucket does not exist, we will attempt to ' .
      'create it. Please note the following restrictions on bucket names:<br />' .
      '-Must start and end with a number or letter<br />' .
      '-Must only contain lowercase letters, numbers, and dashes [a-z0-9-]<br />' .
      '-Must be between 3 and 255 characters long',
      'validators' => array(
        array('StringLength', true, array(3, 255)),
        array('Regex', true, array('/^[a-z0-9][a-z0-9-]+[a-z0-9]$/')),
      ),
    ));
    $this->getElement('bucket')->getDecorator('description')->setOption('escape', false);

    // Element: Client id
    $this->addElement('Text', 'clientid', array(
      'label' => 'Client Id *',
      'description' => 'Please enter your Client ID. To know how to generate and configure it, please <a href="admin/sitebackup/manage/destination-faq/show/4#faq_4" onclick target="_blank">click here</a>.',
    ));
    $this->clientid->addDecorator('Description', array('placement' => 'PREPEND', 'class' => 'description', 'escape' => false));

    // Element: Client secret
    $this->addElement('Text', 'clientsecret', array(
      'label' => 'Client Secret Key *',
      'description' => 'Please enter your Client Secret Key. To know how to generate and configure it, please <a href="admin/sitebackup/manage/destination-faq/show/4#faq_4" target="_blank">click here</a>.',
    ));
    $this->clientsecret->addDecorator('Description', array('placement' => 'PREPEND', 'class' => 'description', 'escape' => false));

    //Element: App Key
    $this->addElement('Text', 'appkey', array(
      'label' => 'App Key *',
      'description' => 'Please enter your App Key. To know how to generate and configure it, please <a href="admin/sitebackup/manage/destination-faq/show/6#faq_6" target="_blank">click here</a>.',
    ));
    $this->appkey->addDecorator('Description', array('placement' => 'PREPEND', 'class' => 'description', 'escape' => false));

    //Element: App Secret
    $this->addElement('Text', 'appsecret', array(
      'label' => 'App Secret *',
      'description' => 'Please enter your App Secret. To know how to generate and configure it, please <a href="admin/sitebackup/manage/destination-faq/show/6#faq_6" onclick target="_blank">click here</a>.',
    ));
    $this->appsecret->addDecorator('Description', array('placement' => 'PREPEND', 'class' => 'description', 'escape' => false));

    $this->addElement('Button', 'submit', array(
      'label' => 'Create',
      'type' => 'submit',
    ));
  }

}
