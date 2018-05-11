<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminBadgerequestController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_AdminBadgerequestController extends Core_Controller_Action_Admin {

  //ACTION FOR MANAGING THE BADGE REQUESTES
  public function manageAction() {

    //GET NAVIGATION
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepagebadge_admin_main', array(), 'sitepagebadge_admin_main_badgerequest');

    //GENERATE FORM
    $this->view->formFilter = $formFilter = new Sitepagebadge_Form_Admin_Filter();

    //FETCH REQUESTS
    $badgerequestTable = Engine_Api::_()->getDbtable('badgerequests', 'sitepagebadge');
    $badgerequestTableName = $badgerequestTable->info('name');

    $userTableName = Engine_Api::_()->getDbtable('users', 'user')->info('name');

    $pagetableName = Engine_Api::_()->getDbtable('pages', 'sitepage')->info('name');

    $page = $this->_getParam('page', 1);
    $select = $badgerequestTable->select()
                    ->setIntegrityCheck(false)
                    ->from($badgerequestTableName)
                    ->join($pagetableName, $pagetableName . '.page_id = ' . $badgerequestTableName . '.page_id', array('page_id', 'title'))
                    ->join($userTableName, $userTableName . '.user_id = ' . $pagetableName . '.owner_id', array('user_id', 'email', 'displayname'))
                    ->group($badgerequestTableName . '.badgerequest_id');
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
                'order' => 'badgerequest_id',
                'order_direction' => 'DESC',
                    ), $values);

    $this->view->assign($values);
    $select->order((!empty($values['order']) ? $values['order'] : "badgerequest_id" ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
    $this->view->paginator = array();
    include APPLICATION_PATH . '/application/modules/Sitepagebadge/controllers/license/license2.php';
  }

  //ACTION FOR TAKING ACTION ON BADGE REQUSET FOR SITE-ADMIN
  public function changeStatusAction() {

    //GET BADGE REQUEST
    $badgerequest_id = $this->_getParam('badgerequest_id');
    $badgerequest = Engine_Api::_()->getItem('sitepagebadge_badgerequest', $badgerequest_id);

    //GET PAGE ITEM
    $this->view->sitepage_id = $pageid = $badgerequest->page_id;
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $badgerequest->page_id);
    $this->view->sitepage_title = $sitepage->title;

    //GET BADGE DETAIL
    $this->view->badge = $badge = Engine_Api::_()->getItem('sitepagebadge_badge', $badgerequest->badge_id);
    $badge_path = Engine_Api::_()->storage()->get($badge->badge_main_id, '')->getPhotoUrl();
    $this->view->badge_image = $badge_image = '<img src="' . $badge_path . '" class="photo" width="50" />';

    //GET USER ITEM
    $this->view->member = $member = Engine_Api::_()->getItem('user', $sitepage->owner_id);

    $this->view->badgerequest = $badgerequest;
    $host = $_SERVER['HTTP_HOST'];
    $comments_mail = '';

    if ($this->getRequest()->isPost()) {
      $status = $_POST['status'];
      $comments = $_POST['admin_comment'];
      if (!empty($comments)) {
        $comments_mail.= Zend_Registry::get('Zend_Translate')->_("Comments by the administrator:") . '<br />' . $comments;
      }

      $modified_date = new Zend_Db_Expr('NOW()');
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      //UPDATE THE BADGE REQUEST STATUS
      $badgerequestTable = Engine_Api::_()->getDbtable('badgerequests', 'sitepagebadge');
      $badgerequestTable->update(array('status' => $status, 'admin_comment' => $comments, 'modified_date' => $modified_date), array('badgerequest_id = ?' => $badgerequest_id));

      $site_title = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', 'Advertisement');

      $pagetitle = '<a href = http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('page_url' => $sitepage->page_url), 'sitepage_entry_view') . ">$sitepage->title</a>";

      $pageobjectlink = $host . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('page_url' => $sitepage->page_url), 'sitepage_entry_view');

      try {

        if ($status != 2) {
          if ($status == 1) { //SEND EMAIL TO PAGE OWNER IF BADGE REQUEST HAS BEEN APPROVED BY SITE-ADMIN
            Engine_Api::_()->getDbtable('pages', 'sitepage')->update(array('badge_id' => $badgerequest->badge_id), array('page_id = ?' => $pageid));

            $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($member->email, 'SITEPAGE_BADGEREQUEST_APPROVED_EMAIL', array(
                'page_title' => $sitepage->title,
                'badge_image' => $badge_image,
                'site_title' => $site_title,
                'page_title_with_link' => $pagetitle,
                'admin_comment' => $comments_mail,
                'page_view_url' => $pageobjectlink,
                'email' => $email,
                'queue' => true
            ));
          } elseif ($status == 4) { //SEND EMAIL TO PAGE OWNER IF BADGE REQUEST IN HOLDING STATUS BY SITE-ADMIN
            $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($member->email, 'SITEPAGE_BADGEREQUEST_HOLDING_EMAIL', array(
                'page_title' => $sitepage->title,
                'badge_image' => $badge_image,
                'site_title' => $site_title,
                'page_title_with_link' => $pagetitle,
                'admin_comment' => $comments_mail,
                'page_view_url' => $pageobjectlink,
                'email' => $email,
                'queue' => true
            ));
          }
        } else { //SEND EMAIL TO PAGE OWNER IF BADGE REQUEST HAS BEEN DECLINED BY SITE-ADMIN
          $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($member->email, 'SITEPAGE_BADGEREQUEST_DECLINED_EMAIL', array(
              'page_title' => $sitepage->title,
              'badge_image' => $badge_image,
              'site_title' => $site_title,
              'page_title_with_link' => $pagetitle,
              'admin_comment' => $comments_mail,
              'page_view_url' => $pageobjectlink,
              'email' => $email,
              'queue' => true
          ));
        }
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 300,
          'parentRefresh' => 300,
          'messages' => array(Zend_Registry::get('Zend_Translate')->_('Status updated successfully.'))
      ));
    }
  }

  //ACTION FOR DELETING THE BADGE REQUEST
  public function deleteAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->badgeRequestId = $badgeRequestId = $this->_getParam('badgerequest_id');

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
        $badgeRequest = Engine_Api::_()->getItem('sitepagebadge_badgerequest', $badgeRequestId);

        //DELETE THE BADGE REQUEST
        $badgeRequest->delete();
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
    $this->renderScript('admin-badgerequest/delete.tpl');
  }

  //ACTION FOR MULTI-DELETE BADGE REQUESTS
  public function multiDeleteAction() {
    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $badgeRequest = Engine_Api::_()->getItem('sitepagebadge_badgerequest', (int) $value);

          //DELETE THE BADGE REQUEST
          $badgeRequest->delete();
        }
      }
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
  }

}
?>