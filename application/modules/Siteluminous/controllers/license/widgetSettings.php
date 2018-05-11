<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: widgetSettings.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$db = Engine_Db_Table::getDefaultAdapter();
$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`)
VALUES 
("siteluminous_admin_settings_images", "siteluminous", "Images", "", \'{"route":"admin_default","module":"siteluminous","controller":"settings","action":"images"}\', "siteluminous_admin_main", "", 1, 0, 2),
("siteluminous_admin_theme_customization", "siteluminous", "Theme Customization", "", \'{"route":"admin_default","module":"siteluminous","controller":"customization"}\', "siteluminous_admin_main", "", 1, 0, 3),
("siteluminous_admin_settings_footer_menu", "siteluminous", "Footer Menu", "", \'{"route":"admin_default","module":"siteluminous","controller":"settings","action":"footer-menu"}\', "siteluminous_admin_main", "", 1, 0, 5);');

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
("siteluminous_footer_first_column", "siteluminous", "First Column", NULL, \'{"uri":"javascript:void(0)"}\', "siteluminous_footer", NULL, "1", "1", "1"),
("siteluminous_footer_first_column_1", "siteluminous", "First Column - 1", "" , \'{"route":"default"}\', "siteluminous_footer", NULL , "1", "1", "2"), 
("siteluminous_footer_first_column_2", "siteluminous", "First Column - 2", "" , \'{"route":"default"}\', "siteluminous_footer", NULL , "1", "1", "3"), 
("siteluminous_footer_first_column_3", "siteluminous", "First Column - 3", "" , \'{"route":"default"}\', "siteluminous_footer", NULL , "1", "1", "4"), 

("siteluminous_footer_second_column", "siteluminous", "Second Column", NULL , \'{"uri":"javascript:void(0)"}\', "siteluminous_footer", NULL , "1", "1", "10"), 
("siteluminous_footer_second_column_1", "siteluminous", "Second Column - 1", "" , \'{"route":"default"}\', "siteluminous_footer", NULL , "1", "1", "11"), 
("siteluminous_footer_second_column_2", "siteluminous", "Second Column - 2", "" , \'{"route":"default"}\', "siteluminous_footer", NULL , "1", "1", "12"), 
("siteluminous_footer_second_column_3", "siteluminous", "Second Column - 3", "" , \'{"route":"default"}\', "siteluminous_footer", NULL , "1", "1", "13"), 


("siteluminous_footer_third_column", "siteluminous", "Third Column", NULL , \'{"uri":"javascript:void(0)"}\', "siteluminous_footer", NULL , "1", "1", "20"), 
("siteluminous_footer_third_column_1", "siteluminous", "Third Column - 1", "" , \'{"route":"default"}\', "siteluminous_footer", NULL , "1", "1", "21"), 
("siteluminous_footer_third_column_2", "siteluminous", "Third Column - 2", "" , \'{"route":"default"}\', "siteluminous_footer", NULL , "1", "1", "22"), 
("siteluminous_footer_third_column_3", "siteluminous", "Third Column - 3", "" , \'{"route":"default"}\', "siteluminous_footer", NULL , "1", "1", "23")
;');

$db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`, `order`) VALUES 
("siteluminous_footer", "standard", "Responsive Luminous Theme - Footer Menu", "1");');

$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'header')
        ->limit(1)
        ->query()
        ->fetchColumn();
if (!empty($pageId)) {
    $contentTable = Engine_Api::_()->getDbtable('content', 'core');
    $contentSelect = $contentTable->select()
            ->where('page_id = ?', $pageId)
            ->where('type =?', 'widget')
            ->where('name = ?', 'sitemenu.menu-main');
    $contentItem = $contentTable->fetchRow($contentSelect);
    if (!empty($contentItem)) {
        $contentItemData = $contentItem->toArray();
        $contentItemData['params']['sitemenu_totalmenu'] = 5;
        $contentItem->params = $contentItemData['params'];
        $contentItem->save();
    }
}

$page_id = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'core_index_index')
        ->limit(1)
        ->query()
        ->fetchColumn();
