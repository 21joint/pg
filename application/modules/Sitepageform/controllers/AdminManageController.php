<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminManageController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageform_AdminManageController extends Core_Controller_Action_Admin {

  //ACTION FOR MANAGING FORMS
  public function indexAction() {

    //CREATE NAVIGATION TABS
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sitepageform_admin_main', array(), 'sitepageform_admin_main_manage');

    //HIDDEN SEARCH FORM CONTAIN ORDER AND ORDER DIRECTION  
    $this->view->formFilter = $formFilter = new Sitepageform_Form_Admin_Manage_Filter();
    $page = $this->_getParam('page', 1);
    $table = Engine_Api::_()->getDbtable('sitepageforms', 'sitepageform');
    $table_name = $table->info('name');
    $sitepage_table = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $table_name1 = $sitepage_table->info('name');
    $select = $table->select()
            ->setIntegrityCheck(false)
            ->from($table_name)
            ->joinInner($table_name1, "$table_name.page_id = $table_name1.page_id", array('title as sitepage_title', 'page_id as sitepage_id'));

    $values = array();
    if ($formFilter->isValid($this->_getAllParams())) {
      $values = $formFilter->getValues();
    }

    foreach ($values as $key => $value) {
      if (null === $value) {
        unset($values[$key]);
      }
    }

    //FILTERING PARAMETERES
    $this->view->page_title = '';
    $this->view->form_title = '';
    $this->view->pageform_status = '';

    $values = array_merge(array(
        'order' => 'page_id',
        'order_direction' => 'DESC',
            ), $values);


    if (!empty($_POST['page_title'])) {
      $page_name = $_POST['page_title'];
    } elseif (!empty($_GET['page_title'])) {
      $page_name = $_GET['page_title'];
    } elseif ($this->_getParam('page_title', '')) {
      $page_name = $this->_getParam('page_title', '');
    } else {
      $page_name = '';
    }

    if (!empty($_POST['form_title'])) {
      $form_name = $_POST['form_title'];
    } elseif (!empty($_GET['form_title'])) {
      $form_name = $_GET['form_title'];
    } elseif ($this->_getParam('form_title', '')) {
      $form_name = $this->_getParam('form_title', '');
    } else {
      $form_name = '';
    }

    //SEARCHING
    $this->view->page_title = $values['page_title'] = $page_name;
    $this->view->form_title = $values['form_title'] = $form_name;

    if (!empty($page_name)) {
      $select->where($table_name1 . '.title  LIKE ?', '%' . $page_name . '%');
    }

    if (!empty($form_name)) {
      $select->where($table_name . '.title  LIKE ?', '%' . $form_name . '%');
    }

    if (isset($_POST['search'])) {
      if (!empty($_POST['pageform_status'])) {

        $this->view->pageform_status = $_POST['pageform_status'];
        switch ($this->view->pageform_status) {
          case 1:
            $select->where($table_name . '.status = ? ', 1);
            break;
          case 2:
            $select->where($table_name . '.status = ? ', 0);
            break;
        }
      }
    }
    $this->view->assign($values);
    $select->order((!empty($values['order']) ? $values['order'] : 'page_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
    include APPLICATION_PATH . '/application/modules/Sitepageform/controllers/license/license2.php';
  }

  //ACTION FOR DISABLING THE FORM
  public function disableFormAction() {

    //CHECK USER VALIDATION
    if (!$this->_helper->requireUser()->isValid())
      return;

    //GET PAGE ID
    $page_id = $this->_getParam('id');

    //GET FORM DATA
    $formTable = Engine_Api::_()->getDbtable('sitepageforms', 'sitepageform');
    $formSelect = $formTable->select()->where('page_id = ?', $page_id);
    $formSelectData = $formTable->fetchRow($formSelect);

    //GET FORM ID AND OBJECT
    $this->view->form_id = $sitepageform_id = $formSelectData->sitepageform_id;
    $sitepageform = Engine_Api::_()->getItem('sitepageform', $sitepageform_id);

    //SEND STATUS TO TPL
    $this->view->status = $sitepageform->status;

    //SMOOTHBOX
    if (null === $this->_helper->ajaxContext->getCurrentContext()) {
      $this->_helper->layout->setLayout('default-simple');
    } else {//NO LAYOUT
      $this->_helper->layout->disableLayout(true);
    }

    if (!$this->getRequest()->isPost())
      return;
    $db = Engine_Api::_()->getDbtable('sitepageforms', 'sitepageform')->getAdapter();
    $db->beginTransaction();
    try {
      if ($sitepageform->status == 0) {
        $sitepageform->status = 1;
      } else {
        $sitepageform->status = 0;
      }

      $sitepageform->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollback();
      throw $e;
    }
    $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array(Zend_Registry::get('Zend_Translate')->_(''))
    ));
  }

}

?>