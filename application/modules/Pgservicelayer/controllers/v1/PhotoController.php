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
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $id = $this->getParam("photoID");   
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $avatarPhoto = ucfirst($this->getParam("avatarPhoto","icon"));
        $table = Engine_Api::_()->getDbTable('files', 'pgservicelayer');
        $tableName = $table->info("name");
        $select = $table->select()->where('parent_file_id IS NULL');
        if(is_string($id) && !empty($id)){
            $select->where("$tableName.file_id = ?",$id);
        }else if(is_array($id) && !empty ($id)){
            $select->where("$tableName.file_id IN (?)",$id);
        }
        $select->where("$tableName.gg_deleted = ?",0);
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $response['ResultCount'] = $paginator->getTotalItemCount();
        $response['Results'] = array();
        foreach($paginator as $photo){
            $photos = $responseApi->getContentImage($photo);
            $photoArray = array(
                'photoID' => (string)$photo->getIdentity(),
                'photoURL' => ''
            );
            $photoArray['photoURL'] = isset($photos['photoURL'.$avatarPhoto])?$photos['photoURL'.$avatarPhoto]:$photos['photoURLIcon'];
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
        $avatarPhoto = ucfirst($this->getParam("avatarPhoto","icon"));
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
            $photoArray['photoURL'] = isset($photos['photoURL'.$avatarPhoto])?$photos['photoURL'.$avatarPhoto]:$photos['photoURLIcon'];
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
                $children = $storageFile->getChildren();
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