<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$coreSettings = Engine_Api::_()->getApi('settings', 'core');
$db = Engine_Db_Table::getDefaultAdapter();
$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`)
VALUES 
("captivate_admin_settings_images", "captivate", "Images", "", \'{"route":"admin_default","module":"captivate","controller":"settings","action":"images"}\', "captivate_admin_main", "", 1, 0, 3),
("captivate_admin_theme_customization", "captivate", "Theme Customization", "", \'{"route":"admin_default","module":"captivate","controller":"customization"}\', "captivate_admin_main", "", 1, 0, 4),
("captivate_admin_settings_banners", "captivate", "Banners", "", \'{"route":"admin_default","module":"captivate","controller":"settings","action":"banners"}\', "captivate_admin_main", "", 1, 0, 5),
("captivate_admin_footer_templates", "captivate", "Footer Templates", "", \'{"route":"admin_default","module":"captivate","controller":"footer-templates"}\', "captivate_admin_main", "", 1, 0, 6),
("captivate_admin_settings_footer_menu", "captivate", "Footer Menu", "", \'{"route":"admin_default","module":"captivate","controller":"settings","action":"footer-menu"}\', "captivate_admin_main", "", 1, 0, 7),
("captivate_admin_layout_index", "captivate", "Layout Settings", "", \'{"route":"admin_default","module":"captivate","controller":"layout", "action": "index"}\', "captivate_admin_main", "", 1, 0, 2)');

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
("captivate_footer_first_column", "captivate", "First Column", NULL, \'{"uri":"javascript:void(0)"}\', "captivate_footer", NULL, "1", "1", "1"),
("captivate_footer_first_column_1", "captivate", "First Column - 1", "" , \'{"route":"default"}\', "captivate_footer", NULL , "1", "1", "2"), 
("captivate_footer_first_column_2", "captivate", "First Column - 2", "" , \'{"route":"default"}\', "captivate_footer", NULL , "1", "1", "3"), 
("captivate_footer_first_column_3", "captivate", "First Column - 3", "" , \'{"route":"default"}\', "captivate_footer", NULL , "1", "1", "4"), 

("captivate_footer_second_column", "captivate", "Second Column", NULL , \'{"uri":"javascript:void(0)"}\', "captivate_footer", NULL , "1", "1", "10"), 
("captivate_footer_second_column_1", "captivate", "Second Column - 1", "" , \'{"route":"default"}\', "captivate_footer", NULL , "1", "1", "11"), 
("captivate_footer_second_column_2", "captivate", "Second Column - 2", "" , \'{"route":"default"}\', "captivate_footer", NULL , "1", "1", "12"), 
("captivate_footer_second_column_3", "captivate", "Second Column - 3", "" , \'{"route":"default"}\', "captivate_footer", NULL , "1", "1", "13"), 


("captivate_footer_third_column", "captivate", "Third Column", NULL , \'{"uri":"javascript:void(0)"}\', "captivate_footer", NULL , "1", "1", "20"), 
("captivate_footer_third_column_1", "captivate", "Third Column - 1", "" , \'{"route":"default"}\', "captivate_footer", NULL , "1", "1", "21"), 
("captivate_footer_third_column_2", "captivate", "Third Column - 2", "" , \'{"route":"default"}\', "captivate_footer", NULL , "1", "1", "22"), 
("captivate_footer_third_column_3", "captivate", "Third Column - 3", "" , \'{"route":"default"}\', "captivate_footer", NULL , "1", "1", "23")
;');

$db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`, `order`) VALUES 
("captivate_footer", "standard", "Responsive Captivate Theme - Footer Menu", "1");');

Engine_Api::_()->captivate()->setDefaultLayout($_POST);

$global_directory_name = APPLICATION_PATH . '/public/seaocore_themes';
$global_settings_file = $global_directory_name . '/captivateThemeConstants.css';
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
$tableNameContent = Engine_Api::_()->getDbtable('content', 'core');
$tableNameContentName = $tableNameContent->info('name');

$db = Engine_Db_Table::getDefaultAdapter();
$select = new Zend_Db_Select($db);
$select
        ->from('engine4_core_modules')
        ->where('name = ?', 'sitecontentcoverphoto')
        ->where('enabled = ?', 1);
