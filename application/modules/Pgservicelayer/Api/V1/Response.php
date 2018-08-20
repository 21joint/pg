<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_Api_V1_Response extends Sdparentalguide_Api_Core {
    protected $_permissionsData = null;
    public function getFormatedDateTime($datetime){
        if(empty($datetime) || $datetime == "0000-00-00 00:00:00"){
            return "";
        }
        $view = Zend_Registry::get("Zend_View");
        return $view->locale()->toDateTime($datetime,array('format' => 'YYYY-MM-d HH:mm:ss'));
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
            'averageReviewRating' => sprintf("%.1f",$listingRating['author_rating']),
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
        $contentImages['avatarPhotoID'] = (string)$user->photo_id;
//        $contentImages['photoURL'] = isset($userPhotos['photoURL'.$avatarPhoto])?$userPhotos['photoURL'.$avatarPhoto]:$userPhotos['photoURLIcon'];
        $coverPhotos = array();
        if(!empty($user->coverphoto)){
            $fileObject = Engine_Api::_()->storage()->get($user->coverphoto);
            $coverPhotos = $this->getContentImage($fileObject);
            $coverPhotos['coverPhotoID'] = (string)$user->coverphoto;
            $coverPhotoPosition = (array)@json_decode($user->coverphotoparams);
            $coverPhotos['coverPhotoPosition']['top'] = (int)isset($coverPhotoPosition['top'])?$coverPhotoPosition['top']:0;
            $coverPhotos['coverPhotoPosition']['left'] = (int)isset($coverPhotoPosition['left'])?$coverPhotoPosition['left']:0;
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
            'href' => $user->getHref(),
            'isPrivate' => (bool)(!$user->search),
            'isInfluencer' => (bool)$user->gg_is_influencer
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
            case 'sdparentalguide_guide':
                $itemArray = $this->getGuideData($itemObject);
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
    
    public function getPermissionData(User_Model_User $user){
        if($this->_permissionsData != null){
            return $this->_permissionsData;
        }
        $user_id = $user->getIdentity();
        if (APPLICATION_ENV === 'production') {
            $cache = Zend_Registry::get('Zend_Cache');
            $cacheName = 'member_permission_'.(int)$user_id;
            $data = $cache->load($cacheName);
            if (!empty($data)) {
                $this->_permissionsData = $data;
                return $data;
            }
        }
        $level_id = !empty($user_id) ? $user->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        $listingtype_id = '0';
        $level = Engine_Api::_()->getItem("authorization_level",$level_id);
        $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
        $editListings = $permissionsTable->getAllowed('sitereview_listing', $level_id, "edit_listtype_$listingtype_id");
        $deleteListings = $permissionsTable->getAllowed('sitereview_listing', $level_id, "delete_listtype_$listingtype_id");
        $editQuestions = $permissionsTable->getAllowed('ggcommunity', $level_id, 'edit_question');
        $deleteQuestions = $permissionsTable->getAllowed('ggcommunity', $level_id, 'delete_question');
        $canSelectAnswer = $permissionsTable->getAllowed('ggcommunity', $level_id, 'best_answer');
        $canEditGuide = $permissionsTable->getAllowed('sdparentalguide_guide', $level_id, 'edit');
        $canDeleteGuide = $permissionsTable->getAllowed('sdparentalguide_guide', $level_id, 'delete');
        $permissionData = array(
            'memberID' => (string)$user->getIdentity(),
            'isAdmin' => (bool)$user->isAdminOnly(),
            'isModerator' => (bool)$user->isAdmin(),
            'canCommentOnComments' => (bool)$permissionsTable->getAllowed('sdparentalguide_custom', $level_id, "comment_comment"),
            'canLikeComments' => (bool)$permissionsTable->getAllowed('sdparentalguide_custom', $level_id, "like_comment"),
            
            //Reviews
            'canViewReview' => (bool)$permissionsTable->getAllowed('sitereview_listing', $level_id, "view_listtype_$listingtype_id"),
            'canCreateReview' => (bool)$permissionsTable->getAllowed('sitereview_listing', $level_id, "create_listtype_$listingtype_id"),
            'canEditReview' => (bool)$editListings,
            'canDeleteReview' => (bool)$deleteListings,
            'canEditOthersReview' => (bool)($editListings == 2),
            'canDeleteOthersReview' => (bool)($deleteListings == 2),
            'canLikeReview' => (bool)$permissionsTable->getAllowed('sitereview_listing', $level_id, "comment_listtype_$listingtype_id"),
            'canRateProductReview' => (bool)$permissionsTable->getAllowed('sitereview_listing', $level_id, "rate_product_listtype_$listingtype_id"),
            'canRateAuthorReview' => (bool)$permissionsTable->getAllowed('sitereview_listing', $level_id, "rate_author_listtype_$listingtype_id"),
            'canCommentReview' => (bool)$permissionsTable->getAllowed('sitereview_listing', $level_id, "comment_listtype_$listingtype_id"),
            'canApproveReview' => (bool)$permissionsTable->getAllowed('sitereview_listing', $level_id, "approved_listtype_$listingtype_id"),
            'canFlagReview' => (bool)$permissionsTable->getAllowed('sitereview_listing', $level_id, "flag_listtype_$listingtype_id"),
            'canGradeReview' => (bool)$permissionsTable->getAllowed('sitereview_listing', $level_id, "grade_listtype_$listingtype_id"),
            
            //Questions
            'canViewQuestion' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'view_question'),
            'canCreateQuestion' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'create_question'),
            'canEditQuestion' => (bool)$editQuestions,
            'canDeleteQuestion' => (bool)$deleteQuestions,
            'canEditOthersQuestion' => (bool)($editQuestions == 2),
            'canDeleteOthersQuestion' => (bool)($deleteQuestions == 2),
            'canVoteQuestion' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'vote_question'),
            'canCommentQuestion' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'comment_question'),
            'canAnswerQuestion' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'answer_question'),
            'canVoteAnswer' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'vote_answer'),
            'canCommentAnswer' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'comment_answer'),
            'canSelectAnswer' => (bool)$canSelectAnswer,
            'canChangeSelectedAnswer' => (bool)($canSelectAnswer == 2 || $canSelectAnswer == 6 || $canSelectAnswer == 4),
            'canSelectOthersAnswer' => (bool)($canSelectAnswer == 6 || $canSelectAnswer == 4),
            'canChangeOthersSelectedAnswer' => (bool)($canSelectAnswer == 4),
            'canApproveQuestion' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'approve_question'),
            'canChangeCloseDate' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'edit_close_date'),
            'canFlagQuestion' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'flag_question'),
            'canFlagAnswer' => (bool)$permissionsTable->getAllowed('ggcommunity', $level_id, 'flag_answer'),
            
            //Guides
            'canViewGuide' => (bool)$permissionsTable->getAllowed('sdparentalguide_guide', $level_id, 'view'),
            'canCreateGuide' => (bool)$permissionsTable->getAllowed('sdparentalguide_guide', $level_id, 'create'),
            'canEditGuide' => (bool)$canEditGuide,
            'canDeleteGuide' => (bool)$canDeleteGuide,
            'canEditOthersGuide' => (bool)($canEditGuide == 2),
            'canDeleteOthersGuide' => (bool)($canDeleteGuide == 2),
            'canLikeGuide' => (bool)$permissionsTable->getAllowed('sdparentalguide_guide', $level_id, 'like'),
            'canRateGuide' => (bool)$permissionsTable->getAllowed('sdparentalguide_guide', $level_id, 'rate'),
            'canCommentGuide' => (bool)$permissionsTable->getAllowed('sdparentalguide_guide', $level_id, 'comment'),
            'canApproveGuide' => (bool)$permissionsTable->getAllowed('sdparentalguide_guide', $level_id, 'approve'),
            'canFlagGuide' => (bool)$permissionsTable->getAllowed('sdparentalguide_guide', $level_id, 'flag'), 
            
            //Badge
            'canAssignAnyBadge' => (bool)$permissionsTable->getAllowed('sdparentalguide_custom', $level_id, "assign_badge"),
            
        );
        if (APPLICATION_ENV === 'production') {
            $cache->setLifetime(300); //300 seconds
            $cache->save($permissionData, $cacheName);
        }
        $this->_permissionsData = $permissionData;
        return $permissionData;
    }
    
    public function getGuideStatus(Sdparentalguide_Model_Guide $guide){        
        if($guide->gg_deleted){
            return "Deleted";
        }
        if($guide->closed){
            return "Closed";
        }
        if($guide->draft){
            return "Draft";
        }
        if(!$guide->approved){
            return "Pending Approval";
        }        
        return "Published";
    }
    public function getGuideData(Sdparentalguide_Model_Guide $guide,$includeGuideItems = true){
        $tmpBody = strip_tags($guide->description);
        $shortDesc = ( Engine_String::strlen($tmpBody) > 100 ? Engine_String::substr($tmpBody, 0, 100) . '...' : $tmpBody );
        $topic = Engine_Api::_()->getItem("sdparentalguide_topic",$guide->topic_id);
        $owner = $guide->getOwner();
        $contentImages = $this->getContentImage($guide);
        $contentImages['coverPhotoID'] = (string)$guide->photo_id;
        $listingRating = Engine_Api::_()->getDbTable("listingRatings","sdparentalguide")->getAvgListingRating($guide);
        $guideData = array(
            'guideID' => (string)$guide->getIdentity(),
            'title' => (string)$guide->title,
            'shortDescription' => (string)$shortDesc,
            'longDescription' => (string)$tmpBody,
            'guideTopic' => $this->getTopicData($topic),
            'guideItems' => array(),
            'createdDateTime' => (string)$this->getFormatedDateTime($guide->creation_date),
            'author' => $owner->getIdentity()?$this->getUserData($owner):array(),
            'lastModifiedDateTime' => (string)$this->getFormatedDateTime($guide->modified_date),
            'status' => (string)$this->getGuideStatus($guide),
            'approved' => (bool)$guide->approved,
            'featured' => (bool)$guide->featured,
            'publishedDateTime' => $guide->approved?$this->getFormatedDateTime($guide->published_date):'',
            'sponsored' => (bool)$guide->sponsored,
            'new' => (bool)$guide->newlabel,
            'guideItemCount' => $guide->guide_item_count,
            'coverPhoto' => $contentImages,
            'commentsCount' => $guide->comment_count,
            'likesCount' => $guide->like_count,
            'viewCount' => $guide->view_count,
            'clickCount' => $guide->click_count,
            'averageRating' => sprintf("%.1f",$listingRating['product_rating']),
            'privacySettings' => array(),
        );
        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
        foreach ($roles as $role) {
            if (1 == $auth->isAllowed($guide, $role, "view")) {
                $guideData['privacySettings']['authView'] = $role;
            }
            if (1 == $auth->isAllowed($guide, $role, "comment")) {
                $guideData['privacySettings']['authComment'] = $role;
            }
        }
        if($includeGuideItems){
            $guideItems = $guide->getItems();
            foreach($guideItems as $guideItem){
                $guideData['guideItems'][] = $this->getGuideItemData($guideItem);
            }
        }
        return $guideData;
    }
    
    public function getGuideItemData(Sdparentalguide_Model_GuideItem $guideItem){
        $tmpBody = strip_tags($guideItem->description);
        $shortDesc = ( Engine_String::strlen($tmpBody) > 100 ? Engine_String::substr($tmpBody, 0, 100) . '...' : $tmpBody );
        
        $guideData = array(
            'guideItemID' => (string)$guideItem->getIdentity(),
            'description' => (string)$tmpBody,
            'sequence' => (int)$guideItem->sequence,
            'contentType' => Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($guideItem->content_type),
            'contentObject' => $this->getGuideItemContentData($guideItem),
            'createdDateTime' => (string)$this->getFormatedDateTime($guideItem->creation_date),
            'lastModifiedDateTime' => (string)$this->getFormatedDateTime($guideItem->modified_date),
            'guideID' => (bool)$guideItem->guide_id,
            
        );
        return $guideData;
    }
    public function getGuideItemContentData(Sdparentalguide_Model_GuideItem $guideItem){
        $itemObject = $guideItem->getContent();
        if(empty($itemObject)){
            return array();
        }
        $itemArray = array();
        switch($guideItem->content_type){
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
        return $itemArray;
    }
}