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
class Sitepagereview_Widget_TopratedpagesSitepagereviewsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

		//DON'T RENDER IF DISABLE RATING
    $ratingShow = (int) Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepagereview');
    if (empty($ratingShow)) {
      return $this->setNoRender();
    }

    //GET SITEPAGE SITEPAGE FOR MOST RATED
		$params[] = array();
		$params['itemCount'] = $this->_getParam('itemCount', 3);
    $params['category_id'] = $this->_getParam('category_id',0);

    //GET STORE SETTING
    $params['is_store'] = $this->_getParam('is_store', 0);  
    
    $this->view->topRatedPages = Engine_Api::_()->getDbTable('pages', 'sitepage')->getListings('Top Rated', $params, null, null, array('page_id', 'photo_id','title', 'body', 'page_url', 'owner_id', 'rating'));

		//DON'T RENDER IF RESULTS COUNT IS ZERO
    if ((Count($this->view->topRatedPages) <= 0)) {
      return $this->setNoRender();
    }
  }

}
?>
