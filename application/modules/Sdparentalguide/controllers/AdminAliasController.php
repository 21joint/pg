<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_AdminAliasController extends Core_Controller_Action_Admin{   
    
    public function init(){
        $subject = null;
        if( !Engine_Api::_()->core()->hasSubject() )
        {
          $id = $this->_getParam('searchterm_id');

          if( null !== $id )
          {
            $subject = Engine_Api::_()->getItem("sdparentalguide_search_term",$id);
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
    
    public function indexAction(){
        $this->_helper->requireSubject('sdparentalguide_search_term');
        $this->view->badge = $searchterm = Engine_Api::_()->core()->getSubject();
        $searchterm_id = $searchterm->getIdentity();
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_search');
        $this->view->navigation2 = $navigation2 = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main_search', array(), 'sdparentalguide_admin_search_alias'); 
        
        $page = $this->_getParam('page', 1);
        $values = $this->getRequest()->getPost();
        
        $this->view->searchterm = $searchterm = Engine_Api::_()->getItem("sdparentalguide_search_term",$searchterm_id);
        
        $table = Engine_Api::_()->getDbtable('searchTermsAliases', 'sdparentalguide');
        
        $select = $table->select()
            ->where("searchterm_id =? ", $searchterm_id)    
            ->order("searchtermsalias_id DESC");
        
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        $this->view->paginator = $paginator->setCurrentPageNumber( $page );
        $paginator->setItemCountPerPage(15);
    }
    public function createAction(){
        $this->view->form = $form = new Sdparentalguide_Form_Admin_Alias_Create();
        $viewer = Engine_Api::_()->user()->getViewer();
        
        if(!$this->getRequest()->isPost()){
            return;
        }
                
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }
        
        $table = Engine_Api::_()->getDbtable('searchTermsAliases', 'sdparentalguide');
        
        $db = $table->getAdapter();
        $db->beginTransaction();
        try{
            $values = $form->getValues();
            $alias = $table->createRow();
            $alias->setFromArray($values);
            $alias->searchterm_id = $this->_getParam('searchterm_id');
            $alias->save();
            $db->commit();
            
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 1000,
                'parentRefresh' => true,
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('New Alias Term is created successfully.'))
            ));
        } catch (Exception $ex) {
            $db->rollBack();
            throw $ex;
        }
    }
    public function editAction(){
        $id = $this->_getParam('searchtermsalias_id');
        $aliastable = Engine_Api::_()->getDbtable('searchTermsAliases', 'sdparentalguide');
        $alias = $aliastable->find($id)->current();
        $this->view->form = $form = new Sdparentalguide_Form_Admin_Alias_Create();
        $form->setTitle("Edit Topic");
        $form->populate($alias->toArray());
        
        if(!$this->getRequest()->isPost()){
            return;
        }
        
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }
        
        $table = Engine_Api::_()->getDbtable('searchTermsAliases', 'sdparentalguide');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try{
            $values = $form->getValues();
            $alias->setFromArray($values);
            $alias->save();
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
    public function deleteAction(){
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('searchtermsalias_id');

        if( $this->getRequest()->isPost())
        {
          $table = Engine_Api::_()->getDbtable('searchTermsAliases', 'sdparentalguide');

          $db = $table->getAdapter();
          $db->beginTransaction();

          try
          {
            $table = Engine_Api::_()->getDbtable('searchTermsAliases', 'sdparentalguide');  
            $select = $table->delete(array('searchtermsalias_id = ?' => $id));
            $db->commit();

            return $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 1000,
                'parentRefresh'=> 10,
                'messages' => array('Alias Term has been deleted successfully.')
            ));
          }

          catch( Exception $e )
          {
            $db->rollBack();
            throw $e;
          }


        }
        // Output
        $this->renderScript('admin-alias/delete.tpl');
    }
  
}