<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageinvite
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageinvite_Api_Core extends Core_Api_Abstract {

  public function sendSuggestion($reciever_object, $sender_object, $page_id) {
    $suggTable = Engine_Api::_()->getItemTable('suggestion');
    $sugg = $suggTable->createRow();
    $sugg->owner_id = $reciever_object->getIdentity();
    $sugg->sender_id = $sender_object->getIdentity();
    $sugg->entity = 'sitepage';
    $sugg->entity_id = $page_id;
    $sugg->save();

    // Add in the notification table for show in the "update".
    // $reciever_object : Object which are geting suggestion.
    // $sender_obj : Object which are sending suggestion.
    // $sugg : Object from which table we'll link.
    // suggestion_sitepage :notification type.
    Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($reciever_object, $sender_object, $sugg, 'page_suggestion');
  }
}