<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminReactionController.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_AdminReactionController extends Core_Controller_Action_Admin {
  public function init() {
    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitereaction_admin_main', array(), 'sitereaction_admin_main_reaction');
  }

  public function indexAction() {
     $table = Engine_Api::_()->getDbtable('reactionicons', 'sitereaction');		
   // GET PAGE LIST.		
     $select = $table->select()		
       ->order('order ASC');		
     $this->view->reactionIcons = $table->fetchAll($select);		

  }

  public function addAction() {
    $this->view->form = $form = new Sitereaction_Form_Admin_Reaction_Create();
    $form->photo->setAttrib('required', true);
    if (!$this->getRequest()->isPost()) {
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    // Process
    $values = $form->getValues();
    $table = Engine_Api::_()->getItemTable('sitereaction_reactionicon');
    $hasAlready = $table->fetchRow(array('type = ?' => $values['type']));
    if (!empty($hasAlready)) {
      $itemError = Zend_Registry::get('Zend_Translate')->_("Type already exists.");
      $form->getDecorator('errors')->setOption('escape', false);
      $form->addError($itemError);
      return;
    }

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
      $row = $table->createRow();		 
      $row->setFromArray($values);		       
      $row->save();
      // Set photo
      if (!empty($values['photo'])) {
        $row->setPhoto($form->photo);
      }
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->_redirect('admin/sitereaction/reaction');
  }

  public function editAction() {
    $this->view->form = $form = new Sitereaction_Form_Admin_Reaction_Create();
    $reaction_id = $this->_getParam('reaction_id');
    $row = Engine_Api::_()->getItem('sitereaction_reactionicon', $reaction_id);
    $form->populate($row->toArray());
    if ($row->type === 'like') {
      $form->removeElement('type');
    }
    if (!$this->getRequest()->isPost()) {
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    // Process
    $values = $form->getValues();
    $table = Engine_Api::_()->getItemTable('sitereaction_reactionicon');
    if (!empty($values['type']) && $values['type'] != $row->type) {
      $hasAlready = $table->fetchRow(array('type = ?' => $values['type']));
      if (!empty($hasAlready)) {
        $itemError = Zend_Registry::get('Zend_Translate')->_("Type already exists.");
        $form->getDecorator('errors')->setOption('escape', false);
        $form->addError($itemError);
        return;
      }
    }

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
    $row->setFromArray($values);	
    $row->save();
      
      // Set photo
      if (!empty($values['photo'])) {
        $row->setPhoto($form->photo);
      }
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->_redirect('admin/sitereaction/reaction');
  }

  public function deleteAction() {
    //LAYOUT
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->reaction_id = $reaction_id = $this->_getParam('reaction_id');

    if (!$this->getRequest()->isPost()) {
      return;
    }
    $values = $this->getRequest()->getPost();

    if ($values['confirm'] != $reaction_id) {
      return;
    }
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
      $row = Engine_Api::_()->getItem('sitereaction_reactionicon', $reaction_id);	
      $row->delete();
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

//ACTION FOR UPDATE ORDER 
  public function updateOrderAction() {
    //CHECK POST
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      $values = $_POST;
      try {
        foreach ($values['order'] as $key => $value) {		 
           $row = Engine_Api::_()->getItem('sitereaction_reactionicon', (int) $value);		
           if (!empty($row)) {		
             $row->order = $key + 1;		
             $row->save();		
           }		
         }
        $db->commit();
        $this->_redirect('admin/sitereaction/reaction');
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }
  }

}
