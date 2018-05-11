<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Plugin_Core {

  //DELETE USERS BELONGINGS BEFORE THAT USER DELETION
  public function onUserDeleteBefore($event) {
    $payload = $event->getPayload();
    if ($payload instanceof User_Model_User) {
      //DELETE OFFERS
      $sitepageofferTable = Engine_Api::_()->getDbtable('offers', 'sitepageoffer');
      $sitepageofferSelect = $sitepageofferTable->select()->where('owner_id = ?', $payload->getIdentity());
      foreach ($sitepageofferTable->fetchAll($sitepageofferSelect) as $sitepageoffer) {
        Engine_Api::_()->sitepageoffer()->deleteContent($sitepageoffer->offer_id);
      }
    }
  }

}
?>