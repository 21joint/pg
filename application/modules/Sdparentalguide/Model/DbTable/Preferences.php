<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_DbTable_Preferences extends Engine_Db_Table
{
    protected $_rowClass = "Sdparentalguide_Model_Preference";
    protected $_name = 'gg_user_preferences';
    
    public function getUserCategories($user = null){
        if(empty($user)){
            $user = Engine_Api::_()->user()->getViewer();
        }
        
        $select = $this->select()
                ->from($this->info("name"),array('category_id'))
                ->where('user_id = ?',$user->getIdentity());
        $categories = $select->query()->fetchAll(Zend_Db::FETCH_COLUMN);
        if(empty($categories)){
            return array(0);
        }
        return $categories;
    }
} 




