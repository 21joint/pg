<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_TopicController extends Pgservicelayer_Controller_Action_Api
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
        $id = $this->getParam("topicID");
        $search = $this->getParam("topicName");
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",50);
        $table = Engine_Api::_()->getDbtable('topics', 'sdparentalguide');
        $tableName = $table->info("name");
        $select = $table->select();
        $select->order("topic_id DESC");
        $select->where('approved = ? ', 1);
        if(is_string($id) && !empty($id)){
            $select->where("$tableName.topic_id = ?",$id);
        }else if(is_array($id) && !empty ($id)){
            $select->where("$tableName.topic_id IN (?)",$id);
        }
        if(!empty($search)){
            $select->where("name LIKE ?","%".$search."%");
        }
        $select->where("$tableName.gg_deleted = ?",0);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $response['ResultCount'] = $paginator->getTotalItemCount();
        $response['Results'] = array();
        foreach($paginator as $topic){
            $response['Results'][] = $responseApi->getTopicData($topic);
        }
        $this->respondWithSuccess($response);
    }
    
    public function postAction(){
        
    }
    
    public function putAction(){
        
    }
    
    public function deleteAction(){
        
    }
}
