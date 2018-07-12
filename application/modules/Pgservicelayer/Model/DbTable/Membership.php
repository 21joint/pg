<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_Model_DbTable_Membership extends User_Model_DbTable_Membership
{
  protected $_type = 'user';
  protected $_name = 'user_membership';


  public function membership(Core_Model_Item_Abstract $resource){
    $this->_type = $resource->getType();  
    $table = $resource->membership()->getTable();
    $this->_name = $table->info("name");
    return new Engine_ProxyObject($resource, $this);
  }
}
