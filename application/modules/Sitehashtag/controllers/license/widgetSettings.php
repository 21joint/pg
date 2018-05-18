<?php 

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    widgetSetting.php 2015-11-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */


$coreSettingsApi = Engine_Api::_()->getApi('settings', 'core');

$menuOptions = $coreSettingsApi->getSetting('advancedactivity.composer.menuoptions', Engine_Api::_()->advancedactivity()->getComposerMenuList());

if(!in_array('hashtagXXXsitehashtag', $menuOptions)) {
    $menuOptions[] = 'hashtagXXXsitehashtag';
    $coreSettingsApi->setSetting('advancedactivity.composer.menuoptions', $menuOptions);
}

$db = Engine_Db_Table::getDefaultAdapter();

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sitehashtag_admin_main_manage", "sitehashtag", "Manage Modules", "", \'{"route":"admin_default","module":"sitehashtag","controller":"manage"}\', "sitehashtag_admin_main", "", 5);');

$db->query('INSERT IGNORE INTO `engine4_sitehashtag_contents` (`module_name`, `resource_type`, `enabled`) VALUES
("sitepage", "sitepage_page", 1),
("sitebusiness", "sitebusiness_business", 1),
("sitegroup", "sitegroup_group", 1),
("siteevent", "siteevent_event", 1),
("sitestore", "sitestore_store", 1),
("document", "document", 1),
("recipe", "recipe", 1),
("list", "list_listing", 1),
("sitefaq", "sitefaq_faq", 1),
("sitetutorial", "sitetutorial_tutorial", 1),
("feedback", "feedback", 1),
("sitereview", "sitereview_listing", 1),
("album", "album", 1),
("music", "music_playlist", 1),
("video", "video", 1),
("blog", "blog", 1),
("group", "group", 1),
("event", "event", 1),
("classified", "classified", 1),
("forum", "forum", 1),
("poll", "poll", 1),
("advancedactivity", "status", 1);');