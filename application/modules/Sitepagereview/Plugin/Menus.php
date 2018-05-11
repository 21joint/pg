<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Plugin_Menus {

  public function canViewReviews() {

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.review.show.menu', 1)) {
      return false;
    }

    $table = Engine_Api::_()->getDbtable('reviews', 'sitepagereview');
    $rName = $table->info('name');
    $table_pages = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $rName_pages = $table_pages->info('name');
    $select = $table->select()
                    ->setIntegrityCheck(false)
                    ->from($rName_pages, array('photo_id', 'title as sitepage_title'))
                    ->join($rName, $rName . '.page_id = ' . $rName_pages . '.page_id');
                    
    $select = $select
                    ->where($rName_pages . '.closed = ?', '0')
                    ->where($rName_pages . '.approved = ?', '1')
                    ->where($rName_pages . '.search = ?', '1')
                    ->where($rName_pages . '.declined = ?', '0')
                    ->where($rName_pages . '.draft = ?', '1');
    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $select->where($rName_pages . '.expiration_date  > ?', date("Y-m-d H:i:s"));
    }
    $row = $table->fetchAll($select);
    $count = count($row);
    if (empty($count)) {
      return false;
    }
    return true;
  }


  //SITEMOBILE PAGE REVIEW MENUS
  public function onMenuInitialize_SitepagereviewEdit($row) {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    $sitepagereview = $this->getSitepageReviewObject();
    if (empty($sitepagereview)) {
      return false;
    }
    //PAGE ID
    $page_id = $sitepagereview->page_id;

    $owner_id = $sitepagereview->owner_id;

    //CHECKS FOR EDIT
    if ($viewer_id != $owner_id) {
      return false;
    }

    return array(
        'label' => 'Edit Review',
        'route' => 'sitepagereview_edit',
        'class' => 'ui-btn-action',
        'params' => array(
            'review_id' => $sitepagereview->getIdentity(),
            'page_id' => $page_id,
            'tab' => Zend_Controller_Front::getInstance()->getRequest()->getParam('tab')
        )
    );
  }

  public function onMenuInitialize_SitepagereviewDelete($row) {
    $subject = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    $sitepagereview = $this->getSitepageReviewObject();
    if (empty($sitepagereview)) {
      return false;
    }
    //PAGE ID
    $page_id = $sitepagereview->page_id;

    $owner_id = $sitepagereview->owner_id;
    $viewer->level_id;

    //CHECK FOR DELETE
    if ($viewer_id != $owner_id && $viewer->level_id != 1) {
      return false;
    }

    return array(
        'label' => 'Delete Review',
        'route' => 'sitepagereview_delete',
        'class' => 'ui-btn-danger smoothbox',
        'params' => array(
            'review_id' => $sitepagereview->getIdentity(),
            'page_id' => $page_id,
            'tab' => Zend_Controller_Front::getInstance()->getRequest()->getParam('tab')
        )
    );
  }

  public function onMenuInitialize_SitepagereviewReport($row) {
    $subject = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    $review_report = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.report', 1);
    $viewer_id = $viewer->getIdentity();

    //CHECK FOR REPORT
    if ($review_report != 1 || empty($viewer_id)) {
      return false;
    }

    return array(
        'label' => 'Report',
        'route' => 'default',
        'class' => 'ui-btn-action smoothbox',
        'params' => array(
            'module' => 'core',
            'controller' => 'report',
            'action' => 'create',
            'subject' => $subject->getGuid(),
        // 'format' => 'smoothbox'
        )
    );
  }

  public function getSitepageReviewObject() {
    $subject = Engine_Api::_()->core()->getSubject();
    //GET REVIEW ID
    $review_id = $subject->getIdentity();
    //GET REVIEW ITEM
    $sitepagereview = Engine_Api::_()->getItem('sitepagereview_review', $review_id);
    //ASK
    if (empty($sitepagereview)) {
      return false;
    }
    return $sitepagereview;
  }

}
?>