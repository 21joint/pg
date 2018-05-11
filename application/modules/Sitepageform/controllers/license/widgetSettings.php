<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    sitepageevent
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: WidgetSettings.php 6590 2010-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

//START LANGUAGE WORK
Engine_Api::_()->getApi('language', 'sitepage')->languageChanges();
//END LANGUAGE WORK
 
//GET DB
$db = Zend_Db_Table_Abstract::getDefaultAdapter();	

//CHECK THAT SITEPAGE PLUGIN IS ACTIVATED OR NOT
$select = new Zend_Db_Select($db);
	$select
  ->from('engine4_core_settings')
  ->where('name = ?', 'sitepage.is.active')
	->limit(1);
$sitepage_settings = $select->query()->fetchAll();
if(!empty($sitepage_settings)) {
	$sitepage_is_active = $sitepage_settings[0]['value'];
}
else {
	$sitepage_is_active = 0;
}

//CHECK THAT SITEPAGE PLUGIN IS INSTALLED OR NOT
$select = new Zend_Db_Select($db);
	$select
	  ->from('engine4_core_modules')
  ->where('name = ?', 'sitepage')
	->where('enabled = ?', 1);
$check_sitepage = $select->query()->fetchObject();
if(!empty($check_sitepage)  && !empty($sitepage_is_active)) {
	$select = new Zend_Db_Select($db);
	$select_page = $select
										 ->from('engine4_core_pages', 'page_id')
										 ->where('name = ?', 'sitepage_index_view')
										 ->limit(1);
  $page = $select_page->query()->fetchAll();
	if(!empty($page)) {
		$page_id = $page[0]['page_id'];
		
		//INSERTING THE FORM WIDGET IN SITEPAGE_ADMIN_CONTENT TABLE ALSO.
		Engine_Api::_()->getDbtable('admincontent', 'sitepage')->setAdminDefaultInfo('sitepageform.sitepage-viewform', $page_id, 'Form', 'true', '114');			
	 
		//INSERTING THE FORM WIDGET IN CORE_CONTENT TABLE ALSO.
		Engine_Api::_()->getApi('layoutcore', 'sitepage')->setContentDefaultInfo('sitepageform.sitepage-viewform', $page_id, 'Form', 'true', '114');
		
    //INSERTING THE FORM WIDGET IN SITEPAGE_CONTENT TABLE ALSO.
    $select = new Zend_Db_Select($db);								
		$contentpage_ids = $select->from('engine4_sitepage_contentpages', 'contentpage_id')->query()->fetchAll();
    foreach ($contentpage_ids as $contentpage_id) {
			if(!empty($contentpage_id)) {
        Engine_Api::_()->getDbtable('content', 'sitepage')->setDefaultInfo('sitepageform.sitepage-viewform', $contentpage_id['contentpage_id'], 'Form', 'true', '114');
			}
		}
	}	
	
	$select = new Zend_Db_Select($db);
	$select 
					->from('engine4_core_menuitems')
					->where('name = ?', 'sitepageform_admin_main_fields');
	$queary_info = $select->query()->fetchAll();
	if (empty($queary_info)) {
		$db->insert('engine4_core_menuitems', array(
				'name' => 'sitepageform_admin_main_fields',
				'module' => 'sitepageform',
				'label' => 'Form Questions',
				'plugin' => '',
				'params' => '{"route":"admin_default","module":"sitepageform","controller":"fields","action":"index"}',
				'menu' => 'sitepageform_admin_main',
				'submenu' => '',
				'order' => 3,
		));
	}
}  
	  
$select = new Zend_Db_Select($db);
$content = $select->from('engine4_sitepageform_pagequetions')->query()->fetchAll();		
if(empty($content)) {
	$select = new Zend_Db_Select($db);
	$sitepage_result = $select->from('engine4_sitepage_pages')->query()->fetchAll();
	if(!empty($sitepage_result)) {
		foreach ($sitepage_result as $key => $value) {
			$page_id = $value['page_id'];
			$values = $value ['title'];
			$db->insert('engine4_sitepageform_fields_options', array(
										'field_id' => 1,
										'label' => $values,
			));
		$option_id =  $db->lastInsertId('engine4_sitepageform_fields_options');
		$select = new Zend_Db_Select($db);
		$select_content = $select
														->from('engine4_sitepageform_pagequetions')
														->where('page_id = ?', $page_id)
														->where('option_id = ?', $option_id)
														->limit(1);
		$content = $select_content->query()->fetchAll();
		if( empty($content) ) {
				$db->insert('engine4_sitepageform_pagequetions', array(
					'page_id' => $page_id,
					'option_id' => $option_id,
				));
			}

		$db->insert('engine4_sitepageform_sitepageforms', array(
										'page_id' => $page_id,
										));
		}
	}
}
//CODE FOR INCREASE THE SIZE OF engine4_authorization_permissions's FIELD type
$type_array = $db->query('SHOW COLUMNS FROM engine4_authorization_permissions LIKE \'type\'')->fetch();
if(!empty($type_array)) {
	$varchar = $type_array['Type'];
	$length_varchar = explode('(', $varchar);
	$length = explode(')', $length_varchar[1]);
	$length_type = $length[0];
	if($length_type < 32) 	{
		$run_query  = $db->query('ALTER TABLE `engine4_authorization_permissions` CHANGE `type` `type` VARCHAR( 32 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL');
	}	
}

?>