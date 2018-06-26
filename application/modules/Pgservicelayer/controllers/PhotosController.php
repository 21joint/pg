<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_PhotosController extends Pgservicelayer_Controller_Action_Api
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
            echo $ex->getMessage();exit;
            $this->respondWithServerError($ex);
        }
    }
    public function getAction(){
        $response['ResultCount'] = 0;
        $response['Results'] = array();
        $this->respondWithSuccess($response);
    }
    public function postAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        if (!$viewer->getIdentity()) {
//            $this->respondWithError('unauthorized');
        }
        if(empty($_FILES['Filedata']['tmp_name'])){
            $this->respondWithValidationError('parameter_missing',$this->translate("Photo missing in Filedata."));
        }
        $table = Engine_Api::_()->getDbTable('files', 'pgservicelayer');
        $db = $table->getDefaultAdapter();
        $db->beginTransaction();
        try{
            $photo = $table->setPhoto($_FILES['Filedata']);
            if(empty($photo)){
                $db->rollBack();
                $this->respondWithError('file_not_uploaded');
            }
            $db->commit();
        } catch (Exception $ex) {
            $db->rollBack();
            $this->respondWithServerError($ex);
        }
        $responseApi = Engine_Api::_()->getApi("response","pgservicelayer");
        $contentImages = $responseApi->getContentImage($photo);
        $photoArray = array(
            'photoID' => (string)$photo->getIdentity(),
            'photoURL' => ''
        );
        $photoArray = array_merge($photoArray,$contentImages);
        $this->respondWithSuccess($photoArray);
    }
    public function putAction(){
        
    }
    public function deleteAction(){
        
    }
}
