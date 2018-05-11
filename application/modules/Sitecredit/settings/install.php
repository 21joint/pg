<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Installer extends Engine_Package_Installer_Module {

    public function onPreinstall() {
        $getErrorMsg = $this->getVersion();
        if (!empty($getErrorMsg)) {
            return $this->_error($getErrorMsg);
        }
        $PRODUCT_TYPE = 'sitecredit';
        $PLUGIN_TITLE = 'Sitecredit';
        $PLUGIN_VERSION = '4.9.4p5';
        $PLUGIN_CATEGORY = 'plugin';
        $PRODUCT_DESCRIPTION = 'This plugin enables users to earn credits / reward points by performing various activities: content creation, inviting friends to join the community using referral sign-ups, liking a post, by commenting, etc. on SocialEngine community websites.';
        $PRODUCT_TITLE = 'Credits, Reward Points and Virtual Currency - User Engagement Plugin';
        $_PRODUCT_FINAL_FILE = 0;
        $SocialEngineAddOns_version = '4.9.1p6';
        $file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
        $is_file = @file_exists($file_path);
        if (empty($is_file)) {
            include APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/license3.php";
        } else {
            $db = $this->getDb();
            $select = new Zend_Db_Select($db);
            $select->from('engine4_core_modules')->where('name = ?', $PRODUCT_TYPE);
            $is_Mod = $select->query()->fetchObject();
            if (empty($is_Mod))
                include_once $file_path;
        }

        parent::onPreinstall();
    }

    function onInstall() {
        $db = $this->getDb();

        $db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='sitecredit';");
        if($this->isTableExists('engine4_siteeventticket_orders')){
            if(!$this->isColumnExists('engine4_siteeventticket_orders','credit_point')){
                $db->query("ALTER TABLE `engine4_siteeventticket_orders` ADD `credit_point` INT(11) NOT NULL");
            }
            if(!$this->isColumnExists('engine4_siteeventticket_orders','credit_value')){
                 $db->query("ALTER TABLE `engine4_siteeventticket_orders` ADD `credit_value` FLOAT(11) NOT NULL");
            }
            if($this->isColumnExists('engine4_siteeventticket_orders','credit_value')){
                 $db->query("ALTER TABLE `engine4_siteeventticket_orders` CHANGE `credit_value` `credit_value` FLOAT(11) NOT NULL");
            }
        }
        if($this->isTableExists('engine4_sitestoreproduct_orders')){
            if(!$this->isColumnExists('engine4_sitestoreproduct_orders','credit_point')){
                $db->query("ALTER TABLE `engine4_sitestoreproduct_orders` ADD `credit_point` INT(11) NOT NULL");
            }
            if(!$this->isColumnExists('engine4_sitestoreproduct_orders','credit_value')){
                $db->query("ALTER TABLE `engine4_sitestoreproduct_orders` ADD `credit_value` FLOAT(11) NOT NULL");
            }
            if($this->isColumnExists('engine4_sitestoreproduct_orders','credit_value')){
                $db->query("ALTER TABLE `engine4_sitestoreproduct_orders` CHANGE `credit_value` `credit_value` FLOAT(11) NOT NULL");
            }
        }

        if($this->isTableExists('engine4_sitestoreproduct_otherinfo')) {
            if(!$this->isColumnExists('engine4_sitestoreproduct_otherinfo','credit_handling_type')){
              $db->query("ALTER TABLE `engine4_sitestoreproduct_otherinfo` ADD `credit_handling_type` INT NOT NULL DEFAULT '0' COMMENT '1=> credit redemption . 0=> discount'");
            }
            if(!$this->isColumnExists('engine4_sitestoreproduct_otherinfo','credit_limit')){
              $db->query("ALTER TABLE `engine4_sitestoreproduct_otherinfo` ADD `credit_limit` FLOAT NOT NULL DEFAULT '0' ");
            }
        }
        if($this->isTableExists('engine4_sitestore_stores')) {
            if(!$this->isColumnExists('engine4_sitestore_stores','credit_limit')){
                $db->query("ALTER TABLE `engine4_sitestore_stores` ADD `credit_limit` FLOAT NOT NULL DEFAULT '0'");
            }
        }
        if($this->isTableExists('engine4_sitecredit_credits')){
              $db->query('ALTER TABLE `engine4_sitecredit_credits` CHANGE `type` `type` ENUM(\'activity_type\',\'upgrade_request\',\'buy\',\'store\',\'event\',\'sent_to_friend\',\'received_from_friend\',\'bonus\',\'deduction\',\'affiliate\',\'subscription\',\'siteeventpaid_package\',\'sitestore_package\',\'sitepage_package\',\'sitereviewpaidlisting_package\',\'sitegroup_package\') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;');
        }

        if($this->isTableExists('engine4_sitecredit_modules')){
            if(!$this->isColumnExists('engine4_sitecredit_modules','module_id')){

                $db->query("ALTER TABLE engine4_sitecredit_modules DROP PRIMARY KEY;");
                $db->query('ALTER TABLE `engine4_sitecredit_modules` ADD `module_id` INT NOT NULL AUTO_INCREMENT FIRST, ADD `flag` CHAR(30) NULL DEFAULT NULL AFTER `module_id`, ADD PRIMARY KEY (`module_id`);');
                $db->query('UPDATE `engine4_sitecredit_modules` SET `flag` = \'product\' WHERE `engine4_sitecredit_modules`.`title` = \'Stores / Marketplace - Ecommerce\';');
                $db->query('UPDATE `engine4_sitecredit_modules` SET `flag` = \'product\' WHERE `engine4_sitecredit_modules`.`name` = \'siteeventticket\';');
                $db->query('INSERT INTO `engine4_sitecredit_modules` ( `name`, `title`, `flag`) VALUES (\'siteeventpaid\', \'Package Payment - Advanced Events - Paid Event and Ticket Selling Extension\',\'package\'), (\'sitepage\', \'Package Payment -  Directory / Pages\',\'package\'), (\'sitereviewpaidlisting\', \'Package Payment - Multiple Listing Types Plugin Core (Reviews & Ratings Plugin)\',\'package\'),(\'sitegroup\', \'Package Payment - Groups / Communities\',\'package\'),(\'payment\', \'User Subscription\',\'package\'),(\'sitestore\', \'Package Payment - Stores / Marketplace - Ecommerce\',\'package\');');
            }
        }

        $menu_id = $db->select()
          ->from('engine4_core_menuitems', 'id')
          ->where('name = ?', 'sitecredit_admin_main_level')
          ->limit(1)
          ->query()
          ->fetchColumn();
        
        if($menu_id) {
          // Insert menuitems
          $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitecredit_admin_main_statistics", "sitecredit", "Statistics", "", \'{"route":"admin_default","module":"sitecredit","controller":"settings","action":"statistics"}\', "sitecredit_admin_main", "", "1", "0", "46"),
            ("sitecredit_admin_main_support", "sitecredit", "Support", "", \'{"route":"admin_default","module":"sitecredit","controller":"settings","action":"support"}\', "sitecredit_admin_main", "", "1", "0", "47");');
          $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`id`, `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES (NULL, "sitecredit_admin_main_modulelist", "sitecredit", "Modules List", "", \'{"route":"admin_default","module":"sitecredit","controller":"module","action":"module-list"}\', "sitecredit_admin_main", "", "1", 
            "0", "41");');
        }

        $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES ("Sitecredit_bonus", "sitecredit", "{var:$website_name} has sent you {var:$credit_value} credits as bonus. ", "0", "", "1"),("Sitecredit_received_from_friend", "sitecredit", "{item:$subject} has sent you {var:$credit_value} credits.", "0", "", "1");');

        $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sitecredit_quick_link", "sitecredit", "My Credits", "Sitecredit_Plugin_Menus::allowedToViewCredits", \'{"route":"credit_general","action":"index"}\', "sitecredit_quick", "", 1);');
        $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES ("sitecredit_quick", "standard", "Sitecredit Quick Navigation Menu");');  

        $this->_creditManagePage();
        $this->_creditTransactionPage();
        $this->_earnCreditPage();


        $menu_id = $db->select()
          ->from('engine4_sitecredit_modules', 'module_id')
          ->where('name = ?', 'communityad')
          ->limit(1)
          ->query()
          ->fetchColumn();

        if(empty($menu_id)) {
          $db->query('INSERT INTO `engine4_sitecredit_modules` (`module_id`, `flag`, `name`, `title`, `integrated`, `minimum_credit`, `minimum_checkout_total`, `percentage_checkout`) VALUES (NULL, \'package\', \'communityad\', \'Community Ads Plugin\', \'1\', NULL, NULL, NULL);');
        }
        if($this->isTableExists('engine4_sitecredit_credits')) {
          $db->query('ALTER TABLE `engine4_sitecredit_credits` CHANGE `type` `type` ENUM(\'activity_type\',\'upgrade_request\',\'buy\',\'store\',\'event\',\'sent_to_friend\',\'received_from_friend\',\'bonus\',\'deduction\',\'affiliate\',\'subscription\',\'siteeventpaid_package\',\'sitestore_package\',\'sitepage_package\',\'sitereviewpaidlisting_package\',\'sitegroup_package\',\'communityad_package\') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;');
        }
        
        parent::onInstall();

    }
    protected function _creditManagePage()
      {
        
        $db = $this->getDb();

        // profile page
        $page_id = $db->select()
          ->from('engine4_core_pages', 'page_id')
          ->where('name = ?', 'sitecredit_index_index')
          ->limit(1)
          ->query()
          ->fetchColumn();

        
        // insert if it doesn't exist yet
        if( !$page_id ) {
          // Insert page
          $db->insert('engine4_core_pages', array(
            'name' => 'sitecredit_index_index',
            'displayname' => 'Credits - My Credit Page',
            'title' => 'My Credits',
            'description' => 'This page allows user to get details of their earned credits.',
            'custom' => 0,
          ));
          $page_id = $db->lastInsertId();
          
          // Insert top
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
          ));
          $top_id = $db->lastInsertId();
          
          // Insert main
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
          ));
          $main_id = $db->lastInsertId();
          
          // Insert top-middle
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
          ));
          $top_middle_id = $db->lastInsertId();
          
          // Insert main-middle
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
          ));
          $main_middle_id = $db->lastInsertId();
          
          // Insert main-right
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'right',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 1,
          ));
          $main_right_id = $db->lastInsertId();
          // Insert main-left
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'left',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 1,
          ));
          $main_left_id = $db->lastInsertId();
          // Insert menu
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.main-navigation-menu',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
          ));
          // Insert left widgets
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.send-to-friend',
            'page_id' => $page_id,
            'parent_content_id' => $main_left_id,
            'order' => 1,
            'params' => '{"title":"Send Credits to Friends","titleCount":true,"name":"sitecredit.send-to-friend"}'
          ));
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.recent-activities',
            'page_id' => $page_id,
            'parent_content_id' => $main_left_id,
            'order' => 2,
            'params' => '{"title":"Recent Activities","titleCount":true,"countactivity":"5","nomobile":"0","name":"sitecredit.recent-activities"}'
          ));
          // Insert Middle Widget content
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.my-credits',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
            'params' => '{"title":"My Credits","titleCount":true,"name":"sitecredit.my-credits"}'
          ));
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.terms-and-conditions',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 2,
            'params' => '{"title":"Terms & Conditions","titleCount":true,"name":"sitecredit.terms-and-conditions"}'
          ));

          // Insert Right Content
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.upgrade-level',
            'page_id' => $page_id,
            'parent_content_id' => $main_right_id,
            'order' => 1,
             'params' => '{"title":"Upgrade Member Level","titleCount":true,"showlevel":"0","nomobile":"0","name":"sitecredit.upgrade-level"}'
          ));
          
          
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.buy-credits',
            'page_id' => $page_id,
            'parent_content_id' => $main_right_id,
            'order' => 2,
            'params' => '{"title":"Buy Credits","titleCount":true,"name":"sitecredit.buy-credits"}'
          ));
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.next-target',
            'page_id' => $page_id,
            'parent_content_id' => $main_right_id,
            'order' => 3,
             'params' => '{"title":"Referral Link","titleCount":true,"targetType":"link","nomobile":"0","name":"sitecredit.next-target"}'
          ));
          
        }
        
        return $this;
      }
    protected function _creditTransactionPage()
      {
        
        $db = $this->getDb();

        // profile page
        $page_id = $db->select()
          ->from('engine4_core_pages', 'page_id')
          ->where('name = ?', 'sitecredit_index_transaction')
          ->limit(1)
          ->query()
          ->fetchColumn();

        
        // insert if it doesn't exist yet
        if( !$page_id ) {
          // Insert page
          $db->insert('engine4_core_pages', array(
            'name' => 'sitecredit_index_transaction',
            'displayname' => 'Credits - Transactions Page',
            'title' => 'Transactions Page',
            'description' => ' This page displays the credit transactions',
            'custom' => 0,
          ));
          $page_id = $db->lastInsertId();
          
          // Insert top
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
          ));
          $top_id = $db->lastInsertId();
          
          // Insert main
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
          ));
          $main_id = $db->lastInsertId();
          
          // Insert top-middle
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
          ));
          $top_middle_id = $db->lastInsertId();
          
          // Insert main-middle
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
          ));
          $main_middle_id = $db->lastInsertId();
          
          // Insert main-right
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'right',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 1,
          ));
          $main_right_id = $db->lastInsertId();
          // Insert main-left
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'left',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 1,
          ));
          $main_left_id = $db->lastInsertId();
          // Insert menu
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.main-navigation-menu',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
          ));
          
          // Insert Middle Widget content
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.browse-transaction',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
            'params' => '{"title":"My Transactions","titleCount":true,"itemCount":"10","show_content":"2","truncationActivity":"35","nomobile":"0","name":"sitecredit.browse-transaction"}'
          ));
          

          // Insert Right Content
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.badges',
            'page_id' => $page_id,
            'parent_content_id' => $main_right_id,
            'order' => 1,
             'params' => '{"title":"My Badge","titleCount":true,"countbadge":"1","nomobile":"0","name":"sitecredit.badges"}'
          ));
          
          
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.user-credit-information',
            'page_id' => $page_id,
            'parent_content_id' => $main_right_id,
            'order' => 2,
            'params' => '{"title":"My Credit Information","titleCount":true,"showBalance":"1","showRank":"1","showNextRank":"1","showlimit":"1","nomobile":"0","name":"sitecredit.user-credit-information"}'
          ));
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.next-target',
            'page_id' => $page_id,
            'parent_content_id' => $main_right_id,
            'order' => 3,
             'params' => '{"title":"","titleCount":true,"targetType":"badge","nomobile":"0","name":"sitecredit.next-target"}'
          ));
          
        }
        
        return $this;
      }
    protected function _earnCreditPage()
      {
        
        $db = $this->getDb();

        // profile page
        $page_id = $db->select()
          ->from('engine4_core_pages', 'page_id')
          ->where('name = ?', 'sitecredit_index_earncredit')
          ->limit(1)
          ->query()
          ->fetchColumn();

        
        // insert if it doesn't exist yet
        if( !$page_id ) {
          // Insert page
          $db->insert('engine4_core_pages', array(
            'name' => 'sitecredit_index_earncredit',
            'displayname' => 'Credits - Earn Credits Page',
            'title' => 'Earn Credits Page',
            'description' => 'This page contains the details for how a user can earn credits',
            'custom' => 0,
          ));
          $page_id = $db->lastInsertId();
          
          // Insert top
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
          ));
          $top_id = $db->lastInsertId();
          
          // Insert main
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
          ));
          $main_id = $db->lastInsertId();
          
          // Insert top-middle
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
          ));
          $top_middle_id = $db->lastInsertId();
          
          // Insert main-middle
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
          ));
          $main_middle_id = $db->lastInsertId();
          
          // Insert main-right
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'right',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 1,
          ));
          $main_right_id = $db->lastInsertId();
          // Insert main-left
          $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'left',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 1,
          ));
          $main_left_id = $db->lastInsertId();
          // Insert menu
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.main-navigation-menu',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
          ));
          // Insert left widgets
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.earn-credits',
            'page_id' => $page_id,
            'parent_content_id' => $main_left_id,
            'order' => 1,
            'params' => '{"title":"How to Earn Credits","titleCount":true,"name":"sitecredit.earn-credits"}'
          ));
          
          // Insert Middle Widget content
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.show-activity-credit',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
            'params' => '{"title":"Assigned Credits","titleCount":true,"itemCountPage":"15","show_content_credit":"2","truncationActivity":"70","nomobile":"0","name":"sitecredit.show-activity-credit"}'
          ));

          // Insert Right Content
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitecredit.top-member',
            'page_id' => $page_id,
            'parent_content_id' => $main_right_id,
            'order' => 1,
             'params' => '{"title":"Top Active Members","titleCount":true,"topmember":"earned","count":"10","showFriendRequest":"1","nomobile":"0","name":"sitecredit.top-member"}'
          ));
          
        }
        
        return $this;
      }
    function isTableExists($tableName){
        $db = $this->getDb();
        $isTableExists = $db->query("SHOW TABLES LIKE '$tableName'")->fetch();
        if($isTableExists){
            return true;
        }
        return false;
    }

    function isColumnExists($tableName,$columnName){
        $db = $this->getDb();
        $isColumnExists = $db->query("SHOW COLUMNS FROM $tableName LIKE '$columnName'")->fetch();
        if($isColumnExists)
            return true;
        
        return false;
    }
    private function getVersion() {
        $db = $this->getDb();

        $errorMsg = '';
        $finalModules = $getResultArray = array();
        $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();

        $modArray = array(
            'sitestore' => '4.9.1p5',
            'siteeventticket' => '4.9.1p2',
            'sitegateway' => '4.9.1',
            'Sitecoupon' => '4.9.1p2',
            'sitegroup' => '4.9.1p5',
            'sitepage' => '4.9.1p5',
            'sitereviewpaidlisting' => '4.9.1p3',
            'sitesubscription' => '4.9.1p4',
        );

        foreach ($modArray as $key => $value) {
            $isMod = $db->query("SELECT * FROM  `engine4_core_modules` WHERE  `name` LIKE  '" . $key . "'")->fetch();
            if (!empty($isMod) && !empty($isMod['version'])) {
                $isModSupport = $this->checkVersion($isMod['version'], $value);
                if (empty($isModSupport)) {
                    $finalModules['modName'] = $key;
                    $finalModules['title'] = $isMod['title'];
                    $finalModules['versionRequired'] = $value;
                    $finalModules['versionUse'] = $isMod['version'];
                    $getResultArray[] = $finalModules;
                }
            }
        }

        foreach ($getResultArray as $modArray) {
            $errorMsg .= '<div class="tip"><span>Note: Your website does not have the latest version of "' . $modArray['title'] . '". Please upgrade "' . $modArray['title'] . '" on your website to the latest version available in your SocialEngineAddOns Client Area to enable its integration with "Multi Currency / Currency Switcher Plugin".<br/> Please <a href="' . $base_url . '/manage">Click here</a> to go Manage Packages.</span></div>';
        }

        return $errorMsg;
    }
    function checkVersion($databaseVersion, $checkDependancyVersion) {
        if (strcasecmp($databaseVersion, $checkDependancyVersion) == 0)
            return -1;
        $databaseVersionArr = explode(".", $databaseVersion);
        $checkDependancyVersionArr = explode('.', $checkDependancyVersion);
        $fValueCount = $count = count($databaseVersionArr);
        $sValueCount = count($checkDependancyVersionArr);
        if ($fValueCount > $sValueCount)
            $count = $sValueCount;
        for ($i = 0; $i < $count; $i++) {
            $fValue = $databaseVersionArr[$i];
            $sValue = $checkDependancyVersionArr[$i];
            if (is_numeric($fValue) && is_numeric($sValue)) {
                $result = $this->compareValues($fValue, $sValue);
                if ($result == -1) {
                    if (($i + 1) == $count) {
                        return $this->compareValues($fValueCount, $sValueCount);
                    } else
                        continue;
                }
                return $result;
            }
            elseif (is_string($fValue) && is_numeric($sValue)) {
                $fsArr = explode("p", $fValue);
                $result = $this->compareValues($fsArr[0], $sValue);
                return $result == -1 ? 1 : $result;
            } elseif (is_numeric($fValue) && is_string($sValue)) {
                $ssArr = explode("p", $sValue);
                $result = $this->compareValues($fValue, $ssArr[0]);
                return $result == -1 ? 0 : $result;
            } elseif (is_string($fValue) && is_string($sValue)) {
                $fsArr = explode("p", $fValue);
                $ssArr = explode("p", $sValue);
                $result = $this->compareValues($fsArr[0], $ssArr[0]);
                if ($result != -1)
                    return $result;
                $result = $this->compareValues($fsArr[1], $ssArr[1]);
                return $result;
            }
        }
    }

    public function compareValues($firstVal, $secondVal) {
        $num = $firstVal - $secondVal;
        return ($num > 0) ? 1 : ($num < 0 ? 0 : -1);
    }

}
