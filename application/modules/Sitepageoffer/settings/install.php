<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Installer extends Engine_Package_Installer_Module {

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
            $PRODUCT_TYPE = 'sitepageoffer';
            $PLUGIN_TITLE = 'Sitepageoffer';
            $PLUGIN_VERSION = '4.9.4p2';
            $PLUGIN_CATEGORY = 'plugin';
            $PRODUCT_DESCRIPTION = 'Sitepageoffer Plugin';
            $PRODUCT_TITLE = 'Directory / Pages - Offers Extension';
            $_PRODUCT_FINAL_FILE = 0;
            $sitepage_plugin_version = '4.8.6';
            $SocialEngineAddOns_version = '4.8.9p3';
            $file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
            $is_file = file_exists($file_path);
            if (empty($is_file)) {
                include APPLICATION_PATH . "/application/modules/Sitepage/controllers/license/license4.php";
            } else {
                include $file_path;
            }

            //CODE FOR INCREASE THE SIZE OF engine4_authorization_permissions's FIELD type
            $type_array = $db->query("SHOW COLUMNS FROM engine4_authorization_permissions LIKE 'type'")->fetch();
            if (!empty($type_array)) {
                $varchar = $type_array['Type'];
                $length_varchar = explode("(", $varchar);
                $length = explode(")", $length_varchar[1]);
                $length_type = $length[0];
                if ($length_type < 32) {
                    $run_query = $db->query("ALTER TABLE `engine4_authorization_permissions` CHANGE `type` `type` VARCHAR( 32 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL");
                }
            }

            $pageTime = time();
            $db->query("INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
			('sitepageoffer.basetime', $pageTime ),
			('sitepageoffer.isvar', 0 ),
			('sitepageoffer.filepath', 'Sitepageoffer/controllers/license/license2.php');");

            //PUT SITEPAGE OFFER WIDGET IN ADMIN CONTENT TABLE
            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_core_modules')
                    ->where('name = ?', 'sitepageoffer')
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
                            ->where('name = ?', 'sitepageoffer.profile-sitepageoffers')
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
                                    'name' => 'sitepageoffer.profile-sitepageoffers',
                                    'parent_content_id' => $tab_id,
                                    'order' => 116,
                                    'params' => '{"title":"Offers","titleCount":"true"}',
                                ));
                            }
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


            return $this->_error("<span style='color:red'>Note: You have installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> but not activated it on your site yet. Please activate it first before installing the Directory / Pages - Offers Extension.</span><br/> <a href='" . 'http://' . $core_final_url . "admin/sitepage/settings/readme'>Click here</a> to activate the Directory / Pages Plugin.");
        } else {
            $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
            return $this->_error("<span style='color:red'>Note: You have not installed the <a href='http://www.socialengineaddons.com/socialengine-directory-pages-plugin' target='_blank'>Directory / Pages Plugin</a> on your site yet. Please install it first before installing the <a href='http://www.socialengineaddons.com/pageextensions/socialengine-directory-pages-offers' target='_blank'>Directory / Pages - Offers Extension</a>.</span><br/> <a href='" . $base_url . "/manage'>Click here</a> to go Manage Packages.");
        }
    }

    function onInstall() {

        $db = $this->getDb();

        $db->query('UPDATE  `engine4_activity_notificationtypes` SET  `body` =  \'{item:$subject} has created a page offer {item:$object}.\' WHERE  `engine4_activity_notificationtypes`.`type` =  "sitepageoffer_create";');

        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitepageoffer');
        $is_sitepageoffer_object = $select->query()->fetchObject();

        if (!empty($is_sitepageoffer_object)) {
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES("sitepageoffer_admin_main_fields", "sitepageoffer", "Offer Questions", "", \'{"route":"admin_default","module":"sitepageoffer","controller":"fields"}\', "sitepageoffer_admin_main", "", 4)');
        }

        $offerMapsTable = $db->query('SHOW TABLES LIKE \'engine4_sitepageoffer_offer_fields_maps\'')->fetch();
        if (empty($offerMapsTable)) {
            $db->query("CREATE TABLE `engine4_sitepageoffer_offer_fields_maps` (
				`field_id` int(11) NOT NULL,
				`option_id` int(11) NOT NULL,
				`child_id` int(11) NOT NULL,
				`order` smallint(6) NOT NULL,
				PRIMARY KEY  (`field_id`,`option_id`,`child_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;");
        }

        $offerMetaTable = $db->query('SHOW TABLES LIKE \'engine4_sitepageoffer_offer_fields_meta\'')->fetch();
        if (empty($offerMetaTable)) {
            $db->query("CREATE TABLE `engine4_sitepageoffer_offer_fields_meta` (
				`field_id` int(11) NOT NULL auto_increment,
				`type` varchar(24) collate latin1_general_ci NOT NULL,
				`label` varchar(64) NOT NULL,
				`description` varchar(255) NOT NULL default '',
				`alias` varchar(32) NOT NULL default '',
				`required` tinyint(1) NOT NULL default '0',
				`display` tinyint(1) unsigned NOT NULL,
				`search` tinyint(1) unsigned NOT NULL default '0',
				`show` tinyint(1) unsigned NOT NULL default '1',
				`order` smallint(3) unsigned NOT NULL default '999',
				`config` text NOT NULL,
				`validators` text NULL,
				`filters` text NULL,
				`style` text NULL,
				`error` text NULL,
				PRIMARY KEY  (`field_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;");
        }

        $offerOptionsTable = $db->query('SHOW TABLES LIKE \'engine4_sitepageoffer_offer_fields_options\'')->fetch();
        if (empty($offerOptionsTable)) {
            $db->query("CREATE TABLE `engine4_sitepageoffer_offer_fields_options` (
				`option_id` int(11) NOT NULL auto_increment,
				`field_id` int(11) NOT NULL,
				`label` varchar(255) NOT NULL,
				`order` smallint(6) NOT NULL default '999',
				PRIMARY KEY  (`option_id`),
				KEY `field_id` (`field_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;");
        }

        $offerValuesTable = $db->query('SHOW TABLES LIKE \'engine4_sitepageoffer_offer_fields_values\'')->fetch();
        if (empty($offerValuesTable)) {
            $db->query("CREATE TABLE `engine4_sitepageoffer_offer_fields_values` (
				`item_id` int(11) NOT NULL,
				`field_id` int(11) NOT NULL,
				`index` smallint(3) NOT NULL default '0',
				`value` text NOT NULL,
				PRIMARY KEY  (`item_id`,`field_id`,`index`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;");
        }

        $offerSearchTable = $db->query('SHOW TABLES LIKE \'engine4_sitepageoffer_offer_fields_search\'')->fetch();
        if (empty($offerSearchTable)) {
            $db->query("CREATE TABLE IF NOT EXISTS `engine4_sitepageoffer_offer_fields_search` (
				`item_id` int(11) NOT NULL,
				PRIMARY KEY  (`item_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;");
        }

        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitepageoffer')
                ->where('version < ?', '4.2.7');
        $is_enabled = $select->query()->fetchObject();

        if ($is_enabled) {
            $db->update('engine4_activity_actiontypes', array('is_generated' => '1'), array('type = ?' => 'sitepageoffer_home'));
        }

        $db->query("INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
( 'offer_claim', 'sitepageoffer', '[host][email][template_header][message]][template_footer]');");

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
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitepageoffer')
                ->where('version < ?', '4.2.6');
        $is_enabled = $select->query()->fetchObject();

        if ($is_enabled) {
            $column_claim_exist = $db->query('SHOW COLUMNS FROM engine4_sitepageoffer_offers LIKE \'claim_count\'')->fetch();
            if (empty($column_claim_exist)) {
                $db->query('ALTER TABLE `engine4_sitepageoffer_offers` ADD `claim_count` INT( 5 ) NOT NULL AFTER `description`');
            }

            $column_claimed_exist = $db->query('SHOW COLUMNS FROM engine4_sitepageoffer_offers LIKE \'claimed\'')->fetch();
            if (empty($column_claimed_exist)) {
                $db->query('ALTER TABLE `engine4_sitepageoffer_offers` ADD `claimed` INT( 5 ) NOT NULL DEFAULT "0" AFTER `claim_count`');
            }

            $column_viewcount_exist = $db->query('SHOW COLUMNS FROM engine4_sitepageoffer_offers LIKE \'view_count\'')->fetch();
            if (empty($column_viewcount_exist)) {
                $db->query('ALTER TABLE `engine4_sitepageoffer_offers` ADD `view_count` INT( 11 ) NOT NULL AFTER `claimed`');
            }

            $column_commentcount_exist = $db->query('SHOW COLUMNS FROM engine4_sitepageoffer_offers LIKE \'comment_count\'')->fetch();
            if (empty($column_commentcount_exist)) {
                $db->query('ALTER TABLE `engine4_sitepageoffer_offers` ADD `comment_count` INT( 11 ) NOT NULL DEFAULT "0" AFTER `view_count`');
            }

            $column_likecount_exist = $db->query('SHOW COLUMNS FROM engine4_sitepageoffer_offers LIKE \'like_count\'')->fetch();
            if (empty($column_likecount_exist)) {
                $db->query('ALTER TABLE `engine4_sitepageoffer_offers` ADD `like_count` INT( 11 ) NOT NULL AFTER `comment_count`');
            }

            $column_code_exist = $db->query('SHOW COLUMNS FROM engine4_sitepageoffer_offers LIKE \'coupon_code\'')->fetch();
            if (empty($column_code_exist)) {
                $db->query('ALTER TABLE `engine4_sitepageoffer_offers` ADD `coupon_code` VARCHAR( 32 ) NOT NULL AFTER `url`');
            }

            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_sitepageoffer_offers');
            $totalOffers = $select->query()->fetchAll();
            foreach ($totalOffers as $sitepageoffer) {
                $offer_id = $sitepageoffer->offer_id;
                $db->query("INSERT IGNORE INTO `engine4_authorization_allow` (`resource_type`, `resource_id`, `action`, `role`, `role_id`, `value`, `params`) VALUES
				('sitepageoffer_offer', '$offer_id', 'comment', 'everyone', 0, 1, NULL);");
                $db->query("INSERT IGNORE INTO `engine4_authorization_allow` (`resource_type`, `resource_id`, `action`, `role`, `role_id`, `value`, `params`) VALUES
				('sitepageoffer_offer', '$offer_id', 'comment', 'owner_member', 0, 1, NULL);");
                $db->query("INSERT IGNORE INTO `engine4_authorization_allow` (`resource_type`, `resource_id`, `action`, `role`, `role_id`, `value`, `params`) VALUES
				('sitepageoffer_offer', '$offer_id', 'comment', 'owner_member_member', 0, 1, NULL);");
                $db->query("INSERT IGNORE INTO `engine4_authorization_allow` (`resource_type`, `resource_id`, `action`, `role`, `role_id`, `value`, `params`) VALUES
				('sitepageoffer_offer', '$offer_id', 'comment', 'owner_network', 0, 1, NULL);");
                $db->query("INSERT IGNORE INTO `engine4_authorization_allow` (`resource_type`, `resource_id`, `action`, `role`, `role_id`, `value`, `params`) VALUES
				('sitepageoffer_offer', '$offer_id', 'comment', '	registered', 0, 1, NULL);");
            }

            // Check if it's already been placed
            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_core_pages')
                    ->where('name = ?', 'sitepageoffer_index_view')
                    ->limit(1);

            $info = $select->query()->fetch();

            if (empty($info)) {
                $db->insert('engine4_core_pages', array(
                    'name' => 'sitepageoffer_index_view',
                    'displayname' => 'Page Offer View Page',
                    'title' => 'View Page Offer',
                    'description' => 'This is the view page for a page offer.',
                    'custom' => 1,
                    'provides' => 'subject=sitepageoffer',
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
                    'name' => 'sitepageoffer.offer-content',
                    'parent_content_id' => $middle_id,
                    'order' => 1,
                    'params' => '',
                ));

                if (!empty($infomation) && !empty($rowinfo)) {
                    $db->insert('engine4_core_content', array(
                        'page_id' => $page_id,
                        'type' => 'widget',
                        'name' => 'sitepage.page-ads',
                        'parent_content_id' => $right_id,
                        'order' => 4,
                        'params' => '{"title":"","titleCount":""}',
                    ));
                }
            }

            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_core_modules')
                    ->where('name = ?', 'sitepageoffer')
                    ->where('enabled = ?', 1);
            $is_enabled = $select->query()->fetchObject();
            if (!empty($is_enabled)) {
                $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitepageoffer_admin_main_offer_tab", "sitepageoffer", "Tabbed Offers Widget", "", \'{"route":"admin_default","module":"sitepageoffer","controller":"settings", "action": "widget"}\', "sitepageoffer_admin_main", "", 1, 0, 4)');

                $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitepageoffer_admin_main_dayitems", "sitepageoffer", "Offer of the Day", "", \'{"route":"admin_default","module":"sitepageoffer","controller":"settings", "action": "manage-day-items"}\', "sitepageoffer_admin_main", "", 1, 0, 5)');
            }
            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_core_pages')
                    ->where('name = ?', 'sitepageoffer_index_home')
                    ->limit(1);
            $info = $select->query()->fetch();
            if (empty($info)) {
                $db->insert('engine4_core_pages', array(
                    'name' => 'sitepageoffer_index_home',
                    'displayname' => 'Page Offers Home',
                    'title' => 'Page Offers Home',
                    'description' => 'This is page offer home page.',
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
                //INSERT TOP RATED PAGE OFFER WIDGET
                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.offers-sitepageoffers',
                    'parent_content_id' => $left_id,
                    'order' => 13,
                    'params' => '{"title":"Most Popular Offers","popularity":"popular","titleCount":"true"}',
                ));

                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.offers-sitepageoffers',
                    'parent_content_id' => $left_id,
                    'order' => 14,
                    'params' => '{"title":"Most Viewed Offers","popularity":"view_count","titleCount":"true"}',
                ));

                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.sitepage-hotoffer',
                    'parent_content_id' => $left_id,
                    'order' => 15,
                    'params' => '{"title":"Hot Page Offers","titleCount":"true"}',
                ));

                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.sitepage-latestoffer',
                    'parent_content_id' => $left_id,
                    'order' => 16,
                    'params' => '{"title":"Latest Page Offers","titleCount":"true"}',
                ));

                // Middele
                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.hot-offers-slideshow',
                    'parent_content_id' => $middle_id,
                    'order' => 16,
                    'params' => '{"title":"Hot Offers","vertical":"0", "noOfRow":"2","inOneRow":"3","interval":"250","name":"sitepageoffer.hot-offers-slideshow"}',
                ));

                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.list-offers-tabs-view',
                    'parent_content_id' => $middle_id,
                    'order' => 17,
                    'params' => '{"title":"Offers","margin_photo":"12"}',
                ));
                // Right Side
                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.sitepageofferlist-link',
                    'parent_content_id' => $right_id,
                    'order' => 19,
                    'params' => '',
                ));

                // Right Side
                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.search-sitepageoffer',
                    'parent_content_id' => $right_id,
                    'order' => 18,
                    'params' => '',
                ));

                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.offer-of-the-day',
                    'parent_content_id' => $left_id,
                    'order' => 12,
                    'params' => '{"title":"Offer of the Day"}',
                ));

                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.offers-sitepageoffers',
                    'parent_content_id' => $right_id,
                    'order' => 22,
                    'params' => '{"title":"Most Commented Offers","popularity":"comment_count","titleCount":"true"}',
                ));

                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.offers-sitepageoffers',
                    'parent_content_id' => $right_id,
                    'order' => 23,
                    'params' => '{"title":"Most Liked Offers","popularity":"like_count","titleCount":"true"}',
                ));
            }
        }

        $table_exist = $db->query("SHOW TABLES LIKE 'engine4_seaocore_searchformsetting'")->fetch();
        if (!empty($table_exist)) {
            $db->query("INSERT IGNORE INTO `engine4_seaocore_searchformsetting` (`module` ,`name` ,`display` ,`order` ,`label`) VALUES ('sitepage', 'offer_type', '1', '50', 'Pages With Offers')");
        }

        $table_exist = $db->query("SHOW TABLES LIKE 'engine4_sitepageoffer_offers'")->fetch();
        if (!empty($table_exist)) {
            //ADD THE INDEX FROM THE "engine4_sitepageoffer_offers" TABLE
            $pageIdColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepageoffer_offers` WHERE Key_name = 'page_id'")->fetch();

            if (empty($pageIdColumnIndex)) {
                $db->query("ALTER TABLE `engine4_sitepageoffer_offers` ADD INDEX ( `page_id` );");
            }

            //ADD THE INDEX FROM THE "engine4_sitepageoffer_offers" TABLE
            $ownerIdColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepageoffer_offers` WHERE Key_name = 'owner_id'")->fetch();

            if (empty($ownerIdColumnIndex)) {
                $db->query("ALTER TABLE `engine4_sitepageoffer_offers` ADD INDEX ( `owner_id` );");
            }
        }

        $table_exist = $db->query("SHOW TABLES LIKE 'engine4_sitepageoffer_albums'")->fetch();

        if (!empty($table_exist)) {
            //DROP THE INDEX FROM THE "engine4_sitepageoffer_albums" TABLE
            $offerIdColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepageoffer_albums` WHERE Key_name = 'sitepageoffer_id'")->fetch();

            if (!empty($offerIdColumnIndex)) {
                $db->query("ALTER TABLE `engine4_sitepageoffer_albums` DROP INDEX `sitepageoffer_id`");
            }

            //ADD THE INDEX FROM THE "engine4_sitepageoffer_albums" TABLE
            $offerIdColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepageoffer_albums` WHERE Key_name = 'offer_id'")->fetch();

            if (empty($offerIdColumnIndex)) {
                $db->query("ALTER TABLE `engine4_sitepageoffer_albums` ADD INDEX ( `offer_id` );");
            }
        }

        $table_exist = $db->query("SHOW TABLES LIKE 'engine4_sitepageoffer_photos'")->fetch();
        if (!empty($table_exist)) {
            //DROP THE INDEX FROM THE "engine4_sitepageoffer_photos" TABLE
            $offerIdAlbumsColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepageoffer_photos` WHERE Key_name = 'sitepageoffer_id'")->fetch();

            if (!empty($offerIdAlbumsColumnIndex)) {
                $db->query("ALTER TABLE `engine4_sitepageoffer_photos` DROP INDEX `sitepageoffer_id`");
            }

            //ADD THE INDEX FROM THE "engine4_sitepageoffer_photos" TABLE
            $offerIdColumnIndex = $db->query("SHOW INDEX FROM `engine4_sitepageoffer_photos` WHERE Key_name = 'offer_id'")->fetch();

            if (empty($offerIdColumnIndex)) {
                $db->query("ALTER TABLE `engine4_sitepageoffer_photos` ADD INDEX ( `offer_id` );");
            }

            //ADD THE COLUMN FROM THE "engine4_sitepageoffer_photos" TABLE
            $collectionIdColumn = $db->query("SHOW COLUMNS FROM engine4_sitepageoffer_photos LIKE 'collection_id'")->fetch();

            if (empty($collectionIdColumn)) {
                $db->query("ALTER TABLE `engine4_sitepageoffer_photos` ADD `collection_id` INT( 11 ) NOT NULL AFTER `file_id`");
            }
        }



        //REMOVED WIDGET SETTING TAB FROM ADMIN PANEL
        $select = new Zend_Db_Select($db);
        $select->from('engine4_core_modules')
                ->where('name = ?', 'sitepageoffer')
                ->where('version <= ?', '4.1.7p2');
        $is_enabled = $select->query()->fetchObject();
        if (!empty($is_enabled)) {
            $widget_names = array('offer', 'hotoffer');

            foreach ($widget_names as $widget_name) {

                $widget_type = $widget_name;
                if ($widget_type == 'offer') {
                    $widget_name = 'sitepageoffer.' . 'sitepage' . '-latestoffer';
                }
                if ($widget_type == 'hotoffer') {
                    $widget_name = 'sitepageoffer.' . 'sitepage' . '-hotoffer';
                }

                $setting_name = 'sitepageoffer.' . $widget_type . '.widgets';

                $total_items = $db->select()
                        ->from('engine4_core_settings', array('value'))
                        ->where('name = ?', $setting_name)
                        ->limit(1)
                        ->query()
                        ->fetchColumn();

                if (empty($total_items)) {
                    $total_items = 3;
                }

                //WORK FOR CORE CONTENT PAGES
                $select = new Zend_Db_Select($db);
                $select->from('engine4_core_content', array('name', 'params', 'content_id'))->where('name = ?', $widget_name);
                $widgets = $select->query()->fetchAll();
                foreach ($widgets as $widget) {
                    $explode_params = explode('}', $widget['params']);
                    if (!empty($explode_params[0]) && !strstr($explode_params[0], '"itemCount"')) {
                        $params = $explode_params[0] . ',"itemCount":"' . $total_items . '"}';

                        $db->update('engine4_core_content', array('params' => $params), array('content_id = ?' => $widget['content_id'], 'name = ?' => $widget_name));
                    }
                }

                //WORK FOR ADMIN USER CONTENT PAGE
                $select = new Zend_Db_Select($db);
                $select->from('engine4_sitepage_admincontent', array('name', 'params', 'admincontent_id'))->where('name = ?', $widget_name);
                $widgets = $select->query()->fetchAll();
                foreach ($widgets as $widget) {
                    $explode_params = explode('}', $widget['params']);
                    if (!empty($explode_params[0]) && !strstr($explode_params[0], '"itemCount"')) {
                        $params = $explode_params[0] . ',"itemCount":"' . $total_items . '"}';

                        $db->update('engine4_sitepage_admincontent', array('params' => $params), array('admincontent_id = ?' => $widget['admincontent_id'], 'name = ?' => $widget_name));
                    }
                }

                //WORK FOR USER CONTENT PAGES
                $select = new Zend_Db_Select($db);
                $select->from('engine4_sitepage_content', array('name', 'params', 'content_id'))->where('name = ?', $widget_name);
                $widgets = $select->query()->fetchAll();
                foreach ($widgets as $widget) {
                    $explode_params = explode('}', $widget['params']);
                    if (!empty($explode_params[0]) && !strstr($explode_params[0], '"itemCount"')) {
                        $params = $explode_params[0] . ',"itemCount":"' . $total_items . '"}';

                        $db->update('engine4_sitepage_content', array('params' => $params), array('content_id = ?' => $widget['content_id'], 'name = ?' => $widget_name));
                    }
                }
            }
        }

        //START THE WORK FOR MAKE WIDGETIZE PAGE OF OFFERS LISTING
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitepageoffer')
                ->where('version < ?', '4.2.0');
        $is_enabled = $select->query()->fetchObject();
        if (!empty($is_enabled)) {
            $select = new Zend_Db_Select($db);
            //$db->update('engine4_sitepageoffer_offers', array('end_time' => NULL), array('end_settings = ?' => '0'));
            $select
                    ->from('engine4_core_pages')
                    ->where('name = ?', 'sitepageoffer_index_offerlist')
                    ->limit(1);
            ;
            $info = $select->query()->fetch();

            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_core_pages')
                    ->where('name = ?', 'sitepageoffer_index_browse')
                    ->limit(1);
            ;
            $info_browse = $select->query()->fetch();

            if (empty($info) && empty($info_browse)) {
                $db->insert('engine4_core_pages', array(
                    'name' => 'sitepageoffer_index_browse',
                    'displayname' => 'Page Offers',
                    'title' => 'Page Offers',
                    'description' => 'This is the page offers.',
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

                //INSERT NAVIGATION WIDGET
                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepage.browsenevigation-sitepage',
                    'parent_content_id' => $top_middle_id,
                    'order' => 1,
                    'params' => '{"title":"","titleCount":""}',
                ));

                //INSERT OFFER WIDGET
                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.sitepage-offer',
                    'parent_content_id' => $middle_id,
                    'order' => 2,
                    'params' => '{"title":"","titleCount":""}',
                ));

                //INSERT HOT PAGE OFFER WIDGET
                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.sitepage-hotoffer',
                    'parent_content_id' => $right_id,
                    'order' => 4,
                    'params' => '{"title":"Hot Page Offers","titleCount":"true"}',
                ));

                //INSERT LATEST PAGE OFFER WIDGET
                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.sitepage-latestoffer',
                    'parent_content_id' => $right_id,
                    'order' => 5,
                    'params' => '{"title":"Latest Page Offers","titleCount":"true"}',
                ));

                //INSERT SEARCH PAGE OFFER WIDGET
                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.search-sitepageoffer',
                    'parent_content_id' => $right_id,
                    'order' => 3,
                    'params' => '{"title":"","titleCount":"true"}',
                ));

                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.sitepage-sponsoredoffer',
                    'parent_content_id' => $right_id,
                    'order' => 6,
                    'params' => '{"title":"Sponsored Offers","titleCount":"true"}',
                ));

                //INSERT AVAILABLE PAGE OFFER WIDGET
                $db->insert('engine4_core_content', array(
                    'page_id' => $page_id,
                    'type' => 'widget',
                    'name' => 'sitepageoffer.sitepage-dateoffer',
                    'parent_content_id' => $right_id,
                    'order' => 7,
                    'params' => '{"title":"Available Offers","titleCount":"true"}',
                ));

                if ($infomation && $rowinfo) {
                    $db->insert('engine4_core_content', array(
                        'page_id' => $page_id,
                        'type' => 'widget',
                        'name' => 'sitepage.page-ads',
                        'parent_content_id' => $right_id,
                        'order' => 10,
                        'params' => '{"title":"","titleCount":""}',
                    ));
                }
            } else {
                $db->update('engine4_core_pages', array('name' => 'sitepageoffer_index_browse'), array('name = ?' => 'sitepageoffer_index_offerlist'));
            }
        }
        //END THE WORK FOR MAKE WIDGETIZE PAGE OF OFFERS LISTING
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_settings')
                ->where('name = ?', 'sitepage.feed.type');
        $info = $select->query()->fetch();
        $enable = 1;
        if (!empty($info))
            $enable = $info['value'];
        $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`, `is_object_thumb`) VALUES("sitepageoffer_admin_new", "sitepageoffer", "{item:$object} added a new offer:", ' . $enable . ', 6, 2, 1, 1, 1, 1)');

        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitepageoffer')
                ->where('version < ?', '4.2.3');
        $is_enabled = $select->query()->fetchObject();
        if ($is_enabled) {
            $db->query("ALTER TABLE `engine4_sitepageoffer_offers` CHANGE `end_time` `end_time` DATETIME NOT NULL;");
        }

        $table_exist = $db->query("SHOW TABLES LIKE 'engine4_facebookse_mixsettings'")->fetch();
        if (!empty($table_exist)) {
            $db->query("INSERT IGNORE INTO `engine4_facebookse_mixsettings` (`module`, `resource_type`, `resource_id`, `owner_field`, `module_title`, `module_description`, `enable`, `send_button`, `like_type`, `like_faces`, `like_width`, `like_font`, `like_color`, `layout_style`, `opengraph_enable`, `title`, `photo_id`, `description`, `types`, `fbadmin_appid`, `commentbox_enable`, `commentbox_privacy`, `commentbox_width`, `commentbox_color`, `module_enable`, `default`, `activityfeed_type`, `streampublish_message`, `streampublish_story_title`, `streampublish_link`, `streampublish_caption`, `streampublish_description`, `streampublish_action_link_text`, `streampublish_action_link_url`, `streampublishenable`, `activityfeedtype_text`) VALUES ('sitepageoffer', 'sitepageoffer_offer', 'offer_id', 'owner_id', 'title', 'description', 1, 1, 'like', 0, 450, '', 'light', 'standard', 0, '', 0, '', '', 1, 1, 1, 450, 'light', 1, 1, 'sitepageoffer_new', 'View my Page Offer!', '{*sitepageoffer_title*}', '{*sitepageoffer_url*}', '{
*actor*} added a new Page Offer on {*site_title*}: {*site_url*}.', '{*sitepageoffer_desc*}', 'View Offer', '{*sitepageoffer_url*}', 0, 'Creating a Page Offer');");
        }

        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitemobile')
                ->where('enabled = ?', 1);
        $is_sitemobile_object = $select->query()->fetchObject();
        if ($is_sitemobile_object) {
            include APPLICATION_PATH . "/application/modules/Sitepageoffer/controllers/license/mobileLayoutCreation.php";
        }

        $claimTable = $db->query('SHOW TABLES LIKE \'engine4_sitepageoffer_claims\'')->fetch();
        if (!empty($claimTable)) {
            $pageIdIndex = $db->query("SHOW INDEX FROM `engine4_sitepageoffer_claims` WHERE Key_name = 'page_id'")->fetch();
            if (!empty($pageIdIndex)) {
                $db->query("ALTER TABLE `engine4_sitepageoffer_claims` ADD INDEX (`page_id`, `owner_id`, `offer_id`)");
            }
        }

        $offerTable = $db->query('SHOW TABLES LIKE \'engine4_sitepageoffer_offers\'')->fetch();
        if (!empty($offerTable)) {
            $hotofferColumn = $db->query("SHOW COLUMNS FROM engine4_sitepageoffer_offers LIKE 'hotoffer'")->fetch();
            if (!empty($hotofferColumn)) {
                $hotofferIndex = $db->query("SHOW INDEX FROM `engine4_sitepageoffer_offers` WHERE Key_name = 'hotoffer'")->fetch();
                if (!empty($hotofferIndex)) {
                    $db->query("ALTER TABLE `engine4_sitepageoffer_offers` ADD INDEX (`hotoffer`)");
                }
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
        if (!empty($is_sitemobile_object)) {
            $db->query("INSERT IGNORE INTO `engine4_sitemobile_modules` (`name`, `visibility`) VALUES
('sitepageoffer','1')");
            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_sitemobile_modules')
                    ->where('name = ?', 'sitepageoffer')
                    ->where('integrated = ?', 0);
            $is_sitemobile_object = $select->query()->fetchObject();
            if ($is_sitemobile_object) {
                $actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
                $controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
                if ($controllerName == 'manage' && $actionName == 'install') {
                    $view = new Zend_View();
                    $baseUrl = (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"]) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . str_replace('install/', '', $view->url(array(), 'default', true));
                    $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                    $redirector->gotoUrl($baseUrl . 'admin/sitemobile/module/enable-mobile/enable_mobile/1/name/sitepageoffer/integrated/0/redirect/install');
                }
            }
        }

        //Work for the word changes in the page plugin .csv file.
        $actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        if ($controllerName == 'manage' && ($actionName == 'install' || $actionName == 'query')) {
            $view = new Zend_View();
            $baseUrl = (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"]) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . str_replace('install/', '', $view->url(array(), 'default', true));
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
            $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES ("nestedcomment_sitepageoffer_offer", "sitepageoffer", \'{item:$subject} replied to a comment on {item:$owner}\'\'s page offer {item:$object:$title}: {body:$body}\', 1, 1, 1, 1, 1, 1)');
        }
    }

}
