<?php $db = Engine_Db_Table::getDefaultAdapter(); 
$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("advancedpagecache_admin_main_full_page", "advancedpagecache", "Manage Single User Caching","",\'{"route":"admin_default","module":"advancedpagecache","controller":"page-caching"}\',"advancedpagecache_admin_main","", 3),
("advancedpagecache_admin_main_partial","advancedpagecache","Manage Multiple Users Caching","",\'{"route":"admin_default","module":"advancedpagecache","controller":"page-caching","action":"partial-page"}\',"advancedpagecache_admin_main","", 2);
');
$db->query('INSERT IGNORE INTO `engine4_core_tasks` (`task_id`, `title`, `module`, `plugin`, `timeout`, `processes`, `semaphore`, `started_last`, `started_count`, `completed_last`, `completed_count`, `failure_last`, `failure_count`, `success_last`, `success_count`) VALUES (NULL, "Cache Clear", "advancedpagecache", "Advancedpagecache_Plugin_Task_Clear", "900","","","","","","","","","","");');
if(!empty($default)) {
$browser_setting_file = APPLICATION_PATH . '/application/settings/advancedpagecache.php';
        $defaultFilePath = APPLICATION_PATH . '/temporary/sitecache';
        if (!is_dir($defaultFilePath)) {
            mkdir($defaultFilePath);
            chmod($defaultFilePath, 0777);
        }
        $browser_currentCache = array(
            'browser_lifetime' => 600,
            'ignoreUrl' => array(
                0 => '/activity/notifications',
                1 => '/members/home',
            ),
            'utilization_space' => 1,
            'default_backend' => 'File',
            'frontend' => array(
                'lifetime' => 1200,
                'cache_id_prefix' => 'advancedpagecache_page_',
                'default_options' => array(
                    'cache_with_get_variables' => true,
                    'cache_with_cookie_variables' => true,
                    'cache_with_session_variables' => true,
                    'tags' => array('browse_cache'),
                    'cache' => true
                ),
                'memorize_headers' => array('location'),
            ),
            'backend' => array(
                'File' => array(
                    'cache_dir' => APPLICATION_PATH . '/temporary/sitecache',
                ),
            ),
            'disable_browse' => '1',
        );
        $browser_currentCache['default_file_path'] = $defaultFilePath;

        $browser_code = "<?php\ndefined('_ENGINE') or die('Access Denied');\nreturn ";
        $browser_code .= var_export($browser_currentCache, true);
        $browser_code .= '; ?>';
        if (is_writable($browser_setting_file) || (is_writable(dirname($browser_setting_file)) && !file_exists($browser_setting_file))) {
            // do nothing
        } else {
            //if files are not writable
            $phrase = Zend_Registry::get('Zend_Translate')->_('Changes made to this form will not be saved.  Please adjust the permissions (CHMOD) of file %s to 777 and try again.');
            echo sprintf($phrase, '/application/settings/advancedpagecache_partial.php');
            return;
        }

        file_put_contents($browser_setting_file, $browser_code);

        $partial_setting_file = APPLICATION_PATH . '/application/settings/advancedpagecache_partial.php';
        $partial_code = "<?php\ndefined('_ENGINE') or die('Access Denied');\n return array (
    'partial_lifetime' => '60',  'partialUrl' => 
    array (
    '/help/contact' => 'all',
    '/help/privacy' => 'all',
    '/help/terms' => 'all',
    '/signup' => 'all',
    '/login' => 'all',
    '/events' => 'member_level',
    '/albums' => 'member_level',
    '/albums/browse' => 'member_level',
    '/albums/photo/browse' => 'member_level',
    '/groups' => 'member_level',
    ),
    'disable_partial' => '1',
    ); ?>";
        if (is_writable($partial_setting_file) || (is_writable(dirname($partial_setting_file)) && !file_exists($partial_setting_file))) {
            // do nothing
        } else {
            //if files are not writable
            $phrase = Zend_Registry::get('Zend_Translate')->_('Changes made to this form will not be saved.  Please adjust the permissions (CHMOD) of file %s to 777 and try again.');
            echo sprintf($phrase, '/application/settings/advancedpagecache_partial.php');
            return;
        }
        file_put_contents($partial_setting_file, $partial_code);
}


?>