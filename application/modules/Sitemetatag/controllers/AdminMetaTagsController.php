<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitemetatag_AdminMetaTagsController extends Core_Controller_Action_Admin {

    // ACTION TO MANGAGE META TAGS FOR ALL THE PAGES
    public function manageAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitemetatag_admin_main', array(), 'sitemetatag_admin_main_metatags');
        $this->view->subNavigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitemetatag_admin_main_metatags', array(), 'sitemetatag_admin_main_widgetized');
        $this->view->formFilter = $formFilter = new Sitemetatag_Form_Admin_MetaTags_Filter();
        if ($formFilter->isValid($this->_getAllParams())) 
            $values = $formFilter->getValues();
        foreach ($values as $key => $value) {
            if (null === $value || $value === '') {
                unset($values[$key]);
            }
        }
        $values['order'] = isset($values['order']) ? $values['order'] : 'page_id';
        include_once APPLICATION_PATH . '/application/modules/Sitemetatag/controllers/license/license2.php';
    }

    // ACTION TO EDIT META TAGS FOR A PARTICULAR PAGE
    public function editAction() {

        $page_id = $this->_getParam('page_id', 0);
        if(empty($page_id)) 
            return $this->_forward('notfound', 'error', 'core');
        
        $this->_helper->layout->setLayout('admin-simple');
        $this->view->corePage = $corePage = Engine_Api::_()->getItem('core_page', $page_id);
        $this->view->form = $form = new Sitemetatag_Form_Admin_MetaTags_Edit(array('page' => $corePage));
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $postData = $this->getRequest()->getPost();
            $corePage->title = $values['title'];
            $corePage->description = $values['description'];
            $corePage->save();
            $pageinfoTable = Engine_Api::_()->getDbtable('pageinfo','sitemetatag');
            $pageInfo = $pageinfoTable->getPageinfoRow(array('page_id' => $page_id));
            if(empty($pageInfo))
                $pageInfo = $pageinfoTable->createRow();
            $pageInfo->page_id = $page_id;
            $pageInfo->setFromArray($values);
            if (!empty($values['photo']))
                $pageInfo->setPhoto($form->photo);
            else if ($values['remove_photo']) 
                $pageInfo->photo_id = 0;
            $pageInfo->save();

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
        $pageinfoTable = Engine_Api::_()->getDbtable('pageinfo','sitemetatag');
        $pageInfo = $pageinfoTable->getPageinfoRow(array('page_id' => $page_id));
        if(empty($pageInfo))
            $pageInfo = $pageinfoTable->createRow();
        $pageInfo->page_id = $page_id;
        $pageInfo->enable_opengraph = boolval(!$pageInfo->enable_opengraph);
        $pageInfo->save();
        $this->_redirect('admin/sitemetatag/meta-tags/manage');
    }

    //  ACTION TO ENABLE OR DISABLE TWITTER CARDS SETTING FOR A PARTICULAR PAGE
    public function editTwitterCardsAction() {
        $page_id = $this->_getParam('page_id',0);
        if(empty($page_id)) 
            return $this->_forward('notfound', 'error', 'core');
        $pageinfoTable = Engine_Api::_()->getDbtable('pageinfo','sitemetatag');
        $pageInfo = $pageinfoTable->getPageinfoRow(array('page_id' => $page_id));
        if(empty($pageInfo))
            $pageInfo = $pageinfoTable->createRow();
        $pageInfo->page_id = $page_id;
        $pageInfo->enable_twittercards = boolval(!$pageInfo->enable_twittercards);
        $pageInfo->save();
        $this->_redirect('admin/sitemetatag/meta-tags/manage');
    }

    public function nonWidgetizedAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitemetatag_admin_main', array(), 'sitemetatag_admin_main_metatags');
        $this->view->subNavigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitemetatag_admin_main_metatags', array(), 'sitemetatag_admin_main_nonwidgetized');

        $this->view->form = $form = new Sitemetatag_Form_Admin_MetaTags_NonWidgetized();
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $i = 0;
            foreach ($values as $key => $value) {
                if (is_null($value))
                    $value = "";
                Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
            }
            $form->addNotice($this->view->translate('Your changes have been saved successfully.'));
        }
    
    }
}
