<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepage
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Plugin_Menus {

  public function canViewOffers() {

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.offer.show.menu', 1)) {
      return false;
    }

    $table = Engine_Api::_()->getDbtable('offers', 'sitepageoffer');
    $rName = $table->info('name');
    $table_pages = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $rName_pages = $table_pages->info('name');
    
    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
   
    $today = date("Y-m-d H:i:s");
    if (!empty($viewer_id)) {
      // Convert times
      $oldTz = date_default_timezone_get();
      date_default_timezone_set($viewer->timezone);
      $today = date("Y-m-d H:i:s");
      date_default_timezone_set($oldTz);
    }
    $select = $table->select()
                    ->setIntegrityCheck(false)
                    ->from($rName_pages, array('photo_id', 'title as sitepage_title'))
                    ->join($rName, $rName . '.page_id = ' . $rName_pages . '.page_id')
                    ->where("($rName.end_settings = 1 AND $rName.end_time >= '$today' OR $rName.end_settings = 0) ");

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

  //SITEMOBILE PAGE ALBUM MENUS
  public function onMenuInitialize_SitepageofferAdd($row) {

    $can_create_offer = $this->commonChecks();

    //CHECKS FOR ADD OFFER
    if (empty($can_create_offer)) {
      return false;
    }

    $sitepageoffer = $this->getSitepageOfferObject();

    if (empty($sitepageoffer)) {
      return false;
    }

    $page_id = $sitepageoffer->page_id;
    return array(
        'label' => 'Add an Offer',
        'route' => 'sitepageoffer_general',
        'class'=>'ui-btn-action',
        'params' => array(
            'action' => 'create',
            'page_id' => $page_id,
            'tab' => Zend_Controller_Front::getInstance()->getRequest()->getParam('tab')
        )
    );
  }

  public function onMenuInitialize_SitepageofferEdit($row) {

    $subject = Engine_Api::_()->core()->getSubject();

    $can_create_offer = $this->commonChecks();

    //CHECKS FOR EDIT OFFER
    if (empty($can_create_offer)) {
      return false;
    }

    $sitepageoffer = $this->getSitepageOfferObject();

    if (empty($sitepageoffer)) {
      return false;
    }

    $page_id = $sitepageoffer->page_id;

    return array(
        'label' => 'Edit Offer',
        'route' => 'sitepageoffer_general',
        'class'=>'ui-btn-action',
        'params' => array(
            'action' => 'edit',
            'offer_id' => $subject->getIdentity(),
            'page_id' => $page_id,
            'tab' => Zend_Controller_Front::getInstance()->getRequest()->getParam('tab')
        )
    );
  }

  public function onMenuInitialize_SitepageofferDelete($row) {

    $subject = Engine_Api::_()->core()->getSubject();

    $can_create_offer = $this->commonChecks();

    //CHECKS FOR DELETE OFFER
    if (empty($can_create_offer)) {
      return false;
    }

    $sitepageoffer = $this->getSitepageOfferObject();

    if (empty($sitepageoffer)) {
      return false;
    }

    $page_id = $sitepageoffer->page_id;
    return array(
        'label' => 'Delete Offer',
        'route' => 'sitepageoffer_general',
        'class'=>'ui-btn-danger',
        'params' => array(
            'action' => 'delete',
            'offer_id' => $subject->getIdentity(),
            'page_id' => $page_id,
            'tab' => Zend_Controller_Front::getInstance()->getRequest()->getParam('tab')
        )
    );
  }

//  public function onMenuInitialize_SitepageofferPrint($row) {
//
//    $subject = Engine_Api::_()->core()->getSubject();
//
//    $sitepageoffer = $this->getSitepageOfferObject();
//
//    if (empty($sitepageoffer)) {
//      return false;
//    }
//
//    $page_id = $sitepageoffer->page_id;
//    return array(
//        'label' => 'Print Offer',
//        'route' => 'sitepageoffer_general',
//        'target' => '_blank',
//        'params' => array(
//            'action' => 'print',
//            'offer_id' => $subject->getIdentity(),
//            'page_id' => $page_id
//        )
//    );
//  }

  public function onMenuInitialize_SitepageofferShare($row) {

    $subject = Engine_Api::_()->core()->getSubject();
    return array(
        'label' => 'Share',
        'route' => 'default',
        'class' => 'ui-btn-action smoothbox',
        'params' => array(
            'module' => 'activity',
            'controller' => 'index',
            'action' => 'share',
            'id' => $subject->getIdentity(),
            'type' => 'sitepageoffer_offer',
        ),

    );
  }

  public function onMenuInitialize_SitepageofferReport($row) {

    $subject = Engine_Api::_()->core()->getSubject();

    return array(
        'label' => 'Report',
        'route' => 'default',
        'class' => 'ui-btn-action smoothbox',
        'params' => array(
            'module' => 'core',
            'controller' => 'report',
            'action' => 'create',
            'subject' => $subject->getGuid(),
        ),

    );
  }

  public function commonChecks() {

    //$viewer = Engine_Api::_()->user()->getViewer();

    $sitepageoffer = $this->getSitepageOfferObject();
    if (empty($sitepageoffer)) {
      return false;
    }
    $page_id = $sitepageoffer->page_id;
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
    //START MANAGE-ADMIN CHECK
    $can_offer = 1;
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'offer');
    if (empty($isManageAdmin)) {
      $can_offer = 0;
    }

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
    } else {
      $can_edit = 1;
    }

    $can_create_offer = '';
    //OFFER CREATION AUTHENTICATION CHECK
    if ($can_edit == 1 && $can_offer == 1) {
      $can_create_offer = 1;
    }

    return $can_create_offer;
  }

  public function getSitepageOfferObject() {
    $subject = Engine_Api::_()->core()->getSubject();
    $offer_id = $subject->getIdentity();
    $sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $offer_id);

    if (empty($sitepageoffer)) {
      return false;
    }

    return $sitepageoffer;
  }

}

?>