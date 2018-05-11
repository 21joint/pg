<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Installer extends Engine_Package_Installer_Module
{

  function onPreInstall() {
		$PRODUCT_TYPE = 'feedback';
		$PLUGIN_TITLE = 'Feedback';
		$PLUGIN_VERSION = '4.9.2';
		$PLUGIN_CATEGORY = 'plugin';
		$PRODUCT_DESCRIPTION = 'Collect and act upon valuable feedback and ideas from your community.';
		$_PRODUCT_FINAL_FILE = 'license3.php';
		$_BASE_FILE_NAME = 0;
    $PRODUCT_TITLE = 'Feedback Plugin';
    $SocialEngineAddOns_version = '4.8.6p1';
    $file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
    $is_file = file_exists($file_path);
    if (empty($is_file)) {
      include_once APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/license4.php";
    } else {
			if( !empty($_PRODUCT_FINAL_FILE) ) {
				include_once APPLICATION_PATH . '/application/modules/' . $PLUGIN_TITLE . '/controllers/license/' . $_PRODUCT_FINAL_FILE;
			}
      $db = $this->getDb();
      $select = new Zend_Db_Select($db);
      $select->from('engine4_core_modules')->where('name = ?', $PRODUCT_TYPE);
      $is_Mod = $select->query()->fetchObject();
      if( empty($is_Mod) ) {
	include_once $file_path;
      }
    }
    parent::onPreInstall();
  }

  function onInstall()
  {
		$db     = $this->getDb();
		$db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='feedback';");
		$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("mobi_browse_feedback", "feedback", "Feedback", "", \'{"route":"feedback_browse"}\', "mobi_browse", "", 0, 0, 999)');

		$db->query("
		INSERT IGNORE INTO `engine4_authorization_permissions`
			SELECT
				level_id as `level_id`,
				'feedback' as `type`,
				'edit' as `name`,
				2 as `value`,
				NULL as `params`
			FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
		");

		$voteColumn = $db->query("SHOW COLUMNS FROM engine4_feedback_votes LIKE 'total_votes'")->fetch();
		if(!empty($voteColumn)) {
			$db->query("ALTER TABLE `engine4_feedback_votes` DROP `total_votes`");
		}

		$table_exist = $db->query("SHOW TABLES LIKE 'engine4_feedbacks'")->fetch();
		if(!empty($table_exist)) {
			$column_exist = $db->query("SHOW COLUMNS FROM engine4_feedbacks LIKE 'user_id'")->fetch();
			if(!empty($column_exist)) {
				$db->query("ALTER TABLE `engine4_feedbacks` DROP `user_id`");
			}

			$column_exist = $db->query("SHOW COLUMNS FROM engine4_feedbacks LIKE 'feedback_slug'")->fetch();
			if(!empty($column_exist)) {
				$db->query("ALTER TABLE `engine4_feedbacks` DROP `feedback_slug`;");
			}
		}

		$table_exist = $db->query("SHOW TABLES LIKE 'engine4_feedback_severities'")->fetch();
		if (!empty($table_exist)) {

			$column_exist = $db->query("SHOW COLUMNS FROM engine4_feedback_severities LIKE 'order'")->fetch();
			if (empty($column_exist)) {
				$db->query("ALTER TABLE `engine4_feedback_severities` ADD `order` INT( 3 ) NOT NULL DEFAULT '0'");
			}
		}

		$table_exist = $db->query("SHOW TABLES LIKE 'engine4_feedback_categories'")->fetch();
		if (!empty($table_exist)) {

			$column_exist = $db->query("SHOW COLUMNS FROM engine4_feedback_categories LIKE 'order'")->fetch();
			if (empty($column_exist)) {
				$db->query("ALTER TABLE `engine4_feedback_categories` ADD `order` INT( 3 ) NOT NULL DEFAULT '0'");
			}
		}
    
    $isTableExist = $db->query("SHOW TABLES LIKE 'engine4_feedbacks'")->fetch();
    if (!empty($isTableExist)) {
      $isIndexed = $db->query("SHOW INDEX FROM `engine4_feedbacks` WHERE Key_name = 'category_id'")->fetch();
      if (empty($isIndexed)) {
        $db->query("ALTER TABLE `engine4_feedbacks` ADD INDEX ( `category_id` );");
      }
      
      $isIndexed = $db->query("SHOW INDEX FROM `engine4_feedbacks` WHERE Key_name = 'stat_id'")->fetch();
      if (empty($isIndexed)) {
        $db->query("ALTER TABLE `engine4_feedbacks` ADD INDEX ( `stat_id` );");
      }
      
      $isIndexed = $db->query("SHOW INDEX FROM `engine4_feedbacks` WHERE Key_name = 'severity_id'")->fetch();
      if (empty($isIndexed)) {
        $db->query("ALTER TABLE `engine4_feedbacks` ADD INDEX ( `severity_id` );");
      }      
    }    
		
		$db->query("INSERT IGNORE INTO `engine4_seaocore_searchformsetting` (`module`, `name`, `display`, `order`, `label`) VALUES
		('feedback', 'orderby', 1, 4, 'Browse By'),
		('feedback', 'stat', 1, 3, 'Status'),
		('feedback', 'category', 1, 2, 'Category'),
		('feedback', 'search', 1, 1, 'Search Feedback');");
		
		$select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_settings')
            ->where('name = ?', 'feedback.isActivate')
            ->limit(1);
    $feedbackActive = $select->query()->fetch();
    
		if(!empty($feedbackActive)) {
			$db->query("INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
	('feedback_admin_main_form_search', 'feedback', 'Search Form Settings', '', '{\"route\":\"admin_default\",\"module\":\"feedback\",\"controller\":\"settings\",\"action\":\"form-search\"}', 'feedback_admin_main', '', 1, 0, 799)");
    }
		
		$select = new Zend_Db_Select($db);
		$select
						->from('engine4_core_pages')
						->where('name = ?', 'feedback_index_browse')
						->limit(1);
		;
		$info_browse = $select->query()->fetch();

		if (empty($info_browse) ) {
			$db->insert('engine4_core_pages', array(
					'name' => 'feedback_index_browse',
					'displayname' => 'Feedbacks - Browse Feedbacks',
					'title' => 'Browse Feedbacks',
					'description' => 'This is the feedback browse page.',
					'custom' => 0,
			));
			$page_id = $db->lastInsertId('engine4_core_pages');
      
      $count = 1;
      
			//INSERT TOP CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'top',
					'parent_content_id' => Null,
					'order' => $count++,
					'params' => '',
			));
			$top_id = $db->lastInsertId('engine4_core_content');      

			//CONTAINERS
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'main',
					'parent_content_id' => Null,
					'order' => $count++,
					'params' => '',
			));
			$container_id = $db->lastInsertId('engine4_core_content');
      
			//INSERT MAIN - RIGHT CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'right',
					'parent_content_id' => $container_id,
					'order' => $count++,
					'params' => '',
			));
			$right_id = $db->lastInsertId('engine4_core_content');
      
			//INSERT TOP- MIDDLE CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'middle',
					'parent_content_id' => $top_id,
					'order' => $count++,
					'params' => '',
			));
			$top_middle_id = $db->lastInsertId('engine4_core_content');      

			//INSERT MAIN - MIDDLE CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'middle',
					'parent_content_id' => $container_id,
					'order' => $count++,
					'params' => '',
			));
			$middle_id = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.navigation-feedbacks',
					'parent_content_id' => $top_middle_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":""}',
			));

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.search-feedback',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":"true"}',
			));

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.new-feedback',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":"true"}',
			));
      
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.recent-feedbacks',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"Most Voted Feedbacks","popularity":"total_votes"}',
			));      

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.browse-feedbacks',
					'parent_content_id' => $middle_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":""}',
			));

		}
    
		$select = new Zend_Db_Select($db);
		$select
						->from('engine4_core_pages')
						->where('name = ?', 'feedback_index_edit')
						->limit(1);
		;
		$info_edit = $select->query()->fetch();

		if (empty($info_edit) ) {
			$db->insert('engine4_core_pages', array(
					'name' => 'feedback_index_edit',
					'displayname' => 'Feedbacks - Edit Feedback Page',
					'title' => 'Edit Feedback Page',
					'description' => 'This page allows users to edit feedbacks.',
					'custom' => 0,
			));
			$page_id = $db->lastInsertId('engine4_core_pages');
      
      $count = 1;
      
			//INSERT TOP CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'top',
					'parent_content_id' => Null,
					'order' => $count++,
					'params' => '',
			));
			$top_id = $db->lastInsertId('engine4_core_content');      

			//CONTAINERS
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'main',
					'parent_content_id' => Null,
					'order' => $count++,
					'params' => '',
			));
			$container_id = $db->lastInsertId('engine4_core_content');
      
			//INSERT MAIN - RIGHT CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'right',
					'parent_content_id' => $container_id,
					'order' => $count++,
					'params' => '',
			));
			$right_id = $db->lastInsertId('engine4_core_content');
      
			//INSERT TOP- MIDDLE CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'middle',
					'parent_content_id' => $top_id,
					'order' => $count++,
					'params' => '',
			));
			$top_middle_id = $db->lastInsertId('engine4_core_content');      

			//INSERT MAIN - MIDDLE CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'middle',
					'parent_content_id' => $container_id,
					'order' => $count++,
					'params' => '',
			));
			$middle_id = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.navigation-feedbacks',
					'parent_content_id' => $top_middle_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":""}',
			));

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'core.content',
					'parent_content_id' => $middle_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":""}',
			));

		}
    
		$select = new Zend_Db_Select($db);
		$select
						->from('engine4_core_pages')
						->where('name = ?', 'feedback_index_view')
						->limit(1);
		;
		$info_view = $select->query()->fetch();

		if (empty($info_view) ) {
			$db->insert('engine4_core_pages', array(
					'name' => 'feedback_index_view',
					'displayname' => 'Feedbacks - Feedback View Page',
					'title' => 'Feedback View Page',
					'description' => 'This is the feedback view page.',
					'custom' => 0,
			));
			$page_id = $db->lastInsertId('engine4_core_pages');
      
      $count = 1;
      
			//INSERT TOP CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'top',
					'parent_content_id' => Null,
					'order' => $count++,
					'params' => '',
			));
			$top_id = $db->lastInsertId('engine4_core_content');      

			//CONTAINERS
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'main',
					'parent_content_id' => Null,
					'order' => $count++,
					'params' => '',
			));
			$container_id = $db->lastInsertId('engine4_core_content');
      
			//INSERT MAIN - RIGHT CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'right',
					'parent_content_id' => $container_id,
					'order' => $count++,
					'params' => '',
			));
			$right_id = $db->lastInsertId('engine4_core_content');     

			//INSERT MAIN - MIDDLE CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'middle',
					'parent_content_id' => $container_id,
					'order' => $count++,
					'params' => '',
			));
			$middle_id = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.owner-photo-feedback',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":"true"}',
			));

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.options-feedback',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":"true"}',
			));
      
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.search-box-feedbacks',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"Search Feedbacks"}',
			));

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.similar-feedbacks',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"FEEDBACK OF SAME CATEGORY","popularity":"views"}',
			));

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.owner-tags-feedbacks',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"%s\'s Tags"}',
			));  								   

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'core.content',
					'parent_content_id' => $middle_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":""}',
			));

		}    
    
		$select = new Zend_Db_Select($db);
		$select
						->from('engine4_core_pages')
						->where('name = ?', 'feedback_index_list')
						->limit(1);
		;
		$info_list = $select->query()->fetch();

		if (empty($info_list) ) {
			$db->insert('engine4_core_pages', array(
					'name' => 'feedback_index_list',
					'displayname' => 'Feedbacks - User\'s Feedbacks',
					'title' => 'User\'s Feedbacks',
					'description' => 'This page allows viewer to view the user\'s feedbacks.',
					'custom' => 0,
			));
			$page_id = $db->lastInsertId('engine4_core_pages');
      
      $count = 1;
      
			//INSERT TOP CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'top',
					'parent_content_id' => Null,
					'order' => $count++,
					'params' => '',
			));
			$top_id = $db->lastInsertId('engine4_core_content');      

			//CONTAINERS
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'main',
					'parent_content_id' => Null,
					'order' => $count++,
					'params' => '',
			));
			$container_id = $db->lastInsertId('engine4_core_content');
      
			//INSERT MAIN - RIGHT CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'right',
					'parent_content_id' => $container_id,
					'order' => $count++,
					'params' => '',
			));
			$right_id = $db->lastInsertId('engine4_core_content');     

			//INSERT MAIN - MIDDLE CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'middle',
					'parent_content_id' => $container_id,
					'order' => $count++,
					'params' => '',
			));
			$middle_id = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.owner-photo-feedback',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":"true"}',
			));
      
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.search-box-feedbacks',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"Search Feedbacks"}',
			));								   

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'core.content',
					'parent_content_id' => $middle_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":""}',
			));

		}    
    
		$select = new Zend_Db_Select($db);
		$select
						->from('engine4_core_pages')
						->where('name = ?', 'feedback_index_manage')
						->limit(1);
		;
		$info_manage = $select->query()->fetch();

		if (empty($info_manage) ) {
			$db->insert('engine4_core_pages', array(
					'name' => 'feedback_index_manage',
					'displayname' => 'Feedbacks - My Feedbacks',
					'title' => 'My Feedbacks',
					'description' => 'This page shows the user’s feedbacks.',
					'custom' => 0,
			));
			$page_id = $db->lastInsertId('engine4_core_pages');
      
      $count = 1;
      
			//INSERT TOP CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'top',
					'parent_content_id' => Null,
					'order' => $count++,
					'params' => '',
			));
			$top_id = $db->lastInsertId('engine4_core_content');      

			//CONTAINERS
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'main',
					'parent_content_id' => Null,
					'order' => $count++,
					'params' => '',
			));
			$container_id = $db->lastInsertId('engine4_core_content');
      
			//INSERT MAIN - RIGHT CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'right',
					'parent_content_id' => $container_id,
					'order' => $count++,
					'params' => '',
			));
			$right_id = $db->lastInsertId('engine4_core_content');
      
			//INSERT TOP- MIDDLE CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'middle',
					'parent_content_id' => $top_id,
					'order' => $count++,
					'params' => '',
			));
			$top_middle_id = $db->lastInsertId('engine4_core_content');      

			//INSERT MAIN - MIDDLE CONTAINER
			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'container',
					'name' => 'middle',
					'parent_content_id' => $container_id,
					'order' => $count++,
					'params' => '',
			));
			$middle_id = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.navigation-feedbacks',
					'parent_content_id' => $top_middle_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":""}',
			));

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'feedback.search-feedback',
					'parent_content_id' => $right_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":"true"}',
			));  

			$db->insert('engine4_core_content', array(
					'page_id' => $page_id,
					'type' => 'widget',
					'name' => 'core.content',
					'parent_content_id' => $middle_id,
					'order' => $count++,
					'params' => '{"title":"","titleCount":""}',
			));

		}    

    parent::onInstall();
  }
  
 //SITEMOBILE CODE TO CALL MY.SQL ON POST INSTALL
    public function onPostInstall() {
        $moduleName = 'feedback';
        $db = $this->getDb();
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitemobile')
                ->where('enabled = ?', 1);
        $is_sitemobile_object = $select->query()->fetchObject();
        if (!empty($is_sitemobile_object)) {
            $db->query("INSERT IGNORE INTO `engine4_sitemobile_modules` (`name`, `visibility`) VALUES
('$moduleName','1')");
            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_sitemobile_modules')
                    ->where('name = ?', $moduleName)
                    ->where('integrated = ?', 0);
            $is_sitemobile_object = $select->query()->fetchObject();
            if ($is_sitemobile_object) {
                $actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
                $controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
                if ($controllerName == 'manage' && $actionName == 'install') {
                    $view = new Zend_View();
                    $baseUrl = (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"]) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . str_replace('install/', '', $view->url(array(), 'default', true));
                    $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                    $redirector->gotoUrl($baseUrl . 'admin/sitemobile/module/enable-mobile/enable_mobile/1/name/' . $moduleName . '/integrated/0/redirect/install');
                }
            }
        }
    }

}