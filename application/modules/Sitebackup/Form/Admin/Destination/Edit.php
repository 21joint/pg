<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: Edit.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
class Sitebackup_Form_Admin_Destination_Edit extends Sitebackup_Form_Admin_Destination_Create
{

  protected $_destination_mode;
  protected $_name;

  public function getDestination()
  {
    return $this->_destination_mode;
  }

  public function setDestination($destination_mode)
  {
    $this->_destination_mode = $destination_mode;
    return $this;
  }

  public function getName()
  {
    return $this->_name;
  }

  public function setName($name)
  {
    $this->_name = $name;
    return $this;
  }

  public function init()
  {
    parent::init();
    $destination_mode = $this->getDestination();

    if( !empty($destination_mode) ) {

      switch( $destination_mode ) {
        case 1:
          $destination_mode_name = 'Email';
          $description = 'Note: It can be used for both manual and automatic database backups.';
          break;
        case 2:
          $destination_mode_name = 'FTP Directory';
          $description = 'Note: It can be used for both manual and automatic database backups and manual for file backups.';
          break;
        case 3:
          $destination_mode_name = 'MySQL Database';
          $description = 'Note: It can be used for both manual and automatic database backups.';
          break;
        case 4:
          $destination_mode_name = 'Amazon S3';
          $description = 'Note: It can be used for both manual and automatic database backups and manual for file backups.';
          break;
        case 5:
          $destination_mode_name = 'Google Drive';
          $description = 'Note: It can be used for both manual and automatic database backups.';
          break;
        case 6:
          $destination_mode_name = 'Dropbox';
          $description = 'Note: It can be used for both manual and automatic database backups.';
          break;
      }
    }

    $this->setTitle('Edit Backup Destination: ' . $this->getName());
    $form_description = sprintf(Zend_Registry::get('Zend_Translate')->_("Here, you can edit the details of the backup destination.<br/>[Note: If you are using this destination for automatic backup, then changes saved here will also effect the automatic backup process. Please check the backup settings after making changes here.]"));
    $this->setDescription("$form_description");
    $this->loadDefaultDecorators();
    $this->getDecorator('Description')->setOption('escape', false);

    $this->addElement('Dummy', 'directory_path', array(
      'label' => 'Destination Type',
      'description' => $destination_mode_name . '    [' . $description . ']',
      'order' => 0
    ));

    $this->destination_mode->setValue($destination_mode);
    $this->removeElement('destination_mode');

    $this->addElement('Hidden', 'destination_mode', array(
      'label' => 'Destination Type',
      'order' => 1
    ));

    $this->submit->setLabel('Save Changes');
  }

}
