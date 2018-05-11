<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
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

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sitepagereview_admin_main_level", "sitepagereview", "Member Level Settings", "", \'{"route":"admin_default","module":"sitepagereview","controller":"settings","action":"level"}\', "sitepagereview_admin_main", "", 2)');

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitepagereview_admin_main_dayitems", "sitepagereview", "Review of the Day", "", \'{"route":"admin_default","module":"sitepagereview","controller":"settings", "action": "manage-day-items"}\', "sitepagereview_admin_main", "", 1, 0, 3)');

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
		
		//INSERTING THE POLL WIDGET IN SITEPAGE_ADMIN_CONTENT TABLE ALSO.
		Engine_Api::_()->getDbtable('admincontent', 'sitepage')->setAdminDefaultInfo('sitepagereview.profile-sitepagereviews', $page_id, 'Reviews', 'true', '113');		
	 
		//INSERTING THE POLL WIDGET IN CORE_CONTENT TABLE ALSO.
		Engine_Api::_()->getApi('layoutcore', 'sitepage')->setContentDefaultInfo('sitepagereview.profile-sitepagereviews', $page_id, 'Reviews', 'true', '113');
		
    //INSERTING THE POLL WIDGET IN SITEPAGE_CONTENT TABLE ALSO.
    $select = new Zend_Db_Select($db);								
		$contentpage_ids = $select->from('engine4_sitepage_contentpages', 'contentpage_id')->query()->fetchAll();
    foreach ($contentpage_ids as $contentpage_id) {
			if(!empty($contentpage_id)) {
        Engine_Api::_()->getDbtable('content', 'sitepage')->setDefaultInfo('sitepagereview.profile-sitepagereviews', $contentpage_id['contentpage_id'], 'Reviews', 'true', '113');
        
        $select = new Zend_Db_Select($db);
		    $select_content = $select
		                 ->from('engine4_sitepage_content')
		                 ->where('contentpage_id = ?', $contentpage_id['contentpage_id'])
		                 ->where('type = ?', 'widget')
		                 ->where('name = ?', 'sitepagereview.ratings-sitepagereviews')
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
			        'name' => 'sitepagereview.ratings-sitepagereviews',
			        'parent_content_id' => $left_id,
			        'order' => 15,
			        'params' => '{"title":"Ratings","titleCount":""}',
			        ));
		       	}
		      }
		    }       
			}
		}
		
	  $select = new Zend_Db_Select($db);
    $select_content = $select
                 ->from('engine4_sitepage_admincontent')
                 ->where('page_id = ?', $page_id)
                 ->where('type = ?', 'widget')
                 ->where('name = ?', 'sitepagereview.ratings-sitepagereviews')
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
	        'name' => 'sitepagereview.ratings-sitepagereviews',
	        'parent_content_id' => $left_id,
	        'order' => 15,
	        'params' => '{"title":"Ratings","titleCount":""}',
	        ));
       	}
      }
    } 
    
	  $select = new Zend_Db_Select($db);
    $select_content = $select
                 ->from('engine4_core_content')
                 ->where('page_id = ?', $page_id)
                 ->where('type = ?', 'widget')
                 ->where('name = ?', 'sitepagereview.ratings-sitepagereviews')
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
	        'name' => 'sitepagereview.ratings-sitepagereviews',
	        'parent_content_id' => $left_id,
	        'order' => 15,
	        'params' => '{"title":"Ratings","titleCount":""}',
	        ));
       	}
      }
    }   
	}
	
	//PUT TOP RATED WIDGET AT PAGE HOME
  $select = new Zend_Db_Select($db);
	$select_page = $select
											 ->from('engine4_core_pages', 'page_id')
											 ->where('name = ?', 'sitepage_index_home')
											 ->limit(1);
  $page = $select_page->query()->fetchAll();
	if(!empty($page)) { 
		$page_id = $page[0]['page_id'];
		$select = new Zend_Db_Select($db);
		$select_content = $select
														->from('engine4_core_content')
														->where('page_id = ?', $page_id)
														->where('type = ?', 'widget')
														->where('name = ?', 'sitepagereview.topratedpages-sitepagereviews')
														->limit(1);
		$content = $select_content->query()->fetchAll();
		if(empty($content)) {
			$select = new Zend_Db_Select($db);
			$select_container = $select
																->from('engine4_core_content', 'content_id')
															->where('page_id = ?', $page_id)
																->where('type = ?', 'container')
																->where('name = ?', 'main')
																->limit(1);
			$container = $select_container->query()->fetchAll();
		  if(!empty($container)) {
				$container_id = $container[0]['content_id'];
				$select = new Zend_Db_Select($db);
				$select_left = $select
																->from('engine4_core_content')
															->where('parent_content_id = ?', $container_id)
																->where('type = ?', 'container')
																->where('name = ?', 'left')
																->limit(1);
				$left = $select_left->query()->fetchAll();
				if(!empty($left)) {
					$left_id = $left[0]['content_id'];
					$select = new Zend_Db_Select($db);
						$select_tab = $select
																->from('engine4_core_content')
																->where('type = ?', 'widget')
																->where('name = ?', 'core.container-tabs')
																->where('page_id = ?', $page_id)
																->limit(1);
						$tab = $select_tab->query()->fetchAll();
						if(!empty($tab)) {
							$tab_id = $tab[0]['content_id'];
					}

					$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagereview.topratedpages-sitepagereviews',
						'parent_content_id' => ($left_id ? $left_id : $tab_id),
						'order' => 998,
						'params' => '{"title":"Top Rated Pages","titleCount":"true"}',
						));
			  }
			}
		}
	}	

	$contentTable = Engine_Api::_()->getDbtable('content', 'core');
  $contentTableName = $contentTable->info('name');
 
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

  $select = new Zend_Db_Select($db);
  $select
          ->from('engine4_core_pages')
          ->where('name = ?', 'sitepagereview_index_browse')
          ->limit(1);
  ;
  $info = $select->query()->fetch();
  if ( empty($info) ) {
    $db->insert('engine4_core_pages', array(
        'name' => 'sitepagereview_index_browse',
        'displayname' => 'Browse Page Reviews',
        'title' => 'Page Reviews List',
        'description' => 'This is the page reviews.',
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

    //INSERT REVIEW WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagereview.sitepage-review', $middle_id, 2,"Reviews");

    //INSERT SEARCH PAGE REVIEW WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagereview.search-sitepagereview', $right_id, 3, "", "true");

    //INSERT FEATURED PAGE REVIEW WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagereview.featured-sitepagereviews', $right_id, 4, "Featured Reviews", "true");

    //INSERT MOST COMMENTED PAGE REVIEW WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagereview.homecomment-sitepagereviews', $right_id, 5, "Most Commented Reviews", "true");

    //INSERT MOST POPULAR PAGE REVIEW WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagereview.site-popular-reviews', $right_id, 6, "Most Popular Reviews", "true");

    //INSERT MOST LIKED PAGE REVIEW WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagereview.homelike-sitepagereviews', $right_id, 7, "Most Liked Reviews", "true");

    //INSERT TOP REVIEWER PAGE REVIEW WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagereview.reviewer-sitepagereviews', $right_id, 8, "Top Reviewers", "true");

    //INSERT TITEM OF THE DAY PAGE REVIEW WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagereview.review-of-the-day', $right_id, 9, "Review of the Day", "true");

    //INSERT RECENT PAGE REVIEW WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagereview.recent-sitepagereviews', $right_id, 10, "Recent Reviews", "true");

    if ( $infomation && $rowinfo ) {
      Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepage.page-ads', $right_id, 11, "", "true");
    }
  }

  $select = new Zend_Db_Select($db);

  // Check if it's already been placed
  $select = new Zend_Db_Select($db);
  $select
          ->from('engine4_core_pages')
          ->where('name = ?', 'sitepagereview_index_view')
          ->limit(1);
  ;
  $info = $select->query()->fetch();

  if ( empty($info) ) {
    $db->insert('engine4_core_pages', array(
        'name' => 'sitepagereview_index_view',
        'displayname' => 'Page Review View Page',
        'title' => 'View Page Review',
        'description' => 'This is the view page for a page review.',
        'custom' => 1,
        'provides' => 'subject=sitepagereview',
    ));
    $page_id = $db->lastInsertId('engine4_core_pages');

    // containers
    $db->insert('engine4_core_content', array(
        'page_id' => $page_id,
        'type' => 'container',
        'name' => 'main',
        'parent_content_id' => null,
        'order' => 1,
        'params' => '',
    ));
    $container_id = $db->lastInsertId('engine4_core_content');

    $db->insert('engine4_core_content', array(
        'page_id' => $page_id,
        'type' => 'container',
        'name' => 'right',
        'parent_content_id' => $container_id,
        'order' => 1,
        'params' => '',
    ));
    $right_id = $db->lastInsertId('engine4_core_content');

    $db->insert('engine4_core_content', array(
        'page_id' => $page_id,
        'type' => 'container',
        'name' => 'middle',
        'parent_content_id' => $container_id,
        'order' => 3,
        'params' => '',
    ));
    $middle_id = $db->lastInsertId('engine4_core_content');

    // middle column content
    $db->insert('engine4_core_content', array(
        'page_id' => $page_id,
        'type' => 'widget',
        'name' => 'sitepagereview.review-content',
        'parent_content_id' => $middle_id,
        'order' => 1,
        'params' => '',
    ));

    $db->insert('engine4_core_content', array(
        'page_id' => $page_id,
        'type' => 'widget',
        'name' => 'sitepagereview.sitepage-review-detail',
        'parent_content_id' => $right_id,
        'order' => 1,
        'params' => '{"title":"Review Details"}',
    ));

    if ( $infomation && $rowinfo ) {
      Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepage.page-ads', $right_id, 2, "", "true");
    }

  }

  $select = new Zend_Db_Select($db);
	$select
	->from('engine4_core_modules')
	->where('name = ?', 'mobi')
	->where('enabled 	 = ?', 1)
	->limit(1);
	;  

	$infomation = $select->query()->fetch();
	if(!empty($infomation)) {
		$select = new Zend_Db_Select($db);
		$select
		->from('engine4_core_pages')
		->where('name = ?', 'sitepagereview_mobi_view')
		->limit(1);
		;
		$info = $select->query()->fetch();
		if (empty($info)) {
			$db->insert('engine4_core_pages', array(
						'name' => 'sitepagereview_mobi_view',
						'displayname' => 'Mobile Page Review Profile',
						'title' => 'Mobile Page Review Profile',
						'description' => 'This is the mobile verison of a Page review profile page.',
						'custom' => 0,
			));
			$page_id = $db->lastInsertId('engine4_core_pages');

			// containers
			$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'container',
						'name' => 'main',
						'parent_content_id' => null,
						'order' => 1,
						'params' => '',
			));
			$container_id = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'container',
						'name' => 'right',
						'parent_content_id' => $container_id,
						'order' => 1,
						'params' => '',
			));
			$right_id = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'container',
						'name' => 'middle',
						'parent_content_id' => $container_id,
						'order' => 3,
						'params' => '',
			));
			$middle_id = $db->lastInsertId('engine4_core_content');

			// middle column content
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'sitepagereview.review-content',
					'parent_content_id' => $middle_id,
					'order' => 1,
					'params' => '',
			));

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'sitepagereview.sitepage-review-detail',
					'parent_content_id' => $right_id,
					'order' => 1,
					'params' => '{"title":"Review Details"}',
			));
		}
	}

  $select = new Zend_Db_Select($db);
	$select
					->from('engine4_core_pages')
					->where('name = ?', 'sitepagereview_index_home')
					->limit(1);
	$info = $select->query()->fetch();
	if (empty($info)) {
		$db->insert('engine4_core_pages', array(
				'name' => 'sitepagereview_index_home',
				'displayname' => 'Page Reviews Home',
				'title' => 'Page Reviews Home',
				'description' => 'This is page review home page.',
				'custom' => 1
		));
		$page_id = $db->lastInsertId('engine4_core_pages');

		// containers
		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'container',
				'name' => 'main',
				'parent_content_id' => null,
				'order' => 2,
				'params' => '',
		));
		$container_id = $db->lastInsertId('engine4_core_content');

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'container',
				'name' => 'right',
				'parent_content_id' => $container_id,
				'order' => 5,
				'params' => '',
		));
		$right_id = $db->lastInsertId('engine4_core_content');

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'container',
				'name' => 'left',
				'parent_content_id' => $container_id,
				'order' => 4,
				'params' => '',
		));
		$left_id = $db->lastInsertId('engine4_core_content');

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'container',
				'name' => 'top',
				'parent_content_id' => null,
				'order' => 1,
				'params' => '',
		));
		$top_id = $db->lastInsertId('engine4_core_content');

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'container',
				'name' => 'middle',
				'parent_content_id' => $top_id,
				'order' => 6,
				'params' => '',
		));
		$top_middle_id = $db->lastInsertId('engine4_core_content');

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'container',
				'name' => 'middle',
				'parent_content_id' => $container_id,
				'order' => 6,
				'params' => '',
		));
		$middle_id = $db->lastInsertId('engine4_core_content');

	// Top Middle
		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepage.browsenevigation-sitepage',
				'parent_content_id' => $top_middle_id,
				'order' => 3,
				'params' => '',
		));

		// Left
		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagereview.recent-sitepagereviews',
				'parent_content_id' => $left_id,
				'order' => 16,
				'params' => '{"title":"Recent Reviews","titleCount":"true"}',
		));

		// Middle
		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagereview.featured-reviews-slideshow',
				'parent_content_id' => $middle_id,
				'order' => 15,
				'params' => '{"title":"Featured Reviews","titleCount":"true"}',
		));

    $db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepage.category-pages-sitepage',
				'parent_content_id' => $middle_id,
				'order' => 16,
				'params' => '{"title":"Most Reviewed this Month","titleCount":true,"itemCount":"6","pageCount":"3","popularity":"review_count","interval":"month","nomobile":"1"}',
		));


		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagereview.review-tabs',
				'parent_content_id' => $middle_id,
				'order' => 17,
				'params' => '{"title":"People\'s Reviews"}',
		));
		// Right Side
		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagereview.sitepagereviewlist-link',
				'parent_content_id' => $right_id,
				'order' => 19,
				'params' => '',
		));

		// Right Side
		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagereview.search-sitepagereview',
				'parent_content_id' => $right_id,
				'order' => 18,
				'params' => '',
		));

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagereview.review-of-the-day',
				'parent_content_id' => $left_id,
				'order' => 13,
				'params' => '{"title":"Review of the Day"}',
		));

    $db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagereview.topratedpages-sitepagereviews',
				'parent_content_id' => $left_id,
				'order' => 14,
				'params' => '{"title":"Top Rated Pages","itemCountPerPage":3}',
		));

    $db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagereview.reviewer-sitepagereviews',
				'parent_content_id' => $left_id,
				'order' => 15,
				'params' => '{"title":"Top Reviewers","itemCountPerPage":3}',
		));

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagereview.homecomment-sitepagereviews',
				'parent_content_id' => $right_id,
				'order' => 22,
				'params' => '{"title":"Most Commented Reviews","titleCount":"true"}',
		));

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagereview.homelike-sitepagereviews',
				'parent_content_id' => $right_id,
				'order' => 21,
				'params' => '{"title":"Most Liked Reviews","titleCount":"true"}',
		));
	}
	
}

?>