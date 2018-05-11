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
		
	  //INSERT THE BADGE WIDGET IN CORE CONTENT TABLE
		$select = new Zend_Db_Select($db);
    $select_content = $select
                 ->from('engine4_core_content')
                 ->where('page_id = ?', $page_id)
                 ->where('type = ?', 'widget')
                 ->where('name = ?', 'sitepagebadge.badge-sitepagebadge')
                 ->limit(1);
    $content = $select_content->query()->fetchAll();
    if(empty($content)) {
      $select = new Zend_Db_Select($db);
      $select_container = $select
                   ->from('engine4_core_content', 'content_id')
                   ->where('page_id = ?', $page_id)
                   ->where('type = ?', 'container')
                   ->limit(1);
      $container = $select_container->query()->fetchAll();
      if(!empty($container)) {
	      $container_id = $container[0]['content_id'];
	      $select = new Zend_Db_Select($db);
	      $select_left = $select
	                   ->from('engine4_core_content')
	                   ->where('parent_content_id = ?', $container_id)
	                   ->where('type = ?', 'container')
                     ->where('page_id = ?', $page_id)
	                   ->where('name in (?)', array('left', 'right'))
	                   ->limit(1);
	      $left = $select_left->query()->fetchAll();
	      if(!empty($left)) {
	        $left_id = $left[0]['content_id'];
	        $db->insert('engine4_core_content', array(
		        'page_id' => $page_id,
		        'type' => 'widget',
		        'name' => 'sitepagebadge.badge-sitepagebadge',
		        'parent_content_id' => $left_id,
		        'order' => 16,
		        'params' => '{"title":"Badge","titleCount":""}',
		       ));
	       }
      }
    }			
		
	  //INSERT THE BADGE WIDGET IN CORE CONTENT TABLE
		$select = new Zend_Db_Select($db);
    $select_content = $select
                 ->from('engine4_sitepage_admincontent')
                 ->where('page_id = ?', $page_id)
                 ->where('type = ?', 'widget')
                 ->where('name = ?', 'sitepagebadge.badge-sitepagebadge')
                 ->limit(1);
    $content = $select_content->query()->fetchAll();
    if(empty($content)) {
      $select = new Zend_Db_Select($db);
      $select_container = $select
                   ->from('engine4_sitepage_admincontent', 'admincontent_id')
                   ->where('page_id = ?', $page_id)
                   ->where('type = ?', 'container')
                   ->limit(1);
      $container = $select_container->query()->fetchAll();
      if(!empty($container)) {
	      $container_id = $container[0]['admincontent_id'];
	      $select = new Zend_Db_Select($db);
	      $select_left = $select
	                   ->from('engine4_sitepage_admincontent')
	                   ->where('parent_content_id = ?', $container_id)
	                   ->where('type = ?', 'container')
                     ->where('page_id = ?', $page_id)
	                   ->where('name in (?)', array('left', 'right'))
	                   ->limit(1);
	      $left = $select_left->query()->fetchAll();
	      if(!empty($left)) {
	        $left_id = $left[0]['admincontent_id'];
	        $db->insert('engine4_sitepage_admincontent', array(
		        'page_id' => $page_id,
		        'type' => 'widget',
		        'name' => 'sitepagebadge.badge-sitepagebadge',
		        'parent_content_id' => $left_id,
		        'order' => 16,
		        'params' => '{"title":"Badge","titleCount":""}',
		       ));
	       }
      }
    }			
				
    //INSERTING THE FORM WIDGET IN SITEPAGE_CONTENT TABLE ALSO.
    $select = new Zend_Db_Select($db);								
		$contentpage_ids = $select->from('engine4_sitepage_contentpages', 'contentpage_id')->query()->fetchAll();
		
    foreach ($contentpage_ids as $contentpage_id) {
			if(!empty($contentpage_id)) {		
				$select = new Zend_Db_Select($db);
		    $select_content = $select
		                 ->from('engine4_sitepage_content')
		                 ->where('contentpage_id = ?', $contentpage_id['contentpage_id'])
		                 ->where('type = ?', 'widget')
		                 ->where('name = ?', 'sitepagebadge.badge-sitepagebadge')
		                 ->limit(1);
		    $content = $select_content->query()->fetchAll();
		    if(empty($content)) {
		      $select = new Zend_Db_Select($db);
		      $select_container = $select
		                   ->from('engine4_sitepage_content', 'content_id')
		                   ->where('contentpage_id = ?', $contentpage_id['contentpage_id'])
		                   ->where('type = ?', 'container')
		                   ->limit(1);
		      $container = $select_container->query()->fetchAll();
		      if(!empty($container)) {
			      $container_id = $container[0]['content_id'];
			      $select = new Zend_Db_Select($db);
			      $select_left = $select
			                   ->from('engine4_sitepage_content')
			                   ->where('parent_content_id = ?', $container_id)
			                   ->where('type = ?', 'container')
													->where('contentpage_id = ?', $contentpage_id['contentpage_id'])
													->where('name in (?)', array('left', 'right'))
			                   ->limit(1);
			      $left = $select_left->query()->fetchAll();
			      if(!empty($left)) {
			        $left_id = $left[0]['content_id'];
			        $db->insert('engine4_sitepage_content', array(
				        'contentpage_id' => $contentpage_id['contentpage_id'],
				        'type' => 'widget',
				        'name' => 'sitepagebadge.badge-sitepagebadge',
				        'parent_content_id' => $left_id,
				        'order' => 16,
				        'params' => '{"title":"Badge","titleCount":""}',
				      ));
			      }
		      }
		    }
			}
    }		
	}

  $contentTable = Engine_Api::_()->getDbtable('content', 'core');
  $contentTableName = $contentTable->info('name');

  $select = new Zend_Db_Select($db);
  $select
          ->from('engine4_core_pages')
          ->where('name = ?', 'sitepagebadge_index_showbadges')
          ->limit(1);
  ;
  $info = $select->query()->fetch();
  if ( empty($info) ) {
    $db->insert('engine4_core_pages', array(
        'name' => 'sitepagebadge_index_showbadges',
        'displayname' => 'Page badges',
        'title' => 'Page Badges List',
        'description' => 'This is the page badges.',
        'custom' => 1,
    ));
    $page_id = $db->lastInsertId('engine4_core_pages');
//INSERT MAIN CONTAINER
    $mainContainer = $contentTable->createRow();
    $mainContainer->page_id = $page_id;
    $mainContainer->type = 'container';
    $mainContainer->name = 'main';
    $mainContainer->order = 2;
    $mainContainer->save();
    $container_id = $mainContainer->content_id;

//INSERT MAIN - MIDDLE CONTAINER
    $mainMiddleContainer = $contentTable->createRow();
    $mainMiddleContainer->page_id = $page_id;
    $mainMiddleContainer->type = 'container';
    $mainMiddleContainer->name = 'middle';
    $mainMiddleContainer->parent_content_id = $container_id;
    $mainMiddleContainer->order = 6;
    $mainMiddleContainer->save();
    $middle_id = $mainMiddleContainer->content_id;

//INSERT MAIN - RIGHT CONTAINER
    $mainRightContainer = $contentTable->createRow();
    $mainRightContainer->page_id = $page_id;
    $mainRightContainer->type = 'container';
    $mainRightContainer->name = 'right';
    $mainRightContainer->parent_content_id = $container_id;
    $mainRightContainer->order = 5;
    $mainRightContainer->save();
    $right_id = $mainRightContainer->content_id;

//INSERT TOP CONTAINER
    $topContainer = $contentTable->createRow();
    $topContainer->page_id = $page_id;
    $topContainer->type = 'container';
    $topContainer->name = 'top';
    $topContainer->order = 1;
    $topContainer->save();
    $top_id = $topContainer->content_id;

//INSERT TOP- MIDDLE CONTAINER
    $topMiddleContainer = $contentTable->createRow();
    $topMiddleContainer->page_id = $page_id;
    $topMiddleContainer->type = 'container';
    $topMiddleContainer->name = 'middle';
    $topMiddleContainer->parent_content_id = $top_id;
    $topMiddleContainer->order = 6;
    $topMiddleContainer->save();
    $top_middle_id = $topMiddleContainer->content_id;

    //INSERT NAVIGATION WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepage.browsenevigation-sitepage', $top_middle_id, 1);

    //INSERT BADGE WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagebadge.sitepage-badge', $middle_id, 2);

     //INSERT MOST RECENT PAGE BADGE WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagebadge.popular-sitepagebadge', $right_id, 3, "Most Popular Badges", "true");


    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_modules')
            ->where('name = ?', 'communityad')
            ->where('enabled 	 = ?', 1)
            ->limit(1);
    ;
    $infomation = $select->query()->fetch();
    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_settings')
            ->where('name = ?', 'sitepage.communityads')
            ->where('value 	 = ?', 1)
            ->limit(1);
    ;
    $rowinfo = $select->query()->fetch();
    if ( $infomation && $rowinfo ) {
      Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepage.page-ads', $right_id, 6, "", "true");
    }
  }
}

?>