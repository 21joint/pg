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

class Sitepagereview_Widget_ReviewOfTheDayController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

		//GET REVIEW OF THE DAY
    $this->view->reviewOfDay = Engine_Api::_()->getDbtable('reviews', 'sitepagereview')->reviewOfDay();
  
		//DON'T RENDER IF NO REVIEW OF THE DAY IS EXIST
    if (empty($this->view->reviewOfDay)) {
      return $this->setNoRender();
    }
  }
}
?>