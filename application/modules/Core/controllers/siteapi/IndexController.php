<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteapi
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    IndexController.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Core_IndexController extends Siteapi_Controller_Action_Standard {

    protected $_getAPPBuilderBaseURL = 'public/app-builder';

    /**
     * Getting the enabled and allowed modules.
     *
     * @return array
     */
    public function getEnabledModulesAction() {
// Validate request methods
        $this->validateRequestMethod();
        $getEnabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
        $this->respondWithSuccess($getEnabledModuleNames);
    }

    public function getDefaultLanguageAction() {
        $this->validateRequestMethod();
        $getDefaultLanguages = $this->getLanguages();
        $this->respondWithSuccess($getDefaultLanguages);
    }

    /**
     * Get dashboard menus
     *
     * @return array $response
     */
    public function getDashboardMenusAction() {
        $this->validateRequestMethod();
        $categoryName = '';
        $response = $menuArray = array();
        $type = $this->getRequestParam('type', 'android');
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $browse_as_guest = $this->getRequestParam('browse_as_guest', false);
        try {
// Getting the dashboard menus.
            $getLocations = $this->getLocations();
            $viewer = Engine_Api::_()->user()->getViewer();
            $table = ($type === 'ios') ? Engine_Api::_()->getDbtable('menus', 'siteiosapp') : Engine_Api::_()->getDbtable('menus', 'siteandroidapp');

// Synchroniz liting type to menu table
            if (($type == 'android') && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled("sitereview") && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled("sitereviewlistingtype"))
                Engine_Api::_()->getApi('core', 'siteandroidapp')->synchroniseDashboardMenus();


// Synchroniz liting type to menu table
            if (($type == 'ios') && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled("sitereview") && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled("sitereviewlistingtype"))
                Engine_Api::_()->getApi('core', 'siteiosapp')->synchroniseDashboardMenus();

            $select = $table->getSelect(array('status' => 1));
            $mneuObj = $table->fetchAll($select);

            foreach ($mneuObj as $menu) {

//                if ($menu->type == 'category') {
//                    $categoryName = $menu->dashboard_label;
//                    continue;
//                }
// By passin case if version lessthen the set versions.
                if (isset($menu->params) && !empty($menu->params)) {
                    $params = @unserialize($menu->params);
                    if (
                            ($type == 'android') &&
                            isset($params['version']) &&
                            !empty($params['version']) &&
                            _ANDROID_VERSION &&
                            _ANDROID_VERSION < $params['version']
                    )
                        continue;

                    if (
                            ($type == 'ios') &&
                            isset($params['version']) &&
                            !empty($params['version']) &&
                            _IOS_VERSION
                    ) {
                        if (version_compare(_IOS_VERSION, $params['version']) < 1)
                            continue;
                    }
                }

// Remove the following query whenever Sitepage release in our Android App                
                if (($menu->name == 'core_main_sitepage') && _CLIENT_TYPE && ((_CLIENT_TYPE == 'android') || (_CLIENT_TYPE == 'both')) && _ANDROID_VERSION && _ANDROID_VERSION < 1.7)
                    continue;

                if (($menu->name == 'core_main_cometchat') && _CLIENT_TYPE && ((_CLIENT_TYPE == 'android') || (_CLIENT_TYPE == 'both')) && _ANDROID_VERSION && _ANDROID_VERSION < '1.7.8')
                    continue;

                if (($menu->name == 'core_main_sitepage') && _CLIENT_TYPE && ((_CLIENT_TYPE == 'ios') || (_CLIENT_TYPE == 'both')) && _IOS_VERSION) {
                    if (version_compare(_IOS_VERSION, '1.5.6') < 1)
                        continue;
                }

// spread_the_word icon will not come in case of 1.4 OR less versions.
                if ($type == 'ios') {
                    $version = $this->getRequestParam('version', '1.4');
                    $version = (int) @str_replace(".", "", $version);
                    if (($version <= 14) && ($menu->name === 'spread_the_word'))
                        continue;
                }

                if (($menu->show == 'login') && !$viewer->getIdentity())
                    continue;

                if (($menu->show == 'logout') && $viewer->getIdentity())
                    continue;

// If available module not enabled.
                if (isset($menu->module) && !empty($menu->module) && !Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled($menu->module))
                    continue;

// Validate authrization view permission.
                if (isset($menu->module) &&
                        !empty($menu->module) &&
                        !in_array($menu->module, array("core", "user", "activity", "messages", "advancedactivity", "sitetagcheckin", "sitereview", "siteevent", "sitegroup", "sitepage", "sitestore", "sitestoreproduct", "sitestoreoffer", "siteapi", "sitevideo"))
                ) {
                    $itemType = ($menu->module == 'music') ? 'music_playlist' : $menu->module;
                    //   if (!Engine_Api::_()->authorization()->isAllowed($itemType, $viewer, 'view'))
                    //    continue;
                }


                switch ($menu->module) {
                    case 'music':
                        $modName = 'music_playlist';
                        break;
                    case 'siteevent':
                        $modName = 'siteevent_event';
                        break;
                    case 'sitepage':
                        $modName = 'sitepage_page';
                        break;
                    case 'sitereview':
                        $modName = 'sitereview';
                        $listingtype_id = $params['listingtype_id'];
                        break;
                    case 'sitegroup':
                        $modName = 'sitegroup_group';
                        break;
                    case 'sitevideo':
                        $modName = 'video';
                        break;
                    case 'sitestoreproduct':
                    case 'sitestoreoffer':
                    case 'sitestore':
                        $modName = 'sitestore_store';
                        break;
                    default :
                        $modName = $menu->module;
                }

                if ($type == 'android' && isset($menu->siteandroidapp_menucolor) && !empty($menu->siteandroidapp_menucolor)) {
                    $color = $menu->siteandroidapp_menucolor;
                } elseif ($type == 'ios' && isset($menu->siteiosapp_menucolor) && !empty($menu->siteiosapp_menucolor)) {
                    $color = $menu->siteiosapp_menucolor;
                } else
                    $color = '';
                
                if ($menu->module == 'sitereview') {
                    $memberView = $this->_helper->requireAuth()->setAuthParams('sitereview_listing', null, "view_listtype_$listingtype_id")->isValid();
                } else
                    $memberView = $this->_helper->requireAuth()->setAuthParams($modName, null, 'view')->isValid();

                $memberView = !empty($memberView) ? 1 : 0;

                if (strstr($menu->dashboard_label, 'Terms Of Service')) {
                    $menu->dashboard_label = Engine_Api::_()->getApi('Core', 'siteapi')->translate(str_replace('Terms Of Service', 'Terms of Service', $menu->dashboard_label));
                }

// Version condition for Advanced Event 
                if (($type == 'ios') && _CLIENT_TYPE && (_CLIENT_TYPE == 'both')) {
                    if ($menu->name == 'core_main_siteevent')
                        continue;
                }

// Version condition for Advanced Event 
                if (_CLIENT_TYPE && (_CLIENT_TYPE == 'ios') && _IOS_VERSION && _IOS_VERSION < '1.5.2') {
                    if ($menu->name == 'sitereview_wishlist')
                        continue;
                }


                if (($menu->name == 'core_main_sitegroup') && _CLIENT_TYPE && ((_CLIENT_TYPE == 'android') || (_CLIENT_TYPE == 'both')) && _ANDROID_VERSION && _ANDROID_VERSION < '1.7.1')
                    continue;

                if (($menu->name == 'core_main_sitegroup') && _CLIENT_TYPE && ((_CLIENT_TYPE == 'ios') || (_CLIENT_TYPE == 'both'))) {
                    if (version_compare(_IOS_VERSION, '1.7.9') < 1)
                        continue;
                }

                // Version condition for Advanced Event 
                if (($type == 'ios') && _CLIENT_TYPE && (_CLIENT_TYPE == 'both')) {
                    if ($menu->name == 'core_main_siteevent' || $menu->name == 'sitereview_listing')
                        continue;
                }

                // Version condition for Sitereview Plugin 
                if (_CLIENT_TYPE && (_CLIENT_TYPE == 'ios') && _IOS_VERSION && _IOS_VERSION < '1.5.2') {
                    if ($menu->name == 'sitereview_wishlist' || $menu->name == 'sitereview_listing')
                        continue;
                }

                $getHost = Engine_Api::_()->getApi('Core', 'siteapi')->getHost();
                $baseParentUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
                $baseParentUrl = @trim($baseParentUrl, "/");
                if ($menu->module == 'sitereview' && $menu->name != 'sitereview_wishlist') {
                    if (isset($menu->params) && !empty($menu->params)) {
                        $params = @unserialize($menu->params);
                        $menuArray[] = array(
                            'name' => $menu->name,
                            'type' => $menu->type,
                            'label' => $this->translate($menu->dashboard_label),
                            'color' => $color,
                            'headerLabel' => Engine_Api::_()->getApi('Core', 'siteapi')->translate($menu->header_label),
                            'header_label_singular' => (isset($params['header_label_singular']) && !empty($params['header_label_singular'])) ? Engine_Api::_()->getApi('Core', 'siteapi')->translate($params['header_label_singular']) : Engine_Api::_()->getApi('Core', 'siteapi')->translate($menu->header_label),
                            'icon' => (!empty($menu->icon)) ? $menu->icon : "",
                            'url' => (!empty($menu->url)) ? $menu->url : "",
                            'listingtype_id' => $params['listingtype_id'],
                            'viewBrowseType' => $this->_getViewTypeLabel($params['listingtype_id'], 2),
                            'viewProfileType' => $this->_getViewTypeLabel($params['listingtype_id'], 1),
                            'canCreate' => Engine_Api::_()->getApi('Core', 'siteapi')->getCreateAuthSitereviewArray($menu),
                            "memberView" => $memberView,
                        );
                    }
                } else {

                    if (($menu->name == 'core_main_sitestoreproduct_orders' || $menu->name == 'core_main_wishlist') && !$viewer_id)
                        continue;

                    if ($menu->module == "siteapi" && $menu->name == 'core_main_wishlist' && (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitestore') || Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereview'))) {
                        $isFavouriteEnable = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.favourite', 0) ? 1 : 0;
                        if (empty($isFavouriteEnable)) {
                            $menuArray[] = array(
                                'name' => $menu->name,
                                'type' => $menu->type,
                                'label' => $this->translate($menu->dashboard_label),
                                'headerLabel' => Engine_Api::_()->getApi('Core', 'siteapi')->translate($menu->header_label),
                                'color' => $color,
                                "memberView" => $memberView,
                                'icon' => (!empty($menu->icon)) ? $menu->icon : "",
                                'url' => (!empty($menu->url)) ? $menu->url : "",
                                'canCreate' => array(
                                    'sitestore' => (int) (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitestore') && (((_CLIENT_TYPE == 'android') && _ANDROID_VERSION >= '1.7.8') || (_CLIENT_TYPE == 'ios' && _IOS_VERSION >= '1.6.6'))),
                                    'sitereview' => (int) Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereview') && (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.favourite', 0) ? 1 : 0,
                                ),
                            );
                        }
                        continue;
                    }

                    $tempResultArray = array(
                        'name' => $menu->name,
                        'type' => $menu->type,
                        'label' => $this->translate($menu->dashboard_label),
                        'headerLabel' => Engine_Api::_()->getApi('Core', 'siteapi')->translate($menu->header_label),
                        'color' => $color,
                        'icon' => (!empty($menu->icon)) ? $menu->icon : "",
                        'url' => (!empty($menu->url)) ? $menu->url : "",
                    );

                    //Added Member view type
                    if ($menu->name == 'core_main_user') {
                        $viewType = ($type == 'android') ? Engine_Api::_()->getApi('settings', 'core')->getSetting("android.member.view", 1) : Engine_Api::_()->getApi('settings', 'core')->getSetting("ios.member.view", 1);
                        $tempResultArray['memberViewType'] = $viewType;
                    }

                    // Start work to add "canCreate" variable value.
                    if (
                            isset($menu['default']) &&
                            isset($menu['module']) &&
                            !empty($menu['default']) &&
                            !empty($menu['module']) &&
                            !in_array($menu['module'], array('core', 'activity', 'user'))
                    ) {
                        $tempResultArray['memberView'] = $memberView;

                        $tempResultArray['canCreate'] = Engine_Api::_()->getApi('Core', 'siteapi')->getCreateAuthArray($menu['module']);
                        if ($menu->name == 'core_main_siteevent') {
                            $ticket = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteeventticket.ticket.enabled', 1);
                            $enabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteeventticket');

                            if (!empty($ticket) && !empty($enabled)) {
                                $tempResultArray['canCreate']['myTicketEnabled'] = 1;
                                $tempResultArray['canCreate']['coupontEnabled'] = 1;
                            }
                        }
                    }
                    // End work to add "canCreate" variable value.

                    if (!empty($getLocations) && isset($menu->name) && ($menu->name == 'seaocore_location'))
                        $tempResultArray['data'] = $getLocations;

                    $menuArray[] = $tempResultArray;
                }
            }

            $response['menus'] = $menuArray;

            // Getting the available languages.
            $response['languages'] = $this->getLanguages($type);

            $response['location'] = ($type == 'android') ? Engine_Api::_()->getApi('settings', 'core')->getSetting("android.enable.location", 1) : Engine_Api::_()->getApi('settings', 'core')->getSetting("ios.enable.location", 1);

            $viewType = ($type == 'android') ? Engine_Api::_()->getApi('settings', 'core')->getSetting("android.member.view", 1) : Engine_Api::_()->getApi('settings', 'core')->getSetting("ios.member.view", 1);

            $response['memberViewType'] = $viewType;

            $response['isShowAppName'] = Engine_Api::_()->getApi('settings', 'core')->getSetting("siteiosapp.app.name", 0);
            $response['showFilterType'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.all.update.show',1);
            $response['is_show_greeting_announcement'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.greeting.announcement',1);
            $autodetectLocation = ($type == 'android') ? Engine_Api::_()->getApi('settings', 'core')->getSetting("siteandroid.autodetect.enable", 0) : Engine_Api::_()->getApi('settings', 'core')->getSetting("siteios.autodetect.enable", 0);

            $isChangeManually = ($type == 'android') ? Engine_Api::_()->getApi('settings', 'core')->getSetting("siteandroid.change.location", 0) : Engine_Api::_()->getApi('settings', 'core')->getSetting("siteios.change.location", 0);
            // @Todo: Remove the following condition after iOS and Android App upgrade.
            if (!empty($getLocations)) {
                $response['restapilocation'] = $this->getLocations();
                $response['restapilocation']['autodetectLocation'] = $autodetectLocation;
                $response['restapilocation']['isChangeManually'] = $isChangeManually;
                $response['seaolocation'] = $this->getSeaoLocations();
            }

            if (!empty($browse_as_guest)) {
                $browse_as_guest = ($type === 'ios') ? Engine_Api::_()->getApi('settings', 'core')->getSetting('siteiosapp.browse.guest', 1) : Engine_Api::_()->getApi('settings', 'core')->getSetting('siteandroidapp.browse.guest', 1);

                $response['browse_as_guest'] = $browse_as_guest;
            }
            $response['isFavouriteEnable'] = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.favourite', 0) ? 1 : 0;
            $response['video_quality'] = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('siteandroidapp.video.quality', 0) ? 1 : 0;

            if ($type === 'ios') {
                $response['siteiosappMode'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteiosapp.current.mode', 1);
                $response['siteiosappSharedSecretKey'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteiosapp.shared.secret');
            }
            
            //check playlist and channel enabled or not
            if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitevideo')) {
                $response['isChannelEnable'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitevideo.channel.allow', 1);
                 $response['isPlaylistEnable'] =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sitevideo.playlist.allow', 1);
            }
            else{
                 $response['isChannelEnable'] =0;
                 $response['isPlaylistEnable']=0;
            }

            // Set isPrimemessengerActive key in response
            if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('primemessenger')) {
                $response['isPrimeMessengerActive'] = Engine_Api::_()->primemessenger()->isPrimeMessengerActive();
            }

            $response['enable_modules'] = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
        } catch (Exception $ex) {
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
        $this->respondWithSuccess($response);
    }

    /**
     * Get locations array
     *
     * @return array $locationMultiOptions
     */
    protected function getLocations() {
        $locationResponse = array();
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $locationDefault = $settings->getSetting('seaocore.locationdefault', '');
        $seaocore_locationspecific = $settings->getSetting('seaocore.locationspecific', '');
        $seaocore_locationspecificcontent = $settings->getSetting('seaocore.locationspecificcontent', '');
        if (!empty($locationDefault))
            $locationResponse['default'] = $locationDefault;

        if (Engine_Api::_()->seaocore()->getLocationsTabs()) {
            if ($seaocore_locationspecific) {
                $locationResponse['locationType'] = 'specific';
                $locations = Engine_Api::_()->getDbTable('locationcontents', 'seaocore')->getLocations(array('status' => 1));
                $locationsArray = array();
                foreach ($locations as $location) {
                    $locationsArray[$location->location] = $location->title;
                }
                if ($locations) {
                    $locationResponse['restapilocation'] = $locationsArray;
                    return $locationResponse;
                }
            } else {
                $locationResponse['locationType'] = 'notspecific';
                return $locationResponse;
            }
        }

        //Default value
        if (!isset($locationResponse['locationType']) || empty($locationResponse['locationType']))
            $locationResponse['locationType'] = 'notspecific';
        return $locationResponse;
    }

    /**
     * Get locations array
     *
     * @return array $locationMultiOptions
     */
    protected function getSeaoLocations() {
        $locationResponse = array();
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $locationDefault = $settings->getSetting('seaocore.locationdefault', '');
        $seaocore_locationspecific = $settings->getSetting('seaocore.locationspecific', '');
        $seaocore_locationspecificcontent = $settings->getSetting('seaocore.locationspecificcontent', '');
        if (!empty($locationDefault))
            $locationResponse['default'] = $locationDefault;

        if (Engine_Api::_()->seaocore()->getLocationsTabs()) {
            if ($seaocore_locationspecific) {
                $locationResponse['locationType'] = 'specific';
                $locations = Engine_Api::_()->getDbTable('locationcontents', 'seaocore')->getLocations(array('status' => 1));
                $locationsArray = array();
                foreach ($locations as $location) {
                    $locationsArray[$location->location] = $location->title;
                }
                if ($locations) {
                    $locationResponse['seaolocation'] = $locationsArray;
                    return $locationResponse;
                }
            } else {
                $locationResponse['locationType'] = 'notspecific';
                return $locationResponse;
            }
        }

        return;
    }

    public function locationSuggestAction() {

        $locationResponse = array();
        $search = $this->getParam('suggest', null);
        $latitude = $this->getParam('latitude', 0);
        $longitude = $this->getParam('longitude', 0);

        $local = Engine_Api::_()->getApi('Location', 'siteapi')->getSuggestGooglePalces($search);

        $this->respondWithSuccess($local);
    }

    /**
     * Get Setting for Guest User Browse 
     *
     * @return array $response
     */
    public function browseAsGuestAction() {
        $this->validateRequestMethod();

        $type = $this->getRequestParam('type', 'android');

        $browse_as_guest = ($type === 'ios') ? Engine_Api::_()->getApi('settings', 'core')->getSetting('siteiosapp.browse.guest', 1) : Engine_Api::_()->getApi('settings', 'core')->getSetting('siteandroidapp.browse.guest', 1);

        $response['browse_as_guest'] = $browse_as_guest;

        $this->respondWithSuccess($response);
    }

    /**
     * Get account setting menus
     *
     * @return array $menuArray
     */
    public function getUserAccountMenuAction() {
        $this->validateRequestMethod();
        $menuArray = array();
        $user = Engine_Api::_()->user()->getViewer();
        $auth_delete = $this->_helper->requireAuth()->setAuthParams($user, null, 'delete')->isValid();
        $isLastSuperAdmin = false;
        if (1 === count(Engine_Api::_()->user()->getSuperAdmins()) && 1 === $user->level_id)
            $isLastSuperAdmin = true;
        $menuArray[] = array(
            'name' => 'general',
            'label' => $this->translate('General'),
            'url' => '/members/settings/general'
        );
        $menuArray[] = array(
            'name' => 'privacy',
            'label' => $this->translate('Privacy'),
            'url' => '/members/settings/privacy'
        );
        $menuArray[] = array(
            'name' => 'network',
            'label' => $this->translate('Networks'),
            'url' => '/members/settings/network'
        );
        $menuArray[] = array(
            'name' => 'notification',
            'label' => $this->translate('Notifications'),
            'url' => '/members/settings/notifications'
        );
        $menuArray[] = array(
            'name' => 'password',
            'label' => $this->translate('Change Password'),
            'url' => '/members/settings/password'
        );
        if ($auth_delete && !$isLastSuperAdmin)
            $menuArray[] = array(
                'name' => 'delete',
                'label' => $this->translate('Delete account'),
                'url' => '/members/settings/delete'
            );
        if (_CLIENT_TYPE && ((_CLIENT_TYPE == 'android') && _ANDROID_VERSION && _ANDROID_VERSION >= '1.8') || ((_CLIENT_TYPE == 'ios') && _IOS_VERSION && _IOS_VERSION >= '1.8.0')) {
            $level = Engine_Api::_()->getItem('authorization_level', $user->level_id);
            if (!in_array($level->type, array('admin', 'moderator'))) {

                // If there are enabled gateways or packages,
                if (Engine_Api::_()->getDbtable('gateways', 'payment')->getEnabledGatewayCount() > 0 &&
                        Engine_Api::_()->getDbtable('packages', 'payment')->getEnabledNonFreePackageCount() > 0) {
                    $menuArray[] = array(
                        'name' => 'subscription',
                        'label' => $this->translate('Subscription'),
                        'url' => '/members/settings/subscriptions'
                    );
                }
            }
        }
        if (_CLIENT_TYPE && (_CLIENT_TYPE == 'android') && _ANDROID_VERSION && _ANDROID_VERSION >= '1.7.3') {
            if (Engine_Api::_()->getApi('settings', 'core')->getSetting("enable.siteandroidapp.sound", 1))
                $menuArray[] = array(
                    'name' => 'sound',
                    'label' => $this->translate('Sounds'),
                );
        }

        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitestore')) {
            $menuArray[] = array(
                'name' => 'mystore',
                'label' => $this->translate('My Store Account'),
                'url' => '/sitestore/orders',
            );
        }

        if (_CLIENT_TYPE && (_CLIENT_TYPE == 'android') && _ANDROID_VERSION && _ANDROID_VERSION >= '1.7.7.1') {
            $level = Engine_Api::_()->getItem('authorization_level', $user->level_id);
            if (!in_array($level->type, array('admin', 'moderator'))) {
                // If there are enabled gateways or packages,
                if (Engine_Api::_()->getDbtable('gateways', 'payment')->getEnabledGatewayCount() > 0 &&
                        Engine_Api::_()->getDbtable('packages', 'payment')->getEnabledNonFreePackageCount() > 0) {
                    $menuArray[] = array(
                        'name' => 'subscription',
                        'label' => $this->translate('Subscription'),
                        'url' => '/members/settings/subscriptions'
                    );
                }
            }
        }
        if (_CLIENT_TYPE && (_CLIENT_TYPE == 'android') && _ANDROID_VERSION && _ANDROID_VERSION >= '1.7.3') {
            if (Engine_Api::_()->getApi('settings', 'core')->getSetting("enable.siteandroidapp.sound", 1))
                $menuArray[] = array(
                    'name' => 'sound',
                    'label' => $this->translate('Sounds'),
                );
        }
        $table = Engine_Api::_()->getDbtable('menuItems', 'core');
        $select = $table->select()
                ->where('enabled = ?', 1)
                ->where('name like ' . "'%user_settings%'");

        $menus = $table->fetchAll($select);
        foreach ($menus as $menu) {
            $labelArray[] = $this->translate($menu['label']);
        }
        foreach ($menuArray as $menu) {
            if (!in_array($menu['label'], $labelArray)) {

                continue;
            } else
                $menuArray1[] = $menu;
        }
        $this->respondWithSuccess($menuArray1);
    }

    /**
     * Report to site administrator.
     *
     * @return array
     */
    public function reportCreateAction() {
        if ($this->getRequest()->isGet() && !$this->getRequestParam("category") && !$this->getRequestParam("description")) {
            $this->respondWithSuccess(Engine_Api::_()->getApi('Siteapi_Core', 'core')->getReportForm());
        } else if ($this->getRequest()->isPost()) {
            $data = array();
            $data["category"] = $this->getRequestParam("category");
            $data["description"] = $this->getRequestParam("description");
            $type = $this->getRequestParam('type');
            $id = $this->getRequestParam('id');

            // Make a subject
            $subject = Engine_Api::_()->getItem($type, $id);

            // Form validation
            $validators = Engine_Api::_()->getApi('Siteapi_FormValidators', 'core')->getReportFormValidators();
            $data['validators'] = $validators;
            $validationMessage = $this->isValid($data);
            if (!empty($validationMessage) && @is_array($validationMessage)) {
                $this->respondWithValidationError('validation_fail', $validationMessage);
            }

            // Process
            $table = Engine_Api::_()->getItemTable('core_report');
            $db = $table->getAdapter();
            $db->beginTransaction();

            try {
                $viewer = Engine_Api::_()->user()->getViewer();

                $report = $table->createRow();
                $report->category = $data["category"];
                $report->description = $data["description"];
                $report->subject_type = $subject->getType();
                $report->subject_id = $subject->getIdentity();
                $report->user_id = $viewer->getIdentity();
                $report->save();

                // Increment report count
                Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.reports');

                $db->commit();
                $this->successResponseNoContent('no_content');
            } catch (Exception $e) {
                $db->rollBack();
                $this->respondWithValidationError('internal_server_error', $e->getMessage());
            }
        } else {
            $this->respondWithValidationError('internal_server_error');
        }
    }

    /*
     *  ******************* START APIS OF [Like and Comments] *********************
     */

    /**
     * Get the likes and comment information respective to any content.
     *
     * @return array
     */
    public function likesCommentsAction() {
        // Validate request methods
        $this->validateRequestMethod();

        $allComments = array();
        $type = $this->getRequestParam('subject_type');
        $siteapiLikeComments = Zend_Registry::isRegistered('siteapiLikeComments') ? Zend_Registry::get('siteapiLikeComments') : null;
        $type = empty($type) ? $this->getRequestParam('content_type') : $type;
        if (empty($type) || empty($siteapiLikeComments))
            $this->respondWithError('no_record');

        $id = $this->getRequestParam('subject_id');
        $id = empty($id) ? $this->getRequestParam('content_id') : $id;
        if (empty($id) || empty($siteapiLikeComments))
            $this->respondWithError('no_record');

        $limit = $this->getRequestParam('limit', 10);
        $page = $this->getRequestParam('page');
        $comment_id = $this->getRequestParam('comment_id');
        $commentLikes = false;

        $subject = Engine_Api::_()->getItem($type, $id);
        $bodyParams = $likeUsersArray = array();

        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity() ||
                (!method_exists($subject, 'comments') && !method_exists($subject, 'likes')))
            $this->respondWithError('no_record');

        // Perms
        $viewer = Engine_Api::_()->user()->getViewer();
        $canComment = $subject->authorization()->isAllowed($viewer, 'comment');
        $canDelete = $subject->authorization()->isAllowed($viewer, 'edit');

        // $canComment & $canDelete variable need to be assigned diffrent values for sitegroup
        if (strpos($subject->getType(), "sitegroup") !== false) {
            if ($subject->getType() == 'sitegroup_group') {
                $groupSubject = $subject;
            } elseif ($subject->getType() == 'sitegroupmusic_playlist') {
                $groupSubject = $subject->getParentType();
            } elseif ($subject->getType() == 'sitegroupnote_photo') {
                $groupSubject = $subject->getParent()->getParent()->getParent();
            } elseif ($subject->getType() == 'sitegroupevent_photo') {
                $groupSubject = $subject->getEvent()->getParentPage();
            } else {
                $groupSubject = $subject->getParent();
            }
            $groupApi = Engine_Api::_()->sitegroup();
            $canComment = $groupApi->isManageAdmin($groupSubject, 'comment');
            $canDelete = $groupApi->isManageAdmin($groupSubject, 'edit');
        }

        // Likes    
        $likes = $subject->likes()->getLikePaginator();
        $isLike = Engine_Api::_()->getDbTable("likes", "core")->isLike($subject, $viewer);

        // RETURN THE LIKES USERS ARRAY.
        if ($this->getRequestParam('viewAllLikes', 1) && !empty($comment_id)) {
            $commentLikes = true;
            $tableName = (strstr($type, "activity")) ? "activity_comment" : "core_comment";
            $comment = Engine_Api::_()->getItem($tableName, $comment_id);
            if (empty($comment))
                $this->respondWithError('no_record');

            $viewAllLikes = $this->getRequestParam('viewAllLikes', 1);
            if (!empty($viewAllLikes)) {
                $userObject = $comment->likes()->getAllLikesUsers();
                foreach ($userObject as $user) {
                    $tempUserArray = Engine_Api::_()->getApi('Core', 'siteapi')->validateUserArray($user);
                    $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($user);
                    $tempUserArray = array_merge($tempUserArray, $getContentImages);
                    $likeUsersArray[] = $tempUserArray;
                }
                $bodyParams['viewAllLikesBy'] = $likeUsersArray;
            }
        }

        if (empty($commentLikes)) {
            // If has a page, display oldest to newest
            if (null !== $page) {
                $commentSelect = $subject->comments()->getCommentSelect();
                $commentSelect->order('comment_id ' . $this->getRequestParam('order', 'ASC'));
                $comments = Zend_Paginator::factory($commentSelect);
                $comments->setCurrentPageNumber($page);
                $comments->setItemCountPerPage($limit);
            } else {
                // If not has a page, show the
                $commentSelect = $subject->comments()->getCommentSelect();
                $commentSelect->order('comment_id DESC');
                $comments = Zend_Paginator::factory($commentSelect);
                $comments->setCurrentPageNumber(1);
                $comments->setItemCountPerPage(4);
            }

            // Hide if can't post
            if (!$canComment && !$canDelete)
                $this->respondWithError('unauthorized');

            $getTotalCommentCount = $comments->getTotalItemCount();

            // RETURN THE LIKES USERS ARRAY.
            $viewAllLikes = $this->getRequestParam('viewAllLikes', 1);
            if (!empty($viewAllLikes)) {
                $userObject = $subject->likes()->getAllLikesUsers();
                foreach ($userObject as $user) {
                    $tempUserArray = Engine_Api::_()->getApi('Core', 'siteapi')->validateUserArray($user);
                    $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($user);
                    $tempUserArray = array_merge($tempUserArray, $getContentImages);
                    $likeUsersArray[] = $tempUserArray;
                }
                $bodyParams['viewAllLikesBy'] = $likeUsersArray;
            }

            // RETURN THE COMMENTS ARRAY.
            $viewAllComments = $this->getRequestParam('viewAllComments', 1);
            if (!empty($viewAllComments)) {
                // Iterate over the comments backwards (or forwards!)
                $comments = $comments->getIterator();
                if ($page) {
                    $i = 0;
                    $l = count($comments) - 1;
                    $d = 1;
                    $e = $l + 1;
                } else {
                    $i = count($comments) - 1;
                    $l = count($comments);
                    $d = -1;
                    $e = -1;
                }

                for (; $i != $e; $i += $d) {
                    $comment = $comments[$i];

                    $a = $comment->likes()->getAllLikesUsers();
                    foreach ($a as $user) {
                        $tempUserArray = Engine_Api::_()->getApi('Core', 'siteapi')->validateUserArray($user);
                        $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($user);
                        $tempUserArray = array_merge($tempUserArray, $getContentImages);
                        $likeUsersArray[] = $tempUserArray;
                    }

                    $poster = Engine_Api::_()->getItem($comment->poster_type, $comment->poster_id);
                    $commentInfo["comment_id"] = $comment->comment_id;
                    $commentInfo["user_id"] = $poster->getIdentity();
                    $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($poster, false, 'author');
                    $commentInfo = array_merge($commentInfo, $getContentImages);
                    $commentInfo["author_title"] = $poster->getTitle();
                    $commentInfo["comment_body"] = $comment->body;
                    $commentInfo["comment_date"] = $comment->creation_date;
                    $commentInfo["like_count"] = $comment->likes()->getLikeCount();


                    if ($poster->isSelf($viewer)) {
                        $commentInfo["delete"] = array(
                            "name" => "delete",
                            "label" => $this->translate('Delete'),
                            "url" => "comment-delete",
                            'urlParams' => array(
                                "subject_type" => $subject->getType(),
                                "subject_id" => $subject->getIdentity(),
                                "comment_id" => $comment->comment_id
                            )
                        );
                    } else {
                        $commentInfo["delete"] = null;
                    }

                    if (!empty($canComment)) {
                        $isLiked = $comment->likes()->isLike($viewer);
                        if (empty($isLiked)) {
                            $likeInfo["name"] = "like";
                            $likeInfo["label"] = $this->translate('Like');
                            $likeInfo["url"] = "like";
                            $likeInfo['urlParams'] = array(
                                "subject_type" => $subject->getType(),
                                "subject_id" => $subject->getIdentity(),
                                "comment_id" => $comment->getIdentity()
                            );

                            $likeInfo["isLike"] = 0;
                        } else {
                            $likeInfo["name"] = "unlike";
                            $likeInfo["label"] = $this->translate('Unlike');
                            $likeInfo["url"] = "unlike";
                            $likeInfo['urlParams'] = array(
                                "subject_type" => $subject->getType(),
                                "subject_id" => $subject->getIdentity(),
                                "comment_id" => $comment->getIdentity()
                            );
                            $likeInfo["isLike"] = 1;
                        }

                        $commentInfo["like"] = $likeInfo;
                    } else {
                        $commentInfo["like"] = null;
                    }

                    $allComments[] = $commentInfo;
                }

                $bodyParams['viewAllComments'] = $allComments;
            }
        }

        // FOLLOWING ARE THE GENRAL INFORMATION OF THE PLUGIN, WHICH WILL RETURN IN EVERY CALLING.
        $bodyParams['isLike'] = !empty($isLike) ? 1 : 0;
        $bodyParams['canComment'] = $canComment;
        $bodyParams['canDelete'] = $canDelete;
        $bodyParams['getTotalComments'] = $getTotalCommentCount;
        $bodyParams['getTotalLikes'] = $likes->getTotalItemCount();

        if (!empty($siteapiLikeComments))
            $this->respondWithSuccess($bodyParams);
    }

    /**
     * Like to content and comment
     *
     * @return array
     */
    public function likeAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        $type = $this->getRequestParam('subject_type');
        $id = $this->getRequestParam('subject_id');
        $sendAppNotification = $this->getRequestParam('sendNotification', 1);


        $siteapiGlobalView = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.global.view', 0);
        $siteapiLSettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.lsettings', 0);
        $siteapiInfoType = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.androiddevice.type', 0);
        $siteapiGlobalType = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.global.type', 0);
        $siteapiLikeComments = Zend_Registry::isRegistered('siteapiLikeComments') ? Zend_Registry::get('siteapiLikeComments') : null;
        $subject = Engine_Api::_()->getItem($type, $id);

        if (empty($subject) || empty($siteapiLikeComments))
            $this->respondWithError('no_record');

        $viewer = Engine_Api::_()->user()->getViewer();
        $comment_id = $this->getRequestParam('comment_id');

        if ($comment_id) {
            $commentedItem = $subject->comments()->getComment($comment_id);
            $isLike = Engine_Api::_()->getDbTable("likes", "core")->isLike($commentedItem, $viewer);
            if (!empty($isLike))
                $this->respondWithError('already_liked');
        } else {
            $commentedItem = $subject;
            $isLike = Engine_Api::_()->getDbTable("likes", "core")->isLike($subject, $viewer);
            if (!empty($isLike))
                $this->respondWithError('already_liked');
        }

        if (empty($siteapiGlobalType)) {
            for ($check = 0; $check < strlen($siteapiLSettings); $check++) {
                $tempSitemenuLtype += @ord($siteapiLSettings[$check]);
            }
            $tempSitemenuLtype = $tempSitemenuLtype + $siteapiGlobalView;
        }


        // Process
        $db = $commentedItem->likes()->getAdapter();
        $db->beginTransaction();
        try {
            $commentedItem->likes()->addLike($viewer);

            // Add notification
            $owner = $commentedItem->getOwner();

            if (isset($sendAppNotification) && !empty($sendAppNotification)) {
                if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
                    Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($owner, $viewer, $commentedItem, 'liked', array(
                        'label' => $commentedItem->getShortType()
                    ));
                }
            }
            // Stats
            Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.likes');

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }

        // For comments, render the resource
        if ($subject->getType() == 'core_comment') {
            $type = $subject->resource_type;
            $id = $subject->resource_id;
            Engine_Api::_()->core()->clearSubject();
        } else {
            $type = $subject->getType();
            $id = $subject->getIdentity();
        }

//    $bodyArray["name"] = "unlike";
//    $bodyArray["label"] = $this->translate('Unlike');
//    $bodyArray["isLike"] = 1;
//
//    if ( !empty($comment_id) ) {
//      $bodyArray["url"] = "unlike";
//      $bodyArray["urlParams"] = array(
//          "subject_type" => $subject->getType(),
//          "subject_id" => $subject->getIdentity(),
//          "comment_id" => $comment_id
//      );
//    } else {
//      $bodyArray["url"] = "unlike";
//      $bodyArray["urlParams"] = array(
//          "subject_type" => $subject->getType(),
//          "subject_id" => $subject->getIdentity()
//      );
//    }
//
//    $this->respondWithSuccess($bodyArray);

        if (!empty($tempSitemenuLtype) && ($tempSitemenuLtype != $siteapiInfoType)) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting('siteapi.viewtypeinfo.type', 1);
        } else {
            if (!empty($siteapiLikeComments)) {
                $this->successResponseNoContent('no_content');
            }
        }
    }

    /**
     * Unlike to content and comment
     *
     * @return array
     */
    public function unlikeAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        $type = $this->getRequestParam('subject_type');
        $id = $this->getRequestParam('subject_id');

        $siteapiLikeComments = Zend_Registry::isRegistered('siteapiLikeComments') ? Zend_Registry::get('siteapiLikeComments') : null;
        $subject = Engine_Api::_()->getItem($type, $id);

        if (empty($subject) || empty($siteapiLikeComments))
            $this->respondWithError('no_record');


        $viewer = Engine_Api::_()->user()->getViewer();
        $comment_id = $this->getRequestParam('comment_id');

        if ($comment_id) {
            $commentedItem = $subject->comments()->getComment($comment_id);
            $isLike = Engine_Api::_()->getDbTable("likes", "core")->isLike($commentedItem, $viewer);
            if (empty($isLike))
                $this->respondWithError('already_unliked');
        } else {
            $commentedItem = $subject;
            $isLike = Engine_Api::_()->getDbTable("likes", "core")->isLike($subject, $viewer);
            if (empty($isLike))
                $this->respondWithError('already_unliked');
        }
        // Process
        $db = $commentedItem->likes()->getAdapter();
        $db->beginTransaction();
        try {
            $commentedItem->likes()->removeLike($viewer);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }

        // For comments, render the resource
        if ($subject->getType() == 'core_comment') {
            $type = $subject->resource_type;
            $id = $subject->resource_id;
            Engine_Api::_()->core()->clearSubject();
        } else {
            $type = $subject->getType();
            $id = $subject->getIdentity();
        }

