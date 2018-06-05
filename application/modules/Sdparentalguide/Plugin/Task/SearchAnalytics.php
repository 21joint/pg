<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_SearchAnalytics extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1) {
        $analyticsTable = Engine_Api::_()->getDbtable('searchAnalytics', 'sdparentalguide');
        if($page == 1){
            $db = $analyticsTable->getDefaultAdapter();
            $db->query("TRUNCATE TABLE engine4_gg_search_analytics");
        }
        
        $table = Engine_Api::_()->getDbtable('search', 'sdparentalguide');
        $tableName = $table->info("name");

        $select = $table->select()
                    ->from($tableName, array('COUNT(search_activity_id) as count', 'search_text as search_term'))
                    ->group("search_text")
                    ;
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_task->per_page);
        if($paginator->count() < $paginator->getCurrentPageNumber()){
            return $paginator;
        }
        
        foreach($paginator as $searchActivity){
            $analyticsValues['search_term'] = $searchActivity['search_term'];
            $analyticsValues['count'] = $searchActivity['count'];
            $analyticsTerm = $analyticsTable->createRow();
            $analyticsTerm->setFromArray($analyticsValues);
            $analyticsTerm->save();
        }
        
        return $paginator;
    }
}