<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminManageController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_AdminManageController extends Core_Controller_Action_Admin {

  //ACTION FOR MANAGE BADGES
  public function manageAction() {

    //GET NAVIGATION
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepagebadge_admin_main', array(), 'sitepagebadge_admin_main_manage');

    //GET MANAGE FILTER FORM     
    $this->view->formFilter = $formFilter = new Sitepagebadge_Form_Admin_Filter();
    $this->view->page = $page = $this->_getParam('page', 1);

    //PROCESS FROM 
    $values = array();
    if ($formFilter->isValid($this->_getAllParams())) {
      $values = $formFilter->getValues();
    }

    foreach ($values as $key => $value) {
      if (null === $value) {
        unset($values[$key]);
      }
    }

    $values = array_merge(array(
                'order' => 'badge_id',
                'order_direction' => 'DESC',
                    ), $values);

    $this->view->assign($values);

		//MAKE QUERY
    $select = Engine_Api::_()->getDbTable('badges', 'sitepagebadge')->select()
									->order((!empty($values['order']) ? $values['order'] : 'badge_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

		//FETCH THE BADGE DATA
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(50);
    $paginator->setCurrentPageNumber($page);
  }

  //ACTION FOR CREATING THE BADGE
  public function createAction() {

    //GET NAVIGATION
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepagebadge_admin_main', array(), 'sitepagebadge_admin_main_manage');

    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    //PREPARE FORM
    $this->view->form = $form = new Sitepagebadge_Form_Admin_Create();

    if (!$this->getRequest()->isPost()) {
      return;
    }

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $badge_info = Engine_Api::_()->sitepagebadge()->badgeInfo();

      //UPLOAD BADGE MAIN
      if (isset($_FILES['badge_main']) && is_uploaded_file($_FILES['badge_main']['tmp_name'])) {
        $main_file = $_FILES['badge_main'];
        $name = basename($main_file['tmp_name']);
        $path = dirname($main_file['tmp_name']);
        $main_mainName = $path . '/' . $main_file['name'];

        $main_photo_params = array(
            'parent_id' => $viewer_id,
            'parent_type' => "sitepagebadge_badge",
        );

        //BADGE IMAGE WORK
        $image = Engine_Image::factory();
        $image->open($main_file['tmp_name']);
        $image->open($main_file['tmp_name'])
                ->resample(0, 0, $image->width, $image->height, $image->width, $image->height)
                ->write($main_mainName)
                ->destroy();

        try {
          $main_photoFile = Engine_Api::_()->storage()->create($main_mainName, $main_photo_params);
        } catch (Exception $e) {
          if ($e->getCode() == Storage_Api_Storage::SPACE_LIMIT_REACHED_CODE) {
            echo $e->getMessage();
            exit();
          }
        }
      }

      $main_file_id = 0;
      if (!empty($main_photoFile->file_id)) {
        $main_file_id = $main_photoFile->file_id;
      }

			$table = Engine_Api::_()->getItemTable('sitepagebadge_badge');
      $db = $table->getAdapter();
      $db->beginTransaction();

      try {
        $values = $form->getValues();
        include APPLICATION_PATH . '/application/modules/Sitepagebadge/controllers/license/license2.php';
        // Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
    }
  }

  //ACTION FOR EDITTING THE BADGE
  public function editAction() {

    //GET NAVIGATION
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepagebadge_admin_main', array(), 'sitepagebadge_admin_main_manage');

    if (!$this->_helper->requireUser()->isValid())
      return;

    //GET LOGGED IN USER INFORMATION
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    //PREPARE FORM
    $this->view->form = $form = new Sitepagebadge_Form_Admin_Edit();

    //FETCH BADGE ITEM
    $this->view->badge_id = $badge_id = $this->_getParam('id');
    $sitepagebadge = Engine_Api::_()->getItem('sitepagebadge_badge', $badge_id);

    $form->populate($sitepagebadge->toArray());

    if (!$this->getRequest()->isPost()) {
      return;
    }

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      //UPLOAD BADGE MAIN
      $new_file_id = 0;
      if (isset($_FILES['badge_main']) && is_uploaded_file($_FILES['badge_main']['tmp_name'])) {
        $main_file = $_FILES['badge_main'];
        $name = basename($main_file['tmp_name']);
        $path = dirname($main_file['tmp_name']);
        $main_mainName = $path . '/' . $main_file['name'];

        $main_photo_params = array(
            'parent_id' => $viewer_id,
            'parent_type' => "sitepagebadge_badge",
        );

        $image = Engine_Image::factory();
        $image->open($main_file['tmp_name']);
        $image->open($main_file['tmp_name'])
                ->resample(0, 0, $image->width, $image->height, $image->width, $image->height)
                ->write($main_mainName)
                ->destroy();

        try {
          $main_photoFile = Engine_Api::_()->storage()->create($main_mainName, $main_photo_params);
        } catch (Exception $e) {
          if ($e->getCode() == Storage_Api_Storage::SPACE_LIMIT_REACHED_CODE) {
            echo $e->getMessage();
            exit();
          }
        }

        $new_file_id = $main_photoFile->file_id;

        //DELETE PREVIOUS FILE
        if (!empty($sitepagebadge->badge_main_id)) {
          $storage_main_file = Engine_Api::_()->getItem('storage_file', $sitepagebadge->badge_main_id);
          if (!empty($storage_main_file)) {
            $storage_main_file->delete();
          }
        }
      }

      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $values = $form->getValues();
        $sitepagebadge->setFromArray($values);
        if (!empty($new_file_id)) {
          $sitepagebadge->badge_main_id = $new_file_id;
        }
        $sitepagebadge->save();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
    }
  }

  //ACTION FOR ASSIGNING THE BADGE
  public function assignBadgeAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->page_id = $page_id = $this->_getParam('page_id');

    //GET PAGE ITEM
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
    $this->view->previous_badge_id = $sitepage->badge_id;

    //FATCH BADGE DATA
    $this->view->badgeData = $badgeData = Engine_Api::_()->getDbTable('badges', 'sitepagebadge')->getBadgesData($params = array());

    //CHECK PAGE OWNER HAS BEEN ALREADY REQUESTED FOR BADGE OR NOT
    $this->view->previous_request_status = Engine_Api::_()->getDbtable('badgerequests', 'sitepagebadge')->badgeRequestStatus($page_id);

    if ($this->getRequest()->isPost()) {
      $badge_id = $this->_getParam('badge_id');
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

        //ASSIGN BADGE
        $sitepage->badge_id = $badge_id;
        $sitepage->save();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array(Zend_Registry::get('Zend_Translate')->_(''))
      ));
    }
    $this->renderScript('admin-manage/assign-badge.tpl');
  }

  //ACTION FOR DELETING BADGE
  public function deleteAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->badge_id = $badge_id = $this->_getParam('id');
   
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

        Engine_Api::_()->getDbTable('pages', 'sitepage')->update(array('badge_id' => 0), array('badge_id = ?' => $badge_id));

        Engine_Api::_()->getDbTable('badgerequests', 'sitepagebadge')->delete(array('badge_id = ?' => $badge_id));

				$sitepagebadge = Engine_Api::_()->getItem('sitepagebadge_badge', $badge_id);

        //DELETE BADGE IMAGE FILE
        $badgeFile = Engine_Api::_()->getItem('storage_file', $sitepagebadge->badge_main_id);
        if (!empty($badgeFile->file_id)) {
          $badgeFile->delete();
        }

        //DELETE BADGE
        Engine_Api::_()->getDbtable('badges', 'sitepagebadge')->delete(array('badge_id = ?' => $badge_id));

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array(Zend_Registry::get('Zend_Translate')->_(''))
      ));
    }
    $this->renderScript('admin-manage/delete.tpl');
  }

  //ACTION FOR MULTI-DELETE BADGES
  public function multiDeleteAction() {
    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {

          Engine_Api::_()->getDbTable('pages', 'sitepage')->update(array('badge_id' => 0), array('badge_id = ?' => (int) $value));

          Engine_Api::_()->getDbTable('badgerequests', 'sitepagebadge')->delete(array('badge_id = ?' => (int) $value));

          $badge = Engine_Api::_()->getItem('sitepagebadge_badge', (int) $value);

          //DELETE BADGE IMAGE FILE
          $badgeFile = Engine_Api::_()->getItem('storage_file', $badge->badge_main_id);
          if (!empty($badgeFile->file_id)) {
            $badgeFile->delete();
          }

          //DELETE BADGE
          Engine_Api::_()->getDbtable('badges', 'sitepagebadge')->delete(array('badge_id = ?' => $badge->badge_id));
        }
      }
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
  }

}
?>