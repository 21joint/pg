<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitepageoffer_Widget_OffersSitepageoffersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {  	

		//GET WIDGET SETTINGS
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
		$this->view->popularity = $popularity = $this->_getParam('popularity', 'view_count');
		$totalOffers = $this->_getParam('itemCount', 3);
		$category_id = $this->_getParam('category_id', 0);
		
    $getPackageOffer = Engine_Api::_()->sitepage()->getPackageAuthInfo('sitepageoffer');
    $offerType = 'alloffers';

		//GET PAGE RESULTS
		$this->view->recentlyview = $row = Engine_Api::_()->getDbtable('offers', 'sitepageoffer')->getWidgetOffers($totalOffers, $offerType,$category_id,$popularity);
		
    //SET NO RENDER
    if ( ( Count($row) <= 0 ) || empty($getPackageOffer) ) {
      return $this->setNoRender();
    }
  }

}