<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_Plugin_Core {

  //DELETE USERS BELONGINGS BEFORE USER DELETION
  public function onUserDeleteBefore($event) {
    $payload = $event->getPayload();

    if ($payload instanceof User_Model_User) {

      //DELETE BADGE REQUESTS
      $sitepageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
      $sitepageSelect = $sitepageTable->select()->where('owner_id = ?', $payload->getIdentity());

			$tableBadgeRequest = Engine_Api::_()->getItemTable('sitepagebadge_badgerequest');

      foreach ($sitepageTable->fetchAll($sitepageSelect) as $sitepage) {
        if (!empty($sitepage->page_id)) {

          //DELETE BADGE REQUESTS CORROSPONDING TO THAT PAGE ID
					$tableBadgeRequest->delete(array('page_id = ?' => $sitepage->page_id));

        }
      }
    }
  }

}
?>