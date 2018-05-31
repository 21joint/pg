<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Sdparentalguide_Api_Core extends Core_Api_Abstract{
    public function getUserCredits($user = null){
        if(empty($user)){
            $user = Engine_Api::_()->user()->getViewer();
        }
        if(!$user->getIdentity()){
            return null;
        }
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $param['user_id'] = $user->getIdentity();
        $param['basedon'] = $settings->getSetting('credit.ranking', 0);
        $param['count'] = 1;
        $credits = Engine_Api::_()->getDbtable('credits','sitecredit')->Credits($param);
        if (empty($credits->credit)) {
            return 0;
        }
        return $credits->credit;
    }
    public function getUserCredibility($user = null){
        $score = $this->getUserCredibility($user);
        if(empty($score)){
            $score = 0;
        }
        $view = Zend_Registry::get("Zend_View");
        return $view->translate(array('Credibility Score %s', 'Credibility Score %s', $score),$this->locale()->toNumber($score));
    }
    public function getFieldValue(User_Model_User $user,$field_id = 1){
      $valuesTable = Engine_Api::_()->fields()->getTable('user', 'values');
      $profile_type = $valuesTable->select()
              ->from($valuesTable->info('name'),'value')
              ->where('field_id = ?',$field_id)
              ->where('item_id = ?',$user->getIdentity())
              ->query()
              ->fetchColumn();
       return $profile_type;
    }
    public function getProfileName(User_Model_User $user){
      $optionsTable = Engine_Api::_()->fields()->getTable('user', 'options');
      $profile_type = $this->getFieldValue($user);
      $profileName = "";
      if(!empty($profile_type)){
          $profileName = $optionsTable->select()
                  ->from($optionsTable->info('name'),'label')
                  ->where('option_id = ?',$profile_type)
                  ->query()
                  ->fetchColumn();
      }
      return $profileName;
    }
    public function getUserBadge($credit){
        $table = Engine_Api::_()->getDbtable('Badges','sitecredit');
        $select = $table->select();
        $select->where("credit_count <= ?",(int)$credit)->order("credit_count DESC")->limit(1);
        return $table->fetchRow($select);
    }
    public function getFirstName(User_Model_User $user){
        return $this->getFieldValue($user,3);
    }
    public function getLastName(User_Model_User $user){
        return $this->getFieldValue($user,4);
    }
    public function getOptionLabel($option_id){
        if(empty($option_id)){
            return null;
        }
        $optionsTable = Engine_Api::_()->fields()->getTable("user","options");
        return $optionsTable->select()
                ->from($optionsTable->info("name"),array('label'))
                ->where('option_id = ?',$option_id)
                ->query()->fetchColumn();
    }
    public function getOptionValue($field_id,$item){
        $option_id = $this->getFieldValue($field_id, $item);
        if(empty($option_id)){
            return null;
        }
        return $this->getOptionLabel($option_id);        
    }
    
    public function getFieldChilds($field_id,$option_id = null){
        if(empty($field_id)){
            return null;
        }
        $valuesTable = Engine_Api::_()->fields()->getTable("user","maps");
        $select = $valuesTable->select()
                ->from($valuesTable->info("name"),array('child_id'))
                ->where('field_id = ?',$field_id)
                ;
        if(!empty($option_id)){
            $select->where("option_id = ?",$option_id);
        }
        return $select->query()->fetchAll(Zend_Db::FETCH_COLUMN);
    }
    public function getFieldOptions($field_id,$multiOptions = true){
        $optionsTable = Engine_Api::_()->fields()->getTable("user","options");
        $select = $optionsTable->select();
        if(is_array($field_id)){
            $select->where('field_id IN (?)',$field_id);
        }else{
            $select->where('field_id = ?',$field_id);
        }
        $fieldOptions = $optionsTable->fetchAll($select);
        if(!$multiOptions){
            return $fieldOptions;
        }
        $multiOptionsData = array('' => 'Select Sector');
        foreach($fieldOptions as $option){
            $multiOptionsData[$option->option_id] = $option->label;
        }
        return $multiOptionsData;
    }
    public function getBadgeLevels(){
        return array(
//            '1' => 'Expert',
            '2' => 'Platinum',
            '3' => 'Gold',
            '4' => 'Silver',
            '5' => 'Bronze'
        );
    }
    
    public function getBadgeTypes(){
        return array(
            '1' => 'Special',
            '2' => 'Expert',
            '3' => 'Contribution',
        );
    }
    
    public function getListingPhotos(Sitereview_Model_Listing $listing){
        $album = $listing->getSingletonAlbum();        
        $photosPaginator = $album->getCollectiblesPaginator();
        return $photosPaginator;
    }
    
    public function getListingTypesArray(){
        $listingTypeTable = Engine_Api::_()->getDbTable('listingtypes', 'sitereview');
        $listingTypeTableName = $listingTypeTable->info('name');
        $select = $listingTypeTable->select()->from($listingTypeTableName, array('title_plural', 'listingtype_id'))->where('visible = ?',1)->order("order ASC")->order("listingtype_id ASC");
        
        $listingTypeDatas = $listingTypeTable->fetchAll($select)->toArray();
        $listingTypes = array();
        foreach ($listingTypeDatas as $key) {
          $listingTypes[$key['listingtype_id']] = $key['title_plural'];
        }

        return $listingTypes;
    }
}
