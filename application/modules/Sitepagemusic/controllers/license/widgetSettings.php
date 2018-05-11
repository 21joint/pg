<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: widgetSettings.php 2011-05-05 9:40:21Z SocialEngineAddOns $
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
if (!empty($sitepage_settings)) {
  $sitepage_is_active = $sitepage_settings[0]['value'];
} else {
  $sitepage_is_active = 0;
}

//CHECK THAT SITEPAGE PLUGIN IS INSTALLED OR NOT
$select = new Zend_Db_Select($db);
$select
        ->from('engine4_core_modules')
        ->where('name = ?', 'sitepage')
        ->where('enabled = ?', 1);
$check_sitepage = $select->query()->fetchObject();
if (!empty($check_sitepage) && !empty($sitepage_is_active)) {
  $select = new Zend_Db_Select($db);
  $select_page = $select->from('engine4_core_pages', 'page_id')->where('name = ?', 'sitepage_index_view')->limit(1);
  $page = $select_page->query()->fetchAll();
  if (!empty($page)) {
    $page_id = $page[0]['page_id'];

    //INSERTING THE MUSIC WIDGET IN SITEPAGE_ADMIN_CONTENT TABLE ALSO.
    Engine_Api::_()->getDbtable('admincontent', 'sitepage')->setAdminDefaultInfo('sitepagemusic.profile-sitepagemusic', $page_id, 'Music', 'true', '120');

    //INSERTING THE MUSIC WIDGET IN CORE_CONTENT TABLE ALSO.
    Engine_Api::_()->getApi('layoutcore', 'sitepage')->setContentDefaultInfo('sitepagemusic.profile-sitepagemusic', $page_id, 'Music', 'true', '120');

    //INSERTING THE MUSIC WIDGET IN SITEPAGE_CONTENT TABLE ALSO.
    $select = new Zend_Db_Select($db);
    $contentpage_ids = $select->from('engine4_sitepage_contentpages', 'contentpage_id')->query()->fetchAll();
    foreach ($contentpage_ids as $contentpage_id) {
      if (!empty($contentpage_id)) {
        Engine_Api::_()->getDbtable('content', 'sitepage')->setDefaultInfo('sitepagemusic.profile-sitepagemusic', $contentpage_id['contentpage_id'], 'Music', 'true', '120');
        //INSERT THE PAGE PROFILE PLAYER WIDGET
        $select = new Zend_Db_Select($db);
        $select_content = $select
                ->from('engine4_sitepage_content')
                ->where('contentpage_id = ?', $contentpage_id['contentpage_id'])
                ->where('type = ?', 'widget')
                ->where('name = ?', 'sitepagemusic.profile-player')
                ->limit(1);
        $content = $select_content->query()->fetchAll();
        if (empty($content)) {
          $select = new Zend_Db_Select($db);
          $select_container = $select
                  ->from('engine4_sitepage_content', 'content_id')
                  ->where('contentpage_id = ?', $contentpage_id['contentpage_id'])
                  ->where('type = ?', 'container')
                  ->limit(1);
          $container = $select_container->query()->fetchAll();
          if (!empty($container)) {
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
            if (!empty($left)) {
              $left_id = $left[0]['content_id'];
              $db->insert('engine4_sitepage_content', array(
                  'contentpage_id' => $contentpage_id['contentpage_id'],
                  'type' => 'widget',
                  'name' => 'sitepagemusic.profile-player',
                  'parent_content_id' => $left_id,
                  'order' => 26,
                  'params' => '{"title":"","titleCount":""}',
              ));
            }
          }
        }
      }
    }
    //INSERT THE PAGE PROFILE PLAYER WIDGET
    $select = new Zend_Db_Select($db);
    $select_content = $select
            ->from('engine4_core_content')
            ->where('page_id = ?', $page_id)
            ->where('type = ?', 'widget')
            ->where('name = ?', 'sitepagemusic.profile-player')
            ->limit(1);
    $content = $select_content->query()->fetchAll();
    if (empty($content)) {
      $select = new Zend_Db_Select($db);
      $select_container = $select
              ->from('engine4_core_content', 'content_id')
              ->where('page_id = ?', $page_id)
              ->where('type = ?', 'container')
              ->limit(1);
      $container = $select_container->query()->fetchAll();
      if (!empty($container)) {
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
        if (!empty($left)) {
          $left_id = $left[0]['content_id'];
          $db->insert('engine4_core_content', array(
              'page_id' => $page_id,
              'type' => 'widget',
              'name' => 'sitepagemusic.profile-player',
              'parent_content_id' => $left_id,
              'order' => 26,
              'params' => '{"title":"","titleCount":""}',
          ));
        }
      }
    }  
    //INSERT THE PAGE PROFILE PLAYER WIDGET
    $select = new Zend_Db_Select($db);
    $select_content = $select
            ->from('engine4_sitepage_admincontent')
            ->where('page_id = ?', $page_id)
            ->where('type = ?', 'widget')
            ->where('name = ?', 'sitepagemusic.profile-player')
            ->limit(1);
    $content = $select_content->query()->fetchAll();
    if (empty($content)) {
      $select = new Zend_Db_Select($db);
      $select_container = $select
              ->from('engine4_sitepage_admincontent', 'admincontent_id')
              ->where('page_id = ?', $page_id)
              ->where('type = ?', 'container')
              ->limit(1);
      $container = $select_container->query()->fetchAll();
      if (!empty($container)) {
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
        if (!empty($left)) {
          $left_id = $left[0]['admincontent_id'];
          $db->insert('engine4_sitepage_admincontent', array(
              'page_id' => $page_id,
              'type' => 'widget',
              'name' => 'sitepagemusic.profile-player',
              'parent_content_id' => $left_id,
              'order' => 26,
              'params' => '{"title":"","titleCount":""}',
          ));
        }
      }
    }      
  }
  $contentTable = Engine_Api::_()->getDbtable('content', 'core');
  $contentTableName = $contentTable->info('name');

  $select = new Zend_Db_Select($db);
  $select
          ->from('engine4_core_pages')
          ->where('name = ?', 'sitepagemusic_playlist_browse')
          ->limit(1);
  ;
  $info = $select->query()->fetch();
  if ( empty($info) ) {
    $db->insert('engine4_core_pages', array(
        'name' => 'sitepagemusic_playlist_browse',
        'displayname' => 'Browse Page music',
        'title' => 'Page Music List',
        'description' => 'This is the page music.',
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

//INSERT MUSIC WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagemusic.sitepage-music', $middle_id, 2);

    //INSERT SEARCH PAGE MUSIC WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagemusic.search-sitepagemusic', $right_id, 3, "", "true");

    //INSERT MOST COMMENTED PAGE MUSIC WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagemusic.homecomment-sitepagemusic', $right_id, 4, "Most Commented Playlists", "true");

    //INSERT SPONSORED PAGE MUSIC WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagemusic.sitepage-sponsoredmusic', $right_id, 5, "Sponsored Playlists", "true");

    //INSERT MOST LIKED PAGE MUSIC WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagemusic.homelike-sitepagemusic', $right_id, 6, "Most Liked Playlists", "true");

    //INSERT RECENT PAGE MUSIC WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagemusic.homerecent-sitepagemusic', $right_id, 7, "Recent Page Playlists", "true");

   //INSERT POPULAR PAGE MUSIC WIDGET
    Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepagemusic.homepopular-sitepagemusic', $right_id, 8, "Popular Page Playlists", "true");

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
      Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepage.page-ads', $right_id, 10, "", "true");
    }
  }

  $select = new Zend_Db_Select($db);

  // Check if it's already been placed
  $select = new Zend_Db_Select($db);
  $select
          ->from('engine4_core_pages')
          ->where('name = ?', 'sitepagemusic_playlist_view')
          ->limit(1);
  ;
  $info = $select->query()->fetch();

  if ( empty($info) ) {
    $db->insert('engine4_core_pages', array(
        'name' => 'sitepagemusic_playlist_view',
        'displayname' => 'Page Music View Page',
        'title' => 'View Page Music',
        'description' => 'This is the view page for a page music.',
        'custom' => 1,
        'provides' => 'subject=sitepagemusic',
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
        'name' => 'sitepagemusic.music-content',
        'parent_content_id' => $middle_id,
        'order' => 1,
        'params' => '',
    ));
    if ( $infomation && $rowinfo ) {
      Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepage.page-ads', $right_id, 1, "", "true");
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
		->where('name = ?', 'sitepagemusic_mobi_view')
		->limit(1);
		;
		$info = $select->query()->fetch();
		if (empty($info)) {
			$db->insert('engine4_core_pages', array(
						'name' => 'sitepagemusic_mobi_view',
						'displayname' => 'Mobile Page Music Profile',
						'title' => 'Mobile Page Music Profile',
						'description' => 'This is the mobile verison of a Page music profile page.',
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
					'name' => 'sitepagemusic.music-content',
					'parent_content_id' => $middle_id,
					'order' => 1,
					'params' => '',
			));
		}
	}
  $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitepagemusic_admin_submain_general_tab", "sitepagemusic", "General Settings", "", \'{"route":"admin_default","module":"sitepagemusic","controller":"widgets", "action": "index"}\', "sitepagemusic_admin_submain", "", 1, 0, 1)');

	$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitepagemusic_admin_submain_music_tab", "sitepagemusic", "Tabbed Musics Widget", "", \'{"route":"admin_default","module":"sitepagemusic","controller":"settings", "action": "widget"}\', "sitepagemusic_admin_submain", "", 1, 0, 2)');

	$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitepagemusic_admin_submain_dayitems", "sitepagemusic", "Music of the Day", "", \'{"route":"admin_default","module":"sitepagemusic","controller":"settings", "action": "manage-day-items"}\', "sitepagemusic_admin_submain", "", 1, 0, 4)');


	$select = new Zend_Db_Select($db);
	$select
					->from('engine4_core_pages')
					->where('name = ?', 'sitepagemusic_playlist_home')
					->limit(1);
	$info = $select->query()->fetch();
	if (empty($info)) {
		$db->insert('engine4_core_pages', array(
				'name' => 'sitepagemusic_playlist_home',
				'displayname' => 'Page Music Home',
				'title' => 'Page Music Home',
				'description' => 'This is page music home page.',
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
				'name' => 'sitepagemusic.homerecent-sitepagemusic',
				'parent_content_id' => $left_id,
				'order' => 14,
				'params' => '{"title":"Recent Page Playlists","titleCount":"true"}',
		));

    $db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagemusic.homepopular-sitepagemusic',
				'parent_content_id' => $left_id,
				'order' => 15,
				'params' => '{"title":"Popular Page Playlists","titleCount":"true"}',
		));

	// Middele
		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagemusic.featured-musics-carousel',
				'parent_content_id' => $middle_id,
				'order' => 17,
				'params' => '{"title":"Featured Playlists","vertical":"0", "noOfRow":"2","inOneRow":"3","interval":"250","name":"sitepagemusic.featured-musics-carousel"}',
		));

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagemusic.list-musics-tabs-view',
				'parent_content_id' => $middle_id,
				'order' => 18,
				'params' => '{"title":"Playlists","margin_photo":"12"}',
		));
		// Right Side
		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagemusic.sitepagemusiclist-link',
				'parent_content_id' => $right_id,
				'order' => 20,
				'params' => '',
		));

		// Right Side
		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagemusic.search-sitepagemusic',
				'parent_content_id' => $right_id,
				'order' => 19,
				'params' => '',
		));

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagemusic.music-of-the-day',
				'parent_content_id' => $left_id,
				'order' => 13,
				'params' => '{"title":"Playlist of the Day"}',
		));

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagemusic.homefeaturelist-sitepagemusics',
				'parent_content_id' => $right_id,
				'order' => 21,
				'params' => '{"title":"Featured Playlists","itemCountPerPage":3}',
		));


		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagemusic.homecomment-sitepagemusic',
				'parent_content_id' => $right_id,
				'order' => 22,
				'params' => '{"title":"Most Commented Playlists","titleCount":"true"}',
		));

		$db->insert('engine4_core_content', array(
				'page_id' => $page_id,
				'type' => 'widget',
				'name' => 'sitepagemusic.homelike-sitepagemusic',
				'parent_content_id' => $right_id,
				'order' => 23,
				'params' => '{"title":"Most Liked Playlists","titleCount":"true"}',
		));
	}
}
?>