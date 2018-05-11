<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: PartialPageCache.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Plugin_PartialPageCache extends Zend_Controller_Plugin_Abstract {

    protected $_defaultOptions = array(
        'cache_with_get_variables' => true,
        'cache_with_post_variables' => false,
        'cache_with_session_variables' => true,
        'cache_with_files_variables' => false,
        'cache_with_cookie_variables' => true,
        'make_id_with_get_variables' => true,
        'make_id_with_post_variables' => false,
        'make_id_with_session_variables' => false,
        'make_id_with_files_variables' => false,
        'make_id_with_cookie_variables' => false,
        'lifetime' => 3600,
    );
    protected $_cacheId = null;
    protected $_cacheData = null;

    public function routeStartup(Zend_Controller_Request_Abstract $request) {
        $this->_cacheId = $this->_makeId();
        $front = Zend_Controller_Front::getInstance();

        $dispatcher = $front->getDispatcher();
        if ($this->_cacheId && $dispatcher instanceof Advancedpagecache_Controller_Dispatcher_Standard) {
            $dispatcher->setCacheId($this->_cacheId)->loadFromCache();
        }
    }

    protected function _canCache() {
        $pluginPartialCache = Zend_Registry::isRegistered('pluginPartialCache') ? Zend_Registry::get('pluginPartialCache') : null;
        $path = $this->getRequest()->getPathInfo();
        $cache_ignore=$this->getRequest()->getQuery();
        if (isset($cache_ignore['igonrePageCache'])||empty($pluginPartialCache) || substr($path, 0, strlen('/admin')) == '/admin' || substr($path, 0, strlen('/logout')) == '/logout' || substr($path, 0, strlen('/utility/task')) == '/utility/task') {
            return false;
        }
        $allowUrls = array();
        $file = APPLICATION_PATH . '/application/settings/advancedpagecache_partial.php';
        if (file_exists($file)) {
            $config = include $file;
            if (empty($config['disable_partial']))
                return false;
            $allowUrls = isset($config['partialUrl']) && is_array($config['partialUrl']) ? $config['partialUrl'] : array();
            $this->_defaultOptions['lifetime'] = $config['partial_lifetime'];
        }
        $isValidPath = false;

        foreach (array_keys($allowUrls) as $allowPath) {
            if (rtrim($path, '/') === rtrim($allowPath, '/')) {
                $isValidPath = true;
                break;
            }
        }
        return $isValidPath;
    }

    /**
     * Make an id depending on REQUEST_URI and superglobal arrays (depending on options)
     *
     * @return mixed|false a cache id (string), false if the cache should have not to be used
     */
    protected function _makeId() {
        $request = $this->getRequest();
        if (APPLICATION_ENV != 'production' || $request->isXmlHttpRequest() || $request->isFlashRequest() || !$this->_canCache()) {
            return false;
        }
        $tmp = $request->getPathInfo();
        foreach (array('Get', 'Post', 'Session', 'Files', 'Cookie') as $arrayName) {
            $tmp2 = $this->_makePartialId($arrayName, $this->_defaultOptions['cache_with_' . strtolower($arrayName) . '_variables'], $this->_defaultOptions['make_id_with_' . strtolower($arrayName) . '_variables']);
            if ($tmp2 === false) {
                return false;
            }
            $tmp = $tmp . $tmp2;
        }

        if (Engine_Api::_()->seaocore()->isSiteMobileModeEnabled()) {
          $tmp .= '_sitemobile_mode';
        }


        $file = APPLICATION_PATH . '/application/settings/advancedpagecache_partial.php';
        $allowUrls = array();
        if (file_exists($file)) {
            $config = include $file;
            $allowUrls = isset($config['partialUrl']) && is_array($config['partialUrl']) ? $config['partialUrl'] : array();
        }

        $translateLocale = Zend_Registry::get('Zend_Translate')->getLocale();
        foreach ($allowUrls as $key => $value) {
            if (substr($request->getPathInfo(), 0, strlen($key)) != $key) {
                continue;
            }
            if ($value == 'member_level') {
                $viewer = Engine_Api::_()->user()->getViewer();
                $viewerId = $viewer && $viewer->getIdentity() ? $viewer->level_id : 0;
                $tmp .= $viewerId;
            } elseif ($value == 'loggedin') {
                $viewer = Engine_Api::_()->user()->getViewer();
                $loggedin = $viewer && $viewer->getIdentity() ? 'auth' : 'non_auth';
                $tmp .= $loggedin;
            }

            $tmp .= $translateLocale;
            $tmp = md5($tmp);
        }
        return $tmp;
    }

    /**
     * Make a partial id depending on options
     *
     * @param  string $arrayName Superglobal array name
     * @param  bool   $bool1     If true, cache is still on even if there are some variables in the superglobal array
     * @param  bool   $bool2     If true, we have to use the content of the superglobal array to make a partial id
     * @return mixed|false Partial id (string) or false if the cache should have not to be used
     */
    protected function _makePartialId($arrayName, $bool1, $bool2) {
        switch ($arrayName) {
            case 'Get':
                $var = array_diff_key($_GET, array('rewrite' => 1));
                break;
            case 'Post':
                $var = $_POST;
                break;
            case 'Session':
                if (isset($_SESSION)) {
                    $var = $_SESSION;
                } else {
                    $var = null;
                }
                break;
            case 'Cookie':
                if (isset($_COOKIE)) {
                    $var = $_COOKIE;
                } else {
                    $var = null;
                }
                break;
            case 'Files':
                $var = $_FILES;
                break;
            default:
                return false;
        }
        if ($bool1) {
            if ($bool2 && count($var) > 0) {
                return serialize($var);
            }
            return '';
        }
        if (count($var) > 0) {
            return false;
        }
        return '';
    }

}
