<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminPageCachingController.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_AdminPageCachingController extends Core_Controller_Action_Admin {

    public function indexAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('advancedpagecache_admin_main', array(), 'advancedpagecache_admin_main_full_page');

        $setting_file = APPLICATION_PATH . '/application/settings/advancedpagecache.php';

        if (file_exists($setting_file)) {
            $currentCache = include $setting_file;
        } else {
            $currentCache = array(
                'browser_lifetime' => 600,
                'ignoreUrl' => array(),
                'utilization_space' => 100,
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
            );
        }
        $this->view->currentCache = $currentCache;

        $ignoreUrlArray = array_combine($currentCache['ignoreUrl'], $currentCache['ignoreUrl']);

        $this->view->form = $form = new Advancedpagecache_Form_Admin_Showignoreurl();
        $form->setOptionArray($ignoreUrlArray);

        if (!$this->getRequest()->isPost()) {
            return;
        }
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }
        if (is_writable($setting_file) || (is_writable(dirname($setting_file)) && !file_exists($setting_file))) {
            // do nothing
        } else {
            //if( (is_file($setting_file) && !is_writable($setting_file))
            //    || (!is_file($setting_file) && is_dir(dirname($setting_file)) && !is_writable(dirname($setting_file))) ) {
            $phrase = Zend_Registry::get('Zend_Translate')->_('Changes made to this form will not be saved.  Please adjust the permissions (CHMOD) of file %s to 777 and try again.');
            $form->addError(sprintf($phrase, '/application/settings/advancedpagecache.php'));
            return;
        }
        //process $currentCache = ?
        include_once APPLICATION_PATH . '/application/modules/Advancedpagecache/controllers/license/license2.php';

        // Save settings
        if (file_put_contents($setting_file, $code)) {
            $ignoreUrlArray = array_combine($currentCache['ignoreUrl'], $currentCache['ignoreUrl']);
            $form->setOptionArray($ignoreUrlArray);
            $form->addNotice('Your changes have been saved.');
        }
    }

    public function partialPageAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('advancedpagecache_admin_main', array(), 'advancedpagecache_admin_main_partial');

        $setting_file = APPLICATION_PATH . '/application/settings/advancedpagecache_partial.php';

        if (file_exists($setting_file)) {
            $currentCache = include $setting_file;
        } else {
            $currentCache = array(
                'partialUrl' => ''
            );
        }
        $this->view->currentCache = $currentCache;

        $this->view->optionArray = array('member_level' => 'Member level Caching', 'loggedin' => 'Logged In / Non-Logged In User', 'all' => 'Common For All');
    }

    public function addUrlAction() {

        $this->view->form = $form = new Advancedpagecache_Form_Admin_Addignoreurl();

        $setting_file = APPLICATION_PATH . '/application/settings/advancedpagecache.php';

        if (file_exists($setting_file)) {
            $currentCache = include $setting_file;
        } else {
            $currentCache = array(
                'ignoreUrl' => ''
            );
        }

        if (!$this->getRequest()->isPost()) {
            return;
        }
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }
        if (is_writable($setting_file) || (is_writable(dirname($setting_file)) && !file_exists($setting_file))) {
            // do nothing
        } else {
            //if( (is_file($setting_file) && !is_writable($setting_file))
            //    || (!is_file($setting_file) && is_dir(dirname($setting_file)) && !is_writable(dirname($setting_file))) ) {
            $phrase = Zend_Registry::get('Zend_Translate')->_('Changes made to this form will not be saved.  Please adjust the permissions (CHMOD) of file %s to 777 and try again.');
            $form->addError(sprintf($phrase, '/application/settings/advancedpagecache.php'));
            return;
        }
        // Process
        // $currentCache =
        include_once APPLICATION_PATH . '/application/modules/Advancedpagecache/controllers/license/license2.php';

        // Save settings

        if (file_put_contents($setting_file, $code)) {
            $form->addNotice('Your changes have been saved.');
        }

        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('')
        ));
    }

    public function addPartialUrlAction() {

        $this->view->form = $form = new Advancedpagecache_Form_Admin_Addpartialurl();

        $setting_file = APPLICATION_PATH . '/application/settings/advancedpagecache_partial.php';

        if (file_exists($setting_file)) {
            $currentCache = include $setting_file;
        } else {
            $currentCache = array(
                'partialUrl' => ''
            );
        }

        if (!$this->getRequest()->isPost()) {
            return;
        }
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }
        if (is_writable($setting_file) || (is_writable(dirname($setting_file)) && !file_exists($setting_file))) {
            // do nothing
        } else {
            //if( (is_file($setting_file) && !is_writable($setting_file))
            //    || (!is_file($setting_file) && is_dir(dirname($setting_file)) && !is_writable(dirname($setting_file))) ) {
            $phrase = Zend_Registry::get('Zend_Translate')->_('Changes made to this form will not be saved.  Please adjust the permissions (CHMOD) of file %s to 777 and try again.');
            $form->addError(sprintf($phrase, '/application/settings/advancedpagecache_partial.php'));
            return;
        }
        // Process
        // $currentCache =
        $values = $form->getValues();
        $addUrl = array();
        foreach (explode(',', $values['addUrl']) as $addurl) {
            $addUrl[$addurl] = $values['cache_basedon'];
        }
        include_once APPLICATION_PATH . '/application/modules/Advancedpagecache/controllers/license/license2.php';

        // Save settings
        if (file_put_contents($setting_file, $code)) {
            $form->addNotice('Your changes have been saved.');
        }
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('')
        ));
    }

    public function removeUrlAction() {

        $key = urldecode($this->_getParam('key'));

        $setting_file = APPLICATION_PATH . '/application/settings/advancedpagecache_partial.php';

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (is_writable($setting_file) || (is_writable(dirname($setting_file)) && !file_exists($setting_file))) {
            // do nothing
        } else {
            //if( (is_file($setting_file) && !is_writable($setting_file))
            //    || (!is_file($setting_file) && is_dir(dirname($setting_file)) && !is_writable(dirname($setting_file))) ) {
            $phrase = Zend_Registry::get('Zend_Translate')->_('Changes made to this form will not be saved.  Please adjust the permissions (CHMOD) of file %s to 777 and try again.');
            $form->addError(sprintf($phrase, '/application/settings/advancedpagecache_partial.php'));
            return;
        }

        $removeUrl[$key] = '';
        if (file_exists($setting_file)) {
            $currentCache = include $setting_file;
        } else {
            return;
        }

        $currentCache['partialUrl'] = array_diff_key(!empty($currentCache['partialUrl']) ? $currentCache['partialUrl'] : array(), !empty($removeUrl) ? $removeUrl : array());
        $code = "<?php\n\nreturn ";
        $code .= var_export($currentCache, true);
        $code .= '; ?>';

        if (file_put_contents($setting_file, $code)) {
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('')
            ));
        }
    }

    public function editAction() {

        $key = urldecode($this->_getParam('key'));
        $basedon = $this->_getParam('basedon');

        $setting_file = APPLICATION_PATH . '/application/settings/advancedpagecache_partial.php';

        $this->view->form = $form = new Advancedpagecache_Form_Admin_Addpartialurl();
        $form->setTitle('Configure Cache Setting');
        $form->removeElement('addUrl');
        $form->removeElement('ad_header2');
        $form->submit->setLabel('Save Changes');
        $form->populate(array('cache_basedon' => $basedon));

        if (!$this->getRequest()->isPost()) {
            return;
        }
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        if (is_writable($setting_file) || (is_writable(dirname($setting_file)) && !file_exists($setting_file))) {
            // do nothing
        } else {
            //if( (is_file($setting_file) && !is_writable($setting_file))
            //    || (!is_file($setting_file) && is_dir(dirname($setting_file)) && !is_writable(dirname($setting_file))) ) {
            $phrase = Zend_Registry::get('Zend_Translate')->_('Changes made to this form will not be saved.  Please adjust the permissions (CHMOD) of file %s to 777 and try again.');
            $form->addError(sprintf($phrase, '/application/settings/advancedpagecache_partial.php'));
            return;
        }

        $values = $form->getValues();
        $editUrl[$key] = $values['cache_basedon'];


        if (file_exists($setting_file)) {
            $currentCache = include $setting_file;
        } else {
            return;
        }
        include_once APPLICATION_PATH . '/application/modules/Advancedpagecache/controllers/license/license2.php';


        if (file_put_contents($setting_file, $code)) {
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('')
            ));
        }
    }

    public function testerAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('advancedpagecache_admin_main', array(), 'advancedpagecache_admin_main_tester');
    }

    public function calculateAction() {
        $code = $this->_getParam('url');
        $query = parse_url($code, PHP_URL_QUERY);
        if ($query) {
            $url = $code . '&igonrePageCache=true';
        } else {
            $url = $code . '?igonrePageCache=true';
        }
        // to get time without caching

        $start = microtime(true);
        file_get_contents($url);
        $this->view->withoutapc_load_time = round((microtime(true) - $start), 4);

        // for caching page
        file_get_contents($code);
        //calculate load time with caching
        if ($query) {
            $cacheurl = $code . '&PageCache=' . time();
        } else {
            $cacheurl = $code . '?PageCache=' . time();
        }
        $start = microtime(true);
        file_get_contents($cacheurl);
        $this->view->apc_load_time = round((microtime(true) - $start), 4);
        $this->_helper->layout->setLayout('default-simple');
    }

}
