<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_Credibility extends Core_Plugin_Task_Abstract
{
    public function execute() {
        $usersTable = Engine_Api::_()->getDbTable("users","user");
        $select = $usersTable->select()
                ->from($usersTable->info("name"))
                ->where("gg_contribution_updated = ?",0)->limit(50);
        $users = $usersTable->fetchAll($select);
        if(count($users) <= 0){
            $usersTable->update(array('gg_contribution_updated' => 0),array('gg_contribution_updated = ?' => 1));
            return;
        }
        
        $creditsTable = Engine_Api::_()->getDbtable('credits','sdparentalguide');
        foreach($users as $user){
            $row = $creditsTable->getUserActivityCount($user);
            $userCredits = 0;
            $userActivities = 0;
            if(!empty($row)){
                $userCredits = $row->credit;
                $userActivities = $row->activities;
            }
            $user->gg_contribution = $userCredits;
            $user->gg_activities = $userActivities;
            $user->gg_contribution_updated = 1;
            $user->save();
        }
    }
}