<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_ContributionLevel extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1) {
        $paginator = Zend_Paginator::factory(array());
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->_task->per_page);
        return $paginator;
    }
}