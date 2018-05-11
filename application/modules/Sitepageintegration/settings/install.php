<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_Installer extends Engine_Package_Installer_Module {

  function onPreInstall() {
    //GET DB
    $db = $this->getDb();

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
            ->where('name = ?', 'sitepage')
            ->where('enabled = ?', 1);
    $check_sitepage = $select->query()->fetchObject();
    
    if ( !empty($check_sitepage) && !empty($sitepage_is_active) ) {
      $PRODUCT_TYPE = 'sitepageintegration';
      $PLUGIN_TITLE = 'Sitepageintegration';
      $PLUGIN_VERSION = '4.9.4';
      $PLUGIN_CATEGORY = 'plugin';
      $PRODUCT_DESCRIPTION = 'Directory / Pages - Multiple Listings and Products Showcase Extension';
      $_PRODUCT_FINAL_FILE = 0;
      $sitepage_plugin_version = '4.8.0';
      $SocialEngineAddOns_version = '4.8.9p12';
      $PRODUCT_TITLE = 'Directory / Pages - Multiple Listings and Products Showcase Extension';
      $file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
      $is_file = file_exists($file_path);
      if ( empty($is_file) ) {
        include APPLICATION_PATH . "/application/modules/Sitepage/controllers/license/license4.php";
      }else {
        $select = new Zend_Db_Select($db);
        $select->from('engine4_core_modules')->where('name = ?', $PRODUCT_TYPE);
        $is_Mod = $select->query()->fetchObject();
        if (empty($is_Mod)) {
          include_once $file_path;
        }
      }
    }elseif ( !empty($check_sitepage) && empty($sitepage_is_active) ) {
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
      
      return $this->_error("<span style='color:red'>Note: You have installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> but not activated it on your site yet. Please activate it first before installing the Directory / Pages - Multiple Listings and Products Showcase Extension.</span><br/> <a href='" . 'http://' . $core_final_url . "admin/sitepage/settings/readme'>Click here</a> to activate the Directory / Pages Plugin.");
    }
    else {
      $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
      return $this->_error("<span style='color:red'>Note: You have not installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> on your site yet. Please install it first before installing the <a href='http://www.socialengineaddons.com/pageextensions/socialengine-directory-pages-multiple-listings-products-showcase' target='_blank'>Directory / Pages - Multiple Listings and Products Showcase Extension</a>.</span><br/> <a href='" . $base_url . "/manage'>Click here</a> to go Manage Packages.");
    }
    
    parent::onPreInstall();
  }
  
  function onInstall() {

    $db = $this->getDb();
    
    $db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='sitepageintegration';");
    
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

    //CHECK SITEPAGE PLUGIN IS INSTALL OR NOT.
    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_modules')
            ->where('name = ?', 'sitepage');
    $check_sitepage = $select->query()->fetchAll();

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

    if (empty($check_sitepage)) {
      // Page plugin is not install at your site.
      return $this->_error('<span style="color:red">You have not installed the <a href="http://www.socialengineaddons.com/socialengine-directory-pages-plugin" target="_blank"> Directory / Pages Plugin </a> on your website. Please install and enable the <a href="http://www.socialengineaddons.com/socialengine-directory-pages-plugin" target="_blank"> Directory / Pages Plugin </a> before installing the <a href="http://www.socialengineaddons.com/socialengine-directory-businesses-plugin" target="_blank"> Directory / Pages - Multiple Listings and Products Showcase Extension </a>. If you have any questions then please file a support ticket for this from the "Support" section of your Client Area on SocialEngineAddOns.</span>');
    } else if (!empty($check_sitepage) && empty($check_sitepage[0]['enabled'])) {
      // Plugin not Enable at your site
      return $this->_error('<span style="color:red">You have installed the <a href="http://www.socialengineaddons.com/socialengine-directory-pages-plugin" target="_blank"> Directory / Pages Plugin </a> on your website, but have not enabled it yet. Please enabled the <a href="http://www.socialengineaddons.com/socialengine-directory-pages-plugin" target="_blank"> Directory / Pages Plugin </a> before installing the <a href="http://www.socialengineaddons.com/socialengine-directory-businesses-plugin" target="_blank"> Directory / Pages - Multiple Listings and Products Showcase Extension </a>. <a href="http://' .$core_final_url . 'install/manage/" target="_blank"> Click here </a> to enable the Directory / Pages Plugin. </span>');
    } else if (!empty($check_sitepage) && empty($sitepage_is_active)) {
      // Please activate page plugin
      return $this->_error('<span style="color:red">You have installed the <a href="http://www.socialengineaddons.com/socialengine-directory-pages-plugin" target="_blank"> Directory / Pages Plugin </a> on your website but have not activated it yet. Please activate the <a href="http://www.socialengineaddons.com/socialengine-directory-pages-plugin" target="_blank"> Directory / Pages Plugin </a> before installing the <a href="http://www.socialengineaddons.com/socialengine-directory-businesses-plugin" target="_blank"> Directory / Pages - Multiple Listings and Products Showcase Extension </a>. <a href="http://' . $core_final_url . 'admin/sitepage/settings/readme" target="_blank"> Click here </a> to activate the Directory / Pages Plugin. </span>');
    } else {
			//CHECK IF THE SITE REVIEW PLUGIN IS ALREADY INSTALLED:
			$select = new Zend_Db_Select($db);
			$select
				->from('engine4_core_modules')
				->where('name = ?', 'sitereview');
			$is_enabled = $select->query()->fetchObject();
			
			if (!empty($is_enabled)) {
				$select = new Zend_Db_Select($db);
				$select->from('engine4_sitereview_listingtypes', array('listingtype_id', 'title_singular'));
				$listingTypes = $select->query()->fetchAll();
				foreach ($listingTypes as $listingType) {
					$listingtype_id = $listingType['listingtype_id'];
					$listingtype_title = str_replace('"' , '\"', $listingType['title_singular']);
					$db->query("INSERT IGNORE INTO `engine4_sitepageintegration_mixsettings` (`module`, `resource_type`, `resource_id`, `item_title`, `enabled`) VALUES ('sitereview', 'sitereview_listing_$listingtype_id', 'listing_id', '$listingtype_title', 0);");
				}
			}

			$db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES ("integration_sitepage_page", "sitepageintegration", \'{item:$subject} has added your {var:$linkname} to {item:$object}.\', "0", "");');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitepage_sitebusiness_gutter_create", "sitepage", "Create New Business", "Sitepage_Plugin_Menus", "", "sitepage_gutter", "", 1, 0, 999);');
      
			$db->query("DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = 'list_gutter_create' AND `module` = 'sitepage';");
			
			$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitepage_list_gutter_create", "sitepage", "Post a new Listing", "Sitepage_Plugin_Menus", \'{"route":"list_general", "class":"buttonlink item_icon_list_listing","action":"create"}\', "sitepage_gutter", "", 0, 0, 999);');
			
      $db->query("DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` LIKE 'sitereview_gutter_create_%' AND `module` = 'sitepage' AND `menu` = 'sitepage_gutter';");

			$select = new Zend_Db_Select($db);
			$select
					->from('engine4_core_modules')
					->where('name = ?', 'sitereview');
			$is_enabled = $select->query()->fetchObject();

			if (!empty($is_enabled)) {
				$select = new Zend_Db_Select($db);
				$select->from('engine4_sitereview_listingtypes', array('listingtype_id', 'title_singular'));
				$listingTypes = $select->query()->fetchAll();
				foreach ($listingTypes as $listingType) {
				
					$listingtype_id = $listingType['listingtype_id'];
					$listingtype_title = ucfirst(str_replace('"' , '\"', $listingType['title_singular']));

					//ADD MENU FROM PAGE PLUGIN LEFT SIDE.
					$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name` , `module` , `label` , `plugin` ,`params`, `menu`, `enabled`, `custom`, `order`) VALUES ("sitepage_sitereview_gutter_create_'.$listingtype_id.'", "sitepage", "Post New ' . $listingtype_title .  ' ", \'Sitepage_Plugin_Menus::sitepagesitereviewGutterCreate\', \'{"route":"sitereview_general_listtype_'.$listingtype_id.'", "action":"create", "listing_id": "'.$listingtype_id.'", "class":"buttonlink item_icon_sitereview_listtype_'. $listingtype_id.'"}\', "sitepage_gutter", 1, 0, 999 )');
				}
			}
      
        $mixsettingsTable = $db->query('SHOW TABLES LIKE \'engine4_sitepageintegration_mixsettings\'')->fetch();
        if (!empty($mixsettingsTable)) {
          $moduleIdIndex = $db->query("SHOW INDEX FROM `engine4_sitepageintegration_mixsettings` WHERE Key_name = 'module'")->fetch();     
          if(empty($moduleIdIndex)) {
            $db->query("ALTER TABLE `engine4_sitepageintegration_mixsettings` ADD INDEX ( `module` )");
          }    
        }         

      parent::onInstall();
    }
  }
  
  public function onPostInstall() {
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