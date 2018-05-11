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
class Sitepageoffer_Widget_SitepageSponsoredofferController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $getPackageOffer = Engine_Api::_()->sitepage()->getPackageAuthInfo('sitepageoffer');

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();

    //NUMBER OF OFFERS IN LISTING
    $totalOffers = $this->_getParam('itemCount', 3);
    $category_id = $this->_getParam('category_id',0);

    //GET OFFER DATAS
    $offerType = 'sponsored';
    $this->view->recentlyview = $row = Engine_Api::_()->getDbtable('offers', 'sitepageoffer')->getWidgetOffers($totalOffers, $offerType,$category_id);

    if ( ( Count($row) <= 0 ) || empty($getPackageOffer) ) {
      return $this->setNoRender();
    }
  }

}

?>