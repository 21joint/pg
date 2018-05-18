<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Installer extends Engine_Package_Installer_Module {

  function onPreInstall() {
    //GET DB
    $db = $this->getDb();
    
    $getErrorMsg = $this->getVersion(); 
    if (!empty($getErrorMsg)) {
      return $this->_error($getErrorMsg);
    }
    
    //CHECK THAT SITEPAGE PLUGIN IS ACTIVATED OR NOT
    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_settings')
            ->where('name = ?', 'sitepage.is.active')
            ->limit(1);
    $sitepage_settings = $select->query()->fetchAll();
    if ( !empty($sitepage_settings) ) {
      $sitepage_is_active = $sitepage_settings[0]['value'];
    }
    else {
      $sitepage_is_active = 0;
    }

    //CHECK THAT SITEPAGE PLUGIN IS INSTALLED OR NOT
    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_modules')
            ->where('name = ?', 'sitepage');
    $check_sitepage = $select->query()->fetchObject();
    $curr_module_version = strcasecmp($check_sitepage->version, '4.1.6p1');
    if ( !empty($check_sitepage->enabled) && !empty($sitepage_is_active) ) {
      if ( $curr_module_version >= 0 ) {
        $PRODUCT_TYPE = 'sitepagemusic';
        $PLUGIN_TITLE = 'sitepagemusic';
        $PLUGIN_VERSION = '4.9.4p1';
        $PLUGIN_CATEGORY = 'plugin';
        $PRODUCT_DESCRIPTION = 'Sitepagemusic Plugin';
        $pageTime = time();
        $_PRODUCT_FINAL_FILE = 0;
      $sitepage_plugin_version = '4.8.6';
      $SocialEngineAddOns_version = '4.8.9p3';
        $PRODUCT_TITLE = 'Directory / Pages - Music Extension';
        $file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
        $is_file = file_exists($file_path);
        if ( empty($is_file) ) {
          include APPLICATION_PATH . "/application/modules/Sitepage/controllers/license/license4.php";
        }
        else {
          include $file_path;
        }
        $db->query("INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES ('sitepagemusic.basetime', $pageTime ), ('sitepagemusic.isvar', 0 );");

        // Insert when "Page music Plugin Enabled".
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitepagemusic')
                ->where('version < ?', '4.1.7');
        $is_enabled = $select->query()->fetchObject();
        if ( !empty($is_enabled) ) {
          //INSERTING THE MUSIC WIDGET IN SITEPAGE_CONTENT TABLE ALSO.
          $select = new Zend_Db_Select($db);
          $contentpage_ids = $select->from('engine4_sitepage_contentpages', 'contentpage_id')->query()->fetchAll();
          foreach ( $contentpage_ids as $contentpage_id ) {
            if ( !empty($contentpage_id) ) {

              //INSERT THE PAGE PROFILE PLAYER WIDGET
              $select = new Zend_Db_Select($db);
              $select_content = $select
                              ->from('engine4_sitepage_content')
                              //->where('contentpage_id = ?', $contentpage_id['contentpage_id'])
                              ->where('type = ?', 'widget')
                              ->where('name = ?', 'sitepagemusic.profile-player');
              //->limit(1);
              $content = $select_content->query()->fetchAll();
              if ( !empty($content) ) {
                $select = new Zend_Db_Select($db);
                $select_container = $select
                                ->from('engine4_sitepage_content', 'content_id')
                                ->where('contentpage_id = ?', $contentpage_id['contentpage_id'])
                                ->where('type = ?', 'container')
                                ->limit(1);
                $container = $select_container->query()->fetchAll();
                if ( !empty($container) ) {
                  $container_id = $container[0]['content_id'];
                  $select = new Zend_Db_Select($db);
                  $select_left = $select
                                  ->from('engine4_sitepage_content')
                                  ->where('parent_content_id = ?', $container_id)
                                  ->where('type = ?', 'container')
                                  ->where('name = ?', 'left')
                                  ->limit(1);
                  $left = $select_left->query()->fetchAll();
                  if ( !empty($left) ) {
                    $left_id = $left[0]['content_id'];
                    $db->update('engine4_sitepage_content', array('contentpage_id' => $contentpage_id['contentpage_id']), array('parent_content_id =?' => $left_id)
                    );
                  }
                }
              }
            }
          }
        }
        parent::onPreInstall();
      }
      else {
        return $this->_error('<div class="global_form"><div><div> You do not have the latest version 4.1.6p1 of the Directory / Pages Plugin. Please download the latest version of this plugin from your Client Area on <a href="http://www.socialengineaddons.com" target="_blank">SocialEngineAddOns</a> and upgrade this on your site.</div></div></div>');
      }
    }
    elseif ( !empty($check_sitepage) && empty($sitepage_is_active) ) {
      $baseUrl = $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getBaseUrl();
      $url_string = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
      if ( strstr($url_string, "manage/install") ) {
        $calling_from = 'install';
      }
      else if ( strstr($url_string, "manage/query") ) {
        $calling_from = 'queary';
      }
      $explode_base_url = explode("/", $baseUrl);
      foreach ( $explode_base_url as $url_key ) {
        if ( $url_key != 'install' ) {
          $core_final_url .= $url_key . '/';
        }
      }
      return $this->_error("<span style='color:red'>Note: You have installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> but not activated it on your site yet. Please activate it first before installing the Directory / Pages - Music Extension.</span><br/> <a href='" . 'http://' . $core_final_url . "admin/sitepage/settings/readme'>Click here</a> to activate the Directory / Pages Plugin.");
    }
    else {
      $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
      return $this->_error("<span style='color:red'>Note: You have not installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> on your site yet. Please install it first before installing the <a href='http://www.socialengineaddons.com/pageextensions/socialengine-directory-pages-music' target='_blank'>Directory / Pages - Music Extension</a>.</span><br/> <a href='" . $base_url . "/manage'>Click here</a> to go Manage Packages.");
    }
  }

  function onInstall() {

    $db = $this->getDb();
    
    $db->query('UPDATE  `engine4_activity_notificationtypes` SET  `body` =  \'{item:$subject} has created a page music {item:$object}.\' WHERE  `engine4_activity_notificationtypes`.`type` =  "sitepagemusic_create";');
    
    $table_exist = $db->query("SHOW TABLES LIKE 'engine4_sitepagemusic_playlists'")->fetch();
    if (!empty($table_exist)) {
      //DROP THE COLUMN FROM THE "engine4_sitepagemusic_playlists" TABLE
      $ownerTypeColumn = $db->query("SHOW COLUMNS FROM engine4_sitepagemusic_playlists LIKE 'owner_type'")->fetch();
      if(!empty($ownerTypeColumn)) {
        $db->query("ALTER TABLE `engine4_sitepagemusic_playlists` DROP `owner_type`");
      }

      //DROP THE INDEX FROM THE "engine4_sitepagemusic_playlists" TABLE
      $creationDateColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepagemusic_playlists` WHERE Key_name = 'creation_date'")->fetch();

      if( !empty($creationDateColumnIndex)) {
        $db->query("ALTER TABLE `engine4_sitepagemusic_playlists` DROP INDEX `creation_date`");
      }

      //DROP THE INDEX FROM THE "engine4_sitepagemusic_playlists" TABLE
      $playCountColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepagemusic_playlists` WHERE Key_name = 'play_count'")->fetch();

      if( !empty($playCountColumnIndex)) {
        $db->query("ALTER TABLE `engine4_sitepagemusic_playlists` DROP INDEX `play_count`");
      }

      //ADD THE INDEX FROM THE "engine4_sitepagemusic_playlists" TABLE
      $pageIdColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepagemusic_playlists` WHERE Key_name = 'page_id'")->fetch();

      if( empty($pageIdColumnIndex)) {
        $db->query("ALTER TABLE `engine4_sitepagemusic_playlists` ADD INDEX ( `page_id` );");
      }        

      //ADD THE COLUMN FROM THE "engine4_sitepagemusic_playlists" TABLE
      $specialColumn = $db->query("SHOW COLUMNS FROM engine4_sitepagemusic_playlists LIKE 'special'")->fetch();
      if(empty($specialColumn)) {
        $db->query("ALTER TABLE `engine4_sitepagemusic_playlists` ADD `special` ENUM( 'wall', 'message' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;");
      }      

      //ADD THE COLUMN FROM THE "engine4_sitepagemusic_playlists" TABLE
      $playlistFileIdColumn = $db->query("SHOW COLUMNS FROM engine4_sitepagemusic_playlist_songs LIKE 'playlist_file_id'")->fetch();
      if(empty($playlistFileIdColumn)) {
        $db->query("ALTER TABLE `engine4_sitepagemusic_playlist_songs` ADD `playlist_file_id` INT( 11 ) NOT NULL;");
      } 
    }

    $table_exist = $db->query("SHOW TABLES LIKE 'engine4_sitepagemusic_playlist_songs'")->fetch();
    if (!empty($table_exist)) {
      //DROP THE INDEX FROM THE "engine4_sitepagemusic_playlist_songs" TABLE
      $playCountSongsColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepagemusic_playlist_songs` WHERE Key_name = 'play_count'")->fetch();

      if( !empty($playCountSongsColumnIndex)) {
        $db->query("ALTER TABLE `engine4_sitepagemusic_playlist_songs` DROP INDEX `play_count`");
      } 
    }
    //REMOVED WIDGET SETTING TAB FROM ADMIN PANEL
    $select = new Zend_Db_Select($db);
    $select->from('engine4_core_modules')
            ->where('name = ?', 'sitepagemusic')
            ->where('version <= ?', '4.1.7');
    $is_enabled = $select->query()->fetchObject();
    if ( !empty($is_enabled) ) {
      $widget_names = array('comment', 'recent', 'like', 'popular', 'homepopularmusics', 'homerecentmusics');

      foreach ( $widget_names as $widget_name ) {

        $widget_type = $widget_name;

        $widget_name = 'sitepagemusic.' . $widget_name . '-sitepagemusic';

        if ( $widget_type == 'homepopularmusics' ) {
          $widget_name == 'sitepagemusic.homepopular-sitepagemusics';
        }
        if ( $widget_type == 'homerecentmusics' ) {
          $widget_name == 'sitepagemusic.homerecent-sitepagemusics';
        }

        $setting_name = 'sitepagemusic.' . $widget_type . '.widgets';
        $total_items = $db->select()
                        ->from('engine4_core_settings', array('value'))
                        ->where('name = ?', $setting_name)
                        ->limit(1)
                        ->query()
                        ->fetchColumn();

        if ( empty($total_items) ) {
          $total_items = 3;
        }

        //WORK FOR CORE CONTENT PAGES
        $select = new Zend_Db_Select($db);
        $select->from('engine4_core_content', array('name', 'params', 'content_id'))->where('name = ?', $widget_name);
        $widgets = $select->query()->fetchAll();
        foreach ( $widgets as $widget ) {
          $explode_params = explode('}', $widget['params']);
          if ( !empty($explode_params[0]) && !strstr($explode_params[0], '"itemCount"') ) {
            $params = $explode_params[0] . ',"itemCount":"' . $total_items . '"}';

            $db->update('engine4_core_content', array('params' => $params), array('content_id = ?' => $widget['content_id'], 'name = ?' => $widget_name));
          }
        }

        //WORK FOR ADMIN USER CONTENT PAGE
        $select = new Zend_Db_Select($db);
        $select->from('engine4_sitepage_admincontent', array('name', 'params', 'admincontent_id'))->where('name = ?', $widget_name);
        $widgets = $select->query()->fetchAll();
        foreach ( $widgets as $widget ) {
          $explode_params = explode('}', $widget['params']);
          if ( !empty($explode_params[0]) && !strstr($explode_params[0], '"itemCount"') ) {
            $params = $explode_params[0] . ',"itemCount":"' . $total_items . '"}';

            $db->update('engine4_sitepage_admincontent', array('params' => $params), array('admincontent_id = ?' => $widget['admincontent_id'], 'name = ?' => $widget_name));
          }
        }

        //WORK FOR USER CONTENT PAGES
        $select = new Zend_Db_Select($db);
        $select->from('engine4_sitepage_content', array('name', 'params', 'content_id'))->where('name = ?', $widget_name);
        $widgets = $select->query()->fetchAll();
        foreach ( $widgets as $widget ) {
          $explode_params = explode('}', $widget['params']);
          if ( !empty($explode_params[0]) && !strstr($explode_params[0], '"itemCount"') ) {
            $params = $explode_params[0] . ',"itemCount":"' . $total_items . '"}';

            $db->update('engine4_sitepage_content', array('params' => $params), array('content_id = ?' => $widget['content_id'], 'name = ?' => $widget_name));
          }
        }
      }
    }

    //START THE WORK FOR MAKE WIDGETIZE PAGE OF MUSIC LISTING AND MUSIC VIEW PAGE
    $select = new Zend_Db_Select($db);
		$select
						->from('engine4_core_modules')
						->where('name = ?', 'sitepagemusic')
						->where('version < ?', '4.2.0');
		$is_enabled = $select->query()->fetchObject();
    if(!empty($is_enabled)) {
			$select = new Zend_Db_Select($db);
			$select
							->from('engine4_core_pages')
							->where('name = ?', 'sitepagemusic_playlist_musiclist')
							->limit(1);
			;
			$info = $select->query()->fetch();

      $select = new Zend_Db_Select($db);
			$select
							->from('engine4_core_pages')
							->where('name = ?', 'sitepagemusic_playlist_browse')
							->limit(1);
			;
			$info_browse = $select->query()->fetch();

			if ( empty($info) && empty($info_browse) ) {
				$db->insert('engine4_core_pages', array(
						'name' => 'sitepagemusic_playlist_browse',
						'displayname' => 'Browse Page Music',
						'title' => 'Page Music',
						'description' => 'This is the page music.',
						'custom' => 1,
				));
				$page_id = $db->lastInsertId('engine4_core_pages');

				//CONTAINERS
				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'container',
						'name' => 'main',
						'parent_content_id' => Null,
						'order' => 2,
						'params' => '',
				));
				$container_id = $db->lastInsertId('engine4_core_content');

				//INSERT MAIN - MIDDLE CONTAINER
				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'container',
						'name' => 'middle',
						'parent_content_id' => $container_id,
						'order' => 6,
						'params' => '',
				));
				$middle_id = $db->lastInsertId('engine4_core_content');


				//INSERT MAIN - RIGHT CONTAINER
				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'container',
						'name' => 'right',
						'parent_content_id' => $container_id,
						'order' => 5,
						'params' => '',
				));
				$right_id = $db->lastInsertId('engine4_core_content');


				//INSERT TOP CONTAINER
				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'container',
						'name' => 'top',
						'parent_content_id' => Null,
						'order' => 1,
						'params' => '',
				));
				$top_id = $db->lastInsertId('engine4_core_content');


				//INSERT TOP- MIDDLE CONTAINER
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
						'type' => 'widget',
						'name' => 'sitepage.browsenevigation-sitepage',
						'parent_content_id' => $top_middle_id,
						'order' => 1,
						'params' => '{"title":"","titleCount":""}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagemusic.search-sitepagemusic',
						'parent_content_id' => $right_id,
						'order' => 3,
						'params' => '{"title":"","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagemusic.sitepage-music',
						'parent_content_id' => $middle_id,
						'order' => 2,
						'params' => '{"title":"","titleCount":""}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagemusic.homecomment-sitepagemusic',
						'parent_content_id' => $right_id,
						'order' => 4,
						'params' => '{"title":"Most Commented Playlists","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagemusic.sitepage-sponsoredmusic',
						'parent_content_id' => $right_id,
						'order' => 5,
						'params' => '{"title":"Sponsored Playlists","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagemusic.homelike-sitepagemusic',
						'parent_content_id' => $right_id,
						'order' => 6,
						'params' => '{"title":"Most Liked Playlists","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagemusic.homerecent-sitepagemusic',
						'parent_content_id' => $right_id,
						'order' => 7,
						'params' => '{"title":"Recent Page Playlists","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagemusic.homepopular-sitepagemusic',
						'parent_content_id' => $right_id,
						'order' => 8,
						'params' => '{"title":"Popular Page Playlists","titleCount":"true"}',
				));

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
				$rowinfo = $select->query()->fetch();
				if ( $infomation && $rowinfo ) {
					$db->insert('engine4_core_content', array(
							'page_id' => $page_id,
							'type' => 'widget',
							'name' => 'sitepage.page-ads',
							'parent_content_id' => $right_id,
							'order' => 9,
							'params' => '{"title":"","titleCount":""}',
					));
				}
			}
      else {
        $db->update('engine4_core_pages', array('name' => 'sitepagemusic_playlist_browse'), array('name = ?' => 'sitepagemusic_playlist_musiclist'));
      }

			$db = $this->getDb();
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
				$rowinfo = $select->query()->fetch();
				if ( $infomation && $rowinfo ) {
					$db->insert('engine4_core_content', array(
							'page_id' => $page_id,
							'type' => 'widget',
							'name' => 'sitepage.page-ads',
							'parent_content_id' => $right_id,
							'order' => 10,
							'params' => '{"title":"","titleCount":""}',
					));
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
    }
    
    $select = new Zend_Db_Select($db);
		$select
					->from('engine4_core_modules')
					->where('name = ?', 'sitepagemusic')
					->where('version < ?', '4.2.1');
		$is_enabled_music = $select->query()->fetchObject();
		if($is_enabled_music) {
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
      $featuredColumn = $db->query("SHOW COLUMNS FROM engine4_sitepagemusic_playlists LIKE 'featured'")->fetch();
			if (empty($featuredColumn)) {
				$db->query("ALTER TABLE `engine4_sitepagemusic_playlists` ADD `featured` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `special`");
			}
      $db->update('engine4_core_pages', array('displayname' => 'Browse Page Music'), array('displayname = ?' => 'Page Music')); 
    }    

    //END THE WORK FOR MAKE WIDGETIZE PAGE OF MUSIC LISTING AND MUSIC VIEW PAGE
      $select = new Zend_Db_Select($db);
    $select
          ->from('engine4_core_settings')
          ->where('name = ?', 'sitepage.feed.type');
  $info = $select->query()->fetch();
    $enable = 1;
    if (!empty($info))
      $enable = $info['value'];
    $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`, `is_object_thumb`) VALUE("sitepagemusic_admin_new", "sitepagemusic", "{item:$object} created a new playlist {var:$linked_music_title}:", ' . $enable . ', 6, 2, 1, 1, 1, 1)');
    

		$select = new Zend_Db_Select($db);
		$select
					->from('engine4_core_modules')
					->where('name = ?', 'sitemobile')
					->where('enabled = ?', 1);
		$is_sitemobile_object = $select->query()->fetchObject();
		if($is_sitemobile_object)  {
			include APPLICATION_PATH . "/application/modules/Sitepagemusic/controllers/license/mobileLayoutCreation.php";
		}
    
    $playlistTable = $db->query('SHOW TABLES LIKE \'engine4_sitepagemusic_playlists\'')->fetch();
    if(!empty($playlistTable)) {    
        $featuredIndex = $db->query("SHOW INDEX FROM `engine4_sitepagemusic_playlists` WHERE Key_name = 'featured'")->fetch();   
        if(empty($featuredIndex)) {
          $db->query("ALTER TABLE `engine4_sitepagemusic_playlists` ADD INDEX ( `featured` )");
        }      
    }
    $this->setActivityFeeds();
    parent::onInstall();
  }
  
   public function onPostInstall() {

    $db = $this->getDb();
		$select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_modules')
            ->where('name = ?', 'sitemobile')
            ->where('enabled = ?', 1);
    $is_sitemobile_object = $select->query()->fetchObject();
    if(!empty($is_sitemobile_object)) {
			$db->query("INSERT IGNORE INTO `engine4_sitemobile_modules` (`name`, `visibility`) VALUES
('sitepagemusic','1')");
			$select = new Zend_Db_Select($db);
			$select
							->from('engine4_sitemobile_modules')
							->where('name = ?', 'sitepagemusic')
							->where('integrated = ?', 0);
			$is_sitemobile_object = $select->query()->fetchObject();
      if($is_sitemobile_object)  {
				$actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
				$controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
				if($controllerName == 'manage' && $actionName == 'install') {
          $view = new Zend_View();
					$baseUrl = ( !empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"]) ? 'https://':'http://') .  $_SERVER['HTTP_HOST'] . str_replace('install/', '', $view->url(array(), 'default', true));
					$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
					$redirector->gotoUrl($baseUrl . 'admin/sitemobile/module/enable-mobile/enable_mobile/1/name/sitepagemusic/integrated/0/redirect/install');
				} 
      }
    } 
    
    //Work for the word changes in the page plugin .csv file.
    $actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
    $controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    if($controllerName == 'manage' && ($actionName == 'install' || $actionName == 'query')) {
      $view = new Zend_View();
      $baseUrl = ( !empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"]) ? 'https://':'http://') .  $_SERVER['HTTP_HOST'] . str_replace('install/', '', $view->url(array(), 'default', true));
      $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
      if ($actionName == 'install') {
        $redirector->gotoUrl($baseUrl . 'admin/sitepage/settings/language/redirect/install');
      } else {
        $redirector->gotoUrl($baseUrl . 'admin/sitepage/settings/language/redirect/query');
      }
    }
    
  } 
  
  private function getVersion() {
  
    $db = $this->getDb();

    $errorMsg = '';
    $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();

    $modArray = array(
      'sitepage' => '4.6.0p1'
    );
    
    $finalModules = array();
    foreach ($modArray as $key => $value) {
    		$select = new Zend_Db_Select($db);
		$select->from('engine4_core_modules')
					->where('name = ?', "$key")
					->where('enabled = ?', 1);
		$isModEnabled = $select->query()->fetchObject();
			if (!empty($isModEnabled)) {
				$select = new Zend_Db_Select($db);
				$select->from('engine4_core_modules',array('title', 'version'))
					->where('name = ?', "$key")
					->where('enabled = ?', 1);
				$getModVersion = $select->query()->fetchObject();

				$isModSupport = $this->checkVersion($getModVersion->version, $value);
				if (empty($isModSupport)) {
					$finalModules[] = $getModVersion->title;
				}
			}
    }

    foreach ($finalModules as $modArray) {
      $errorMsg .= '<div class="tip"><span style="background-color: #da5252;color:#FFFFFF;">Note: You do not have the latest version of the Directory / Pages Plugin. Please upgrade Directory / Pages Plugin on your website to the latest version available in your SocialEngineAddOns Client Area to enable its integration with "Directory / Pages Plugin".<br/> Please <a class="" href="' . $base_url . '/manage">Click here</a> to go Manage Packages.</span></div>';
    }

    return $errorMsg;
  }
      private function checkVersion($databaseVersion, $checkDependancyVersion) {
        $f = $databaseVersion;
        $s = $checkDependancyVersion;
        if (strcasecmp($f, $s) == 0)
            return -1;

        $fArr = explode(".", $f);
        $sArr = explode('.', $s);
        if (count($fArr) <= count($sArr))
            $count = count($fArr);
        else
            $count = count($sArr);

        for ($i = 0; $i < $count; $i++) {
            $fValue = $fArr[$i];
            $sValue = $sArr[$i];
            if (is_numeric($fValue) && is_numeric($sValue)) {
                if ($fValue > $sValue)
                    return 1;
                elseif ($fValue < $sValue)
                    return 0;
                else {
                    if (($i + 1) == $count) {
                        return -1;
                    } else
                        continue;
                }
            }
            elseif (is_string($fValue) && is_numeric($sValue)) {
                $fsArr = explode("p", $fValue);

                if ($fsArr[0] > $sValue)
                    return 1;
                elseif ($fsArr[0] < $sValue)
                    return 0;
                else {
                    return 1;
                }
            } elseif (is_numeric($fValue) && is_string($sValue)) {
                $ssArr = explode("p", $sValue);

                if ($fValue > $ssArr[0])
                    return 1;
                elseif ($fValue < $ssArr[0])
                    return 0;
                else {
                    return 0;
                }
            } elseif (is_string($fValue) && is_string($sValue)) {
                $fsArr = explode("p", $fValue);
                $ssArr = explode("p", $sValue);
                if ($fsArr[0] > $ssArr[0])
                    return 1;
                elseif ($fsArr[0] < $ssArr[0])
                    return 0;
                else {
                    if ($fsArr[1] > $ssArr[1])
                        return 1;
                    elseif ($fsArr[1] < $ssArr[1])
                        return 0;
                    else {
                        return -1;
                    }
                }
            }
        }
    }
  public function setActivityFeeds() {
    $db = $this->getDb();
    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_modules')
            ->where('name = ?', 'nestedcomment')
            ->where('enabled = ?', 1);
    $is_nestedcomment_object = $select->query()->fetchObject();
    if ($is_nestedcomment_object) {
        $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES ("nestedcomment_sitepagemusic_playlist", "sitepagemusic", \'{item:$subject} replied to a comment on {item:$owner}\'\'s page music playlist {item:$object:$title}: {body:$body}\', 1, 1, 1, 1, 1, 1)');
    }
  }  
}
?>