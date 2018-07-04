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
    
    public function getHost() {
        return _ENGINE_SSL ? 'https://' . $_SERVER['HTTP_HOST'] : 'http://' . $_SERVER['HTTP_HOST'];
    }

    /**
     * Remove restricted fields from user array in response.
     * 
     * @param type $user: SocialEngine user array
     * @return array
     */
    public function validateUserArray(User_Model_User $user, $ignoreParams = array()) {
        try {
            $restrictedFields = array('email', 'password', 'salt', 'creation_ip', 'lastlogin_ip');
            $userArray = $user->toArray();
            $userArray['displayname'] = $user->getTitle(false);
            foreach ($restrictedFields as $restrictedValue) {
                if (!in_array($restrictedValue, $ignoreParams))
                    unset($userArray[$restrictedValue]);
            }

            if (isset($user->language) && ($user->language == 'English'))
                $user->language = 'en';

            if (isset($user->local) && ($user->local == 'English'))
                $user->local = 'en';

            return $userArray;
        } catch (Exception $ex) {
            // Blank Exception
        }
    }

    /**
     * Getting the content URL
     * 
     * @param type $subject: Object of content
     * @return array
     */
    public function getContentURL($subject) {

        $url = array();
        try {
            if (!empty($subject)) {
                $getHref = $subject->getHref();
                if (!empty($getHref)) {
                    $host = $this->getHost();
                    $url['content_url'] = !empty($getHref) ? $host . $getHref : '';
                }
            }
        } catch (Exception $ex) {
            // Blank Exception
        }

        return $url;
    }

    /**
     * Getting the all type(main, icon, normal and profile) of image urls.
     * 
     * @param type $subject: Object of content
     * @param type $getOwnerImage: Need Object Owner images
     * @param type $key: Need to modify response key value
     * @return array
     */
    public function getContentImage($subject, $getOwnerImage = false, $key = false) {
        if (!isset($subject) || empty($subject))
            return;
        $getParentHost = $this->getHost();
        $baseParentUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $baseParentUrl = @trim($baseParentUrl, "/");
        $staticBaseUrl = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.static.baseurl', null);

        // Check IF default service "Local Storage" or not.
        $getDefaultStorageId = Engine_Api::_()->getDbtable('services', 'storage')->getDefaultServiceIdentity();
        $getDefaultStorageType = Engine_Api::_()->getDbtable('services', 'storage')->getService($getDefaultStorageId)->getType();
        $host = '';
        if ($getDefaultStorageType == 'local')
            $host = !empty($staticBaseUrl) ? $staticBaseUrl : $this->getHost();

        $type = (empty($getOwnerImage)) ? $subject->getType() : $subject->getOwner()->getType();
        $images = array();
        if (empty($getOwnerImage)) { // Getting content images
            // If image url already contains http://
            if (strstr($subject->getPhotoUrl('thumb.main'), 'http://') || strstr($subject->getPhotoUrl('thumb.main'), 'https://'))
                $host = '';

            $tempKey = empty($key) ? 'image' : $key . '_image';
            $images[$tempKey] = (($thumbMain = $subject->getPhotoUrl('thumb.main')) && !empty($thumbMain)) ? (!strstr($thumbMain, "application/modules")) ? $host . $subject->getPhotoUrl('thumb.main') : $this->getDefaultImage($type, 'main') : $this->getDefaultImage($type, 'main');
            if (!strstr($images[$tempKey], 'http'))
                $images[$tempKey] = $getParentHost . DIRECTORY_SEPARATOR . $baseParentUrl . $images[$tempKey];

            $images[$tempKey . '_normal'] = (($thubNormal = $subject->getPhotoUrl('thumb.normal')) && !empty($thubNormal)) ? (!strstr($thubNormal, "application/modules")) ? $host . $subject->getPhotoUrl('thumb.normal') : $this->getDefaultImage($type, 'normal') : $this->getDefaultImage($type, 'normal');
            if (!strstr($images[$tempKey . '_normal'], 'http'))
                $images[$tempKey . '_normal'] = $getParentHost . DIRECTORY_SEPARATOR . $baseParentUrl . $images[$tempKey . '_normal'];

            $images[$tempKey . '_profile'] = (($thumbProfile = $subject->getPhotoUrl('thumb.profile')) && !empty($thumbProfile)) ? (!strstr($thubNormal, "application/modules")) ? $host . $subject->getPhotoUrl('thumb.profile') : $this->getDefaultImage($type, 'profile') : $this->getDefaultImage($type, 'profile');
            if (!strstr($images[$tempKey . '_profile'], 'http'))
                $images[$tempKey . '_profile'] = $getParentHost . DIRECTORY_SEPARATOR . $baseParentUrl . $images[$tempKey . '_profile'];

            $images[$tempKey . '_icon'] = (($thumbIcon = $subject->getPhotoUrl('thumb.icon')) && !empty($thumbIcon)) ? (!strstr($thubNormal, "application/modules")) ? $host . $subject->getPhotoUrl('thumb.icon') : $this->getDefaultImage($type, 'icon') : $this->getDefaultImage($type, 'icon');
            if (!strstr($images[$tempKey . '_icon'], 'http'))
                $images[$tempKey . '_icon'] = $getParentHost . DIRECTORY_SEPARATOR . $baseParentUrl . $images[$tempKey . '_icon'];

            // Add content url
            $contentURL = $this->getContentURL($subject);
            $contentCoverImage = null;
            $images = array_merge($images, $contentURL);
            if (isset($contentCoverImage) && !empty($contentCoverImage))
                $images = array_merge($images, $contentCoverImage);
        } else { // Getting owner images
            if (strstr($subject->getOwner()->getPhotoUrl('thumb.main'), 'http://') || strstr($subject->getOwner()->getPhotoUrl('thumb.main'), 'https://'))
                $host = '';

            $tempKey = empty($key) ? 'owner_image' : $key . '_owner_image';
            $images[$tempKey] = ($subject->getOwner()->getPhotoUrl('thumb.main')) ? $host . $subject->getOwner()->getPhotoUrl('thumb.main') : $this->getDefaultImage($type, 'main');
            if (!strstr($images[$tempKey], 'http'))
                $images[$tempKey] = $getParentHost . DIRECTORY_SEPARATOR . $baseParentUrl . $images[$tempKey];

            $images[$tempKey . '_normal'] = ($subject->getOwner()->getPhotoUrl('thumb.normal')) ? $host . $subject->getOwner()->getPhotoUrl('thumb.normal') : $this->getDefaultImage($type, 'normal');
            if (!strstr($images[$tempKey . '_normal'], 'http'))
                $images[$tempKey . '_normal'] = $getParentHost . DIRECTORY_SEPARATOR . $baseParentUrl . $images[$tempKey . '_normal'];

            $images[$tempKey . '_profile'] = ($subject->getOwner()->getPhotoUrl('thumb.profile')) ? $host . $subject->getOwner()->getPhotoUrl('thumb.profile') : $this->getDefaultImage($type, 'profile');
            if (!strstr($images[$tempKey . '_profile'], 'http'))
                $images[$tempKey . '_profile'] = $getParentHost . DIRECTORY_SEPARATOR . $baseParentUrl . $images[$tempKey . '_profile'];

            $images[$tempKey . '_icon'] = ($subject->getOwner()->getPhotoUrl('thumb.icon')) ? $host . $subject->getOwner()->getPhotoUrl('thumb.icon') : $this->getDefaultImage($type, 'icon');
            if (!strstr($images[$tempKey . '_icon'], 'http'))
                $images[$tempKey . '_icon'] = $getParentHost . DIRECTORY_SEPARATOR . $baseParentUrl . $images[$tempKey . '_icon'];
        }

        return $images;
    }

    /**
     * Getting the default images url
     * 
     * @param type $module: Module name
     * @param type $type: Image type
     * @return string
     */
    public function getDefaultImage($module, $type = 'icon') {
        $getHost = $this->getHost();
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $baseUrl = @trim($baseUrl, "/");
        switch ($module) {
            case "album_photo":
            case "group_photo":
            case "event_photo":
                return '';
                break;

            case "user":
                $path = '/application/modules/User/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_user_thumb_icon.png';
                else
                    $imageName = 'nophoto_user_thumb_profile.png';
                break;

            case "classified":
                $path = '/application/modules/Classified/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_classified_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_classified_thumb_normal.png';
                else
                    $imageName = 'nophoto_classified_thumb_profile.png';
                break;

            case "sitestoreproduct_category":
                $path = '/application/modules/Sitestoreproduct/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_product_caregory.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_product_caregory.png';
                else
                    $imageName = 'nophoto_product_caregory.png';
                break;

            case "sitestoreproduct_wishlist":
                $path = '/application/modules/Sitestoreproduct/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_wishlist_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_wishlist_thumb_normal.png';
                else
                    $imageName = 'nophoto_wishlist_thumb_profile.png';
                break;

            case "sitestoreproduct_product":
                $path = '/application/modules/Sitestoreproduct/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_product_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_product_thumb_normal.png';
                else
                    $imageName = 'nophoto_product_thumb_profile.png';
                break;

            case "sitestore_store":
                $path = '/application/modules/Sitestore/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_store_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_store_thumb_normal.png';
                else
                    $imageName = 'nophoto_store_thumb_profile.png';
                break;

            case "sitestore_album":
                $path = '/application/modules/Sitestore/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_album_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_album_thumb_normal.png';
                else
                    $imageName = 'nophoto_album_thumb_normal.png';
                break;

            case "group":
                $path = '/application/modules/Group/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_group_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_group_thumb_normal.png';
                else
                    $imageName = 'nophoto_group_thumb_profile.png';
                break;

            case "event":
                $path = '/application/modules/Event/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_event_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_event_thumb_normal.png';
                else
                    $imageName = 'nophoto_event_thumb_profile.png';
                break;
            case "siteevent_event":
                $path = '/application/modules/Siteevent/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_event_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_event_thumb_normal.png';
                else
                    $imageName = 'nophoto_event_thumb_profile.png';
                break;
            case "siteevent_organizer":
                $path = '/application/modules/Siteevent/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_organizer_thumb_icon.png';
                else
                    $imageName = 'nophoto_organizer_thumb_profile.png';
                break;
            case "siteevent_diary":
                $path = '/application/modules/Siteevent/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_diary_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_diary_thumb_normal.png';
                else
                    $imageName = 'nophoto_diary_thumb_profile.png';
                break;
            case "siteevent_category":
                $path = '/application/modules/Siteevent/externals/images/';
                $imageName = 'nophoto_event_caregory.png';
                break;
            case "siteevent_organizer":
                $path = '/application/modules/Siteevent/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_organizer_thumb_icon.png';
                else
                    $imageName = 'nophoto_organizer_thumb_profile.png';
                break;

            case "album":
                $path = '/application/modules/Album/externals/images/';
                $imageName = 'nophoto_album_thumb_normal.png';
                break;

            case "forum":
                $path = '/application/modules/Forum/externals/images/';
                $imageName = 'forum.png';
                break;

            case "video":
                $path = 'application/modules/Video/externals/images/';
                if ($type == 'icon'){
                    $imageName = 'nophoto_video_thumb_icon.png';
                }else{
                    $imageName = 'nophoto_video_thumb_normal.png';
                }
                break;

            case "siteevent_video":
                $path = '/application/modules/Siteevent/externals/images/';
                $imageName = 'video.png';
                break;

            case "music_playlist":
                $path = '/application/modules/Music/externals/images/';
                $imageName = 'nophoto_playlist_main.png';
                break;
            case "music_playlist_song":
                $path = '/application/modules/Music/externals/images/';
                $imageName = 'nophoto_playlist_song_thumb_main.png';
                break;
            
            case "forum_post":
                $path = '/application/modules/Forum/externals/images/';
                $imageName = 'nophoto_post_thumb_icon.png';
                break;

            case "forum_forum":
                $path = '/application/modules/Forum/externals/images/';
                $imageName = 'nophoto_forum_thumb_icon.png';
                break;

            case "forum_topic":
                $path = '/application/modules/Forum/externals/images/';
                $imageName = 'nophoto_topic_thumb_icon.png';
                break;
            case "sitereview_listing":
                $path = '/application/modules/Sitereview/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_listing_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_listing_thumb_normal.png';
                else
                    $imageName = 'nophoto_listing_thumb_profile.png';
                break;
            case "sitereview_wishlist":
                $path = '/application/modules/Sitereview/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_wishlist_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_wishlist_thumb_normal.png';
                else
                    $imageName = 'nophoto_wishlist_thumb_profile.png';
                break;
            case "sitereview_category":
                $path = '/application/modules/Sitereview/externals/images/';
                $imageName = 'category.png';
                break;
            case "sitegroup_group":
                $path = '/application/modules/Sitegroup/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_sitegroup_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_sitegroup_thumb_normal.png';
                else
                    $imageName = 'nophoto_sitegroup_thumb_profile.png';
                break;
            case "sitegroupoffer_offer":
                $path = '/application/modules/Sitegroupoffer/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_offer_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_offer_thumb_normal.png';
                else
                    $imageName = 'nophoto_offer_thumb_profile.png';
                break;
            case "siteeventticket_coupon":
                $path = '/application/modules/Siteeventticket/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_coupon_thumb_icon.png';
                else if ($type == 'normal')
                    $imageName = 'nophoto_coupon_thumb_normal.png';
                else
                    $imageName = 'nophoto_coupon_thumb_profile.png';
                break;
            case "sitegroup_category":
                $path = '/application/modules/Sitegroup/externals/images/';
                $imageName = 'category.png';
                break;
            default:
                $path = '/application/modules/User/externals/images/';
                if ($type == 'icon')
                    $imageName = 'nophoto_user_thumb_icon.png';
                else
                    $imageName = 'nophoto_user_thumb_profile.png';
                break;
        }

        // Get file url
        $imageUrl = $getHost . '/' . $baseUrl . $path . $imageName;
        if (strstr($imageUrl, 'index.php/'))
            $imageUrl = str_replace('index.php/', '', $imageUrl);

        if (!empty($imageUrl))
            return $imageUrl;
    }   
    


    public function getDateTime($datefromtable) {

        $datetime1 = new DateTime($datefromtable);
        $datetime2 = new DateTime(date('Y/m/d'));
        $interval = $datetime1->diff($datetime2);
       

        $years = $interval->y;

        if($years>0) {
            $months = $interval->m + ($interval->y*12);
        }else {
            $months = $interval->m;
        }
        
        $mouthss = $months . ' Months';

        if(( $months >= 0) && ( $months <= 1)){
            $baby = 'NewBorn';   
        }elseif(( $months >= 1) && ( $months <= 11)){
            $baby = 'Baby';
        }elseif(( $months >= 12) && ( $months <= 23)){
            $baby = 'Toddler';
        }elseif( $months >= 24 &&  $months <= 47 ){
            $baby = 'Preschool';
        }elseif( $months >= 48 &&  $months <= 121 ){
            $baby = 'School-Age';
        }elseif( $months > 112){
            $baby = 'Teen';
        }else {
            $baby = '';
        }

        $users = array();

        array_push($users,['baby' => $baby, 'mouth' => $mouthss ]);

        return $users;
    }
}
