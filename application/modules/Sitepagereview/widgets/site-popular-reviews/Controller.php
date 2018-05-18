<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 6590 2010-12-31 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Widget_SitePopularReviewsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $sitepagereview_recentInfo = Zend_Registry::isRegistered('sitepagereview_recentInfo') ? Zend_Registry::get('sitepagereview_recentInfo') : null;

		$popularity = $this->_getParam('popularity', 'view_count');

		//FETCH REVIEW DATA
    $params = array();
		$params['orderby'] = "$popularity DESC";
    $params['category_id'] = $this->_getParam('category_id',0);
		$params['zero_count'] = "$popularity";
		$params['limit'] = $this->_getParam('itemCount', 3);
		$params['page_validation'] = 1;
    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('reviews', 'sitepagereview')->reviewRatingData($params);

		//DON'T RENDER IF NO DATA FOUND
    if ((Count($paginator) <= 0) || empty($sitepagereview_recentInfo)) {
      return $this->setNoRender();
    }
  }

}
?>