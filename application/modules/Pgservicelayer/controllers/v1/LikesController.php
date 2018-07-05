<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_LikesController extends Pgservicelayer_Controller_Action_Api
{
    public function init(){
        $timezone = Engine_Api::_()->getApi('settings', 'core')->core_locale_timezone;
        $viewer   = Engine_Api::_()->user()->getViewer();
        $defaultLocale = $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en_US');
        $defaultLocaleObj = new Zend_Locale($defaultLocale);
        Zend_Registry::set('LocaleDefault', $defaultLocaleObj);

        if ($viewer->getIdentity()) {
            $timezone = $viewer->timezone;
        }
        Zend_Registry::set('timezone', $timezone);
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
        Engine_Api::_()->getApi('Core', 'siteapi')->setTranslate();
        Engine_Api::_()->getApi('Core', 'siteapi')->setLocal();
        
        $resourceType = $this->getParam("resourceType");
        $resourceId = $this->getParam("resourceID");
        if(empty($resourceType) || empty($resourceId)){
            $this->respondWithError('no_record');
        }
        
        if(!Engine_Api::_()->hasItemType($resourceType)){
            $this->respondWithError('no_record');
        }
        
        $subject = Engine_Api::_()->getItem($resourceType,$resourceId);
        if(empty($subject) || !$subject->getIdentity()){
            $this->respondWithError('no_record');
        }
        
        if(!Engine_Api::_()->core()->hasSubject()){
            Engine_Api::_()->core()->setSubject($subject);
        }
        
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
        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity() ||
                (!method_exists($subject, 'comments') && !method_exists($subject, 'likes')))
            $this->respondWithError('no_record');
        $canComment = $subject->authorization()->isAllowed($viewer, 'comment');
        $canDelete = $subject->authorization()->isAllowed($viewer, 'edit');
        if (!$canComment && !$canDelete)
                $this->respondWithError('unauthorized');
        
        if (!$canComment && !$canDelete)
            $this->respondWithError('unauthorized');
        
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",10);
        $comment_id = $this->getRequestParam('commentID');
        if(!empty($comment_id)){
            $commentedItem = $subject->comments()->getComment($comment_id);
            if(!empty($commentedItem)){
                $isLike = Engine_Api::_()->getDbTable("likes", "core")->isLike($commentedItem, $viewer);
                $likes = $commentedItem->likes()->getLikePaginator();
                $usersObject = $commentedItem->likes()->getAllLikesUsers();
            }else{
                $isLike = Engine_Api::_()->getDbTable("likes", "core")->isLike($subject, $viewer);
                $likes = $subject->likes()->getLikePaginator();
                $usersObject = $subject->likes()->getAllLikesUsers();
            }            
        }else{
            $isLike = Engine_Api::_()->getDbTable("likes", "core")->isLike($subject, $viewer);
            $likes = $subject->likes()->getLikePaginator();
            $usersObject = $subject->likes()->getAllLikesUsers();
        }
        
        $likes->setCurrentPageNumber($page);
        $likes->setItemCountPerPage($limit);
        
        $response['ResultCount'] = $likes->getTotalItemCount();
        $response['Results'] = array();
        $response['isLike'] = !empty($isLike) ? 1 : 0;
        $response['canComment'] = $canComment;
        $response['canDelete'] = $canDelete;
        
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");        
        foreach($usersObject as $user){
            $response['Results'][] = $responseApi->getUserData($user);
        }
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
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
        $canDelete = $subject->authorization()->isAllowed($viewer, 'edit');
        if (!$canComment && !$canDelete)
            $this->respondWithError('unauthorized');
        
        // Filter HTML
        $filter = new Zend_Filter();
        $filter->addFilter(new Engine_Filter_Censor());
        $filter->addFilter(new Engine_Filter_HtmlSpecialChars());

        $body = $filter->filter($body);

        $db = $subject->comments()->getCommentTable()->getAdapter();
        $db->beginTransaction();

        try {
            $comment = $subject->comments()->addComment($viewer, $body);            
            Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.comments');
            if (!empty($comment)) {
                $db->commit();
                $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
                $response['ResultCount'] = 1;
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
        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity() ||
                (!method_exists($subject, 'comments') && !method_exists($subject, 'likes')))
            $this->respondWithError('no_record');
        $viewer = Engine_Api::_()->user()->getViewer();
        $canComment = $subject->authorization()->isAllowed($viewer, 'comment');
        $canDelete = $subject->authorization()->isAllowed($viewer, 'edit');
        if (!$canComment && !$canDelete)
            $this->respondWithError('unauthorized');
        
        // Filter HTML
        $filter = new Zend_Filter();
        $filter->addFilter(new Engine_Filter_Censor());
        $filter->addFilter(new Engine_Filter_HtmlSpecialChars());

        $body = $this->getParam('body');
        $body = $filter->filter($body);
        $comment_id = $this->getParam('commentID');
        if(empty($comment_id)){
            $this->respondWithError('no_record');
        }
        $comment = $subject->comments()->getComment($comment_id);   
        if(empty($comment)){
            $this->respondWithError('no_record');
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
        $canDelete = $subject->authorization()->isAllowed($viewer, 'edit');
        if (!$canComment && !$canDelete)
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
