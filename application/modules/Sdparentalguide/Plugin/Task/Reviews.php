<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_Reviews extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1,$job_user = null) {
        $usersTable = Engine_Api::_()->getDbtable("users","user");
        if($page == 1){
            $where = array();
            if(!empty($job_user)){
                $where['user_id = ?'] = $job_user;
            }
            $usersTable->update(array('gg_review_count' => 0),$where);
        }
        
        $listingTable = Engine_Api::_()->getDbtable("listings","sitereview");
        $select = $listingTable->select()->from($listingTable->info("name"),array("*",new Zend_Db_Expr("COUNT(listing_id) as gg_review_count")))
                ->where('approved = ?',1)
                ->group("owner_id");   
        if(!empty($job_user)){
            $select->where("owner_id = ?",$job_user);
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_task->per_page);
        if($paginator->count() < $paginator->getCurrentPageNumber()){
            return $paginator;
        }
        
        foreach($paginator as $listing){
            $usersTable->update(array('gg_review_count' => $listing->gg_review_count),array('user_id = ?' => $listing->owner_id));
        }
        
        return $paginator;
    }
}