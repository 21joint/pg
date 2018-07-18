<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_Contribution extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1,$job_user = null) {
        $usersTable = Engine_Api::_()->getDbtable("users","user");
        if($page == 1){
            $where = array();
            if(!empty($job_user)){
                $where['user_id = ?'] = $job_user;
            }
            $usersTable->update(array('gg_contribution' => 0),$where);
        }
        
        $select = $usersTable->select()
                ->from($usersTable->info("name"));
        if(!empty($job_user)){
            $select->where("user_id = ?",$job_user);
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_task->per_page);
        if($paginator->count() < $paginator->getCurrentPageNumber()){
            return $paginator;
        }
        
        $creditsTable = Engine_Api::_()->getDbtable('credits','sdparentalguide');
        foreach($paginator as $user){
            $row = $creditsTable->getUserActivityCount($user);
            $userCredits = 0;
            $userActivities = 0;
            if(!empty($row)){
                $userCredits = $row->credit;
                $userActivities = $row->activities;
            }
            $user->gg_contribution = $userCredits;
            $user->gg_activities = $userActivities;
            $user->save();
        }
        
        return $paginator;
    }
}