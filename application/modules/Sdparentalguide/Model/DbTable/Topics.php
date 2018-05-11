<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_DbTable_Topics extends Engine_Db_Table
{
    protected $_rowClass = "Sdparentalguide_Model_Topic";
    protected $_name = 'gg_topics';
    
    public function checkTopic($topicTitle){
        if(empty($topicTitle)){
            return false;
        }
        
        $topic = $this->fetchRow($this->select()->where("name = ?",$topicTitle));
        if(empty($topic)){
            return false;
        }
        return $topic;
    }
    public function createTagTopic($tagText){
        $viewer = Engine_Api::_()->user()->getViewer();            
        $topic = $this->createRow();
        $topic->name = $tagText;
        $topic->description = $tagText;
        $topic->owner_id = $viewer->getIdentity();
        $topic->search = 0;
        $topic->approved = 0;
        $topic->custom = 0;
        $topic->save();
        return $topic;
    }
    public function checkListingTopic($listingTypeId,$categoryId = 0,$subCategoryId = 0){
        $select = $this->select()
                ->where('listingtype_id = ?',$listingTypeId)
                ->where('category_id = ?',(int)$categoryId)
                ->where('subcategory_id = ?',(int)$subCategoryId)
                ->where('custom = ?',0);
        $topic = $this->fetchRow($select);
        if(empty($topic)){
            return false;
        }
        return true;
    }
    public function createListingTopic($listingTypeId,$categoryId = 0,$subCategoryId = 0){
        if($this->checkListingTopic($listingTypeId, $categoryId, $subCategoryId)){
            return;
        }
        $listingType = Engine_Api::_()->getItem("sitereview_listingtype",$listingTypeId);
        if(empty($listingType)){
            return;
        }
        $topicTitle = $listingType->getTitle();
        $category = Engine_Api::_()->getItem("sitereview_category",$categoryId);
        if(!empty($category)){
            $topicTitle .= " &#187; ".$category->getTitle();
        }
        $subcategory = Engine_Api::_()->getItem("sitereview_category",$subCategoryId);
        if(!empty($subcategory)){
            $topicTitle .= " &#187; ".$subcategory->getTitle();
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        $topic = $this->createRow();
        $topic->setFromArray(array(
            "name" => $topicTitle,
            "description" => $topicTitle,
            "owner_id" => $viewer->getIdentity(),
            'listingtype_id' => $listingTypeId,
            'category_id' => $categoryId,
            'subcategory_id' => $subCategoryId,
            'custom' => 0
        ));
        $topic->save();
    }
} 




