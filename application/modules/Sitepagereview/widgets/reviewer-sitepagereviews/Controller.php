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
class Sitepagereview_Widget_ReviewerSitepagereviewsController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
		//TOTAL ITEMS IN WIDGET
		$itemCount = $this->_getParam('itemCount', 3);
    $category_id = $this->_getParam('category_id',0);
		//GET RESULTS
		$this->view->paginator = Engine_Api::_()->getDbtable('reviews', 'sitepagereview')->topReviewers($itemCount,$category_id);

		//DON'T RENDER IF NO DATA
    if (Count($this->view->paginator) <= 0) {
      return $this->setNoRender();
    }
  }
}
?>