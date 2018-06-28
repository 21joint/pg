<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Pgservicelayer_Api_Response extends Sdparentalguide_Api_Core {
    public function getListingOverview(Sitereview_Model_Listing $sitereview){
        $tableOtherinfo = Engine_Api::_()->getDbTable('otherinfo', 'sitereview');
        $overview = $tableOtherinfo->getColumnValue($sitereview->getIdentity(), 'overview');
        $staticBaseUrl = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.static.baseurl', null);
        $serverHost = Engine_Api::_()->getApi('Core', 'siteapi')->getHost();
        $getDefaultStorageId = Engine_Api::_()->getDbtable('services', 'storage')->getDefaultServiceIdentity();
        $getDefaultStorageType = Engine_Api::_()->getDbtable('services', 'storage')->getService($getDefaultStorageId)->getType();

        $host = '';
        if ($getDefaultStorageType == 'local')
            $host = !empty($staticBaseUrl) ? $staticBaseUrl : $serverHost;
        if (!empty($overview)) {
            $overview = str_replace('src="/', 'src="' . $host . '/', $overview);
            $overview = str_replace('"', "'", $overview);
        }
        return $overview;
    }
    public function getListingStatus(Sitereview_Model_Listing $sitereview){
        if($sitereview->gg_deleted){
            return "Deleted";
        }
        
        if(!$sitereview->draft && $sitereview->approved){
            return "Published";
        }
        if($sitereview->draft){
            return "Draft";
        }
        if($sitereview->approved){
            return "Pending Approval";
        }
    }
    public function getListingCategorization(Sitereview_Model_Listing $sitereview){
        $listingType = $sitereview->getListingType();
        $categoryArray = array(
            'type' => $listingType->getTitle(),
            'typeID' => (string)$listingType->getIdentity(),
            'category' => '',
            'categoryID' => '',
            'subCategory' => '',
            'subCategoryID' => ''
        );
        $category = $sitereview->getCategory();
        if(!empty($category)){
            $categoryArray['category'] = $category->getTitle();
            $categoryArray['categoryID'] = (string)$category->getIdentity();
        }
        $subcategory = Engine_Api::_()->getItem('sitereview_category', $sitereview->subcategory_id);
        if(!empty($subcategory)){
            $categoryArray['subCategory'] = $subcategory->getTitle();
            $categoryArray['subCategoryID'] = (string)$subcategory->getIdentity();
        }
        return $categoryArray;
    }
    public function getListingTopic(Sitereview_Model_Listing $sitereview){
        $topicArray = array(
            'topicID' => '',
            'topicName' => ''
        );
        $listingType = $sitereview->getListingType();
        $topic = Engine_Api::_()->getItem("sdparentalguide_topic",$listingType->gg_topic_id);
        if(empty($topic)){
            return $topicArray;
        }
        $topicArray['topicID'] = $topic->getIdentity();
        $topicArray['topicName'] = $topic->getTitle();
    }
    public function getListingPhotos(\Sitereview_Model_Listing $listing) {
        $listingPhotos = parent::getListingPhotos($listing);
        if($listingPhotos->getTotalItemCount() <= 0){
            return array();
        }
        $listingPhotosArray = array();
        foreach($listingPhotos as $photo){
            $contentImages = $this->getContentImage($photo);
            $photoArray = array(
                'photoID' => (string)$photo->getIdentity(),
                'photoURL' => ''
            );
            $photoArray = array_merge($photoArray,$contentImages);
            $listingPhotosArray[] = $photoArray;
        }
        return $listingPhotosArray;
    }
    
    public function getReviewData(Sitereview_Model_Listing $sitereview){
        $view = Zend_Registry::get("Zend_View");
        $viewer = Engine_Api::_()->user()->getViewer();
        $user = $sitereview->getOwner();
        $contentImages = $this->getContentImage($sitereview);
        $listingRating = Engine_Api::_()->getDbTable("listingRatings","sdparentalguide")->getAvgListingRating($sitereview);
        $listingtype_id = $sitereview->listingtype_id;
        $sitereviewArray = array(
            'reviewID' => (string)$sitereview->getIdentity(),
            'title' => $sitereview->getTitle(),
            'summaryDescription' => $sitereview->getDescription(),
            'longDescription' => $this->getListingOverview($sitereview),
            'likesCount' => $sitereview->likes()->getLikeCount(),
            'commentsCount' => $sitereview->comments()->getCommentCount(),
            'publishedDateTime' => $view->locale()->toDateTime($sitereview->approved_date,array('format' => 'YYYY-MM-d HH:MM:ss')),
            'featured' => $sitereview->featured,
            'approved' => $sitereview->approved,
            'createdDateTime' => $view->locale()->toDateTime($sitereview->creation_date,array('format' => 'YYYY-MM-d HH:MM:ss')),
            'status' => (string)$this->getListingStatus($sitereview),
            'author' => $this->getUserData($user),
            'authorRating' => $sitereview->gg_author_product_rating,
            'coverPhoto' => $sitereviewArray,
            'reviewCategorization' => $this->getListingCategorization($sitereview),
            'reviewTopic' => $this->getListingTopic($sitereview),
            'privacySettings' => array(),
            'reviewPhotos' => $this->getListingPhotos($sitereview),
            'averageReviewRating' => sprintf("%.1f",$listingRating['review_rating']),
            'averageProductRating' => sprintf("%.1f",$listingRating['product_rating']),            
        );
        
        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
        foreach ($roles as $role) {
            if (1 == $auth->isAllowed($sitereview, $role, "view_listtype_$listingtype_id")) {
                $sitereviewArray['privacySettings']['authView'] = $role;
            }
            if (1 == $auth->isAllowed($sitereview, $role, "comment_listtype_$listingtype_id")) {
                $sitereviewArray['privacySettings']['authComment'] = $role;
            }
        }
        
        $roles_photo = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered');
        foreach ($roles_photo as $role) {
            if (1 == $auth->isAllowed($sitereview, $role, "topic_listtype_$listingtype_id")) {
                $sitereviewArray['privacySettings']['authTopic'] = $role;
            }
            if (1 == $auth->isAllowed($sitereview, $role, "photo_listtype_$listingtype_id")) {
                $sitereviewArray['privacySettings']['authPhoto'] = $role;
            }
            if (1 == $auth->isAllowed($sitereview, $role, "video_listtype_$listingtype_id")) {
                $sitereviewArray['privacySettings']['authVideo'] = $role;
            }
        }
        
        
        return $sitereviewArray;
    }
    public function getUserData(User_Model_User $user){
        $contentImages = $this->getContentImage($user);
        $contentImages['photoID'] = (string)$user->photo_id;
        $expert = ($user->gg_expert_bronze_count || $user->gg_expert_silver_count || $user->gg_expert_gold_count || $user->gg_expert_platinum_count);
        $userArray = array(
            'memberID' => (string)$user->getIdentity(),
            'memberName' => (string)$user->username,
            'displayName' => $user->getTitle(),
            'firstName' => $this->getFieldValue($user, 3),
            'lastName' => (string)$this->getFieldValue($user, 4),
            'followersCount' => $user->gg_followers_count,
            'followingCount' => $user->gg_following_count,
            'reviewCount' => $user->gg_review_count,
            'questionCount' => $user->gg_question_count,
            'answerCount' => 0, //No answer count
            'guideCount' => $user->gg_guide_count,
            'contribution' => $user->gg_contribution,
            'contributionLevel' => $user->gg_contribution_level,
            'avatarPhoto' => $contentImages,
            'bronzeCount' => $user->gg_bronze_count,
            'silverCount' => $user->gg_silver_count,
            'goldCount' => $user->gg_gold_count,
            'platinumCount' => $user->gg_platinum_count,
            'expertBronzeCount' => $user->gg_expert_bronze_count,
            'expertSilverCount' => $user->gg_expert_silver_count,
            'expertGoldCount' => $user->gg_expert_gold_count,
            'expertPlatinumCount' => $user->gg_expert_platinum_count,
            'mvp' => (bool)$user->gg_mvp,
            'expert' => (bool)$expert,
        );
        return $userArray;
    }
}