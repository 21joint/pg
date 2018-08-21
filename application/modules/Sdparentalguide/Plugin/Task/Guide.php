<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_Guide extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1,$job_user = null) {
        $usersTable = Engine_Api::_()->getDbtable("users","user");
        if($page == 1){
            $where = array();
            if(!empty($job_user)){
                $where['user_id = ?'] = $job_user;
            }
            $usersTable->update(array('gg_guide_count' => 0),$where);
        }
        
        $guidesTable = Engine_Api::_()->getDbtable("guides","sdparentalguide");
        $select = $guidesTable->select()->from($guidesTable->info("name"),array("*",new Zend_Db_Expr("COUNT(guide_id) as gg_guide_count")))
                ->where('approved = ?',1)->where("draft = ?",0)->group("owner_id");
        if(!empty($job_user)){
            $select->where("owner_id = ?",$job_user);
        }
                
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_task->per_page);
        if($paginator->count() < $paginator->getCurrentPageNumber()){
            return $paginator;
        }
                
        foreach($paginator as $guide){
            $usersTable->update(array('gg_guide_count' => $guide->gg_guide_count),array('user_id = ?' => $guide->owner_id));
        }
        
        return $paginator;
    }
}