//    $bodyArray["name"] = "like";
//    $bodyArray["label"] = $this->translate('Like');
//    $bodyArray["isLike"] = 0;
//
//    if ( !empty($comment_id) ) {
//      $bodyArray["url"] = "like";
//      $bodyArray["urlParams"] = array(
//          "subject_type" => $subject->getType(),
//          "subject_id" => $subject->getIdentity(),
//          "comment_id" => $comment_id
//      );
//    } else {
//      $bodyArray["url"] = "like";
//      $bodyArray["urlParams"] = array(
//          "subject_type" => $subject->getType(),
//          "subject_id" => $subject->getIdentity()
//      );
//    }
//
//    $this->respondWithSuccess($bodyArray);
        if (!empty($siteapiLikeComments))
            $this->successResponseNoContent('no_content');
    }

    /**
     * Comment post to content
     *
     * @return array
     */
    public function commentCreateAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');
        Engine_Api::_()->getApi('Core', 'siteapi')->setTranslate();
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        $type = $this->getRequestParam('subject_type');
        $id = $this->getRequestParam('subject_id');
        $body = $this->getRequestParam('body');

        $viewer = Engine_Api::_()->user()->getViewer();

        $subject = Engine_Api::_()->getItem($type, $id);
        $siteapiCommentCreate = Zend_Registry::isRegistered('siteapiCommentCreate') ? Zend_Registry::get('siteapiCommentCreate') : null;
        $send_notification = $this->getRequestParam('send_notification', 1);

        if (!empty($subject))
            $canComment = $subject->authorization()->isAllowed($viewer, 'comment');

        if (empty($siteapiCommentCreate) || empty($canComment) || empty($subject) || empty($body))
            $this->respondWithError('no_record');

        // Filter HTML
        $filter = new Zend_Filter();
        $filter->addFilter(new Engine_Filter_Censor());
        $filter->addFilter(new Engine_Filter_HtmlSpecialChars());

        $body = $filter->filter($body);

        $db = $subject->comments()->getCommentTable()->getAdapter();
        $db->beginTransaction();

        try {
            $comment = $subject->comments()->addComment($viewer, $body);

            if (isset($send_notification) && !empty($send_notification)) {

                $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
                $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
                $subjectOwner = $subject->getOwner('user');

                // Activity
                $action = $activityApi->addActivity($viewer, $subject, 'comment_' . $subject->getType(), '', array(
                    'owner' => $subjectOwner->getGuid(),
                    'body' => $body
                ));

                // Notifications
                // Add notification for owner (if user and not viewer)
                if ($subjectOwner->getType() == 'user' && $subjectOwner->getIdentity() != $viewer->getIdentity()) {
                    Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($subjectOwner, $viewer, $subject, 'commented', array(
                        'label' => $subject->getShortType()
                    ));
                }

                // Add a notification for all users that commented or like except the viewer and poster
                // @todo we should probably limit this
                $commentedUserNotifications = array();
                foreach ($subject->comments()->getAllCommentsUsers() as $notifyUser) {
                    if ($notifyUser->getIdentity() == $viewer->getIdentity() || $notifyUser->getIdentity() == $subjectOwner->getIdentity())
                        continue;

                    // Don't send a notification if the user both commented and liked this
                    $commentedUserNotifications[] = $notifyUser->getIdentity();

                    Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($notifyUser, $viewer, $subject, 'commented_commented', array(
                        'label' => $subject->getShortType()
                    ));
                }

                // Add a notification for all users that liked
                // @todo we should probably limit this
                foreach ($subject->likes()->getAllLikesUsers() as $notifyUser) {
                    // Skip viewer and owner
                    if ($notifyUser->getIdentity() == $viewer->getIdentity() || $notifyUser->getIdentity() == $subjectOwner->getIdentity())
                        continue;

                    // Don't send a notification if the user both commented and liked this
                    if (in_array($notifyUser->getIdentity(), $commentedUserNotifications))
                        continue;

                    Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($notifyUser, $viewer, $subject, 'liked_commented', array(
                        'label' => $subject->getShortType()
                    ));
                }
            }

            // Increment comment count
            $canComment = $subject->authorization()->isAllowed($viewer, 'comment');
            $canDelete = $subject->authorization()->isAllowed($viewer, 'edit');

            Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.comments');
            $commentInfo = array();
            if (!empty($comment)) {
//        $getHosts = Engine_Api::_()->getApi('Core', 'siteapi')->getHost();
                $poster = Engine_Api::_()->getItem($comment->poster_type, $comment->poster_id);
                $commentInfo["comment_id"] = $comment->comment_id;
                $commentInfo["user_id"] = $poster->getIdentity();
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($poster);
                $commentInfo = array_merge($commentInfo, $getContentImages);
                //to provide the same image names as in likes-comment response
                $getContentImages = array();
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($poster, false, 'author');
                $commentInfo = array_merge($commentInfo, $getContentImages);
                $commentInfo["author_title"] = $poster->getTitle();
                $commentInfo["comment_body"] = $comment->body;
                $commentInfo["comment_date"] = $comment->creation_date;

//                if (!empty($canDelete) || $poster->isSelf($viewer)) {
                if ($poster->isSelf($viewer)) {
                    $commentInfo["delete"] = array(
                        "name" => "delete",
                        "label" => $this->translate('Delete'),
                        "url" => "comment-delete",
                        'urlParams' => array(
                            "subject_type" => $subject->getType(),
                            "subject_id" => $subject->getIdentity(),
                            "comment_id" => $comment->comment_id
                        )
                    );
                } else {
                    $commentInfo["delete"] = null;
                }

                if (!empty($canComment)) {
                    $isLiked = $comment->likes()->isLike($viewer);
                    if (empty($isLiked)) {
                        $likeInfo["name"] = "like";
                        $likeInfo["label"] = $this->translate('Like');
                        $likeInfo["url"] = "like";
                        $likeInfo["urlParams"] = array(
                            "subject_type" => $subject->getType(),
                            "subject_id" => $subject->getIdentity(),
                            "comment_id" => $comment->getIdentity()
                        );
                        $likeInfo["isLike"] = 0;
                    } else {
                        $likeInfo["name"] = "unlike";
                        $likeInfo["label"] = $this->translate('Unlike');
                        $likeInfo["url"] = "unlike";
                        $likeInfo["urlParams"] = array(
                            "subject_type" => $subject->getType(),
                            "subject_id" => $subject->getIdentity(),
                            "comment_id" => $comment->getIdentity()
                        );
                        $likeInfo["isLike"] = 1;
                    }
                    $commentInfo["like_count"] = $comment->likes()->getLikeCount();
                    $commentInfo["like"] = $likeInfo;
                } else {
                    $commentInfo["like"] = null;
                }

                $db->commit();
                $this->respondWithSuccess($commentInfo);
            } else {
                $this->respondWithValidationError('internal_server_error', 'Problem in comment');
            }
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
    }

    public function addCommentNotificationsAction() {

        // Validate request methods
        $this->validateRequestMethod('POST');

        $type = $this->getRequestParam('subject_type');
        $id = $this->getRequestParam('subject_id');

        $viewer = Engine_Api::_()->user()->getViewer();

        $subject = Engine_Api::_()->getItem($type, $id);

        $comment_id = $this->getRequestParam('comment_id');

        if ($comment_id)
            $comment = $subject->comments()->getComment($comment_id);

        $siteapiCommentCreate = Zend_Registry::isRegistered('siteapiCommentCreate') ? Zend_Registry::get('siteapiCommentCreate') : null;

        if (!empty($subject))
            $canComment = $subject->authorization()->isAllowed($viewer, 'comment');

        if (empty($siteapiCommentCreate) || empty($canComment) || empty($subject) || empty($comment))
            $this->respondWithError('no_record');
        try {
            $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
            $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
            $subjectOwner = $subject->getOwner('user');

            // Notifications
            // Add notification for owner (if user and not viewer)
            if ($subjectOwner->getType() == 'user' && $subjectOwner->getIdentity() != $viewer->getIdentity()) {
                Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($subjectOwner, $viewer, $subject, 'commented', array(
                    'label' => $subject->getShortType()
                ));
            }

            // Add a notification for all users that commented or like except the viewer and poster
            // @todo we should probably limit this
            $commentedUserNotifications = array();
            foreach ($subject->comments()->getAllCommentsUsers() as $notifyUser) {
                if ($notifyUser->getIdentity() == $viewer->getIdentity() || $notifyUser->getIdentity() == $subjectOwner->getIdentity())
                    continue;

                // Don't send a notification if the user both commented and liked this
                $commentedUserNotifications[] = $notifyUser->getIdentity();

                Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($notifyUser, $viewer, $subject, 'commented_commented', array(
                    'label' => $subject->getShortType()
                ));
            }

            // Add a notification for all users that liked
            // @todo we should probably limit this
            foreach ($subject->likes()->getAllLikesUsers() as $notifyUser) {
                // Skip viewer and owner
                if ($notifyUser->getIdentity() == $viewer->getIdentity() || $notifyUser->getIdentity() == $subjectOwner->getIdentity())
                    continue;

                // Don't send a notification if the user both commented and liked this
                if (in_array($notifyUser->getIdentity(), $commentedUserNotifications))
                    continue;

                Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($notifyUser, $viewer, $subject, 'liked_commented', array(
                    'label' => $subject->getShortType()
                ));
            }

            $this->successResponseNoContent('no_content');
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
    }

    public function isSitevideoPluginEnabledAction() {
        // Validate request methods
        $this->validateRequestMethod();

        $videoModuleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitevideo');
        $videoIntegrationModuleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitevideointegration');

        if (empty($videoModuleEnabled) || empty($videoIntegrationModuleEnabled)) {
            $response['sitevideoPluginEnabled'] = 0;
            $response['canCreateVideo'] = 0;
            $this->respondWithSuccess($response, true);
        }

        //GET VIEWER DETAILS
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        $subject_type = $this->_getParam('subject_type');
        $subject_id = $this->_getParam('subject_id');

        if (empty($subject_id) || empty($subject_type))
            $this->respondWithError('no_record');

        //GET VIDEO SUBJECT
        $subject = Engine_Api::_()->getItem($subject_type, $subject_id);
        Engine_Api::_()->core()->setSubject($subject);

        $moduleName = $moduleName = strtolower($subject->getModuleName());
        $getShortType = ucfirst($subject->getShortType());

        if ($moduleName == 'sitereview' && isset($subject->listingtype_id)) {
            if (!(Engine_Api::_()->getDbtable('modules', 'sitevideo')->getIntegratedModules(array('enabled' => 1, 'item_type' => 'sitereview_listing_' . $subject->listingtype_id, 'item_module' => 'sitereview', 'checked' => 'enabled')))) {
                $response['sitevideoPluginEnabled'] = 0;
                $response['canCreateVideo'] = 0;
                $this->respondWithSuccess($response, true);
            }
        } else {
            if (!(Engine_Api::_()->getDbtable('modules', 'sitevideo')->getIntegratedModules(array('enabled' => 1, 'item_type' => $subject->getType(), 'item_module' => strtolower($subject->getModuleName()), 'checked' => 'enabled')))) {
                $response['sitevideoPluginEnabled'] = 0;
                $response['canCreateVideo'] = 0;
                $this->respondWithSuccess($response, true);
            }
        }
        $params['parent_type'] = $subject->getType();
        $params['parent_id'] = $subject->getIdentity();

        if ($moduleName == 'sitepage' || $moduleName == 'sitebusiness' || $moduleName == 'sitegroup' || $moduleName == 'sitestore') {
            $isModuleOwnerAllow = 'is' . $getShortType . 'OwnerAllow';
            $videoCount = Engine_Api::_()->$moduleName()->getTotalCount($subject->getIdentity(), 'sitevideo', 'videos');

            //START PACKAGE WORK
            if (Engine_Api::_()->$moduleName()->hasPackageEnable()) {
                if (!Engine_Api::_()->$moduleName()->allowPackageContent($subject->package_id, "modules", $moduleName . 'video')) {
                    $response['sitevideoPluginEnabled'] = 0;
                    $response['canCreateVideo'] = 0;
                    $this->respondWithSuccess($response, true);
                }
            } else {
                $isOwnerAllow = Engine_Api::_()->$moduleName()->$isModuleOwnerAllow($subject, 'svcreate');
                if (empty($isOwnerAllow)) {
                    $response['sitevideoPluginEnabled'] = 0;
                    $response['canCreateVideo'] = 0;
                    $this->respondWithSuccess($response, true);
                }
            }

            //START MANAGE-ADMIN CHECK
            $isManageAdmin = Engine_Api::_()->$moduleName()->isManageAdmin($subject, 'view');
            if (empty($isManageAdmin)) {
                $response['sitevideoPluginEnabled'] = 0;
                $response['canCreateVideo'] = 0;
                $this->respondWithSuccess($response, true);
            }

            if (empty($videoCount)) {
                $response['sitevideoPluginEnabled'] = 0;
                $response['canCreateVideo'] = 0;
                $this->respondWithSuccess($response, true);
            }
        } else if ($moduleName == 'siteevent') {
            $videoCount = Engine_Api::_()->$moduleName()->getTotalCount($subject->getIdentity(), 'sitevideo', 'videos');
            //AUTHORIZATION CHECK
            if (empty($videoCount)) {
                $response['sitevideoPluginEnabled'] = 0;
                $response['canCreateVideo'] = 0;
                $this->respondWithSuccess($response, true);
            }
        } else if ($moduleName == 'sitereview') {
            //AUTHORIZATION CHECK
            $table = Engine_Api::_()->getDbtable('videos', 'sitevideo');

            $videoCount = $count = $table
                    ->select()
                    ->from($table->info('name'), array('count(*) as count'))
                    ->where("parent_type = ?", 'sitereview_listing_' . $subject->listingtype_id)
                    ->where("parent_id =?", $subject->getIdentity())
                    ->query()
                    ->fetchColumn();

            if (empty($videoCount)) {
                $response['sitevideoPluginEnabled'] = 0;
                $response['canCreateVideo'] = 0;
                $this->respondWithSuccess($response, true);
            }
        }
        
        if(isset($viewer_id) && !empty($viewer_id))
            $response['canCreateVideo'] = 1;

        $response['sitevideoPluginEnabled'] = 1;
        $response['totalItemCount'] = $videoCount;
        $this->respondWithSuccess($response, true);
    }

    /**
     * Delete posted comment
     *
     * @return array
     */
    public function commentDeleteAction() {
        // Validate request methods
        $this->validateRequestMethod('DELETE');

        $type = $this->getRequestParam('subject_type');
        $id = $this->getRequestParam('subject_id');
        $comment_id = $this->getRequestParam('comment_id');

        if (empty($type) || empty($id) || empty($comment_id))
            $this->respondWithError('no_record');

        $viewer = Engine_Api::_()->user()->getViewer();
        $subject = Engine_Api::_()->getItem($type, $id);
        $comment = Engine_Api::_()->getItem("core_comment", $comment_id);
        $poster = Engine_Api::_()->getItem($comment->poster_type, $comment->poster_id);

        if (!empty($subject))
            $canDelete = $subject->authorization()->isAllowed($viewer, 'edit');

        if (!$poster->isSelf($viewer) && empty($canDelete))
            $this->respondWithError('unauthorized');

        // Process
        $db = $subject->comments()->getCommentTable()->getAdapter();
        $db->beginTransaction();
        try {
            $subject->comments()->removeComment($comment_id);
            $db->commit();

            $this->successResponseNoContent('no_content');
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
    }

    /*
     *  ******************* END APIS OF [Like and Comments] *********************
     */

    /**
     * Get language array
     *
     * @return array $localeMultiOptions
     */
    protected function getLanguages($type) {
        // Set the translations for zend library.
        if (!Zend_Registry::isRegistered('Zend_Translate'))
            Engine_Api::_()->getApi('Core', 'siteapi')->setTranslate();

        //PREPARE LANGUAGE LIST
        $languageList = Zend_Registry::get('Zend_Translate')->getList();
        $appConfiguredLanguage = $this->_languageMultioptions($type);

        //PREPARE DEFAULT LANGUAGE
        $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en');
        if (!in_array($defaultLanguage, $languageList)) {
            if ($defaultLanguage == 'auto' && isset($languageList['en'])) {
                $defaultLanguage = 'en';
            } else {
                $defaultLanguage = '';
            }
        }

        //INIT DEFAULT LOCAL
        $viewer = Engine_Api::_()->user()->getViewer();
        $local = ($viewer->getIdentity()) ? $viewer->locale : 'auto';
        $localeObject = new Zend_Locale($local); //Zend_Registry::get('Locale');
        $languages = Zend_Locale::getTranslationList('language', $localeObject);
        $territories = Zend_Locale::getTranslationList('territory', $localeObject);

        $localeMultiOptions = array();
        foreach ($languageList as $key) {
            $languageName = null;
            if (!empty($languages[$key])) {
                $languageName = $languages[$key];
            } else {
                $tmpLocale = new Zend_Locale($key);
                $region = $tmpLocale->getRegion();
                $language = $tmpLocale->getLanguage();
                if (!empty($languages[$language]) && !empty($territories[$region])) {
                    $languageName = $languages[$language] . ' (' . $territories[$region] . ')';
                }
            }
            if (_CLIENT_TYPE && (_CLIENT_TYPE == 'android') && _ANDROID_VERSION && _ANDROID_VERSION > '1.7') {
                if (array_key_exists($key, $appConfiguredLanguage)) {
                    if ($languageName) {
                        $localeMultiOptions[$key] = $languageName;
                    } else {
                        $localeMultiOptions[$key] = Zend_Registry::get('Zend_Translate')->_('Unknown');
                    }
                }
            } else {
                if ($languageName) {
                    $localeMultiOptions[$key] = $languageName;
                } else {
                    $localeMultiOptions[$key] = Zend_Registry::get('Zend_Translate')->_('Unknown');
                }
            }
        }

        // Get default language
        $defaultLanguage = ($viewer->getIdentity()) ? $viewer->language : $defaultLanguage;

        return array(
            'default' => $defaultLanguage,
            'languages' => $localeMultiOptions
        );
    }

    /*
     * Get the view type for browse and profile page
     * 
     * @param $listingtype_id int
     * @param $viewType int
     * @return int
     */

    private function _getViewTypeLabel($listingtype_id, $viewType = 2) {
        // Make a object of listing type
        $db = Engine_Db_Table::getDefaultAdapter();
        $select = new Zend_Db_Select($db);
        if (_CLIENT_TYPE && (_CLIENT_TYPE == 'ios')) {
            $select->from('engine4_siteiosapp_listingtypeViewMaps')
                    ->where('listingtype_id = ?', $listingtype_id);
        } else {
            $select->from('engine4_siteandroidapp_listingtypeViewMaps')
                    ->where('listingtype_id = ?', $listingtype_id);
        }
        $row = $select->query()->fetchObject();

        /*
         * 1 for listing profile type
         * 2 for listing browse type
         */
        if (isset($viewType) && $viewType == 1) {
            return (!isset($row->profileView_id)) ? 3 : $row->profileView_id;
        } else if (isset($viewType) && $viewType == 2) {
            return (!isset($row->browseView_id)) ? 2 : $row->browseView_id;
        }

        return;
    }

    private function _languageMultioptions($type = 'android') {
        $getWebsiteName = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
        $websiteStr = str_replace(".", "-", $getWebsiteName);

        $this->_directoryName = ($type == 'ios') ? 'ios-' . $websiteStr . '-app-builder' : 'android-' . $websiteStr . '-app-builder';
        $this->_getAPPBuilderBaseURL = 'public/' . $this->_directoryName;

        //Get available language file.
        if ($type == 'ios') {
            foreach ($this->_getAPPLanguageDetailsForUpload($type) as $key => $values) {
                @chmod(APPLICATION_PATH . DIRECTORY_SEPARATOR . $this->_getAPPBuilderBaseURL . DIRECTORY_SEPARATOR . $values['directoryName'] . '/' . $values['fileName'] . '.strings', 0777);

                if (@file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . $this->_getAPPBuilderBaseURL . DIRECTORY_SEPARATOR . $values['directoryName'] . '/' . $values['fileName'] . '.strings'))
                    $getDefaultAvailableLanguages[$key] = $values;
                else if (@file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . $this->_getAPPBuilderBaseURL . DIRECTORY_SEPARATOR . $values['directoryName'] . '/' . $values['fileName'] . '.csv'))
                    $getDefaultAvailableLanguages[$key] = $values;
            }
        }
        else {
            foreach ($this->_getAPPLanguageDetailsForUpload($type) as $key => $values) {
                @chmod(APPLICATION_PATH . DIRECTORY_SEPARATOR . $this->_getAPPBuilderBaseURL . DIRECTORY_SEPARATOR . $values['directoryName'] . '/' . $values['fileName'] . '.xml', 0777);

                if (@file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . $this->_getAPPBuilderBaseURL . DIRECTORY_SEPARATOR . $values['directoryName'] . '/' . $values['fileName'] . '.xml'))
                    $getDefaultAvailableLanguages[$key] = $values;
                else if (@file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . $this->_getAPPBuilderBaseURL . DIRECTORY_SEPARATOR . $values['directoryName'] . '/' . $values['fileName'] . '.csv'))
                    $getDefaultAvailableLanguages[$key] = $values;
            }
        }

        $getAPPLanguageDetailsForUpload = !empty($getDefaultAvailableLanguages) ? $getDefaultAvailableLanguages : array();
        return $getAPPLanguageDetailsForUpload;
    }

    /*
     * Get default available languages.
     */

    private function _getAPPLanguageDetailsForUpload($type = 'android') {
        $appType = ($type == 'android') ? '_language_android_mobileapp' : '_language_ios_mobileapp';
        $getLanguages = Engine_Api::_()->getApi('Core', 'siteapi')->getLanguages(true);
        if (isset($getLanguages)) {
            $languageArray = array();
            foreach ($getLanguages as $key => $label) {
                $languageArray[$key] = array(
                    'title' => 'Upload Language File For: [' . $label . ']',
                    'directoryName' => 'Languages_App',
                    'fileName' => $key . $appType,
                );
            }
        }

        return $languageArray;
    }

    public function sendNotificationAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        $type = $this->getRequestParam('subject_type');
        $id = $this->getRequestParam('subject_id');

        $viewer = Engine_Api::_()->user()->getViewer();
        $subject = Engine_Api::_()->getItem($type, $id);

        // Process
        try {
            // Add notification
            $owner = $subject->getOwner();

            if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
                Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($owner, $viewer, $subject, 'liked', array(
                    'label' => $subject->getShortType()
                ));
            }
            $this->successResponseNoContent('no_content');
        } catch (Exception $e) {
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
    }

    public function getNewVersionAction() {
        $this->validateRequestMethod();
        $viewer = Engine_Api::_()->user()->getViewer();
        $response = array();

        $type = $this->getRequestParam('type', 'android');
        $version = $this->getRequestParam('version', null);

        if (
                ($type == 'android') && _ANDROID_VERSION
        ) {
            $popupEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting("android.popup.enable", 0);
            $latestVersion = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteandroidapp.version.upgrade');
            $versionDescription = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteandroidapp.version.description');


            $response['latestVersion'] = $latestVersion;
            $response['description'] = $versionDescription;
            $response['isPopUpEnabled'] = $popupEnable;
        }

        if (
                ($type == 'ios') && _IOS_VERSION
        ) {
            $popupEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting("ios.popup.enable", 0);
            $latestVersion = Engine_Api::_()->getApi('settings', 'core')->getSetting('ios.version.upgrade');
            $versionDescription = Engine_Api::_()->getApi('settings', 'core')->getSetting('ios.version.description');
            $response['latestVersion'] = $latestVersion;
            $response['description'] = $versionDescription;
            $response['isPopUpEnabled'] = $popupEnable;
        }

        if (!empty($response)) {
            $this->respondWithSuccess($response);
        }
    }

}
