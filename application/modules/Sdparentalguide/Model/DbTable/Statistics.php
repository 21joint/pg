<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_DbTable_Statistics extends Engine_Db_Table
{
    protected $_rowClass = "Sdparentalguide_Model_Statistic";
    protected $_name = 'gg_site_activities';
    public function logStatistics($view = null){
        if(empty($view)){
            $view = Zend_Registry::get('Zend_View');
        }
        $url = $view->url();
        $viewer = Engine_Api::_()->user()->getViewer(); 
        $siteActivity = $this->createRow();
        $siteActivity->url = $url;
//        $siteActivity->title = $this->getPageTitle($view);
        $siteActivity->is_member = $viewer->getIdentity()?1:0;
        $siteActivity->userID = $viewer->getIdentity()?$viewer->getIdentity():0;
        $siteActivity->save();
    }
    
    public function getPageTitle($view = null){
        if(empty($view)){
            $view = Zend_Registry::get('Zend_View');
        }
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if (isset($view->layout()->siteinfo['identity'])) {
            $identity = $view->layout()->siteinfo['identity'];
        } else {
            $identity = $request->getModuleName() . '-' .
                $request->getControllerName() . '-' .
                $request->getActionName();
        }
        
        $pageTitleKey = 'pagetitle-' . $request->getModuleName() . '-' . $request->getActionName()
        . '-' . $request->getControllerName();
        $pageTitle = $view->translate(strtoupper($pageTitleKey));
        if(Engine_Api::_()->core()->hasSubject()){
            return Engine_Api::_()->core()->getSubject()->getTitle();
        }
        
        if(!empty($pageTitle)){
            return $pageTitle;
        }
    }
} 




