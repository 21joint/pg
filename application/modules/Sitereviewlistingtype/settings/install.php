<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereviewlistingtype
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereviewlistingtype_Installer extends Engine_Package_Installer_Module {

  function onPreinstall() {
		
    $db = $this->getDb();

    //CHECK THAT SITEPAGE PLUGIN IS ACTIVATED OR NOT
    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_settings')
            ->where('name = ?', 'sitereview.isActivate')
            ->limit(1);
    $sitereview_settings = $select->query()->fetchAll();
    $sitereview_is_active = !empty($sitereview_settings) ? $sitereview_settings[0]['value'] : 0;

    //CHECK THAT Reviews & Ratings Plugin IS INSTALLED OR NOT
    $select = new Zend_Db_Select($db);
    $isSitereviewEnabled = $select
            ->from('engine4_core_modules', 'enabled')
            ->where('name = ?', 'sitereview')
            ->where('enabled = ?', 1)
            ->query()
            ->fetchColumn()
    ;

    if (empty($isSitereviewEnabled)) {
      $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();

      return $this->_error("<span style='color:red'>Note: You have not installed the '<a href='http://www.socialengineaddons.com/socialengine-reviews-ratings-plugin' target='_blank'>Multiple Listing Types Plugin Core (Reviews & Ratings Plugin)</a>' on your site yet. Please install it first before installing the '<a href='http://www.socialengineaddons.com/reviewsextensions/socialengine-multiple-listing-types-extension' target='_blank'>Multiple Listing Types - Listing Type Creation Extension</a>'. <a href='" . $base_url . "/manage'>Click here</a> to go to Manage Packages.</span>");

    } else {
      if (empty($sitereview_is_active)) {
        $core_final_url = ''; 
        $baseUrl = $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getBaseUrl();
        $explode_base_url = explode("/", $baseUrl);
        foreach ($explode_base_url as $url_key) {
          if ($url_key != 'install') {
            $core_final_url .= $url_key . '/';
          }
        }
        return $this->_error("<span style='color:red'>Note: You have installed the 'Multiple Listing Types Plugin Core (Reviews & Ratings Plugin)' but not activated it on your site yet. Please activate it first before installing the 'Multiple Listing Types - Listing Type Creation Extension'.</span> <a href='" . 'http://' . $core_final_url . "admin/sitereview/settings/readme'>Click here</a> to activate the 'Multiple Listing Types Plugin Core (Reviews & Ratings Plugin)'.");
      }

      $PRODUCT_TYPE = 'sitereviewlistingtype';
      $PLUGIN_TITLE = 'Sitereviewlistingtype';
      $PLUGIN_VERSION = '4.8.9p1';
      $PLUGIN_CATEGORY = 'plugin';
      $PRODUCT_DESCRIPTION = 'Multiple Listing Types - Listing Type Creation Extension';
      $_PRODUCT_FINAL_FILE = 0;
      $SocialEngineAddOns_version = '4.8.9p12';
      $PRODUCT_TITLE = 'Multiple Listing Types - Listing Type Creation Extension';

      $file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
      $is_file = file_exists($file_path);

      if (empty($is_file)) {
        include_once APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/license3.php";
      } else {
        $select = new Zend_Db_Select($db);
        $select->from('engine4_core_modules')->where('name = ?', $PRODUCT_TYPE);
        $is_Mod = $select->query()->fetchObject();
        if (empty($is_Mod)) {
          include_once $file_path;
        }
      }
    }
    parent::onPreinstall();
  }

  function onInstall() {

    $db = $this->getDb();
    
    $db->query("INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
('SITEREVIEW_CHANGELISTINGTYPE_EMAIL', 'sitereviewlistingtype', '[host],[email],[list_title],[object_link],[listing_type],[new_listing_type],[list_title_with_link],[site_contact_us_link]');");

    //CODE FOR INCREASE THE SIZE OF engine4_core_menuitems FIELD label
    $type_array = $db->query("SHOW COLUMNS FROM engine4_core_menuitems LIKE 'label'")->fetch();
    if (!empty($type_array)) {
      $varchar = $type_array['Type'];
      $length_varchar = explode("(", $varchar);
      $length = explode(")", $length_varchar[1]);
      $length_type = $length[0];
      if ($length_type < 64) {
        $run_query = $db->query("ALTER TABLE `engine4_core_menuitems` CHANGE `label` `label` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ");
      }
    }       
    
    $db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='sitereviewlistingtype';");

    $db->update('engine4_core_menuitems', array('params' => '{"route":"admin_default","module":"sitereviewlistingtype","controller":"listingtypes","action":"manage"}'), array('name = ?' => 'sitereview_admin_main_listingtypes'));
    $db->update('engine4_core_menuitems', array('label' => 'SEAO - Reviews & Ratings - Multiple Listing Types Plugin'), array('name = ?' => 'core_admin_main_plugins_sitereview'));

    parent::onInstall();
  }

  function onEnable() {

    $db = $this->getDb();

    $db->update('engine4_core_menuitems', array('params' => '{"route":"admin_default","module":"sitereviewlistingtype","controller":"listingtypes","action":"manage"}'), array('name = ?' => 'sitereview_admin_main_listingtypes'));

    $db->update('engine4_core_menuitems', array('label' => 'SEAO - Reviews & Ratings - Multiple Listing Types Plugin'), array('name = ?' => 'core_admin_main_plugins_sitereview'));

    parent::onEnable();
  }

  function onDisable() {

    $db = $this->getDb();

    $db->update('engine4_core_menuitems', array('params' => '{"route":"admin_default","module":"sitereview","controller":"general","action":"listing-types"}'), array('name = ?' => 'sitereview_admin_main_listingtypes'));

    $db->update('engine4_core_menuitems', array('label' => 'SEAO - Reviews & Ratings Plugin'), array('name = ?' => 'core_admin_main_plugins_sitereview'));

    parent::onDisable();
  }

}
