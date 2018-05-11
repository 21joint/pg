<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSitemapController.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_AdminSitemapController extends Core_Controller_Action_Admin {

    //SITEMAP INDEX ACTION TO LIST INDEX SITEMAP AND CONTENT WISE SITEMAP FILES
    public function indexAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_sitemap');
        $contentTypeTable = Engine_Api::_()->getDbtable('contenttypes','siteseo');
        $this->view->contentTypes = $contentTypeTable->getSearchItemTypes();
        $sitemapApi = Engine_Api::_()->getApi('sitemap','siteseo');
        $this->view->hasGlobalSitemap = $sitemapApi->hasGlobalSitemap();
        if($this->view->hasGlobalSitemap) {
            $setting = Engine_Api::_()->getApi('settings', 'core');
            $this->view->globalSitemapPath = $sitemapApi->getPublicSitemapPath();
            $this->view->lastModifiedDate = $setting->getSetting("siteseo.sitemap.modified.date", false);
            $this->view->hasCompressedSitemap = $sitemapApi->hasGlobalCompressedSitemap();
            $this->view->lastSubmitDate = $setting->getSetting("siteseo.sitemap.submit.date", false);
        }
        //CHANGE ORDER OF SITEMAP CONTENT 
        $getData = $this->_getAllParams();
        include_once APPLICATION_PATH . '/application/modules/Siteseo/controllers/license/license2.php';
    }

    //ACTION TO EDIT FIELDS OF CONTENT TYPES
    public function editAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_sitemap');
        $contentTypeId = $this->_getParam('contenttype_id', 0);
        if(empty($contentTypeId)) 
            return $this->_forward('notfound', 'error', 'core');
        $this->_helper->layout->setLayout('admin-simple');
        $contentType = Engine_Api::_()->getItem('siteseo_contenttype', $contentTypeId);
        $this->view->form = $form = new Siteseo_Form_Admin_Sitemap_Edit(array('contentType' => $contentType));
        $this->view->type = $contentType->type;
        $this->view->schema = $contentType->schema;

        // GET SPECIFIC SCHEMA TYPES
        $this->view->specificSchemaArray = $form->getSpecificSchemaArray();
        $this->view->selectedSpecificSchema = $contentType->specific_schematype;

        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $postData = $this->getRequest()->getPost();
            unset($postData['title']);
            unset($postData['type']);
            $contentType->setFromArray($postData);
            $contentType->save();

            //PARENT REDIRECT AFTER SAVING VALUES
            $this->_forward('success', 'utility', 'core', array(
              'smoothboxClose' => true,
              'parentRedirect' => $this->_helper->url->url(array('action' => 'index')),
              'parentRedirectTime' => '15',
              'format' => 'smoothbox',
              'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your changes have been saved succesfully !'))
              ));
        }
    }

    //  ACTION TO ENABLE OR DISABLE OPEN GRAPH SETTING FOR A PARTICULAR PAGE
    public function enableDisableAction() {
        $contentTypeId = $this->_getParam('contenttype_id', 0);
        if(empty($contentTypeId)) 
            $this->_redirect('admin/siteseo/meta-tags/manage');
        $contentType = Engine_Api::_()->getItem('siteseo_contenttype', $contentTypeId);
        $contentType->enabled = boolval(!$contentType->enabled);
        $contentType->save();
        $this->_redirect('admin/siteseo/sitemap');
    }

    //ACTION TO BUILD SITEMAP FOR BOTH CONTENT TYPE AS WELL AS INDEX SITEMAP FILE
    public function buildAction() {
        $type = $this->_getParam('type', null);
        $sitemapApi = Engine_Api::_()->getApi('sitemap','siteseo');
        if(empty($type)) {
            $params = array('contentSitemaps' => true);
            $status = $sitemapApi->buildIndexSitemap($params);
        } else {
            $contentTypeTable = Engine_Api::_()->getDbtable('contenttypes', 'siteseo');
            $contentType = $contentTypeTable->getContentType(array('type' => $type));
            $status  = $sitemapApi->buildContentSitemap($contentType);
        }
        $message = $status ? 'Sitemap has been Created successfully !' : 'Sitemap could not be created.';
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => true,
            'parentRedirect' => 'admin/siteseo/sitemap',
            'parentRedirectTime' => '15',
            'format' => 'smoothbox',
            'messages' => array(Zend_Registry::get('Zend_Translate')->_($message))
            ));

    }

    //ACTION TO SUBMIT SITEMAP OF BOTH CONTENT TYPE AS WELL AS INDEX SITEMAP FILE
    public function submitAction() {
        $this->view->form = $form = new Siteseo_Form_Admin_Sitemap_Submit();        
        if(!$this->getRequest()->isPost()) 
            return;
        $this->view->searchEngines = $searchEngines = $this->_getParam('search_engines',false);
        $regenerate = $this->_getParam('regenerate', false);
        if(!empty($searchEngines))
            $returnCodes = Engine_Api::_()->getApi('sitemap','siteseo')->submitSitemap($searchEngines, $regenerate);
        //PARENT REDIRECT AFTER SAVING VALUES
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => true,
            'parentRedirect' => $this->_helper->url->url(array('action' => 'index')),
            'parentRedirectTime' => '15',
            'format' => 'smoothbox',
            'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your sitemap has been submitted successfully !'))
            ));
    }

    //ACTION TO DOWNLOAD SIETMAP INDEX FILE
    public function downloadAction() {
        $params = array('useXslStylesheet' => false, 'download' => true);
        $sitemapIndex = Engine_Api::_()->getApi('sitemap','siteseo')->buildIndexSitemap($params);
        $filename = Engine_Api::_()->getApi('sitemap','siteseo')->getSitemapFileName();
        $this->_helper->layout->disableLayout();
        header("Content-Type: application/xml;");
        header("Content-Disposition: attachment; filename=$filename;");
        echo $sitemapIndex;
    }

    //ACTION TO DOWNLOAD SIETMAP INDEX FILE
    public function autoSubmitSettingsAction() {
        $this->view->form = $form = new Siteseo_Form_Admin_Sitemap_AutoSubmit();
        if (!$this->getRequest()->isPost() || !$form->isValid($this->getRequest()->getPost()))
            return;

        $values = $form->getValues();
        $setting = Engine_Api::_()->getApi('settings', 'core');
        $interval = substr($values['submit_interval'], 3);
        $timeout = 3600 * 24 * intval($interval);

        //SAVE VALUES FOR AUTO SUBMIT OF SITEMAP
        $setting->setSetting("siteseo.sitemap.submit.searchengines", $values['search_engines']);
        $coreTaskTable = Engine_Api::_()->getDbtable('tasks', 'core');
        $select = $coreTaskTable->select()->where('plugin = ? ', 'Siteseo_Plugin_Task_AutoSubmitSitemap');
        $row = $coreTaskTable->fetchRow($select);

        $row->timeout = $timeout;
        $row->save();
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => true,
            'parentRedirect' => $this->_helper->url->url(array('action' => 'index')),
            'parentRedirectTime' => '15',
            'format' => 'smoothbox',
            'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your changes have been saved succesfully !'))
            ));        
    }

    //ACTION TO SELECT MENUS WHOSE URLS WILL BE SHOWN IN THE SITEMAP FILES
    public function selectMenuAction() {
        $this->view->form = $form = new Siteseo_Form_Admin_Sitemap_SelectMenu();        
        if (!$this->getRequest()->isPost())
            return;
        if(!$form->isValid($this->getRequest()->getPost()))
            return;

        $values = $form->getValues();
        $selectedmenu = json_encode($values['selectedmenu']);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('siteseo.sitemap.selectedmenu', $selectedmenu);
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => true,
            'parentRedirect' => $this->_helper->url->url(array('action' => 'index')),
            'parentRedirectTime' => '15',
            'format' => 'smoothbox',
            'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your changes have been saved succesfully !'))
            ));        
    }

}
