<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageinvite
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageinvite_Installer extends Engine_Package_Installer_Module {

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
      $PRODUCT_TYPE = 'sitepageinvite';
      $PLUGIN_TITLE = 'Sitepageinvite';
      $PLUGIN_VERSION = '4.9.4p1';
      $PLUGIN_CATEGORY = 'plugin';
      $PRODUCT_DESCRIPTION = 'Sitepageinvite Plugin';
      $PRODUCT_TITLE = 'Directory / Pages - Inviter Extension';
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
      
      
      
//       $select = new Zend_Db_Select($db);
//    $select
//            ->from('engine4_core_settings', array('value'))
//            ->where('name = ?', 'pageinvite.show.webmail');
//    $inviteModules = $select->query()->fetchColumn();
//    if (!empty($inviteModules)) {
//      $webmail_values = unserialize($inviteModules);
//      //IF FACEBOOK_MAIL DOES NOT EXIST IN TABLE THEN INSERT THIS 
//      if (!in_array('facebook_mail', $webmail_values)) {
//        $webmail_values[] = 'facebook_mail';
//      }
//      //IF LINKEDIN MAIL DOES NOT EXIST IN TABLE THEN INSERT THIS  
//      if (!in_array('linkedin_mail', $webmail_values)) {
//        $webmail_values[] = 'linkedin_mail';
//      }
			 $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_settings', array('value'))
            ->where('name = ?', 'pageinvite.show.webmail');
    $inviteModules = $select->query()->fetchColumn();
    if (!empty($inviteModules)) {
      $webmail_values = unserialize($inviteModules);
      //IF FACEBOOK_MAIL DOES NOT EXIST IN TABLE THEN INSERT THIS 
      if (!in_array('facebook_mail', $webmail_values)) {
        $webmail_values[] = 'facebook_mail';
      }
      //IF LINKEDIN MAIL DOES NOT EXIST IN TABLE THEN INSERT THIS  
      if (!in_array('linkedin_mail', $webmail_values)) {
        $webmail_values[] = 'linkedin_mail';
      }
      //IF TWITTER MAIL DOES NOT EXIST IN TABLE THEN INSERT THIS  
      if (!in_array('twitter_mail', $webmail_values)) {
        $webmail_values[] = 'twitter_mail';
      }
      $webmail_values = serialize($webmail_values);


      $db->query("UPDATE `engine4_core_settings` SET `value` = '$webmail_values' WHERE `engine4_core_settings`.`name` = 'pageinvite.show.webmail' LIMIT 1;");
    } else {

      $db->query('INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
        ("pageinvite.show.webmail", \'a:7:{i:0;s:5:"gmail";i:1;s:5:"yahoo";i:2;s:11:"window_mail";i:3;s:3:"aol";i:4;s:13:"facebook_mail";i:5;s:13:"linkedin_mail";i:6;s:12:"twitter_mail";}\');'
      );
    }

      //MAKING A COLOMN IN THE INVITE TABLE

      $type_array = $db->query("SHOW COLUMNS FROM `engine4_invites` LIKE 'social_profileid'")->fetch();
      if (empty($type_array)) {
        $run_query = $db->query("ALTER TABLE `engine4_invites` ADD `social_profileid` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `new_user_id` ");
      }
      $pageTime = time();
      $db->query("INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
			('sitepageinvite.basetime', $pageTime ),
			('sitepageinvite.isvar', 0 ),
			('sitepageinvite.filepath', 'Sitepageinvite/controllers/license/license2.php');");

      parent::onPreInstall();
   // } 
  }
  elseif (!empty($check_sitepage) && empty($sitepage_is_active)) {
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
      
      return $this->_error("<span style='color:red'>Note: You have installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> but not activated it on your site yet. Please activate it first before installing the Directory / Pages - Inviter and Promotion Extension.</span><br/> <a href='" . 'http://' . $core_final_url . "admin/sitepage/settings/readme'>Click here</a> to activate the Directory / Pages Plugin.");
    }
    else {
      $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
      return $this->_error("<span style='color:red'>Note: You have not installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> on your site yet. Please install it first before installing the <a href='http://www.socialengineaddons.com/pageextensions/socialengine-directory-pages-inviter-and-promotion' target='_blank'>Directory / Pages - Inviter and Promotion Extension</a>.</span><br/> <a href='" . $base_url . "/manage'>Click here</a> to go Manage Packages.");
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
?>