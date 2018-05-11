<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Integration.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_Form_Admin_Integration extends Engine_Form {

  public function init() {
		
		$sitebusinessEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusiness');
		$sitereviewEnabled = Engine_Api::_()->getDbtable( 'modules' , 'core' )->isModuleEnabled('sitereview') ;
		$sitereviewlistingtypeEnabled = Engine_Api::_()->getDbtable( 'modules' , 'core' )->isModuleEnabled('sitereviewlistingtype') ;
		$listEnabled = Engine_Api::_()->getDbtable( 'modules' , 'core' )->isModuleEnabled('list');
		
		if (!empty($sitebusinessEnabled) && (!empty($sitereviewEnabled) || !empty($listEnabled))) {
			$Description = "This widget displays listings / businesses added from a selected content module, which you can choose from the settings of this widget, to the Page currently being viewed. You can place this widget multiple times on Page Profile page with different content modules chosen for each placement. It is recommended to place this widget in the Tabbed Blocks area of the Page Profile page.";
		} elseif (!empty($sitereviewEnabled) || !empty($listEnabled)) {
			$Description = "This widget displays listings added from a selected content module, which you can choose from the settings of this widget, to the Page currently being viewed. You can place this widget multiple times on Page Profile page with different content modules chosen for each placement. It is recommended to place this widget in the Tabbed Blocks area of the Page Profile page.";
		} elseif($sitebusinessEnabled) {
			$Description = "This widget displays businesses added from a selected content module, which you can choose from the settings of this widget, to the Page currently being viewed. You can place this widget multiple times on Page Profile page with different content modules chosen for each placement. It is recommended to place this widget in the Tabbed Blocks area of the Page Profile page.";
		}
 
    $this->setMethod('post');
    $this->setTitle('Page Profile Listings (selected content)')
					->setDescription("$Description");
					
		$modNameValues = array();
		
    $mixSettingsResults = Engine_Api::_()->getDbtable('mixsettings', 'sitepageintegration')->getIntegrationItems();

		foreach($mixSettingsResults as $modNameValue) {
		
			if(empty($modNameValue["listingtype_id"])) {
				$modNameValue["listingtype_id"] = 0;
			}

			$modNameValues[$modNameValue["resource_type"] . '_' .  $modNameValue["listingtype_id"]] = $modNameValue["item_title"];
		}
		
		if  (isset($modNameValue))
			$modNameValues = array_merge(array(""), $modNameValues);

		if (!empty($modNameValues)) {
			$this->addElement('Select', 'resource_type', array(
			'label' => 'Select the content',
			'multiOptions' => $modNameValues
			));
			
			$this->addElement('Text', 'title_truncation', array(
					'label' => 'Title Truncation Limit',
					'value' => 70,
			));
			
			$this->addElement( 'Radio' , 'show_posted_date' , array (
				'label' => 'Do you want “Posted By” and “Posted Date” information options to be available in this block?',
				'multiOptions' => array (
					1 => 'Yes' ,
					0 => 'No'
				) ,
				'value' => 0,
			)) ;
		}
		else {
		  $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
			$URL = $view->baseUrl() . "/admin/sitepageintegration/settings";
      $click = '<a href="' . $URL . '" target="_blank">Click here</a>';
	    $description = sprintf("Note: You have not enabled adding of listings / businesses from any content module to Directory Items / Pages. To enable adding of listings / businesses, please %s",  $click);
			$description = "<div class='tip'><span>" . $description . "</span></div>" ;
			$this->addElement( 'Dummy' , 'resource_type' , array (
			'description' => $description ,
			) ) ;
			$this->resource_type->addDecorator( 'Description' , array ( 'placement' => Zend_Form_Decorator_Abstract::PREPEND , 'escape' => false ) ) ;
		}
  }
}