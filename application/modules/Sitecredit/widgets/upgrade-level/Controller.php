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
class Sitecredit_Widget_UpgradeLevelController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
    
        $this->view->viewer=$viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer)
            return  $this->setNoRender();
    
        $this->view->showlevel=$showlevel= $this->_getParam('showlevel', 1);

        $permissionTable = Engine_Api::_()->getDbtable('levels', 'sitecredit');
        $permissionTableName = $permissionTable->info('name');

        $this->view->superadmin=false;
        if ($viewer->getIdentity()==1) {
            $this->view->superadmin=true;
            $select=$permissionTable->select();
            $this->view->allLevels= $levels= $permissionTable->fetchAll($select);
        }
    
        $sitecreditUpgradeLevel = Zend_Registry::isRegistered('sitecreditUpgradeLevel') ? Zend_Registry::get('sitecreditUpgradeLevel') : null;
        if (empty($sitecreditUpgradeLevel))
            return $this->setNoRender();
        
        try {
            $param=Array();
            $param['user_id']=$viewer->getIdentity();
            $param['basedon']= 0;
            $this->view->currentCredits=$currentCredits =Engine_Api::_()->getDbtable('credits', 'sitecredit')->Credits($param)->credit;
      
            if (empty($currentCredits)) {
                return $this->setNoRender();
            }

            $select = $permissionTable->select()->from($permissionTableName , array("$permissionTableName.credit_point"));
            $select->where("$permissionTableName.level_id=?",$viewer->level_id);

            $result= $permissionTable->fetchRow($select)->credit_point;
            
            if ($result) {
                $select = $permissionTable->select()->where("$permissionTableName.level_id != ?",$viewer->level_id)->where("$permissionTableName.credit_point >= ?",(int)$result)->where("$permissionTableName.credit_point >0");
            }else {
                $select = $permissionTable->select()->where("$permissionTableName.level_id != ?",$viewer->level_id)->where("$permissionTableName.credit_point > 0");
            }
            
            $select->order('credit_point ASC');
            
            if ($showlevel) {
                $this->view->levels=$levels=$permissionTable->fetchRow($select);
                $this->view->level=Engine_Api::_()->getItem('authorization_level', $levels->level_id)->title;
            }else {
                $this->view->levels=$levels=$permissionTable->fetchAll($select);
            }
      
            if ($showlevel) {
                if ($currentCredits<$levels->credit_point) {
                    $this->view->needCredits = 1;
                    $this->view->creditNeeded=$levels->credit_point;
                }else {
                    $this->view->needCredits = 0;
                    $this->view->level_id = $levels->level_id;
                    $this->view->creditNeeded=$levels->credit_point;
                }
            }

            $table=Engine_Api::_()->getDbtable('upgraderequests','sitecredit');
            $select=$table->select()->where('user_id =?',$viewer->getIdentity())->where('status=?','pending');
            $this->view->result=$result=$table->fetchRow($select);

        }catch(Exception $e) {
            throw $e;
        }

    }

}