$is_sitecontentcoverphoto_object = $select->query()->fetchObject();
if ($is_sitecontentcoverphoto_object) {

    $select = new Zend_Db_Select($db);
    $page_id = $select
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'siteevent_index_view')
            ->query()
            ->fetchColumn();
    if ($page_id) {
        $db->query('UPDATE `engine4_core_content` SET `params` = \'{"modulename":"siteevent_event","showContent_0":"","showContent_siteevent_event":["title","joinButton","inviteGuest","updateInfoButton","inviteRsvpButton","optionsButton","venue","startDate","endDate","location","hostName", "addToMyCalendar","shareOptions"],"profile_like_button":"0","columnHeight":"400","sitecontentcoverphotoChangeTabPosition":"1","contacts":"","showMemberLevelBasedPhoto":"1","emailme":"1","editFontColor":"0","contentFullWidth":"1","title":"","nomobile":"0","name":"sitecontentcoverphoto.content-cover-photo"}\' WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1;');
        $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "siteevent.add-to-my-calendar-siteevent" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');
        $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "siteevent.list-profile-breadcrumb" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');

        $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "seaocore.social-share-buttons" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');

        $select = new Zend_Db_Select($db);
        $content_id = $select
                ->from('engine4_core_content', 'content_id')
                ->where('name = ?', 'top')
                ->where('page_id = ?', $page_id)
                ->query()
                ->fetchColumn();

        if ($content_id) {
            $select = new Zend_Db_Select($db);
            $content_id = $select
                    ->from('engine4_core_content', 'content_id')
                    ->where('name = ?', 'middle')
                    ->where('parent_content_id = ?', $content_id)
                    ->where('page_id = ?', $page_id)
                    ->query()
                    ->fetchColumn();
            if ($content_id) {
                $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $content_id . '" WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1;');
            }
        }
    }


    $select = new Zend_Db_Select($db);
    $page_id = $select
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sitealbum_album_view')
            ->query()
            ->fetchColumn();
    if ($page_id) {
        $db->query('UPDATE `engine4_core_content` SET `params` = \'{"modulename":"album","showContent_0":"","showContent_album":["mainPhoto","title","owner","description","totalPhotos","viewCount","likeCount","commentCount","location","CategoryLink","updateDate","optionsButton","shareOptions"],"showContent_sitebusiness_business":"","showContent_siteevent_event":"","showContent_sitegroup_group":"","showContent_sitepage_page":"","showContent_sitestore_store":"","showContent_sitestoreproduct_product":"","profile_like_button":"1","columnHeight":"235","showMember":"1","memberCount":"8","onlyMemberWithPhoto":"1","contentFullWidth":"1","sitecontentcoverphotoChangeTabPosition":"1","contacts":"","showMemberLevelBasedPhoto":"1","emailme":"1","editFontColor":"0","title":"","nomobile":"0","name":"sitecontentcoverphoto.content-cover-photo"}\' WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1;');
        $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sitealbum.profile-breadcrumb" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');

        $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "seaocore.social-share-buttons" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');
    }

    if (!$coreSettings->getSetting('sitepage.layoutcreate', 0)) {
        $select = new Zend_Db_Select($db);
        $page_id = $select
                ->from('engine4_core_pages', 'page_id')
                ->where('name = ?', 'sitepage_index_view')
                ->query()
                ->fetchColumn();
        if ($page_id) {

            $top_content_id = $tableNameContent->select()
                    ->from($tableNameContentName, 'content_id')
                    ->where('page_id =?', $page_id)
                    ->where('name =?', 'top')
                    ->query()
                    ->fetchColumn();
            if (empty($top_content_id)) {
                $db->insert('engine4_core_content', array(
                    'type' => 'container',
                    'name' => 'top',
                    'page_id' => $page_id,
                    'parent_content_id' => null,
                    'order' => 1,
                    'params' => ''
                ));
                $content_id = $db->lastInsertId('engine4_core_content');
                $middle_content_id = $tableNameContent->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $page_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'middle')
                        ->query()
                        ->fetchColumn();

                if (empty($middle_content_id)) {
                    $db->insert('engine4_core_content', array(
                        'type' => 'container',
                        'name' => 'middle',
                        'page_id' => $page_id,
                        'parent_content_id' => $content_id,
                        'order' => 2,
                        'params' => ''
                    ));

                    $content_id = $db->lastInsertId('engine4_core_content');
                    if ($content_id) {
                        $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $content_id . '" WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1;');
                    }
                }
            } else {
                $middle_content_id = $tableNameContent->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $page_id)
                        ->where('parent_content_id =?', $top_content_id)
                        ->where('name =?', 'middle')
                        ->query()
                        ->fetchColumn();

                if (!empty($middle_content_id)) {
                    $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $middle_content_id . '" WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1;');
                }
            }

            $db->query('UPDATE `engine4_core_content` SET `params` = \'{"modulename":"sitepage_page","showContent_0":"","showContent_siteevent_event":"","showContent_sitepage_page":["mainPhoto","title","followButton","likeCount","followCount","optionsButton","shareOptions"],"profile_like_button":"1","columnHeight":"400","contentFullWidth":"1","sitecontentcoverphotoChangeTabPosition":"1","contacts":"","showMemberLevelBasedPhoto":"1","emailme":"1","editFontColor":"0","title":"","nomobile":"0","name":"sitecontentcoverphoto.content-cover-photo"}\' WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1;');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sitepage.page-profile-breadcrumb" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "seaocore.social-share-buttons" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sitepage.thumbphoto-sitepage" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');
        }
    }

    if (!$coreSettings->getSetting('sitebusiness.layoutcreate', 0)) {
        $select = new Zend_Db_Select($db);
        $business_id = $select
                ->from('engine4_core_pages', 'page_id')
                ->where('name = ?', 'sitebusiness_index_view')
                ->query()
                ->fetchColumn();
        if ($business_id) {

            $top_content_id = $tableNameContent->select()
                    ->from($tableNameContentName, 'content_id')
                    ->where('page_id =?', $business_id)
                    ->where('name =?', 'top')
                    ->query()
                    ->fetchColumn();
            if (empty($top_content_id)) {
                $db->insert('engine4_core_content', array(
                    'type' => 'container',
                    'name' => 'top',
                    'page_id' => $business_id,
                    'parent_content_id' => null,
                    'order' => 1,
                    'params' => ''
                ));
                $content_id = $db->lastInsertId('engine4_core_content');
                $middle_content_id = $tableNameContent->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $business_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'middle')
                        ->query()
                        ->fetchColumn();

                if (empty($middle_content_id)) {
                    $db->insert('engine4_core_content', array(
                        'type' => 'container',
                        'name' => 'middle',
                        'page_id' => $business_id,
                        'parent_content_id' => $content_id,
                        'order' => 2,
                        'params' => ''
                    ));

                    $content_id = $db->lastInsertId('engine4_core_content');
                    if ($content_id) {
                        $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $content_id . '" WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $business_id . '" LIMIT 1;');
                    }
                }
            } else {
                $middle_content_id = $tableNameContent->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $business_id)
                        ->where('parent_content_id =?', $top_content_id)
                        ->where('name =?', 'middle')
                        ->query()
                        ->fetchColumn();

                if (!empty($middle_content_id)) {
                    $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $middle_content_id . '" WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $business_id . '" LIMIT 1;');
                }
            }

            $db->query('UPDATE `engine4_core_content` SET `params` = \'{"modulename":"sitebusiness_business","showContent_0":"","showContent_siteevent_event":"","showContent_sitebusiness_business":["mainPhoto","title","followButton","likeCount","followCount","optionsButton","shareOptions"],"profile_like_button":"1","columnHeight":"400","contentFullWidth":"1","sitecontentcoverphotoChangeTabPosition":"1","contacts":"","showMemberLevelBasedPhoto":"1","emailme":"1","editFontColor":"0","title":"","nomobile":"0","name":"sitecontentcoverphoto.content-cover-photo"}\' WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $business_id . '" LIMIT 1;');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sitebusiness.business-profile-breadcrumb" AND `engine4_core_content`.`page_id` = "' . $business_id . '" LIMIT 1');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "seaocore.social-share-buttons" AND `engine4_core_content`.`page_id` = "' . $business_id . '" LIMIT 1');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sitebusiness.thumbphoto-sitebusiness" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');
        }
    }

    if (!$coreSettings->getSetting('sitegroup.layoutcreate', 0)) {
        $select = new Zend_Db_Select($db);
        $group_id = $select
                ->from('engine4_core_pages', 'page_id')
                ->where('name = ?', 'sitegroup_index_view')
                ->query()
                ->fetchColumn();
        if ($group_id) {

            $top_content_id = $tableNameContent->select()
                    ->from($tableNameContentName, 'content_id')
                    ->where('page_id =?', $group_id)
                    ->where('name =?', 'top')
                    ->query()
                    ->fetchColumn();
            if (empty($top_content_id)) {
                $db->insert('engine4_core_content', array(
                    'type' => 'container',
                    'name' => 'top',
                    'page_id' => $group_id,
                    'parent_content_id' => null,
                    'order' => 1,
                    'params' => ''
                ));
                $content_id = $db->lastInsertId('engine4_core_content');
                $middle_content_id = $tableNameContent->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $group_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'middle')
                        ->query()
                        ->fetchColumn();

                if (empty($middle_content_id)) {
                    $db->insert('engine4_core_content', array(
                        'type' => 'container',
                        'name' => 'middle',
                        'page_id' => $group_id,
                        'parent_content_id' => $content_id,
                        'order' => 2,
                        'params' => ''
                    ));

                    $content_id = $db->lastInsertId('engine4_core_content');
                    if ($content_id) {
                        $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $content_id . '" WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $group_id . '" LIMIT 1;');
                    }
                }
            } else {
                $middle_content_id = $tableNameContent->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $group_id)
                        ->where('parent_content_id =?', $top_content_id)
                        ->where('name =?', 'middle')
                        ->query()
                        ->fetchColumn();

                if (!empty($middle_content_id)) {
                    $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $middle_content_id . '" WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $group_id . '" LIMIT 1;');
                }
            }

            $db->query('UPDATE `engine4_core_content` SET `params` = \'{"modulename":"sitegroup_group","showContent_0":"","showContent_siteevent_event":"","showContent_sitegroup_group":["mainPhoto","title","followButton","likeCount","followCount","optionsButton","shareOptions"],"profile_like_button":"1","columnHeight":"400","contentFullWidth":"1","sitecontentcoverphotoChangeTabPosition":"1","contacts":"","showMemberLevelBasedPhoto":"1","emailme":"1","editFontColor":"0","title":"","nomobile":"0","name":"sitecontentcoverphoto.content-cover-photo"}\' WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $group_id . '" LIMIT 1;');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sitegroup.group-profile-breadcrumb" AND `engine4_core_content`.`page_id` = "' . $group_id . '" LIMIT 1');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "seaocore.social-share-buttons" AND `engine4_core_content`.`page_id` = "' . $group_id . '" LIMIT 1');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sitegroup.thumbphoto-sitegroup" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');
        }
    }

    if (!$coreSettings->getSetting('sitestore.layoutcreate', 0)) {
        $select = new Zend_Db_Select($db);
        $store_id = $select
                ->from('engine4_core_pages', 'page_id')
                ->where('name = ?', 'sitestore_index_view')
                ->query()
                ->fetchColumn();
        if ($store_id) {

            $top_content_id = $tableNameContent->select()
                    ->from($tableNameContentName, 'content_id')
                    ->where('page_id =?', $store_id)
                    ->where('name =?', 'top')
                    ->query()
                    ->fetchColumn();
            if (empty($top_content_id)) {
                $db->insert('engine4_core_content', array(
                    'type' => 'container',
                    'name' => 'top',
                    'page_id' => $store_id,
                    'parent_content_id' => null,
                    'order' => 1,
                    'params' => ''
                ));
                $content_id = $db->lastInsertId('engine4_core_content');
                $middle_content_id = $tableNameContent->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $store_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'middle')
                        ->query()
                        ->fetchColumn();

                if (empty($middle_content_id)) {
                    $db->insert('engine4_core_content', array(
                        'type' => 'container',
                        'name' => 'middle',
                        'page_id' => $store_id,
                        'parent_content_id' => $content_id,
                        'order' => 2,
                        'params' => ''
                    ));

                    $content_id = $db->lastInsertId('engine4_core_content');
                    if ($content_id) {
                        $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $content_id . '" WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $store_id . '" LIMIT 1;');
                    }
                }
            } else {
                $middle_content_id = $tableNameContent->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $store_id)
                        ->where('parent_content_id =?', $top_content_id)
                        ->where('name =?', 'middle')
                        ->query()
                        ->fetchColumn();

                if (!empty($middle_content_id)) {
                    $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $middle_content_id . '" WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $store_id . '" LIMIT 1;');
                }
            }

            $db->query('UPDATE `engine4_core_content` SET `params` = \'{"modulename":"sitestore_store","showContent_0":"","showContent_siteevent_event":"","showContent_sitestore_store":["mainPhoto","title","followButton","likeCount","followCount","optionsButton","shareOptions"],"profile_like_button":"1","columnHeight":"400","contentFullWidth":"1","sitecontentcoverphotoChangeTabPosition":"1","contacts":"","showMemberLevelBasedPhoto":"1","emailme":"1","editFontColor":"0","title":"","nomobile":"0","name":"sitecontentcoverphoto.content-cover-photo"}\' WHERE `engine4_core_content`.`name` = "sitecontentcoverphoto.content-cover-photo" AND `engine4_core_content`.`page_id` = "' . $store_id . '" LIMIT 1;');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sitestore.store-profile-breadcrumb" AND `engine4_core_content`.`page_id` = "' . $store_id . '" LIMIT 1');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "seaocore.social-share-buttons" AND `engine4_core_content`.`page_id` = "' . $store_id . '" LIMIT 1');
            $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sitestore.thumbphoto-sitestore" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');
        }
    }
}
$select = new Zend_Db_Select($db);
$select
        ->from('engine4_core_modules')
        ->where('name = ?', 'siteusercoverphoto')
        ->where('enabled = ?', 1);
