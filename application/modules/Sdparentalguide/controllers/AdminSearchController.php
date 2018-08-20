<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_AdminSearchController extends Core_Controller_Action_Admin
{
    public function indexAction(){
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_search');
        $this->view->navigation2 = $navigation2 = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main_search', array(), 'sdparentalguide_admin_search_alias'); 
        
        $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Search_Filter();
        $page = $this->_getParam('page', 1);
        $values = $this->getRequest()->getPost();
        
        $table = Engine_Api::_()->getDbtable('searchTerms', 'sdparentalguide');
        $searchTableName = $table->info('name');
        $select = $table->select()
                ->setIntegrityCheck(false)
                ->from($searchTableName);
        
        if(!empty($values['search'])){
            $select->where($searchTableName.'.name LIKE ? ', "%".$values['search']."%");
        }
        
        if(!empty($values['alias'])){
            $aliasTable = Engine_Api::_()->getDbtable('searchTermsAliases', 'sdparentalguide');
            $aliasTableName = $aliasTable->info('name');

            $select->join($aliasTableName, $aliasTableName.'.searchterm_id = '.$searchTableName.'.searchterm_id',NULL)
                ->where($aliasTableName.'.name LIKE ? ', "%".$values['alias']."%");
        }
        $select->order($searchTableName.'.searchterm_id DESC')
            ->group($searchTableName.'.searchterm_id');
        
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        $this->view->paginator = $paginator->setCurrentPageNumber( $page );
        $paginator->setItemCountPerPage(15);
    }
    public function createAction(){
        $this->view->form = $form = new Sdparentalguide_Form_Admin_Search_Create();
        $viewer = Engine_Api::_()->user()->getViewer();
        
        if(!$this->getRequest()->isPost()){
            return;
        }
                
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }
        
        $table = Engine_Api::_()->getDbtable('searchTerms', 'sdparentalguide');
        
        $db = $table->getAdapter();
        $db->beginTransaction();
        try{
            $values = $form->getValues();
            $term = $table->createRow();
            $term->setFromArray($values);
            $term->save();
            $db->commit();
            
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 1000,
                'parentRefresh' => true,
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('New Search Term is created successfully.'))
            ));
        } catch (Exception $ex) {
            $db->rollBack();
            throw $ex;
        }
    }
    public function deleteAction(){
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('searchterm_id');

        if( $this->getRequest()->isPost())
        {
          $table = Engine_Api::_()->getDbtable('searchTerms', 'sdparentalguide');

          $db = $table->getAdapter();
          $db->beginTransaction();

          try
          {
            $table = Engine_Api::_()->getDbtable('searchTerms', 'sdparentalguide');  
            $select = $table->delete(array('searchterm_id = ?' => $id));
            
            $aliastable = Engine_Api::_()->getDbtable('searchTermsAliases', 'sdparentalguide');
            $select = $aliastable->delete(array('searchterm_id = ?' => $id));
            
            $db->commit();

            return $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 1000,
                'parentRefresh'=> 10,
                'messages' => array('Search Term has been deleted successfully.')
            ));
          }

          catch( Exception $e )
          {
            $db->rollBack();
            throw $e;
          }


        }
        // Output
        $this->renderScript('admin-search/delete.tpl');
    }
    
    public function activityAction(){
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_search');
        $this->view->navigation2 = $navigation2 = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main_search', array(), 'sdparentalguide_admin_search_activity'); 
        
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $this->view->order_column = $column_name = $request->getParam('column_name','search_text');
        $this->view->order = $order = $request->getParam('order','ASC');        
        $table = Engine_Api::_()->getDbtable('search', 'sdparentalguide');
        $clear = $request->getParam("clear");
        if(!empty($clear)){
            $db = $table->getDefaultAdapter();
            $db->query("TRUNCATE TABLE engine4_gg_search_activity");
            return $this->_helper->redirector->gotoRoute(array('clear' => 0));
        }
        
        $select = $table->select();
        $search = $request->getParam("query");
        if(!empty($search)){
            $select->where("search_text LIKE ?","%".$search."%");
        }
        if(!empty($column_name)){
            $select->order("$column_name $order");
        }
        
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        $this->view->paginator = $paginator->setCurrentPageNumber($this->getParam('page', 1));
        $paginator->setItemCountPerPage(10);
    }

    public function customactivityAction(){
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_search');
        $this->view->navigation2 = $navigation2 = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main_search', array(), 'sdparentalguide_admin_search_customactivity');

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $this->view->order_column = $column_name = $request->getParam('column_name','site_activity_id');
        $this->view->order = $order = $request->getParam('order', 'ASC');
        $table = Engine_Api::_()->getDbtable('statistics', 'sdparentalguide');
        $tableName = $table->info("name");
        $usertable = Engine_Api::_()->getDbtable('users', 'user');
        $usertableName = $usertable->info("name");
//        $clear = $request->getParam("clear");
//        if(!empty($clear)){
//            $db = $table->getDefaultAdapter();
//            $db->query("TRUNCATE TABLE engine4_gg_search_activity");
//            return $this->_helper->redirector->gotoRoute(array('clear' => 0));
//        }

        $select = $table->select()->setIntegrityCheck(false);
        $select->from($tableName, array("*"))->join($usertableName, $usertableName . ".user_id = " . $tableName . ".gg_user_created", array("username", "email"));

        $username = $request->getParam("username");
        if(!empty($username)){
            $select->where("username LIKE ?","%".$username."%");
        }
        $email = $request->getParam("email");
        if(!empty($email)){
            $select->where("email LIKE ?","%".$email."%");
        }
        $url = $request->getParam("url");
        if(!empty($url)){
            $select->where("url LIKE ?","%".$url."%");
        }
        $is_member = $request->getParam("is_member");
        if(isset($is_member) && $is_member != -1){
            $select->where("is_member = ?", $is_member);
        }
        if(!empty($column_name)){
            $select->order("$column_name $order");
        }

        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        $this->view->paginator = $paginator->setCurrentPageNumber($this->getParam('page', 1));
        $paginator->setItemCountPerPage(10);
    }

    public function analyticsAction(){
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_search');
        $this->view->navigation2 = $navigation2 = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main_search', array(), 'sdparentalguide_admin_search_analytics'); 
                
//        $searchActivitiesTable = Engine_Api::_()->getDbtable('search', 'sdparentalguide');
//        $selectActivities = $searchActivitiesTable->select()
//                    ->from($searchActivitiesTable->info('name'), array('COUNT(search_activity_id) as count', 'search_text as search_term'))
//                    ->group("search_text")
//                    ;
//        $searchActivities = $searchActivitiesTable->fetchAll($selectActivities);
//        
        $analyticsTable = Engine_Api::_()->getDbtable('searchAnalytics', 'sdparentalguide');
//        $analyticsTableName = $analyticsTable->info('name');
//        
//        foreach ($searchActivities as $searchActivity) {
//            $analyticsSelect = $analyticsTable->select()->where($analyticsTableName.'.search_term LIKE ? ', "%".$searchActivity['search_term']."%");
//            $analytics = $analyticsTable->fetchRow($analyticsSelect);
//            if($analytics){
//                $analytics->count = $searchActivity->count;
//                $analytics->save();
//            }else{
//                $analyticsValues['search_term'] = $searchActivity['search_term'];
//                $analyticsValues['count'] = $searchActivity['count'];
//                $analyticsTerm = $analyticsTable->createRow();
//                $analyticsTerm->setFromArray($analyticsValues);
//                $analyticsTerm->save();                
//            }
//        }
        
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $this->view->order_column = $column_name = $request->getParam('column_name','count');
        $this->view->order = $order = $request->getParam('order','DESC');
        $search = $request->getParam("query");
        
        $clear = $request->getParam("clear");
        if(!empty($clear)){
            $db = $analyticsTable->getDefaultAdapter();
            $db->query("TRUNCATE TABLE engine4_gg_search_analytics");
            return $this->_helper->redirector->gotoRoute(array('clear' => 0));
        }
        
        $select = $analyticsTable->select();
        if(!empty($search)){
            $select->where("search_term LIKE ?","%".$search."%");
        }
        if(!empty($column_name)){
            $select->order("$column_name $order");
        }
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        $this->view->paginator = $paginator->setCurrentPageNumber($this->getParam('page', 1));
        $paginator->setItemCountPerPage(10);
    }
  
}