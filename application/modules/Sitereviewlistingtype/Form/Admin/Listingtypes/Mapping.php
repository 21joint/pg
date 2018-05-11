<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereviewlistingtype
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Mapping.php 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereviewlistingtype_Form_Admin_Listingtypes_Mapping extends Engine_Form
{
  public function init()
  {
		$this
		->setTitle('Delete Listing Type')
		->setDescription('Select the Listing Type below to move listings from this listing type to another listing type. If you do not select any option below then all the listings belonging to this listing type will be deleted.');

    $this->addElement('Button', 'submit', array(
      'label' => 'Delete',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array(
        'ViewHelper',
      ),
    ));
    
    $listingTypes1 = array();
    
    //GET LISTINGTYPE ID 
    $listingtype_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('listingtype_id', null);

    $listingTypes = Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->getListingTypesArray($listingtype_id, 1);
    
    $this->addElement('Select', 'new_listingtype_id', array(
        'label' => 'Listing Type',
        'multiOptions' => $listingTypes,
        'value' => 0
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
			'onclick' => 'javascript:closeSmoothbox()',
      'link' => true,
      'prependText' => ' or ',
      'decorators' => array(
        'ViewHelper',
      ),
    ));

    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
      'decorators' => array(
        'FormElements',
        'DivDivDivWrapper',
      ),
    ));
	}
}