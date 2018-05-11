<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegroup
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    IndexController.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegroup_IndexController extends Siteapi_Controller_Action_Standard {

    /**
     * Auth checkup and creating the subject.
     * 
     */
    public function init() {
        $viewer = Engine_Api::_()->user()->getViewer();

        if (!Engine_Api::_()->authorization()->isAllowed('sitegroup_group', $viewer, 'view')) {

            $this->respondWithError('unauthorized');
        }

        if ($this->getRequestParam("group_id") && (0 !== ($group_id = (int) $this->getRequestParam("group_id")) &&
                null !== ($group = Engine_Api::_()->getItem('sitegroup_group', $group_id)))) {
            Engine_Api::_()->core()->setSubject($group);
        }
    }

    /**
     * Returns the Advanced Group listings matching the get parameters with pagination.
     * 
     * @return pagination of Advanced Group listings
     */
    public function browseAction() {
        // Validate request methods
        $this->validateRequestMethod();

        $getRequest = $this->_getAllParams();
        $getRequest['draft'] = 1;
        $getRequest['visible'] = 1;
        $getRequest['type'] = 'browse';
        $getRequest['group'] = isset($getRequest['page']) ? $getRequest['page'] : 1;
        $getRequest['limit'] = !isset($getRequest['limit']) ? $getRequest['limit'] : 20;
        // Set location of app dashboard
        if (isset($getRequest['restapilocation']) && !empty($getRequest['restapilocation']))
            $getRequest['location'] = $getRequest['restapilocation'];

        // Set location of adv search form
        if (isset($_GET['location']) && !empty($_GET['location']))
            $getRequest['location'] = $_GET['location'];

        try {
            $response = $this->_getSitegroups($getRequest);
            $this->respondWithSuccess($response, true);
        } catch (Exception $ex) {
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
    }

    /**
     * Returns the Users Advanced Group listings matching the get parameters with pagination.
     * 
     * @return pagination of Advanced Group listings
     */
    public function manageAction() {
        // Validate request methods
        $this->validateRequestMethod();

        $viewer = $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer->getIdentity())
            $this->respondWithError('unauthorized');

        $getRequest = $this->_getAllParams();
        $getRequest['draft'] = 0;
        $getRequest['manage'] = $getRequest['visible'] = 1;
        $getRequest['user_id'] = Engine_Api::_()->user()->getViewer()->getIdentity();
        $getRequest['visible'] = $getRequest['manage'] = 1;
        $getRequest['owner_id'] = Engine_Api::_()->user()->getViewer()->getIdentity();
        $getRequest['type_location'] = 'manage';
        $getRequest['page'] = (isset($getRequest['page'])) ? $getRequest['page'] : 1;
        $getRequest['limit'] = !isset($getRequest['limit']) ? $getRequest['limit'] : 20;

        try {
            $response = $this->_getSitegroups($getRequest);
            $this->respondWithSuccess($response, true);
        } catch (Exception $ex) {
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
    }

    /**
     * Returns the create Group form or Stores data and creates a Group.
     * 
     * @return array
     */
    public function createAction() {
        if (!Zend_Registry::isRegistered('Zend_Translate'))
            Engine_Api::_()->getApi('Core', 'siteapi')->setTranslate();
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $level_id = $viewer->level_id;

        if (!$viewer_id)
            $this->respondWithError('unauthorized');

        if (!Engine_Api::_()->authorization()->isAllowed('sitegroup_group', $viewer, 'create'))
            $this->respondWithError('unauthorized');

        $quota = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitegroup_group', "max");
        $currentCount = 10;

        if ($currentCount >= $quota && !empty($quota))
            $this->respondWithError('group_creation_quota_exceed');

        // if (!Engine_Api::_()->sitegroup()->hasPackageEnable()) {
        //    $this->respondWithError('sitegroup_package_error');
        //}
        // Get directory group form 
        if ($this->getRequest()->isGet()) {

            $form_fields = Engine_Api::_()->getApi('Siteapi_Core', 'sitegroup')->getForm();
            $this->respondWithSuccess($form_fields);
        }

        // If method not Post or form not valid , Return
        if ($this->getRequest()->isPost()) {
            $sitegroupUrlEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupurl');
            $table = Engine_Api::_()->getItemTable('sitegroup_group');
            $db = $table->getAdapter();
            $db->beginTransaction();

            try {
                // Create sitegroup
                $values = $this->getAllParams();
                $data = $_REQUEST;

                // Start form validation
                $validators = Engine_Api::_()->getApi('Siteapi_FormValidators', 'Sitegroup')->getFormValidators();
                $values['validators'] = $validators;
                $values['package_id'] = 1;
                $validationMessage = $this->isValid($values);

                // Response validation error
                if (!empty($validationMessage) && @is_array($validationMessage)) {

                    $this->respondWithValidationError('validation_fail', $validationMessage);
                }

                // Custom field work
                $categoryIds = array();
                $categoryIds[] = $values['category_id'];
                $categoryIds[] = $values['subcategory_id'];
                $categoryIds[] = $values['subsubcategory_id'];

                try {
                    $values['profile_type'] = Engine_Api::_()->getDbTable('categories', 'sitegroup')->getProfileType($categoryIds, 0, 'profile_type');
                } catch (Exception $ex) {
                    $values['profile_type'] = 0;
                }

                if (isset($values['profile_type']) && !empty($values['profile_type'])) {
                    // START FORM VALIDATION

                    $profileFieldsValidators = Engine_Api::_()->getApi('Siteapi_FormValidators', 'sitegroup')->getFieldsFormValidations($values);
                    $values['validators'] = $profileFieldsValidators;

                    $profileFieldsValidationMessage = $this->isValid($values);
                }

                if (is_array($groupValidationMessage) && is_array($profileFieldsValidationMessage))
                    $validationMessage = array_merge($groupValidationMessage, $profileFieldsValidationMessage);
                else if (is_array($groupValidationMessage))
                    $validationMessage = $groupValidationMessage;
                else if (is_array($profileFieldsValidationMessage))
                    $validationMessage = $profileFieldsValidationMessage;
                else
                    $validationMessage = 1;

                if (!empty($sitegroupUrlEnabled)) {
                    $values['group_url'] = $group_url = $this->_getParam('group_uri');

                    if (!$group_url) {
                        $groupValidationMessage['group_uri'] = $this->translate('Group Url is required field');
                    }
                    if (!empty($sitegroupUrlEnabled)) {
                        $urlArray = Engine_Api::_()->sitegroup()->getBannedUrls();
                    }
                    if (empty($group_url)) {
                        $groupValidationMessage['group_uri'] = $this->translate('Url not valid');
                    }

                    $url_lenght = strlen($group_url);
                    if ($url_lenght < 3) {
                        $groupValidationMessage['group_uri'] = $this->translate("Url should be atleast 3 characters long");
                    } elseif ($url_lenght > 255) {
                        $groupValidationMessage['group_uri'] = $this->translate("Url should be atmost 255 characters long");
                    }

                    $change_url = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.change.url', 1);
                    $check_url = $this->_getParam('check_url');
                    if (!empty($check_url)) {
                        $groupId = $this->_getParam('group_id');
                        $group_id = Engine_Api::_()->sitegroup()->getGroupId($group_url, $groupId);
                    } else {
                        $group_id = Engine_Api::_()->sitegroup()->getGroupId($group_url);
                    }
                    if (!empty($sitegroupUrlEnabled)) {
                        if (!empty($group_id) || (in_array(strtolower($group_url), $urlArray))) {
                            $groupValidationMessage['group_uri'] = $this->translate("Url not available");
                        }
                    } else {
                        if (!empty($group_id)) {
                            $groupValidationMessage['group_uri'] = $this->translate("Url not available");
                        }
                    }

                    if (!preg_match("/^[a-zA-Z0-9-_]+$/", $group_url))
                        $groupValidationMessage['group_uri'] = $this->translate("URL component can contain alphabets, numbers, underscores & dashes only");
                }

                if (!empty($validationMessage) && @is_array($validationMessage)) {
                    $this->respondWithValidationError('validation_fail', $validationMessage);
                }

                if (!empty($groupValidationMessage) && @is_array($groupValidationMessage)) {
                    $this->respondWithValidationError('validation_fail', $groupValidationMessage);
                }

                // End form validation
                $values['owner_id'] = $viewer->getIdentity();
                $values['subcategory_id'] = (isset($values['subcategory_id']) && !empty($values['subcategory_id'])) ? $values['subcategory_id'] : 0;
                $values['subsubcategory_id'] = (isset($values['subsubcategory_id']) && !empty($values['subsubcategory_id'])) ? $values['subsubcategory_id'] : 0;
                $sitegroup = $table->createRow();
                if (Engine_Api::_()->getApi('subCore', 'sitegroup')->groupBaseNetworkEnable()) {
                    if (isset($values['networks_privacy']) && !empty($values['networks_privacy'])) {
                        if (in_array(0, $values['networks_privacy'])) {
                            $values['networks_privacy'] = new Zend_Db_Expr('NULL');
                        } else if (is_array($values['networks_privacy'])) {
                            $values['networks_privacy'] = (string) join(",", $values['networks_privacy']);
                        }
                    }
                }

                $sitegroup->setFromArray($values);
                $package = Engine_Api::_()->getItem('sitegroup_package', $sitegroup->package_id);

                if (!Engine_Api::_()->sitegroup()->hasPackageEnable()) {
                    $sitegroup->featured = Engine_Api::_()->authorization()->getPermission($level_id, 'sitegroup_group', 'featured');
                    $sitegroup->sponsored = Engine_Api::_()->authorization()->getPermission($level_id, 'sitegroup_group', 'sponsored');
                    $sitegroup->approved = Engine_Api::_()->authorization()->getPermission($level_id, 'sitegroup_group', 'approved');
                } else {
                    $sitegroup->featured = $package->featured;
                    $sitegroup->sponsored = $package->sponsored;
                    if ($package->isFree()) {
                        $sitegroup->approved = $package->approved;
                    } else {
                        $sitegroup->approved = 0;
                    }
                }

                if (!empty($sitegroup->approved)) {
                    $sitegroup->pending = 0;
                    $sitegroup->aprrove_date = date('Y-m-d H:i:s');

                    if (Engine_Api::_()->sitegroup()->hasPackageEnable()) {
                        $expirationDate = $package->getExpirationDate();
                        if (!empty($expirationDate))
                            $sitegroup->expiration_date = date('Y-m-d H:i:s', $expirationDate);
                        else
                            $sitegroup->expiration_date = '2250-01-01 00:00:00';
                    }
                    else {
                        $sitegroup->expiration_date = '2250-01-01 00:00:00';
                    }
                }

                $sitegroup->save();

                if (!empty($sitegroup->approved)) {
                    Engine_Api::_()->sitegroup()->sendMail("ACTIVE", $sitegroup->group_id);
                } else {
                    Engine_Api::_()->sitegroup()->sendMail("APPROVAL_PENDING", $sitegroup->group_id);
                }

                $manageadminsTable = Engine_Api::_()->getDbtable('manageadmins', 'sitegroup');
                $row = $manageadminsTable->createRow();
                $row->user_id = $sitegroup->owner_id;
                $row->group_id = $sitegroup->group_id;
                $row->save();

                // Start profile maps work
                Engine_Api::_()->getDbtable('profilemaps', 'sitegroup')->profileMapping($sitegroup);
                $group_id = $sitegroup->group_id;

                if (!empty($sitegroupUrlEnabled)) {
                    $sitegroupUrlEnabledvalues['group_url'] = trim(preg_replace('/-+/', '-', preg_replace('/[^a-z0-9-]+/i', '-', strtolower($values['title']))), '-');
                    if (!empty($count_index) || !empty($count_index_url)) {
                        $values['group_url'] = $values['group_url'] . '-' . $group_id;
                        $table->update(array('group_url' => $values['group_url']), array('group_id = ?' => $group_id));
                    } else {
                        $values['group_url'] = $values['group_url'];
                        $table->update(array('group_url' => $values['group_url']), array('group_id = ?' => $group_id));
                    }
                }

                $sitegroupFormEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupform');
                if ($sitegroupFormEnabled) {
                    $tablecontent = Engine_Api::_()->getDbtable('content', 'core');
                    $params = $tablecontent->select()
                                    ->from($tablecontent->info('name'), 'params')
                                    ->where('name = ?', 'sitegroupform.sitegroup-viewform')
                                    ->query()->fetchColumn();
                    $decodedParam = Zend_Json::decode($params);
                    $tabName = $decodedParam['title'];
                    if (empty($tabName))
                        $tabName = 'Form';

                    $sitegroupformtable = Engine_Api::_()->getDbtable('sitegroupforms', 'sitegroupform');
                    $optionid = Engine_Api::_()->getDbtable('groupquetions', 'sitegroupform');
                    $table_option = Engine_Api::_()->fields()->getTable('sitegroupform', 'options');
                    $sitegroupform = $table_option->createRow();
                    $sitegroupform->setFromArray($values);
                    $sitegroupform->label = $values['title'];
                    $sitegroupform->field_id = 1;
                    $option_id = $sitegroupform->save();
                    $optionids = $optionid->createRow();
                    $optionids->option_id = $option_id;
                    $optionids->group_id = $group_id;
                    $optionids->save();
                    $sitegroupforms = $sitegroupformtable->createRow();
                    if (isset($sitegroupforms->offer_tab_name))
                        $sitegroupforms->offer_tab_name = $tabName;
                    $sitegroupforms->description = 'Please leave your feedback below and enter your contact details.';
                    $sitegroupforms->group_id = $group_id;
                    $sitegroupforms->save();
                }

                // Set photo
                $albumTable = Engine_Api::_()->getDbtable('albums', 'sitegroup');
                $sitegroupinfo = $sitegroup->toarray();
                if (!empty($_FILES)) {
                    Engine_Api::_()->getApi('Siteapi_Core', 'sitegroup')->setPhoto($_FILES['photo'], $sitegroup);

                    $album_id = $albumTable->update(array('photo_id' => $sitegroup->photo_id), array('group_id = ?' => $sitegroup->group_id));
                } else {
                    $album_id = $albumTable->insert(array(
                        'photo_id' => 0,
                        'owner_id' => $sitegroupinfo['owner_id'],
                        'group_id' => $sitegroupinfo['group_id'],
                        'title' => $sitegroupinfo['title'],
                        'creation_date' => $sitegroupinfo['creation_date'],
                        'modified_date' => $sitegroupinfo['modified_date']));
                }

                // Add tags
                $tags = preg_split('/[,]+/', $values['tags']);
                $tags = array_filter(array_map("trim", $tags));
                $sitegroup->tags()->addTagMaps($viewer, $tags);

                if (!empty($group_id)) {
                    $sitegroup->setLocation();
                }

                // Set privacy
                $auth = Engine_Api::_()->authorization()->context;

                // Get the group admin list.
                $ownerList = $sitegroup->getGroupOwnerList();

                $sitegroupmemberEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupmember');
                if (!empty($sitegroupmemberEnabled)) {
                    $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
                } else {
                    $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
                }

                $values['auth_view'] = (isset($values['auth_view']) && !empty($values['auth_view'])) ? $values['auth_view'] : "everyone";
                $values['auth_comment'] = (isset($values['auth_comment']) && !empty($values['auth_comment'])) ? $values['auth_comment'] : "everyone";
                $viewMax = array_search($values['auth_view'], $roles);
                $commentMax = array_search($values['auth_comment'], $roles);

                foreach ($roles as $i => $role) {
                    $auth->setAllowed($sitegroup, $role, 'view', ($i <= $viewMax));
                    $auth->setAllowed($sitegroup, $role, 'comment', ($i <= $commentMax));
                    $auth->setAllowed($sitegroup, $role, 'print', 1);
                    $auth->setAllowed($sitegroup, $role, 'tfriend', 1);
                    $auth->setAllowed($sitegroup, $role, 'overview', 1);
                    $auth->setAllowed($sitegroup, $role, 'map', 1);
                    $auth->setAllowed($sitegroup, $role, 'insight', 1);
                    $auth->setAllowed($sitegroup, $role, 'layout', 1);
                    $auth->setAllowed($sitegroup, $role, 'contact', 1);
                    $auth->setAllowed($sitegroup, $role, 'form', 1);
                    $auth->setAllowed($sitegroup, $role, 'offer', 1);
                    $auth->setAllowed($sitegroup, $role, 'invite', 1);
                }

                $sitegroupmemberEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupmember');
                if (!empty($sitegroupmemberEnabled)) {
                    $roles = array('owner', 'like_member', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
                } else {
                    $roles = array('owner', 'like_member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
                }

                // Start work for sub group.
                $values['auth_sspcreate'] = (isset($values['auth_sspcreate']) && !empty($values['auth_sspcreate'])) ? $values['auth_sspcreate'] : "owner";

                $createMax = array_search($values['auth_sspcreate'], $roles);
                foreach ($roles as $i => $role) {
                    if ($role === 'like_member') {
                        $role = $ownerList;
                    }
                    $auth->setAllowed($sitegroup, $role, 'sspcreate', ($i <= $createMax));
                }
                // End work for subgroup
                // Start sitegroupdiscussion plugin work      
                $sitegroupdiscussionEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupdiscussion');
                if ($sitegroupdiscussionEnabled) {

                    // Start discussion privacy work
                    if (empty($values['sdicreate'])) {
                        $values['sdicreate'] = "registered";
                    }

                    $createMax = array_search($values['sdicreate'], $roles);
                    foreach ($roles as $i => $role) {
                        if ($role === 'like_member') {
                            $role = $ownerList;
                        }
                        $auth->setAllowed($sitegroup, $role, 'sdicreate', ($i <= $createMax));
                    }
                    // End discussion privacy work
                }

                // End sitegroupdiscussion plugin work
                // Start sitegroupalbum plugin work
                $sitegroupalbumEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupalbum');
                if ($sitegroupalbumEnabled) {
                    // Start photo privacy work
                    $values['spcreate'] = (isset($values['spcreate']) && !empty($values['spcreate'])) ? $values['spcreate'] : "registered";
                    $createMax = array_search($values['spcreate'], $roles);
                    foreach ($roles as $i => $role) {
                        if ($role === 'like_member') {
                            $role = $ownerList;
                        }
                        $auth->setAllowed($sitegroup, $role, 'spcreate', ($i <= $createMax));
                    }
                    // End photo privacy work
                }

                // End sitegroupalbum privacy work
                // Start sitegroupsocument privacy work
                $sitegroupDocumentEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupdocument');
                if ($sitegroupDocumentEnabled) {
                    $sitegroupmemberEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupmember');
                    if (!empty($sitegroupmemberEnabled)) {
                        $roles = array('owner', 'like_member', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
                    } else {
                        $roles = array('owner', 'like_member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
                    }

                    $values['sdcreate'] = (isset($values['sdcreate']) && !empty($values['sdcreate'])) ? $values['sdcreate'] : "registered";

                    $createMax = array_search($values['sdcreate'], $roles);
                    foreach ($roles as $i => $role) {
                        if ($role === 'like_member') {
                            $role = $ownerList;
                        }
                        $auth->setAllowed($sitegroup, $role, 'sdcreate', ($i <= $createMax));
                    }
                }
                // End sitegroupdocument privacy work
                // Start sitegroupvideo privacy work
                $sitegroupVideoEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupvideo');
                if ($sitegroupVideoEnabled || (Engine_Api::_()->hasModuleBootstrap('sitevideo') && Engine_Api::_()->getDbtable('modules', 'sitevideo')->getIntegratedModules(array('enabled' => 1, 'item_type' => 'sitegroup_group', 'item_module' => 'sitegroup')))) {
                    $values['svcreate'] = (isset($values['svcreate']) && !empty($values['svcreate'])) ? $values['svcreate'] : "registered";

                    $createMax = array_search($values['svcreate'], $roles);
                    foreach ($roles as $i => $role) {
                        if ($role === 'like_member') {
                            $role = $ownerList;
                        }
                        $auth->setAllowed($sitegroup, $role, 'svcreate', ($i <= $createMax));
                    }
                }
                // End sitegroupvideo privacy work
                // Start sitegrouppoll privacy work
                $sitegroupPollEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegrouppoll');
                if ($sitegroupPollEnabled) {
                    $values['splcreate'] = (isset($values['splcreate']) && !empty($values['splcreate'])) ? $values['splcreate'] : "registered";

                    $createMax = array_search($values['splcreate'], $roles);
                    foreach ($roles as $i => $role) {
                        if ($role === 'like_member') {
                            $role = $ownerList;
                        }
                        $auth->setAllowed($sitegroup, $role, 'splcreate', ($i <= $createMax));
                    }
                }
                // End sitegrouppoll privacy work
                // Start sitegroupnote privacy work
                $sitegroupNoteEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupnote');
                if ($sitegroupNoteEnabled) {
                    $values['sncreate'] = (isset($values['sncreate']) && !empty($values['sncreate'])) ? $values['sncreate'] : "registered";

                    $createMax = array_search($values['sncreate'], $roles);
                    foreach ($roles as $i => $role) {
                        if ($role === 'like_member') {
                            $role = $ownerList;
                        }
                        $auth->setAllowed($sitegroup, $role, 'sncreate', ($i <= $createMax));
                    }
                }


                // End sitegroupnote privacy work
                // Start sitegroupmusic privacy work
                $sitegroupMusicEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupmusic');
                if ($sitegroupMusicEnabled) {
                    $values['smcreate'] = (isset($values['smcreate']) && !empty($values['smcreate'])) ? $values['smcreate'] : "registered";

                    $createMax = array_search($values['smcreate'], $roles);
                    foreach ($roles as $i => $role) {
                        if ($role === 'like_member') {
                            $role = $ownerList;
                        }
                        $auth->setAllowed($sitegroup, $role, 'smcreate', ($i <= $createMax));
                    }
                }
                // End sitegroupmusic privacy work
                // Start sitegroupevent privacy work
                $sitegroupeventEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupevent');
                if ($sitegroupeventEnabled || (Engine_Api::_()->hasModuleBootstrap('siteevent') && Engine_Api::_()->getDbtable('modules', 'siteevent')->getIntegratedModules(array('enabled' => 1, 'item_type' => 'sitegroup_group', 'item_module' => 'sitegroup')))) {
                    $values['secreate'] = (isset($values['secreate']) && !empty($values['secreate'])) ? $values['secreate'] : "registered";

                    $createMax = array_search($values['secreate'], $roles);
                    foreach ($roles as $i => $role) {
                        if ($role === 'like_member') {
                            $role = $ownerList;
                        }
                        $auth->setAllowed($sitegroup, $role, 'secreate', ($i <= $createMax));
                    }
                }
                // End sitegroupevent privacy work
                // Start sitegroupmember privacy work
                $sitegroupMemberEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupmember');
                if ($sitegroupMemberEnabled) {
                    $membersTable = Engine_Api::_()->getDbtable('membership', 'sitegroup');
                    $row = $membersTable->createRow();
                    $row->resource_id = $sitegroup->group_id;
                    $row->group_id = $sitegroup->group_id;
                    $row->user_id = $sitegroup->owner_id;
                    $row->notification = '0';
                    $row->save();
                    Engine_Api::_()->sitegroup()->updateMemberCount($sitegroup);
                    $sitegroup->save();
                }
                $memberInvite = Engine_Api::_()->getApi('settings', 'core')->getSetting('groupmember.invite.option', 1);
                $member_approval = Engine_Api::_()->getApi('settings', 'core')->getSetting('groupmember.member.approval.option', 1);
                if (empty($memberInvite)) {
                    $memberInviteOption = Engine_Api::_()->getApi('settings', 'core')->getSetting('groupmember.invite.automatically', 1);
                    $sitegroup->member_invite = $memberInviteOption;
                    $sitegroup->save();
                }
                if (empty($member_approval)) {
                    $member_approvalOption = Engine_Api::_()->getApi('settings', 'core')->getSetting('groupmember.member.approval.automatically', 1);
                    $sitegroup->member_approval = $member_approvalOption;
                    $sitegroup->save();
                }
                // End sitegroupmember privacy work
                // Start business integration work
                $business_id = $this->_getParam('business_id');
                if (!empty($business_id)) {
                    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
                    $moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessintegration');
                    if (!empty($moduleEnabled)) {
                        $contentsTable = Engine_Api::_()->getDbtable('contents', 'sitebusinessintegration');
                        $row = $contentsTable->createRow();
                        $row->owner_id = $viewer_id;
                        $row->resource_owner_id = $sitegroup->owner_id;
                        $row->business_id = $business_id;
                        $row->resource_type = 'sitegroup_group';
                        $row->resource_id = $sitegroup->group_id;
                        $row->save();
                    }
                }
                $group_id = $this->_getParam('group_id');
                if (!empty($group_id)) {
                    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
                    $moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupintegration');
                    if (!empty($moduleEnabled)) {
                        $contentsTable = Engine_Api::_()->getDbtable('contents', 'sitegroupintegration');
                        $row = $contentsTable->createRow();
                        $row->owner_id = $viewer_id;
                        $row->resource_owner_id = $sitegroup->owner_id;
                        $row->group_id = $group_id;
                        $row->resource_type = 'sitegroup_group';
                        $row->resource_id = $sitegroup->group_id;
                        $row->save();
                    }
                }
                // End business integration work
                // Start store integration work
                $store_id = $this->_getParam('store_id');
                if (!empty($store_id)) {
                    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
                    $moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitestoreintegration');
                    $sitestoreEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitestore');
                    if (!empty($moduleEnabled) && !empty($sitestoreEnabled)) {
                        $contentsTable = Engine_Api::_()->getDbtable('contents', 'sitestoreintegration');
                        $row = $contentsTable->createRow();
                        $row->owner_id = $viewer_id;
                        $row->resource_owner_id = $sitegroup->owner_id;
                        $row->store_id = $store_id;
                        $row->resource_type = 'sitegroup_group';
                        $row->resource_id = $sitegroup->group_id;
                        $row->save();
                    }
                }
                // End store integration work
                // Start subgroup work
                $parent_id = $this->_getParam('parent_id');
                if (!empty($parent_id)) {
                    $sitegroup->subgroup = 1;
                    $sitegroup->parent_id = $parent_id;
                    $sitegroup->save();
                }

                // Custom field work
                if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.profile.fields', 1)) {

                    $mapData = Engine_Api::_()->getApi('core', 'fields')->getFieldsMaps('sitegroup_group');
                    // Getting profile fields
                    $getRowsMatching = $mapData->getRowsMatching('option_id', $values['profile_type']);
                    $fieldValues = Engine_Api::_()->fields()->getFieldsValues($sitegroup);

                    // Looking for data in form post and inserting in field values
                    if (!empty($getRowsMatching)) {
                        foreach ($getRowsMatching as $field) {
                            $key = $field->field_id . '_' . $field->option_id . '_' . $field->child_id . '_field_' . $field->child_id;
                            $a[] = $key;
                            if (isset($values[$key]) && !empty($values[$key])) {
                                $fieldvalue = $fieldValues->getRowsMatching(array(
                                    'field_id' => $field->child_id,
                                    'item_id' => $sitegroup->group_id,
                                ));

                                if (!empty($fieldvalue)) {
                                    $fieldvalue[0]->value = $values[$key];
                                    $fieldvalue[0]->save();
                                } else {
                                    $valuesRow = $fieldValues->createRow();
                                    $valuesRow->field_id = $field->child_id;
                                    $valuesRow->item_id = $sitegroup->group_id;
                                    $valuesRow->index = 0;
                                    $valuesRow->value = $values[$key];
                                    $valuesRow->save();
                                }
                            }
                        }
                    }
                }


                // Start default email to superadmin when anyone create groups.
                $emails = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.defaultgroupcreate.email', Engine_API::_()->seaocore()->getSuperAdminEmailAddress());
                if (!empty($emails)) {
                    $emails = explode(",", $emails);
                    $host = $_SERVER['HTTP_HOST'];
                    $newVar = _ENGINE_SSL ? 'https://' : 'http://';
                    $object_link = $newVar . $host . $sitegroup->getHref();
                    $viewerGetTitle = $viewer->getTitle();
                    $sender_link = '<a href=' . $newVar . $host . $viewer->getHref() . ">$viewerGetTitle</a>";
                    foreach ($emails as $email) {
                        $email = trim($email);
                        Engine_Api::_()->getApi('mail', 'core')->sendSystem($email, 'SITEGROUP_GROUP_CREATION', array(
                            'sender' => $sender_link,
                            'object_link' => $object_link,
                            'object_title' => $sitegroup->getTitle(),
                            'object_description' => $sitegroup->getDescription(),
                            'queue' => true
                        ));
                    }
                }
                // End default email to superadmin when anyone create groups.

                if (!empty($sitegroup) && !empty($sitegroup->draft) && empty($sitegroup->pending)) {
                    Engine_Api::_()->sitegroup()->attachGroupActivity($sitegroup);


                    // Start AUTOMATICALLY LIKE THE GROUP WHEN MEMBER CREATE A GROUP.
                    $autoLike = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.automatically.like', 1);
                    if (!empty($autoLike)) {
                        Engine_Api::_()->sitegroup()->autoLike($sitegroup->group_id, 'sitegroup_group');
                    }
                    //END automatically like the group when member create a group.
                    // Sending activity feed to facebook.
                    $enable_Facebooksefeed = $enable_fboldversion = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('facebooksefeed');
                    if (!empty($enable_Facebooksefeed)) {
                        $sitegroup_array = array();
                        $sitegroup_array['type'] = 'sitegroup_new';
                        $sitegroup_array['object'] = $sitegroup;
                        Engine_Api::_()->facebooksefeed()->sendFacebookFeed($sitegroup_array);
                    }
                }
                // Commit
                $db->commit();
// Change request method POST to GET
                $this->setRequestMethod();
                $this->_forward('view', 'profile', 'sitegroup', array(
                    'group_id' => $sitegroup->getIdentity()
                ));
                return;
            } catch (Exception $e) {
                $db->rollBack();
                $this->respondWithError('internal_server_error', $e->getMessage());
            }
        }
    }

    /**
     * Returns the Directory group search form 
     * 
     */
    public function searchFormAction() {

        // Validate request method
        $this->validateRequestMethod();
        $this->respondWithSuccess(Engine_Api::_()->getApi('Siteapi_Core', 'sitegroup')->getBrowseSearchForm());
    }

    /**
     * Returns Categories , Sub-Categories, SubSub-Categories and groups array
     * 
     * 
     */
    public function categoryAction() {

        // Validate request method
        $this->validateRequestMethod();

        // Get viewer
        $viewer = Engine_Api::_()->user()->getViewer();
        // Prepare response
        $values = $response = array();
        $category_id = $this->getRequestParam('category_id', null);
        $subCategory_id = $this->getRequestParam('subCategory_id', null);
        $subsubcategory_id = $this->getRequestParam('subsubcategory_id', null);
        $showAllCategories = $this->getRequestParam('showAllCategories', 1);
        $showCategories = $this->getRequestParam('showCategories', 1);
        $showGroups = $this->getRequestParam('showGroups', 1);

        if ($this->getRequestParam('showCount')) {
            $showCount = 1;
        } else {
            $showCount = $this->getRequestParam('showCount', 0);
        }
        $orderBy = $this->getRequestParam('orderBy', 'category_name');
        $displayOnlyUsefulGroups = 1;
        $getHost = Engine_Api::_()->getApi('Core', 'siteapi')->getHost();


        try {
            $tableCategory = Engine_Api::_()->getDbtable('categories', 'sitegroup');
            Engine_Api::_()->getApi('Core', 'siteapi')->setView();

            $categories = array();

            // Get groups table
            $tableSitegroup = Engine_Api::_()->getDbtable('groups', 'sitegroup');
            $sitegroupShowAllCategories = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.categorywithslug', 1);
            $showAllCategories = !empty($sitegroupShowAllCategories) ? $showAllCategories : 0;

            if ($showCategories) {
                if ($showAllCategories) {
                    $category_info = $tableCategory->getCategories(array('category_id', 'category_name', 'cat_order'), null, 0, 0, 1, 0, $orderBy, 1);
                    $categoriesCount = count($category_info);
                    foreach ($category_info as $value) {
                        $sub_cat_array = array();
                        $photoName = Engine_Api::_()->storage()->get($value['file_id'], '');
                        if ($showCount) {
                            $category_array = array('category_id' => $value->category_id,
                                'category_name' => $this->translate($value->category_name),
                                'order' => $value->cat_order,
                                'count' => $value->count,
                            );

                            if (!empty($photoName)) {
                                $category_array['image_icon'] = (strstr($photoName->getPhotoUrl(), 'http')) ? $photoName->getPhotoUrl() : $getHost . $photoName->getPhotoUrl();
                            } else {
                                $getDefaultImage = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($value);
                                $category_array['image_icon'] = $getDefaultImage['image_icon'];
                            }
                        } else {
                            $category_array = array('category_id' => $value->category_id,
                                'category_name' => $this->translate($value->category_name),
                                'order' => $value->cat_order,
                            );
                            if (!empty($photoName)) {
                                $category_array['image_icon'] = (strstr($photoName->getPhotoUrl(), 'http')) ? $photoName->getPhotoUrl() : $getHost . $photoName->getPhotoUrl();
                            } else {
                                $getDefaultImage = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($value);
                                $category_array['image_icon'] = $getDefaultImage['image_icon'];
                            }
                        }
                        $categories[] = $category_array;
                    }
                } else {
                    $category_info = $tableCategory->getAllCategories(0, 'category_id', null, array(), array('category_id', 'category_name', 'cat_order'));
                    $categoriesCount = count($category_info);
                    foreach ($category_info as $value) {
                        $photoName = Engine_Api::_()->storage()->get($value['file_id'], '');

                        if ($showCount) {
                            $category_array = array('category_id' => $value->category_id,
                                'category_name' => $value->category_name,
                                'order' => $value->cat_order,
                                'count' => $value->count,
                            );
                            if (!empty($photoName)) {
                                $category_array['image_icon'] = (strstr($photoName->getPhotoUrl(), 'http')) ? $photoName->getPhotoUrl() : $getHost . $photoName->getPhotoUrl();
                            } else {
                                $getDefaultImage = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($value);
                                $category_array['image_icon'] = $getDefaultImage['image_icon'];
                            }
                        } else {
                            $category_array = array('category_id' => $value->category_id,
                                'category_name' => $this->translate($value->category_name),
                                'order' => $value->cat_order,
                            );
                            if (!empty($photoName)) {
                                $category_array['image_icon'] = (strstr($photoName->getPhotoUrl(), 'http')) ? $photoName->getPhotoUrl() : $getHost . $photoName->getPhotoUrl();
                            } else {
                                $getDefaultImage = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($value);
                                $category_array['image_icon'] = $getDefaultImage['image_icon'];
                            }
                        }
                        $categories[] = $category_array;
                    }
                }

                $response['categories'] = $categories;
                if (!empty($category_id)) {

                    if ($showAllCategories) {
                        $category_info2 = $tableCategory->getAllCategories($category_id, 'category_id', null, array(), array('category_id', 'category_name', 'cat_order'));
                        foreach ($category_info2 as $subresults) {
                            if ($showCount) {
                                $sub_cat_array[] = $tmp_array = array('sub_cat_id' => $subresults->category_id,
                                    'sub_cat_name' => $this->translate($subresults->category_name),
                                    'count' => $value->count,
                                    'order' => $subresults->cat_order);
                            } else {
                                $sub_cat_array[] = $tmp_array = array('sub_cat_id' => $subresults->category_id,
                                    'sub_cat_name' => $this->translate($subresults->category_name),
                                    'order' => $subresults->cat_order);
                            }
                        }
                    } else {
                        $category_info2 = $tableCategory->getAllCategories($category_id, 'category_id', null, array(), array('category_id', 'category_name', 'cat_order'));
                        foreach ($category_info2 as $subresults) {
                            if ($showCount) {
                                $sub_cat_array[] = $tmp_array = array('sub_cat_id' => $subresults->category_id,
                                    'sub_cat_name' => $this->translate($subresults->category_name),
                                    'count' => $value->count,
                                    'order' => $subresults->cat_order);
                            } else {
                                $sub_cat_array[] = $tmp_array = array('sub_cat_id' => $subresults->category_id,
                                    'sub_cat_name' => $this->translate($subresults->category_name),
                                    'order' => $subresults->cat_order);
                            }
                        }
                    }

                    $response['subCategories'] = $sub_cat_array;
                }

                if (!empty($subCategory_id)) {

                    if ($showAllCategories) {
                        $subcategory_info2 = $tableCategory->getAllCategories($subCategory_id, 'category_id', null, array(), array('category_id', 'category_name', 'cat_order'));
                        $treesubarrays = array();
                        foreach ($subcategory_info2 as $subvalues) {
                            if ($showCount) {
                                $treesubarrays[] = $treesubarray = array('tree_sub_cat_id' => $subvalues->category_id,
                                    'tree_sub_cat_name' => $this->translate($subvalues->category_name),
                                    'count' => $value->count,
                                    'order' => $subvalues->cat_order,
                                );
                            } else {
                                $treesubarrays[] = $treesubarray = array('tree_sub_cat_id' => $subvalues->category_id,
                                    'tree_sub_cat_name' => $this->translate($subvalues->category_name),
                                    'order' => $subvalues->cat_order,
                                );
                            }
                        }
                    } else {
                        $subcategory_info2 = $tableCategory->getAllCategories($subCategory_id, 'category_id', null, array(), array('category_id', 'category_name', 'cat_order'));
                        $treesubarrays = array();
                        foreach ($subcategory_info2 as $subvalues) {
                            if ($showCount) {
                                $treesubarrays[] = $treesubarray = array('tree_sub_cat_id' => $subvalues->category_id,
                                    'tree_sub_cat_name' => $this->translate($subvalues->category_name),
                                    'order' => $subvalues->cat_order,
                                    'count' => $value->count,
                                );
                            } else {
                                $treesubarrays[] = $treesubarray = array('tree_sub_cat_id' => $subvalues->category_id,
                                    'tree_sub_cat_name' => $this->translate($subvalues->category_name),
                                    'order' => $subvalues->cat_order
                                );
                            }
                        }
                    }
                    $response['subsubCategories'] = $treesubarrays;
                }
            }

            if ($showGroups && isset($category_id) && !empty($category_id)) {
                $params = array();
                $itemCount = $params['itemCount'] = $this->_getParam('itemCount', 0);

                // Get categories
                $categories = array();

                $category_groups_array = array();

                $params = $this->_getAllParams();
                // Get group results
                $category_groups_info = $this->_getSitegroups($params);
                $response['groups'] = $category_groups_info;
            }
            if (isset($categoriesCount) && !empty($categoriesCount))
                $response['totalItemCount'] = $categoriesCount;
            $response['canCreate'] = Engine_Api::_()->authorization()->isAllowed('sitegroup_group', $viewer, 'create');

            $this->respondWithSuccess($response, true);
        } catch (Exception $ex) {
            $this->respondWithError('internal_server_error', $e->getMessage());
        }
    }

    /*
     * Group url Validation
     *
     */

    public function groupurlvalidationAction() {

        $this->validateRequestMethod();

        $group_url = $this->_getParam('group_url', $this->_getParam('group_uri'));
        if (!$group_url)
            $this->respondWithValidationError('parameter_missing', "parameter named group_url missing");

        $sitegroupUrlEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupurl');
        if (!empty($sitegroupUrlEnabled)) {
            $urlArray = Engine_Api::_()->sitegroup()->getBannedUrls();
        }
        if (empty($group_url)) {
            $this->respondWithValidationError('urlNotvalid', "Url not valid");
        }

        $url_lenght = strlen($group_url);
        if ($url_lenght < 3) {
            $this->respondWithValidationError('urlNotvalid', "Url should be atleast 3 characters long");
        } elseif ($url_lenght > 255) {
            $this->respondWithValidationError('urlNotvalid', "url should be atmost 255 characters long");
        }

        $change_url = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.change.url', 1);
        $check_url = $this->_getParam('check_url');
        if (!empty($check_url)) {
            $groupId = $this->_getParam('group_id');
            $group_id = Engine_Api::_()->sitegroup()->getGroupId($group_url, $groupId);
        } else {
            $group_id = Engine_Api::_()->sitegroup()->getGroupId($group_url);
        }
        if (!empty($sitegroupUrlEnabled)) {
            if (!empty($group_id) || (in_array(strtolower($group_url), $urlArray))) {
                $this->respondWithValidationError('urlNotvalid', "Url not available");
            }
        } else {
            if (!empty($group_id)) {
                $this->respondWithValidationError('urlNotvalid', "Url not available");
            }
        }

        if (!preg_match("/^[a-zA-Z0-9-_]+$/", $group_url)) {
            $this->respondWithValidationError('urlNotvalid', "URL component can contain alphabets, numbers, underscores & dashes only");
        } else {
            $this->successResponseNoContent('no_content');
        }
    }

    /**
     * Returns the paginated Directory groups listings after filtering from search papameters if sent
     * 
     * @param array params
     */
    private function _getSitegroups($params) {
        $response = $tempParams = $data = $tempResponse = array();
        $imageType = 'thumb.icon';
        $viewer = Engine_Api::_()->user()->getViewer();
        $tableObj = Engine_Api::_()->getDbtable('groups', 'sitegroup');
        $searchLocation = $params['location'];

        if (isset($params['min']) && !empty($params['min']))
            $params['sitegroup_price']['min'] = $params['min'];

        if (isset($params['max']) && !empty($params['max']))
            $params['sitegroup_price']['max'] = $params['max'];

        //TO GET OR NOT THE EXACT LOCATION OF EVENT
        if (isset($params['restapilocation']) && !empty($params['restapilocation']))
            $params['location'] = $params['restapilocation'];

        //TO GET OR NOT THE EXACT LOCATION OF EVENT
        if (!empty($searchLocation) && isset($searchLocation))
            $params['location'] = $searchLocation;

        $siteapigroupBrowse = Zend_Registry::isRegistered('sitegroup_browse') ? Zend_Registry::get('sitegroup_browse') : null;
        $response['canCreate'] = $this->_helper->requireAuth()->setAuthParams('sitegroup_group', null, 'create')->checkRequire();

        if (empty($params['manage']) && $params['show'] == 2) {
            // Get an array of friend ids
            $table = Engine_Api::_()->getItemTable('user');
            $select = $viewer->membership()->getMembersSelect('user_id');
            $friends = $table->fetchAll($select);
            // Get stuff
            $ids = array();
            foreach ($friends as $friend) {
                $ids[] = $friend->user_id;
            }
            $params['users'] = $ids;
        }

        if (empty($params['manage']) && isset($params['user_id']) && !empty($params['user_id'])) {
            $params['type'] = 'browse';
            $params['orderby'] = 'creation_date';
            $params['type_location'] = 'manage';
        }

        if (isset($params['image_type']) && !empty($params['image_type']))
            $imageType = $params['image_type'];


        $groupsObj = Engine_Api::_()->sitegroup()->getSitegroupsPaginator($params);
        $items_count = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.group', $params['limit']);
        $response['totalItemCount'] = $getTempGroupsCount = $groupsObj->getTotalItemCount();

        if ($getTempGroupsCount) {
            foreach ($groupsObj as $groupObj) {
                $data = $groupObj->toArray();

                // Set the price & currency 
                if (isset($data['price']) && $data['price'] > 0) {
                    $data['currency'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
                    $data['price'] = (int) $data['price'];
                } else if (isset($data['price']))
                    unset($data['price']);

                if (!empty($params['manage']) && $groupObj->isOwner($viewer))
                    $data["menu"] = Engine_Api::_()->getApi('Siteapi_Core', 'sitegroup')->gutterMenus($groupObj, 'manage');

                $categoryObj = Engine_Api::_()->getItem('sitegroup_category', $data['category_id']);
                if (isset($categoryObj) && !empty($categoryObj))
                    $data['category_title'] = $categoryObj->getTitle();

                // Add images
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($groupObj);
                $data = array_merge($data, $getContentImages);

                // Add owner images
                $getContentOwnerImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($groupObj, true);
                $data = array_merge($data, $getContentOwnerImages);

                $data["owner_title"] = $groupObj->getOwner()->getTitle();
                $ownerUrl = Engine_Api::_()->getApi('Core', 'siteapi')->getContentURL($groupObj->getOwner(), "owner_url");

                $data = array_merge($data, $ownerUrl);

                $contentUrl = Engine_Api::_()->getApi('Core', 'siteapi')->getContentURL($groupObj);
                $data = array_merge($data, $contentUrl);

                $isAllowedView = $groupObj->authorization()->isAllowed($viewer, 'view');
                $data["allow_to_view"] = empty($isAllowedView) ? 0 : 1;

                $isAllowedEdit = $groupObj->authorization()->isAllowed($viewer, 'edit');
                $data["edit"] = empty($isAllowedEdit) ? 0 : 1;
                $isAllowedDelete = $groupObj->authorization()->isAllowed($viewer, 'delete');
                $data["delete"] = empty($isAllowedDelete) ? 0 : 1;

                $tempResponse[] = $data;
            }

            if (!empty($tempResponse))
                $response['response'] = $tempResponse;
        }
        return $response;
    }

    public function packagesAction() {
        //ONLY LOGGED IN USER CAN VIEW THIS PAGE
        if (!$this->_helper->requireUser()->isValid())
            $this->respondWithError('unauthorized');
        // CHECK FOR PERMISSION OF CREATE EVENT

        if (!$this->_helper->requireAuth()->setAuthParams('sitegroup_group', null, 'view')->isValid())
            $this->respondWithError('unauthorized');
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
        Engine_Api::_()->getApi('Core', 'siteapi')->setLocal();

        //GET VIEWER
        $viewer = Engine_Api::_()->user()->getViewer();

        $viewer_id = $viewer->getIdentity();

        $overview = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.overview', 0);


        //GET USER LEVEL ID
        if (!empty($viewer_id)) {
            $level_id = $viewer->level_id;
        } else {
            $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        }


        $bodyParams = $tempArray = array();
        $packageInfoArray = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.package.information', array("price", "billing_cycle", "duration", "featured", "sponsored", "rich_overview", "videos", "photos", "description", "ticket_type"));

        $packageCount = Engine_Api::_()->getDbTable('packages', 'sitegroup')->getEnabledPackageCount();
        if ($packageCount) {
            $packageTable = Engine_Api::_()->getDbtable('packages', 'sitegroup');
            $packageSql = $packageTable->getPackagesSql();
            $paginator = Zend_Paginator::factory($packageSql);
            if ($paginator) {
                $bodyParams['getTotalItemCount'] = $paginator->getTotalItemCount();
                foreach ($paginator as $row => $package) {
                    $packageShowArray = array();

                    if (isset($package->package_id) && !empty($package->package_id))
                        $packageShowArray['package_id'] = $package->package_id;

                    if (isset($package->title) && !empty($package->title)) {
                        $packageShowArray['title']['label'] = $this->translate('Title');
                        $packageShowArray['title']['value'] = $this->translate($package->title);
                    }

                    if (in_array('price', $packageInfoArray)) {
                        if ($package->price > 0.00) {
                            $packageShowArray['price']['label'] = $this->translate('Price');
                            $packageShowArray['price']['value'] = $package->price;
                            $packageShowArray['price']['currency'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
                        } else {
                            $packageShowArray['price']['label'] = $this->translate('Price');
                            $packageShowArray['price']['value'] = $this->translate('FREE');
                        }
                    }

                    if (in_array('billing_cycle', $packageInfoArray)) {
                        $packageShowArray['billing_cycle']['label'] = $this->translate('Billing Cycle');
                        $packageShowArray['billing_cycle']['value'] = $package->getBillingCycle();
                    }

                    if (in_array('contactdetails', $packageInfoArray)) {
                        $packageShowArray['contactdetails']['label'] = $this->translate('Contact details');
                        $packageShowArray['contactdetails']['value'] = $this->translate('No');
                        if ($package->contact_details)
                            $packageShowArray['contactdetails']['value'] = $this->translate("Yes");
                    }

                    if (in_array('sendanupdate', $packageInfoArray)) {
                        $packageShowArray['sendanupdate']['label'] = $this->translate('Send an Updates');
                        $packageShowArray['sendanupdate']['value'] = $this->translate('No');
                        if ($package->sendupdate)
                            $packageShowArray['sendanupdate']['value'] = $this->translate("Yes");
                    }

                    if (in_array('apps', $packageInfoArray)) {
                        $modules = unserialize($package->modules);
                        $submodules = $package->getSubModulesString();
                        $packageShowArray['apps']['label'] = $this->translate('Apps available');
                        $packageShowArray['apps']['value'] = $this->translate('No');
                        if (!empty($modules) && !empty($submodules))
                            $packageShowArray['apps']['value'] = $this->translate("Yes");
                    }

                    if (in_array('adds', $packageInfoArray)) {
                        $packageShowArray['adds']['label'] = $this->translate('Advertisement');
                        $packageShowArray['adds']['value'] = $this->translate('No');
                        if ($package->contact_details)
                            $packageShowArray['adds']['value'] = $this->translate("Yes");
                    }

                    if (in_array('duration', $packageInfoArray)) {
                        $packageShowArray['duration']['label'] = $this->translate("Duration");
                        $packageShowArray['duration']['value'] = $package->getPackageQuantity();
                    }

                    if (in_array('featured', $packageInfoArray)) {
                        if ($package->featured == 1) {
                            $packageShowArray['featured']['label'] = $this->translate('Featured');
                            $packageShowArray['featured']['value'] = $this->translate('Yes');
                        } else {
                            $packageShowArray['featured']['label'] = $this->translate('Featured');
                            $packageShowArray['featured']['value'] = $this->translate('No');
                        }
                    }

                    if (in_array('sponsored', $packageInfoArray)) {
                        if ($package->sponsored == 1) {
                            $packageShowArray['Sponsored']['label'] = $this->translate('Sponsored');
                            $packageShowArray['Sponsored']['value'] = $this->translate('Yes');
                        } else {
                            $packageShowArray['Sponsored']['label'] = $this->translate('Sponsored');
                            $packageShowArray['Sponsored']['value'] = $this->translate('No');
                        }
                    }

                    if (in_array('tellafriend', $packageInfoArray)) {
                        $packageShowArray['tellafriend']['label'] = $this->translate('Tell a friend');
                        $packageShowArray['tellafriend']['value'] = $this->translate('No');
                        if ($package->tellafriend)
                            $packageShowArray['tellafriend']['value'] = $this->translate('Yes');
                    }

                    if (in_array('print', $packageInfoArray)) {
                        $packageShowArray['print']['label'] = $this->translate('Print');
                        $packageShowArray['print']['value'] = $this->translate('No');
                        if ($package->print)
                            $packageShowArray['print']['value'] = $this->translate('Yes');
                    }

                    if (in_array('overview', $packageInfoArray)) {
                        $packageShowArray['overview']['label'] = $this->translate('Overview');
                        $packageShowArray['overview']['value'] = $this->translate('No');
                        if ($package->overview)
                            $packageShowArray['overview']['value'] = $this->translate('Yes');
                    }

                    if (in_array('map', $packageInfoArray)) {
                        $packageShowArray['map']['label'] = $this->translate('Map');
                        $packageShowArray['map']['value'] = $this->translate('No');
                        if ($package->overview)
                            $packageShowArray['map']['value'] = $this->translate('Yes');
                    }

                    if (in_array('insights', $packageInfoArray)) {
                        $packageShowArray['insights']['label'] = $this->translate('Insights');
                        $packageShowArray['insights']['value'] = $this->translate('No');
                        if ($package->insights)
                            $packageShowArray['insights']['value'] = $this->translate('Yes');
                    }

                    if (in_array('rich_overview', $packageInfoArray) && ($overview && (empty($level_id) || Engine_Api::_()->authorization()->getPermission($level_id, 'sitepage_page', "overview")))) {
                        if ($package->overview == 1) {
                            $packageShowArray['rich_overview']['label'] = $this->translate('Rich Overview');
                            $packageShowArray['rich_overview']['value'] = $this->translate('Yes');
                        } else {
                            $packageShowArray['rich_overview']['label'] = $this->translate('Rich Overview');
                            $packageShowArray['rich_overview']['value'] = $this->translate('No');
                        }
                    }

                    if (in_array('videos', $packageInfoArray) && (empty($level_id) || Engine_Api::_()->authorization()->getPermission($level_id, 'sitepage_page', "video"))) {
                        if ($package->video == 1) {
                            if ($package->video_count) {
                                $packageShowArray['videos']['label'] = $this->translate('Videos');
                                $packageShowArray['videos']['value'] = $package->video_count;
                            } else {
                                $packageShowArray['videos']['label'] = $this->translate('Videos');
                                $packageShowArray['videos']['value'] = $this->translate("Unlimited");
                            }
                        } else {
                            $packageShowArray['videos']['label'] = $this->translate('Videos');
                            $packageShowArray['videos']['value'] = $this->translate('No');
                        }
                    }

                    if (in_array('photos', $packageInfoArray) && (empty($level_id) || Engine_Api::_()->authorization()->getPermission($level_id, 'sitepage_page', "photo"))) {
                        if ($package->photo == 1) {
                            if ($packagem->photo_count) {
                                $packageShowArray['photos']['label'] = $this->translate('Photos');
                                $packageShowArray['photos']['value'] = $package->photo_count;
                            } else {
                                $packageShowArray['photos']['label'] = $this->translate('Photos');
                                $packageShowArray['photos']['value'] = $this->translate("Unlimited");
                            }
                        } else {
                            $packageShowArray['photos']['label'] = $this->translate('Photos');
                            $packageShowArray['photos']['value'] = $this->translate('No');
                        }
                    }

                    if (in_array('description', $packageInfoArray)) {
                        $packageShowArray['description']['label'] = $this->translate("Description");
                        $packageShowArray['description']['value'] = $this->translate($package->description);
                    }
                    $packageArray["package"] = $packageShowArray;
                    $tempMenu = array();
                    $tempMenu[] = array(
                        'label' => $this->translate('Create Group'),
                        'name' => 'create',
                        'url' => 'advancedgroups/create',
                        'urlParams' => array(
                            'package_id' => $package->package_id,
                        )
                    );
                    $tempMenu[] = array(
                        'label' => $this->translate('Package Info'),
                        'name' => 'package_info',
                        'url' => 'advancedgroups/packages',
                        'urlParams' => array(
                            'package_id' => $package->package_id
                        )
                    );

                    $packageArray['menu'] = $tempMenu;
                    $bodyParams['response'][] = $packageArray;
                }

                if (isset($bodyParams) && !empty($bodyParams))
                    $this->respondWithSuccess($bodyParams);
            }
        }
        if (empty($bodyParams))
            $bodyParams['getTotalItemCount'] = 0;

        $this->respondWithSuccess($bodyParams);
    }

    public function upgradePackageAction() {

        //ONLY LOGGED IN USER CAN VIEW THIS PAGE
        if (!$this->_helper->requireUser()->isValid())
            $this->respondWithError('unauthorized');
        // CHECK FOR PERMISSION OF CREATE EVENT

        if (!$this->_helper->requireAuth()->setAuthParams('sitegroup_group', null, 'view')->isValid())
            $this->respondWithError('unauthorized');

        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
        Engine_Api::_()->getApi('Core', 'siteapi')->setLocal();

        //GET VIEWER
        $viewer = Engine_Api::_()->user()->getViewer();

        $viewer_id = $viewer->getIdentity();

        $overview = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.overview', 0);


        //GET USER LEVEL ID
        if (!empty($viewer_id)) {
            $level_id = $viewer->level_id;
        } else {
            $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        }



        if (!Engine_Api::_()->sitegroup()->hasPackageEnable()) {
            $this->respondWithError('no_record');
        }

        //GET GROUP ID GROUP OBJECT AND THEN CHECK VALIDATIONS
        $group_id = $this->_getParam('group_id');
        if (empty($group_id)) {
            $this->respondWithError('no_record');
        }
        $sitegroup = Engine_Api::_()->getItem('sitegroup_group', $group_id);
        if (empty($sitegroup)) {
            $this->respondWithError('no_record');
        }
        if (isset($sitegroup->package_id) && !empty($sitegroup->package_id))
            $currentPackage = Engine_Api::_()->getItem('sitegroup_package', $sitegroup->package_id);
        if ($this->getRequest()->isGet()) {

            $bodyParams = $tempArray = array();
            $packageInfoArray = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.package.information', array("price", "billing_cycle", "duration", "featured", "sponsored", "rich_overview", "videos", "photos", "description", "ticket_type"));

            $packageCount = Engine_Api::_()->getDbTable('packages', 'sitegroup')->getEnabledPackageCount();
            if ($packageCount) {
                $table = Engine_Api::_()->getItemtable('sitegroup_package');
                $packages_select = $table->getPackagesSql($sitegroup->getOwner())
                        ->where("update_list = ?", 1)
                        ->where("enabled = ?", 1)
                        ->where("package_id <> ?", $sitegroup->package_id);
                $paginator = Zend_Paginator::factory($packages_select);

                if ($paginator) {
                    $bodyParams['getTotalItemCount'] = $paginator->getTotalItemCount();
                    foreach ($paginator as $row => $package) {
                        $packageShowArray = array();

                        if (isset($package->package_id) && !empty($package->package_id))
                            $packageShowArray['package_id'] = $package->package_id;
                       
                        if (isset($package->title) && !empty($package->title)) {
                            $packageShowArray['title']['label'] = $this->translate('Title');
                            $packageShowArray['title']['value'] = $this->translate($package->title);
                        }

                        if (in_array('price', $packageInfoArray)) {
                            if ($package->price > 0.00) {
                                $packageShowArray['price']['label'] = $this->translate('Price');
                                $packageShowArray['price']['value'] = $package->price;
                                $packageShowArray['price']['currency'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
                            } else {
                                $packageShowArray['price']['label'] = $this->translate('Price');
                                $packageShowArray['price']['value'] = $this->translate('FREE');
                            }
                        }

                        if (in_array('billing_cycle', $packageInfoArray)) {
                            $packageShowArray['billing_cycle']['label'] = $this->translate('Billing Cycle');
                            $packageShowArray['billing_cycle']['value'] = $package->getBillingCycle();
                        }

                        if (in_array('contactdetails', $packageInfoArray)) {
                            $packageShowArray['contactdetails']['label'] = $this->translate('Contact details');
                            $packageShowArray['contactdetails']['value'] = $this->translate('No');
                            if ($package->contact_details)
                                $packageShowArray['contactdetails']['value'] = $this->translate("Yes");
                        }

                        if (in_array('sendanupdate', $packageInfoArray)) {
                            $packageShowArray['sendanupdate']['label'] = $this->translate('Send an Updates');
                            $packageShowArray['sendanupdate']['value'] = $this->translate('No');
                            if ($package->sendupdate)
                                $packageShowArray['sendanupdate']['value'] = $this->translate("Yes");
                        }

                        if (in_array('apps', $packageInfoArray)) {
                            $modules = unserialize($package->modules);
                            $submodules = $package->getSubModulesString();
                            $packageShowArray['apps']['label'] = $this->translate('Apps available');
                            $packageShowArray['apps']['value'] = $this->translate('No');
                            if (!empty($modules) && !empty($submodules))
                                $packageShowArray['apps']['value'] = $this->translate("Yes");
                        }

                        if (in_array('adds', $packageInfoArray)) {
                            $packageShowArray['adds']['label'] = $this->translate('Advertisement');
                            $packageShowArray['adds']['value'] = $this->translate('No');
                            if ($package->contact_details)
                                $packageShowArray['adds']['value'] = $this->translate("Yes");
                        }

                        if (in_array('duration', $packageInfoArray)) {
                            $packageShowArray['duration']['label'] = $this->translate("Duration");
                            $packageShowArray['duration']['value'] = $package->getPackageQuantity();
                        }

                        if (in_array('featured', $packageInfoArray)) {
                            if ($package->featured == 1) {
                                $packageShowArray['featured']['label'] = $this->translate('Featured');
                                $packageShowArray['featured']['value'] = $this->translate('Yes');
                            } else {
                                $packageShowArray['featured']['label'] = $this->translate('Featured');
                                $packageShowArray['featured']['value'] = $this->translate('No');
                            }
                        }

                        if (in_array('sponsored', $packageInfoArray)) {
                            if ($package->sponsored == 1) {
                                $packageShowArray['Sponsored']['label'] = $this->translate('Sponsored');
                                $packageShowArray['Sponsored']['value'] = $this->translate('Yes');
                            } else {
                                $packageShowArray['Sponsored']['label'] = $this->translate('Sponsored');
                                $packageShowArray['Sponsored']['value'] = $this->translate('No');
                            }
                        }

                        if (in_array('tellafriend', $packageInfoArray)) {
                            $packageShowArray['tellafriend']['label'] = $this->translate('Tell a friend');
                            $packageShowArray['tellafriend']['value'] = $this->translate('No');
                            if ($package->tellafriend)
                                $packageShowArray['tellafriend']['value'] = $this->translate('Yes');
                        }

                        if (in_array('print', $packageInfoArray)) {
                            $packageShowArray['print']['label'] = $this->translate('Print');
                            $packageShowArray['print']['value'] = $this->translate('No');
                            if ($package->print)
                                $packageShowArray['print']['value'] = $this->translate('Yes');
                        }

                        if (in_array('overview', $packageInfoArray)) {
                            $packageShowArray['overview']['label'] = $this->translate('Overview');
                            $packageShowArray['overview']['value'] = $this->translate('No');
                            if ($package->overview)
                                $packageShowArray['overview']['value'] = $this->translate('Yes');
                        }

                        if (in_array('map', $packageInfoArray)) {
                            $packageShowArray['map']['label'] = $this->translate('Map');
                            $packageShowArray['map']['value'] = $this->translate('No');
                            if ($package->overview)
                                $packageShowArray['map']['value'] = $this->translate('Yes');
                        }

                        if (in_array('insights', $packageInfoArray)) {
                            $packageShowArray['insights']['label'] = $this->translate('Insights');
                            $packageShowArray['insights']['value'] = $this->translate('No');
                            if ($package->insights)
                                $packageShowArray['insights']['value'] = $this->translate('Yes');
                        }

                        if (in_array('rich_overview', $packageInfoArray) && ($overview && (empty($level_id) || Engine_Api::_()->authorization()->getPermission($level_id, 'sitepage_page', "overview")))) {
                            if ($package->overview == 1) {
                                $packageShowArray['rich_overview']['label'] = $this->translate('Rich Overview');
                                $packageShowArray['rich_overview']['value'] = $this->translate('Yes');
                            } else {
                                $packageShowArray['rich_overview']['label'] = $this->translate('Rich Overview');
                                $packageShowArray['rich_overview']['value'] = $this->translate('No');
                            }
                        }

                        if (in_array('videos', $packageInfoArray) && (empty($level_id) || Engine_Api::_()->authorization()->getPermission($level_id, 'sitepage_page', "video"))) {
                            if ($package->video == 1) {
                                if ($package->video_count) {
                                    $packageShowArray['videos']['label'] = $this->translate('Videos');
                                    $packageShowArray['videos']['value'] = $package->video_count;
                                } else {
                                    $packageShowArray['videos']['label'] = $this->translate('Videos');
                                    $packageShowArray['videos']['value'] = $this->translate("Unlimited");
                                }
                            } else {
                                $packageShowArray['videos']['label'] = $this->translate('Videos');
                                $packageShowArray['videos']['value'] = $this->translate('No');
                            }
                        }

                        if (in_array('photos', $packageInfoArray) && (empty($level_id) || Engine_Api::_()->authorization()->getPermission($level_id, 'sitepage_page', "photo"))) {
                            if ($package->photo == 1) {
                                if ($packagem->photo_count) {
                                    $packageShowArray['photos']['label'] = $this->translate('Photos');
                                    $packageShowArray['photos']['value'] = $package->photo_count;
                                } else {
                                    $packageShowArray['photos']['label'] = $this->translate('Photos');
                                    $packageShowArray['photos']['value'] = $this->translate("Unlimited");
                                }
                            } else {
                                $packageShowArray['photos']['label'] = $this->translate('Photos');
                                $packageShowArray['photos']['value'] = $this->translate('No');
                            }
                        }

                        if (in_array('description', $packageInfoArray)) {
                            $packageShowArray['description']['label'] = $this->translate("Description");
                            $packageShowArray['description']['value'] = $this->translate($package->description);
                        }
                        $packageArray['package'] = $packageShowArray;

                        $tempMenu = array();
                        $tempMenu[] = array(
                            'label' => $this->translate('Upgrade Package'),
                            'name' => 'upgrade_package',
                            'url' => 'advancedgroups/upgrade-package',
                            'urlParams' => array(
                                'package_id' => $package->package_id,
                                'group_id' => $sitegroup->group_id
                            )
                        );
                        $tempMenu[] = array(
                            'label' => $this->translate('Package Info'),
                            'name' => 'package_info',
                            'url' => 'advancedgroups/upgrade-package',
                            'urlParams' => array(
                                'package_id' => $package->package_id,
                                'group_id' => $sitegroup->group_id
                            )
                        );

                        $packageArray['menu'] = $tempMenu;

                        $bodyParams['response'][] = $packageArray;
                    }

                    if (isset($currentPackage) && !empty($currentPackage)) {
                        $bodyParams['currentPackage'] = $currentPackage->toArray();
                    }

                    if (isset($bodyParams) && !empty($bodyParams))
                        $this->respondWithSuccess($bodyParams);
                }
            }
        }



        elseif ($this->getRequest()->getPost()) {

            $package_id = $this->_getParam('package_id');
            $package_chnage = Engine_Api::_()->getItem('sitegroup_package', $package_id);
            if (empty($package_chnage) || !$package_chnage->enabled || (!empty($package_chnage->level_id) && !in_array($sitegroup->getOwner()->level_id, explode(",", $package_chnage->level_id)))) {
                $this->respondWithError('unauthorized');
            }
            $getPackageAuth = Engine_Api::_()->sitegroup()->getPackageAuthInfo('sitegroup');

            if (!empty($_POST['package_id'])) {
                $table = $sitegroup->getTable();
                $db = $table->getAdapter();
                $db->beginTransaction();

                try {
                    $is_upgrade_package = true;

                    //APPLIED CHECKS BECAUSE CANCEL SHOULD NOT BE CALLED IF ALREADY CANCELLED 
                    if ($sitegroup->status == 'active')
                        $sitegroup->cancel($is_upgrade_package);

                    $sitegroup->package_id = $_POST['package_id'];
                    $package = Engine_Api::_()->getItem('sitegroup_package', $sitegroup->package_id);

                    $sitegroup->featured = $package->featured;
                    $sitegroup->sponsored = $package->sponsored;
                    $sitegroup->pending = 1;
                    $sitegroup->expiration_date = new Zend_Db_Expr('NULL');
                    $sitegroup->status = 'initial';
                    if (($package->isFree()) && !empty($getPackageAuth)) {
                        $sitegroup->approved = $package->approved;
                    } else {
                        $sitegroup->approved = 0;
                    }

                    if (!empty($sitegroup->approved)) {

                        $sitegroup->pending = 0;
                        $expirationDate = $package->getExpirationDate();
                        if (!empty($expirationDate))
                            $sitegroup->expiration_date = date('Y-m-d H:i:s', $expirationDate);
                        else
                            $sitegroup->expiration_date = '2250-01-01 00:00:00';

                        if (empty($sitegroup->aprrove_date)) {
                            $sitegroup->aprrove_date = date('Y-m-d H:i:s');
                            if (!empty($sitegroup) && !empty($sitegroup->draft) && empty($sitegroup->pending)) {
                                Engine_Api::_()->sitegroup()->attachGroupActivity($sitegroup);
                            }
                        }
                    }
                    $sitegroup->save();
                    $db->commit();
                    $this->successResponseNoContent('no_content', true);
                } catch (Exception $e) {
                    $db->rollBack();
                }
            }
            /*  $this->_forward('success', 'utility', 'core', array(
              'smoothboxClose' => true,
              'format' => 'smoothbox',
              'parentRedirect' => $this->view->url(array('action' => 'update-package', 'group_id' => $sitegroup->group_id), 'sitegroup_packages', true),
              'parentRedirectTime' => 15,
              'messages' => array(Zend_Registry::get('Zend_Translate')->_('The package for your Group has been successfully changed.'))
              )); */
        }
    }

    private function _packagesEnabled() {
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        if (_CLIENT_TYPE && ((_CLIENT_TYPE == 'android' && _ANDROID_VERSION >= '1.6.3') || _CLIENT_TYPE == 'ios' && _IOS_VERSION >= '1.5.1')) {
            if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.package.enable', 1)) {
                $packageTable = Engine_Api::_()->getDbtable('packages', 'sitegroup');
                $packageSql = $packageTable->getPackagesSql();
                $paginator = Zend_Paginator::factory($packageSql);
                $packageCount = $paginator->getTotalItemCount();

                if ($packageCount == 1) {
                    foreach ($paginator as $row => $package) {
                        if (($package->price == '0.00')) {
                            return 0;
                        } else
                            return 1;
                    }
                } else {
                    $overview = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.overview', 0);

                    if ($packageCount > 0) {
                        return 1;
                    } else {
                        return 0;
                    }
                }
            }
        }
        return 0;
    }

}