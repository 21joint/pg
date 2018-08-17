<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_AdminGuidesController extends Core_Controller_Action_Admin
{
  public function indexAction(){
      $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_guides');
      
      $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Guide_Filter();
      $page = $this->_getParam('page', 1);
      $values = $this->getRequest()->getPost();
      if( $formFilter->isValid($this->_getAllParams()) ) {
//          $values = $formFilter->getValues();
      }
      
      $table = Engine_Api::_()->getDbtable("guides","sdparentalguide");
      $tableName = $listingTableName = $table->info("name");    
      $usersTable = Engine_Api::_()->getDbtable('users', 'user');
      $usersTableName = $usersTable->info("name");
      $select = $table->select()->setIntegrityCheck(false)->from($tableName)
              ->joinLeft($usersTableName,"$usersTableName.user_id = $tableName.owner_id",array());
      
    if( !empty($values['displayname']) ) {
      $select->where($usersTableName.'.displayname LIKE ?', '%' . $values['displayname'] . '%');
    }
    if( !empty($values['username']) ) {
      $select->where($usersTableName.'.username LIKE ?', '%' . $values['username'] . '%');
    }
    if( !empty($values['email']) ) {
      $select->where($usersTableName.'.email LIKE ?', '%' . $values['email'] . '%');
    }
    if( !empty($values['guide_title']) ) {
      $select->where($listingTableName.".title LIKE ? OR $listingTableName.description LIKE ?", '%' . $values['guide_title'] . '%');
    }
    if( !empty($values['level']) ) {
      $select->where($usersTableName.'.level_id = ?', $values['level'] );
    }
    if( isset($values['enabled']) && $values['enabled'] != -1 ) {
      $select->where($usersTableName.'.enabled = ?', $values['enabled'] );
    }
    
      if ($values['approved'] != '' && $values['approved'] != -1) {
        $select->where($listingTableName . '.approved = ? ', $values['approved']);
      }

      if ($values['featured'] != '' && $values['featured'] != -1) {
        $select->where($listingTableName . '.featured = ? ', $values['featured']);
      }
      
      if ($values['sponsored'] != '' && $values['sponsored'] != -1) {
        $select->where($listingTableName . '.sponsored = ? ', $values['sponsored']);
      }

      if ($values['newlabel'] != '' && $values['newlabel'] != -1) {
        $select->where($listingTableName . '.newlabel = ? ', $values['newlabel']);
      }

      if ($values['status'] != '' && $values['status'] != -1) {
        $select->where($listingTableName . '.closed = ? ', $values['status']);
      }
      
      if (!empty($values['topic_id'])) {
        $select->where($listingTableName . '.topic_id = ? ', $values['topic_id']);
      }
            
      $values = array_merge(array(
        'order' => 'guide_id',
        'order_direction' => 'DESC',
            ), $values);

      $select->order((!empty($values['order']) ? $values['order'] : 'guide_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));          
      $valuesCopy = array_filter($values);
      
       // Make paginator
      $this->view->paginator = $paginator = Zend_Paginator::factory($select);
      $this->view->paginator = $paginator->setCurrentPageNumber( $page );
      $paginator->setItemCountPerPage(15);
      $this->view->formValues = $valuesCopy;
  }
  
  public function updateGuideAction(){
      $updateKey = $this->getParam("param_key");
      if(empty($updateKey)){
          $this->view->status = false;
          return;
      }
      $guide = Engine_Api::_()->getItem("sdparentalguide_guide",$this->getParam("guide_id",0));
      if(empty($guide) || !isset($guide->$updateKey)){
          $this->view->status = false;
          return;
      }
      $status = $this->getParam("status",0);
      $guide->$updateKey = $status;
      $guide->save();
      $this->view->status = true;
      
  }
  
  public function changeOwnerAction() {

    //LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

    //GET LISTING ID
    $this->view->guide_id = $guide_id = $this->_getParam('guide_id');

    //FORM
    $form = $this->view->form = new Sdparentalguide_Form_Admin_Guide_Changeowner();

    //SET ACTION
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));
    $guide = Engine_Api::_()->getItem('sdparentalguide_guide', $guide_id);

    //CHECK FORM VALIDATION
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      //GET FORM VALUES
      $values = $form->getValues();

      //GET USER ID WHICH IS NOW NEW USER
      $changeuserid = $values['user_id'];

      //GET DB
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
          $guide->owner_id = $changeuserid;
          $guide->save();
          
          $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      //SUCCESS
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 300,
          'parentRefresh' => 300,
          'messages' => array(Zend_Registry::get('Zend_Translate')->_('The guide owner has been changed succesfully.'))
      ));
    }
  }
  
  public function changeTopicAction() {

    //LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

    //GET LISTING ID
    $this->view->guide_id = $guide_id = $this->_getParam('guide_id');

    //FORM
    $form = $this->view->form = new Sdparentalguide_Form_Admin_Guide_Changetopic();

    //SET ACTION
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));
    $guide = Engine_Api::_()->getItem('sdparentalguide_guide', $guide_id);

    //CHECK FORM VALIDATION
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      //GET FORM VALUES
      $values = $form->getValues();
      $topic_id = $values['topic_id'];
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
          $guide->topic_id = $topic_id;
          $guide->save();
          
          $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      //SUCCESS
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 300,
          'parentRefresh' => 300,
          'messages' => array(Zend_Registry::get('Zend_Translate')->_('The guide topic has been changed succesfully.'))
      ));
    }
  }
  
  public function deleteAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $guide_id = $this->_getParam('guide_id');
    $this->view->guide_id = $guide_id;

    if ($this->getRequest()->isPost()) {
      Engine_Api::_()->getItem('sdparentalguide_guide', $guide_id)->delete();
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('Deleted Succesfully.')
      ));
    }
  }
  
  public function deleteGuideAction(){
      $guide_ids = $this->getParam("guide_ids");
      if(empty($guide_ids)){
          $this->_forward('index');
          return;
      }
      $table = Engine_Api::_()->getDbtable("guides","sdparentalguide");
      $guides = $table->fetchAll($table->select()->where("guide_id IN (?)",$guide_ids));
      foreach($guides as $guide){
          $guide->delete();
      }
      
      $this->_forward('index');
  }
  
  public function denyGuideAction(){
      $guide_ids = $this->getParam("guide_ids");
      if(empty($guide_ids)){
          $this->_forward('index');
          return;
      }
      $table = Engine_Api::_()->getDbtable("guides","sdparentalguide");
      $guides = $table->fetchAll($table->select()->where("guide_id IN (?)",$guide_ids));
      foreach($guides as $guide){
          $guide->approved = 0;
          $guide->save();
      }
      
      $this->_forward('index');
  }
  
  public function approveGuideAction(){
      $guide_ids = $this->getParam("guide_ids");
      if(empty($guide_ids)){
          $this->_forward('index');
          return;
      }
      $table = Engine_Api::_()->getDbtable("guides","sdparentalguide");
      $guides = $table->fetchAll($table->select()->where("guide_id IN (?)",$guide_ids));
      foreach($guides as $guide){
          $guide->approved = 1;
          $guide->save();
      }
      
      $this->_forward('index');
  }
}