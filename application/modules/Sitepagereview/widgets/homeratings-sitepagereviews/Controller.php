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
class Sitepagereview_Widget_HomeratingsSitepagereviewsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    //TOTAL REVIEWS BELONGS TO  PAGE
    $this->view->totalReviews = Engine_Api::_()->getDbTable('reviews', 'sitepagereview')->totalReviews();
    $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
    $this->view->content_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $sitepage->page_id, $layout);
  }

}
?>