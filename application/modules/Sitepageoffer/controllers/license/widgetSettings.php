<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: WidgetSettings.php 6590 2010-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
//START LANGUAGE WORK
Engine_Api::_()->getApi('language', 'sitepage')->languageChanges();
//END LANGUAGE WORK 
//GET DB
$db = Zend_Db_Table_Abstract::getDefaultAdapter();

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
    //PUT SITEPAGE OFFER TAB AT SITEPAGE PROFILE PAGE
    $select = new Zend_Db_Select($db);
    $select_page = $select
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sitepage_index_view')
            ->limit(1);
    $page = $select_page->query()->fetchAll();
    if (!empty($page)) {
        $page_id = $page[0]['page_id'];
        //INSERTING THE DOCUMENT WIDGET IN SITEPAGE_ADMIN_CONTENT TABLE ALSO.
        Engine_Api::_()->getDbtable('admincontent', 'sitepage')->setAdminDefaultInfo('sitepageoffer.profile-sitepageoffers', $page_id, 'Offers', 'true', '116');

        //INSERTING THE DOCUMENT WIDGET IN CORE_CONTENT TABLE ALSO.
        Engine_Api::_()->getApi('layoutcore', 'sitepage')->setContentDefaultInfo('sitepageoffer.profile-sitepageoffers', $page_id, 'Offers', 'true', '116');

        //INSERTING THE DOCUMENT WIDGET IN SITEPAGE_CONTENT TABLE ALSO.
        $select = new Zend_Db_Select($db);
        $contentpage_ids = $select->from('engine4_sitepage_contentpages', 'contentpage_id')->query()->fetchAll();
        foreach ($contentpage_ids as $contentpage_id) {
            if (!empty($contentpage_id)) {
                Engine_Api::_()->getDbtable('content', 'sitepage')->setDefaultInfo('sitepageoffer.profile-sitepageoffers', $contentpage_id['contentpage_id'], 'Offers', 'true', '116');
            }
        }
    }

    //PUT TOP RATED WIDGET AT PAGE HOME
    $select = new Zend_Db_Select($db);
    $select_page = $select
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sitepage_index_home')
            ->limit(1);
    $page = $select_page->query()->fetchAll();
    if (!empty($page)) {
        $page_id = $page[0]['page_id'];
        $select = new Zend_Db_Select($db);
        $select_content = $select
                ->from('engine4_core_content')
                ->where('page_id = ?', $page_id)
                ->where('type = ?', 'widget')
                ->where('name = ?', 'sitepageoffer.sitepage-hotoffer')
                ->limit(1);
        $content = $select_content->query()->fetchAll();
        if (empty($content)) {
            $select = new Zend_Db_Select($db);
            $select_container = $select
                    ->from('engine4_core_content', 'content_id')
                    ->where('page_id = ?', $page_id)
                    ->where('type = ?', 'container')
                    ->where('name = ?', 'main')
                    ->limit(1);
            $container = $select_container->query()->fetchAll();
            if (!empty($container)) {
                $container_id = $container[0]['content_id'];

                $select = new Zend_Db_Select($db);
                $select_left = $select
                        ->from('engine4_core_content')
                        ->where('parent_content_id = ?', $container_id)
                        ->where('type = ?', 'container')
                        ->where('name = ?', 'left')
                        ->limit(1);
                $left = $select_left->query()->fetchAll();
                if (!empty($left)) {
                    $left_id = $left[0]['content_id'];
                    $select = new Zend_Db_Select($db);
                    $select_tab = $select
                            ->from('engine4_core_content')
                            ->where('type = ?', 'widget')
                            ->where('name = ?', 'core.container-tabs')
                            ->where('page_id = ?', $page_id)
                            ->limit(1);
                    $tab = $select_tab->query()->fetchAll();
                    if (!empty($tab)) {
                        $tab_id = $tab[0]['content_id'];
                    }

                    $db->insert('engine4_core_content', array(
                        'page_id' => $page_id,
                        'type' => 'widget',
                        'name' => 'sitepageoffer.sitepage-hotoffer',
                        'parent_content_id' => ($left_id ? $left_id : $tab_id),
                        'order' => 999,
                        'params' => '{"title":"Hot Page Offers","titleCount":"true"}',
                    ));
                }
            }
        }
    }
    $contentTable = Engine_Api::_()->getDbtable('content', 'core');
    $contentTableName = $contentTable->info('name');

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
    ;
    $rowinfo = $select->query()->fetch();

    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_pages')
            ->where('name = ?', 'sitepageoffer_index_browse')
            ->limit(1);
    ;
    $info = $select->query()->fetch();
    if (empty($info)) {
        $db->insert('engine4_core_pages', array(
            'name' => 'sitepageoffer_index_browse',
            'displayname' => 'Page Offers',
            'title' => 'Page Offers List',
            'description' => 'This is the page offers.',
            'custom' => 1,
        ));
        $page_id = $db->lastInsertId('engine4_core_pages');
//INSERT MAIN CONTAINER
        $mainContainer = $contentTable->createRow();
        $mainContainer->page_id = $page_id;
        $mainContainer->type = 'container';
        $mainContainer->name = 'main';
        $mainContainer->order = 2;
        $mainContainer->save();
        $container_id = $mainContainer->content_id;

//INSERT MAIN - MIDDLE CONTAINER
        $mainMiddleContainer = $contentTable->createRow();
        $mainMiddleContainer->page_id = $page_id;
        $mainMiddleContainer->type = 'container';
        $mainMiddleContainer->name = 'middle';
        $mainMiddleContainer->parent_content_id = $container_id;
        $mainMiddleContainer->order = 6;
        $mainMiddleContainer->save();
        $middle_id = $mainMiddleContainer->content_id;

//INSERT MAIN - RIGHT CONTAINER
        $mainRightContainer = $contentTable->createRow();
        $mainRightContainer->page_id = $page_id;
        $mainRightContainer->type = 'container';
        $mainRightContainer->name = 'right';
        $mainRightContainer->parent_content_id = $container_id;
        $mainRightContainer->order = 5;
        $mainRightContainer->save();
        $right_id = $mainRightContainer->content_id;

//INSERT TOP CONTAINER
        $topContainer = $contentTable->createRow();
        $topContainer->page_id = $page_id;
        $topContainer->type = 'container';
        $topContainer->name = 'top';
        $topContainer->order = 1;
        $topContainer->save();
        $top_id = $topContainer->content_id;

//INSERT TOP- MIDDLE CONTAINER
        $topMiddleContainer = $contentTable->createRow();
        $topMiddleContainer->page_id = $page_id;
        $topMiddleContainer->type = 'container';
        $topMiddleContainer->name = 'middle';
        $topMiddleContainer->parent_content_id = $top_id;
        $topMiddleContainer->order = 6;
        $topMiddleContainer->save();
        $top_middle_id = $topMiddleContainer->content_id;

        //INSERT NAVIGATION WIDGET
        Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepage.browsenevigation-sitepage', $top_middle_id, 1);

        //INSERT OFFER WIDGET
        Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepageoffer.sitepage-offer', $middle_id, 2);

        //INSERT SEARCH PAGE OFFER WIDGET
        Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepageoffer.search-sitepageoffer', $right_id, 3, "", "true");

        //INSERT HOT PAGE OFFER WIDGET
        Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepageoffer.sitepage-hotoffer', $right_id, 4, "Hot Page Offers", "true");

        //INSERT LATEST PAGE OFFER WIDGET
        Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepageoffer.sitepage-latestoffer', $right_id, 5, "Latest Page Offers", "true");

        //INSERT SPONSORED PAGE OFFER WIDGET
        Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepageoffer.sitepage-sponsoredoffer', $right_id, 6, "Sponsored Offers", "true");

        //INSERT AVAILABLE PAGE OFFER WIDGET
        Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepageoffer.sitepage-dateoffer', $right_id, 7, "Available Offers", "true");

        if ($infomation && $rowinfo) {

            //INSERT PAGE ADA WIDGET
            Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepage.page-ads', $right_id, 7, "", "true");
        }
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
            Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepage.page-ads', $right_id, 4, "", "true");
        }
    }

    $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitepageoffer_admin_main_offer_tab", "sitepageoffer", "Tabbed Offers Widget", "", \'{"route":"admin_default","module":"sitepageoffer","controller":"settings", "action": "widget"}\', "sitepageoffer_admin_main", "", 1, 0, 3)');

    $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sitepageoffer_admin_main_dayitems", "sitepageoffer", "Offer of the Day", "", \'{"route":"admin_default","module":"sitepageoffer","controller":"settings", "action": "manage-day-items"}\', "sitepageoffer_admin_main", "", 1, 0, 4)');

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

        //INSERT HOT PAGE OFFER WIDGET
        Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepageoffer.sitepage-hotoffer', $left_id, 15, "Hot Page Offers", "true");

        //INSERT LATEST PAGE OFFER WIDGET
        Engine_Api::_()->sitepage()->setDefaultDataWidget($contentTable, $contentTableName, $page_id, 'widget', 'sitepageoffer.sitepage-latestoffer', $left_id, 16, "Latest Page Offers", "true");


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

    $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES("sitepageoffer_admin_main_fields", "sitepageoffer", "Offer Questions", "", \'{"route":"admin_default","module":"sitepageoffer","controller":"fields"}\', "sitepageoffer_admin_main", "", 4)');
}
?>