<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_Contribution extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1) {
        $usersTable = Engine_Api::_()->getDbtable("users","user");
        if($page == 1){
            $usersTable->update(array('gg_contribution' => 0),array());
        }
        
        $select = $usersTable->select()
                ->from($usersTable->info("name"));
        
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