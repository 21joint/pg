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

class Sitepagereview_Widget_ReviewTabsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

		//GET PARAMETERS FOR FETCH DATA
    $this->view->category_id = $category_id = $this->_getParam('category_id',0);
    $this->view->is_ajax = $is_ajax = $this->_getParam('isajax', '');
		$this->view->tabName = $tabName = $this->_getParam('tabName', 'recent');
		$this->view->itemCount = $itemCount = $this->_getParam('itemCount', 3);
		$this->view->popularity = $popularity = $this->_getParam('popularity', 'view_count');
		$default_visibility = array('recent','popular','reviewer');
		$this->view->visibility = $this->_getParam('visibility', $default_visibility);
		if($tabName == 'popular') {

			//GET RESULTS
			$params = array();
			$params['orderby'] = "$popularity DESC";
      $params['category_id'] = $category_id;
			$params['zero_count'] = $popularity;
			$params['limit'] = $itemCount;
			$params['page_validation'] = 1;
			$this->view->paginator = Engine_Api::_()->getDbtable('reviews', 'sitepagereview')->reviewRatingData($params);

		}
		elseif($tabName == 'reviewer') {

			//GET RESULTS
			$this->view->paginator = Engine_Api::_()->getDbtable('reviews', 'sitepagereview')->topReviewers($itemCount,$category_id);
		}
		else {

			//GET RESULTS
			$params = array();
      $params['category_id'] = $category_id;
			$params['limit'] = $itemCount;
			$params['page_validation'] = 1;
			$this->view->paginator = Engine_Api::_()->getDbtable('reviews', 'sitepagereview')->reviewRatingData($params);
		}
	}
}
?>