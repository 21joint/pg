<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_CommentsController extends Pgservicelayer_Controller_Action_Api
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
                $this->putAction();
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
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity() && $this->isApiRequest()){
            $this->respondWithError('unauthorized');
        }
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        $parentCommentId = null;
        $commentId = $this->getParam('commentID');
        if($subject->getType() == "core_comment"){
            $subject = Engine_Api::_()->getItem($subject->resource_type,$subject->resource_id);
            $parentCommentId = $this->getParam("contentID");
        }
        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity() ||
                (!method_exists($subject, 'comments') && !method_exists($subject, 'likes')))
            $this->respondWithError('no_record');
        $canComment = $subject->authorization()->isAllowed($viewer, 'comment');
        if (!$viewer->getIdentity() && !$canComment)
            $this->respondWithError('unauthorized');
        
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $orderBy = $this->getParam("orderBy","createdDateTime");
        $orderByDirection = $this->getParam("orderByDirection","descending");
        $orderByDirection = (strtolower($orderByDirection) == "descending")?"DESC":"ASC";
        $isLike = Engine_Api::_()->getDbTable("likes", "core")->isLike($subject, $viewer);
        $commentSelect = $subject->comments()->getCommentSelect();
        $commentSelect->reset("order");
        if($orderBy == "createdDateTime"){
            $commentSelect->order("creation_date $orderByDirection");
        }else{
            $commentSelect->order("comment_id $orderByDirection");
        }
        
        if(!empty($parentCommentId)){
            $commentSelect->where("parent_comment_id = ?",$parentCommentId);
        }else{
            $commentSelect->where("parent_comment_id = ?",0);
        }
        
        if(!empty($commentId)){
            $commentSelect->where("comment_id = ?",$commentId);
        }
        $commentSelect->where("gg_deleted = ?",0);
        $comments = Zend_Paginator::factory($commentSelect);
        $comments->setCurrentPageNumber($page);
        $comments->setItemCountPerPage($limit);
        
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        $response['isLike'] = (bool)(!empty($isLike) ? 1 : 0);
        $response['canComment'] = (bool)$canComment;
        if($page > $comments->count()){
            $this->respondWithSuccess($response);
        }
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        foreach($comments as $comment){
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($comment->getType());
            ++$response['ResultCount'];
            $response['Results'][] = $responseApi->getCommentData($comment);
        }
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        $parentCommentId = null;
        if($subject->getType() == "core_comment"){
            $subject = Engine_Api::_()->getItem($subject->resource_type,$subject->resource_id);
            $parentCommentId = $this->getParam("contentID");
        }
        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity() ||
                (!method_exists($subject, 'comments') && !method_exists($subject, 'likes')))
            $this->respondWithError('no_record');
        $viewer = Engine_Api::_()->user()->getViewer();
        $canComment = $subject->authorization()->isAllowed($viewer, 'comment');
        if (!$viewer->getIdentity() && !$canComment)
            $this->respondWithError('unauthorized');
        
        // Filter HTML
        $filter = new Zend_Filter();
        $filter->addFilter(new Engine_Filter_Censor());
        $filter->addFilter(new Engine_Filter_HtmlSpecialChars());

        $body = $this->getParam('body');
        $body = $filter->filter($body);
        if(empty($body)){
            $this->respondWithValidationError('validation_fail', array(
                'body' => $this->translate("Please complete this field - it is required.")
            ));
        }

        $db = $subject->comments()->getCommentTable()->getAdapter();
        $db->beginTransaction();

        try {
            $comment = $subject->comments()->addComment($viewer, $body);
            if(!empty($parentCommentId) && isset($comment->parent_comment_id)){
                $comment->parent_comment_id = $parentCommentId;
                $comment->save();
            }
            Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.comments');
            if (!empty($comment)) {
                $db->commit();
                $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
                $response['ResultCount'] = 1;
                $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($comment->getType());
                $response['Results'] = array();
                $response['Results'][] = $responseApi->getCommentData($comment);
                $this->respondWithSuccess($response);
            } else {
                $this->respondWithValidationError('internal_server_error', 'Problem in comment');
            }
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
        
    }
    
    public function putAction(){
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        $parentCommentId = null;
        if($subject->getType() == "core_comment"){
            $subject = Engine_Api::_()->getItem($subject->resource_type,$subject->resource_id);
            $parentCommentId = $this->getParam("contentID");
        }
        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity() ||
                (!method_exists($subject, 'comments') && !method_exists($subject, 'likes')))
            $this->respondWithError('no_record');
        $viewer = Engine_Api::_()->user()->getViewer();
        $canComment = $subject->authorization()->isAllowed($viewer, 'comment');
        if (!$viewer->getIdentity() && !$canComment)
            $this->respondWithError('unauthorized');
        
        $comment_id = $this->getParam('commentID');
        if(empty($comment_id)){
            $this->respondWithError('no_record');
        }
        $comment = $subject->comments()->getComment($comment_id);   
        if(empty($comment)){
            $this->respondWithError('no_record');
        }
        
        // Filter HTML
        $filter = new Zend_Filter();
        $filter->addFilter(new Engine_Filter_Censor());
        $filter->addFilter(new Engine_Filter_HtmlSpecialChars());

        $body = $this->getParam('body',$comment->body);
        $body = $filter->filter($body);
        if(empty($body)){
            $this->respondWithValidationError('validation_fail', array(
                'body' => $this->translate("Please complete this field - it is required.")
            ));
        }
        
        $poster = Engine_Api::_()->getItem($comment->poster_type, $comment->poster_id);
        if(!$poster->isSelf($viewer)){
            $this->respondWithError('unauthorized');
        }

        $db = $subject->comments()->getCommentTable()->getAdapter();
        $db->beginTransaction();

        try {
            $comment->body = $body;
            $comment->save();
            if (!empty($comment)) {
                $db->commit();
                $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
                $response['ResultCount'] = 1;
                $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($comment->getType());
                $response['Results'] = array();
                $response['Results'][] = $responseApi->getCommentData($comment);
                $this->respondWithSuccess($response);
            } else {
                $this->respondWithValidationError('internal_server_error', 'Problem in comment');
            }
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
        
    }
    
    public function deleteAction(){
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity() ||
                (!method_exists($subject, 'comments') && !method_exists($subject, 'likes')))
            $this->respondWithError('no_record');
        $viewer = Engine_Api::_()->user()->getViewer();
        $canComment = $subject->authorization()->isAllowed($viewer, 'comment');
        if (!$viewer->getIdentity() && !$canComment)
            $this->respondWithError('unauthorized');
        
        $id = $this->getParam("commentID");
        $idsArray = (array)$id;
        if(is_string($id) && !empty($id)){
            $idsArray = array($id);
        }
        $comments = Engine_Api::_()->getItemMulti("core_comment",$idsArray);
        if (empty($comments)) {
            $this->respondWithError('no_record');
        }
        $db = $subject->comments()->getCommentTable()->getAdapter();
        $db->beginTransaction();
        try {
            foreach($comments as $comment){
                $poster = Engine_Api::_()->getItem($comment->poster_type, $comment->poster_id);                
                if(!$poster->isSelf($viewer)){
                    $this->respondWithError('unauthorized');
                }
                $comment->gg_deleted = 1;
                $comment->save();
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
        $this->successResponseNoContent('no_content');        
        
    }
}
