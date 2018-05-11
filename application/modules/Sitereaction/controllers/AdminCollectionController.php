<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminCollectionController.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_AdminCollectionController extends Core_Controller_Action_Admin {
  public function init() {
    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitereaction_admin_main', array(), 'sitereaction_admin_main_collection');
  }

  public function indexAction() {
    $table = Engine_Api::_()->getDbtable('collections', 'sitereaction');	
     // GET PAGE LIST.		
   $select = $table->select()		
      ->order('order ASC');		
     $this->view->collections = $table->fetchAll($select);
  }

  public function createAction() {

    if (isset($_GET['ul']))
      return $this->_forward('upload-sticker', null, null, array('format' => 'json'));
    // Get form
    $this->view->form = $form = new Sitereaction_Form_Admin_Collection_Create();

    if (!$this->getRequest()->isPost()) {
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    $db = Engine_Api::_()->getItemTable('sitereaction_collection')->getAdapter();
    $db->beginTransaction();

    try {
    $form->saveValues();	
    $db->commit();
      
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    $this->_helper->redirector->gotoRoute(array('action' => 'index'));
  }

  public function addMoreAction() {

    if (isset($_GET['ul']))
      return $this->_forward('upload-sticker', null, null, array('format' => 'json'));
    $collection_id = $this->_getParam('collection_id');
    $this->view->collection = $collection = Engine_Api::_()->getItem('sitereaction_collection', $collection_id);
    // Get form
    $this->view->form = $form = new Sitereaction_Form_Admin_Collection_Sticker_Add();
    $form->setTitle("Add more stickers for: " . $collection->getTitle());

    if (!$this->getRequest()->isPost()) {
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    $db = Engine_Api::_()->getItemTable('sitereaction_collection')->getAdapter();
    $db->beginTransaction();

    try {

      $form->saveValues($collection);
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    $this->_helper->redirector->gotoRoute(array('action' => 'index'));
  }

  public function manageAction() {
    $collection_id = $this->_getParam('collection_id');
    $this->view->collection = $collection = Engine_Api::_()->getItem('sitereaction_collection', $collection_id);
  }

  public function editAction() {
    $collection_id = $this->_getParam('collection_id');
    $this->view->collection = $collection = Engine_Api::_()->getItem('sitereaction_collection', $collection_id);
    $this->view->form = $form = new Sitereaction_Form_Admin_Collection_Edit();
  try {
    if (!$this->getRequest()->isPost()) {
      $colleactionArray = $collection->toArray();
      if (date('Y-m-d H:i:s', strtotime($colleactionArray['end_time'])) === '2050-12-31 23:59:59') {
        unset($colleactionArray['end_time']);
      }
      $form->populate($colleactionArray);
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }
   // Process
    $values = $form->getValues();
    if ($values['end_time'] === '0000-00-00') {
      unset($values['end_time']);
    }
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    $collection->setFromArray($values);	
    $collection->save();
     
      
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
     die("Exception ".$e);
    }
    $this->_redirect('admin/sitereaction/collection');
  }

  public function deleteAction() {
    //LAYOUT
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->collection_id = $collection_id = $this->_getParam('collection_id');

    if (!$this->getRequest()->isPost()) {
      return;
    }
    $values = $this->getRequest()->getPost();

    if ($values['confirm'] != $collection_id) {
      return;
    }

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
      $collection = Engine_Api::_()->getItem('sitereaction_collection', $collection_id);
      $collection->delete();
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

  public function uploadStickerAction() {

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

    $values = $this->getRequest()->getPost();
    if (empty($values['Filename']) && !isset($_FILES['Filedata'])) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No file');
      return;
    }

    if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid Upload');
      return;
    }

    $db = Engine_Api::_()->getDbtable('stickers', 'sitereaction')->getAdapter();
    $db->beginTransaction();

    try {
      $stickerTable = Engine_Api::_()->getDbtable('stickers', 'sitereaction');
      $sticker = $stickerTable->createRow();
      $sticker->save();

      $sticker->order = $sticker->sticker_id;
      $sticker->setSticker($_FILES['Filedata']);
      $sticker->save();

      $this->view->status = true;
      $this->view->name = $sticker->getTitle();
      $this->view->sticker_id = $sticker->sticker_id;
      $this->view->imgSrc = $sticker->getPhotoUrl();
      $db->commit();
      return $sticker->sticker_id;
    } catch (Core_Model_Exception $e) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = $this->view->translate($e->getMessage());
      throw $e;
      return;
    } catch (Exception $e) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
      throw $e;
      return;
    }
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
          $row = Engine_Api::_()->getItem('sitereaction_collection', (int) $value);
          if (!empty($row)) {
            $row->order = $key + 1;
            $row->save();
          }
        }
        $db->commit();
        $this->_redirect('admin/sitereaction/collection');
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }
  }

  public function manageSearchAction() {
    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitereaction_admin_main', array(), 'sitereaction_admin_main_searchlist');
    $this->view->searchList = Engine_Api::_()->getDbtable('stickersearch', 'sitereaction')->getList();
  }

  public function createSearchAction() {
    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitereaction_admin_main', array(), 'sitereaction_admin_main_searchlist');
    // Get form
    $this->view->form = $form = new Sitereaction_Form_Admin_Collection_Search_Create();

    if (!$this->getRequest()->isPost()) {
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
      $table = Engine_Api::_()->getDbtable('stickersearch', 'sitereaction');
      $row = $table->createRow();
      $row->setFromArray($values);
      $row->save();
      // Set photo
      if( !empty($values['photo']) ) {
        $row->setIcon($form->photo);
      }
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->_redirect('admin/sitereaction/collection/manage-search');
  }

  public function editSearchAction() {
    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitereaction_admin_main', array(), 'sitereaction_admin_main_searchlist');
    // Get form
    $search_id = $this->_getParam('search_id');
    $table = Engine_Api::_()->getDbtable('stickersearch', 'sitereaction');
    $this->view->search = $search = $table->findRow($search_id);
    $this->view->form = $form = new Sitereaction_Form_Admin_Collection_Search_Edit(array(
      'BackGroundColor' => $search->background_color));
    if (!$this->getRequest()->isPost()) {
      $form->populate($search->toArray());
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
      $search->setFromArray($values);
      $search->save();
      // Set photo
      if( !empty($values['photo']) ) {
        $search->setIcon($form->photo);
      }
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->_redirect('admin/sitereaction/collection/manage-search');
  }

  public function deleteSearchAction() {
    //LAYOUT
    $this->_helper->layout->setLayout('admin-simple');
// Get form
    $this->view->search_id = $search_id = $this->_getParam('search_id');

    if (!$this->getRequest()->isPost()) {
      return;
    }
    $values = $this->getRequest()->getPost();

    if ($values['confirm'] != $search_id) {
      return;
    }

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
      $table = Engine_Api::_()->getDbtable('stickersearch', 'sitereaction');
      $search = $table->findRow($search_id);
      $search->delete();
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

  public function updateSearchOrderAction() {
    //CHECK POST
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $table = Engine_Api::_()->getDbtable('stickersearch', 'sitereaction');
      $db->beginTransaction();
      $values = $_POST;
      try {
        foreach ($values['order'] as $key => $value) {
          $row = $table->findRow($value);
          if (!empty($row)) {
            $row->order = $key + 1;
            $row->save();
          }
        }
        $db->commit();
        $this->_redirect('admin/sitereaction/collection/manage-search');
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }
  }

}
