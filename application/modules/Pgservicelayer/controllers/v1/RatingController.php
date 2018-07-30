<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_RatingController extends Pgservicelayer_Controller_Action_Api
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
        
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $table = Engine_Api::_()->getDbTable("listingRatings","sdparentalguide");
        $select = $table->select();
        
        $contentType = $this->getParam("contentType");
        $contentType = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($contentType);
        if(!empty($contentType)){
            $select->where("listing_type = ?",$contentType);
        }
        
        $contentID = $this->getParam("contentID");
        if(!empty($contentID)){
            $select->where("listing_id = ?",$contentID);
        }
        $ratingID = $this->getParam("ratingID");
        if(!empty($ratingID)){
            $select->where("listingrating_id = ?",$ratingID);
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        if (!($subject instanceof Core_Model_Item_Abstract) || !$subject->getIdentity())
            $this->respondWithError('no_record');
        
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer->getIdentity())
            $this->respondWithError('unauthorized');
        

        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
            
            $table = Engine_Api::_()->getDbTable("listingRatings","sdparentalguide");
            $userRating = $table->getRating($subject,$viewer);
            $reviewRating = $this->getParam("author");
            $productRating = $this->getParam("product");
            if(empty($reviewRating)){
                $this->respondWithValidationError('validation_fail', array(
                    'author' => $this->translate("Please complete this field - it is required.")
                ));
            }
            if(empty($productRating)){
                $this->respondWithValidationError('validation_fail', array(
                    'product' => $this->translate("Please complete this field - it is required.")
                ));
            }
            if($reviewRating > 5){
                $this->respondWithValidationError('validation_fail', array(
                    'author' => $this->translate("Maximum rating limit exceeded.")
                ));
            }
            if($productRating > 5){
                $this->respondWithValidationError('validation_fail', array(
                    'product' => $this->translate("Maximum rating limit exceeded.")
                ));
            }
            if(empty($userRating)){
                $userRating = $table->createRow();
                $userRating->user_id = $viewer->getIdentity();
                $userRating->listing_id = $subject->getIdentity();
                $userRating->listing_type = $subject->getType();
            }
            $userRating->review_rating = $reviewRating;
            $userRating->product_rating = $productRating;
            $userRating->save();
            
            $db->commit();
            $this->successResponseNoContent('no_content');            
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
        
    }
    
    public function putAction(){
        $this->postAction();
    }
}
