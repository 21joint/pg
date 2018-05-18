<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitetagcheckin
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: CheckinController.php 6590 2012-08-20 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitetagcheckin_IndexController extends Siteapi_Controller_Action_Standard {
    public function contentCheckinAction() {
        // VALIDATE REQUEST METHOD
        $this->validateRequestMethod();

        //GET VIEWER
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        $subject_id = (int) $this->getRequestParam('subject_id');
        $subject_type = $this->getRequestParam('subject_type');
        $checkedin_users = $this->getRequestParam('checkedin_users', 0);

        if (isset($subject_id) && !empty($subject_id) && isset($subject_type) && !empty($subject_type))
            $subject = Engine_Api::_()->getItem($subject_type, $subject_id);
        //DON'T RENDER IF SUBJECT IS NOT THERE
        if (!$subject) {
            $this->respondWithError('unauthorized');
        }

        //GET VIEWER
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        //IF THERE IS NO VIEWER THEN SET NO RENDER
        if (empty($viewer_id)) {
            $this->respondWithError('unauthorized');
        }

        //GET RESOURCE TYPE   
        $resource_type = $resource_type = $subject->getType();
        //GET RESOURCE ID
        $resource_id = $resource_id = $subject->getIdentity();

        //HOW TO USE THIS WIDGET
        $checkin_verb = $this->_getParam('checkin_verb', 'Check-in here');
        $checkedinto_verb = $this->_getParam('checkedinto_verb', 'checked-into');
        //SET NO RENDER
        if (_ANDROID_VERSION>='1.7' && _IOS_VERSION>='1.4.5') {
            $this->respondWithError('unauthorized');
        }
        //CHECK-IN IS ENABLED FOR THIS CONTENT OR NOT
        $content_select = Engine_Api::_()->getDbTable('contents', 'sitetagcheckin')->getContentInformation(array('resource_type' => $resource_type, 'enabled' => 1));



        //GET ADDLOCATION TABLE
        $addLocationTable = Engine_Api::_()->getDbtable('addlocations', 'sitetagcheckin');

        //GET CHECKIN COUNT
        $response['check_in_count'] = $addLocationTable->getCheckinCount(null, $resource_id, $resource_type, 'checkin', 'parent_id');

        //GET USER CHECKIN COUNT
        $response['user_check_in_count'] = $addLocationTable->getCheckinCount($viewer, $resource_id, $resource_type, 'checkin', 'parent_id');

        if ($checkedin_users && $response['check_in_count'] > 0) {
            $paginator = Engine_Api::_()->getDbtable('addlocations', 'sitetagcheckin')->getCheckinUsers($subject, 0, null, 'public', null);
            foreach ($paginator as $user) {
                $tempUser = Engine_Api::_()->getApi('Core', 'siteapi')->validateUserArray($user);
                $tempUser['displayname'] = $user->getTitle();
                $verification = Engine_Api::_()->getApi('settings', 'core')->getSetting('user.friends.verification');
                $tempUser['isVerified'] = $verification;
                $locationEnabled = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitemember.location', 1);
                if (empty($locationEnabled))
                    $tempUser['location'] = '';
                // Add images
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($user);
                $tempUser = array_merge($tempUser, $getContentImages);

                $table = Engine_Api::_()->getDbtable('block', 'user');
                $select = $table->select()
                        ->where('user_id = ?', $user->getIdentity())
                        ->where('blocked_user_id = ?', $viewer->getIdentity())
                        ->limit(1);
                $row = $table->fetchRow($select);
                if ($row == NULL) {
                    $tempUser['menus'] = Engine_Api::_()->getApi('Siteapi_Core', 'user')->userFriendship($user);
                } else {
                    $tempUser['menus'] = array();
                }

                // Add extra fields in case of Advanced Member Plugin.
                $tempUser = array_merge($tempUser, Engine_Api::_()->getApi('Siteapi_Core', 'sitemember')->addAdvancedMemberSettings($user));


                $users[] = $tempUser;
            }
            $response['totalItemCount'] = $paginator->getTotalItemCount();
            $response['checkedin_users'] = $users;
        }

        $this->respondWithSuccess($response, true);
    }

    public function suggestAction() {
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
        Engine_Api::_()->getApi('Core', 'siteapi')->setLocal();
        $coreModule = Engine_Api::_()->getDbtable('modules', 'core');

        //INITIALISE DATA ARRAY
        $data = array();

        //GET TYPING CONTENT
        $text = $this->getRequestParam('suggest', null);

        //GET LATITUDE
        $latitude = $this->getRequestParam('latitude', 0);

        //GET LONGITUDE
        $longitude = $this->getRequestParam('longitude', 0);

        //INITIALISE SUGGESION
        $initial_suggestion = 0;

        //FOR FIRST TIME IF THERE IS NO TEXT 
        if (empty($text)) {
            $text = $this->getRequestParam('location_detected', null);
            $initial_suggestion = 1;
        }

        //CHECK SITEPAGE IS ENABLED OR NOT
        $sitepageEnabled = 0; //$coreModule->isModuleEnabled('sitepage');
        $settings = Engine_Api::_()->getApi('settings', 'core');
        //CHECK SITEBUSIENSS IS ENABLED OR NOT
        $sitebusinessEnabled = 0; //$coreModule->isModuleEnabled('sitebusiness');
        $sitegroupEnabled = 0; //$coreModule->isModuleEnabled('sitegroup');
        $sitestoreEnabled = 0; //$coreModule->isModuleEnabled('sitestore');
        $siteeventEnabled = 0; //$coreModule->isModuleEnabled('siteevent');

        try {
            //CEHCK TEXT
            if ((null !== $text)) {

                //COUNT
                $count = 0;

                //GET SITETAGCHECKIN API
                $apiSitetagcheckin = Engine_Api::_()->sitetagcheckin();

                //INITIALISE RESOURCE PAGE IDS
                $resourcePageIds = '';
                //INITIALISE PREVIOUS PAGE DATA
                $previousPageData = array();
                //INITIALISE PAGE DATA
                $pageData = array();
                //INITIALISE PAGE FlAG
                $pageFlag = 1;

                //INITIALISE RESOURCE BUSINESS IDS
                $resourceBusinessIds = '';
                //INITIALISE PREVIOUS BUSINESS DATA
                $previousBusinessData = array();
                //INITIALISE BUSINESS DATA
                $businessData = array();
                //INITIALISE BUSINESS FlAG
                $businessFlag = 1;

                //INITIALISE RESOURCE GROUP IDS
                $resourceGroupIds = '';
                //INITIALISE PREVIOUS GROUP DATA
                $previousGroupData = array();
                //INITIALISE GROUP DATA
                $groupData = array();
                //INITIALISE GROUP FlAG
                $groupFlag = 1;

                //INITIALISE RESOURCE STORE IDS
                $resourceStoreIds = '';
                //INITIALISE PREVIOUS STORE DATA
                $previousStoreData = array();
                //INITIALISE STORE DATA
                $storeData = array();
                //INITIALISE STORE FlAG
                $storeFlag = 1;

                //INITIALISE RESOURCE EVENT IDS
                $resourceEventIds = '';
                //INITIALISE PREVIOUS EVENT DATA
                $previousEventData = array();
                //INITIALISE EVENT DATA
                $eventData = array();
                //INITIALISE EVENT FlAG
                $eventFlag = 1;

                //INITIALISE PREVIOUS PLACES
                $previousPlaces = array();

                $tagged_location = $settings->getSetting('sitetagcheckin.tagged.location', 1);

                //SHOW SELECTABLE CONETNT
                $showSelectableContents = $settings->getSetting('sitetagcheckin.selectable', '');

                //INITIALISE GOOGLE PLACE FlAG
                $googleplacesFlag = 1;

                //IF ADMIN HAS SET TO DISPLAY THE PAGES / BUSINESSES / GOOGLE PLACES TO SHOW IN THE AUTOSUGGEST
                if (!empty($showSelectableContents)) {
                    if (!in_array('pages', $showSelectableContents)) {
                        $pageFlag = 0;
                    }
                    if (!in_array('businesses', $showSelectableContents)) {
                        $businessFlag = 0;
                    }
                    if (!in_array('groups', $showSelectableContents)) {
                        $groupFlag = 0;
                    }
                    if (!in_array('stores', $showSelectableContents)) {
                        $storeFlag = 0;
                    }
                    if (!in_array('events', $showSelectableContents)) {
                        $eventFlag = 0;
                    }
                    if (!in_array('googleplaces', $showSelectableContents)) {
                        $googleplacesFlag = 0;
                    }
                }

                //CHECK INITIALISE SUGGESION AND ALSO IF HE WAT TO SAVE THE PREVIOUS CHECKIN LOCATIONS
                if ($initial_suggestion == 1 && $tagged_location) {

                    //GET PREVIOUS GOOGLE PLACES
                    if (!empty($googleplacesFlag)) {
                        $previousGooglePlacesResults = $apiSitetagcheckin->getPreviousGooglePlacesResults();
                    }

                    //SITEPAGEENALEB THEN GETITNG THE PREVIOUS SUGGEST CONTENT
                    if ($sitepageEnabled && $pageFlag) {
                        $previousPageResult = $apiSitetagcheckin->getPreviousSuggestContent($text, 'sitepage_page');
                        foreach ($previousPageResult as $pageResult) {
                            $pageResult['id'] = 'sitetagcheckin_' . $count++;
                            $resourcePageIds .= $pageResult['resource_id'] . ',';
                            $previousPageData[] = $pageResult;
                        }
                    }

                    //SITEBUSINESSENALEB THEN GETITNG THE PREVIOUS SUGGEST CONTENT
                    if ($sitebusinessEnabled && $businessFlag) {
                        $previousBusinessResult = $apiSitetagcheckin->getPreviousSuggestContent($text, 'sitebusiness_business');
                        foreach ($previousBusinessResult as $businessResult) {
                            $businessResult['id'] = 'sitetagcheckin_' . $count++;
                            $resourceBusinessIds .= $businessResult['resource_id'] . ',';
                            $previousBusinessData[] = $businessResult;
                        }
                    }

                    //SITEGROUPENALEB THEN GETITNG THE PREVIOUS SUGGEST CONTENT
                    if ($sitegroupEnabled && $groupFlag) {
                        $previousGroupResult = $apiSitetagcheckin->getPreviousSuggestContent($text, 'sitegroup_group');
                        foreach ($previousGroupResult as $groupResult) {
                            $groupResult['id'] = 'sitetagcheckin_' . $count++;
                            $resourceGroupIds .= $groupResult['resource_id'] . ',';
                            $previousGroupData[] = $groupResult;
                        }
                    }

                    //SITEGROUPENALEB THEN GETITNG THE PREVIOUS SUGGEST CONTENT
                    if ($sitestoreEnabled && $storeFlag) {
                        $previousStoreResult = $apiSitetagcheckin->getPreviousSuggestContent($text, 'sitestore_store');
                        foreach ($previousStoreResult as $stroeResult) {
                            $stroeResult['id'] = 'sitetagcheckin_' . $count++;
                            $resourceStoreIds .= $stroeResult['resource_id'] . ',';
                            $previousStoreData[] = $stroeResult;
                        }
                    }

                    //SITEGROUPENALEB THEN GETITNG THE PREVIOUS SUGGEST CONTENT
                    if ($siteeventEnabled && $eventFlag) {
                        $previousEventResult = $apiSitetagcheckin->getPreviousSuggestContent($text, 'siteevent_event');
                        foreach ($previousEventResult as $eventResult) {
                            $eventResult['id'] = 'sitetagcheckin_' . $count++;
                            $resourceEventIds .= $eventResult['resource_id'] . ',';
                            $previousEventData[] = $eventResult;
                        }
                    }

                    //MAKE PREVIOUS GOOGLE PLACE RESULTS ARRAY
                    foreach ($previousGooglePlacesResults as $previousGooglePalces) {
                        $previousGooglePalces['id'] = 'sitetagcheckin_' . $count++;
                        $previousGooglePalces['type'] = 'place';
                        $previousGooglePalces['prefixadd'] = 'in';
//          $previousGooglePalces['photo'] = '<img class="thumb_icon item_photo_user" alt="" src="application/modules/Sitetagcheckin/externals/images/map_icon.png" />';
                        $previousPlaces[] = $previousGooglePalces;
                    }
                }

                //MAKE PAGE ARRAY
                if (!empty($pageFlag) && $sitepageEnabled) {
                    $pageResult = $this->_getSuggestContent($text, 'sitepage_page', $resourcePageIds);
                    foreach ($pageResult as $page) {
                        $page['id'] = 'sitetagcheckin_' . $count++;
                        $pageData[] = $page;
                    }

                    if (!empty($previousPageData)) {
                        $pageData = array_merge($previousPageData, $pageData);
                    }
                }

                //MAKE BUSINESS ARRAY
                if (!empty($businessFlag) && $sitebusinessEnabled) {
                    $businessResult = $this->_getSuggestContent($text, 'sitebusiness_business', $resourceBusinessIds);
                    foreach ($businessResult as $business) {
                        $business['id'] = 'sitetagcheckin_' . $count++;
                        $businessData[] = $business;
                    }

                    if (!empty($previousBusinessData)) {
                        $businessData = array_merge($previousBusinessData, $businessData);
                    }
                }

                //MAKE GROUP ARRAY
                if (!empty($groupFlag) && $sitegroupEnabled) {
                    $groupResult = $this->_getSuggestContent($text, 'sitegroup_group', $resourceGroupIds);
                    foreach ($groupResult as $group) {
                        $group['id'] = 'sitetagcheckin_' . $count++;
                        $groupData[] = $group;
                    }

                    if (!empty($previousGroupData)) {
                        $groupData = array_merge($previousGroupData, $groupData);
                    }
                }

                //MAKE STORE ARRAY
                if (!empty($storeFlag) && $sitestoreEnabled) {
                    $storeResult = $this->_getSuggestContent($text, 'sitestore_store', $resourceStoreIds);
                    foreach ($storeResult as $store) {
                        $store['id'] = 'sitetagcheckin_' . $count++;
                        $storeData[] = $store;
                    }

                    if (!empty($previousStoreData)) {
                        $storeData = array_merge($previousStoreData, $storeData);
                    }
                }

                //MAKE EVENT ARRAY
                if (!empty($eventFlag) && $siteeventEnabled) {
                    $eventResult = $this->_getSuggestContent($text, 'siteevent_event', $resourceEventIds);
                    foreach ($eventResult as $event) {
                        $event['id'] = 'sitetagcheckin_' . $count++;
                        $eventData[] = $event;
                    }

                    if (!empty($previouseventData)) {
                        $eventData = array_merge($previouseventData, $eventData);
                    }
                }

                //MAKE GOOGLE PLACE ARRAY
                if (!empty($googleplacesFlag)) {
                    $a = $suggestGooglePalces = $apiSitetagcheckin->getSuggestGooglePalces($text, $latitude, $longitude);
                    foreach ($suggestGooglePalces as $key => $palces) {
                        if (!empty($previousGooglePlacesResults)) {
                            foreach ($previousGooglePlacesResults as $previousGooglePlaces) {
                                if (isset($palces['label']) && isset($previousGooglePlaces['label']) && $palces['label'] == $previousGooglePlaces['label']) {
                                    unset($suggestGooglePalces[$key]);
                                }
                            }
                        }
                    }

                    foreach ($suggestGooglePalces as $key => $palces) {
                        $palces['id'] = 'sitetagcheckin_' . $count++;
                        $palces['type'] = 'place';
                        $palces['prefixadd'] = 'in';
//          $palces['photo'] = '<img class="thumb_icon item_photo_user" alt="" src="application/modules/Sitetagcheckin/externals/images/map_icon.png" />';
                        $previousPlaces[] = $palces;
                    }
                }

                //MAKE PAGE AND BUSINESS DATA
                $data = array_merge($pageData, $businessData);

                $data = array_merge($data, $groupData);

                $data = array_merge($data, $storeData);

                $data = array_merge($data, $eventData);

                //MAKE FINAL DATA
                $data = array_merge($data, $previousPlaces);

                $this->respondWithSuccess($data);
            } else {
                $this->respondWithError('unauthorized');
            }
        } catch (Exception $ex) {
            // Blank Exception
        }
    }

    /**
     * Return $suggestItems FOR PAGE / BUSINESSES 
     *
     * @param char $keyword
     * @param int $table_name
     * @param int $resource_ids
     */
    private function _getSuggestContent($keyword, $table_name, $resource_ids) {

        //GET ITEM TABLE
        $table = Engine_Api::_()->getItemTable($table_name);

        //GET LOCATIONS ITEMS
        $items = $table->getLocationBaseContents(array('search' => $keyword, 'resource_id' => $resource_ids));

        //GET VIEW OBJECT
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

        //INITIALISE SUGGEST ARRAY
        $suggestItems = array();

        if (!empty($items)) {

            //MAKE SUGEGSTION ARRAY OF PAGE / BUSINESSES 
            foreach ($items as $item) {
                $suggestItems[] = array(
                    'resource_guid' => $item->getGuid(),
                    'google_id' => 0,
                    'label' => $item->getTitle(),
                    'place_id' => 0,
                    'prefixadd' => 'at',
                    'photo' => Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($item),
                    'type' => ucfirst($item->getShortType()),
                    'category' => $item->getCategoryName(),
                    'vicinity' => $item->formatted_address,
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude
                );
            }
        }

        return $suggestItems;
    }

//  //ACTION FOR SHOWING THE CHECKIN BUTTON
    public function checkInAction() {
        $getMapInfo = Engine_Api::_()->sitetagcheckin()->getMapInfo();

        if (!$getMapInfo) {
            $this->respondWithError('unauthorized');
        }

        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        if (empty($viewer_id))
            $this->respondWithError('unauthorized');

        $resource_type_user = $resource_type = $this->getRequestParam('subject_type', 'user');
        $resource_id = $this->getRequestParam('subject_id', $viewer_id);
        $resource = Engine_Api::_()->getItem($resource_type, $resource_id);

        //CHECK IN VERB
        $checkinto_verb = $this->getRequestParam('checkin_verb', 'Check-in');

        //CHECKED IN VERB
        $checkedinto_verb = $this->getRequestParam('checkedinto_verb', 'checked-into');

        //CHECKED IN VERB
        $checkin_your = $this->getRequestParam('checkin_your', "You have checked-in here");

        //CHECK SUBJECT IS EXIST OR NOT IF NOT EXIST THEN SET ACCORDING TO THE PAGE ID AND PHOTO ID
        if (!Engine_Api::_()->core()->hasSubject()) {
            if (0 !== ($resource_id = (int) $this->getRequestParam('subject_id')) &&
                    null !== ($resource = Engine_Api::_()->getItem($resource_type, $resource_id))) {
                Engine_Api::_()->core()->setSubject($resource);
            }
        }


        $location_id = 0;
        $location = Engine_Api::_()->sitetagcheckin()->getCustomFieldLocation($resource);

        $settings = Engine_Api::_()->getApi('settings', 'core');

        //SET PRIVACY AND SMILES WHEN THERE IS ADVANCED ACTIVITY FEED
        $showPrivacyDropdown = in_array('userprivacy', $settings->getSetting('advancedactivity.composer.options', array("withtags", "emotions", "userprivacy")));
        if ($showPrivacyDropdown)
            $showDefaultInPrivacyDropdown = $userPrivacy = Engine_Api::_()->getDbtable('settings', 'user')->getSetting($viewer, "aaf_post_privacy");

        if (empty($userPrivacy))
            $showDefaultInPrivacyDropdown = $userPrivacy = "everyone";

        $availableLabels = array('everyone' => 'Everyone', 'networks' => 'Friends &amp; Networks', 'friends' => 'Friends Only', 'onlyme' => 'Only Me');

        $userFriendListEnable = $settings->getSetting('user.friends.lists');

        if ($userFriendListEnable && !empty($viewer_id)) {
            $listTable = Engine_Api::_()->getItemTable('user_list');
            $lists = $listTable->fetchAll($listTable->select()->where('owner_id = ?', $viewer->getIdentity()));
            $countList = @count($lists);
            if (!empty($countList) && !empty($userPrivacy) && !in_array($userPrivacy, array('everyone', 'networks', 'friends', 'onlyme'))) {
                $privacylists = $listTable->fetchAll($listTable->select()->where('list_id IN(?)', array(explode(",", $userPrivacy))));
                $temp_list = array();
                foreach ($privacylists as $plist) {
                    $temp_list[$plist->list_id] = $plist->title;
                }
                if (count($temp_list) > 0) {
                    // $this->view->privacylists = $temp_list;
                } else {
                    $showDefaultInPrivacyDropdown = $userPrivacy = "friends";
                }
            }
        } else {
            $userFriendListEnable = 0;
        }

        $menuArray['status'] = 1;
        $menuArray['emotions'] = 1;
        $menuArray['withtags'] = 1;
        $menuArray['photo'] = 1;
        $menuArray['date'] = 1;

        if ($showDefaultInPrivacyDropdown && isset($availableLabels)) {
            $menuArray['userprivacy'] = $availableLabels;
        }

        if ($this->getRequest()->isGet()) {
            $response['feed_post_menu'] = $menuArray;
            $this->respondWithSuccess($response);
        } else if ($this->getRequest()->isPost()) {
            $postData = $_REQUEST;
            Engine_Api::_()->getApi('Core', 'siteapi')->setView();

            $body = @$postData['body'];
            $privacy = null;

            //SET PRIVACY AND SMILES WHEN THERE IS ADVANCED ACTIVITY FEED
            $privacy = $settings->getSetting('activity.content', 'everyone');
            if (isset($postData['auth_view']))
                $privacy = @$postData['auth_view'];

            $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');
            $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');
            $postData['body'] = $body;

            //SET UP ACTION VARIABLE
            $action = null;

            //GET ACTIVITY TABLE
            $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');

            //PROCESS
            $db = $actionTable->getAdapter();
            $db->beginTransaction();
            try {
                //TRY ATTACHMENT GETTING STUFF
                $attachment = null;
                $attachmentData = $this->getRequestParam('photo');

                $photoCount = count($_FILES);
// Try attachment getting stuff
                if (!empty($_FILES['photo']) && $photoCount == 1) {
                    $table = Engine_Api::_()->getDbtable('albums', 'album');
                    $type = $this->getRequestParam('image_type', 'wall');
                    $album = $table->getSpecialAlbum($viewer, $type);
                    $photoTable = Engine_Api::_()->getDbtable('photos', 'album');
                    $photo = $photoTable->createRow();
                    $photo->owner_type = 'user';
                    $photo->owner_id = $viewer->getIdentity();
                    $photo->save();
// Set the photo
                    $photo = $this->_setPhoto($_FILES['photo'], $photo);
                    $photo->order = $photo->photo_id;
                    $photo->album_id = $album->album_id;
                    $photo->save();
                    if (!$album->photo_id) {
                        $album->photo_id = $photo->getIdentity();
                        $album->save();
                    }
                    if ($type != 'message') {
// Authorizations
                        $auth = Engine_Api::_()->authorization()->context;
                        $auth->setAllowed($photo, 'everyone', 'view', true);
                        $auth->setAllowed($photo, 'everyone', 'comment', true);
                    }
                    $attachment = $photo;
                } else if (!empty($_FILES) && $photoCount > 1) {
                    $photo_ids = array();
                    $table = Engine_Api::_()->getDbtable('albums', 'album');
                    $type = $this->getRequestParam('image_type', 'wall');
                    $album = $table->getSpecialAlbum($viewer, $type);
                    $photoTable = Engine_Api::_()->getDbtable('photos', 'album');
                    foreach ($_FILES as $wallPhoto) {
                        $photo = $photoTable->createRow();
                        $photo->owner_type = 'user';
                        $photo->owner_id = $viewer->getIdentity();
                        $photo->save();
// Set the photo
                        $photo = $this->_setPhoto($wallPhoto, $photo);
                        $photo->order = $photo->photo_id;
                        $photo->album_id = $album->album_id;
                        $photo->save();
                        if (!$album->photo_id) {
                            $album->photo_id = $photo->getIdentity();
                            $album->save();
                        }
                        if ($type != 'message') {
// Authorizations
                            $auth = Engine_Api::_()->authorization()->context;
                            $auth->setAllowed($photo, 'everyone', 'view', true);
                            $auth->setAllowed($photo, 'everyone', 'comment', true);
                        }
                        $plugin = Engine_Api::_()->loadClass('Album_Plugin_Composer');
                        $method = 'onAttachPhoto';
                        $attachmentData['photo_id'] = $photo->photo_id;
                        $photo_ids[] = $photo->photo_id;
                        $attachmentData['actionBody'] = '';
                        $attachment = $plugin->$method($attachmentData);
                    }
                }
                $body = preg_replace('/<br[^<>]*>/', "\n", $body);

                //GET RESOURCE TITLE
                $getTitle = $resource->getTitle();

                //GET RESOURCE HREF
                $href = $resource->getHref();
                $prefixadd = 'at';
                $location_label = "";
                if ($resource_type == 'event' || $resource_type == 'classified' || $resource_type == 'list_listing' || $resource_type == 'recipe' || $resource_type == 'sitepageevent_event' || $resource_type == 'sitebusinessevent_event' || $resource_type == 'sitegroupevent_event') {
                    $location_label = $location;
                    if (empty($location)) {
                        $location_label = "";
                        $prefixadd = "";
                        $location = "";
                    }
                } elseif ($resource_type == 'sitepage_page' || $resource_type == 'sitebusiness_business' || $resource_type == 'sitegroup_group' || $resource_type == 'sitestore_store' || $resource_type == 'siteevent_event') {
                    $location_label = $getTitle;
                }

                //SET CHECKIN PARAMS
                $checkin_array = array(
                    'type' => ucfirst($resource->getShortType()),
                    'resource_guid' => $resource->getGuid(),
                    'label' => $location_label,
                    'prefixadd' => $prefixadd
                );

                //MAKE CHECKIN PARMAS
                $checkin_params = array("checkin" => $checkin_array);

                //INITIALISE FEED EVENT DATE
                $feed_event_date = "";
                if ($postData['month'] < 10) {
                    $postData['month'] = "0" . $postData['month'];
                }
                if ($postData['day'] < 10) {
                    $postData['day'] = "0" . $postData['day'];
                }
                $event_date = $postData['year'] . '-' . $postData['month'] . '-' . $postData['day'];
                if (isset($postData['checkinstr_status'])) {
                    $activityType = 'sitetagcheckin_lct_add_to_map';
                    $checkin = $postData['checkinstr_status'];
                    $checkinUser = 1;
                } else {
                    $activityType = 'sitetagcheckin_add_to_map';
                }
                if ($postData['month'] == "00") {
                    $feed_event_date = " in " . $postData['year'];
                    $postData['day'] = "00";
                    $event_date = $postData['year'] . '-' . $postData['month'] . '-' . $postData['day'];
                } else if ($postData['month'] != "00" && $postData['day'] == "00") {
                    $feed_event_date = " in " . date('F', mktime(0, 0, 0, $postData['month'])) . ', ' . $postData['year'];
                } else {
                    $feed_event_date = " on " . date("F j, Y", strtotime($event_date));
                }

                $advancedactivityActions = Engine_Api::_()->getDbtable('actions', 'advancedactivity');
                $showPrivacyDropdown = in_array('userprivacy', $settings->getSetting('advancedactivity.composer.options', array("withtags", "emotions", "userprivacy")));

                if ($showPrivacyDropdown) {
                    Engine_Api::_()->getDbtable('userSettings', 'seaocore')->setSetting($viewer, "aaf_post_privacy", $privacy);
                }
                //ADD ACTIVITY
                if ($activityType == 'sitetagcheckin_content' || $activityType == 'sitetagcheckin_add_to_map') {
                    $action = $advancedactivityActions->addActivity($viewer, $resource, $activityType, $body, $privacy, array('checked_into_verb' => $checkedinto_verb, 'checkin' => $checkin_array, 'event_date' => $feed_event_date));
                } else if ($activityType == 'sitetagcheckin_lct_add_to_map') {
                    parse_str($checkin, $checkin_locationparams);
                    $checkin_params = array("checkin" => $checkin_locationparams);
                    $location = $checkin_locationparams['label'];
                    if ($checkin_locationparams['vicinity']) {
                        if (isset($checkin_locationparams['name']) && $checkin_locationparams['name'] && $checkin_locationparams['name'] != $checkin_locationparams['vicinity']) {
                            $checkin_locationparams['label'] = $checkin_locationparams['name'] . ', ' . $checkin_locationparams['vicinity'];
                        } else {
                            $checkin_locationparams['label'] = $checkin_locationparams['vicinity'];
                        }
                    }

                    $action = $advancedactivityActions->addActivity($viewer, $resource, $activityType, $body, $privacy, array('prefixadd' => $checkin_locationparams['prefixadd'], 'checkin' => $checkin_locationparams, 'event_date' => $feed_event_date));
                    $checkin_locationparams['label'] = $this->view->htmlLink($this->view->url(array('guid' => $action->getGuid()), 'sitetagcheckin_viewmap', true), $checkin_locationparams['label'], array());

                    if (empty($checkin_locationparams['resource_guid'])) {
                        $action->params = array_merge($action->params, array('location' => $checkin_locationparams['label']));
                        $action_id = $action->save();
                        $object_id = $resource_id = $action_id;
                        $object_type = $resource_type = "activity_action";
                    } else {
                        $getItemFor = Engine_Api::_()->getItemByGuid($checkin_locationparams['resource_guid']);
                        $object_id = $resource_id = $getItemFor->getIdentity();
                        $object_type = $resource_type = $getItemFor->getType();
                        //GET RESOURCE TITLE
                        $getTitleFor = $getItemFor->getTitle();
                        //GET RESOURCE HREF
                        $hrefFor = $getItemFor->getHref();
                        $action->params = array_merge($action->params, array('location' => "<a href='$hrefFor'>$getTitleFor</a>"));
                        $action_id = $action->save();
                    }
                }

                //TRY TO ATTACH IF NECESSARY
                if ($action && $attachment) {
                    $advancedactivityActions->attachActivity($action, $attachment);
                }
                //START THE WORK FOR TAGGING
                if ($action && isset($postData['toValues']) && !empty($postData['toValues'])) {
                    $actvityNotification = Engine_Api::_()->getDbtable('notifications', 'activity');
                    $actionTag = new Engine_ProxyObject($action, Engine_Api::_()->getDbtable('tags', 'core'));
                    $users = array_values(array_unique(explode(",", $postData['toValues'])));
                    $params = (array) $action->params;
                    $type_name = $this->translate(str_replace('_', ' ', 'post'));
                    foreach (Engine_Api::_()->getItemMulti('user', $users) as $tag) {
                        $actionTag->addTagMap($viewer, $tag, null);
                        if (!(is_array($params) && isset($params['checkin']))) {
                            $actvityNotification->addNotification(
                                    $tag, $viewer, $action, 'tagged', array(
                                'object_type_name' => $type_name,
                                'label' => $type_name,
                                    )
                            );
                        } else {
                            //GET LABEL
                            $label = $params['checkin']['label'];
                            $checkin_resource_guid = $params['checkin']['resource_guid'];
                            //MAKE LOCATION LINK
                            if (isset($checkin_resource_guid) && empty($checkin_resource_guid)) {
                                $locationLink = $this->view->htmlLink('https://maps.google.com/?q=' . urlencode($label), $label, array('target' => '_blank'));
                            } else {
                                $pageItem = Engine_Api::_()->getItemByGuid($checkin_resource_guid);
                                $pageLink = $pageItem->getHref();
                                $pageTitle = $pageItem->getTitle();
                                $locationLink = "<a href='$pageLink'>$pageTitle</a>";
                            }
                            //SEND NOTIFICATION
                            try {
                                $actvityNotification->addNotification($tag, $viewer, $action, "sitetagcheckin_tagged", array("location" => $locationLink, "label" => $type_name));
                            } catch (Exception $ex) {
                                
                            }
                        }
                    }

                    if ($activityType == 'sitetagcheckin_lct_add_to_map') {
                        $apiSitetagCheckin = Engine_Api::_()->sitetagcheckin();
                        $users = array_values(array_unique(explode(",", $postData['toValues'])));
                        $actionParams = (array) $action->params;
                        if (isset($actionParams['checkin'])) {
                            foreach (Engine_Api::_()->getItemMulti('user', $users) as $tag) {
                                $apiSitetagCheckin->saveCheckin($actionParams['checkin'], $action, $actionParams, $tag->user_id);
                            }
                        }
                    }
                }

                //GET ADDLOCAITON TABLE
                $addLocationTable = Engine_Api::_()->getDbtable('addlocations', 'sitetagcheckin');

                //SET LOCATION ID
                if (!empty($location)) {
                    $location_id = $addLocationTable->getLocationId($location);
                }

                //SET ITEM ID
                $action_id = $action->getIdentity();

                //CHECKIN ARRAY
                $content = array(
                    'location_id' => $location_id,
                    'type' => 'checkin',
                    'item_id' => $action_id,
                    'item_type' => 'activity_action',
                    'params' => $checkin_params,
                    'action_id' => $action_id,
                    'event_date' => $event_date,
                    'owner_id' => $viewer_id
                );

                //GET ATTACHMENT CUNT
                $attachmentCount = count($action->getAttachments());
                if (empty($attachmentCount)) {
                    $content_array = array(
                        'resource_id' => $resource_id,
                        'resource_type' => $resource_type,
                        'object_id' => $resource_id,
                        'object_type' => $resource_type
                    );
                    $addLocationTable->saveLocation(array_merge($content_array, $content));
                } else {
                    foreach ($action->getAttachments() as $attachment) {
                        $attact_resource_type = $attachment->meta->type;
                        $attach_resource_id = $attachment->meta->id;
                    }
                    if ($resource_type == 'blog' || $resource_type == 'group' || $resource_type == 'poll' || $resource_type == 'video' || $resource_type == 'document' || $resource_type == 'forum' || $resource_type == 'music' || $resource_type == 'album' || $resource_type == 'sitepagenote_note' || $resource_type == 'sitepageevent_event' || $resource_type == 'sitebusinessnote_note' || $resource_type == 'sitebusinessevent_event' || $resource_type == 'sitegroupnote_note' || $resource_type == 'sitegroupevent_event') {
                        $attach_resource_id = $resource_id;
                        $attact_resource_type = $resource_type;
                    }
                    $content_array = array(
                        'resource_id' => $attach_resource_id,
                        'resource_type' => $attact_resource_type,
                        'object_id' => $resource_id,
                        'object_type' => $resource_type
                    );
                    $addLocationTable->saveLocation(array_merge($content_array, $content));
                }
                $db->commit();
                $this->successResponseNoContent('no_content', true);
            } catch (Exception $e) {
                $db->rollBack();
                $this->respondWithValidationError('internal_server_error', $e->getMessage());
            }
        }
    }

//    //ACTION FOR SHOWING THE CHECKIN USERS
//    public function seeAllCheckinUserAction() {
//
//        //GET RESOURCE TYPE
//        $this->view->resource_type = $resource_type = $this->_getParam('resource_type');
//
//        //GET RESOURCE ID
//        $this->view->resource_id = $resource_id = $this->_getParam('resource_id');
//
//        //GET CHECKED IN STATUS
//        $this->view->checkedin_see_all_heading = $this->_getParam('checkedin_see_all_heading');
//
//        //CHECK SUBJECT IS EXIST OR NOT IF NOT EXIST THEN SET ACCORDING TO THE PAGE ID AND PHOTO ID
//        if (!Engine_Api::_()->core()->hasSubject()) {
//            if (0 !== ($resource_id = (int) $this->_getParam('resource_id')) &&
//                    null !== ($resource = Engine_Api::_()->getItem($resource_type, $resource_id))) {
//                Engine_Api::_()->core()->setSubject($resource);
//            }
//        }
//
//        //GET PAGE NUMBER
//        $this->view->page = $page = $this->_getParam('page', 1);
//
//        //GET SEARCH TEXT
//        $this->view->search = $search = $this->_getParam('search', '');
//
//        //SET AJAX REQUEST
//        $this->view->is_ajax = $this->_getParam('is_ajax', 0);
//
//        //SET CALL STATUS
//        $call_status = $this->_getParam('call_status');
//
//        //GET CHECKIN COUNT
//        $this->view->checkedin_item_count = $checkedin_item_count = (int) $this->_getParam('checkedin_item_count');
//
//        //GET CHECKED IN STATUS
//        $checkedin_status = $this->_getParam('checkedin_status');
//
//        //SET CALL STATUS PUBLIC IF RESOURCE TYPE FORUM AND EMPTY
//        if (empty($call_status) && $resource_type == 'forum_topic') {
//            $call_status = 'public';
//        }
//
//        //SEND CALL STATUS TO THE TPL
//        $this->view->call_status = $call_status;
//
//        //GET ADDLOCAITON TABLE
//        $addlocationsTable = Engine_Api::_()->getDbtable('addlocations', 'sitetagcheckin');
//
//        //SELECT CHECKIN
//        $checkin_fetch = $addlocationsTable->getCheckinUsers($resource, $checkedin_status, $search, $call_status);
//
//        //COUNT TOTAL CHECKIN
//        $check_in_result = $checkin_fetch->getTotalItemCount();
//
//        if (!empty($check_in_result)) {
//            $this->view->user_obj = $checkin_fetch;
//        } else {
//            $this->view->no_result_msg = $this->view->translate('No results were found.');
//        }
//        $checkin_fetch->setCurrentPageNumber($page);
//        $checkin_fetch->setItemCountPerPage($checkedin_item_count);
//
//        //GET COUNT OF ALL USERS
//        $this->view->public_count = $addlocationsTable->getCheckinUsers($resource, $checkedin_status, $search, 'public', 1);
//
//        //GET COUNT OF FRIEND
//        $this->view->friend_count = $addlocationsTable->getCheckinUsers($resource, $checkedin_status, $search, 'friend', 1);
//    }
    /**
     * Set the uploaded photo from activity post.
     *
     * @return object
     */
    private function _setPhoto($photo, $subject) {
        if ($photo instanceof Zend_Form_Element_File) {
            $file = $photo->getFileName();
        } else if (is_array($photo) && !empty($photo['tmp_name'])) {
            $file = $photo['tmp_name'];
        } else if (is_string($photo) && file_exists($photo)) {
            $file = $photo;
        } else {
            throw new Group_Model_Exception('invalid argument passed to setPhoto');
        }
        $fileName = $photo['name'];
        $name = basename($file);
        $extension = ltrim(strrchr($fileName, '.'), '.');
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
        $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
        $params = array(
            'parent_type' => $subject->getType(),
            'parent_id' => $subject->getIdentity(),
            'user_id' => $subject->owner_id,
            'name' => $fileName,
        );
        $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
// Resize image (main)
        $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
                ->resize(720, 720)
                ->write($mainPath)
                ->destroy();
// Resize image (normal)
        $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
                ->resize(140, 160)
                ->write($normalPath)
                ->destroy();
// Store
        try {
            $iMain = $filesTable->createFile($mainPath, $params);
            $iIconNormal = $filesTable->createFile($normalPath, $params);
            $iMain->bridge($iIconNormal, 'thumb.normal');
        } catch (Exception $e) {
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
// Remove temp files
        @unlink($mainPath);
        @unlink($normalPath);
// Update row
        $subject->modified_date = date('Y-m-d H:i:s');
        $subject->file_id = $iMain->file_id;
        $subject->save();
        return $subject;
    }

}
