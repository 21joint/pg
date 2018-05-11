<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_AdminController extends Core_Controller_Action_Admin {

  //ACTION FOR MAKE OFFER HOT AND REMOVE HOT OFFER 
  public function hotofferAction() {

    //GET OFFER ID
    $offerId = $this->_getParam('id');
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $offerId);
      if ($sitepageoffer->hotoffer == 0) {
        $sitepageoffer->hotoffer = 1;
      } else {
        $sitepageoffer->hotoffer = 0;
      }
      $sitepageoffer->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->_redirect('admin/sitepageoffer/manage');
  }

  //VIEW OFFER DETAILS
  public function detailAction() {

    //GET OFFER ID
    $offerId = $this->_getParam('id');

    //FETCH THE OFFER DETAIL
    $this->view->sitepageofferDetail = Engine_Api::_()->getDbtable('offers', 'sitepageoffer')->getOfferDetail($offerId);
  }

  //ACTION FOR DELETE THE OFFERS
  public function deleteAction() {

    //RENDER DEFAULT LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

    //GET OFFER ID
    $this->view->offer_id = $offer_id = $this->_getParam('id');

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {

				Engine_Api::_()->sitepageoffer()->deleteContent($offer_id);

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('')
      ));
    }
    $this->renderScript('admin/delete.tpl');
  }

}
?>