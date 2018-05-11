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
class Sitecredit_Widget_MyCreditsController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer)
            return $this->setNoRender();
   
        $param['user_id']=$viewer->getIdentity();
        $param['basedon']= 0;
        $param['count']= 1;
        $credits = Engine_Api::_()->getDbtable('credits','sitecredit')->Credits($param);
        $totalCredits= $credits->credit;
   
        $this->view->totalCredits=$totalCredits;
        $this->view->creditTypeArray = $GLOBALS['sitecredit_creditType'];
        $checkValidity=Engine_Api::_()->getDbtable('credits','sitecredit')->validityCheck();

        $validityTable=Engine_Api::_()->getDbTable('validities','sitecredit');
        $select=$validityTable->select()->where('user_id=?',$viewer->getIdentity());
        $validityuser= $validityTable->fetchRow($select);
        $sitecreditMyCredit = Zend_Registry::isRegistered('sitecreditMyCredit') ? Zend_Registry::get('sitecreditMyCredit') : null;
        if (empty($sitecreditMyCredit))
            return $this->setNoRender();
  
        if (empty($validityuser)) {  
            Engine_Api::_()->getDbtable('validities','sitecredit')->insertvalidity();
            $validityuser= $validityTable->fetchRow($select);
            $validityDate = date('Y-m-d', strtotime("+$checkValidity months", strtotime($validityuser->start_date)));
        }else {
            $validityDate = date('Y-m-d', strtotime("+$checkValidity months", strtotime($validityuser->start_date)));
            while($validityDate < date('Y-m-d h:m:s')) {
                Engine_Api::_()->getDbtable('validities','sitecredit')->updateValidity($validityDate);
                $validityuser= $validityTable->fetchRow($select);
                $validityDate = date('Y-m-d', strtotime("+$checkValidity months", strtotime($validityuser->start_date)));
            }
        }
        $this->view->validityDate=$validityDate;
       // calculate remaining days for validity if less than 60 days show message to user.
        $now = time(); // or your date as well
        $your_date = strtotime($validityDate);
        $datediff = $your_date-$now;
        $this->view->validityDays=$validityDays=floor($datediff / (60 * 60 * 24));  

        $this->view->result=$result=Engine_Api::_()->getDbtable('credits','sitecredit')->CreditsActivityType($param);
    } 

}
  

