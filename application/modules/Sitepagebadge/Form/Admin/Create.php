<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Create.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_Form_Admin_Create extends Engine_Form {

  public function init() {
    $this->setTitle('Create New Badge')
            ->setDescription('Create a new Badge by filling the details below.');

    $this->addElement('Text', 'title', array(
        'label' => 'Title',
        'allowEmpty' => false,
        'required' => true,
    ));

    $this->addElement('Textarea', 'description', array(
        'label' => 'Description',
        'allowEmpty' => true,
        'required' => false,
    ));

    $this->addElement('File', 'badge_main', array(
        'label' => 'Image',
        'description' => 'Image width should not be more than 165px.',
        'allowEmpty' => false,
        'required' => true,
    ));
    $this->badge_main->addValidator('Extension', false, 'jpg,png,gif,jpeg,JPG,PNG,GIF,JPEG');

    $badge_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', null);
    if (!empty($badge_id)) {

      $this->addElement('File', 'badge_main', array(
          'label' => 'Image',
      ));
      $this->badge_main->addValidator('Extension', false, 'jpg,png,gif,jpeg,JPG,PNG,GIF,JPEG');
      $this->badge_main->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

      $sitepagebadge = Engine_Api::_()->getItem('sitepagebadge_badge', $badge_id);
      $description = Zend_Registry::get('Zend_Translate')->_('Click <a target="_blank" href="%s" class="smoothbox">here</a> to view the previous badge.');
      $photo_id = $sitepagebadge->badge_main_id;
      if (!empty($photo_id)) {
        $path = Engine_Api::_()->storage()->get($photo_id, '')->getPhotoUrl();
        $description = sprintf($description, $path);
        $this->addElement('dummy', 'photo_display', array(
            'label' => 'Previous Badge',
            'description' => $description,
            'ignore' => true,
        ));

        $this->getElement('photo_display')->getDecorator('Description')->setOptions(array('placement', 'APPEND', 'escape' => false));
      }
    } else {
      $this->addElement('File', 'badge_main', array(
          'label' => 'Image',
          'description' => 'Image width should not exceed from 165px.',
          'allowEmpty' => false,
          'required' => true,
      ));
      $this->badge_main->addValidator('Extension', false, 'jpg,png,gif,jpeg,JPG,PNG,GIF,JPEG');
      $this->badge_main->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    }

    $this->addElement('Button', 'submit', array(
        'label' => 'Create',
        'type' => 'submit',
    ));
  }

}
?>