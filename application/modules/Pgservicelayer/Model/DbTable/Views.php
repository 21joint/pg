<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */
class Pgservicelayer_Model_DbTable_Views extends Engine_Db_Table {
    protected $_name = 'gg_views';
    protected $_viewRow = null;
    public function getView($subject,$actionType = "click"){
        if($this->_viewRow != null){
            return $this->_viewRow;
        }
        $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $select = $this->select()->where('conent_type = ?',$subject->getType())->where("owner_id = ?",(int)$user_id)
                ->where('content_id = ?',$subject->getIdentity())->where('action_type = ?',strtolower($actionType));
        $this->_viewRow = $this->fetchRow($select);
        return $this->_viewRow;
    }
    public function hasView($subject,$actionType = "click"){
        if($this->getView($subject, $actionType)){
            return true;
        }else{
            return false;
        }
    }
    public function addView($subject,$actionType = "click"){
//        if($this->hasView($subject,$actionType)){
//            return $this->getView($subject, $actionType);
//        }
        $row = $this->createRow();
        $row->action_type = strtolower($actionType);
        $row->content_id = $subject->getIdentity();
        $row->conent_type = $subject->getType();
        $row->owner_id = (int)Engine_Api::_()->user()->getViewer()->getIdentity();
        $row->creation_date = date("Y-m-d H:i:s");
        $row->save();
        
        if(strtolower($actionType) == "click"){
            $subject->click_count++;
        }else{
            $subject->view_count++;
        }
        $subject->save();
        
        $this->_viewRow = $row;
        return $this->_viewRow;
    }
    public function recalculate($subject,$actionType = "click"){
        $select = $this->select()->where('conent_type = ?',$subject->getType())
                ->where('content_id = ?',$subject->getIdentity())->where('action_type = ?',strtolower($actionType));
        $paginator = Zend_Paginator::factory($select);
        return $paginator->getTotalItemCount();
    }
}
