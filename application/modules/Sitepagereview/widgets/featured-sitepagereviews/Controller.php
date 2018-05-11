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
class Sitepagereview_Widget_FeaturedSitepagereviewsController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  { 
		//GET PARAMETERS FOR FETCH DATA
		$this->view->itemCount = $itemCount = $this->_getParam('itemCount', 3);
		$this->view->is_ajax = $is_ajax = $this->_getParam('isajax', '');
		$this->view->page = $page = $this->_getParam('page');

		//FETCH REVIEW DATA
    $params = array();
		$params['limit'] = $itemCount;
		$params['page_validation'] = 1;
		$params['featured'] = 1;
    $params['category_id'] = $this->_getParam('category_id',0);
    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('reviews', 'sitepagereview')->reviewRatingData($params);
    $paginator->setItemCountPerPage($itemCount);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);

		//CALCULATE TOTAL PAGES
		$total_items = $paginator->getTotalItemCount(); 
		$this->view->total_page =  $total_items/$itemCount;
		if($this->view->total_page > (int)$this->view->total_page) {
			$this->view->total_page += 1;
		}

		//DON'T RENDER IF NO DATA FOUND
    if ($total_items <= 0) {
      return $this->setNoRender();
    }
  }
}
?>