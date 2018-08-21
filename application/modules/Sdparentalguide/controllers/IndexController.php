<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_IndexController extends Core_Controller_Action_Standard
{
  public function indexAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    if ($viewer) {
        Engine_Api::_()->core()->setSubject($viewer);
    }
    $this->_helper->content->setEnabled();

    if (!$this->_helper->requireUser()->isValid())
        return;
  }
  public function listingsAction(){
      if (!$this->_helper->requireUser()->isValid())
        return;
      
      $installApi = Engine_Api::_()->getApi("install","sdparentalguide");
      $installApi->addListingPage();
      $pageTable = Engine_Api::_()->getDbtable('pages', 'core');
      $pageSelect = $pageTable->select()->where('name = ?','sdparentalguide_index_listings');
      $pageObject = $pageTable->fetchRow($pageSelect);
      $viewer = Engine_Api::_()->user()->getViewer();
      if(!$pageObject->allowedToView($viewer) ) {
        return $this->_forward('requireauth', 'error', 'core');
      }
      
      $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
      $level_id = $viewer->getIdentity() ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
      if(!$permissionsTable->getAllowed('sitereview_listing', $level_id, "grade_listtype_0")){
          return $this->_forward('requireauth', 'error', 'core');
      }
      
      $this->_helper->content->setEnabled();
  }
  
  public function suggestUsernameAction(){
        $table = Engine_Api::_()->getDbTable('users', 'user');
        $tableName = $table->info("name");
        $select = $table->select();
        if( null !== ($text = $this->getParam('search', $this->getParam('value'))) ) {
            $select->where("`$tableName`.`username` LIKE ?", '%'. $text .'%');
        }
        $select->limit(20);
        $users = $table->fetchAll($select);
        $data = array();
        if(count($users) > 0){
            foreach($users as $user){
                $data[] = array(
                    'type'  => 'user',
                    'id'    => $user->getIdentity(),
                    'guid'  => $user->getGuid(),
                    'label' => $user->username,
                    'photo' => $this->view->itemPhoto($user, 'thumb.icon'),
                    'url'   => $user->getHref(),
                );
            }
        }
        
        if( $this->_getParam('sendNow', true) ) {
            return $this->_helper->json($data);
        } else {
            $this->_helper->viewRenderer->setNoRender(true);
            $data = Zend_Json::encode($data);
            $this->getResponse()->setBody($data);
        }
  }
  public function suggestDisplaynameAction(){
        $table = Engine_Api::_()->getDbTable('users', 'user');
        $tableName = $table->info("name");
        $select = $table->select();
        if( null !== ($text = $this->getParam('search', $this->getParam('value'))) ) {
            $select->where("`$tableName`.`displayname` LIKE ?", '%'. $text .'%');
        }
        $select->limit(20);
        $users = $table->fetchAll($select);
        $data = array();
        if(count($users) > 0){
            foreach($users as $user){
                $data[] = array(
                    'type'  => 'user',
                    'id'    => $user->getIdentity(),
                    'guid'  => $user->getGuid(),
                    'label' => $user->getTitle(),
                    'photo' => $this->view->itemPhoto($user, 'thumb.icon'),
                    'url'   => $user->getHref(),
                );
            }
        }
        
        if( $this->_getParam('sendNow', true) ) {
            return $this->_helper->json($data);
        } else {
            $this->_helper->viewRenderer->setNoRender(true);
            $data = Zend_Json::encode($data);
            $this->getResponse()->setBody($data);
        }
  }
  
  public function suggestCategoryAction(){
        $table = Engine_Api::_()->getDbTable('categories', 'sitereview');
        $tableName = $table->info("name");
        $select = $table->select();
        if( null !== ($text = $this->getParam('search', $this->getParam('value'))) ) {
            $select->where("`$tableName`.`category_name` LIKE ?", '%'. $text .'%');
        }
        $select->limit(20);
        $categories = $table->fetchAll($select);
        $data = array();
        if(count($categories) > 0){
            foreach($categories as $category){
                $data[] = array(
                    'type'  => 'user',
                    'id'    => $category->getIdentity(),
                    'guid'  => $category->getGuid(),
                    'label' => $category->category_name,
                    'url'   => $category->getHref(),
                );
            }
        }
        
        if( $this->_getParam('sendNow', true) ) {
            return $this->_helper->json($data);
        } else {
            $this->_helper->viewRenderer->setNoRender(true);
            $data = Zend_Json::encode($data);
            $this->getResponse()->setBody($data);
        }
  }
  
  public function getCategoriesAction(){
      $listing_type = $this->getParam("listing_type");
      if(empty($listing_type)){
          $this->view->status = false;
          return;
      }
      $table = Engine_Api::_()->getDbTable("categories","sitereview");;
      $categories = $table->getCategories(null,0,$listing_type);
      $categoryOptions = array();
      foreach($categories as $category){
          $categoryOptions[] = array(
              'id' => $category->getIdentity(),
              'title' => $category->getTitle()
          );
      }
      $this->view->categories = $categoryOptions;
      $this->view->status = true;
  }
  
  public function getSubcategoriesAction(){
      $category_id = $this->getParam("category_id");
      if(empty($category_id)){
          $this->view->status = false;
          return;
      }
      $table = Engine_Api::_()->getDbTable("categories","sitereview");;
      $categories = $table->getSubCategories($category_id);
      $categoryOptions = array();
      foreach($categories as $category){
          $categoryOptions[] = array(
              'id' => $category->getIdentity(),
              'title' => $category->getTitle()
          );
      }
      $this->view->categories = $categoryOptions;
      $this->view->status = true;
  }
  
  public function categoriesAction() {

    $element_value = $this->_getParam('element_value', 1);
    $element_type = $this->_getParam('element_type', 'listingtype_id');

    $categoriesTable = Engine_Api::_()->getDbTable('categories', 'sitereview');
    $select = $categoriesTable->select()
            ->from($categoriesTable->info('name'), array('category_id', 'category_name'))
            ->where("$element_type IN (?)", (array)$element_value);

    if ($element_type == 'listingtype_id') {
      $select->where('cat_dependency = ?', 0)->where('subcat_dependency = ?', 0);
    } elseif ($element_type == 'cat_dependency') {
      $select->where('subcat_dependency = ?', 0);
    } elseif ($element_type == 'subcat_dependency') {
      $select->where('cat_dependency = ?', $element_value);
    }

    $categoriesData = $categoriesTable->fetchAll($select);

    $categories = array();
    if (Count($categoriesData) > 0) {
      foreach ($categoriesData as $category) {
        $data = array();
        $data['category_name'] = $category->category_name;
        $data['category_id'] = $category->category_id;
        $categories[] = $data;
      }
    }
    $this->view->categories = $categories;
  }
  public function assignbadgesAction(){
    if (!$this->_helper->requireUser()->isValid())
        return;
    $viewer = Engine_Api::_()->user()->getViewer();
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $level_id = $viewer->getIdentity() ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    if(!$permissionsTable->getAllowed('sdparentalguide_custom', $level_id, "assign_badge")){
          return $this->_forward('requireauth', 'error', 'core');
    }
    
    $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Badge_FilterUsers();
    $formFilter->removeElement("level_id");
    
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
            ->group("$tableName.user_id")
            ->where($tableName.'.user_id <> ?',$viewer->getIdentity());
    
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
  
  public function assignUserAction(){
      if (!$this->_helper->requireUser()->isValid())
        return;
        $viewer = Engine_Api::_()->user()->getViewer();
        $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
        $level_id = $viewer->getIdentity() ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        if(!$permissionsTable->getAllowed('sdparentalguide_custom', $level_id, "assign_badge")){
            return $this->_forward('requireauth', 'error', 'core');
        }
        
        $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
        $this->_helper->requireSubject('user');
        $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Badge_FilterBadges();
        $this->view->user = $user = Engine_Api::_()->user()->getUser($this->getParam("user_id"));
        if(empty($user) || $user->isSelf($viewer)){
            return $this->_helper->redirector->gotoRoute(array('action' => 'assignbadges'),'sdparentalguide_general',true);
        }
        $formFilter->removeElement("active");
        
        $page = $this->_getParam('page', 1);
        $values = array();
        
        if( $formFilter->isValid($this->_getAllParams()) ) {
          $values = $formFilter->getValues();
        }
        $table = Engine_Api::_()->getDbtable('badges', 'sdparentalguide');
        $tableName = $table->info("name");
        
        
        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        $assignedTableName = $assignedTable->info("name");
        
        $selectAssigned = $table->select()->setIntegrityCheck(false)->from($tableName)
                ->joinLeft($assignedTableName,"$assignedTableName.badge_id = $tableName.badge_id",array("$assignedTableName.active as assigned_active"))
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
            $select->where("listingtype_id = ?",$values['listingtype_id']);
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

        $select->where("active = ?", 1);

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
        
        $this->initBatch();
        $viewer = Engine_Api::_()->user()->getViewer();
        $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
        $level_id = $viewer->getIdentity() ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        if(!$permissionsTable->getAllowed('sdparentalguide_custom', $level_id, "assign_badge")){
            return $this->_forward('requireauth', 'error', 'core');
        }
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
        ));
        $row->save();
        if($badge->active){
            $badge->updateUserCounts($user_id);
        }
        
        $this->view->status = true;        
    }
    public function assignBulkAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
        $level_id = $viewer->getIdentity() ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        if(!$permissionsTable->getAllowed('sdparentalguide_custom', $level_id, "assign_badge")){
            return $this->_forward('requireauth', 'error', 'core');
        }
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
            $row = $assignedTable->fetchRow($assignedTable->select()->where('badge_id = ?',$badge_id)->where('user_id = ?',$user_id));
            if(empty($row)){
                $row = $assignedTable->createRow();
                $row->owner_id = $viewer->getIdentity();
            }            
            $row->setFromArray(array(
                'user_id' => $user_id,
                'badge_id' => $badge->getIdentity(),
                'active' => 1
            ));
            $row->save();
            
            if($badge->active){
                $badge->updateUserCounts($user_id);
            }
        }
        
        
        $this->view->status = true;        
    }
    public function assignStatusAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
        $level_id = $viewer->getIdentity() ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        if(!$permissionsTable->getAllowed('sdparentalguide_custom', $level_id, "assign_badge")){
            return $this->_forward('requireauth', 'error', 'core');
        }
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
    
    public function suggestTopicAction(){
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_topics');
        
        $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Topic_Filter();
        $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
        $tableName = $table->info("name");
        $select = $table->select();
        $select->where('badges = ? ', 1);
        $select->order("topic_id DESC");
        $select->limit(20);
        if( null !== ($text = $this->getParam('search', $this->getParam('value'))) ) {
            $select->where("`$tableName`.`name` LIKE ?", '%'. $text .'%');
        }
        $topics = $table->fetchAll($select);
        $data = array();
        if(count($topics) > 0){
            foreach($topics as $topic){
                $data[] = array(
                    'type'  => 'user',
                    'id'    => $topic->getIdentity(),
                    'label' => $topic->getTitle(),
                    'photo' => "",
                    'url'   => "",
                );
            }
        }
        
        if( $this->_getParam('sendNow', true) ) {
            return $this->_helper->json($data);
        } else {
            $this->_helper->viewRenderer->setNoRender(true);
            $data = Zend_Json::encode($data);
            $this->getResponse()->setBody($data);
        }
    }
    
    public function initBatch(){
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
    
    public function ajaxSearchAction(){
        $listingtype_id = $this->_getParam('listingtype_id', 0);

        //GET LISTINGS AND MAKE ARRAY
        $usersitereviews = Engine_Api::_()->getDbtable('listings', 'sdparentalguide')->getDayItems(trim($this->_getParam('text')), $this->_getParam('limit', 10), $listingtype_id);
        $data = array();
        $mode = $this->_getParam('struct');
        $count = count($usersitereviews);

        $i = 0;
        foreach ($usersitereviews as $usersitereview) {
            $sitereview_url = $this->view->url(array('listing_id' => $usersitereview->listing_id, 'slug' => $usersitereview->getSlug()), "sitereview_entry_view_listtype_$usersitereview->listingtype_id", true);
            $content_photo = $this->view->itemPhoto($usersitereview, 'thumb.icon');
            $i++;
            $data[] = array(
                'id' => $usersitereview->listing_id,
                'label' => $usersitereview->title,
                'photo' => $content_photo,
                'sitereview_url' => $sitereview_url,
                'total_count' => $count,
                'count' => $i
            );
        }

        if (!empty($data) && $i >= 1) {
            if ($data[--$i]['count'] == $count) {
                $data[$count]['id'] = 'stopevent';
                $data[$count]['label'] = $this->_getParam('text');
                $data[$count]['sitereview_url'] = 'seeMoreLink';
                $data[$count]['total_count'] = $count;
            }
        }
        return $this->_helper->json($data);
    }
    
    public function leaderboardAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addLeaderboardPage();
        $this->_helper->content->setEnabled();
    }
}
