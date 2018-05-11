<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: widgetSettings.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$metatable = Engine_Api::_()->fields()->getTable('user', 'meta');
$select = $metatable->select()->from($metatable->info('name'), 'count(*) as count')->where('type =?', 'profile_type');
$count = $select->query()->fetchColumn();

$selectFirstLastName = $metatable->select()->from($metatable->info('name'), 'count(*) as count')->where('type IN (?)', array('first_name', 'last_name'));

$firstlastnameExists = $selectFirstLastName->query()->fetchColumn();

if ($count == 1 && $firstlastnameExists && !Engine_Api::_()->getApi('settings', 'core')->getSetting('sitequicksignup.checkfields')) {
    $metatable->update(array('show' => 0), "type NOT IN ('profile_type', 'first_name', 'last_name')"
    );
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sitequicksignup.checkfields', 1);
}

$tableNameContent = Engine_Api::_()->getDbtable('content', 'core');
$header_page_id = Engine_Api::_()->sitequicksignup()->getWidgetizedPageId(array('name' => 'header'));
$main_content_id = $tableNameContent->select()
        ->from($tableNameContent->info('name'), 'content_id')
        ->where('name =?', 'main')
        ->where('page_id =?', $header_page_id)
        ->query()
        ->fetchColumn();

if (!empty($main_content_id)) {
    $content_id = $tableNameContent->select()
            ->from($tableNameContent->info('name'), 'content_id')
            ->where('name =?', 'core.html-block')
            ->where('page_id =?', $header_page_id)
            ->where('params like (?)', '%jQuery.noConflict()%')->query()
            ->fetchColumn();

    if (!$content_id) {
        $tableNameContent->insert(array(
            'type' => 'widget',
            'name' => 'core.html-block',
            'page_id' => $header_page_id,
            'parent_content_id' => $main_content_id,
            'order' => 1,
            'params' => '{"title":"","data":"<script type=\"text\/javascript\"> \r\nif(typeof(window.jQuery) !=  \"undefined\") {\r\njQuery.noConflict();\r\n}\r\n<\/script>","nomobile":"0","name":"core.html-block"}'
        ));
    }
}

?>