<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_Model_DbTable_Membership extends Core_Model_DbTable_Membership
{
  protected $_type = 'user';
  protected $_name = 'user_membership';
  protected $_userApprovalRequired = null;

  public function isReciprocal()
  {
    return (bool) Engine_Api::_()->getApi('settings', 'core')
        ->getSetting('user.friends.direction', 1);
  }
  
  public function isResourceApprovalRequired(Core_Model_Item_Abstract $resource)
  {
    return true;
  }

  public function membership(Core_Model_Item_Abstract $resource){
    $this->_type = $resource->getType();  
    $table = $resource->membership()->getReceiver();
    $this->_name = $table->info("name");
    return new Engine_ProxyObject($resource, $this);
  }
  
  public function setParentUserApproved(Core_Model_Item_Abstract $resource, User_Model_User $user)
  {
    $this->_isSupportedType($resource);
    $row = $this->getRow($resource, $user);

    if( null === $row )
    {
      throw new Core_Model_Exception("Membership does not exist");
    }

    if( !$row->user_approved )
    {
      $row->user_approved = true;
      if( $row->resource_approved && $row->user_approved )
      {
        $row->active = true;
        if( isset($resource->member_count) )
        {
          $resource->member_count++;
          $resource->save();
        }
      } 
      $this->_checkActive($resource, $user);
      $row->save();
    }

    return $this;
  }
  
  public function setUserApproved(Core_Model_Item_Abstract $resource, User_Model_User $user)
  {
    $this->setParentUserApproved($resource, $user);

    if( $this->isReciprocal() ) {
      parent::setResourceApproved($user, $resource);
    }

    if( !$this->isUserApprovalRequired($resource, $user) ) {
      $this->setParentResourceApproved($resource, $user);

      if( $this->isReciprocal() ) {
        $this->setParentUserApproved($user, $resource);
      }
    }
    
    return $this;
  }
  
  public function setParentResourceApproved(Core_Model_Item_Abstract $resource, User_Model_User $user)
  {
    $this->_isSupportedType($resource);
    $row = $this->getRow($resource, $user);

    if( null === $row )
    {
      throw new Core_Model_Exception("Membership does not exist");
    }

    if( !$row->resource_approved )
    {
      $row->resource_approved = true;
      if( $row->resource_approved && $row->user_approved )
      {
        $row->active = true;
        if( isset($resource->member_count) )
        {
          $resource->member_count++;
          $resource->save();
        }
      }
      $this->_checkActive($resource, $user);
      $row->save();
    }

    return $this;
  }
  
  public function setResourceApproved(Core_Model_Item_Abstract $resource, User_Model_User $user)
  {
    $this->setParentResourceApproved($resource, $user);

    if( $this->isReciprocal() ) {
      $this->setParentUserApproved($user, $resource);
    }

    if( !$this->isUserApprovalRequired($resource, $user) ) {
      $this->setParentUserApproved($resource, $user);
      
      if( $this->isReciprocal() ) {
        $this->setParentResourceApproved($user, $resource);
      }
    }

    return $this;
  }
  
  public function isUserApprovalRequired(Core_Model_Item_Abstract $resource, User_Model_User $user)
  {
    if($this->_userApprovalRequired !== null){
        return $this->_userApprovalRequired;
    }
    if(!isset($resource->view_privacy)){
        $this->_userApprovalRequired = false;
        return false;
    }
    if($resource->view_privacy == "owner"){
        $this->_userApprovalRequired = true;
        return true;
    }
    if($resource->view_privacy == "member" && $resource->membership()->isMember($user)){
        $this->_userApprovalRequired = false;
        return false;
    }
    $this->_userApprovalRequired = true;
    if($resource->view_privacy == "network"){
        try{
            $owner = $resource;
            if($resource->getType() != "user"){
                $owner = $resource->getOwner();
            }
            if($owner->getType() != "user"){
                return true;
            }
            $ownerNetworkIds = $this->getUserNetworkIds($owner);
            $userNetworkIds = $this->getUserNetworkIds($user);
            if(empty($ownerNetworkIds) || empty($userNetworkIds)){
                return true;
            }
            if(array_intersect($ownerNetworkIds, $userNetworkIds)){
                $this->_userApprovalRequired = false;
                return false;
            }
            return true;
        } catch (Exception $ex) {
            //Silent
            return true;
        }
    }
    if($resource->view_privacy == "registered" || $resource->view_privacy == "everyone"){
        $this->_userApprovalRequired = false;
        return false;
    }
    
    return true;
  }
  
  public function getUserNetworkIds($user){
      $mTable = Engine_Api::_()->getDbTable("membership","network");
      return $mTable->select()->from($mTable->info("name"),array('resource_id'))
              ->where("user_id = ?",$user->getIdentity())
              ->where("active = ?",1)
              ->query()->fetchAll(Zend_Db::FETCH_COLUMN)
              ;
  }
  
  public function addMember(Core_Model_Item_Abstract $resource, User_Model_User $user)
  {
    parent::addMember($resource, $user);
  
    if( $this->isReciprocal() ) {
      parent::addMember($user, $resource);
    }
    
//    parent::setResourceApproved($resource, $user);
//
//    if( $this->isReciprocal() ) {
//      parent::setUserApproved($user, $resource);
//    }

    return $this;
  }

  public function removeMember(Core_Model_Item_Abstract $resource, User_Model_User $user)
  {
    parent::removeMember($resource, $user);

    if( $this->isReciprocal() ) {
      parent::removeMember($user, $resource);
    }
    
    return $this;
  }
  
  public function removeAllUserFriendship(User_Model_User $user)
  {
    // first get all cases where user_id == $user->getIdentity
    $select = $this->getTable()->select()
      ->where('user_id = ?', $user->getIdentity());
    
    $friendships = $this->getTable()->fetchAll($select);
    foreach( $friendships as $friendship ) {
      // if active == 1 get the user corresponding to resource_id and take away the member_count by 1
      if($friendship->active){
        $friend = Engine_Api::_()->getItem('user', $friendship->resource_id);
        if($friend && !empty($friend->member_count)){
          $friend->member_count--;
          $friend->save();
        }
      }
      $friendship->delete();
    }

    // get all cases where resource_id == $user->getIdentity
    // remove all   
    $this->getTable()->delete(array(
      'resource_id = ?' => $user->getIdentity()
    ));
  }
}
