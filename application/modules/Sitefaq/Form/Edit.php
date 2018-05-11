<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Edit.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Form_Edit extends Sitefaq_Form_Create
{
  public $_error = array();

	protected $_item;

  public function getItem()
  {
    return $this->_item;
  }

  public function setItem(Core_Model_Item_Abstract $item)
  {
    $this->_item = $item;
    return $this;
  }

  public function init()
  {  
    parent::init();

		$this
		->setTitle('Edit FAQ')
		->setDescription("Edit the information of your FAQ using the form below.")
		->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
  }

}