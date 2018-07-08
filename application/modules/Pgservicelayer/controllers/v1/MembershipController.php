<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_MembershipController extends Pgservicelayer_Controller_Action_Api
{
    public function init(){
        $timezone = Engine_Api::_()->getApi('settings', 'core')->core_locale_timezone;
        $viewer   = Engine_Api::_()->user()->getViewer();
        $defaultLocale = $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en_US');
        $defaultLocaleObj = new Zend_Locale($defaultLocale);
        Zend_Registry::set('LocaleDefault', $defaultLocaleObj);

        if ($viewer->getIdentity()) {
            $timezone = $viewer->timezone;
        }
        Zend_Registry::set('timezone', $timezone);
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
        Engine_Api::_()->getApi('Core', 'siteapi')->setTranslate();
        Engine_Api::_()->getApi('Core', 'siteapi')->setLocal();
        
        $this->requireSubject();
    }
        
    public function indexAction(){
        $this->validateRequestMethod("POST");
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity() && $this->isApiRequest()){
            $this->respondWithError('unauthorized');
        }
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity()){
            $this->respondWithError('no_record');
        }
        if(!method_exists($subject, 'membership')){
            $this->respondWithError('friendship_disabled');
        }
        
        $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
        $db->beginTransaction();

        try {
            $proxyObject = Engine_Api::_()->getDbtable('membership', 'user')->membership($subject);
            $proxyObject
                ->addMember($viewer)
                ->setUserApproved($viewer);
            
            // Follow
            if($subject->getType() == "user"){
                $this->followUser($subject,$viewer);
            }else{
                $this->followSeaoCore($subject,$viewer);
            }
            $db->commit();
            $this->successResponseNoContent('no_content');
        }catch(Exception $e){
            $db->rollBack();
            $this->respondWithServerError($e);
        }
    }
    
    public function followUser($user,$viewer){
        $user->membership()
            ->addMember($viewer)
            ->setUserApproved($viewer);
        if( !$viewer->membership()->isUserApprovalRequired() && !$viewer->membership()->isReciprocal() ) {
        // if one way friendship and verification not required

        // Add activity
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($viewer, $user, 'friends_follow', '{item:$subject} is now following {item:$object}.');

        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
            ->addNotification($user, $viewer, $viewer, 'friend_follow');

        $message = Zend_Registry::get('Zend_Translate')->_("You are now following this member.");
        
      } else if( !$viewer->membership()->isUserApprovalRequired() && $viewer->membership()->isReciprocal() ){
        // if two way friendship and verification not required

        // Add activity
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($user, $viewer, 'friends', '{item:$object} is now friends with {item:$subject}.');
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($viewer, $user, 'friends', '{item:$object} is now friends with {item:$subject}.');

        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
            ->addNotification($user, $viewer, $user, 'friend_accepted');
        
        $message = Zend_Registry::get('Zend_Translate')->_("You are now friends with this member.");

      } else if( !$user->membership()->isReciprocal() ) {
        // if one way friendship and verification required

        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
            ->addNotification($user, $viewer, $user, 'friend_follow_request');
        
        $message = Zend_Registry::get('Zend_Translate')->_("Your friend request has been sent.");
        
      } else if( $user->membership()->isReciprocal() ) {
        // if two way friendship and verification required

        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
            ->addNotification($user, $viewer, $user, 'friend_request');
        
        $message = Zend_Registry::get('Zend_Translate')->_("Your friend request has been sent.");
      }
    }
    
    public function followSeaoCore($resource,$viewer){
        $followTable = Engine_Api::_()->getDbTable('follows', 'seaocore');
        $follow_name = $followTable->info('name');
        $resource_type = $this->getParam("contentType");
        $resource_type = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($resource_type);
        $resource_id = $this->getParam("contentID");
        $viewer_id = $viewer->getIdentity();
        if ($resource_type == 'sitepage_page') {
            $manageAdminsIds = Engine_Api::_()->getDbtable('manageadmins', 'sitepage')->getManageAdmin($resource_id, $viewer_id);
            $tableName = 'engine4_sitepage_membership';
            $ExtensionModuleName = 'sitepagemember';
        } else	if ($resource_type == 'sitebusiness_business') {
            $manageAdminsIds = Engine_Api::_()->getDbtable('manageadmins', 'sitebusiness')->getManageAdmin($resource_id, $viewer_id);
        } else	if ($resource_type == 'sitestore_store') {
            $manageAdminsIds = Engine_Api::_()->getDbtable('manageadmins', 'sitestore')->getManageAdmin($resource_id, $viewer_id);
        }else	if ($resource_type == 'sitegroup_group') {
            $manageAdminsIds = Engine_Api::_()->getDbtable('manageadmins', 'sitegroup')->getManageAdmin($resource_id, $viewer_id);
        }
        
        $follow_id_temp = $resource->follows()->isFollow($viewer);
        if (empty($follow_id_temp)) {
            $follow_id = $followTable->addFollow($resource, $viewer);
            if($viewer_id != $resource->getOwner()->getIdentity()) {
                if ($resource_type == 'sitepage_page' || $resource_type == 'sitebusiness_business' || $resource_type == 'sitegroup_group' || $resource_type == 'sitestore_store') {
                    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled($ExtensionModuleName)) {
                        $object_type = $resource->getType();
                        $object_id = $resource->getIdentity();
                        $subject_type = $viewer->getType();
                        $subject_id = $viewer->getIdentity();
                        $notificationType = 'follow_' . $resource_type;
                        $notificationcreated = '%"notificationfollow":"1"%';
                        $notificationFriendCreated = '%"notificationfollow":"2"%';
                        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

                        $friendId = Engine_Api::_()->user()->getViewer()->membership()->getMembershipsOfIds();

                        if($friendId) {
                            $db->query("INSERT IGNORE INTO `engine4_activity_notifications` (`user_id`, `subject_type`, `subject_id`, `object_type`, `object_id`, `type`,`params`, `date`) SELECT `".$tableName."`.`user_id` as `user_id` ,	'" . $subject_type . "' as `subject_type`, " . $subject_id . " as `subject_id`, '" . $object_type . "' as `object_type`, " . $object_id . " as `object_id`, '" . $notificationType . "' as `type`, 'null' as `params`, '" . date('Y-m-d H:i:s') . "' as ` date `  FROM `".$tableName."` WHERE ($tableName.page_id = " . $resource->page_id . ") AND ($tableName.user_id <> " . $viewer->getIdentity() . ") AND ($tableName.notification = 1) AND ($tableName.action_notification LIKE '".$notificationcreated."' or ($tableName.action_notification LIKE '".$notificationFriendCreated."' and ($tableName .user_id IN (".join(",",$friendId)."))))");
                        } else {
                            $db->query("INSERT IGNORE INTO `engine4_activity_notifications` (`user_id`, `subject_type`, `subject_id`, `object_type`, `object_id`, `type`,`params`, `date`) SELECT `".$tableName."`.`user_id` as `user_id` ,	'" . $subject_type . "' as `subject_type`, " . $subject_id . " as `subject_id`, '" . $object_type . "' as `object_type`, " . $object_id . " as `object_id`, '" . $notificationType . "' as `type`, 'null' as `params`, '" . date('Y-m-d H:i:s') . "' as ` date `  FROM `".$tableName."` WHERE ($tableName.page_id = " . $resource->page_id . ") AND ($tableName.user_id <> " . $viewer->getIdentity() . ") AND ($tableName.notification = 1) AND ($tableName.action_notification LIKE '".$notificationcreated."')");
                        }
                    } else {
                        foreach ($manageAdminsIds as $value) {
                            $action_notification = unserialize($value['action_notification']);
                            $user_subject = Engine_Api::_()->user()->getUser($value['user_id']);
                            //ADD NOTIFICATION
                            if (!empty($value['notification']) && in_array('follow', $action_notification)) {
                                Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($user_subject, $viewer, $resource, 'follow_' . $resource_type, array());
                            }
                        }
                    }
                } else if($resource_type == 'siteevent_event') {
                    $actionTable = Engine_Api::_()->getDbtable('actions', 'seaocore');
                    $action = $actionTable->addActivity($viewer, $resource, 'follow_siteevent_event','', array('owner' => $resource->getOwner()->getGuid()));
                    if ($action != null) {
                        $actionTable->attachActivity($action, $resource);

                        //START NOTIFICATION AND EMAIL WORK
                        Engine_Api::_()->siteevent()->sendNotificationEmail($resource, $action, 'follow_siteevent_event', 'SITEEVENT_FOLLOW_CREATENOTIFICATION_EMAIL', null, null, 'follow', $viewer);
                        //END NOTIFICATION AND EMAIL WORK
                    }
                }
                elseif($resource_type == 'sitereview_wishlist') {
                        //ADD NOTIFICATION
                    Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($resource->getOwner(), $viewer, $resource, 'follow_' . $resource_type, array());
                }

                if($resource_type != 'siteevent_event') {
                        //ADD ACTIVITY FEED
                    $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
                    if ($resource_type != 'sitepage_page' || $resource_type != 'sitebusiness_business' || $resource_type != 'sitegroup_group'  || $resource_type != 'sitestore_store') {
                        $action = $activityApi->addActivity($viewer, $resource, 'follow_' . $resource_type, '', array(
                                'owner' => $resource->getOwner()->getGuid(),
                        ));
                    } else {
                        $action = $activityApi->addActivity($viewer, $resource, 'follow_' . $resource_type);
                    }

                    if(!empty($action)){
                        $activityApi->attachActivity($action, $resource);
                    }                    
                }
            }
        }else{
            $followTable->removeFollow($resource, $viewer);
            
            if($viewer_id != $resource->getOwner()->getIdentity()) {
                if ($resource_type == 'sitepage_page' || $resource_type == 'sitebusiness_business' || $resource_type == 'sitegroup_group' || $resource_type == 'sitestore_store') {
                    Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('object_type = ?' => "$resource_type", 'object_id = ?' => $resource_id, 'subject_id = ?' => $resource_id, 'subject_type = ?' => "$resource_type", 'user_id = ?' => $viewer_id));
                    foreach ($manageAdminsIds as $value) {
                        $user_subject = Engine_Api::_()->user()->getUser($value['user_id']);
                        //DELETE NOTIFICATION
                        $notification = Engine_Api::_()->getDbtable('notifications', 'activity')->getNotificationByObjectAndType($user_subject, $resource, 'follow_' . $resource_type);
                        if($notification) {
                                $notification->delete();
                        }
                    }
                }elseif($resource_type == 'sitereview_wishlist') {
                    //DELETE NOTIFICATION
                    $notification = Engine_Api::_()->getDbtable('notifications', 'activity')->getNotificationByObjectAndType($resource->getOwner(), $resource, 'follow_' . $resource_type);
                    if($notification) {
                        $notification->delete();
                    }
                }

                //DELETE ACTIVITY FEED
                $action_id = Engine_Api::_()->getDbtable('actions', 'activity')
                            ->select()
                            ->from('engine4_activity_actions', 'action_id')
                            ->where('type = ?', "follow_$resource_type")
                            ->where('subject_id = ?', $viewer_id)
                            ->where('subject_type = ?', 'user')
                            ->where('object_type = ?', $resource_type)
                            ->where('object_id = ?', $resource->getIdentity())    
                            ->query()
                            ->fetchColumn();

                if(!empty($action_id)) {
                    $activity = Engine_Api::_()->getItem('activity_action', $action_id);
                    if(!empty($activity)) {
                        $activity->delete();
                    }
                }	
            }
        }
    }
}
