<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Standard.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_Controller_Dispatcher_Standard extends Zend_Controller_Dispatcher_Standard {

    protected $_cacheResponse;
    protected $_cacheId;

    public function dispatch(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response) {
        if ($this->_cacheResponse) {
            $request->setDispatched(true);
            $this->setResponse($response);
            $this->_setFromCache();
            return;
        }
        parent::dispatch($request, $response);
        if ($request->isDispatched()) {
            $this->saveInCache();
        }
        return;
    }

    public function setCacheId($cacheId) {
        $this->_cacheId = $cacheId;
        return $this;
    }

    public function saveInCache() {
        if (!$this->_cacheId || (Zend_Registry::isRegistered('Internal_Action') && Zend_Registry::get('Internal_Action'))) {
            return;
        }
        $pluginDispatcher = Zend_Registry::isRegistered('pluginDispatcher') ? Zend_Registry::get('pluginDispatcher') : null;
        if (empty($pluginDispatcher))
            return;
        $data = array();
        $data['responseBody'] = $this->getResponse()->getBody();
        $data['headers'] = $this->getResponse()->getBody();
        $layout = Zend_Layout::startMvc();
        $data['layout'] = array(
            'viewBasePath' => $layout->getViewBasePath(),
            'viewSuffix' => $layout->getViewSuffix(),
            'isEnabled' => $layout->isEnabled(),
            'layout' => $layout->getLayout(),
            'siteinfo' => $layout->siteinfo
        );
        $view = Zend_Registry::get('Zend_View');
        $cssStyles = $view->headLink()->getContainer()->getArrayCopy();
        $headLinks = array();
        foreach ($cssStyles as $cssItem) {
            $headLinks[] = get_object_vars($cssItem);
        }
        $data['headLinks'] = $headLinks;

        $headScriptsData = $view->headScript()->getContainer()->getArrayCopy();
        $headScripts = array();
        foreach ($headScriptsData as $scriptsData) {
            $headScripts[] = get_object_vars($scriptsData);
        }
        $data['headScripts'] = $headScripts;
        $cache = Zend_Registry::get('Zend_Cache');
        $cache->save($data, $this->_cacheId, array('partial_page'), $this->_defaultOptions['lifetime']);
    }

    public function loadFromCache() {
        if (!$this->_cacheId) {
            return;
        }
        $cache = Zend_Registry::get('Zend_Cache');
        $this->_cacheResponse = $cache->load($this->_cacheId);
        return;
    }

    protected function _setFromCache() {
        $response = $this->getResponse();
        $data = $this->_cacheResponse;
        $response->appendBody($data['responseBody']);

        $view = Zend_Registry::get('Zend_View');
        foreach ($data['headLinks'] as $headLink) {
            $view->headLink()->appendStylesheet($headLink['href']);
        }
        foreach ($data['headScripts'] as $headScript) {
            $scriptData = $view->headScript()->createData($headScript['type'], $headScript['attributes'], $headScript['source']);
            $view->headScript()->append($scriptData);
        }

        $layout = Zend_Layout::startMvc();
        $layout->setViewBasePath($data['layout']['viewBasePath']);
        if ($data['layout']['isEnabled']) {
            $layout->enableLayout();
        } else {
            $layout->disableLayout();
        }
        $layout->setViewSuffix($data['layout']['viewSuffix']);
        $layout->setLayout($data['layout']['layout']);
        $layoutHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('layout');
        $layoutHelper->postDispatch();
    }

}
