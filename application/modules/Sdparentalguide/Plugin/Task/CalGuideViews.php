<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_CalGuideViews extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1,$job_user = null) {
        $table = Engine_Api::_()->getDbtable("guides","sdparentalguide");
        if($page == 1){
            $where = array();
            if(!empty($job_user)){
                $where['owner_id = ?'] = $job_user;
            }
            $table->update(array('view_count' => 0),$where);
        }
        
        
        $select = $table->select();   
        if(!empty($job_user)){
            $select->where("owner_id = ?",$job_user);
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_task->per_page);
        if($paginator->count() < $paginator->getCurrentPageNumber()){
            return $paginator;
        }
        
        $viewsTable = Engine_Api::_()->getDbTable("views","pgservicelayer");
        foreach($paginator as $subject){
            $subject->view_count = $viewsTable->recalculate($subject,"view");
            $subject->save();
        }
        
        return $paginator;
    }
}