<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Plugin_Task_Topic extends Core_Plugin_Task_Abstract
{
    public function execute() {
        Engine_Api::_()->sdparentalguide()->synchronizeTopics();
        Engine_Api::_()->sdparentalguide()->synchronizeListings();
    }
}