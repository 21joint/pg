<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_AdminBadgeController extends Core_Controller_Action_Admin
{
    public function init(){
        $subject = null;
        if( !Engine_Api::_()->core()->hasSubject() )
        {
          $id = $this->_getParam('badge_id');

          if( null !== $id )
          {
            $subject = Engine_Api::_()->getItem("sdparentalguide_badge",$id);
            if( $subject->getIdentity() )
            {
              Engine_Api::_()->core()->setSubject($subject);
            }
          }
        }
        
        if( !Engine_Api::_()->core()->hasSubject() )
        {
          $id = $this->_getParam('user_id');

          if( null !== $id )
          {
            $subject = Engine_Api::_()->user()->getUser($id);
            if( $subject->getIdentity() )
            {
              Engine_Api::_()->core()->setSubject($subject);
            }
          }
        }
    }
    public function createAction(){
        $this->view->form = $form = new Sdparentalguide_Form_Admin_Badge_Create();
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$this->getRequest()->isPost()){
            return;
        }
        
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }
        
        $table = Engine_Api::_()->getDbtable('badges', 'sdparentalguide');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try{
            $values = $form->getValues();
            $values['owner_id'] = $viewer->getIdentity();
            $badge = $table->createRow();
            $badge->setFromArray($values);
            $badge->save();
            
            if(!empty($values['photo'])){
                $badge->setPhoto($form->photo);
            }
            
            $db->commit();
            
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 1000,
                'parentRefresh' => true,
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('New badge is added successfully.'))
            ));
        } catch (Exception $ex) {
            $db->rollBack();
            throw $ex;
        }
    }
    public function editAction(){
        $this->_helper->requireSubject('sdparentalguide_badge');
        $badge = Engine_Api::_()->core()->getSubject();
        $this->view->form = $form = new Sdparentalguide_Form_Admin_Badge_Create();
        $form->populate($badge->toArray());
        $form->photo->setRequired(false);
        $form->photo->setAllowEmpty(true);
        
        if(!$this->getRequest()->isPost()){
            return;
        }
        
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }
        
        $table = Engine_Api::_()->getDbtable('badges', 'sdparentalguide');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try{
            $values = $form->getValues();
            $badge->setFromArray($values);
            $badge->save();
            
            if(!empty($values['photo'])){
                $badge->setPhoto($form->photo);
            }
            
            $db->commit();
            
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 1000,
                'parentRefresh' => true,
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your changes have been saved successfully.'))
            ));
        } catch (Exception $ex) {
            $db->rollBack();
            throw $ex;
        }
    }
    
  public function deleteAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->badge = $badge = Engine_Api::_()->core()->getSubject();
    $this->view->badge_id = $badge->getIdentity();
    
    // Check post
    if( $this->getRequest()->isPost())
    {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try
      {
        $badge->delete();
        $db->commit();
        
        return $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 1000,
            'parentRefresh'=> 10,
            'messages' => array('Badge has been deleted successfully.')
        ));
      }

      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }

      
    }
    // Output
    $this->renderScript('admin-badge/delete.tpl');
  }
    
  public function badgeUsersAction(){
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_badges');
      
    $this->view->navigation2 = $navigation2 = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main_badges', array(), 'sdparentalguide_admin_badge_users');

    $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Badge_FilterUsers();
    $page = $this->_getParam('page', 1);
    $values = array();
    if( $formFilter->isValid($this->_getAllParams()) ) {
      $values = $formFilter->getValues();
    }
    $table = Engine_Api::_()->getDbtable('users', 'user');
    $tableName = $table->info("name");    
    $valuesTable = Engine_Api::_()->fields()->getTable('user', 'values');
    $valuesTableName = $valuesTable->info("name");
    $select = $table->select()->setIntegrityCheck(false)->from($tableName)
            ->group("$tableName.user_id");
    
    if( !empty($values['username']) ) {
      $select->where($tableName.'.username LIKE ?', '%' . $values['username'] . '%');
    }
    
    if( !empty($values['first_name']) ) {
        $valuesTableName1 = $valuesTableName."_1";
        $select->joinLeft(array($valuesTableName1 => $valuesTableName),"$valuesTableName1.item_id = $tableName.user_id",array())
                ->where("$valuesTableName1.field_id = ?",3);
        $select->where($valuesTableName1.'.value LIKE ?', '%' . $values['first_name'] . '%');
    }
    
    if( !empty($values['last_name']) ) {
        $valuesTableName2 = $valuesTableName."_2";
        $select->joinLeft(array($valuesTableName2 => $valuesTableName),"$valuesTableName2.item_id = $tableName.user_id",array())
                ->where("$valuesTableName2.field_id = ?",4);
        $select->where($valuesTableName2.'.value LIKE ?', '%' . $values['last_name'] . '%');
    }
    
    if( !empty($values['level_id']) ) {
        $select->where($tableName.'.level_id = ?',$values['level_id']);
    }
    
    $valuesCopy = array_filter($values);
      
       // Make paginator
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator->setCurrentPageNumber( $page );
    $paginator->setItemCountPerPage(50);
    $this->view->formValues = $valuesCopy;
  }
  
  public function assignAction(){
    $this->_helper->requireSubject('sdparentalguide_badge');
    $this->view->badge = $badge = Engine_Api::_()->core()->getSubject();
    $badge_id = $badge->getIdentity();
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_badges');
      
    $this->view->navigation2 = $navigation2 = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main_badges', array(), 'sdparentalguide_admin_badge_badges');

    $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Badge_FilterAssigned();
    $page = $this->_getParam('page', 1);
    $values = array();
    if( $formFilter->isValid($this->_getAllParams()) ) {
      $values = $formFilter->getValues();
    }
    $values['assigned'] = $this->getParam("assigned");
    $values['profile_display'] = $this->getParam("profile_display");
    $table = Engine_Api::_()->getDbtable('users', 'user');
    $tableName = $table->info("name");    
    $valuesTable = Engine_Api::_()->fields()->getTable('user', 'values');
    $valuesTableName = $valuesTable->info("name");
    $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
    $assignedTableName = $assignedTable->info("name");
    $select = $table->select()->setIntegrityCheck(false)->from($tableName)
            ->group("$tableName.user_id");
    
    if( !empty($values['username']) ) {
      $select->where($tableName.'.username LIKE ?', '%' . $values['username'] . '%');
    }
    
    if( !empty($values['first_name']) ) {
        $valuesTableName1 = $valuesTableName."_1";
        $select->joinLeft(array($valuesTableName1 => $valuesTableName),"$valuesTableName1.item_id = $tableName.user_id",array())
                ->where("$valuesTableName1.field_id = ?",3);
        $select->where($valuesTableName1.'.value LIKE ?', '%' . $values['first_name'] . '%');
    }
    
    if( !empty($values['last_name']) ) {
        $valuesTableName2 = $valuesTableName."_2";
        $select->joinLeft(array($valuesTableName2 => $valuesTableName),"$valuesTableName2.item_id = $tableName.user_id",array())
                ->where("$valuesTableName2.field_id = ?",4);
        $select->where($valuesTableName2.'.value LIKE ?', '%' . $values['last_name'] . '%');
    }
    
    if( !empty($values['level_id']) ) {
        $select->where($tableName.'.level_id = ?',$values['level_id']);
    }
    $select->joinLeft("$assignedTableName","($assignedTableName.user_id = $tableName.user_id AND $assignedTableName.badge_id = $badge_id) OR $assignedTableName.user_id IS NULL",array("badge_id","active","profile_display"));
    if(isset($values['assigned']) && ($values['assigned'] == 0 || $values['assigned'] == 1)){
        $select->where("$assignedTableName.active = ?",(int)$values['assigned']);
    }
    if(isset($values['profile_display']) && ($values['profile_display'] == 0 || $values['profile_display'] == 1)){
        $select->where("$assignedTableName.profile_display = ?",(int)$values['profile_display']);
    }
    $select->group("$tableName.user_id");
    
    $valuesCopy = array_filter($values);
      
       // Make paginator
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator->setCurrentPageNumber( $page );
    $paginator->setItemCountPerPage(50);
    $this->view->formValues = $valuesCopy;
  }
    public function assignUserAction(){
        $this->_helper->requireSubject('user');
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_badges');
        $this->view->navigation2 = $navigation2 = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sdparentalguide_admin_main_badges', array(), 'sdparentalguide_admin_badge_users');  

        $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Badge_FilterBadges();
        $formFilter->removeElement("profile_display");
        $page = $this->_getParam('page', 1);
        $values = array();
        if( $formFilter->isValid($this->_getAllParams()) ) {
          $values = $formFilter->getValues();
        }
        $table = Engine_Api::_()->getDbtable('badges', 'sdparentalguide');
        $tableName = $table->info("name");
        
        $this->view->user = $user = Engine_Api::_()->user()->getUser($this->getParam("user_id"));
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        $assignedTableName = $assignedTable->info("name");
        
        $selectAssigned = $table->select()->setIntegrityCheck(false)->from($tableName)
                ->joinLeft($assignedTableName,"$assignedTableName.badge_id = $tableName.badge_id",array("$assignedTableName.active as assigned_active","$assignedTableName.profile_display"))
                ->where("$assignedTableName.user_id = ?",$user->getIdentity());
        
        $this->view->assignedBadges = $assignedBadges = $table->fetchAll($selectAssigned); 
        $assignedBadgesIds = array();
        if(count($assignedBadges)){
            foreach($assignedBadges as $badge){
                $assignedBadgesIds[] = $badge->getIdentity();
            }
        }
        
        $select = $table->select()->setIntegrityCheck(false)->from($tableName)
                ;
        if(!empty($values['name'])){
            $select->where("name LIKE ?","%".$values['name']."%");
        }

        if(!empty($values['listingtype_id'])){
//            $select->where("listingtype_id = ?",$values['listingtype_id']);
        }
        if(!empty($values['topic_id'])){
            $select->where("topic_id = ?",$values['topic_id']);
        }

        if(!empty($values['level'])){
            $select->where("level = ?",$values['level']);
        }
        
        if(!empty($values['type'])){
            $select->where("type = ?",$values['type']);
        }

        if(isset($values['active']) && ($values['active'] == 0 || $values['active'] == 1)){
            $select->where("active = ?",(int)$values['active']);
        }
        if(isset($values['profile_display']) && ($values['profile_display'] == 0 || $values['profile_display'] == 1)){
            $select->where("$assignedTableName.profile_display = ?",(int)$values['profile_display']);
        }
        if(count($assignedBadgesIds) > 0){
            $select->where("badge_id NOT IN (?)",$assignedBadgesIds);
        }

        $valuesCopy = array_filter($values);

           // Make paginator
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        $this->view->paginator = $paginator->setCurrentPageNumber( $page );
        $paginator->setItemCountPerPage(50);
        $this->view->formValues = $valuesCopy;
        
    }
    public function assignQuickAction(){
        $this->_helper->requireSubject('sdparentalguide_badge');
        $badge = Engine_Api::_()->core()->getSubject();
        $user_id = $this->getParam("user_id");
        if(empty($user_id)){
            $this->view->status = false;
            return;
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        $row = $assignedTable->createRow();
        $row->setFromArray(array(
            'user_id' => $user_id,
            'badge_id' => $badge->getIdentity(),
            'owner_id' => $viewer->getIdentity(),
            'active' => 1,
            'profile_display' => 1
        ));
        $row->save();
        
        if($badge->active){
            $badge->updateUserCounts($user_id);
        }       
        
        $this->view->status = true;        
    }
    public function displayQuickAction(){
        $this->_helper->requireSubject('sdparentalguide_badge');
        $badge = Engine_Api::_()->core()->getSubject();
        $user_id = $this->getParam("user_id");
        if(empty($user_id)){
            $this->view->status = false;
            return;
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        $row = $assignedTable->createRow();
        $row->setFromArray(array(
            'user_id' => $user_id,
            'badge_id' => $badge->getIdentity(),
            'owner_id' => $viewer->getIdentity(),
            'active' => 0,
            'profile_display' => 1
        ));
        $row->save();
        
        $this->view->status = true;        
    }
    public function assignBulkAction(){
        $this->_helper->requireSubject('sdparentalguide_badge');
        $badge = Engine_Api::_()->core()->getSubject();
        $viewer = Engine_Api::_()->user()->getViewer();
        $badge_id = $badge->getIdentity();
        $user_ids = $this->getParam("user_ids");
        if(empty($user_ids)){
            $this->view->status = false;
            return;
        }
        
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        foreach($user_ids as $user_id){
            $row = $assignedTable->fetchRow($assignedTable->select()->where('badge_id = ?',$badge_id)->where('user_id = ?',$user_id));
            if(empty($row)){
                $row = $assignedTable->createRow();
                $row->owner_id = $viewer->getIdentity();
            }            
            $row->setFromArray(array(
                'user_id' => $user_id,
                'badge_id' => $badge->getIdentity(),
                'active' => 1,
                'profile_display' => 1
            ));
            $row->save();
            
            if($badge->active){
                $badge->updateUserCounts($user_id);
            }
        }
        
        
        $this->view->status = true;        
    }
    public function assignStatusAction(){
        $this->_helper->requireSubject('sdparentalguide_badge');
        $badge = Engine_Api::_()->core()->getSubject();
        $user_id = $this->getParam("user_id");
        $status = (int)$this->getParam("status");
        $where = array(
            'badge_id = ?' => $badge->getIdentity()
        );
        if(!empty($user_id)){
            $where['user_id = ?'] = $user_id;
        }
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        $select = $assignedTable->select()->where('user_id = ?',$user_id)->where('badge_id = ?',$badge->getIdentity());
        $assignedRow = $assignedTable->fetchRow($select);
        if($badge->active && $assignedRow->active && !$status){
            $badge->remvoeUserCounts($user_id);
        }
        if($badge->active && !$assignedRow->active && $status){
            $badge->updateUserCounts($user_id);
        }
        
        $assignedTable->update(array('active' => $status),$where);
        
        $this->view->status = true;        
    }
    public function displayStatusAction(){
        $this->_helper->requireSubject('sdparentalguide_badge');
        $badge = Engine_Api::_()->core()->getSubject();
        $user_id = $this->getParam("user_id");
        $status = (int)$this->getParam("status");
        $where = array(
            'badge_id = ?' => $badge->getIdentity()
        );
        if(!empty($user_id)){
            $where['user_id = ?'] = $user_id;
        }
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        $assignedTable->update(array('profile_display' => $status),$where);
        
        $this->view->status = true;        
    }
    public function deleteAssignedAction(){
        $this->_helper->requireSubject('sdparentalguide_badge');
        $badge = Engine_Api::_()->core()->getSubject();
        $user_id = $this->getParam("user_id");
        $where = array(
            'badge_id = ?' => $badge->getIdentity()
        );
        if(!empty($user_id)){
            $where['user_id = ?'] = $user_id;
        }
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        $assignedTable->delete($where);
        
        $badge->remvoeUserCounts($user_id);
        
        $this->view->status = true;        
    }
    public function deleteBulkAction(){
        $this->_helper->requireSubject('sdparentalguide_badge');
        $badge = Engine_Api::_()->core()->getSubject();
        $badge_id = $badge->getIdentity();
        $user_ids = $this->getParam("user_ids");
        if(empty($user_ids)){
            $this->view->status = false;
            return;
        }
        
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        foreach($user_ids as $user_id){
             $assignedTable->delete(array('user_id = ?' => $user_id,'badge_id = ?' => $badge_id));
             $badge->remvoeUserCounts($user_id);
        }
        
        $this->view->status = true;        
    }
    
    public function statusBulkAction(){
        $this->_helper->requireSubject('sdparentalguide_badge');
        $badge = Engine_Api::_()->core()->getSubject();
        $badge_id = $badge->getIdentity();
        $user_ids = $this->getParam("user_ids");
        $status = (int)$this->getParam("status");
        if(empty($user_ids)){
            $this->view->status = false;
            return;
        }
        
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        foreach($user_ids as $user_id){
            $select = $assignedTable->select()->where('user_id = ?',$user_id)->where('badge_id = ?',$badge_id);
            $assignedRow = $assignedTable->fetchRow($select);
            if(empty($assignedRow)){
                continue;
            }
            if($badge->active && $assignedRow->active && !$status){
                $badge->remvoeUserCounts($user_id);
            }
            if($badge->active && !$assignedRow->active && $status){
                $badge->updateUserCounts($user_id);
            }
            $assignedTable->update(array('active' => $status),array('user_id = ?' => $user_id,'badge_id = ?' => $badge_id));
        }
        
        $this->view->status = true;        
    }
    
    public function displayBulkAction(){
        $this->_helper->requireSubject('sdparentalguide_badge');
        $badge = Engine_Api::_()->core()->getSubject();
        $viewer = Engine_Api::_()->user()->getViewer();
        $badge_id = $badge->getIdentity();
        $display = $this->getParam("display");
        $user_ids = $this->getParam("user_ids");
        if(empty($user_ids)){
            $this->view->status = false;
            return;
        }
        
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        foreach($user_ids as $user_id){
            $row = $assignedTable->fetchRow($assignedTable->select()->where('badge_id = ?',$badge_id)->where('user_id = ?',$user_id));
            if(empty($row)){
                $row = $assignedTable->createRow();
                $row->owner_id = $viewer->getIdentity();
            }            
            $row->setFromArray(array(
                'user_id' => $user_id,
                'badge_id' => $badge->getIdentity(),
                'profile_display' => (int)$display
            ));
            $row->save();
        }
        
        
        $this->view->status = true;        
    }
}