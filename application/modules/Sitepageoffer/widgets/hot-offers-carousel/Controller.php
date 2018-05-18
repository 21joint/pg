<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitepageoffer_Widget_HotOffersCarouselController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    //SEARCH PARAMETER
    $params = array();
    $hotOffer = 1;
    $params['offertype'] = 'hotoffer';
    $this->view->viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $this->view->category_id = $params['category_id'] = $this->_getParam('category_id',0);
    $this->view->hotOffers = $hotOffers = Engine_Api::_()->getDbTable('offers', 'sitepageoffer')->getOffers($hotOffer,$params);
    $this->view->totalCount_offer = count($hotOffers);
    if (!($this->view->totalCount_offer > 0)) {
      return $this->setNoRender();
    }

    $this->view->inOneRow_offer = $inOneRow = $this->_getParam('inOneRow', 3);
    $this->view->noOfRow_offer = $noOfRow = $this->_getParam('noOfRow', 2);
    $this->view->totalItemShowoffer = $totalItemShow = $inOneRow * $noOfRow;
    $params['limit'] = $totalItemShow;
    // List List hot
    $this->view->hotOffers = $this->view->hotOffers = $hotOffers = Engine_Api::_()->getDbTable('offers', 'sitepageoffer')->getOffers($hotOffer,$params);

    // CAROUSEL SETTINGS  
    $this->view->interval = $interval = $this->_getParam('interval', 250);
    $this->view->count = $count = $hotOffers->count();
    $this->view->heightRow = @ceil($count / $inOneRow);
    $this->view->vertical = $this->_getParam('vertical', 0);
  }

}