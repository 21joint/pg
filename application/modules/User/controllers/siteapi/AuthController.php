<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteapi
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    AuthController.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class User_AuthController extends Siteapi_Controller_Action_Standard {
    /**
     * Logged-in to the user
     * 
     * @return array
     */
    public function loginAction() {
        $tempHostType = '';
        // Redirect to facebook login
        if (!empty($_REQUEST['facebook_uid'])) {
            $this->_forward('facebook', 'auth', 'user', array(
                "facebook_uid" => $_REQUEST['facebook_uid'],
                "access_token" => $_REQUEST['access_token'],
                "code" => $_REQUEST['code']
            ));
            return;
        }

        // Redirect to twitter login
        if (!empty($_REQUEST['twitter_uid'])) {
            $this->_forward('twitter', 'auth', 'user', array(
                "twitter_uid" => $_REQUEST['twitter_uid'],
                "twitter_token" => $_REQUEST['twitter_token'],
                "twitter_secret" => $_REQUEST['twitter_secret']
            ));
            return;
        }

        // Already logged in
        $siteapiUserLoginAuthentication = Zend_Registry::isRegistered('siteapiUserLoginAuthentication') ? Zend_Registry::get('siteapiUserLoginAuthentication') : null;
        $viewer = Engine_Api::_()->user()->getViewer();
        if ($viewer->getIdentity())
            $this->respondWithError('user_login_default');

        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        if (!empty($siteapiUserLoginAuthentication) && $this->getRequest()->isGet()) {
            $response['form'] = Engine_Api::_()->getApi('Siteapi_Core', 'user')->getLoginForm();

            if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteiosapp')) {
                $response['siteiosappSharedSecretKey'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteiosapp.shared.secret');
                $response['siteiosappMode'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteiosapp.current.mode', 1);
            }
            // Init facebook login link
            $response['facebook'] = $response['twitter'] = 0;
            $settings = Engine_Api::_()->getApi('settings', 'core');
            if ('none' != $settings->getSetting('core_facebook_enable', 'none') && $settings->core_facebook_secret)
                $response['facebook'] = 1;

            if ('none' != $settings->getSetting('core_twitter_enable', 'none') && $settings->core_twitter_secret)
                $response['twitter'] = 1;

            $this->respondWithSuccess($response);
        } else if (!empty($siteapiUserLoginAuthentication) && $this->getRequest()->isPost()) {
            $values = array();
            $email = $password = null;
            $getForm = Engine_Api::_()->getApi('Siteapi_Core', 'user')->getLoginForm();
            foreach ($getForm as $element) {
                if (isset($_REQUEST[$element['name']]))
                    $values[$element['name']] = $_REQUEST[$element['name']];
            }
            $data = $values;

            // START FORM VALIDATION
            $db = Engine_Db_Table::getDefaultAdapter();
            $validators = Engine_Api::_()->getApi('Siteapi_FormValidators', 'user')->getLoginFormValidators();
            $siteapiGlobalView = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.global.view', 0);
            $hostType = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
            $siteapiManageType = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.iosdevice.type', 0);
            $siteapiGlobalType = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.global.type', 0);
            $data['validators'] = $validators;
            $validationMessage = $this->isValid($data);
            if (!empty($validationMessage) && @is_array($validationMessage)) {
                $this->respondWithValidationError('validation_fail', $validationMessage);
            }

            // Getting IP address.
            if (isset($_REQUEST['ip']) && !empty($_REQUEST['ip'])) {
                $valid = preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $_REQUEST['ip']);
                if (empty($valid))
                    $this->respondWithError('ip_not_valid');

                $ipObj = new Engine_IP($_REQUEST['ip']);
                $ipExpr = new Zend_Db_Expr($db->quoteInto('UNHEX(?)', bin2hex($ipObj->toBinary())));
            }else {
                $this->respondWithError('ip_not_found');
            }

            if (empty($siteapiGlobalType)) {
                for ($check = 0; $check < strlen($hostType); $check++) {
                    $tempHostType += @ord($hostType[$check]);
                }
                $tempHostType = $tempHostType + $siteapiGlobalView;
            }

            // Getting the posted email address.
            if (isset($values['email']) && !empty($values['email']))
                $email = $values['email'];

            // Getting the posted password.
            if (isset($values['password']) && !empty($values['password']))
                $password = $values['password'];

            // Check login creds
            $user_table = Engine_Api::_()->getDbtable('users', 'user');
            $user_select = $user_table->select()
                    ->where('email = ?', $email);          // If post exists
            $user = $user_table->fetchRow($user_select);

            // Check if user exists
            if (empty($user)) {
                $this->respondWithError('unauthorized', 'Incorrect Email or Password');

                // Register login
                Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                    'email' => $email,
                    'ip' => $ipExpr,
                    'timestamp' => new Zend_Db_Expr('NOW()'),
                    'state' => 'no-member',
                ));

                return;
            }

            $subscriptionForm = $_REQUEST['subscriptionForm'];
//            if (isset($subscriptionForm) && !empty($subscriptionForm)) {
//                $validators = Engine_Api::_()->getApi('Siteapi_FormValidators', 'user')->getSubscriptionFormValidators();
//                $data['validators'] = $validators;
//                if (isset($_REQUEST['package_id']) && !empty($_REQUEST['package_id']))
//                    $data['package_id'] = $_REQUEST['package_id'];
//                $validationMessage = $this->isValid($data);
//                if (!empty($validationMessage) && @is_array($validationMessage)) {
//                    $this->respondWithValidationError('validation_fail', $validationMessage);
//                }
//            }

            $subscriptionForm = $_REQUEST['subscriptionForm'];
            if (empty($subscriptionForm)) {
                // Handle subscriptions
                if (Engine_Api::_()->hasModuleBootstrap('payment')) {
                    // Check for the user's plan
                    $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                    if (!$subscriptionsTable->check($user)) {

                        // Register login
                        Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                            'user_id' => $user->getIdentity(),
                            'email' => $email,
                            'ip' => $ipExpr,
                            'timestamp' => new Zend_Db_Expr('NOW()'),
                            'state' => 'unpaid',
                        ));
                        // Redirect to subscription page
                        $this->respondWithError('subscription_fail');
                    }
                }
            } else {
                // Handle subscriptions
                if (Engine_Api::_()->hasModuleBootstrap('payment')) {
                    $getHost = Engine_Api::_()->getApi('core', 'siteapi')->getHost();
                    $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
                    $baseUrl = @trim($baseUrl, "/");
                    // Check for the user's plan
                    $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                    if (!$subscriptionsTable->check($user)) {

                        // Handle default payment plan
                        $defaultSubscription = null;
                        try {
                            $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                            if ($subscriptionsTable) {
                                $defaultSubscription = $subscriptionsTable->activateDefaultPlan($user);
                                if ($defaultSubscription) {
                                    // Re-process enabled?
                                    $user->enabled = true;
                                    $user->save();
                                } else {
                                    // Check for the user's plan
                                    $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteiosapp')) {
                                        if (!$subscriptionsTable->check($user)) {
                                            $isIosSubscriber = Engine_Api::_()->getApi('core', 'siteapi')->hasUserIosSubscription($user);
                                            if (isset($isIosSubscriber) && !empty($isIosSubscriber)) {
                                                Engine_Api::_()->getApi('core', 'siteapi')->hasUserIosSubscriptionExpire($user, $isIosSubscriber);
                                            }
                                        }
                                    }

                                    if (!$subscriptionsTable->check($user)) {
                                        // Register login
                                        Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                                            'user_id' => $user->getIdentity(),
                                            'email' => $email,
                                            'ip' => $ipExpr,
                                            'timestamp' => new Zend_Db_Expr('NOW()'),
                                            'state' => 'unpaid',
                                        ));
                                        // Get package
                                        if (($packageId = $_REQUEST['package_id']) &&
                                                ($package = Engine_Api::_()->getItem('payment_package', $packageId))
                                        ) {
                                            $currentSubscription = $subscriptionsTable->fetchRow(array(
                                                'user_id = ?' => $user->getIdentity(),
                                                'active = ?' => true,
                                            ));
                                            // Cancel any other existing subscriptions
                                            Engine_Api::_()->getDbtable('subscriptions', 'payment')
                                                    ->cancelAll($user, 'User cancelled the subscription.', $currentSubscription);
                                            // Insert the new temporary subscription
                                            $db = $subscriptionsTable->getAdapter();
                                            $db->beginTransaction();
                                            try {
                                                $subscription = $subscriptionsTable->createRow();
                                                $subscription->setFromArray(array(
                                                    'package_id' => $_REQUEST['package_id'],
                                                    'user_id' => $user->getIdentity(),
                                                    'status' => 'initial',
                                                    'active' => false, // Will set to active on payment success
                                                    'creation_date' => new Zend_Db_Expr('NOW()'),
                                                ));
                                                $subscription->save();
                                                // If the package is free, let's set it active now and cancel the other
                                                if ($package->isFree()) {
                                                    $subscription->setActive(true);
                                                    $subscription->onPaymentSuccess();
                                                    if ($currentSubscription) {
                                                        $currentSubscription->cancel();
                                                    }
                                                    $user->enabled = true;
                                                    $user->save();
                                                }
                                                $db->commit();
                                            } catch (Exception $e) {
                                                $db->rollBack();
                                                throw $e;
                                            }
                                            if (!$package->isFree()) {
                                                $subscription_id = $subscription->subscription_id;
                                                $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);
                                                $response = $getHost . '/' . $baseUrl . "/payment/subscription/gateway?token=" . $getOauthToken['token'] . "&subscription_id=" . $subscription_id . "&disableHeaderAndFooter=1";
                                                //RESPONSE
                                                $this->respondWithSuccess($response, true);
                                            }
                                        } else {
                                            $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);
                                            if (_IOS_VERSION && _IOS_VERSION >= '1.5.8') {
                                                $response['user_id'] = $user->getIdentity();
                                                $response['subscription'] = 1;
                                            } else {
                                                $response = $getHost . '/' . $baseUrl . "/payment/subscription/choose?token=" . $getOauthToken['token'] . '&disableHeaderAndFooter=1';
                                            }

                                            //RESPONSE
                                            $this->respondWithSuccess($response, true);
                                        }
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            // Silence
                        }
                    }
                }
            }

            // Check if user is verified and enabled
            if (!$user->enabled) {
                if (!$user->verified) {
                    $this->respondWithError('email_not_verified');

                    // Register login
                    Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                        'user_id' => $user->getIdentity(),
                        'email' => $email,
                        'ip' => $ipExpr,
                        'timestamp' => new Zend_Db_Expr('NOW()'),
                        'state' => 'disabled',
                    ));

                    return;
                } else if (!$user->approved) {
                    $this->respondWithError('not_approved');

                    // Register login
                    Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                        'user_id' => $user->getIdentity(),
                        'email' => $email,
                        'ip' => $ipExpr,
                        'timestamp' => new Zend_Db_Expr('NOW()'),
                        'state' => 'disabled',
                    ));

                    return;
                }
            }
