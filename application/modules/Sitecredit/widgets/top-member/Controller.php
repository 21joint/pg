<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Widget_TopMemberController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
        $this->view->friendrequest=$this->_getParam('showFriendRequest',1);
        $param=Array();
        $this->view->basedon = $param['basedon']= $this->_getParam('topmember', 'activities');
    
        if ($this->_getParam('count', 5)<=10) {
            $param['count']=$this->_getParam('count',5); 
        }else {
            $param['count']=10; 
        }
        $sitecreditTopMember = Zend_Registry::isRegistered('sitecreditTopMember') ? Zend_Registry::get('sitecreditTopMember') : null;
        if (empty($sitecreditTopMember))
            return $this->setNoRender();
        if (empty($param['count'])) 
            $param['count']=5;
        $this->view->rawdata= $rawdata=Engine_Api::_()->getDbtable('credits','sitecredit')->activitiesPerformed($param);
        if (count($rawdata)==0) {
            return $this->setNoRender();
        }
    }
}
