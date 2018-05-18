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
class Sitepagereview_Widget_FeaturedReviewsSlideshowController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    
    //SEARCH PARAMETER
    $params = array();
    $params['page_validation'] = 1;
    $params['category_id'] = $this->_getParam('category_id',0);
		$params['featured'] = 1;
    $params['limit'] = $this->_getParam('itemCountPerPage', 10);
   
    $this->view->show_slideshow_object = $featuredReviews = Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->reviewRatingData($params);
    $count = $featuredReviews->getTotalItemCount();
    // Count Featured Reviews
    $this->view->num_of_slideshow = $count;
    // Number of the result.
    if (empty($this->view->num_of_slideshow)) {
      return $this->setNoRender();
    }
  }

}
?>