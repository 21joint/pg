<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_Badges extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1) {
        $usersTable = Engine_Api::_()->getDbtable("users","user");
        if($page == 1){
            $usersTable->update(array(
                'gg_expert_platinum_count' => 0,
                'gg_expert_gold_count' => 0,
                'gg_expert_silver_count' => 0,
                'gg_expert_bronze_count' => 0,
                'gg_platinum_count' => 0,
                'gg_gold_count' => 0,
                'gg_silver_count' => 0,
                'gg_bronze_count' => 0
            ),array());
        }
        
        $table = Engine_Api::_()->getDbtable('badges', 'sdparentalguide');
        $tableName = $table->info("name");

        $assignedTable = Engine_Api::_()->getDbtable('assignedBadges', 'sdparentalguide');
        $assignedTableName = $assignedTable->info("name");

        $select = $table->select()->setIntegrityCheck(false)->from($tableName)
                ->joinLeft($assignedTableName,"$assignedTableName.badge_id = $tableName.badge_id",array("$assignedTableName.active as assigned_active",new Zend_Db_Expr("$assignedTableName.user_id as assigned_user")))
                ->where("$assignedTableName.active = ?",1)
                ->where("$tableName.active = ?",1);
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_task->per_page);
        if($paginator->count() < $paginator->getCurrentPageNumber()){
            return $paginator;
        }
        
        foreach($paginator as $badge){
            $badge->updateUserCounts($badge->assigned_user);
        }
        
        return $paginator;
    }
}