<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    install.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_Installer extends Engine_Package_Installer_Module {

    public function onInstall() {
        $this->_addHashtagResultPage();
        parent::onInstall();
        $db = $this->getDb();
        $isContentTableExist = $db->query("SHOW TABLES LIKE 'engine4_siteadvsearch_contents'")->fetch();
        if (!empty($isContentTableExist)) {
            $db->query("INSERT IGNORE INTO `engine4_siteadvsearch_contents` ( `module_name`, `resource_type`, `resource_title`, `listingtype_id`, `widgetize`, `content_tab`, `main_search`, `order`, `file_id`, `default`, `enabled`) VALUES ('sitehashtag', 'sitehashtag_hashtag', 'Hashtags', 0, 1, 1, 1, 11, 0, 1, 1)");
        }

        $siteforum = $db->select()
                ->from('engine4_core_modules')
                ->where('name = ?', 'siteforum')
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!empty($siteforum)) {
            $table_exist = $db->query("SHOW TABLES LIKE 'engine4_sitehashtag_contents'")->fetch();
            if ($table_exist) {
                $db->query('INSERT IGNORE INTO `engine4_sitehashtag_contents` (`module_name`, `resource_type`, `enabled`) VALUES ("siteforum", "siteforum", 1);');
            }
        }

        $sitenews = $db->select()
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitenews')
                ->limit(1)
                ->query()
                ->fetchColumn();

        if (!empty($sitenews)) {
            $isModExist = $db->query("SELECT * FROM `engine4_sitehashtag_contents` WHERE `module_name` LIKE 'sitenews' LIMIT 1")->fetch();
            if (empty($isModExist)) {
                $db->query('INSERT IGNORE INTO `engine4_sitehashtag_contents` (`module_name`, `resource_type`, `enabled`) VALUES ("sitenews", "sitenews", 1);');
            }
        }
    }

    protected function _addHashtagResultPage() {
        $db = $this->getDb();

        // profile page
        $page_id = $db->select()
                ->from('engine4_core_pages', 'page_id')
                ->where('name = ?', 'sitehashtag_index_index')
                ->limit(1)
                ->query()
                ->fetchColumn();

        // insert if it doesn't exist yet
        if (!$page_id) {
            // Insert page
            $db->insert('engine4_core_pages', array(
                'name' => 'sitehashtag_index_index',
                'displayname' => 'Hashtag - Searched Results Page',
                'title' => 'Hashtag - Searched Results Page',
                'description' => 'This page displays activity feeds searched using hashtags.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

//            // Insert top
//            $db->insert('engine4_core_content', array(
//                'type' => 'container',
//                'name' => 'top',
//                'page_id' => $page_id,
//                'order' => 1,
//            ));
//            $top_id = $db->lastInsertId();
            // Insert main
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => 2,
            ));
            $main_id = $db->lastInsertId();

//            // Insert top-middle
//            $db->insert('engine4_core_content', array(
//                'type' => 'container',
//                'name' => 'middle',
//                'page_id' => $page_id,
//                'parent_content_id' => $top_id,
//            ));
//            $top_middle_id = $db->lastInsertId();
//
//            // Insert main-left
//            $db->insert('engine4_core_content', array(
//                'type' => 'container',
//                'name' => 'left',
//                'page_id' => $page_id,
//                'parent_content_id' => $main_id,
//                'order' => 1,
//            ));
//            $main_left_id = $db->lastInsertId();
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

            // Insert Top Trends
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'sitehashtag.hashtags',
                'page_id' => $page_id,
                'parent_content_id' => $main_right_id,
                'order' => 1,
                'params' => '{"title":"Top Trends"}'
            ));

            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'sitehashtag.search-form',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 1,
            ));
            // Insert content
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'core.content',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 2,
            ));

            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'advancedactivity.home-feeds',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 3,
                'params' => '{"title":"","advancedactivity_tabs":["aaffeed"],"showPosts":"0","showTabs":"0","loadByAjax":"0","showScrollTopButton":"0","widthphotoattachment":"440","width1":"410","width2":"410","height2":"250","width3big":"410","height3big":"250","width3small":"200","height3small":"250","width4big":"410","height4big":"250","width4small":"130","height4small":"100","width5big":"200","height5big":"150","width5small":"130","height5small":"100","nomobile":"0","name":"advancedactivity.home-feeds"}'
            ));
        }
    }

    public function onPostInstall() {
        //SITEMOBILE CODE TO CALL MY.SQL ON POST INSTALL
        $moduleName = 'sitehashtag';
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
        //END - SITEMOBILE CODE TO CALL MY.SQL ON POST INSTALL
    }

}
