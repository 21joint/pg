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

class Sitereaction_Form_Admin_Collection_Search_Edit extends Sitereaction_Form_Admin_Collection_Search_Create {
  protected $_photoRequired = false;
  public function init() {
    parent::init();
    // Init form
    $this
      ->setTitle('Below, you can edit the search options')
      ->setDescription('Edit the information of search option.')
      ->setAttrib('class', 'global_form')
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;

  }

}
