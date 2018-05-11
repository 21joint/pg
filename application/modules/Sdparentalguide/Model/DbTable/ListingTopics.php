<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_DbTable_ListingTopics extends Engine_Db_Table
{
    protected $_rowClass = "Sdparentalguide_Model_ListingTopic";
    protected $_name = 'gg_listing_topics';
    
    public function hasListingTopic($topic_id,$listing_id){
        $select = $this->select()->where("topic_id = ?",$topic_id)->where("listing_id = ?",$listing_id);
        $row = $this->fetchRow($select);
        if(empty($row)){
            return false;
        }
        return true;
    }
    public function addListingTopic($topic_id,$listing_id){
        if($this->hasListingTopic($topic_id, $listing_id)){
            return;
        }
        $this->createListingTopic($topic_id,$listing_id);
    }
    public function createListingTopic($topic_id,$listing_id){
        $row = $this->createRow();
        $row->setFromArray(array(
            'topic_id' => $topic_id,
            'listing_id' => $listing_id
        ));
        $row->save();
    }
} 




