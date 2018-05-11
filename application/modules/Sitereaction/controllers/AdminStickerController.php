<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminStickerController.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_AdminStickerController extends Core_Controller_Action_Admin {
  public function indexAction() {
    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitereaction_admin_main', array(), 'sitereaction_admin_main_emoticon');
  }

  public function deleteAction() {
    //LAYOUT
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->sticker_id = $sticker_id = $this->_getParam('sticker_id');

    if (!$this->getRequest()->isPost()) {
      return;
    }
    $values = $this->getRequest()->getPost();

    if ($values['confirm'] != $sticker_id) {
      return;
    }

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
    $sticker = Engine_Api::_()->getItem('sitereaction_sticker', $sticker_id);	
    $sticker->delete();
      
      $db->commit();
    } catch (Exception $ex) {
      $db->rollBack();
      throw $ex;
    }
    $this->_forward('success', 'utility', 'core', array(
      'smoothboxClose' => 10,
      'parentRefresh' => 10,
      'messages' => array(Zend_Registry::get('Zend_Translate')->_('Deleted'))
    ));
  }

  public function editAction() {
    //LAYOUT
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->sticker_id = $sticker_id = $this->_getParam('sticker_id');
    // Get form
    $this->view->form = $form = new Sitereaction_Form_Admin_Collection_Sticker_Edit();
    $sticker = Engine_Api::_()->getItem('sitereaction_sticker', $sticker_id);
    if (!$this->getRequest()->isPost()) {
      $form->populate($sticker->toArray());
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    // Process
    $values = $form->getValues();
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $sticker->setFromArray($values);		
      $sticker->save();

      $db->commit();
    } catch (Exception $ex) {
      $db->rollBack();
      throw $ex;
    }
    $this->_forward('success', 'utility', 'core', array(
      'smoothboxClose' => 10,
      'parentRefresh' => 10,
      'messages' => array(Zend_Registry::get('Zend_Translate')->_('Saved Changes!'))
    ));
  }

  //ACTION FOR UPDATE ORDER 
  public function updateOrderAction() {
    //CHECK POST
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      $values = $_POST;
      try {
        foreach ($values['order'] as $key => $value) {		
           $row = Engine_Api::_()->getItem('sitereaction_sticker', (int) $value);		        
           if (!empty($row)) {		
             $row->order = $key + 1;		
             $row->save();		
           }		
         }
        
        $db->commit();
        $this->_redirect('admin/sitereaction/collection/manage/collection_id/' . $values['collection_id']);
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }
  }

}
