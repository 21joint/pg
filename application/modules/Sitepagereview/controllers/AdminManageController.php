<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminManageController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_AdminManageController extends Core_Controller_Action_Admin {

  //ACTION FOR MANAGING REVIEWS
  public function manageAction() {

    //GET NAVIGATION
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepagereview_admin_main', array(), 'sitepagereview_admin_main_manage');

    //HIDDEN SEARCH FORM CONTAIN ORDER AND ORDER DIRECTION  
    $this->view->formFilter = $formFilter = new Sitepagereview_Form_Admin_Manage_Filter();
    $page = $this->_getParam('page', 1);

    //GET REVIEW DATAS
    $tableUser = Engine_Api::_()->getItemTable('user')->info('name');
    $tableSitepage = Engine_Api::_()->getItemTable('sitepage_page')->info('name');
    $tableReviewRating = Engine_Api::_()->getDbtable('ratings', 'sitepagereview');
    $tableReviewRatingName = $tableReviewRating->info('name');
    $table = Engine_Api::_()->getDbtable('reviews', 'sitepagereview');
    $rName = $table->info('name');
    $select = $table->select()
                    ->setIntegrityCheck(false)
                    ->from($rName)
                    ->joinLeft($tableUser, "$rName.owner_id = $tableUser.user_id", 'username')
                    ->joinLeft($tableSitepage, "$rName.page_id = $tableSitepage.page_id", array('title AS sitepage_title'))
                    ->joinLeft($tableReviewRatingName, "$rName.review_id = $tableReviewRatingName.review_id", array('rating As review_rating', 'reviewcat_id'))
                    ->where($tableReviewRatingName . '.reviewcat_id = ?', 0);

    $values = array();
    if ($formFilter->isValid($this->_getAllParams())) {
      $values = $formFilter->getValues();
    }

    foreach ($values as $key => $value) {
      if (null === $value) {
        unset($values[$key]);
      }
    }

    //REVIEW SEARCH WORK
    if (isset($_POST['search'])) {
      if (!empty($_POST['review_title'])) {
        $this->view->review_title = $_POST['review_title'];
        $select->where($rName . '.title  LIKE ?', '%' . $_POST['review_title'] . '%');
      }
      if (!empty($_POST['sitepage_title'])) {
        $this->view->sitepage_title = $_POST['sitepage_title'];
        $select->where($tableSitepage . '.title  LIKE ?', '%' . $_POST['sitepage_title'] . '%');
      }
    } else {
      $this->view->review_title = '';
      $this->view->sitepage_title = '';
    }

    $values = array_merge(array(
                'order' => 'review_id',
                'order_direction' => 'DESC',
                    ), $values);

    $this->view->assign($values);

    $select->order((!empty($values['order']) ? $values['order'] : 'review_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

    $this->view->paginator = array();
    include APPLICATION_PATH . '/application/modules/Sitepagereview/controllers/license/license2.php';
  }

  //ACTION FOR MAKE PAGE-DOCUMENT APPROVED/DIS-APPROVED
  public function featuredAction() {
    $review_id = $this->_getParam('review_id');
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $sitepagereview = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
      if ($sitepagereview->featured == 0) {
        $sitepagereview->featured = 1;
      } else {
        $sitepagereview->featured = 0;
      }
      $sitepagereview->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->_redirect('admin/sitepagereview/manage/manage');
  }

  //ACTION FOR DELETING A REVIEW
  public function deleteAction() {

    $review_id = $this->_getParam('review_id');

    if ($this->getRequest()->isPost()) {

      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {

				Engine_Api::_()->sitepagereview()->deleteContent($review_id);

        $db->commit();

      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => true,
          'parentRefresh' => true,
          'messages' => array('Deleted review successfully.')
      ));
    } else {
      $this->renderScript('admin-manage/delete.tpl');
    }
  }

  //ACTION FOR MULTI DELETE REVIEWS
  public function multiDeleteAction() {

    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          //DELETE DOCUMENTS FROM DATABASE AND SCRIBD
          $review_id = (int) $value;
					Engine_Api::_()->sitepagereview()->deleteContent($review_id);
        }
      }
    }
    return $this->_helper->redirector->gotoRoute(array('controller' => 'manage', 'action' => 'manage'));
  }

}
?>