//            }
            // @todo: We have not done work for HOOKS calling like "onUserLoginBefore" and "onUserLoginAfter"
            // Version 3 Import compatibility
            if (empty($user->password)) {
                $compat = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.compatibility.password');
                $migration = null;
                try {
                    $migration = Engine_Db_Table::getDefaultAdapter()->select()
                            ->from('engine4_user_migration')
                            ->where('user_id = ?', $user->getIdentity())
                            ->limit(1)
                            ->query()
                            ->fetch();
                } catch (Exception $e) {
                    $migration = null;
                    $compat = null;
                }
                if (!$migration) {
                    $compat = null;
                }

                if ($compat == 'import-version-3') {
                    // Version 3 authentication
                    $cryptedPassword = self::_version3PasswordCrypt($migration['user_password_method'], $migration['user_code'], $password);
                    if ($cryptedPassword === $migration['user_password']) {
                        // Regenerate the user password using the given password
                        $user->salt = (string) rand(1000000, 9999999);
                        $user->password = $password;
                        $user->save();
                        Engine_Api::_()->user()->getAuth()->getStorage()->write($user->getIdentity());
                        // @todo should we delete the old migration row?
                    } else {
                        $this->respondWithError('auth_fail');
                    }
                    // End Version 3 authentication
                } else {
                    $this->respondWithError('invalid_password');

                    // Register login
                    Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                        'user_id' => $user->getIdentity(),
                        'email' => $email,
                        'ip' => $ipExpr,
                        'timestamp' => new Zend_Db_Expr('NOW()'),
                        'state' => 'v3-migration',
                    ));

                    return;
                }
            }

            // Normal authentication
            else {
                $authResult = Engine_Api::_()->user()->authenticate($email, $password);
                $authCode = $authResult->getCode();
                Engine_Api::_()->user()->setViewer();
                if ($authCode != Zend_Auth_Result::SUCCESS) {
                    $this->respondWithError('auth_fail');

                    // Register login
                    Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                        'user_id' => $user->getIdentity(),
                        'email' => $email,
                        'ip' => $ipExpr,
                        'timestamp' => new Zend_Db_Expr('NOW()'),
                        'state' => 'bad-password',
                    ));

                    return;
                }
            }

            // -- Success! --
            // Register login
            $loginTable = Engine_Api::_()->getDbtable('logins', 'user');
            $loginTable->insert(array(
                'user_id' => $user->getIdentity(),
                'email' => $email,
                'ip' => $ipExpr,
                'timestamp' => new Zend_Db_Expr('NOW()'),
                'state' => 'success',
                'active' => true,
            ));

            // Increment sign-in count
            Engine_Api::_()->getDbtable('statistics', 'core')
                    ->increment('user.logins');

            // Test activity @todo remove
            $viewer = Engine_Api::_()->user()->getViewer();
            if ($user->getIdentity()) {
                $user->lastlogin_date = date("Y-m-d H:i:s");
                $user->lastlogin_ip = $ipExpr;
                $user->save();
                Engine_Api::_()->getDbtable('actions', 'activity')
                        ->addActivity($viewer, $viewer, 'login');
            }

            if (!empty($user->verified) && !empty($user->approved) && empty($user->enabled)) {
                $user->enabled = 1;
                $user->save();
            }

            $userArray = Engine_Api::_()->getApi('Core', 'siteapi')->validateUserArray($user, array('email'));

            // Add images
            $getContentImages = Engine_Api::_()->getApi('core', 'siteapi')->getContentImage($user);
            $userArray = array_merge($userArray, $getContentImages);

            $userArray['cover'] = $userArray['image'];
            if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteusercoverphoto')) {
                $getUserCoverPhoto = Engine_Api::_()->getApi('Siteapi_Core', 'siteusercoverphoto')->getCoverPhoto($user);
                if (!empty($getUserCoverPhoto))
                    $userArray['cover'] = $getUserCoverPhoto;
            }

            if (!empty($tempHostType) && ($tempHostType != $siteapiManageType)) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting('siteapi.global.type', 1);
            } else {
                // Add GCMuser for push notification.
                $device_token = !empty($_REQUEST['registration_id']) ? $_REQUEST['registration_id'] : '';
                $device_token = !empty($_REQUEST['device_token']) ? $_REQUEST['device_token'] : $device_token;
                if (!empty($_REQUEST['device_uuid']) && !empty($device_token)) {
                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteandroidapp')) {
                        Engine_Api::_()->getDbtable('gcmusers', 'siteandroidapp')->addGCMuser(array(
                            'device_uuid' => $_REQUEST['device_uuid'],
                            'registration_id' => $device_token,
                            'user_id' => $user->getIdentity()
                        ));
                    }

                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteiosapp')) {
                        Engine_Api::_()->getDbtable('apnusers', 'siteiosapp')->addApnuser(array(
                            'device_uuid' => $_REQUEST['device_uuid'],
                            'token' => $device_token,
                            'user_id' => $user->getIdentity()
                        ));
                    }
                }

                $tabs = array();
                if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('primemessenger')) {
                    $tabs['primemessenger'] = Engine_Api::_()->primemessenger()->isPrimeMessengerActive();
                }

                $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);
                $this->respondWithSuccess(array(
                    'oauth_token' => $getOauthToken['token'],
                    'oauth_secret' => $getOauthToken['secret'],
                    'user' => $userArray,
                    'tabs' => $tabs
                ));
            }
        }
    }

    /**
     * Logged-out to the user
     * 
     * @return array
     */
    public function logoutAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');

        // Check if already logged out
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer->getIdentity())
            $this->respondWithError('user_already_logged_out');

        // Test activity @todo remove
        Engine_Api::_()->getDbtable('actions', 'activity')
                ->addActivity($viewer, $viewer, 'logout');

        // Update online status
        Engine_Api::_()->getDbtable('online', 'user')
                ->delete(array(
                    'user_id = ?' => $viewer->getIdentity(),
        ));

        // Delete GCMuser for push notification.
        if (!empty($_REQUEST['device_uuid'])) {
            Engine_Api::_()->getDbtable('gcmusers', 'siteandroidapp')->removeGCMUser(array(
                'device_uuid' => $_REQUEST['device_uuid']
            ));

            Engine_Api::_()->getDbtable('apnusers', 'siteiosapp')->removeAPNUser(array(
                'device_uuid' => $_REQUEST['device_uuid']
            ));
        }

        // Delete respective oauth token
        Engine_Api::_()->getApi('oauth', 'siteapi')->removeAccessOauthToken($viewer);

        // Logout
        Engine_Api::_()->user()->getAuth()->clearIdentity();

        $this->successResponseNoContent('no_content');
    }

    /**
     * Forgot password [Email will be send to respective email address]
     * 
     * @return array
     */
    public function forgotAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');

        // no logged in users
        if (Engine_Api::_()->user()->getViewer()->getIdentity())
            $this->respondWithError('user_login_default');

        if ($this->getRequest()->isPost()) {
            if (null !== ($email = (string) $_REQUEST['email']) &&
                    empty($email))
                $this->respondWithError('email_not_found');

            // Check for existing user
            $user = Engine_Api::_()->getDbtable('users', 'user')
                    ->fetchRow(array('email = ?' => $email));
            if (!$user || !$user->getIdentity())
                $this->respondWithError('email_not_found');

            // Check to make sure they're enabled
            if (!$user->enabled)
                $this->respondWithError('email_not_verified');

            // Ok now we can do the fun stuff
            $forgotTable = Engine_Api::_()->getDbtable('forgot', 'user');
            $db = $forgotTable->getAdapter();
            $db->beginTransaction();

            try {
                // Delete any existing reset password codes
                $forgotTable->delete(array(
                    'user_id = ?' => $user->getIdentity(),
                ));

                // Create a new reset password code
                $code = base_convert(md5($user->salt . $user->email . $user->user_id . uniqid(time(), true)), 16, 36);
                $forgotTable->insert(array(
                    'user_id' => $user->getIdentity(),
                    'code' => $code,
                    'creation_date' => date('Y-m-d H:i:s'),
                ));

                // Create url for forgot password
                Engine_Api::_()->getApi('Core', 'siteapi')->setView();
                $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
                $objectLink = $view->url(array('module' => 'user', 'controller' => 'auth', 'action' => 'reset', 'code' => $code, 'uid' => $user->getIdentity()), 'default', true);


                // Send user an email
                Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'core_lostpassword', array(
                    'host' => $_SERVER['HTTP_HOST'],
                    'email' => $user->email,
                    'date' => time(),
                    'recipient_title' => $user->getTitle(),
                    'recipient_link' => $user->getHref(),
                    'recipient_photo' => $user->getPhotoUrl('thumb.icon'),
                    'object_link' => $objectLink,
                    'queue' => false,
                ));

                // Show success
                $db->commit();
                $this->successResponseNoContent('no_content');
            } catch (Exception $e) {
                $db->rollBack();
                $this->respondWithValidationError('internal_server_error', $e->getMessage());
            }
        }
    }

    /**
     * Login to Facebook
     * 
     */
    public function facebookAction() {
        if ('none' == $settings->core_facebook_enable)
            $this->respondWithError("facebook_not_enabled");

        $facebook_uid = $this->getRequestParam('facebook_uid', null);
        if (empty($facebook_uid))
            $this->respondWithValidationError("parameter_missing", "facebook_uid");

        $access_token = $this->getRequestParam('access_token', null);
        if (empty($access_token))
            $this->respondWithValidationError("parameter_missing", "access_token");

        $code = $this->getRequestParam('code', null);
        if (empty($code))
            $this->respondWithValidationError("parameter_missing", "code");

        $user = Engine_Api::_()->user()->getViewer();
        $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
        $settings = Engine_Api::_()->getDbtable('settings', 'core');

        $db = Engine_Db_Table::getDefaultAdapter();

        // Attempt to login
        if (!$user->getIdentity()) {
            // Find out the user_id from the "engine4_user_facebook" table.
            if ($facebook_uid) {
                $user_id = $facebookTable->select()
                        ->from($facebookTable, 'user_id')
                        ->where('facebook_uid = ?', $facebook_uid)
                        ->query()
                        ->fetchColumn();
            }

            // If get the user_id then redirect to login.
            if ($user_id &&
                    $user = Engine_Api::_()->getItem('user', $user_id)) {

                //create auth token and store in database user tokens table.    
                $tokensTable = Engine_Api::_()->getDbtable('tokens', 'siteapi');
                $tokeTableSelect = $tokensTable->select()
                        ->where('user_id = ?', $user->getIdentity());          // If post exists
                $userToken = $tokensTable->fetchRow($tokeTableSelect);

                if (!empty($userToken) && !empty($userToken->token)) {
                    $auth_token = $userToken->token;
                    $auth_secret = $userToken->secret;
                } else {
                    $auth_token = Engine_Api::_()->getApi('oauth', 'siteapi')->generateRandomString();
                    $auth_secret = Engine_Api::_()->getApi('oauth', 'siteapi')->generateRandomString();
                    $tokensTable->insert(array(
                        'user_id' => $user->getIdentity(),
                        'token' => $auth_token,
                        'secret' => $auth_secret,
                    ));
                }

                $userArray = Engine_Api::_()->getApi('Core', 'siteapi')->validateUserArray($user, array('email'));
                // Add images
                $getContentImages = Engine_Api::_()->getApi('core', 'siteapi')->getContentImage($user);
                $userArray = array_merge($userArray, $getContentImages);

                $userArray['cover'] = $userArray['image'];

                // Add GCMuser for push notification.
                $device_token = !empty($_REQUEST['registration_id']) ? $_REQUEST['registration_id'] : '';
                $device_token = !empty($_REQUEST['device_token']) ? $_REQUEST['device_token'] : $device_token;
                if (!empty($_REQUEST['device_uuid']) && !empty($device_token)) {
                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteandroidapp')) {
                        Engine_Api::_()->getDbtable('gcmusers', 'siteandroidapp')->addGCMuser(array(
                            'device_uuid' => $_REQUEST['device_uuid'],
                            'registration_id' => $device_token,
                            'user_id' => $user->getIdentity()
                        ));
                    }

                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteiosapp')) {
                        Engine_Api::_()->getDbtable('apnusers', 'siteiosapp')->addApnuser(array(
                            'device_uuid' => $_REQUEST['device_uuid'],
                            'token' => $device_token,
                            'user_id' => $user->getIdentity()
                        ));
                    }
                }

                $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);

                $subscriptionForm = $_REQUEST['subscriptionForm'];
                if (!empty($subscriptionForm)) {
                    // If there are enabled gateways or packages,
                    if (Engine_Api::_()->getDbtable('gateways', 'payment')->getEnabledGatewayCount() > 0 &&
                            Engine_Api::_()->getDbtable('packages', 'payment')->getEnabledNonFreePackageCount() > 0) {
                        // Handle subscriptions
                        if (Engine_Api::_()->hasModuleBootstrap('payment')) {
                            $getHost = Engine_Api::_()->getApi('core', 'siteapi')->getHost();
                            $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
                            $baseUrl = @trim($baseUrl, "/");
                            // Check for the user's plan
                            $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                            if (!$subscriptionsTable->check($user)) {

                                // Handle default payment plan
                                $defaultSubscription = null;
                                try {
                                    $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                                    if ($subscriptionsTable) {
                                        $defaultSubscription = $subscriptionsTable->activateDefaultPlan($user);
                                        if ($defaultSubscription) {
                                            // Re-process enabled?
                                            $user->enabled = true;
                                            $user->save();
                                        } else {
                                            // Check for the user's plan
                                            $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');

                                            if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteiosapp')) {
                                                if (!$subscriptionsTable->check($user)) {
                                                    $isIosSubscriber = Engine_Api::_()->getApi('core', 'siteapi')->hasUserIosSubscription($user);
                                                    if (isset($isIosSubscriber) && !empty($isIosSubscriber)) {
                                                        Engine_Api::_()->getApi('core', 'siteapi')->hasUserIosSubscriptionExpire($user, $isIosSubscriber);
                                                    }
                                                }
                                            }
                                            if (!$subscriptionsTable->check($user)) {
                                                // Register login
//                                                Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
//                                                    'user_id' => $user->getIdentity(),
//                                                    'email' => $email,
//                                                    'ip' => $ipExpr,
//                                                    'timestamp' => new Zend_Db_Expr('NOW()'),
//                                                    'state' => 'unpaid',
//                                                ));

                                                $currentSubscription = $subscriptionsTable->fetchRow(array(
                                                    'user_id = ?' => $user->getIdentity(),
                                                ));

                                                // Get package
                                                if (($packageId = $_REQUEST['package_id']) &&
                                                        ($package = Engine_Api::_()->getItem('payment_package', $packageId))
                                                ) {
                                                    $currentSubscription = $subscriptionsTable->fetchRow(array(
                                                        'user_id = ?' => $user->getIdentity(),
                                                        'active = ?' => true,
                                                    ));
                                                    // Cancel any other existing subscriptions
                                                    Engine_Api::_()->getDbtable('subscriptions', 'payment')
                                                            ->cancelAll($user, 'User cancelled the subscription.', $currentSubscription);
                                                    // Insert the new temporary subscription
                                                    $db = $subscriptionsTable->getAdapter();
                                                    $db->beginTransaction();
                                                    try {
                                                        $subscription = $subscriptionsTable->createRow();
                                                        $subscription->setFromArray(array(
                                                            'package_id' => $_REQUEST['package_id'],
                                                            'user_id' => $user->getIdentity(),
                                                            'status' => 'initial',
                                                            'active' => false, // Will set to active on payment success
                                                            'creation_date' => new Zend_Db_Expr('NOW()'),
                                                        ));
                                                        $subscription->save();
                                                        // If the package is free, let's set it active now and cancel the other
                                                        if ($package->isFree()) {
                                                            $subscription->setActive(true);
                                                            $subscription->onPaymentSuccess();
                                                            if ($currentSubscription) {
                                                                $currentSubscription->cancel();
                                                            }
                                                            $user->enabled = true;
                                                            $user->save();
                                                        }
                                                        $db->commit();
                                                    } catch (Exception $e) {
                                                        $db->rollBack();
                                                        throw $e;
                                                    }
                                                    if (!$package->isFree()) {

                                                        $subscription_id = $subscription->subscription_id;
                                                        $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);
                                                        if (_IOS_VERSION && _IOS_VERSION >= '1.5.8') {
                                                            $response['subscription_id'] = $subscription_id;
                                                            $response['user_id'] = $user->getIdentity();
                                                            $response['subscription'] = 1;
                                                        } else {
                                                            $response = $getHost . '/' . $baseUrl . "/payment/subscription/gateway?token=" . $getOauthToken['token'] . "&subscription_id=" . $subscription_id;
                                                        }
                                                        //RESPONSE
                                                        $this->respondWithSuccess($response, true);
                                                    }
                                                } else {
                                                    $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);
                                                    if (_IOS_VERSION && _IOS_VERSION >= '1.5.8') {
                                                        $response['user_id'] = $user->getIdentity();
                                                        $response['subscription'] = 1;
                                                    } else {
                                                        $response = $getHost . '/' . $baseUrl . "/payment/subscription/choose?token=" . $getOauthToken['token'] . '&disableHeaderAndFooter=1';
                                                    }

                                                    //RESPONSE
                                                    $this->respondWithSuccess($response, true);
                                                }
                                            }
                                        }
                                    }
                                } catch (Exception $e) {
                                    // Silence
                                }
                            }
                        }
                    }
                }


                // Check if user is verified and enabled
                if (!$user->verified) {
                    $this->respondWithError('email_not_verified');

                    // Register login
                    Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                        'user_id' => $user->getIdentity(),
                        'email' => $email,
                        'ip' => $ipExpr,
                        'timestamp' => new Zend_Db_Expr('NOW()'),
                        'state' => 'disabled',
                    ));

                    return;
                } else if (!$user->approved) {
                    $this->respondWithError('not_approved');

                    // Register login
                    Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                        'user_id' => $user->getIdentity(),
                        'email' => $email,
                        'ip' => $ipExpr,
                        'timestamp' => new Zend_Db_Expr('NOW()'),
                        'state' => 'disabled',
                    ));

                    return;
                }

                if (isset($getOauthToken) && !empty($getOauthToken)) {
                    $this->respondWithSuccess(array(
                        'oauth_token' => $getOauthToken['token'],
                        'oauth_secret' => $getOauthToken['secret'],
                        'user' => $userArray,
                    ));
                }
            } else if ($facebook_uid) {
                $_SERVER['REQUEST_METHOD'] = 'GET';
                $this->_forward('index', 'signup', 'user', array(
                    'facebook_uid' => $_REQUEST['facebook_uid'],
                    'access_token' => $_REQUEST['access_token'],
                    'code' => $_REQUEST['code']
                ));
                return;
            }
        } else {
            try {
                // Attempt to connect account
                $info = $facebookTable->select()
                        ->from($facebookTable)
                        ->where('user_id =' . $user->getIdentity() . ' OR ' . 'facebook_uid=' . $facebook_uid)
                        ->limit(1)
                        ->query()
                        ->fetch();
                if (empty($info)) {
                    $facebookTable->insert(array(
                        'user_id' => $user->getIdentity(),
                        'facebook_uid' => $facebook_uid,
                        'access_token' => $access_token,
                        'code' => $code,
                        'expires' => 0, // @todo make sure this is correct
                    ));
                } else {

                    $this->respondWithError('unauthorized', "This Facebook account has been already registered.");
                }
            } catch (Exception $ex) {
                
            }
        }
        $this->successResponseNoContent('no_content');
    }

    /**
     * Login to Twitter
     * 
     */
    public function twitterAction() {
        if ('none' == $settings->core_facebook_enable)
            $this->respondWithError("twitter_not_enabled");

        $twitter_uid = $this->getRequestParam('twitter_uid', null);
        if (empty($twitter_uid))
            $this->respondWithValidationError("parameter_missing", "twitter_uid");

        $twitter_token = $this->getRequestParam('twitter_token', null);
        if (empty($twitter_token))
            $this->respondWithValidationError("parameter_missing", "twitter_token");

        $twitter_secret = $this->getRequestParam('twitter_secret', null);
        if (empty($twitter_secret))
            $this->respondWithValidationError("parameter_missing", "twitter_secret");

        $user = Engine_Api::_()->user()->getViewer();
        $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
        $settings = Engine_Api::_()->getDbtable('settings', 'core');

        $db = Engine_Db_Table::getDefaultAdapter();

        // Attempt to login
        if (!$user->getIdentity()) {
            // Find out the user_id from the "engine4_user_facebook" table.
            if ($twitter_uid) {
                $user_id = $twitterTable->select()
                        ->from($twitterTable, 'user_id')
                        ->where('twitter_uid = ?', $twitter_uid)
                        ->query()
                        ->fetchColumn();
            }

            // If get the user_if then redirect to login.
            if ($user_id &&
                    $user = Engine_Api::_()->getItem('user', $user_id)) {

                //create auth token and store in database user tokens table.    
                $tokensTable = Engine_Api::_()->getDbtable('tokens', 'siteapi');
                $tokeTableSelect = $tokensTable->select()
                        ->where('user_id = ?', $user->getIdentity());          // If post exists
                $userToken = $tokensTable->fetchRow($tokeTableSelect);

                if (!empty($userToken) && !empty($userToken->token)) {
                    $auth_token = $userToken->token;
                    $auth_secret = $userToken->secret;
                } else {
                    $auth_token = Engine_Api::_()->getApi('oauth', 'siteapi')->generateRandomString();
                    $auth_secret = Engine_Api::_()->getApi('oauth', 'siteapi')->generateRandomString();
                    $tokensTable->insert(array(
                        'user_id' => $user->getIdentity(),
                        'token' => $auth_token,
                        'secret' => $auth_secret,
                    ));
                }

                $userArray = Engine_Api::_()->getApi('Core', 'siteapi')->validateUserArray($user, array('email'));
                // Add images
                $getContentImages = Engine_Api::_()->getApi('core', 'siteapi')->getContentImage($user);
                $userArray = array_merge($userArray, $getContentImages);

                $userArray['cover'] = $userArray['image'];

                // Add GCMuser for push notification.
                $device_token = !empty($_REQUEST['registration_id']) ? $_REQUEST['registration_id'] : '';
                $device_token = !empty($_REQUEST['device_token']) ? $_REQUEST['device_token'] : $device_token;
                if (!empty($_REQUEST['device_uuid']) && !empty($device_token)) {
                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteandroidapp')) {
                        Engine_Api::_()->getDbtable('gcmusers', 'siteandroidapp')->addGCMuser(array(
                            'device_uuid' => $_REQUEST['device_uuid'],
                            'registration_id' => $device_token,
                            'user_id' => $user->getIdentity()
                        ));
                    }

                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteiosapp')) {
                        Engine_Api::_()->getDbtable('apnusers', 'siteiosapp')->addApnuser(array(
                            'device_uuid' => $_REQUEST['device_uuid'],
                            'token' => $device_token,
                            'user_id' => $user->getIdentity()
                        ));
                    }
                }

                $subscriptionForm = $_REQUEST['subscriptionForm'];
                if (!empty($subscriptionForm)) {
                    // If there are enabled gateways or packages,
                    if (Engine_Api::_()->getDbtable('gateways', 'payment')->getEnabledGatewayCount() > 0 &&
                            Engine_Api::_()->getDbtable('packages', 'payment')->getEnabledNonFreePackageCount() > 0) {
                        // Handle subscriptions
                        if (Engine_Api::_()->hasModuleBootstrap('payment')) {
                            $getHost = Engine_Api::_()->getApi('core', 'siteapi')->getHost();
                            $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
                            $baseUrl = @trim($baseUrl, "/");
                            // Check for the user's plan
                            $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                            if (!$subscriptionsTable->check($user)) {
                                // Handle default payment plan
                                $defaultSubscription = null;
                                try {
                                    $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                                    if ($subscriptionsTable) {
                                        $defaultSubscription = $subscriptionsTable->activateDefaultPlan($user);
                                        if ($defaultSubscription) {
                                            // Re-process enabled?
                                            $user->enabled = true;
                                            $user->save();
                                        } else {
                                            // Check for the user's plan
                                            $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                                            if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteiosapp')) {
                                                if (!$subscriptionsTable->check($user)) {
                                                    $isIosSubscriber = Engine_Api::_()->getApi('core', 'siteapi')->hasUserIosSubscription($user);
                                                    if (isset($isIosSubscriber) && !empty($isIosSubscriber)) {
                                                        Engine_Api::_()->getApi('core', 'siteapi')->hasUserIosSubscriptionExpire($user, $isIosSubscriber);
                                                    }
                                                }
                                            }
                                            if (!$subscriptionsTable->check($user)) {
//                                        // Register login
//                                        Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
//                                            'user_id' => $user->getIdentity(),
//                                            'email' => $email,
//                                            'ip' => $ipExpr,
//                                            'timestamp' => new Zend_Db_Expr('NOW()'),
//                                            'state' => 'unpaid',
//                                        ));

                                                $currentSubscription = $subscriptionsTable->fetchRow(array(
                                                    'user_id = ?' => $user->getIdentity(),
                                                ));

                                                // Get package
                                                if (($packageId = $_REQUEST['package_id']) &&
                                                        ($package = Engine_Api::_()->getItem('payment_package', $packageId))
                                                ) {
                                                    $currentSubscription = $subscriptionsTable->fetchRow(array(
                                                        'user_id = ?' => $user->getIdentity(),
                                                        'active = ?' => true,
                                                    ));
                                                    // Cancel any other existing subscriptions
                                                    Engine_Api::_()->getDbtable('subscriptions', 'payment')
                                                            ->cancelAll($user, 'User cancelled the subscription.', $currentSubscription);
                                                    // Insert the new temporary subscription
                                                    $db = $subscriptionsTable->getAdapter();
                                                    $db->beginTransaction();
                                                    try {
                                                        $subscription = $subscriptionsTable->createRow();
                                                        $subscription->setFromArray(array(
                                                            'package_id' => $_REQUEST['package_id'],
                                                            'user_id' => $user->getIdentity(),
                                                            'status' => 'initial',
                                                            'active' => false, // Will set to active on payment success
                                                            'creation_date' => new Zend_Db_Expr('NOW()'),
                                                        ));
                                                        $subscription->save();
                                                        // If the package is free, let's set it active now and cancel the other
                                                        if ($package->isFree()) {
                                                            $subscription->setActive(true);
                                                            $subscription->onPaymentSuccess();
                                                            if ($currentSubscription) {
                                                                $currentSubscription->cancel();
                                                            }
                                                            $user->enabled = true;
                                                            $user->save();
                                                        }
                                                        $db->commit();
                                                    } catch (Exception $e) {
                                                        $db->rollBack();
                                                        throw $e;
                                                    }
                                                    if (!$package->isFree()) {

                                                        $subscription_id = $subscription->subscription_id;
                                                        $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);
                                                        if (_IOS_VERSION && _IOS_VERSION >= '1.5.8') {
                                                            $response['subscription_id'] = $subscription_id;
                                                            $response['user_id'] = $user->getIdentity();
                                                            $response['subscription'] = 1;
                                                        } else {
                                                            $response = $getHost . '/' . $baseUrl . "/payment/subscription/gateway?token=" . $getOauthToken['token'] . "&subscription_id=" . $subscription_id;
                                                        }
                                                        //RESPONSE
                                                        $this->respondWithSuccess($response, true);
                                                    }
                                                } else {
                                                    $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);
                                                    if (_IOS_VERSION && _IOS_VERSION >= '1.5.8') {
                                                        $response['user_id'] = $user->getIdentity();
                                                        $response['subscription'] = 1;
                                                    } else {
                                                        $response = $getHost . '/' . $baseUrl . "/payment/subscription/choose?token=" . $getOauthToken['token'] . '&disableHeaderAndFooter=1';
                                                    }
                                                    //RESPONSE
                                                    $this->respondWithSuccess($response, true);
                                                }
                                            }
                                        }
                                    }
                                } catch (Exception $e) {
                                    // Silence
                                }
                            }
                        }
                    }
                }

                // Check if user is verified and enabled
                if (!$user->verified) {
                    $this->respondWithError('email_not_verified');

                    // Register login
                    Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                        'user_id' => $user->getIdentity(),
                        'email' => $email,
                        'ip' => $ipExpr,
                        'timestamp' => new Zend_Db_Expr('NOW()'),
                        'state' => 'disabled',
                    ));

                    return;
                } else if (!$user->approved) {
                    $this->respondWithError('not_approved');

                    // Register login
                    Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                        'user_id' => $user->getIdentity(),
                        'email' => $email,
                        'ip' => $ipExpr,
                        'timestamp' => new Zend_Db_Expr('NOW()'),
                        'state' => 'disabled',
                    ));

                    return;
                }

                $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);
                if (isset($getOauthToken) && !empty($getOauthToken)) {
                    $this->respondWithSuccess(array(
                        'oauth_token' => $getOauthToken['token'],
                        'oauth_secret' => $getOauthToken['secret'],
                        'user' => $userArray,
                    ));
                }
            } else if ($twitter_uid) {
                $_SERVER['REQUEST_METHOD'] = 'GET';
                $this->_forward('index', 'signup', 'user', array(
                    'twitter_uid' => $twitter_uid,
                    'twitter_token' => $twitter_token,
                    'twitter_secret' => $twitter_secret
                ));
                return;
            }
        } else {
            // Attempt to connect account
            $info = $twitterTable->select()
                    ->from($twitterTable)
                    ->where('user_id =' . $user->getIdentity() . ' OR ' . 'twitter_uid=' . $twitter_uid)
                    ->limit(1)
                    ->query()
                    ->fetch();
            if (empty($info)) {
                $twitterTable->insert(array(
                    'user_id' => $user->getIdentity(),
                    'twitter_uid' => $twitter_uid,
                    'twitter_token' => $twitter_token,
                    'twitter_secret' => $twitter_secret
                ));
            } else {

                $this->respondWithError('unauthorized', "This Twitter account has been already registered.");
            }
        }
        $this->successResponseNoContent('no_content');
    }

    public function updateFcmTokenAction() {
        $this->validateRequestMethod('POST');

        $user_id = $_REQUEST['user_id'];
        $user = Engine_Api::_()->getItem('user', $user_id);

        if (empty($user))
            $this->respondWithError('no_record');

        $device_token = !empty($_REQUEST['registration_id']) ? $_REQUEST['registration_id'] : '';
        $device_token = !empty($_REQUEST['device_token']) ? $_REQUEST['device_token'] : $device_token;
        try {

            if (empty($device_token))
                $this->respondWithError('no_record');
            if (!empty($_REQUEST['device_uuid']) && !empty($device_token)) {
                if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteandroidapp')) {
                    Engine_Api::_()->getDbtable('gcmusers', 'siteandroidapp')->addGCMuser(array(
                        'device_uuid' => $_REQUEST['device_uuid'],
                        'registration_id' => $device_token,
                        'user_id' => $user->getIdentity()
                    ));
                    $this->successResponseNoContent('no_content');
                }
            }
        } catch (Exception $ex) {
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
    }

    public function setUserSubscriptionAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');

        $user_id = $_REQUEST['user_id'];
        $packageId = $_REQUEST['package_id'];
        $isSandbox = $_REQUEST['isSandbox'];
        $transaction_id = $_REQUEST['transaction_id'];
        $device_uuid = $_REQUEST['device_uuid'];
        $receiptData = $_POST['receipt'];

        if (empty($user_id) || empty($packageId))
            $this->respondWithError('unauthorized');

        $package = Engine_Api::_()->getItem('payment_package', $packageId);
        $user = Engine_Api::_()->getItem('user', $user_id);

        if (empty($package) || empty($user))
            $this->respondWithError('unauthorized');

        $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
        $currentSubscription = $subscriptionsTable->fetchRow(array(
            'user_id = ?' => $user->getIdentity(),
            'active = ?' => true,
        ));

        // Cancel any other existing subscriptions
        Engine_Api::_()->getDbtable('subscriptions', 'payment')
                ->cancelAll($user, 'User cancelled the subscription.', $currentSubscription);

        // Insert the new temporary subscription
        $db = $subscriptionsTable->getAdapter();
        $db->beginTransaction();
        try {
            $subscription = $subscriptionsTable->createRow();
            $subscription->setFromArray(array(
                'package_id' => $_REQUEST['package_id'],
                'user_id' => $user->getIdentity(),
                'status' => 'initial',
                'active' => false, // Will set to active on payment success
                'creation_date' => new Zend_Db_Expr('NOW()'),
            ));
            $subscription->save();
            if (empty($subscription))
                $this->respondWithError('unauthorized');

            // If the package is free, let's set it active now and cancel the other
            if ($package->isFree()) {
                $subscription->setActive(true);
                $subscription->onPaymentSuccess();
                if ($currentSubscription) {
                    $currentSubscription->cancel();
                }
                $user->enabled = true;
                $user->save();
                $db->commit();
                $this->successResponseNoContent('no_content');
            }

            if (!$package->isFree()) {
                if (empty($transaction_id) || empty($receiptData))
                    $this->respondWithError('unauthorized');

                $info = Engine_Api::_()->getApi('Core', 'siteapi')->getReceiptData($receiptData, $transaction_id, $isSandbox);

                if (isset($info) && !empty($info) && isset($info["receipt"]) && !empty($info['receipt'])) {
                    $creation_date = $info['receipt']->purchase_date;
                    $db = Engine_Db_Table::getDefaultAdapter();

                    // Delete user_id if already exist in table
                    $select = new Zend_Db_Select($db);
                    $iosSubscriptionsTable = Engine_Api::_()->getDbtable('userSubscriptions', 'siteiosapp');
                    $isViewRowExist = $iosSubscriptionsTable->fetchRow(array(
                        'user_id = ?' => $user_id,
                    ));

                    if (isset($isViewRowExist) && !empty($isViewRowExist)) {
                        $isViewRowExist->delete();
                    }

                    // check if transaction_id already exist in table
                    $isViewRowExist = $iosSubscriptionsTable->fetchRow(array(
                        'transaction_id = ?' => $transaction_id,
                    ));
                    if (isset($isViewRowExist) && !empty($isViewRowExist)) {
                        $this->respondWithError('unauthorized');
                    } else {
                        $iosSubscription = $iosSubscriptionsTable->createRow();
                        $iosSubscription->setFromArray(array(
                            'transaction_id' => $transaction_id,
                            'user_id' => $user_id,
                            'email' => $user->email,
                            'displayname' => $user->displayname,
                            'package_id' => $packageId,
                            'device_uuid' => $device_uuid,
                            'creation_date' => $creation_date,
                            'receipt' => $receiptData,
                            'isSandbox' => $isSandbox
                        ));
                        $iosSubscription->save();
                    }

                    $db->commit();

                    // Check subscription?
                    if ($this->_checkSubscriptionStatus($subscription, $user)) {
                        $this->respondWithError('unauthorized');
                    }
                    $subscription->onPaymentSuccess();

                    if ($subscriptionsTable->check($user)) {
                        $this->successResponseNoContent('no_content');
                    }
                } else
                    $this->respondWithError('unauthorized');
            }
        } catch (Exception $ex) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
    }

    protected function _checkSubscriptionStatus(
    Zend_Db_Table_Row_Abstract $subscription = null, $user) {
        if (!$user) {
            return false;
        }

        if (null === $subscription) {
            $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
            $subscription = $subscriptionsTable->fetchRow(array(
                'user_id = ?' => $user->getIdentity(),
                'active = ?' => true,
            ));
        }

        if (!$subscription) {
            return false;
        }

        if ($subscription->status == 'active' ||
                $subscription->status == 'trial') {
            if (!$subscription->getPackage()->isFree()) {
                Zend_Auth::getInstance()->getStorage()->write($this->_user->getIdentity());
                Engine_Api::_()->user()->setViewer();
            } else {
                Zend_Auth::getInstance()->getStorage()->write($this->_user->getIdentity());
                Engine_Api::_()->user()->setViewer();
            }
            return true;
        }

        return false;
    }

    static protected function _version3PasswordCrypt($method, $salt, $password) {
        // For new methods
        if ($method > 0) {
            if (!empty($salt)) {
                list($salt1, $salt2) = str_split($salt, ceil(strlen($salt) / 2));
                $salty_password = $salt1 . $password . $salt2;
            } else {
                $salty_password = $password;
            }
        }

        // Hash it
        switch ($method) {
            // crypt()
            default:
            case 0:
                $user_password_crypt = crypt($password, '$1$' . str_pad(substr($salt, 0, 8), 8, '0', STR_PAD_LEFT) . '$');
                break;

            // md5()
            case 1:
                $user_password_crypt = md5($salty_password);
                break;

            // sha1()
            case 2:
                $user_password_crypt = sha1($salty_password);
                break;

            // crc32()
            case 3:
                $user_password_crypt = sprintf("%u", crc32($salty_password));
                break;
        }

        return $user_password_crypt;
    }

    public function upgradeSubscriptionAction() {
        $user = Engine_Api::_()->user()->getViewer();
        $user_id = $user->getIdentity();
        if (empty($user_id))
            $this->respondWithError('unauthorized');
        // Set the translations for zend library.
        if (!Zend_Registry::isRegistered('Zend_Translate'))
            Engine_Api::_()->getApi('Core', 'siteapi')->setTranslate();
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
        Engine_Api::_()->getApi('Core', 'siteapi')->setLocal();
        try {


            $level = Engine_Api::_()->getItem('authorization_level', $user->level_id);
            if (in_array($level->type, array('admin', 'moderator'))) {
                $this->respondWithValidationError('validation_fail', $this->translate(array('level' => 'Subscriptions are not required for administrators and moderators.')));
            }

            $response = Engine_Api::_()->getApi('Siteapi_Core', 'user')->subscriptionUpgradeForm();
            $this->respondWithSuccess($response);
        } catch (Exception $ex) {
            
        }
    }

}
