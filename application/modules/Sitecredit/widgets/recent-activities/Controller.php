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
class Sitecredit_Widget_RecentActivitiesController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
        
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer)
            return  $this->setNoRender();
        
        $user_id=$viewer->getIdentity();
        $this->view->language=Zend_Registry::get('Locale')->getLanguage();
        $param = array();
        $this->view->count=$param['user_id']=$user_id;
        
        if ($this->_getParam('countactivity', 5)<=10) {
            $this->view->count=$param['count']= $this->_getParam('countactivity', 5);
        }else {
            $this->view->count=$param['count']= 10;
        }
        
        $sitecreditRecentActivities = Zend_Registry::isRegistered('sitecreditRecentActivities') ? Zend_Registry::get('sitecreditRecentActivities') : null;
        if (empty($sitecreditRecentActivities))
            return $this->setNoRender();
        
        $creditTable=Engine_Api::_()->getDbtable('credits','sitecredit');
        $creditTableName=$creditTable->info('name');
        $activityTable=Engine_Api::_()->getDbtable('activitycredits','sitecredit');
        $activityTableName=$activityTable->info('name');

        $select=$activityTable->select()->setIntegrityCheck(false);

        $select->from($activityTableName)->join($creditTableName, $creditTableName . '.type_id = ' . $activityTableName . '.activitycredit_id',array($creditTableName . '.user_id',$creditTableName . '.creation_date',$creditTableName . '.type',$creditTableName . '.credit_point' ));
        $select->where($creditTableName .'.user_id = ?',$param['user_id'])->where($creditTableName .".type='activity_type'")->order($creditTableName .'.creation_date DESC')->limit($param['count']);
        $result=$activityTable->fetchAll($select);

        if (empty($result) || count($result)==0) {
            return $this->setNoRender();
        }
        $this->view->result=$result;
    }

}
