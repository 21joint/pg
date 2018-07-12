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
        parent::init();
        
        $this->requireSubject();
    }
    
    public function indexAction(){
        try{
            $method = strtolower($this->getRequest()->getMethod());
            if($method == 'get'){
                $this->getAction();
            }
            else if($method == 'post'){
                $this->postAction();
            }
            else if($method == 'put' || $method == 'patch'){
                $this->respondWithError('invalid_method');
            }
            else if($method == 'delete'){
                $this->deleteAction();
            }
            else{
                $this->respondWithError('invalid_method');
            }
        } catch (Exception $ex) {
            $this->respondWithServerError($ex);
        }
    }
    
    public function getAction(){
        $this->validateRequestMethod("GET");
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
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",10);
        if($subject->getType() == "user"){
            $table = $subject->membership()->getReceiver();
            $tableName = $table->info("name");
            $select = $subject->membership()->getMembersSelect();
            $select->from($tableName,array("*",new Zend_Db_Expr("IF(gg_guid IS NULL,CONCAT(resource_id,'_user','-',user_id,'_user'),gg_guid) as gg_guid")));
        }else{
            $table = Engine_Api::_()->getDbtable('follows', 'seaocore');
            $select = $table->select()
                    ->where("resource_type = ?",$subject->getType())
                    ->where("resource_id = ?",$subject->getIdentity());
        }
        $select->where("gg_deleted = ?",0);
        
        $followerID = $this->getParam("followerID");
        if(!empty($followerID) && $subject->getType() == "user"){
            $select->where("user_id = ?",$followerID);
        }
        if(!empty($followerID) && $subject->getType() != "user"){
            $select->where("poster_id = ?",$followerID);
        }
        
        $orderByDirection = $this->getParam("orderByDirection","descending");
        $orderBy = $this->getParam("orderBy","createdDateTime");
        $orderByDirection = ($orderByDirection == "descending")?"DESC":"ASC";
        if($orderBy == "createdDateTime"){
            $select->order("creation_date $orderByDirection");
        }else if($subject->getType() == "user"){
            $select->order("gg_guid $orderByDirection");
        }else{
            $select->order("follow_id $orderByDirection");
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        
        $response['ResultCount'] = $paginator->getTotalItemCount();
        $response['Results'] = array();
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        foreach($paginator as $row){
            $response['Results'][] = $responseApi->getFollowData($subject,$row);
        }
        $this->respondWithSuccess($response);
    }
        
    public function postAction(){
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
        
        if($subject->isSelf($viewer)){
            $this->respondWithError('unauthorized',$this->translate("You cannot follow yourself."));
        }        
        
        $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
        $db->beginTransaction();

        try {
            // Follow
            if($subject->getType() == "user"){
                $proxyObject = Engine_Api::_()->getDbTable("membership","pgservicelayer")->membership($subject);
                if($proxyObject->isMember($viewer)){
                    $this->removeUserFollow($subject,$viewer);
                }else{
                    $this->followUser($subject,$viewer);
                }
                
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
        $proxyObject = Engine_Api::_()->getDbTable("membership","pgservicelayer")->membership($user);
        $isUserApprovalRequired = $proxyObject->isUserApprovalRequired($viewer);
        $proxyObject
            ->addMember($viewer)
            ->setUserApproved($viewer);
        $row = $proxyObject->getRow($viewer);
        if(!empty($row)){
            $row->gg_guid = $user->getGuid()."-".$viewer->getGuid();
            $row->creation_date = date("Y-m-d H:i:s");
            $row->save();
        }        
        if( !$isUserApprovalRequired && !$viewer->membership()->isReciprocal() ) {
        // if one way friendship and verification not required

        // Add activity
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($viewer, $user, 'friends_follow', '{item:$subject} is now following {item:$object}.');

        // Add notification
        Engine_Api::_()->getApi('Siteapi_Core', 'activity')
            ->addNotification($user, $viewer, $viewer, 'friend_follow');
        
        $user->gg_followers_count++;
        $user->save();
        
        $viewer->gg_following_count++;
        $viewer->save();

      } else if( !$isUserApprovalRequired && $viewer->membership()->isReciprocal() ){
        // if two way friendship and verification not required

        // Add activity
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($user, $viewer, 'friends', '{item:$object} is now friends with {item:$subject}.');
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($viewer, $user, 'friends', '{item:$object} is now friends with {item:$subject}.');

        // Add notification
        Engine_Api::_()->getApi('Siteapi_Core', 'activity')
            ->addNotification($user, $viewer, $user, 'friend_accepted');

      } else if( !$user->membership()->isReciprocal() ) {
        // if one way friendship and verification required

        // Add notification
        Engine_Api::_()->getApi('Siteapi_Core', 'activity')
            ->addNotification($user, $viewer, $user, 'friend_follow_request');

      } else if( $user->membership()->isReciprocal() ) {
        // if two way friendship and verification required

        // Add notification
        Engine_Api::_()->getApi('Siteapi_Core', 'activity')
            ->addNotification($user, $viewer, $user, 'friend_request');
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
        
        $follow_id_temp = $followTable->isFollow($resource,$viewer);
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
                                Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($user_subject, $viewer, $resource, 'follow_' . $resource_type, array());
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
                    Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($resource->getOwner(), $viewer, $resource, 'follow_' . $resource_type, array());
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
    
    public function deleteAction(){
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
        
        $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
        $db->beginTransaction();

        try {
            // Follow
            if($subject->getType() == "user"){
                if($subject->isSelf($viewer)){
                    $this->respondWithError('unauthorized',$this->translate("You cannot unfollow yourself."));
                }
                $this->removeUserFollow($subject,$viewer);
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
    
    public function removeUserFollow($user,$viewer){
        if(!$user->membership()->isMember($viewer)){
            return;
        }
        $user->membership()->removeMember($viewer);
        
        $user->lists()->removeFriendFromLists($viewer);
        $viewer->lists()->removeFriendFromLists($user);

        // Set the requests as handled
        $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
          ->getNotificationBySubjectAndType($user, $viewer, 'friend_request');
        if( $notification ) {
          $notification->mitigated = true;
          $notification->read = 1;
          $notification->save();
        }
        $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
            ->getNotificationBySubjectAndType($viewer, $user, 'friend_follow_request');
        if( $notification ) {
          $notification->mitigated = true;
          $notification->read = 1;
          $notification->save();
        }
        
        $user->gg_followers_count--;
        $user->save();
        
        $viewer->gg_following_count--;
        $viewer->save();
    }
    
    public function confirmAction(){
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
        $user = $subject;
        $proxyObject = Engine_Api::_()->getDbTable("membership","pgservicelayer")->membership($viewer);
        $isUserApprovalRequired = Engine_Api::_()->getDbTable("membership","pgservicelayer")->isUserApprovalRequired($user,$viewer); //It will set whethere resource approval is required or not.
        if($proxyObject->isMember($viewer)){
            $this->respondWithError('unauthorized',$this->translate("You are already following."));
        }
        
        $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
        $db->beginTransaction();

        try {
          $proxyObject->setResourceApproved($user);

          // Add activity
          if( !$user->membership()->isReciprocal() ) {
            Engine_Api::_()->getDbtable('actions', 'activity')
                ->addActivity($user, $viewer, 'friends_follow', '{item:$subject} is now following {item:$object}.');
          } else {
            Engine_Api::_()->getDbtable('actions', 'activity')
              ->addActivity($user, $viewer, 'friends', '{item:$object} is now friends with {item:$subject}.');
            Engine_Api::_()->getDbtable('actions', 'activity')
              ->addActivity($viewer, $user, 'friends', '{item:$object} is now friends with {item:$subject}.');
          }

          // Add notification
          if( !$user->membership()->isReciprocal() ) {
            Engine_Api::_()->getApi('Siteapi_Core', 'activity')
              ->addNotification($user, $viewer, $user, 'friend_follow_accepted');
          } else {
            Engine_Api::_()->getApi('Siteapi_Core', 'activity')
              ->addNotification($user, $viewer, $user, 'friend_accepted');
          }

          // Set the requests as handled
          $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
              ->getNotificationBySubjectAndType($viewer, $user, 'friend_request');
          if( $notification ) {
            $notification->mitigated = true;
            $notification->read = 1;
            $notification->save();
          }
          $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
              ->getNotificationBySubjectAndType($viewer, $user, 'friend_follow_request');
          if( $notification ) {
            $notification->mitigated = true;
            $notification->read = 1;
            $notification->save();
          }
          
          $viewer->gg_followers_count++;
          $viewer->save();
        
          $user->gg_following_count++;
          $user->save();

          // Increment friends counter
          Engine_Api::_()->getDbtable('statistics', 'core')->increment('user.friendships');

          $db->commit();

          $this->successResponseNoContent('no_content');
        } catch( Exception $e ) {
            $db->rollBack();
            $this->respondWithServerError($e);
        }
    }
    public function rejectAction(){
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
        $user = $subject;
        $proxyObject = Engine_Api::_()->getDbTable("membership","pgservicelayer")->membership($viewer);
        $isUserApprovalRequired = Engine_Api::_()->getDbTable("membership","pgservicelayer")->isUserApprovalRequired($user,$viewer); //It will set whethere resource approval is required or not.
        if(!$proxyObject->isMember($user,false)){
            $this->respondWithError('unauthorized',$this->translate("You are not already following."));
        }
        
        // Process
        $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
        $db->beginTransaction();

        try {
          if ($viewer->membership()->isMember($user)) {
            $viewer->membership()->removeMember($user);
          }

          // Set the request as handled
          $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
            ->getNotificationBySubjectAndType($viewer, $user, 'friend_request');
          if( $notification ) {
            $notification->mitigated = true;
            $notification->read = 1;
            $notification->save();
          }
          $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
              ->getNotificationBySubjectAndType($viewer, $user, 'friend_follow_request');
          if( $notification ) {
            $notification->mitigated = true;
            $notification->read = 1;
            $notification->save();
          }

          $db->commit();

          $this->successResponseNoContent('no_content');
        } catch( Exception $e ) {
          $db->rollBack();
          $this->respondWithServerError($e);
        }
    }
}
