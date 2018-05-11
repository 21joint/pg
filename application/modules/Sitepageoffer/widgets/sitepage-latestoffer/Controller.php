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
class Sitepageoffer_Widget_SitepageLatestofferController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $getPackageOffer = Engine_Api::_()->sitepage()->getPackageAuthInfo('sitepageoffer');
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
		$layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);

		//NUMBER OF OFFERS IN LISTING
		$totalOffers = $this->_getParam('itemCount', 3);
		
    $sitepageoffer_lastoffer = Zend_Registry::isRegistered('sitepageoffer_lastoffer') ? Zend_Registry::get('sitepageoffer_lastoffer') : null;

    //GET OFFER DATAS
    $offerType = 'latest';
    $category_id = $this->_getParam('category_id',0);
    $this->view->recentlyview = $row = Engine_Api::_()->getDbtable('offers', 'sitepageoffer')->getWidgetOffers($totalOffers, $offerType,$category_id);
    $this->view->hotOffer = 0;

    if ( ( Count($row) <= 0 ) || empty($sitepageoffer_lastoffer) || empty($getPackageOffer) ) {
      return $this->setNoRender();
    }
  }

}
?>