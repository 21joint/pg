<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_AdminTopicsController extends Core_Controller_Action_Admin
{
    public function init(){
        $subject = null;
        if( !Engine_Api::_()->core()->hasSubject() )
        {
          $id = $this->_getParam('topic_id');

          if( null !== $id )
          {
            $subject = Engine_Api::_()->getItem("sdparentalguide_topic",$id);
            if( $subject->getIdentity() )
            {
              Engine_Api::_()->core()->setSubject($subject);
            }
          }
        }
    }
    public function indexAction(){
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sdparentalguide_admin_main', array(), 'sdparentalguide_admin_main_topics');
        
        $this->view->formFilter = $formFilter = new Sdparentalguide_Form_Admin_Topic_Filter();
        $page = $this->_getParam('page', 1);
        $values = $this->getRequest()->getPost();
        
        $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
        $select = $table->select();
        
        if (!empty($values['search'])) {
            $select->where('name LIKE ? OR description LIKE ? OR body LIKE ?', "%".$values['search']."%");
        }
        
        if (!empty($values['category_id'])) {
            $select->where('category_id = ? ', $values['category_id']);
        }
        
        if (isset($values['active']) && $values['active'] == 0) {
            $select->where('approved = ? ', (int)$values['active']);
        }
        
        if (isset($values['active']) && $values['active'] == 1) {
            $select->where('approved = ? ', (int)$values['active']);
        }
        
        if (isset($values['badges']) && $values['badges'] == 1) {
            $select->where('badges = ? ', (int)$values['badges']);
        }
        
        if (isset($values['featured']) && $values['featured'] == 1) {
            $select->where('featured = ? ', (int)$values['featured']);
        }
        
        if (!empty($values['subcategory_id'])) {
            $select->where('subcategory_id = ? ', $values['subcategory_id']);
        }

        if (!empty($values['listingtype_id'])) {
            $select->where('listingtype_id = ? ', $values['listingtype_id']);
        }
        $select->order("topic_id DESC");
        
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        $this->view->paginator = $paginator->setCurrentPageNumber( $page );
        $paginator->setItemCountPerPage(15);
    }
    public function createAction(){
        $this->view->form = $form = new Sdparentalguide_Form_Admin_Topic_Create();
        $viewer = Engine_Api::_()->user()->getViewer();
        
        $listingtype_id = $this->getParam('listingtype_id');
        if(!empty($listingtype_id)){
            $categoryOptions = $this->getCategories($listingtype_id);
            $form->category_id->setMultiOptions($categoryOptions);
        }
        $category_id = $this->getParam("category_id");
        if(!empty($category_id)){
            $subcategoryOptions = $this->getSubcategories($category_id);
            if(!empty($subcategoryOptions)){
                $form->subcategory_id->setMultiOptions($subcategoryOptions);
            }
        }
        
        if(!$this->getRequest()->isPost()){
            return;
        }
                
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }
        
        $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try{
            $values = $form->getValues();
            $values['owner_id'] = $viewer->getIdentity();
            $topic = $table->createRow();
            $topic->setFromArray($values);
            $topic->save();
            
            if(!empty($values['photo'])){
                $topic->setPhoto($form->photo);
            }
            
            $db->commit();
            
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 1000,
                'parentRefresh' => true,
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('New topic is created successfully.'))
            ));
        } catch (Exception $ex) {
            $db->rollBack();
            throw $ex;
        }
    }
    public function editAction(){
        $this->_helper->requireSubject('sdparentalguide_topic');
        $topic = Engine_Api::_()->core()->getSubject();
        $this->view->form = $form = new Sdparentalguide_Form_Admin_Topic_Create();
        $form->setTitle("Edit Topic");
        $form->photo->setRequired(false);
        $form->photo->setAllowEmpty(true);
        $form->populate($topic->toArray());
        if(!empty($topic->listingtype_id)){
            $categoryOptions = $this->getCategories($topic->listingtype_id);
            $form->category_id->setMultiOptions($categoryOptions);
        }
        if(!empty($topic->category_id)){
            $subcategoryOptions = $this->getSubcategories($topic->category_id);
            if(!empty($subcategoryOptions)){
                $form->subcategory_id->setMultiOptions($subcategoryOptions);
            }    
        }
        
        if(!$this->getRequest()->isPost()){
            return;
        }
        
        if(!$form->isValid($this->getRequest()->getPost())){
            return;
        }
        
        $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try{
            $values = $form->getValues();
            $topic->setFromArray($values);
            $topic->save();
            $db->commit();
            
            if(!empty($values['photo'])){
                $topic->setPhoto($form->photo);
            }
            
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
    $this->view->badge = $topic = Engine_Api::_()->core()->getSubject();
    $this->view->badge_id = $topic->getIdentity();
    
    // Check post
    if( $this->getRequest()->isPost())
    {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try
      {
        $topic->delete();
        $db->commit();
        
        return $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 1000,
            'parentRefresh'=> 10,
            'messages' => array('Topic has been deleted successfully.')
        ));
      }

      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }

      
    }
    // Output
    $this->renderScript('admin-topics/delete.tpl');
  }
  
  public function approveAction(){
        $this->_helper->requireSubject('sdparentalguide_topic');
        $topic = Engine_Api::_()->core()->getSubject();
        $status = (int)$this->getParam("status");
        $where = array(
            'topic_id = ?' => $topic->getIdentity()
        );
        $topic->approved = $status;
        $topic->save();        
        $this->view->status = true;        
  }
  
  public function approveBulkAction(){
        $status = (int)$this->getParam("status");
        $topicIds = $this->getParam("topic_ids");     
        if(empty($topicIds)){
            $this->view->status = false;
            return;
        }
        foreach($topicIds as $id){
            $topic = Engine_Api::_()->getItem("sdparentalguide_topic",$id);
            if(empty($topic)){
                continue;
            }
            $topic->approved = $status;
            $topic->save();
        }
        $this->view->status = true;        
  }
  
  public function getCategories($listing_type){
      $table = Engine_Api::_()->getDbTable("categories","sitereview");;
      $categories = $table->getCategories(null,0,$listing_type);
      $categoryOptions = array('0' => 'Category');
      foreach($categories as $category){
          $categoryOptions[$category->getIdentity()] = $category->getTitle();
      }
      return $categoryOptions;
  }
  
  public function getSubcategories($category_id){
      $table = Engine_Api::_()->getDbTable("categories","sitereview");
      $categories = $table->getSubCategories($category_id);
      $categoryOptions = array('0' => 'Sub Category');
      foreach($categories as $category){
          $categoryOptions[$category->getIdentity()] = $category->getTitle();
      }
      return $categoryOptions;
  }
      
  public function bulkBadgesAction(){
        $status = (int)$this->getParam("status");
        $topicIds = $this->getParam("topic_ids");     
        if(empty($topicIds)){
            $this->view->status = false;
            return;
        }
        foreach($topicIds as $id){
            $topic = Engine_Api::_()->getItem("sdparentalguide_topic",$id);
            if(empty($topic)){
                continue;
            }
            $topic->badges = $status;
            $topic->save();
        }
        $this->view->status = true;        
  }
  
  public function suggestAction(){
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
    
    public function multiModifyAction(){
        $topic_ids = $this->getParam("topic_ids");
        if(empty($topic_ids)){
            return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
        }
        foreach($topic_ids as $topic_id){
            $topic = Engine_Api::_()->getItem("sdparentalguide_topic",$topic_id);
            if(empty($topic)){
                continue;
            }
            $topic->delete();
        }
        return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }
}