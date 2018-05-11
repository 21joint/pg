<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_AdminSettingsController extends Core_Controller_Action_Admin {

    public function indexAction() {
        $default=$this->_getParam('enable_default');
        include_once APPLICATION_PATH . '/application/modules/Advancedpagecache/controllers/license/license1.php';
    }

    public function faqAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('advancedpagecache_admin_main', array(), 'advancedpagecache_admin_main_faq');
    }

    public function readmeAction() {
        
    }

    public function deleteBrowserAction() {

        if (!$this->getRequest()->isPost()) {
            return;
        }

        $cacheOptions = array(
            'cache_with_get_variables' => true,
            'cache_with_cookie_variables' => true,
            'cache_with_session_variables' => true,
            'tags' => array('browse_cache'),
            'cache' => true
        );
        $frontendOptions = array(
            'lifetime' => 1200,
            'cache_id_prefix' => 'advancedpagecache_page_',
            'default_options' => $cacheOptions,
            'memorize_headers' => array('location'),
        );

        // Get configurations
        $cacheSettingFile = APPLICATION_PATH . '/application/settings/advancedpagecache.php';
        if (file_exists($cacheSettingFile)) {
            $cacheConfig = include $cacheSettingFile;
            $backend = key($cacheConfig['backend']);
            $backendOptions = $cacheConfig['backend'][$backend];
            $frontendOptions = $cacheConfig['frontend'];
        } else {
            $path = APPLICATION_PATH . '/temporary/sitecache';
            !@is_dir($path) && @mkdir($path, 0777, true);
            $backend = 'File';
            $backendOptions = array(
                'cache_dir' => $path
            );
        }
        $customBackendNaming = false;
        if ($backend == 'Engine_Cache_Backend_Redis')
            $customBackendNaming = true;
        if ($backend == 'Engine_Cache_Backend_Apc')
            $customBackendNaming = true;
        // getting a Zend_Cache_Frontend_Page object
        include_once APPLICATION_PATH . '/application/modules/Advancedpagecache/controllers/license/license2.php';

        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('Single User Cache has been cleared.')
        ));
    }

    public function deletePartialAction() {
        if (!$this->getRequest()->isPost()) {
            return;
        }
        include_once APPLICATION_PATH . '/application/modules/Advancedpagecache/controllers/license/license2.php';

        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('Multiple Users Cache has been cleared.')
        ));
    }

    public function editRootFileAction() {
        
    }

}
