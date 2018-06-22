<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_AdminManageController extends Core_Controller_Action_Admin
{
  public function indexAction(){
      $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_manage');
      
            
      $settingFile = APPLICATION_PATH . '/application/settings/cache.php';
      $defaultFilePath = APPLICATION_PATH . '/temporary/cache';
      $this->view->form = $form = new Sdparentalguide_Form_Admin_Manage_Global();
      return;

      if( file_exists($settingFile) ) {
        $currentCache = include $settingFile;
      } else {
        $currentCache = array(
          'default_backend' => 'File',
          'frontend' => array (
            'core' => array (
              'automatic_serialization' => true,
              'cache_id_prefix' => 'Engine4_',
              'lifetime' => '300',
              'caching' => true,
              'gzip' => 1,
            ),
          ),
          'backend' => array(
            'File' => array(
              'cache_dir' => APPLICATION_PATH . '/temporary/cache',
            ),
          ),
        );
        $form->addError(Zend_Registry::get('Zend_Translate')->_("Please update cache settings from Global Settings --> Performance."));
        return;
      }      
      $currentCache['default_file_path'] = $defaultFilePath;      
      
      $form->populate($currentCache);
      
      if( is_writable($settingFile) || (is_writable(dirname($settingFile)) && !file_exists($settingFile)) ) {
        // do nothing
      } else {
        $phrase = Zend_Registry::get('Zend_Translate')->_('Changes made to this form will not be saved.  Please adjust the permissions (CHMOD) of file %s to 777 and try again.');
        $form->addError(sprintf($phrase, '/application/settings/cache.php'));
        return;
      }
      
      
      if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {
            
            $post = $this->getRequest()->getPost();
            $table = Engine_Api::_()->getDbTable("settings","sdparentalguide");      
            $table->setSetting("gg.cache",$post['enable']);
            $table->setSetting("gg.cacheage",$post['lifetime']);/*
          
//            $code = "<?php\ndefined('_ENGINE') or die('Access Denied');\nreturn ";
//            $options = array();
//            switch( $this->getRequest()->getPost('type') ) {
//              case 'File':
//                $options['file_locking'] = (bool) $this->_getParam('file_locking');
//                $options['cache_dir'] = $this->_getParam('file_path');
//                if( !is_writable($options['cache_dir']) ) {
//                  $options['cache_dir'] = $defaultFilePath;
//                  $form->getElement('file_path')->setValue($defaultFilePath);
//                }
//                break;
//              case 'Memcached':
//                $options['servers'][] = array(
//                  'host' => $this->_getParam('memcache_host'),
//                  'port' => (int) $this->_getParam('memcache_port'),
//                );
//                $options['compression'] = (bool) $this->_getParam('memcache_compression');
//                break;
//              case 'Engine_Cache_Backend_Redis':
//                $options['servers'][] = array(
//                  'host' => $this->_getParam('redis_host'),
//                  'port' => (int) $this->_getParam('redis_port'),
//                );
//            }
//            $currentCache['backend'] = array($this->_getParam('type') => $options);
//            $currentCache['frontend']['core']['lifetime'] = $this->_getParam('lifetime');
//            $currentCache['frontend']['core']['caching'] = (bool) $this->_getParam('enable');
//            $currentCache['frontend']['core']['gzip'] = (bool) $this->_getParam('gzip_html');     
//
//            $code .= var_export($currentCache, true);
//            $code .= '; ?>';*/
            $this->view->success = true;
            
            
            if( $this->view->success /*&& file_put_contents($settingFile, $code)*/ ) {
                $form->addNotice('Your changes have been saved.');
            } elseif( $this->view->success ) {
                $form->addError('Your settings were unable to be saved to the
                  cache file.  Please log in through FTP and either CHMOD 777 the file
                  <em>/application/settings/cache.php</em>, or edit that file and
                  replace the existing code with the following:<br/>
                  <code>' . htmlspecialchars($code) . '</code>');
            }
      }    
  }
  
  public function onboardingAction(){
      $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_onboarding');
      
      $this->view->form = $form = new Sdparentalguide_Form_Admin_Manage_Onboarding();
      
      if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ){
          $post = $this->getRequest()->getPost();
          $table = Engine_Api::_()->getDbTable("settings","sdparentalguide");      
          $table->setSetting("gg.use.default.onboarding",$post['enable']);
          $form->addNotice('Your changes have been saved.');
//          $signupTable = Engine_Api::_()->getDbTable("signup","user");
//          if(!empty($post['enable'])){
//              $signupTable->update(array(
//                  
//              ));
//          }
          
      }
  }
  
  public function listingsAction(){
      $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_listings');
      
      $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Manage_FilterListings();
      $page = $this->_getParam('page', 1);
      $values = $this->getRequest()->getPost();
      if( $formFilter->isValid($this->_getAllParams()) ) {
//          $values = $formFilter->getValues();
      }
      
      $table = Engine_Api::_()->getDbtable('listings', 'sitereview');
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
    if( !empty($values['listing_title']) ) {
      $select->where($listingTableName.".title LIKE ? OR $listingTableName.body LIKE ?", '%' . $values['listing_title'] . '%');
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
      
      if (!empty($values['category_id'])) {
        $select->where($listingTableName . '.category_id = ? ', $values['category_id']);
      }
      
      if (!empty($values['listing_type'])) {
        $select->where($listingTableName . '.listingtype_id = ? ', $values['listing_type']);
      }
      
      $values = array_merge(array(
        'order' => 'listing_id',
        'order_direction' => 'DESC',
            ), $values);

      $select->order((!empty($values['order']) ? $values['order'] : 'listing_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
          
      $valuesCopy = array_filter($values);
      
       // Make paginator
      $this->view->paginator = $paginator = Zend_Paginator::factory($select);
      $this->view->paginator = $paginator->setCurrentPageNumber( $page );
      $paginator->setItemCountPerPage(15);
      $this->view->formValues = $valuesCopy;
  }
  
  public function badgesAction(){
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_badges');
    $this->view->navigation2 = $navigation2 = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main_badges', array(), 'sdparentalguide_admin_badge_badges');  
    
    $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Badge_FilterBadges();
    $page = $this->_getParam('page', 1);
    $values = array();
    if( $formFilter->isValid($this->_getAllParams()) ) {
      $values = $formFilter->getValues();
    }
    $table = Engine_Api::_()->getDbtable('badges', 'sdparentalguide');
    $tableName = $table->info("name");    
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
    
    if(isset($values['active']) && ($values['active'] == 0 || $values['active'] == 1)){
        $select->where("active = ?",(int)$values['active']);
    }  
    if(isset($values['profile_display']) && ($values['profile_display'] == 0 || $values['profile_display'] == 1)){
        $select->where("profile_display = ?",(int)$values['profile_display']);
    }
    $select->order("badge_id DESC");
    $valuesCopy = array_filter($values);
      
       // Make paginator
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator->setCurrentPageNumber( $page );
    $paginator->setItemCountPerPage(50);
    $this->view->formValues = $valuesCopy;
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
  
  public function featuredUsersAction(){
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_featuredusers');
    $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Manage_FilterUsers();
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
    
    if( !empty($values['featured']) || is_array($values['featured']) ) {
        $select->where($tableName.'.gg_featured = ?',1);
    }
    
    if( !empty($values['mvp']) || is_array($values['mvp']) ) {
        $select->where($tableName.'.gg_mvp = ?',1);
    }
    
    if( !empty($values['expert']) || is_array($values['expert']) ) {
        $select->where($tableName.'.gg_expert = ?',1);
    }
        
//    echo $select;exit;
    $valuesCopy = array_filter($values);
      
       // Make paginator
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator->setCurrentPageNumber( $page );
    $paginator->setItemCountPerPage(15);
    $this->view->formValues = $valuesCopy;
  }
  public function suggestAction(){
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
  public function suggestLevelAction(){
        $table = Engine_Api::_()->getDbTable("levels","authorization");
        $tableName = $table->info("name");
        $select = $table->select();
        if( null !== ($text = $this->getParam('search', $this->getParam('value'))) ) {
            $select->where("`$tableName`.`title` LIKE ?", '%'. $text .'%');
        }
        $select->limit(20);
        $levels = $table->fetchAll($select);
        $data = array();
        if(count($levels) > 0){
            foreach($levels as $level){
                $data[] = array(
                    'type'  => 'user',
                    'id'    => $level->getIdentity(),
                    'label' => $level->title,
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
  public function markFeaturedAction(){
      $user_ids = $this->getParam("user_ids");
      if(empty($user_ids)){
          $this->view->status = false;
          return;
      }
      $featured = $this->getParam("featured",0);
      $table = Engine_Api::_()->getDbtable('users', 'user');
      $table->update(array('gg_featured' => (int)$featured),array('user_id IN(?)' => $user_ids));
      
      $listingsTable = Engine_Api::_()->getDbTable("listings","sitereview");
      $listingsTable->update(array('featured' => (int)$featured),array('owner_id IN(?)' => $user_ids));
      
      $this->view->status = true;
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
  
  public function updateListingAction(){
      $updateKey = $this->getParam("param_key");
      if(empty($updateKey)){
          $this->view->status = false;
          return;
      }
      $sitereview = Engine_Api::_()->getItem("sitereview_listing",$this->getParam("listing_id",0));
      if(empty($sitereview) || !isset($sitereview->$updateKey)){
          $this->view->status = false;
          return;
      }
      $status = $this->getParam("status",0);
      $sitereview->$updateKey = $status;
      $sitereview->save();
      $this->view->status = true;
      
  }
  public function approveListingAction(){
      $listing_ids = $this->getParam("listing_ids");
      if(empty($listing_ids)){
          $this->_forward('listings');
          return;
      }
      $table = Engine_Api::_()->getDbTable("listings","sitereview");
      $listings = $table->fetchAll($table->select()->where("listing_id IN (?)",$listing_ids));
      foreach($listings as $sitereview){
          $sitereview->approved = 1;
          $sitereview->save();
      }
      
      $this->_forward('listings');
  }
  public function denyListingAction(){
      $listing_ids = $this->getParam("listing_ids");
      if(empty($listing_ids)){
          $this->_forward('listings');
          return;
      }
      $table = Engine_Api::_()->getDbTable("listings","sitereview");
      $listings = $table->fetchAll($table->select()->where("listing_id IN (?)",$listing_ids));
      foreach($listings as $sitereview){
          $sitereview->approved = 0;
          $sitereview->save();
      }
      
      $this->_forward('listings');
  }
  public function deleteListingAction(){
      $listing_ids = $this->getParam("listing_ids");
      if(empty($listing_ids)){
          $this->_forward('listings');
          return;
      }
      $table = Engine_Api::_()->getDbTable("listings","sitereview");
      $listings = $table->fetchAll($table->select()->where("listing_id IN (?)",$listing_ids));
      foreach($listings as $sitereview){
          $sitereview->delete();
      }
      
      $this->_forward('listings');
  }
  
  public function markMvpAction(){
      $user_ids = $this->getParam("user_ids");
      if(empty($user_ids)){
          $this->view->status = false;
          return;
      }
      $mvp = $this->getParam("mvp",0);
      $table = Engine_Api::_()->getDbtable('users', 'user');
      $table->update(array('gg_mvp' => (int)$mvp),array('user_id IN(?)' => $user_ids));
      $this->view->status = true;
  }
  
  public function jobsAction(){
      $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_jobs');
      
      $this->view->formFilterJobs = $formFilterJobs = new Sdparentalguide_Form_Admin_Manage_FilterJobs();
      
      $tasksTable = Engine_Api::_()->getDbTable("tasks","sdparentalguide");
      if($this->getRequest()->isPost()){
          
          $taskId = $this->getParam("task_id");
          $page = $this->getParam("page",1);
          $job_user = $this->getParam("job_user");
          $task = Engine_Api::_()->getItem("sdparentalguide_task",$taskId);
          if(empty($task)){
              $this->view->status = false;
              return;
          }
          if($page == 1){
              $task->log(sprintf($this->view->translate("Batch Job Starting: %s"),$task->getTitle()));
          }
          $paginator = $task->run($page,$job_user);
          $this->view->nextPage = 0;
          if($paginator->count() > $paginator->getCurrentPageNumber()){
              $this->view->nextPage = ($page+1);
          }else{
              $task->log(sprintf($this->view->translate("Batch Job Finished: %s"),$task->getTitle()));
          }
          $this->view->totalPages = $paginator->count();
          $this->view->status = true;
          
      }else{       
        $this->view->tasks = $tasksTable->fetchAll($tasksTable->select());
      }
  }
  
  public function suggestUserAction(){
    $table = Engine_Api::_()->getDbTable('users', 'user');
    $tableName = $table->info("name");
    $valuesTable = Engine_Api::_()->fields()->getTable('user', 'values');
    $valuesTableName = $valuesTable->info("name");
    $select = $table->select();
    $type = $this->getParam("type","displayname");
    if( null !== ($text = $this->getParam('search', $this->getParam('value'))) ) {
        $select->setIntegrityCheck(false)
                ->from($tableName)
                ->joinLeft($valuesTableName,"$valuesTableName.item_id = $tableName.user_id",array());
        if($type == 'first_name'){
            $select->where("$valuesTableName.field_id = ?",3)
                    ->where("$valuesTableName.value LIKE ? ", '%'. $text .'%');
        }else{
            $select->where("$valuesTableName.field_id = ?",4)
                    ->where("$valuesTableName.value LIKE ? ", '%'. $text .'%');
        }
    }
    if( null !== ($text = $this->getParam('username')) ) {
      $select->where("username LIKE ?", '%'. $text .'%');
    }
    if( null !== ($text = $this->getParam('email')) ) {
      $select->where("email LIKE ?", '%'. $text .'%');
    }
    $select->limit(20);
    $users = $table->fetchAll($select);
    $data = array();
    if(count($users) > 0){
      foreach($users as $user){
          $label = $user->username;
          $displaynameArray = explode(" ",$user->displayname);
          if($type == 'email'){
              $label = $user->email;
          }
          $firstName = isset($displaynameArray[0])?$displaynameArray[0]:$user->displayname;
          if($type == 'first_name'){
              $label = $firstName;
          }
          $lastName = isset($displaynameArray[1])?$displaynameArray[1]:'';
          if($type == 'last_name'){
              $label = $lastName;
          }
          $data[] = array(
            'type'  => 'user',
            'id'    => $user->getIdentity(),
            'guid'  => $user->getGuid(),
            'label' => $label,
            'photo' => "",
            'url'   => $user->getHref(),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $user->email,
            'username' => $user->username
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
  
  public function userDetailAction(){
      $user = Engine_Api::_()->user()->getUser($this->getParam("user_id"));
      if(!$user->getIdentity()){
          $this->view->status = false;
          return;
      }
      
      $api = Engine_Api::_()->sdparentalguide();
      $userData = $user->toArray();
      $userData['first_name'] = $api->getFieldValue($user,3);
      $userData['last_name'] = $api->getFieldValue($user,4);
      $userData['href'] = $user->getHref();
      unset($userData['creation_ip']);
      unset($userData['lastlogin_ip']);
      unset($userData['gg_ip_lastmodified']);
      $this->view->data = $userData;
      $this->view->status = true;
  }
}