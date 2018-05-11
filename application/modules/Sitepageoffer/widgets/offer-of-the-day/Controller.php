<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Widget_OfferOfTheDayController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->offerOfDay = $offerOfDay = Engine_Api::_()->getDbtable('offers', 'sitepageoffer')->offerOfDay();
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    if (empty($offerOfDay)) {
      return $this->setNoRender();
    }
  }

}
?>