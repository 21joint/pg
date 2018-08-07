<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Pgservicelayer_Api_Core extends Core_Api_Abstract {
    public function getPermissions(User_Model_User $user){
        $responseApi = Engine_Api::_()->getApi("V1_Response","pgservicelayer");
        return $responseApi->getPermissionData($user);
    }
}