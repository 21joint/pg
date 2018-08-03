<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_Api_V1_Response extends Sdparentalguide_Api_Core {
    public function getFormatedDateTime($datetime){
        if(empty($datetime)){
            return "";
        }
        $view = Zend_Registry::get("Zend_View");
        return $view->locale()->toDateTime($datetime,array('format' => 'YYYY-MM-d HH:MM:ss'));
    }
    private function translate($message = ''){
        return Engine_Api::_()->getApi('Core', 'siteapi')->translate($message);
    }
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
        $listingType = $sitereview->getListingType();
        $topic = Engine_Api::_()->getItem("sdparentalguide_topic",$listingType->gg_topic_id);
        return $this->getTopicData($topic);
    }
    public function getListingPhotos(Sitereview_Model_Listing $listing) {
        $listingPhotos = parent::getListingPhotos($listing);
        if($listingPhotos->getTotalItemCount() <= 0){
            return array();
        }
        $listingPhotosArray = array();
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $avatarPhoto = ucfirst($request->getParam("photoType",""));
        foreach($listingPhotos as $photo){
            $photos = $this->getContentImage($photo);
            $photos['photoID'] = (string)$photo->getIdentity();
            $listingPhotosArray[] = $photos;
        }
        return $listingPhotosArray;
    }
    
    public function getReviewData(Sitereview_Model_Listing $sitereview){
        $view = Zend_Registry::get("Zend_View");
        $viewer = Engine_Api::_()->user()->getViewer();
        $user = $sitereview->getOwner();
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $avatarPhoto = ucfirst($request->getParam("photoType",""));
        $listingRating = Engine_Api::_()->getDbTable("listingRatings","sdparentalguide")->getAvgListingRating($sitereview);
        $listingtype_id = $sitereview->listingtype_id;
        $contentImages = $this->getContentImage($sitereview);
        $contentImages['photoID'] = (string)$sitereview->photo_id;
//        $contentImages['photoURL'] = isset($sitereviewPhtos['photoURL'.$avatarPhoto])?$sitereviewPhtos['photoURL'.$avatarPhoto]:$sitereviewPhtos['photoURLIcon'];
        $tmpBody = strip_tags($sitereview->body);
        $shortDesc = ( Engine_String::strlen($tmpBody) > 100 ? Engine_String::substr($tmpBody, 0, 100) . '...' : $tmpBody );
        $sitereviewArray = array(
            'reviewID' => (string)$sitereview->getIdentity(),
            'title' => $sitereview->getTitle(),
            'shortDescription' => $shortDesc,
            'longDescription' => $tmpBody,
            'likesCount' => $sitereview->likes()->getLikeCount(),
            'commentsCount' => $sitereview->comments()->getCommentCount(),
            'publishedDateTime' => $this->getFormatedDateTime($sitereview->approved_date),
            'featured' => $sitereview->featured,
            'approved' => $sitereview->approved,
            'createdDateTime' => $this->getFormatedDateTime($sitereview->creation_date),
            'lastModifiedDateTime' => $this->getFormatedDateTime($sitereview->modified_date),
            'status' => (string)$this->getListingStatus($sitereview),
            'author' => $this->getUserData($user),
            'authorRating' => $sitereview->gg_author_product_rating,
            'coverPhoto' => $contentImages,
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
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $avatarPhoto = ucfirst($request->getParam("photoType","icon"));
        $contentImages = $this->getContentImage($user);
        $contentImages['photoID'] = (string)$user->photo_id;
//        $contentImages['photoURL'] = isset($userPhotos['photoURL'.$avatarPhoto])?$userPhotos['photoURL'.$avatarPhoto]:$userPhotos['photoURLIcon'];
        $coverPhotos = array();
        if(!empty($user->user_cover)){
            $fileObject = Engine_Api::_()->storage()->get($user->user_cover);
            $coverPhotos = $this->getContentImage($fileObject);
            $coverPhotos['photoID'] = (string)$user->user_cover;
        }
        
        $expert = ($user->gg_expert_bronze_count || $user->gg_expert_silver_count || $user->gg_expert_gold_count || $user->gg_expert_platinum_count);
        $userArray = array(
            'memberID' => (string)$user->getIdentity(),
            'memberName' => (string)$user->username,
            'displayName' => (string)$user->getTitle(),
            'firstName' => (string)$this->getFieldValue($user, 3),
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
            'memberSinceDateTime' => $this->getFormatedDateTime($user->creation_date),
            'coverPhoto' => $coverPhotos,
            'href' => $user->getHref()
        );
        $view = Zend_Registry::get("Zend_View");
        $badgeHelper = new Sdparentalguide_View_Helper_ItemPhotoBadgeColor();
        $badgeHelper->setView($view);
        $userArray['badgeInfo'] = (array)$badgeHelper->ItemPhotoBadgeColor($user);
        return $userArray;
    }
    
    public function getTopicData($topic){
        $topicArray = array(
            'topicID' => '',
            'topicName' => '',
            'topicPhoto' => array(),
            'featured' => false
        );
        if(empty($topic)){
            return $topicArray;
        }
        $topicArray['topicID'] = (string)$topic->getIdentity();
        $topicArray['topicName'] = $topic->getTitle();
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $avatarPhoto = ucfirst($request->getParam("photoType","icon"));
        $contentImages = $this->getContentImage($topic);
//        $contentImages['photoURL'] = isset($topicPhotos['photoURL'.$avatarPhoto])?$topicPhotos['photoURL'.$avatarPhoto]:$topicPhotos['photoURLIcon'];
        $contentImages['photoID'] = (string)$topic->photo_id;
        $topicArray['topicPhoto'] = $contentImages;
        $topicArray['featured'] = (bool)$topic->featured;
        return $topicArray;
    }
    
    public function getCommentData(Core_Model_Item_Abstract $comment){
        $dislikesTable = Engine_Api::_()->getDbTable("dislikes","nestedcomment");
        $viewer = Engine_Api::_()->user()->getViewer();
        $resource = $comment->getResource();
        $childCommentsSelect = $resource->comments()->getCommentSelect();
        $childCommentsSelect->where('parent_comment_id =?', $comment->getIdentity());        
        $commentInfo = array();
        $poster = Engine_Api::_()->getItem($comment->poster_type, $comment->poster_id);
        $commentInfo["commentID"] = (string)$comment->comment_id;
        $commentInfo['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($comment->resource_type);
        $commentInfo['contentID'] = (string)$comment->resource_id;
        $commentInfo['commentsCount'] = Zend_Paginator::factory($childCommentsSelect)->getTotalItemCount();
        $commentInfo["body"] = $comment->body;
        $commentInfo["createdDateTime"] = $this->getFormatedDateTime($comment->creation_date);
        $commentInfo["likesCount"] = $comment->likes()->getLikeCount();        
        $commentInfo['dislikesCount'] = $dislikesTable->dislikes($comment)->getDislikeCount();
        $commentInfo['totalLikesCount'] = $commentInfo["likesCount"] - $commentInfo['dislikesCount'];
        $commentInfo["lastModifiedDateTime"] = $this->getFormatedDateTime($comment->creation_date);
        $commentInfo["canDelete"] = false;
        if ($poster->isSelf($viewer)) {
            $commentInfo["canDelete"] = true;
        }
        $commentInfo['isLiked'] = (bool)$comment->likes()->isLike($viewer);
        $commentInfo["author"] = $this->getUserData($poster);
        return $commentInfo;
    }
    
    public function getQuestionData(Ggcommunity_Model_Question $question){
        $view = Zend_Registry::get("Zend_View");
        $topic = Engine_Api::_()->getItem("sdparentalguide_topic",$question->topic_id);
        $questionArray = array();
        $questionArray['questionID'] = (string)$question->question_id;
        $questionArray['title'] = (string)$question->title;
        $questionArray['body'] = (string)strip_tags($question->body);
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $avatarPhoto = ucfirst($request->getParam("photoType","normal"));
        $contentImages = $this->getContentImage($question);
        $contentImages['photoID'] = (string)$question->photo_id;
//        $contentImages['photoURL'] = isset($questionPhotos['photoURL'.$avatarPhoto])?$questionPhotos['photoURL'.$avatarPhoto]:$questionPhotos['photoURLIcon'];        
        $questionArray['coverPhoto'] = $contentImages;
        $questionArray["closedDateTime"] = $this->getFormatedDateTime($question->date_closed);
        $questionArray['approved'] = (bool)$question->approved;
        $questionArray['featured'] = (bool)$question->featured;
        $questionArray['viewCount'] = $question->view_count;
        $questionArray['commentsCount'] = $question->comment_count;
        $questionArray['answerCount'] = $question->answer_count;
        $questionArray['answerChosen'] = (bool)$question->accepted_answer;
        $questionArray['upVoteCount'] = $question->up_vote_count;
        $questionArray['downVoteCount'] = $question->down_vote_count;
        $questionArray['totalVoteCount'] = $question->up_vote_count - $question->down_vote_count;
        $questionArray['approvedDateTime'] = $this->getFormatedDateTime($question->approved_date);
        $questionArray['status'] = ($question->draft == 0)?$view->translate("Published"):$view->translate("Draft");
        $questionArray['questionTopic'] = $this->getTopicData($topic);
        $questionArray['createdDateTime'] = $this->getFormatedDateTime($question->creation_date);
        $questionArray['publishedDateTime'] = $this->getFormatedDateTime($question->approved_date);
        $questionArray['lastModifiedDateTime'] = $this->getFormatedDateTime($question->modified_date);
        $questionArray['author'] = $this->getUserData($question->getOwner());
        $questionArray["canDelete"] = false;
        $viewer = Engine_Api::_()->user()->getViewer();
        $vote = Engine_Api::_()->ggcommunity()->getVote($question, $viewer);
        $voteType = '';
        $status = 0;
        if(!empty($vote)){
            $voteType = 'upvote';
            if(!$vote->vote_type){
                $voteType = 'downvote';
            }
            $status = 1;
        }
        $questionArray["userVote"] = array(
            'voteType' => $voteType,
            'status' => $status
        );
        if ($question->getOwner()->isSelf($viewer) || $viewer->isAdmin()) {
            $questionArray["canDelete"] = true;
        }
        return $questionArray;
    }
    
    public function getAnswerData(Ggcommunity_Model_Answer $answer,$question = null){
        if(empty($question)){
            $question = Engine_Api::_()->getItem("ggcommunity_question",$answer->parent_id);
        }
        $answerArray = array();
        $answerArray['answerID'] = (string)$answer->answer_id;
        $answerArray['question'] = $this->getQuestionData($question);
        $answerArray['approved'] = (bool)$answer->approved;
        $answerArray['viewCount'] = 0;
        $answerArray['body'] = strip_tags($answer->body);
        $answerArray['commentsCount'] = $answer->comment_count;
        $answerArray['answerChosen'] = (bool)$answer->accepted;
        $answerArray['upVoteCount'] = $answer->up_vote_count;
        $answerArray['downVoteCount'] = $answer->down_vote_count;
        $answerArray['totalVoteCount'] = $answer->up_vote_count - $answer->down_vote_count;
        $answerArray['createdDateTime'] = $this->getFormatedDateTime($answer->creation_date);
        $answerArray['lastModifiedDateTime'] = $this->getFormatedDateTime($answer->modified_date);
        $answerArray['author'] = $this->getUserData($answer->getOwner());
        $answerArray["canDelete"] = false;
        $viewer = Engine_Api::_()->user()->getViewer();
        $vote = Engine_Api::_()->ggcommunity()->getVote($answer, $viewer);
        $voteType = '';
        $status = 0;
        if(!empty($vote)){
            $voteType = 'upvote';
            if(!$vote->vote_type){
                $voteType = 'downvote';
            }
            $status = 1;
        }
        $answerArray["userVote"] = array(
            'voteType' => $voteType,
            'status' => $status
        );
        if ($answer->getOwner()->isSelf($viewer) || $viewer->isAdmin()) {
            $answerArray["canDelete"] = true;
        }
        return $answerArray;
    }
    
    public function getSearchItemData($item){
        $itemObject = Engine_Api::_()->getItem($item->type,$item->id);
        if(empty($itemObject) || !$itemObject->getIdentity()){
            return;
        }
        $itemArray = array();
        switch($item->type){
            case 'sitereview_listing':
                $itemArray = $this->getReviewData($itemObject);
                break;
            case 'user':
                $itemArray = $this->getUserData($itemObject);
                break;
            case 'sdparentalguide_topic':
                $itemArray = $this->getTopicData($itemObject);
                break;
            case 'ggcommunity_question':
                $itemArray = $this->getQuestionData($itemObject);
                break;
            case 'ggcommunity_answer':
                $itemArray = $this->getAnswerData($itemObject);
                break;
            case 'sdparentalguide_badge':
                $itemArray = $this->getBadgeData($itemObject);
                break;
            default:
                break;
        }
        $searchArray = array(
            'searchRank' => "1",
            'contentType' => Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($item->type),
            'contentObject' => $itemArray
        );
        return $searchArray;
    }
    
    public function getFollowData($subject,$row){        
        if($subject->getType() == "user"){
            $resource_id = $row->user_id;
        }else{
            $resource_id = $row->poster_id;
        }
        $followId = '';
        if(isset($row->gg_guid) && !empty($row->gg_guid)){
            $followId = $row->gg_guid;
        }
        if(isset($row->follow_id)){
            $followId = $row->follow_id;
        }
        $follower = Engine_Api::_()->getItem("user",$resource_id);
        $rowData = array(
            'followID' => (string)$followId,
            'contentType' => Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($subject->getType()),
            'contentID' => (string)$subject->getIdentity(),
            'followerID' => (string)$resource_id,
            'createdDateTime' => $this->getFormatedDateTime($row->creation_date),
            'author' => $this->getUserData($follower),
            'approved' => (bool)$row->active
        );
        return $rowData;
    }
    
    public function getViewData($view){
        $owner = Engine_Api::_()->user()->getUser($view->owner_id);
        return array(
            'actionID' => (string)$view->action_id,
            'actionType' => ucfirst($view->action_type),
            'contentType' => Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($view->conent_type),
            'contentID' => (string)$view->content_id,
            'createdDateTime' => $this->getFormatedDateTime($view->creation_date),
            'author' => $owner->getIdentity()?$this->getUserData($owner):array(),
        );
    }
    
    public function getBadgeData(Sdparentalguide_Model_Badge $badge){
        $contentImages = $this->getContentImage($badge);
        $contentImages['photoID'] = (string)$badge->photo_id;
        $topic = $badge->getTopic();
        return array(
            'badgeID' => (string)$badge->getIdentity(),
            'badgeType' => (string)$badge->getBadgeType(),
            'badgeLevel' => (string)$badge->getLevel(),
            'badgePhoto' => $contentImages,
            'badgeDescription' => (string)strip_tags($badge->description),
            'topic' => $this->getTopicData($topic)
        );
    }
    public function getMemberBadgeData(Sdparentalguide_Model_Badge $badge,$member = null){
        $badgeData = $this->getBadgeData($badge);
        if(empty($member) || !$member->getIdentity()){
            $member = Engine_Api::_()->user()->getUser($member);
        }
        
        $active = $assigned = false;
        $assignedRow = $badge->isAssigned($member);
        if(!empty($assignedRow)){
            $assigned = true;
            if($assignedRow->active == true){
                $active = true;
            }
        }
        
        return array(
            'badge' => $badgeData,
            'member' => $this->getUserData($member),
//            'assigned' => $assigned,
            'active' => $active
        );
    }
    
    public function getRatingData(Sdparentalguide_Model_ListingRating $rating){
        return array(
            "ratingID" => (string)$rating->getIdentity(),
            'contentType' => Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($rating->listing_type),
            'contentID' => (string)$rating->listing_id,
        );
    }
    
    public function getContributionData(Sitecredit_Model_Credit $credit){
        $topic = Engine_Api::_()->getItem("sdparentalguide_topic",$credit->gg_topic_id);
        $activityCredit = Engine_Api::_()->getItem("activitycredit",$credit->type_id);
        $contributionData = array(
            'contributionID' => (string)$credit->getIdentity(),
            'memberID' => '',
            'contributionPoints' => (int)$credit->credit_point,
            'contributionDateTime' => $this->getFormatedDateTime($credit->creation_date),
            'contributionDetail' => '',
            'topic' => $this->getTopicData($topic)
        );
        $viewer = Engine_Api::_()->user()->getViewer();
        if($viewer->isAdmin()){
            $contributionData['memberID'] = (string)$credit->user_id;
        }
        if(!empty($activityCredit) && empty($activityCredit->language_en)){
            $activity_type = $this->translate('ADMIN_ACTIVITY_TYPE_' . strtoupper($activityCredit->activity_type));
            $activity_type = str_replace("(subject)","",$activity_type);
            $activity_type = str_replace("(object)","",$activity_type);
            $contributionData['contributionDetail'] = $activity_type;
        }else if(!empty($activityCredit)) {
            $contributionData['contributionDetail'] = $activityCredit->language_en;
        }
        return $contributionData;
    }
}