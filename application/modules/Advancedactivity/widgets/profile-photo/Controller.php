<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedactivity
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedactivity_Widget_ProfilePhotoController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $getCustomBlockSettings = Engine_Api::_()->advancedactivity()->getCustomBlockSettings(array('advancedactivity.profile-photo'));
    if (empty($getCustomBlockSettings)) {
      return $this->setNoRender();
    }

    $this->view->baseURL = Zend_Controller_Front::getInstance()->getBaseUrl();
  }

}

?>