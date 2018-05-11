<?php
/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Ggcommunity
 */
class Ggcommunity_AdminManageController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('ggcommunity_admin_main', array(), 'ggcommunity_admin_main_manage')
    ;

    $this->view->form = $form = new Ggcommunity_Form_Admin_Manage_Filter();
    $page = $this->_getParam('page',1);

    $table = Engine_Api::_()->getDbtable('questions', 'ggcommunity');
    $select = $table->select();

    // Process form
    $values = array();
    if( $form->isValid($this->_getAllParams()) ) {
      $values = $form->getValues();
    }

    foreach( $values as $key => $value ) {
      if( null === $value ) {
        unset($values[$key]);
      }
    }

    $this->view->assign($values);

    // Filter out junk
    $valuesCopy = array_filter($values);

    // Make paginator
    $limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.question.page');
    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('ggcommunity_question')->getQuestionsPaginator($values);
    $this->view->paginator->setItemCountPerPage($limit);
    $this->view->paginator->setCurrentPageNumber($page);

    $this->view->formValues = $valuesCopy;
  }

  public function optionAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->question_id = $id = $this->_getParam('id');
    $this->view->type = $type = $this->_getParam('type');
    $this->view->type_id = $type_id = $this->_getParam('type_id');
    $this->view->form = $form = new Ggcommunity_Form_Admin_Manage_Option();

    $translate = Zend_Registry::get('Zend_Translate');
    
    switch ($type) {
      case '0':
        $option = 'approved';
        break;
      case '1':
        $option = 'featured';
        break;
      case '2':
        $option = 'sponsored';
      break; 
    }

    $option_type = ucfirst($option);
    switch ($type_id) {
      case 0:
        $form->setTitle($translate->translate($option_type . ' Question'));
        $form->setDescription($translate->translate('Are you sure you want to ' . $option .' this item?'));
        $form->submit->setLabel($translate->translate($option_type));
        break;
      case 1:
        $form->setTitle($translate->translate('Un-'. $option_type. ' Question'));
        $form->setDescription($translate->translate('Are you sure you want to un-'. $option. ' this item?'));
        $form->submit->setLabel($translate->translate('Un-' . $option_type));
      break;
    }

    // Check post
    if( $this->getRequest()->isPost() )
    {
    
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try
      {
        $question = Engine_Api::_()->getItem('ggcommunity_question', $id);
        if($type_id == 0 ? $question[$option] = 1 : $question[$option] = 0);
        $question->save();
        $db->commit();
      }

      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 20,
          'parentRefresh'=> 20,
          'messages' => array($translate->translate('This option has been changed'))
      ));
    }

    // Output
    $this->renderScript('admin-manage/option.tpl');
  }

  public function deleteAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->id = $id = $this->_getParam('id');
    $type = $this->_getParam('type');

    // render form for deleting question 
    $this->view->form = $form = new Ggcommunity_Form_Delete();

    // Check post
    if( $this->getRequest()->isPost() )
    {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try
      {
        $subject = Engine_Api::_()->getItem($type, $id);
        $subject->delete();
        $db->commit();

      }

      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }

      $translate = Zend_Registry::get('Zend_Translate');

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 20,
          'parentRefresh'=> 20,
          'messages' => array($translate->translate('This item has been successfully deleted'))
      ));

    }

  }

  public function answerAction() {
    
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('ggcommunity_admin_main', array(), 'ggcommunity_admin_main_answer')
    ;

    $this->view->form = $form = new Ggcommunity_Form_Admin_Manage_Search();

    $table = Engine_Api::_()->getDbtable('answers', 'ggcommunity');
    $select = $table->select();

    // Process form
    $values = array();
    if( $form->isValid($this->_getAllParams()) ) {
      $values = $form->getValues();
    }

    foreach( $values as $key => $value ) {
      if( null === $value ) {
        unset($values[$key]);
      }
    }
    
    $values = array_merge(array(
      'order' => 'answer_id',
      'order_direction' => 'DESC',
    ), $values);

    $this->view->assign($values);

    // Set up select info
    $select->order(( !empty($values['order']) ? $values['order'] : 'answer_id' ) . ' ' . ( !empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

    if( !empty($values['keyword']) ) {
      $select->where('body LIKE ?', '%' . $values['keyword'] . '%');
    }
  
    // Filter out junk
    $valuesCopy = array_filter($values);

    $page = $this->_getParam('page', 1);
    // Make paginator
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator->setCurrentPageNumber( $page );
    $this->view->formValues = $valuesCopy;

  }


  public function commentAction() {
    
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('ggcommunity_admin_main', array(), 'ggcommunity_admin_main_comment')
    ;
    $this->view->form = $form = new Ggcommunity_Form_Admin_Manage_Search();

    $table = Engine_Api::_()->getDbtable('comments', 'ggcommunity');
    $select = $table->select();

    // Process form
    $values = array();
    if( $form->isValid($this->_getAllParams()) ) {
      $values = $form->getValues();
    }

    foreach( $values as $key => $value ) {
      if( null === $value ) {
        unset($values[$key]);
      }
    }

    $values = array_merge(array(
      'order' => 'comment_id',
      'order_direction' => 'DESC',
    ), $values);

    $this->view->assign($values);

    // Set up select info
    $select->order(( !empty($values['order']) ? $values['order'] : 'comment_id' ) . ' ' . ( !empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

    if( !empty($values['keyword']) ) {
      $select->where('body LIKE ?', '%' . $values['keyword'] . '%');
    }
    
    // Filter out junk
    $valuesCopy = array_filter($values);

    $page = $this->_getParam('page', 1);
    // Make paginator
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator->setCurrentPageNumber( $page );
    $this->view->formValues = $valuesCopy;

  }

  
}