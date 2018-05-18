<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageform_Installer extends Engine_Package_Installer_Module {

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
      $PRODUCT_TYPE = 'sitepageform';
      $PLUGIN_TITLE = 'Sitepageform';
      $PLUGIN_VERSION = '4.9.4p1';
      $PLUGIN_CATEGORY = 'plugin';
      $PRODUCT_DESCRIPTION = 'Sitepageform Plugin';
      $PRODUCT_TITLE = 'Directory / Pages - Form Extension';
      $_PRODUCT_FINAL_FILE = 0;
      $sitepage_plugin_version = '4.8.5';
      $SocialEngineAddOns_version = '4.8.9p12';
      $file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
      $is_file = file_exists($file_path);
      if (empty($is_file)) {
        include APPLICATION_PATH . "/application/modules/Sitepage/controllers/license/license4.php";
      } else {
        include $file_path;
      }

      $select = new Zend_Db_Select($db);
      $select
              ->from('engine4_core_modules')
              ->where('name = ?', 'sitepageform');
      $check_sitepageform = $select->query()->fetchObject();
      if (empty($check_sitepageform) && !empty($sitepage_is_active)) {
        $db->query('DROP TABLE IF EXISTS `engine4_sitepageform_fields_options`;');
        $db->query('
					CREATE TABLE `engine4_sitepageform_fields_options` (
						`option_id` int(11) NOT NULL auto_increment,
						`field_id` int(11) NOT NULL,
						`label` varchar(255) NOT NULL,
						`order` smallint(6) NOT NULL default "999",
						PRIMARY KEY  (`option_id`),
						KEY `field_id` (`field_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci');

        $db->query('DROP TABLE IF EXISTS `engine4_sitepageform_sitepageforms`;');
        $db->query('
							CREATE TABLE IF NOT EXISTS `engine4_sitepageform_sitepageforms` (
								`sitepageform_id` int(11) NOT NULL AUTO_INCREMENT,
								`page_id` int(11) NOT NULL,
								`title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT "Leave your Feedback",
								`description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
								`status` int(2) NOT NULL DEFAULT "1",
								`pageformactive` int(2) NOT NULL DEFAULT "1",
								`activeyourname` int(2) NOT NULL DEFAULT "1",
								`activeemail` int(2) NOT NULL DEFAULT "1",
								`activeemailself` int(2) NOT NULL DEFAULT "1",
								`activemessage` int(2) NOT NULL DEFAULT "1",
								PRIMARY KEY (`sitepageform_id`),
                KEY `page_id` (`page_id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
												');

        $db->query('DROP TABLE IF EXISTS `engine4_sitepageform_pagequetions`;');
        $db->query('
						CREATE TABLE IF NOT EXISTS `engine4_sitepageform_pagequetions` (
						`pagequetion_id` int(11) NOT NULL AUTO_INCREMENT,
						`option_id` int(11) NOT NULL,
						`page_id` int(11) NOT NULL,
						PRIMARY KEY (`pagequetion_id`),
            KEY `page_id` (`page_id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }

      $pageTime = time();
      $db->query("INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
			('sitepageform.basetime', $pageTime ),
			('sitepageform.isvar', 0 ),
			('sitepageform.filepath', 'Sitepageform/controllers/license/license2.php');");

      //PUT SITEPAGE FORM WIDGET IN ADMIN CONTENT TABLE
      $select = new Zend_Db_Select($db);
      $select
              ->from('engine4_core_modules')
              ->where('name = ?', 'sitepageform')
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
                          ->where('name = ?', 'sitepageform.sitepage-viewform')
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
                    'name' => 'sitepageform.sitepage-viewform',
                    'parent_content_id' => $tab_id,
                    'order' => 114,
                    'params' => '{"title":"Form"}',
                ));
              }
            }
          }
        }
      }
      
      $table_exist = $db->query("SHOW TABLES LIKE 'engine4_sitepageform_sitepageforms'")->fetch();
      if (!empty($table_exist)) {
        //ADD THE INDEX FROM THE "engine4_sitepageform_sitepageforms" TABLE
        $pageIdColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepageform_sitepageforms` WHERE Key_name = 'page_id'")->fetch();

        if( empty($pageIdColumnIndex)) {
          $db->query("ALTER TABLE `engine4_sitepageform_sitepageforms` ADD INDEX ( `page_id` );");
        }    
      }
      
      $table_exist = $db->query("SHOW TABLES LIKE 'engine4_sitepageform_pagequetions'")->fetch();
      if (!empty($table_exist)) {
        //ADD THE INDEX FROM THE "engine4_sitepageform_pagequetions" TABLE
        $pageIdQuestionColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepageform_pagequetions` WHERE Key_name = 'page_id'")->fetch();

        if( empty($pageIdQuestionColumnIndex)) {
          $db->query("ALTER TABLE `engine4_sitepageform_pagequetions` ADD INDEX ( `page_id` );");
        } 
      }

			$select = new Zend_Db_Select($db);
			$select
						->from('engine4_core_modules')
						->where('name = ?', 'sitemobile')
						->where('enabled = ?', 1);
			$is_sitemobile_object = $select->query()->fetchObject();
			if($is_sitemobile_object)  {
					include APPLICATION_PATH . "/application/modules/Sitepageform/controllers/license/mobileLayoutCreation.php";
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

      return $this->_error("<span style='color:red'>Note: You have installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> but not activated it on your site yet. Please activate it first before installing the Directory / Pages - Form Extension.</span><br/> <a href='" . 'http://' . $core_final_url . "admin/sitepage/settings/readme'>Click here</a> to activate the Directory / Pages Plugin.");
    }
    else {
      $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
      return $this->_error("<span style='color:red'>Note: You have not installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> on your site yet. Please install it first before installing the <a href='http://www.socialengineaddons.com/pageextensions/socialengine-directory-pages-form' target='_blank'>Directory / Pages - Form Extension</a>.</span><br/> <a href='" . $base_url . "/manage'>Click here</a> to go Manage Packages.");
    }
  }

  function onInstall() {

    $db = $this->getDb();
    $column_tab_exist = $db->query('SHOW COLUMNS FROM engine4_sitepageform_sitepageforms LIKE \'offer_tab_name\'')->fetch();
		if (empty($column_tab_exist)) {
			$db->query('ALTER TABLE `engine4_sitepageform_sitepageforms` ADD `offer_tab_name` VARCHAR( 32 ) NOT NULL AFTER `activemessage`');
		}

		$select = new Zend_Db_Select($db);
		$select
						->from('engine4_core_modules')
						->where('name = ?', 'sitepageform')
						->where('enabled = ?', 1);
		$is_enabled = $select->query()->fetchObject();
		
		if(!empty($is_enabled)) {
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
		parent::onInstall();
  }
  
  function onEnable() {
    $db = $this->getDb();
    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_settings')
            ->where('name = ?', 'sitepageform.isActivate')
            ->limit(1);

    $sitepageform_settings = $select->query()->fetchAll();
    if (!empty($sitepageform_settings)) {
      $select = new Zend_Db_Select($db);
      $select_content = $select
                      ->from('engine4_sitepageform_pagequetions');
      $content = $select_content->query()->fetchAll();
      if (!empty($content)) {
				$count_result = count($content);
				$page_id = '';
				$page_id.= $content[0]['page_id'];
				$i = 1;
				while ($i < $count_result) {
					$page_id.= ',' . $content[$i]['page_id'];
					$i++;
				}
				$select = new Zend_Db_Select($db);
				$select_sitepage = $select
												->from('engine4_sitepage_pages')
												->where('engine4_sitepage_pages' . '.page_id NOT IN (' . $page_id . ')');
				$sitepage_result = $select_sitepage->query()->fetchAll();
				if (!empty($sitepage_result)) {
					foreach ($sitepage_result as $key => $value) {
						$page_id = $value['page_id'];
						$values = $value ['title'];
						$db->insert('engine4_sitepageform_fields_options', array(
								'field_id' => 1,
								'label' => $values,
						));
						$option_id = $db->lastInsertId('engine4_sitepageform_fields_options');
						$select = new Zend_Db_Select($db);
						$select_content = $select
														->from('engine4_sitepageform_pagequetions')
														->where('page_id = ?', $page_id)
														->where('option_id = ?', $option_id)
														->limit(1);
						$content = $select_content->query()->fetchAll();
						if (empty($content)) {
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
    }
    parent::onEnable();
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
('sitepageform','1')");
			$select = new Zend_Db_Select($db);
			$select
							->from('engine4_sitemobile_modules')
							->where('name = ?', 'sitepageform')
							->where('integrated = ?', 0);
			$is_sitemobile_object = $select->query()->fetchObject();
      if($is_sitemobile_object)  {
				$actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
				$controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
				if($controllerName == 'manage' && $actionName == 'install') {
          $view = new Zend_View();
					$baseUrl = ( !empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"]) ? 'https://':'http://') .  $_SERVER['HTTP_HOST'] . str_replace('install/', '', $view->url(array(), 'default', true));
					$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
					$redirector->gotoUrl($baseUrl . 'admin/sitemobile/module/enable-mobile/enable_mobile/1/name/sitepageform/integrated/0/redirect/install');
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

}
?>