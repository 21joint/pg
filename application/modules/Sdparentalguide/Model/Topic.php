<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_Topic extends Core_Model_Item_Abstract
{
  public function getTitle(){
      return $this->name;
  }
  public function getListingType(){
      return Engine_Api::_()->getItem('sitereview_listingtype', $this->listingtype_id);
  }
  public function getCategory(){
      return Engine_Api::_()->getItem('sitereview_category', $this->category_id);
  }
  public function getSubCategory(){
      return Engine_Api::_()->getItem('sitereview_category', $this->subcategory_id);
  }
  
  public function getAllListings(){
      if(empty($this->listingtype_id)){
          return;
      }
      $table = Engine_Api::_()->getDbTable("listings","sitereview");
      $select = $table->select()->where('listingtype_id = ?',$this->listingtype_id);
      if(!empty($this->category_id)){
          $select->where("category_id = ?",$this->category_id);
      }
      if(!empty($this->subcategory_id)){
          $select->where("subcategory_id = ?",$this->subcategory_id);
      }
      
      return $table->fetchAll($select);
  }

  
} 




