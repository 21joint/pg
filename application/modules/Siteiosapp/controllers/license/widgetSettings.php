<?php

$db = Engine_Db_Table::getDefaultAdapter();
$menuTable = Engine_Api::_()->getDbTable('menuItems', 'core');

$schema = 'http://';
if (!empty($_SERVER["HTTPS"]) && 'on' == strtolower($_SERVER["HTTPS"])) {
    $schema = 'https://';
}
$getHost = $schema . $_SERVER['HTTP_HOST'];
$baseParentUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
$baseParentUrl = @trim($baseParentUrl, "/");
$url = (!empty($baseParentUrl)) ? $getHost . DIRECTORY_SEPARATOR . $baseParentUrl . DIRECTORY_SEPARATOR . 'help/privacy' : $getHost . DIRECTORY_SEPARATOR . 'help/privacy';
if (strstr($url, '/install/')) {
    $url = str_replace('/install/', '/', $url);
}
$select = new Zend_Db_Select($db);
$select->from('engine4_siteiosapp_menus')
        ->where('name = ?', 'privacy_policy');
$rowExists = $select->query()->fetchObject();
if (isset($rowExists) && !empty($rowExists)) {
    $db->query("UPDATE `engine4_siteiosapp_menus` SET `url` = '" . $url . "' WHERE `engine4_siteiosapp_menus`.`name` = 'privacy_policy'");
}

$url = (!empty($baseParentUrl)) ? $getHost . DIRECTORY_SEPARATOR . $baseParentUrl . DIRECTORY_SEPARATOR . 'help/terms' : $getHost . DIRECTORY_SEPARATOR . 'help/terms';
if (strstr($url, '/install/')) {
    $url = str_replace('/install/', '/', $url);
}
$select = new Zend_Db_Select($db);
$select->from('engine4_siteiosapp_menus')
        ->where('name = ?', 'terms_of_service');
$rowExists = $select->query()->fetchObject();
if (isset($rowExists) && !empty($rowExists)) {
    $db->query(" UPDATE `engine4_siteiosapp_menus` SET `url` = '" . $url . "' WHERE `engine4_siteiosapp_menus`.`name` = 'terms_of_service'");
}

$isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_app_create\' LIMIT 1')->fetch();
if (empty($isRowExist)) {
    $row = $menuTable->createRow();
    $row->name = 'siteiosapp_admin_app_create';
    $row->module = 'siteiosapp';
    $row->label = 'iOS App Setup';
    $row->plugin = '';
    $row->params = '{"route":"admin_default","module":"siteiosapp","controller":"app-builder"}';
    $row->menu = 'siteiosapp_admin_main';
    $row->submenu = '';
    $row->order = 10;
    $row->save();
}

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
("siteiosapp_admin_api_menus", "siteiosapp", "App Dashboard Menus", NULL, \'{"route":"admin_default","module":"siteiosapp","controller":"menus", "action":"manage"}\', "siteiosapp_admin_main", NULL, 1, 0, 5);');

$isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_notifications\' LIMIT 1')->fetch();
if (empty($isRowExist)) {
    $row = $menuTable->createRow();
    $row->name = 'siteiosapp_admin_notifications';
    $row->module = 'siteiosapp';
    $row->label = 'Push Notification Settings';
    $row->plugin = '';
    $row->params = '{"route":"admin_default","module":"siteiosapp","controller":"push-notification"}';
    $row->menu = 'siteiosapp_admin_main';
    $row->submenu = '';
    $row->order = 15;
    $row->save();
}

$isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_send_notifications\' LIMIT 1')->fetch();
if (empty($isRowExist)) {
    $row = $menuTable->createRow();
    $row->name = 'siteiosapp_admin_send_notifications';
    $row->module = 'siteiosapp';
    $row->label = 'Send Push Notification';
    $row->plugin = '';
    $row->params = '{"route":"admin_default","module":"siteiosapp","controller":"push-notification","action":"send"}';
    $row->menu = 'siteiosapp_admin_main';
    $row->submenu = '';
    $row->order = 20;
    $row->save();
}

$isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteiosapp_admin_api_management\' LIMIT 1')->fetch();
if (empty($isRowExist)) {
    $row = $menuTable->createRow();
    $row->name = 'siteiosapp_admin_api_management';
    $row->module = 'siteiosapp';
    $row->label = 'API Management';
    $row->plugin = '';
    $row->params = '{"route":"admin_default","module":"siteapi","controller":"settings"}';
    $row->menu = 'siteiosapp_admin_main';
    $row->submenu = '';
    $row->order = 25;
    $row->save();
}

$isRowExist = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE \'siteapi_admin_api_toIos\' LIMIT 1')->fetch();
if (empty($isRowExist)) {
    $row = $menuTable->createRow();
    $row->name = 'siteapi_admin_api_toIos';
    $row->module = 'siteiosapp';
    $row->label = 'iOS Mobile Application';
    $row->plugin = '';
    $row->params = '{"route":"admin_default","module":"siteiosapp","controller":"settings"}';
    $row->menu = 'siteapi_admin_main';
    $row->submenu = '';
    $row->order = 40;
    $row->save();
}