if (!empty($page_id) && !empty($_POST) && !empty($_POST['landing_page_layout'])) {
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");

    $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'main',
        'page_id' => $page_id,
        'order' => 2,
    ));
    $main_id = $db->lastInsertId();

    // Insert main-middle
    $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'middle',
        'page_id' => $page_id,
        'parent_content_id' => $main_id,
        'order' => 6,
    ));
    $main_middle_id = $db->lastInsertId();

    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'siteluminous.landing-page-css',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'params' => '["[]"]',
        'order' => 3,
    ));

    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'siteluminous.homepage-images',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'params' => '["[]"]',
        'order' => 4,
    ));

    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'siteluminous.landing-search',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'params' => '["[]"]',
        'order' => 5,
    ));

    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'siteluminous.homepage-blocks',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'params' => '["[]"]',
        'order' => 6,
    ));

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteevent')) {
        $slug_plural = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteevent.slugplural', 'event-items');
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'siteevent.events-siteevent',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'params' => '{"title":"Upcoming Events","titleCount":true,"viewType":"gridview","columnWidth":"399","eventType":"0","fea_spo":"newlabel","showEventType":"upcoming","titleLink":"<a href=\"\/' . $slug_plural . '\">Explore All<\/a>","titleLinkPosition":"bottom","photoHeight":"720","photoWidth":"720","titlePosition":"1","columnHeight":"161","popularity":"random","interval":"overall","category_id":"0","subcategory_id":null,"hidden_category_id":"","hidden_subcategory_id":"","hidden_subsubcategory_id":"","eventInfo":["startDate"],"itemCount":"3","truncationLocation":"50","truncation":"16","ratingType":"rating_avg","detactLocation":"0","defaultLocationDistance":"1000","nomobile":"0","name":"siteevent.events-siteevent"}',
            'order' => 7,
        ));
    }

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroup')) {
        $routeStartP = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.manifestUrlP', "groupitems");
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitegroup.recently-popular-random-sitegroup',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'params' => '{"title":"Popular Groups","titleCount":"","layouts_views":["2"],"layouts_oder":"2","layouts_tabs":["3"],"recent_order":"1","popular_order":"2","random_order":"3","featured_order":"4","sponosred_order":"5","list_limit":"3","grid_limit":"3","columnWidth":"399","columnHeight":"161","statistics":["memberCount"],"titleLink":"<a href=\"\/' . $routeStartP . '\">Explore All<\/a>","titleLinkPosition":"bottom","photoHeight":"720","photoWidth":"720","detactLocation":"0","defaultLocationDistance":"1000","listview_turncation":"40","turncation":"40","showlikebutton":"0","showfeaturedLable":"0","showsponsoredLable":"0","showlocation":"0","showgetdirection":"1","showprice":"0","showpostedBy":"0","showdate":"0","category_id":"0","joined_order":"6","loaded_by_ajax":"1","nomobile":"0","name":"sitegroup.recently-popular-random-sitegroup"}',
            'order' => 8,
        ));
    }

    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'siteluminous.homepage-footertext',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'params' => '["[]"]',
        'order' => 9,
    ));

    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'siteluminous.menu-footer',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'params' => '["[]"]',
        'order' => 10,
    ));
}

$isModEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemenu');
if (!empty($isModEnabled)) {
    //START UPDATE MINI MENU WIDGET.
    $select = new Zend_Db_Select($db);
    $select
            ->from('engine4_core_content', array('content_id', 'params'))
            ->where('type =?', 'widget')
            ->where('name =?', 'sitemenu.menu-mini');
    $fetch = $select->query()->fetchAll();
    foreach ($fetch as $modArray) {
        if (!empty($modArray['params'])) {
            $params = Zend_Json::decode($modArray['params']);
            if (is_array($params)) {
                $params['sitemenu_on_logged_out'] = 0;

                $paramss = Zend_Json::encode($params);
                $tableObject = Engine_Api::_()->getDbtable('content', 'core');
                $tableObject->update(array("params" => $paramss), array("content_id =?" => $modArray['content_id']));
            }
        }
    }
    //END UPDATE MINI MENU WIDGET.
}


$global_directory_name = APPLICATION_PATH . '/public/seaocore_themes';
$global_settings_file = $global_directory_name . '/luminousThemeConstants.css';
$is_file_exist = @file_exists($global_settings_file);
if (empty($is_file_exist)) {
    if (!is_dir($global_directory_name)) {
        @mkdir($global_directory_name, 0777);
    }
    @chmod($global_directory_name, 0777);

    $fh = @fopen($global_settings_file, 'w');
    @fwrite($fh, '');
    @fclose($fh);

    @chmod($global_settings_file, 0777);
}