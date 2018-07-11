<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_Api_Search extends Core_Api_Search
{
  protected $_types;
  
  public function index(Core_Model_Item_Abstract $item)
  {
    // Check if not search allowed
    if( isset($item->search) && !$item->search )
    {
      return false;
    }

    // Get info
    $type = $item->getType();
    $id = $item->getIdentity();
    $title = substr(trim($item->getTitle()), 0, 255);
    $description = substr(trim($item->getDescription()), 0, 255);
    $keywords = substr(trim($item->getKeywords()), 0, 255);
    $hiddenText = substr(trim($item->getHiddenSearchData()), 0, 255);
    
    // Ignore if no title and no description
    if( !$title && !$description )
    {
      return false;
    }

    // Check if already indexed
    $table = Engine_Api::_()->getDbtable('search', 'core');
    $select = $table->select()
      ->where('type = ?', $type)
      ->where('id = ?', $id)
      ->limit(1);

    $row = $table->fetchRow($select);

    if( null === $row )
    {
      $row = $table->createRow();
      $row->type = $type;
      $row->id = $id;
      $row->creation_date = date("Y-m-d H:i:s");
    }
    
    $itemObject = $item;
    $topic_id = 0;
    if($type == "sitereview_listing"){
        $listingType = $itemObject->getListingType();
        if(!empty($listingType)){
            $topic_id = $listingType->gg_topic_id;
        }        
    }else if($type == "sdparentalguide_badge" || $type == "ggcommunity_question"){
        $topic_id = $itemObject->topic_id;
    }
    if(!empty($topic_id)){
        $row->topic_id = $topic_id;
    }

    $row->title = $title;
    $row->description = $description;
    $row->keywords = $keywords;
    $row->hidden = $hiddenText;
    $row->modified_date = date("Y-m-d H:i:s");
    $row->save();
  }
}