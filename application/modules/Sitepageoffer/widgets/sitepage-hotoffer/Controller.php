<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Widget_SitepageHotofferController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $getPackageOffer = Engine_Api::_()->sitepage()->getPackageAuthInfo('sitepageoffer');
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    //NUMBER OF OFFERS IN LISTING
    $totalOffers = $this->_getParam('itemCount', 3);

    $sitepageoffer_hotoffer = Zend_Registry::isRegistered('sitepageoffer_hotoffer') ? Zend_Registry::get('sitepageoffer_hotoffer') : null;

    //GET OFFER DATAS
    $offerType = 'hot';
    $category_id = $this->_getParam('category_id',0);
    $this->view->recentlyview = $row = Engine_Api::_()->getDbtable('offers', 'sitepageoffer')->getWidgetOffers($totalOffers, $offerType,$category_id);
    $this->view->hotOffer = 1;

    if ( ( Count($row) <= 0 ) || empty($sitepageoffer_hotoffer) || empty($getPackageOffer) ) {
      return $this->setNoRender();
    }
  }

}
?>