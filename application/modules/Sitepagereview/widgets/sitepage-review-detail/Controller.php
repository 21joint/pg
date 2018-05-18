<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Widget_SitepageReviewDetailController extends Seaocore_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->sitepagereview = $sitepagereview = Engine_Api::_()->getItem('sitepagereview_review', Zend_Controller_Front::getInstance()->getRequest()->getParam('review_id'));
    if (empty($sitepagereview)) {
      return $this->setNoRender();
    }
  }

}
?>