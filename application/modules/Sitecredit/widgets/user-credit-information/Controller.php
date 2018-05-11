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

class Sitecredit_Widget_UserCreditInformationController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {

        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer)
            return  $this->setNoRender();  
         $param=array();
        $this->view->showBalance = $this->_getParam('showBalance', 0);
        $this->view->showRank = $this->_getParam('showRank', 0);
        $this->view->showNextRank = $this->_getParam('showNextRank', 0);
        $this->view->showlimit = $this->_getParam('showlimit', 0);
   

        if (!($this->_getParam('showBalance', 0)) && !($this->_getParam('showRank', 0)) && !($this->_getParam('showNextRank', 0)) && !($this->_getParam('showlimit', 0))) {
            return  $this->setNoRender();
        }
        $this->view->allowBadge=true;
        if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.badge.enable', 0))
           $this->view->allowBadge=false;

        $param['user_id']=$viewer->getIdentity();
        $param['basedon']= Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.ranking', 0);
        $param['count']= 1;
        
        $credits = Engine_Api::_()->getDbtable('credits','sitecredit')->Credits($param);
        if (empty($credits->credit)) {
            return $this->setNoRender(); 
        }
        $this->view->credits= $credits->credit;
        $this->view->result=$result = Engine_Api::_()->getDbtable('Badges','sitecredit')->getBadge($param);

        $sitecreditUserCredit = Zend_Registry::isRegistered('sitecreditUserCredit') ? Zend_Registry::get('sitecreditUserCredit') : null;
        if (empty($sitecreditUserCredit))
            return $this->setNoRender();


      // credit on the basis of which badge assighned use 
  
      // max credit get from next rank;
        $this->view->nextRank=$nextBadge=Engine_Api::_()->getDbtable('Badges','sitecredit')->getNextBadge($param);
  
        $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
        $creditLevelLimit=$permissionsTable->getAllowed('sitecredit_credit', $viewer->level_id,'max_perday');
        $this->view->creditLevelLimit=$creditLimit=$creditLevelLimit;
        $creditEarnedDay=Engine_Api::_()->getDbtable('credits','sitecredit')->CreditEarnDay($param)->credit;
   
        if (empty($creditLimit)) {
            $this->view->nolimit=1;
            $this->view->creditLimit=0;
            if (!empty($creditEarnedDay)) {
                $this->view->creditEarnedDay=$creditEarnedDay;
                $this->view->creditDifference=0;
            }else {
                $this->view->creditEarnedDay=$creditEarnedDay=0;
                $this->view->creditDifference=0;    
            }
        }else {
            $this->view->nolimit=0;
            $this->view->creditLimit=$creditLimit;
            if (!empty($creditEarnedDay)) {
                $this->view->creditEarnedDay=$creditEarnedDay;
                $this->view->creditDifference=$creditLimit-$creditEarnedDay;
            }else {
                $this->view->creditEarnedDay=$creditEarnedDay=0;
                $this->view->creditDifference=$creditLimit;
            }
        }
    }

}

