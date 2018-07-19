<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_Answers extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1,$job_user = null) {

        $usersTable = Engine_Api::_()->getDbtable("users","user");
        if($page == 1){
            $where = array();
            if(!empty($job_user)){
                $where['user_id = ?'] = $job_user;
            }
            $usersTable->update(array('gg_answer_count' => 0), $where);
        }
        
        $questionsTable = Engine_Api::_()->getDbtable("answers","ggcommunity");
        $select = $questionsTable->select()->from($questionsTable->info("name"),array("*",new Zend_Db_Expr("COUNT(answer_id) as gg_answer_count")))
                ->group("user_id");
        if(!empty($job_user)){
            $select->where("user_id = ?", $job_user);
        }

        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_task->per_page);
        if($paginator->count() < $paginator->getCurrentPageNumber() ){
            return $paginator;
        }
        
        foreach($paginator as $question){
            $usersTable->update(array('gg_answer_count' => $question->gg_answer_count),array('user_id = ?' => $question->user_id));
        }
        
        return $paginator;
    }
}