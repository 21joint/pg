<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedactivity
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Actions.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedactivity_Model_DbTable_Notifications extends Activity_Model_DbTable_Notifications {
    
    protected $_name = 'activity_notifications';

    public function addNotification(User_Model_User $user, Core_Model_Item_Abstract $subject,
          Core_Model_Item_Abstract $object, $type, array $params = null)
  {
       $notificationTable = Engine_Api::_()->getDbtable('notificationQueues', 'advancedactivity');
       $notification_id = $notificationTable->insert(array(
            'type' => $type,
            'user_id' => $user->getIdentity(),
            'subject_id' => $subject->getIdentity(),
            'subject_type' => $subject->getType(),
            'object_id' => $object->getIdentity(),
            'object_type' => $object->getType(),
            'date' => date('Y-m-d H:i:s'),
            'params' => $params
        ));
    }
}
