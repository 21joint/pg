<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_Followers extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1) {
        $usersTable = Engine_Api::_()->getDbtable("users","user");
        if($page == 1){
            $usersTable->update(array('gg_followers_count' => 0));
            $usersTable->update(array('gg_following_count' => 0));
        }
        
        $membershipTable = Engine_Api::_()->getDbtable("membership","user");
        $select = $membershipTable->select()
                ->where('active = ?',1);
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_task->per_page);
        if($paginator->count() < $paginator->getCurrentPageNumber()){
            return $paginator;
        }
        
        foreach($paginator as $membership){
            $usersTable->update(array('gg_followers_count' => new Zend_Db_Expr("gg_followers_count + 1")),array('user_id = ?' => $membership->resource_id));
            $usersTable->update(array('gg_following_count' => new Zend_Db_Expr("gg_following_count + 1")),array('user_id = ?' => $membership->user_id));
        }
        
        return $paginator;
    }
}