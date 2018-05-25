<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_Reviews extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1) {
        $usersTable = Engine_Api::_()->getDbtable("users","user");
        if($page == 1){
            $usersTable->update(array('gg_reviews_count' => 0),array());
        }
        
        $listingTable = Engine_Api::_()->getDbtable("listings","sitereview");
        $select = $listingTable->select()->from($listingTable->info("name"),array("*",new Zend_Db_Expr("COUNT(listing_id) as gg_reviews_count")))
                ->where('approved = ?',1)
                ->group("owner_id");        
                
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_task->per_page);
        if($paginator->count() < $paginator->getCurrentPageNumber()){
            return $paginator;
        }
        
        foreach($paginator as $listing){
            $usersTable->update(array('gg_reviews_count' => $listing->gg_reviews_count),array('user_id = ?' => $listing->owner_id));
        }
        
        return $paginator;
    }
}