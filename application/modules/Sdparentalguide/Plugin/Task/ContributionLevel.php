<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_ContributionLevel extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1,$job_user = null) {
        $usersTable = Engine_Api::_()->getDbtable("users","user");
        if($page == 1){
            $where = array();
            if(!empty($job_user)){
                $where['user_id = ?'] = $job_user;
            }
            $usersTable->update(array('gg_contribution_level' => 0),$where);
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
        
        $api = Engine_Api::_()->sdparentalguide();
        foreach($paginator as $user){
            $badge = $api->getUserBadge($user->gg_contribution);
            if(empty($badge)){
                continue;
            }
            $user->gg_contribution_level = $badge->gg_contribution_level;
            if(!$user->isAdminOnly() && $badge->gg_level_id > 0){
                $user->level_id = $badge->gg_level_id;
            }            
            $user->save();
        }
        
        return $paginator;
    }
}