$is_siteusercoverphotoobject = $select->query()->fetchObject();
if ($is_siteusercoverphotoobject) {
    $select = new Zend_Db_Select($db);
    $page_id = $select
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'user_profile_index')
            ->query()
            ->fetchColumn();
    if ($page_id) {
        $coreSettings->setSetting('siteusercoverphoto.content.full.width', 1);
        $top_content_id = $tableNameContent->select()
                ->from($tableNameContentName, 'content_id')
                ->where('page_id =?', $page_id)
                ->where('name =?', 'top')
                ->query()
                ->fetchColumn();
        if (empty($top_content_id)) {
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'parent_content_id' => null,
                'order' => 1,
                'params' => ''
            ));
            $content_id = $db->lastInsertId('engine4_core_content');
            $middle_content_id = $tableNameContent->select()
                    ->from($tableNameContentName, 'content_id')
                    ->where('page_id =?', $page_id)
                    ->where('parent_content_id =?', $content_id)
                    ->where('name =?', 'middle')
                    ->query()
                    ->fetchColumn();

            if (empty($middle_content_id)) {
                $db->insert('engine4_core_content', array(
                    'type' => 'container',
                    'name' => 'middle',
                    'page_id' => $page_id,
                    'parent_content_id' => $content_id,
                    'order' => 2,
                    'params' => ''
                ));

                $content_id = $db->lastInsertId('engine4_core_content');
                if ($content_id) {
                    $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $content_id . '" WHERE `engine4_core_content`.`name` = "siteusercoverphoto.user-cover-photo" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1;');
                }
            }
        } else {
            $middle_content_id = $tableNameContent->select()
                    ->from($tableNameContentName, 'content_id')
                    ->where('page_id =?', $page_id)
                    ->where('parent_content_id =?', $top_content_id)
                    ->where('name =?', 'middle')
                    ->query()
                    ->fetchColumn();

            if (!empty($middle_content_id)) {
                $db->query('UPDATE `engine4_core_content` SET `parent_content_id` =  "' . $middle_content_id . '" WHERE `engine4_core_content`.`name` = "siteusercoverphoto.user-cover-photo" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1;');
            }
        }

        $db->query('UPDATE `engine4_core_content` SET `params` = \'{"title":"","titleCount":"","showContent":["mainPhoto","title","updateInfoButton","settingsButton","optionsButton","friendShipButton","composeMessageButton"],"profile_like_button":"1","columnHeight":"400","editFontColor":"0","nomobile":"0","name":"siteusercoverphoto.user-cover-photo"}\' WHERE `engine4_core_content`.`name` = "siteusercoverphoto.user-cover-photo" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1;');
        $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sitealbum.photo-strips" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');
        $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "user.profile-status" AND `engine4_core_content`.`page_id` = "' . $page_id . '" LIMIT 1');

        $db->query("UPDATE `engine4_core_content` SET  `order` =  '2' WHERE  `engine4_core_content`.`page_id` = $page_id AND `engine4_core_content`.`name` = 'main' LIMIT 1 ;");
    }
}