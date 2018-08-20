<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_GuideitemController extends Pgservicelayer_Controller_Action_Api
{
    public function init(){
        parent::init();        
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
        
//        if(!$this->pggPermission('canViewGuide')){
//            $this->respondWithError('unauthorized');
//        }
        
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $table = Engine_Api::_()->getDbTable("guideItems","sdparentalguide");
        $tableName = $table->info("name");
        $select = $table->select();
        $select->where("$tableName.gg_deleted = ?",0);
        $select->order("sequence ASC")->order("item_id DESC");
        
        $guideID = $this->getParam("guideID","-1");
        if($guideID != -1){
            $select->where("$tableName.guide_id = ?",(int)$guideID);
        }
        
        $guideItemID = $this->getParam("guideItemID","-1");
        if($guideItemID != -1){
            $select->where("$tableName.item_id = ?",(int)$guideItemID);
        }
        
        $contentType = $this->getParam("contentType");
        if(!empty($contentType)){
            if(is_array($contentType)){
                $contentTypes = array();
                foreach($contentType as $type){
                    $contentTypes[] = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($type);
                }
                $select->where($tableName.'.content_type IN(?)', $contentTypes);
            }else{
                $contentType = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($contentType);
                $select->where($tableName.'.content_type = ?', $contentType);
            }            
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        $response['contentType'] = "";
        if($page > $paginator->count()){
            $this->respondWithSuccess($response);
        }
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        foreach($paginator as $guideItem){
            ++$response['ResultCount'];
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($guideItem->getType());
            $response['Results'][] = $responseApi->getGuideItemData($guideItem);
        }
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        
        if(!$viewer->getIdentity()){
            $this->respondWithError('unauthorized');
        }
//        if(!$this->pggPermission('canCreateGuide')){
//            $this->respondWithError('unauthorized');
//        }
        
        $form = Engine_Api::_()->getApi("V1_Forms","pgservicelayer")->getGuideItemForm();
        $validators = Engine_Api::_()->getApi("V1_Validators","pgservicelayer")->getGuideItemValidators();
        $values = $data = $_REQUEST;

        foreach ($form as $element) {
            if (isset($_REQUEST[$element['name']])){
                $values[$element['name']] = $_REQUEST[$element['name']];
            }
        }
        $values['validators'] = $validators;
        $validationMessage = $this->isValid($values);
        if (!empty($validationMessage) && @is_array($validationMessage)) {
            $this->respondWithValidationError('validation_fail', $validationMessage);
        }
        
        //Values for database
        $values = array(
            'description' => $this->getParam("description"),
            'sequence' => (int)$this->getParam("sequence",0),
            'content_type' => $this->getParam("contentType"),
            'content_id' => $this->getParam("contentID"),
            'guide_id' => $this->getParam("guideID",0),
        );
        
        $table = Engine_Api::_()->getDbTable("guideItems","sdparentalguide");
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
            $values['content_type'] = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($values['content_type']);
            $guideItem = $table->createRow();
            $guideItem->setFromArray($values);
            $guideItem->save();
            
            $guide = $guideItem->getGuide();
            if(!empty($guide)){
                ++$guide->guide_item_count;
                $guide->save();
            }
            
            
            $db->commit();
            
            $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
            $response['ResultCount'] = 1;
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($guideItem->getType());
            $response['Results'] = array();
            $response['Results'][] = $responseApi->getGuideItemData($guideItem);
            $this->respondWithSuccess($response);
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($e);
        }
        
    }
    
    public function putAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        
        if(!$viewer->getIdentity()){
            $this->respondWithError('unauthorized');
        }
        $id = $this->getParam("guideItemID");
        $guideItem = Engine_Api::_()->getItem("sdparentalguide_guide_item",$id);
        if(empty($guideItem) || $guideItem->gg_deleted){
            $this->respondWithError('no_record');
        }
        if(!$this->pggPermission('canEditGuide')){
            $this->respondWithError('unauthorized');
        }
                
        //Values for database
        $values = array(
            'description' => $this->getParam("description",$guideItem->description),
            'sequence' => (int)$this->getParam("sequence",$guideItem->sequence),
            'content_type' => $this->getParam("contentType",$guideItem->content_type),
            'content_id' => $this->getParam("contentID",$guideItem->content_id),
            'guide_id' => $this->getParam("guideID",$guideItem->guide_id),
        );
        
        $table = Engine_Api::_()->getDbTable("guideItems","sdparentalguide");
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
            $oldGuide = $guideItem->getGuide();
            $oldGuideID = $guideItem->guide_id;
            $newGuideID = $this->getParam("guideID",$guideItem->guide_id);
            $values['content_type'] = Engine_Api::_()->sdparentalguide()->mapPGGResourceTypes($values['content_type']);
            $guideItem->setFromArray($values);
            $guideItem->save();
            
            
            if(!empty($oldGuide) && $oldGuideID != $newGuideID){
                --$oldGuide->guide_item_count;
                $oldGuide->save();
            }
            $newGuide = $guideItem->getGuide();
            if(!empty($newGuide) && $oldGuideID != $newGuideID){
                ++$newGuide->guide_item_count;
                $newGuide->save();
            }
            
            if (empty($values['auth_view'])) {
                $values['auth_view'] = "everyone";
            }

            if (empty($values['auth_comment'])) {
                $values['auth_comment'] = "everyone";
            }
            
            $auth = Engine_Api::_()->authorization()->context;
            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
            $viewMax = array_search($values['auth_view'], $roles);
            $commentMax = array_search($values['auth_comment'], $roles);

            foreach ($roles as $i => $role) {
                $auth->setAllowed($guideItem, $role, "view", ($i <= $viewMax));
                $auth->setAllowed($guideItem, $role, "comment", ($i <= $commentMax));
            }
            
            $db->commit();
            
            $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
            $response['ResultCount'] = 1;
            $response['contentType'] = Engine_Api::_()->sdparentalguide()->mapSEResourceTypes($guideItem->getType());
            $response['Results'] = array();
            $response['Results'][] = $responseApi->getGuideItemData($guideItem);
            $this->respondWithSuccess($response);
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($e);
        }
        
    }
    
    public function deleteAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $level_id = !empty($viewer_id) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        $id = $this->getParam("guideItemID");
        $idsArray = (array)$id;
        if(is_string($id) && !empty($id)){
            $idsArray = array($id);
        }
        $guideItems = Engine_Api::_()->getItemMulti("sdparentalguide_guide_item",$idsArray);
        if (empty($guideItems)) {
            $this->respondWithError('no_record');
        }
        $table = Engine_Api::_()->getItemTable('sdparentalguide_guide_item');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try {
            foreach($guideItems as $guideItem){
                if(!$this->pggPermission('canDeleteGuide')){
                    $this->respondWithError('unauthorized');
                }
                $guideItem->gg_deleted = 1;
                $guideItem->save();
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithServerError($e);
        }
        $this->successResponseNoContent('no_content');
    }
}
