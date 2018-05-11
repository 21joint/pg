<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Edit.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_Form_Admin_Collection_Edit extends Sitereaction_Form_Admin_Collection_Create {
  public function init() {

    parent::init();
    // Init form
    $this
      ->setTitle('Edit Collection')
      ->setDescription('Below, you can edit the sticker collection information.')
      ->setAttrib('name', 'admin_edit_collection')
      ->setAttrib('enctype', 'multipart/form-data')
      ->setAttrib('class', 'global_form')
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;

    // Init file
    $this->removeElement('file');
    $this->submit->setLabel('Save Changes');
  }

  /*
   * @overwrite
   */
  public function saveValues() {
    
  }

}
