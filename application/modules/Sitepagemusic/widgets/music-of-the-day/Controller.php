<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Widget_MusicOfTheDayController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->musicOfDay = $musicOfDay = Engine_Api::_()->getDbtable('playlists', 'sitepagemusic')->musicOfDay();
    if (empty($musicOfDay)) {
      return $this->setNoRender();
    }
  }

}
?>