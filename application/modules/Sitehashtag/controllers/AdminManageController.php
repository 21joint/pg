<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    AdminManageController.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_AdminManageController extends Core_Controller_Action_Admin {

    public function indexAction() {

        //GET NAVIGATION
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitehashtag_admin_main', array(), 'sitehashtag_admin_main_manage');
        $globalSetting = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.modules', 2);
        if($globalSetting == 2){
            $this->view->showAll = 1;
        }
        else if($globalSetting == 1){
            $contentTable = Engine_Api::_()->getDbtable('contents', 'sitehashtag');

            $selectModules = $contentTable->select();
            $this->view->modules = $contentTable->fetchAll($selectModules);
        }
        else{
            $this->view->showNone = 1;
        }
        
    }

    public function enabledAction() {

        $contentId = $this->_getParam('content_id');
        if (!$contentId) {
            return;
        }
        $content = Engine_Api::_()->getItem('sitehashtag_content', $contentId);

        if (!($content instanceof Core_Model_Item_Abstract)) {
            return;
        }
        try {

            $content->enabled = !$content->enabled;
            $content->save();
            return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function addContentAction() {
      
      $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitehashtag_admin_main', array(), 'sitehashtag_admin_main_manage');

    $this->view->form = $form = new Sitehashtag_Form_Admin_Manage_Content();
    
    if (!$this->getRequest()->isPost())
      return;
    
    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    // Process
    $values = $form->getValues();
    $contentTable = Engine_Api::_()->getItemTable('sitehashtag_content');
    $contentCheck = $contentTable->fetchRow(array('module_name = ?' => $values['module_name']));
    if (!empty($contentCheck)) {
      $itemError = "Content Type already exists.";
      $form->getDecorator('errors')->setOption('escape', false);
      $form->addError($itemError);
      return;
    }
    $content = $contentTable->createRow();
    $content->setFromArray($values);
    $content->save();
    
    return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
  }


}
