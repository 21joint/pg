<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitepagereview_Widget_FeaturedReviewsCarouselController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    //SEARCH PARAMETER
    $params = array();
    $params['page_validation'] = 1;
		$params['featured'] = 1;
    $widgetType = 'featuredcarousel';
    $this->view->category_id = $params['category_id'] = $this->_getParam('category_id',0);
    $this->view->featuredReviews = $featuredReviews = Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->reviewRatingData($params,$widgetType);
    $this->view->totalCount_review = count($featuredReviews);
    if (!($this->view->totalCount_review > 0)) {
      return $this->setNoRender();
    }

    $this->view->inOneRow_review = $inOneRow = $this->_getParam('inOneRow', 3);
    $this->view->noOfRow_review = $noOfRow = $this->_getParam('noOfRow', 2);
    $this->view->totalItemShowreview = $totalItemShow = $inOneRow * $noOfRow;
    $params['limit'] = $totalItemShow;
    // List List featured
    $this->view->featuredReviews = $featuredReviews = Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->reviewRatingData($params,$widgetType);

    // CAROUSEL SETTINGS  
    $this->view->interval = $interval = $this->_getParam('interval', 250);
    $this->view->count = $count = $featuredReviews->count();
    $this->view->heightRow = @ceil($count / $inOneRow);
    $this->view->vertical = $this->_getParam('vertical', 0);
  }

}