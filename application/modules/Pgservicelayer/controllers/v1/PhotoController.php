<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_PhotoController extends Pgservicelayer_Controller_Action_Api
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
            else if($method == 'put'){
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
        $this->validateParams(array('photoID','page','limit'));
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $id = $this->getParam("photoID",'-1');
        if(empty($id)){
            $id = "-1";
        }
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $avatarPhoto = ucfirst($this->getParam("photoType",""));
        $table = Engine_Api::_()->getDbTable('files', 'pgservicelayer');
        $tableName = $table->info("name");
        $select = $table->select();
        if(is_string($id) && !empty($id)){
            $select->where("$tableName.file_id = ?",$id);
        }else if(is_array($id) && !empty ($id)){
            $select->where("$tableName.file_id IN (?)",$id);
        }
        $select->where("$tableName.gg_deleted = ?",0);
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        foreach($paginator as $photo){
            $photos = $responseApi->getContentImage($photo);
            $photoArray = array(
                'photoID' => (string)$photo->getIdentity(),
                'photoURL' => ''
            );
            $photoArray = array_merge($photoArray,$photos);
            ++$response['ResultCount'];
            $response['Results'][] = $photoArray;
        }
        $this->respondWithSuccess($response);
    }
    public function postAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        if (!$viewer->getIdentity()) {
            $this->respondWithError('unauthorized');
        }
        $image = imagecreatefromstring($this->getInputStream());
        if(empty($_FILES['Filedata']['tmp_name']) && $image === false){
            $this->respondWithValidationError('parameter_missing',$this->translate("Photo missing in Filedata."));
        }
        $avatarPhoto = ucfirst($this->getParam("photoType",""));
        $table = Engine_Api::_()->getDbTable('files', 'pgservicelayer');
        $db = $table->getDefaultAdapter();
        $db->beginTransaction();
        try{
            if(!empty($_FILES['Filedata'])){
                $image = $_FILES['Filedata'];
            }
            
            if(is_resource($image)){
                $image = $table->uploadImage($this->getInputStream());
                if(empty($image)){
                    $this->respondWithValidationError('parameter_missing',$this->translate("Photo missing in stream."));
                }
            }
            
            $photo = $table->setPhoto($image);
            if(empty($photo)){
                $db->rollBack();
                $this->respondWithError('file_not_uploaded');
            }
            $db->commit();
            
            $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
            $photos = $responseApi->getContentImage($photo);
            $photoArray = array(
                'photoID' => (string)$photo->getIdentity(),
                'photoURL' => ''
            );
            $photoArray = array_merge($photoArray,$photos);
            $this->respondWithSuccess($photoArray);
        } catch (Exception $ex) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        
    }
    public function putAction(){
        
    }
    public function deleteAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $level_id = !empty($viewer_id) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        $id = $this->getParam("photoID");
        $idsArray = (array)$id;
        if(is_string($id) && !empty($id)){
            $idsArray = array($id);
        }
        $storageFiles = Engine_Api::_()->getItemMulti("storage_file",$idsArray);
        if (empty($storageFiles)) {
            $this->respondWithError('no_record');
        }
        $table = Engine_Api::_()->getDbTable('files', 'pgservicelayer');
        $db = $table->getDefaultAdapter();
        $db->beginTransaction();
        try{
            foreach($storageFiles as $storageFile){
                if ($storageFile->user_id != $viewer_id) {
                    $this->respondWithError('unauthorized');
                }
                $storageFile->gg_deleted = 1;
                $storageFile->save();
                $children = $storageFile->getChildren('all');
                foreach($children as $child){
                    $child->gg_deleted = 1;
                    $child->save();
                }
            }
            $db->commit();
        } catch (Exception $ex) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        $this->successResponseNoContent('no_content');
    }
}
