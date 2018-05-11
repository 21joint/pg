<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: ProfileIntegration.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_Form_Admin_ProfileIntegration extends Engine_Form {

  public function init() {
  
  		$sitebusinessEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusiness');
		$sitereviewEnabled = Engine_Api::_()->getDbtable( 'modules' , 'core' )->isModuleEnabled('sitereview') ;
		$listEnabled = Engine_Api::_()->getDbtable( 'modules' , 'core' )->isModuleEnabled('list');
		
		if (!empty($sitebusinessEnabled) && (!empty($sitereviewEnabled) || !empty($listEnabled))) {
			$title = "Listing / Business Profile Linked Pages (selected content)";
		} elseif (!empty($sitereviewEnabled) || !empty($listEnabled)) {
			$title = "Listing Profile Linked Pages (selected content)";
		} elseif($sitebusinessEnabled) {
			$title = "Business Profile Linked Pages (selected content)";
		}

    $this->setMethod('post');
    $this->setTitle("$title")
					->setDescription('This widget displays a all the Pages to which the selected content module is added, as chosen by you from the settings of this widget. It is recommended to place this widget in the Tabbed Blocks area of the selected content module’s Profile page.');

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
				'value' => 50,
			));
			
			$this->addElement( 'MultiCheckbox' , 'showContent' , array (
				'label' => 'Select the information options that you want to be available in this block.',
				'multiOptions' => array("postedDate" => "Posted Date", "postedBy" => "Posted By", "viewCount" => "Views", "likeCount" => "Likes", "commentCount" => "Comments", "reviewCreate" => "Reviews"),
				//'value' => array("postedDate", "postedBy", "viewCount", "likeCount", "commentCount","reviewCreate")
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