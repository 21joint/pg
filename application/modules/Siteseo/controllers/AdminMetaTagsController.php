<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_AdminMetaTagsController extends Core_Controller_Action_Admin {

    // ACTION TO MANGAGE META TAGS FOR ALL THE PAGES
    public function manageAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_managemetatags');
        $this->view->subNavigation = $subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main_metatags', array(), 'siteseo_admin_main_pagesmetatags');

        $this->view->formFilter = $formFilter = new Siteseo_Form_Admin_MetaTags_Filter();
        include_once APPLICATION_PATH . '/application/modules/Siteseo/controllers/license/license2.php';
        $values['order'] = isset($values['order']) ? $values['order'] : 'page_id';
        $this->view->formValues = $values;
        $this->view->assign($values);
        $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('pageinfo','siteseo')->getCorePages($values);
    }

    // ACTION TO EDIT META TAGS FOR A PARTICULAR PAGE
    public function editAction() {

        $page_id = $this->_getParam('page_id', 0);
        if(empty($page_id)) 
            return $this->_forward('notfound', 'error', 'core');
        
        $this->_helper->layout->setLayout('admin-simple');
        $this->view->corePage = $corePage = Engine_Api::_()->getItem('core_page',$page_id);
        $this->view->form = $form = new Siteseo_Form_Admin_MetaTags_Edit(array('page' => $corePage));
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $postData = $this->getRequest()->getPost();
            $corePage->title = $values['title'];
            $corePage->description = $values['description'];
            $corePage->keywords = $values['keywords'];
            $corePage->save();
            $pageinfoTable = Engine_Api::_()->getDbtable('pageinfo','siteseo');
            $pageInfo = $pageinfoTable->getPageinfoRow(array('page_id' => $page_id));
            if(empty($pageInfo))
                $pageInfo = $pageinfoTable->createRow();
            $pageInfo->page_id = $page_id;
            include_once APPLICATION_PATH . '/application/modules/Siteseo/controllers/license/license2.php';
            
            //PARENT REDIRECT AFTER SAVING VALUES
            $this->_forward('success', 'utility', 'core', array(
              'smoothboxClose' => true,
              'parentRedirect' => $this->_helper->url->url(array('action' => 'manage')),
              'parentRedirectTime' => '15',
              'format' => 'smoothbox',
              'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your changes have been saved succesfully !'))
              ));
        }
    }

    public function editInlineAction(){
        $primaryKeys = $this->_getParam('key', '{}');
        $primaryKeysArray = json_decode($primaryKeys, true);
        $itemId = $primaryKeysArray['data-id'];
        $column = $this->_getParam('column', 0);
        $value = $this->_getParam('value', 0);
        $corePage = Engine_Api::_()->getItem('core_page', $itemId);
        if(!$corePage || !isset($corePage->$column)) 
            return $this->_helper->json(0);
        $corePage->$column = $value;
        $corePage->save();
        return $this->_helper->json($corePage->$column);
    }

    //  ACTION TO ENABLE OR DISABLE OPEN GRAPH SETTING FOR A PARTICULAR PAGE
    public function editOpenGraphAction() {
        $page_id = $this->_getParam('page_id',0);
        if(empty($page_id)) 
            return $this->_forward('notfound', 'error', 'core');
        $pageinfoTable = Engine_Api::_()->getDbtable('pageinfo','siteseo');
        $pageInfo = $pageinfoTable->getPageinfoRow(array('page_id' => $page_id));
        if(empty($pageInfo))
            $pageInfo = $pageinfoTable->createRow();
        $pageInfo->page_id = $page_id;
        $pageInfo->enable_opengraph = boolval(!$pageInfo->enable_opengraph);
        $pageInfo->save();
        $this->_redirect('admin/siteseo/meta-tags/manage');
    }

    //  ACTION TO ENABLE OR DISABLE TWITTER CARDS SETTING FOR A PARTICULAR PAGE
    public function editTwitterCardsAction() {
        $page_id = $this->_getParam('page_id',0);
        if(empty($page_id)) 
            return $this->_forward('notfound', 'error', 'core');
        $pageinfoTable = Engine_Api::_()->getDbtable('pageinfo','siteseo');
        $pageInfo = $pageinfoTable->getPageinfoRow(array('page_id' => $page_id));
        include_once APPLICATION_PATH . '/application/modules/Siteseo/controllers/license/license2.php';
        
        $this->_redirect('admin/siteseo/meta-tags/manage');
    }

    //  ACTION TO MANAGE META TAGS OF CONTENT ITEMS
    public function manageContentAction(){

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_managemetatags');
        $this->view->subNavigation = $subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main_metatags', array(), 'siteseo_admin_main_contentmetatags');
        $this->view->formFilter = $formFilter = new Siteseo_Form_Admin_MetaTags_Contentfilter();
        if ($formFilter->isValid($this->_getAllParams())) 
            $values = $formFilter->getValues();
        foreach ($values as $key => $value) {
            if (null === $value || $value === '') {
                unset($values[$key]);
            }
        }
        $values['order'] = isset($values['order']) ? $values['order'] : 'id';
        $values['order_direction'] = isset($values['order_direction']) ? $values['order_direction'] : 'DESC';
        $this->view->contentType = isset($values['type']) ? $values['type'] : false;
        $this->view->formValues = $values;
        $this->view->assign($values);
        include_once APPLICATION_PATH . '/application/modules/Siteseo/controllers/license/license2.php';
        
    }

    // ACTION TO EDIT META TAGS FOR A PARTICULAR PAGE
    public function editContentAction() {

        $id = $this->_getParam('id', 0);
        $type = $this->_getParam('type', 0);
        if(empty($id) || empty($type)) 
            return $this->_forward('notfound', 'error', 'core');

        $this->_helper->layout->setLayout('admin-simple');
        $coreSearchTable = Engine_Api::_()->getDbtable('search','core');
        $select = $coreSearchTable->select()->where('id = ? ', $id)->where('type = ? ', $type)->limit(1);
        $searchObject = $coreSearchTable->fetchRow($select);

        $this->view->form = $form = new Siteseo_Form_Admin_MetaTags_Contentedit(array('item' => $searchObject));

        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $postData = $this->getRequest()->getPost();
            include_once APPLICATION_PATH . '/application/modules/Siteseo/controllers/license/license2.php';
            $message = 'Your changes have been saved succesfully !';
            //PARENT REDIRECT AFTER SAVING VALUES
            $this->_forward('success', 'utility', 'core', array(
              'smoothboxClose' => true,
              'parentRedirect' => 'admin/siteseo/meta-tags/manage-content',
              'parentRedirectTime' => '15',
              'format' => 'smoothbox',
              'messages' => array($message)
              ));
        }
    }

    public function editInlineContentAction(){
        $primaryKeys = $this->_getParam('key', '{}');
        $primaryKeysArray = json_decode($primaryKeys, true);
        $itemId = $primaryKeysArray['data-id'];
        $itemType = $primaryKeysArray['data-type'];
        if(empty($itemId) || empty($itemType))
            return $this->_helper->json(0);
        $column = $this->_getParam('column', 0);
        $value = $this->_getParam('value', 0);
        $coreSearchTable = Engine_Api::_()->getDbtable('search','core');
        $coreSearchTable->update(array($column => $value), 
            array("id =?" => $itemId, "type =?" => $itemType));
        return $this->_helper->json($value);
    }

    public function keywordsRankingAction()
    {
      $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_keywordmonitor');
      $keywords = $this->_getParam('keywords', '');
      $keywords = $keywords ? : Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.keywords', null);
      $domain = (new Zend_View_Helper_ServerUrl())->getHost();
      $this->view->domain = $domain = $domain . $this->view->baseUrl();
      if( !$keywords ) {
        return;
      }
      $keywords = explode(',', $keywords);
      $this->view->keywordsRanking = Engine_Api::_()->getApi('google', 'siteseo')->findKeywordRanking($keywords, $domain);
    }

}
