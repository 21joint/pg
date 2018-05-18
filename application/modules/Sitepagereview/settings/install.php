<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Installer extends Engine_Package_Installer_Module {

  function onPreInstall() {

		$db = $this->getDb();

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

			$table_exist = $db->query('SHOW TABLES LIKE \'engine4_sitepage_pages\'')->fetch();
			if(!empty($table_exist)) { 
				$column_exist = $db->query('SHOW COLUMNS FROM engine4_sitepage_pages LIKE \'rating\'')->fetch();
				if(empty($column_exist)) {
					$db->query('ALTER TABLE  `engine4_sitepage_pages` ADD  `rating` FLOAT NOT NULL DEFAULT  "0";');
				}
			}

      $PRODUCT_TYPE = 'sitepagereview';
      $PLUGIN_TITLE = 'Sitepagereview';
      $PLUGIN_VERSION = '4.9.4p1';
      $PLUGIN_CATEGORY = 'plugin';
      $PRODUCT_DESCRIPTION = 'Sitepagereview Plugin';
      $PRODUCT_TITLE = 'Directory / Pages - Reviews and Ratings Extension';
      $_PRODUCT_FINAL_FILE = 0;
      $sitepage_plugin_version = '4.8.6';
      $SocialEngineAddOns_version = '4.8.9p12';
      $file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
      $is_file = file_exists($file_path);
      if (empty($is_file)) {
        include APPLICATION_PATH . "/application/modules/Sitepage/controllers/license/license4.php";
      } else {
        include $file_path;
      }

      $pageTime = time();
      $db->query("INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
			('sitepagereview.basetime', $pageTime ),
			('sitepagereview.isvar', 0 ),
			('sitepagereview.filepath', 'Sitepagereview/controllers/license/license2.php');");

      //PUT SITEPAGE REVIEWS WIDGET IN ADMIN CONTENT TABLE
      $select = new Zend_Db_Select($db);
      $select
              ->from('engine4_core_modules')
              ->where('name = ?', 'sitepagereview')
              ->where('version <= ?', '4.1.5p1');
      $is_enabled = $select->query()->fetchObject();
      if (!empty($is_enabled)) {

        $select = new Zend_Db_Select($db);
        $select_page = $select
                        ->from('engine4_core_pages', 'page_id')
                        ->where('name = ?', 'sitepage_index_view')
                        ->limit(1);
        $page = $select_page->query()->fetchAll();
        if (!empty($page)) {
          $page_id = $page[0]['page_id'];
          $select = new Zend_Db_Select($db);
          $select_content = $select
                          ->from('engine4_sitepage_admincontent')
                          ->where('page_id = ?', $page_id)
                          ->where('type = ?', 'widget')
                          ->where('name = ?', 'sitepagereview.profile-sitepagereviews')
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
              $select_middle = $select
                              ->from('engine4_sitepage_admincontent')
                              ->where('parent_content_id = ?', $container_id)
                              ->where('type = ?', 'container')
                              ->where('name = ?', 'middle')
                              ->limit(1);
              $middle = $select_middle->query()->fetchAll();
              if (!empty($middle)) {
                $middle_id = $middle[0]['admincontent_id'];
                $select = new Zend_Db_Select($db);
                $select_tab = $select
                                ->from('engine4_sitepage_admincontent')
                                ->where('type = ?', 'widget')
                                ->where('name = ?', 'core.container-tabs')
                                ->where('page_id = ?', $page_id)
                                ->limit(1);
                $tab = $select_tab->query()->fetchAll();
                $tab_id = 0;
                if (!empty($tab)) {
                  $tab_id = $tab[0]['admincontent_id'];
                } else {
                  $tab_id = $middle_id;
                }

                $db->insert('engine4_sitepage_admincontent', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepagereview.profile-sitepagereviews',
                    'parent_content_id' => $tab_id,
                    'order' => 113,
                    'params' => '{"title":"Reviews","titleCount":"true"}',
                ));
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
                            ->where('name = ?', 'left')
                            ->limit(1);
            $left = $select_left->query()->fetchAll();
            if (!empty($left)) {
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
      }
      parent::onPreInstall();
    } elseif (!empty($check_sitepage) && empty($sitepage_is_active)) {
      $baseUrl = $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getBaseUrl();
      $url_string = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
      if (strstr($url_string, "manage/install")) {
        $calling_from = 'install';
      } else if (strstr($url_string, "manage/query")) {
        $calling_from = 'queary';
      }
      $explode_base_url = explode("/", $baseUrl);
      foreach ($explode_base_url as $url_key) {
        if ($url_key != 'install') {
          $core_final_url .= $url_key . '/';
        }
      }

      return $this->_error("<span style='color:red'>Note: You have installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> but not activated it on your site yet. Please activate it first before installing the Directory / Pages - Reviews and Ratings Extension.</span><br/> <a href='" . 'http://' . $core_final_url . "admin/sitepage/settings/readme'>Click here</a> to activate the Directory / Pages Plugin.");
    }
    else {
      $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
      return $this->_error("<span style='color:red'>Note: You have not installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> on your site yet. Please install it first before installing the <a href='http://www.socialengineaddons.com/pageextensions/socialengine-directory-pages-reviews-and-ratings' target='_blank'>Directory / Pages - Reviews and Ratings Extension</a>.</span><br/> <a href='" . $base_url . "/manage'>Click here</a> to go Manage Packages.");
    }
  }

	function onInstall() {

    $db = $this->getDb();

		//CHECK THAT review_count COLUMN EXIST OR NOT IN PAGE TABLE
		$column_exist = $db->query("SHOW COLUMNS FROM engine4_sitepage_pages LIKE 'review_count'")->fetch();
		$table_exist = $db->query("SHOW TABLES LIKE 'engine4_sitepagereview_reviews'")->fetch();
		if (!empty($table_exist)) {
			if(empty($column_exist)){
				//ADD review_count COLUMN TO PAGE TABLE
				$db->query("ALTER TABLE `engine4_sitepage_pages` ADD `review_count` INT( 11 ) NOT NULL DEFAULT '0'  AFTER `like_count`");

				//FETCH PAGES
				$pages = $db->select()->from('engine4_sitepage_pages', 'page_id')->query()->fetchAll();

				if (!empty($pages)) {
					foreach($pages as $page)
					{
						$page_id = $page['page_id'];

						if(!empty($page_id)) {

							//GET TOTAL REVIEWS CORROSPONDING TO PAGE ID
							$total_reviews = $db->select()
															->from('engine4_sitepagereview_reviews', array('COUNT(*) AS count'))
															->where('page_id = ?', $page_id)
															->limit(1)
															->query()
															->fetchColumn();

							if(!empty($total_reviews)) {
								//UPDATE TOTAL REVIEWS IN PAGE TABLE
								$db->update('engine4_sitepage_pages', array('review_count' => $total_reviews), array('page_id = ?' => $page_id));
							}
						}
					}
				}
			}

			$featuredColumn = $db->query("SHOW COLUMNS FROM engine4_sitepagereview_reviews LIKE 'featured'")->fetch();
			if(empty($featuredColumn)) {
				$db->query("ALTER TABLE `engine4_sitepagereview_reviews` ADD `featured` TINYINT( 2 ) NOT NULL DEFAULT '0'");
			}
		}

		//REMOVED WIDGET SETTING TAB FROM ADMIN PANEL
    $select = new Zend_Db_Select($db);
    $select->from('engine4_core_modules')
           ->where('name = ?', 'sitepagereview')
           ->where('version <= ?', '4.1.7');
    $is_enabled = $select->query()->fetchObject();
    if (!empty($is_enabled)) {
			$widget_names = array('like', 'comment', 'popular', 'recent', 'topratedpages');

			foreach($widget_names as $widget_name) {

				$widget_type = $widget_name;

				$widget_name = 'sitepagereview.'.$widget_name.'-sitepagereviews';
				
				if($widget_name == 'sitepagereview.topratedpages-sitepagereviews') {
					$setting_name = 'sitepagereview.rate.widgets';
				}
				else {
					$setting_name = 'sitepagereview.'.$widget_type.'.widgets';
				}

				$total_items = $db->select()
												->from('engine4_core_settings', array('value'))
												->where('name = ?', $setting_name)
												->limit(1)
												->query()
												->fetchColumn();

				if(empty($total_items)) {
					$total_items = 3;
				}

				//WORK FOR CORE CONTENT PAGES
				$select = new Zend_Db_Select($db);
				$select->from('engine4_core_content', array('name', 'params', 'content_id'))->where('name = ?', $widget_name);
				$widgets = $select->query()->fetchAll();
				foreach($widgets as $widget) { 
					$explode_params = explode('}',$widget['params']);
					if(!empty($explode_params[0]) && !strstr($explode_params[0], '"itemCount"')) {
						$params = $explode_params[0].',"itemCount":"'.$total_items.'"}';

						$db->update('engine4_core_content', array('params' => $params), array('content_id = ?' => $widget['content_id'], 'name = ?' => $widget_name));
					}
				}

				//WORK FOR ADMIN USER CONTENT PAGE
				$select = new Zend_Db_Select($db);
				$select->from('engine4_sitepage_admincontent', array('name', 'params', 'admincontent_id'))->where('name = ?', $widget_name);
				$widgets = $select->query()->fetchAll();
				foreach($widgets as $widget) {
					$explode_params = explode('}',$widget['params']);
					if(!empty($explode_params[0]) && !strstr($explode_params[0], '"itemCount"')) {
						$params = $explode_params[0].',"itemCount":"'.$total_items.'"}';

						$db->update('engine4_sitepage_admincontent', array('params' => $params), array('admincontent_id = ?' => $widget['admincontent_id'], 'name = ?' => $widget_name));
					}
				}

				//WORK FOR USER CONTENT PAGES
				$select = new Zend_Db_Select($db);
				$select->from('engine4_sitepage_content', array('name', 'params', 'content_id'))->where('name = ?', $widget_name);
				$widgets = $select->query()->fetchAll();
				foreach($widgets as $widget) {
					$explode_params = explode('}',$widget['params']);
					if(!empty($explode_params[0]) && !strstr($explode_params[0], '"itemCount"')) {
						$params = $explode_params[0].',"itemCount":"'.$total_items.'"}';

						$db->update('engine4_sitepage_content', array('params' => $params), array('content_id = ?' => $widget['content_id'], 'name = ?' => $widget_name));
					}
				}
			}
		}

    //START THE WORK FOR MAKE WIDGETIZE PAGE OF REVIEWS LISTING AND REVIEW VIEW PAGE
    $select = new Zend_Db_Select($db);
		$select
						->from('engine4_core_modules')
						->where('name = ?', 'sitepagereview')
						->where('version < ?', '4.2.0');
		$is_enabled = $select->query()->fetchObject();
    if(!empty($is_enabled)){
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

			$select = new Zend_Db_Select($db);
			$select
							->from('engine4_core_pages')
							->where('name = ?', 'sitepagereview_index_reviewlist')
							->limit(1);
			;
			$info = $select->query()->fetch();

      $select = new Zend_Db_Select($db);
			$select
							->from('engine4_core_pages')
							->where('name = ?', 'sitepagereview_index_browse')
							->limit(1);
			;
			$info_browse = $select->query()->fetch();

			if ( empty($info) && empty($info_browse) ) {
				$db->insert('engine4_core_pages', array(
						'name' => 'sitepagereview_index_browse',
						'displayname' => 'Browse Page Reviews',
						'title' => 'Page Reviews',
						'description' => 'This is the page reviews.',
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
						'name' => 'sitepagereview.search-sitepagereview',
						'parent_content_id' => $right_id,
						'order' => 3,
						'params' => '{"title":"","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagereview.sitepage-review',
						'parent_content_id' => $middle_id,
						'order' => 2,
						'params' => '{"title":"Reviews","titleCount":""}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagereview.featured-sitepagereviews',
						'parent_content_id' => $right_id,
						'order' => 4,
						'params' => '{"title":"Featured Reviews","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagereview.homecomment-sitepagereviews',
						'parent_content_id' => $right_id,
						'order' => 5,
						'params' => '{"title":"Most Commented Reviews","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagereview.site-popular-reviews',
						'parent_content_id' => $right_id,
						'order' => 6,
						'params' => '{"title":"Most Popular Reviews","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagereview.homelike-sitepagereviews',
						'parent_content_id' => $right_id,
						'order' => 7,
						'params' => '{"title":"Most Liked Reviews","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagereview.reviewer-sitepagereviews',
						'parent_content_id' => $right_id,
						'order' => 8,
						'params' => '{"title":"Top Reviewers","titleCount":"true"}',
				));


				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagereview.review-of-the-day',
						'parent_content_id' => $right_id,
						'order' => 9,
						'params' => '{"title":"Review of the Day","titleCount":"true"}',
				));

				$db->insert('engine4_core_content', array(
							'page_id' => $page_id,
							'type' => 'widget',
							'name' => 'sitepagereview.recent-sitepagereviews',
							'parent_content_id' => $right_id,
							'order' => 10,
							'params' => '{"title":"Recent Reviews","titleCount":"true"}',
					));

				if ( $infomation && $rowinfo ) {
					$db->insert('engine4_core_content', array(
							'page_id' => $page_id,
							'type' => 'widget',
							'name' => 'sitepage.page-ads',
							'parent_content_id' => $right_id,
							'order' => 11,
							'params' => '{"title":"","titleCount":""}',
					));
				}
			}
      else {
        $db->update('engine4_core_pages', array('name' => 'sitepagereview_index_browse'), array('name = ?' => 'sitepagereview_index_reviewlist'));
      }

			$db = $this->getDb();
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

				// right column
				$db->insert('engine4_core_content', array(
						'page_id' => $page_id,
						'type' => 'widget',
						'name' => 'sitepagereview.sitepage-review-detail',
						'parent_content_id' => $right_id,
						'order' => 1,
						'params' => '{"title":"Review Details"}',
				));

				if ( $infomation && $rowinfo ) {
					$db->insert('engine4_core_content', array(
							'page_id' => $page_id,
							'type' => 'widget',
							'name' => 'sitepage.page-ads',
							'parent_content_id' => $right_id,
							'order' => 2,
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
    }

    $select = new Zend_Db_Select($db);
		$select
						->from('engine4_core_modules')
						->where('name = ?', 'sitepagereview')
						->where('version < ?', '4.2.1');
		$is_enabled = $select->query()->fetchObject();
    if($is_enabled) {
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
      $db->update('engine4_core_pages', array('displayname' => 'Browse Page Reviews'), array('displayname = ?' => 'Page Reviews')); 
    }
    //END THE WORK FOR MAKE WIDGETIZE PAGE OF REVIEWS LISTING AND REVIEW VIEW PAGE

		$select = new Zend_Db_Select($db);
		$select
				->from('engine4_core_modules')
				->where('name = ?', 'sitemobile')
				->where('enabled = ?', 1);
		$is_sitemobile_object = $select->query()->fetchObject();
		if($is_sitemobile_object)  {
			include APPLICATION_PATH . "/application/modules/Sitepagereview/controllers/license/mobileLayoutCreation.php";
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
('sitepagereview','1')");
			$select = new Zend_Db_Select($db);
			$select
							->from('engine4_sitemobile_modules')
							->where('name = ?', 'sitepagereview')
							->where('integrated = ?', 0);
			$is_sitemobile_object = $select->query()->fetchObject();
      if($is_sitemobile_object)  {
				$actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
				$controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
				if($controllerName == 'manage' && $actionName == 'install') {
          $view = new Zend_View();
					$baseUrl = ( !empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"]) ? 'https://':'http://') .  $_SERVER['HTTP_HOST'] . str_replace('install/', '', $view->url(array(), 'default', true));
					$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
					$redirector->gotoUrl($baseUrl . 'admin/sitemobile/module/enable-mobile/enable_mobile/1/name/sitepagereview/integrated/0/redirect/install');
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

  public function setActivityFeeds() {
    $db = $this->getDb();
    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_modules')
            ->where('name = ?', 'nestedcomment')
            ->where('enabled = ?', 1);
    $is_nestedcomment_object = $select->query()->fetchObject();
    if ($is_nestedcomment_object) {
        $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES ("nestedcomment_sitepagereview_review", "sitepagereview", \'{item:$subject} replied to a comment on {item:$owner}\'\'s page review {item:$object:$title}: {body:$body}\', 1, 1, 1, 1, 1, 1)');
    }
  }    
}