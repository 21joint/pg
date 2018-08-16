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
    public function updateUserCount($data,$user_id){
        $usersTable = Engine_Api::_()->getDbTable("users","user");
        $db = Engine_Db_Table::getDefaultAdapter();
        $viewer = Engine_Api::_()->user()->getViewer();
        $ipObj = new Engine_IP();
        $ipExpr = new Zend_Db_Expr($db->quoteInto('UNHEX(?)', bin2hex($ipObj->toBinary())));
        $data['gg_ip_lastmodified'] = $ipExpr;
        $data['gg_guid'] = 'user_'.$user_id;
        $params['gg_user_lastmodified'] = $viewer->getIdentity();
        $params['gg_dt_lastmodified'] = date("Y-m-d H:i:s");
        $usersTable->update($data,array('user_id = ?' => $user_id));
    }
    
    public function getInfluencers($user = null){
        if(empty($user)){
            $user = Engine_Api::_()->user()->getViewer();
        }
        $table = Engine_Api::_()->getDbTable("membership","user");
        $select = $table->select()
                ->from($table->info("name"),array('resource_id'))
                ->where('user_id = ?',$user->getIdentity())
                ->where('active = ?',1);
        $users = $select->query()->fetchAll(Zend_Db::FETCH_COLUMN);
        if(empty($users)){
            return array(0);
        }
        return $users;
    }
}