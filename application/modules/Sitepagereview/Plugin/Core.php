<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Plugin_Core {

  //DELETE USERS BELONGINGS BEFORE THAT USER DELETION
  public function onUserDeleteBefore($event) {
    $payload = $event->getPayload();
    if ($payload instanceof User_Model_User) {

      $sitepagereviewTable = Engine_Api::_()->getDbtable('reviews', 'sitepagereview');
      $sitepagereviewSelect = $sitepagereviewTable->select()->where('owner_id = ?', $payload->getIdentity());

      foreach ($sitepagereviewTable->fetchAll($sitepagereviewSelect) as $sitepagereview) {
				Engine_Api::_()->sitepagereview()->deleteContent($sitepagereview->review_id);
      }
    }
  }

}
?>