<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteapi
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    SignupController.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class User_SignupController extends Siteapi_Controller_Action_Standard {
    /**
     * Throw the init constructor errors.
     *
     * @return array
     */
    public function throwErrorAction() {
        $message = $this->getRequestParam("message", null);
        if (($error_code = $this->getRequestParam("error_code")) && !empty($error_code)) {
            if (!empty($message))
                $this->respondWithValidationError($error_code, $message);
            else
                $this->respondWithError($error_code);
        }

        return;
    }

    /**
     * Validate signup form.
     * 
     * @return array
     */
    public function validationsAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');

        $values = $_REQUEST;
        $validationMessage = $this->_validateForm($values);

        // Response form validations.
        if (!empty($validationMessage) && @is_array($validationMessage)) {
            $this->respondWithValidationError('validation_fail', $validationMessage);
        } else {
            $this->successResponseNoContent('no_content');
        }
    }

    /**
     * Get the signup form and create user after post.
     * 
     * @return array
     */
    public function indexAction() {
        // Check if facebook details already exist.
        if (!empty($_REQUEST['facebook_uid'])) {
            $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
            $user_id = $facebookTable->select()
                    ->from($facebookTable, 'user_id')
                    ->where('facebook_uid = ?', $_REQUEST['facebook_uid'])
                    ->query()
                    ->fetchColumn();

            if (!empty($user_id))
                $this->respondWithError('facebook_uid_exist');
        }
        // Check if twitter details already exist.
        if (!empty($_REQUEST['twitter_uid'])) {
            $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
            $user_id = $twitterTable->select()
                    ->from($twitterTable, 'user_id')
                    ->where('twitter_uid = ?', $_REQUEST['twitter_uid'])
                    ->query()
                    ->fetchColumn();

            if (!empty($user_id))
                $this->respondWithError('twitter_uid_exist');
        }

        $siteapiUserSignup = Zend_Registry::isRegistered('siteapiUserSignup') ? Zend_Registry::get('siteapiUserSignup') : null;
        $siteapiGlobalView = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.global.view', 0);
        $siteapiLSettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.lsettings', 0);
        $siteapiInfoType = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.androiddevice.type', 0);
        $siteapiGlobalType = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteapi.global.type', 0);
        $random = (Engine_Api::_()->getApi('settings', 'core')->getSetting('user.signup.random', 0) == 1);
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        if (empty($siteapiUserSignup) || !empty($viewer_id))
            $this->respondWithError('unauthorized');

        if ($this->getRequest()->isGet()) {
            $this->respondWithSuccess(Engine_Api::_()->getApi('Siteapi_Core', 'user')->getSignupForm());
        } else if ($this->getRequest()->isPost()) {
            $data = $_REQUEST;

            // Form validation
            $validationMessage = $this->_validateForm($data);
            $stepTable = Engine_Api::_()->getDbtable('signup', 'user');
            $stepSelect = $stepTable->select()->where('class = ?', 'User_Plugin_Signup_Photo');
            $row = $stepTable->fetchRow($stepSelect);

            if (empty($row) || empty($row->enable)) {
                $stepSelect = $stepTable->select()->where('class = ?', 'Whcore_Plugin_Signup_Photo');
                $row = $stepTable->fetchRow($stepSelect);
            }

            if (!empty($row) && !empty($row->enable)) {
                if (Engine_Api::_()->getApi('settings', 'core')->getSetting('user.signup.photo', 1)) {
                    if (empty($_FILES['photo'])) {
                        $validationMessage = (is_array($validationMessage)) ? $validationMessage : array();
                        $validationMessage['photo'] = $this->translate('Please complete this field - it is required.');
                    }
                }
            }



            if (!empty($validationMessage) && @is_array($validationMessage)) {
                $this->respondWithValidationError('validation_fail', $validationMessage);
            }

            if (empty($siteapiGlobalType)) {
                for ($check = 0; $check < strlen($siteapiLSettings); $check++) {
                    $tempSitemenuLtype += @ord($siteapiLSettings[$check]);
                }
                $tempSitemenuLtype = $tempSitemenuLtype + $siteapiGlobalView;
            }

            try {
                if (!empty($tempSitemenuLtype) && ($tempSitemenuLtype != $siteapiInfoType)) {
                    Engine_Api::_()->getApi('settings', 'core')->setSetting('siteapi.viewtypeinfo.type', 1);
                } else {
                    // Save user
                    $user = $this->_saveUser($data);
                }



                // Set photo
                if (!empty($_FILES['photo']))
                    Engine_Api::_()->getApi('Siteapi_Core', 'user')->setPhoto($_FILES['photo'], $user);
                // save fields in search table
                if (isset($profileTypeField->field_id) && !empty($profileTypeField->field_id)) {
                    $searchTable = Engine_Api::_()->fields()->getTable('user', 'search');
                    $searchTableName = $searchTable->info('name');
                    $searchFields = $this->setFieldSearchStructure($data);
                    if (isset($searchFields) && !empty($searchFields)) {
                        $searchFields['profile_type'] = $profileTypeField->field_id;
                        $searchFields['item_id'] = $user->getIdentity();
                        $userSearchFields = $searchTable->createRow();
                        $userSearchFields->setFromarray($searchFields);
                        $selectQuery = $searchTable->select()
                                ->from($searchTableName, array('COUNT(1) AS exists'))
                                ->where('`item_id` = ?', $user->getIdentity());
                        $result = $searchTable->fetchRow($selectQuery);
                        if ($result->exists == 0) {
                            $userSearchFields->save();
                        }
                    }
                }

                // Set Displayname
                $aliasValues = Engine_Api::_()->fields()->getFieldsValuesByAlias($user);
                $user->setDisplayName($aliasValues);
                $subscriptionForm = $_REQUEST['subscriptionForm'];
                if (empty($subscriptionForm)) {
                    // Handle subscriptions
                    if (Engine_Api::_()->hasModuleBootstrap('payment')) {
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
                                    }
                                }
                            } catch (Exception $e) {
                                // Silence
                            }

                            if (!$defaultSubscription)
                                $this->respondWithError('subscription_fail');
                        }
                    }
                }

                // Success: forward to login api
                if (!empty($_REQUEST['facebook_uid'])) {
                    $this->_forward('facebook-login', 'signup', 'user', array(
                        'user' => $user
                    ));
                } else if (!empty($_REQUEST['twitter_uid'])) {
                    $this->_forward('twitter-login', 'signup', 'user', array(
                        'user' => $user
                    ));
                } else if (!empty($random)) {
                    $error['password'] = $this->translate('Thanks for joining! An email for the password has been sent to registered email ID.');
                    $this->respondWithValidationError('validation_fail', $error);
                } else {
                    $this->_forward('login', 'auth', 'user', array(
                        'email' => $user->email,
                        'password' => $data['password'],
                        'package_id' => $_REQUEST['package_id']
                    ));
                }
            } catch (Exception $e) {
                $this->respondWithValidationError('internal_server_error', $e->getMessage());
            }
        }
    }

    /**
     * Login to facebook
     * 
     * @return array
     */
    public function facebookLoginAction() {
        $user = $this->getParam("user", null);

        //create auth token and store in database user tokens table.    
        $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
        $tokensTable = Engine_Api::_()->getDbtable('tokens', 'siteapi');
        $tokeTableSelect = $tokensTable->select()
                ->where('user_id = ?', $user->getIdentity());          // If post exists
        $userToken = $tokensTable->fetchRow($tokeTableSelect);
        if (!empty($userToken) && !empty($userToken->token)) {
            $auth_token = $userToken->token;
            $getOauthToken['token'] = $userToken->token;
            $getOauthToken['secret'] = $userToken->secret;
        } else {
            $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);
        }

        $facebookTable->insert(array(
            'user_id' => $user->getIdentity(),
            'facebook_uid' => $_REQUEST['facebook_uid'],
            'access_token' => $_REQUEST['access_token'],
            'code' => $_REQUEST['code'],
            'expires' => 0, // @todo make sure this is correct
        ));

        $userArray = Engine_Api::_()->getApi('Core', 'siteapi')->validateUserArray($user, array('email'));

        // Add images
        $getContentImages = Engine_Api::_()->getApi('core', 'siteapi')->getContentImage($user);
        $userArray = array_merge($userArray, $getContentImages);

        $userArray['cover'] = $userArray['image'];

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
                                        'ip' => $_REQUEST['ip'],
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

//        $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);

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

        $this->respondWithSuccess(array(
            'oauth_token' => $getOauthToken['token'],
            'oauth_secret' => $getOauthToken['secret'],
            'user' => $userArray,
        ));
    }

    public function saveProfileFields($user, $data) {
        // Profile Fields: start work to save profile fields.
        $profileTypeField = null;
        $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
        if (count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type') {
            $profileTypeField = $topStructure[0]->getChild();
        }

        if ($profileTypeField) {
            $profileTypeValue = $data['profile_type'];
            if ($profileTypeValue) {
                $values = Engine_Api::_()->fields()->getFieldsValues($user);
                $valueRow = $values->createRow();
                $valueRow->field_id = $profileTypeField->field_id;
                $valueRow->item_id = $user->getIdentity();
                $valueRow->value = $data['profile_type'];
                $valueRow->save();
            } else {
                $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
                if (count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type') {
                    $profileTypeField = $topStructure[0]->getChild();
                    $options = $profileTypeField->getOptions();
                    if (count($options) == 1) {
                        $values = Engine_Api::_()->fields()->getFieldsValues($user);
                        $valueRow = $values->createRow();
                        $valueRow->field_id = $profileTypeField->field_id;
                        $valueRow->item_id = $user->getIdentity();
                        $valueRow->value = $options[0]->option_id;
                        $valueRow->save();
                    }
                }
            }

            // Save the profile fields information.
            Engine_Api::_()->getApi('Siteapi_Core', 'user')->setProfileFields($user, $data);

            // Set Displayname
            $aliasValues = Engine_Api::_()->fields()->getFieldsValuesByAlias($user);
            $user->setDisplayName($aliasValues);
            $user->save();
        }
    }

    /**
     * Login to Twitter
     * 
     * @return array
     */
    public function twitterLoginAction() {
        $user = $this->getRequestParam("user", null);

        //create auth token and store in database user tokens table.    
        $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
        $tokensTable = Engine_Api::_()->getDbtable('tokens', 'siteapi');
        $tokeTableSelect = $tokensTable->select()
                ->where('user_id = ?', $user->getIdentity());          // If post exists
        $userToken = $tokensTable->fetchRow($tokeTableSelect);

        if (!empty($userToken) && !empty($userToken->token)) {
            $auth_token = $userToken->token;
            $getOauthToken['token'] = $userToken->token;
            $getOauthToken['secret'] = $userToken->secret;
        } else {
            $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);
        }

        $twitterTable->insert(array(
            'user_id' => $user->getIdentity(),
            'twitter_uid' => $_REQUEST['twitter_uid'],
            'twitter_token' => $_REQUEST['twitter_token'],
            'twitter_secret' => $_REQUEST['twitter_secret']
        ));

        $userArray = Engine_Api::_()->getApi('Core', 'siteapi')->validateUserArray($user, array('email'));

        // Add images
        $getContentImages = Engine_Api::_()->getApi('core', 'siteapi')->getContentImage($user);
        $userArray = array_merge($userArray, $getContentImages);

        $userArray['cover'] = $userArray['image'];

        $device_token = !empty($_REQUEST['registration_id']) ? $_REQUEST['registration_id'] : '';
        $device_token = !empty($_REQUEST['device_token']) ? $_REQUEST['device_token'] : $device_token;
        if (!empty($_REQUEST['device_uuid']) && !empty($device_token)) {
            Engine_Api::_()->getDbtable('gcmusers', 'siteandroidapp')->addGCMuser(array(
                'device_uuid' => $_REQUEST['device_uuid'],
                'registration_id' => $device_token,
                'user_id' => $user->getIdentity()
            ));

            Engine_Api::_()->getDbtable('apnusers', 'siteiosapp')->addApnuser(array(
                'device_uuid' => $_REQUEST['device_uuid'],
                'token' => $device_token,
                'user_id' => $user->getIdentity()
            ));
        }

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
                                    $isIosSubscriber = Engine_Api::_()->getApi('core', 'siteapi')->hasUserIosSubscription($user);
                                    if (isset($isIosSubscriber) && !empty($isIosSubscriber)) {
                                        Engine_Api::_()->getApi('core', 'siteapi')->hasUserIosSubscriptionExpire($user);
                                    }
                                }

                                if (!$subscriptionsTable->check($user)) {
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

//        $getOauthToken = Engine_Api::_()->getApi('oauth', 'siteapi')->getAccessOauthToken($user);

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

        $this->respondWithSuccess(array(
            'oauth_token' => $getOauthToken['token'],
            'oauth_secret' => $getOauthToken['secret'],
            'user' => $userArray,
        ));
    }

    /**
     * Validate posted signup form
     * 
     * @return array
     */
    private function _validateForm($values) {
        $validationMessage = array();

        // Enable: account form validation.
        $values['account_validation'] = $this->getRequestParam('account_validation', true);

        // Enable: field form validation.
        $values['fields_validation'] = $this->getRequestParam('fields_validation', true);

        // Set the default profile field id.
        if (isset($values['fields_validation']) && !empty($values['fields_validation']) && empty($values['profile_type'])) {
            $profileFields = Engine_Api::_()->getApi('Siteapi_Core', 'user')->getProfileTypes();
            if (!empty($profileFields) && (COUNT($profileFields) == 1)) {
                $values['profile_type'] = @end(@array_flip($profileFields));
            }
        }

        // If enable the "Field Validations" and "Profile Type" id not exist then return the error. 
        if (!empty($values['fields_validation']) && empty($values['profile_type'])) {
            $this->_forward('throw-error', 'signup', 'user', array(
                "error_code" => "profile_type_missing"
            ));
            return;
        }

        // Getting the validator array
        $validators = Engine_Api::_()->getApi('Siteapi_FormValidators', 'user')->getSignupFormValidators($values);
        $values['validators'] = $validators;
        $validationMessage = $this->isValid($values);

        if (isset($_REQUEST['terms']) && empty($_REQUEST['terms'])) {
            $validationMessage = (is_array($validationMessage)) ? $validationMessage : array();
            $validationMessage['terms'] = $this->translate('Please complete this field - it is required.');
        }

        if (!empty($_REQUEST['facebook_uid'])) {
            $validationMessage = (is_array($validationMessage)) ? $validationMessage : array();

            $facebook_uid = $this->getRequestParam('facebook_uid', null);
            if (empty($facebook_uid))
                $validationMessage['facebook_uid'] = 'Please complete this field - it is required.';

            $access_token = $this->getRequestParam('access_token', null);
            if (empty($access_token))
                $validationMessage['access_token'] = 'Please complete this field - it is required.';

            $code = $this->getRequestParam('code', null);
            if (empty($code))
                $validationMessage['code'] = 'Please complete this field - it is required.';
        }

        if (!empty($_REQUEST['twitter_uid'])) {
            $validationMessage = (is_array($validationMessage)) ? $validationMessage : array();

            $twitter_uid = $this->getRequestParam('twitter_uid', null);
            if (empty($twitter_uid))
                $validationMessage['twitter_uid'] = 'Please complete this field - it is required.';

            $twitter_token = $this->getRequestParam('twitter_token', null);
            if (empty($twitter_token))
                $validationMessage['twitter_token'] = 'Please complete this field - it is required.';

            $twitter_secret = $this->getRequestParam('twitter_secret', null);
            if (empty($twitter_secret))
                $validationMessage['twitter_secret'] = 'Please complete this field - it is required.';
        }

        /*
         * Start: manual signup form validations.
         */

        if (empty($_REQUEST['facebook_uid']) && empty($_REQUEST['twitter_uid'])) {
            // Validate: password and confirm password.
            if (!empty($values['password']) && !empty($values['passconf']) && ($values['password'] != $values['passconf'])) {
                $validationMessage = (is_array($validationMessage)) ? $validationMessage : array();
                $validationMessage['passconf'] = $this->getMessageTemplate('password_mismatch');
            }
        }


        // Validate: timezone
        if (!empty($values['timezone'])) {
            $timeZone = Engine_Api::_()->getApi('Siteapi_Core', 'user')->_getTimeZone;
            if (!array_key_exists($values['timezone'], $timeZone)) {
                $validationMessage = (is_array($validationMessage)) ? $validationMessage : array();
                $validationMessage['timezone'] = $this->getMessageTemplate('timezone_mismatch');
            }
        }

        // Validate: languages
//        if (!empty($values['language'])) {
//            $languages = Engine_Api::_()->getApi('Siteapi_Core', 'user')->getLanguages();
//            if (!array_key_exists($values['language'], $languages)) {
//                $validationMessage = (is_array($validationMessage)) ? $validationMessage : array();
//                $validationMessage['language'] = $this->getMessageTemplate('language_mismatch');
//            }
//        }

        return $validationMessage;
    }

    /**
     * Create user using posted values.
     * 
     * @return array
     */
    private function _saveUser($data) {
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $random = ($settings->getSetting('user.signup.random', 0) == 1);
        $emailadmin = ($settings->getSetting('user.signup.adminemail', 0) == 1);
        if ($emailadmin) {
            // the signup notification is emailed to the first SuperAdmin by default
            $users_table = Engine_Api::_()->getDbtable('users', 'user');
            $users_select = $users_table->select()
                    ->where('level_id = ?', 1)
                    ->where('enabled >= ?', 1);
            $super_admin = $users_table->fetchRow($users_select);
        }

        if (empty($_REQUEST['facebook_uid']) && empty($_REQUEST['twitter_uid']) && $random) {
            $data['password'] = Engine_Api::_()->user()->randomPass(10);
        }

        if (isset($data['language'])) {
            $data['locale'] = $data['language'];
        }

        // Create user
        $user = Engine_Api::_()->getDbtable('users', 'user')->createRow();
        $user->setFromArray($data);
        $user->save();

        $this->saveProfileFields($user, $data);

        // Increment signup counter
        Engine_Api::_()->getDbtable('statistics', 'core')->increment('user.creations');

        if ($user->verified && $user->approved) {
            // Create activity for them
            Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($user, $user, 'signup');
            // Set user as logged in if not have to verify email
            Engine_Api::_()->user()->getAuth()->getStorage()->write($user->getIdentity());
        }

        $mailType = null;
        $mailParams = array(
            'host' => $_SERVER['HTTP_HOST'],
            'email' => $user->email,
            'date' => time(),
            'recipient_title' => $user->getTitle(),
            'recipient_link' => $user->getHref(),
            'recipient_photo' => $user->getPhotoUrl('thumb.icon'),
            'object_link' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
        );

        // Add password to email if necessary
        if (empty($_REQUEST['facebook_uid']) && empty($_REQUEST['twitter_uid']) && $random) {
            $mailParams['password'] = $data['password'];
        }

        // Mail stuff
        switch ($settings->getSetting('user.signup.verifyemail', 0)) {
            case 0:
                // only override admin setting if random passwords are being created
                if ($random) {
                    $mailType = 'core_welcome_password';
                }
                if ($emailadmin) {
                    $mailAdminType = 'notify_admin_user_signup';

                    $mailAdminParams = array(
                        'host' => $_SERVER['HTTP_HOST'],
                        'email' => $user->email,
                        'date' => date("F j, Y, g:i a"),
                        'recipient_title' => $super_admin->displayname,
                        'object_title' => $user->displayname,
                        'object_link' => $user->getHref(),
                    );
                }
                break;

            case 1:
                // send welcome email
                $mailType = ($random ? 'core_welcome_password' : 'core_welcome');
                if ($emailadmin) {
                    $mailAdminType = 'notify_admin_user_signup';

                    $mailAdminParams = array(
                        'host' => $_SERVER['HTTP_HOST'],
                        'email' => $user->email,
                        'date' => date("F j, Y, g:i a"),
                        'recipient_title' => $super_admin->displayname,
                        'object_title' => $user->getTitle(),
                        'object_link' => $user->getHref(),
                    );
                }
                break;

            case 2:
                // verify email before enabling account
                $verify_table = Engine_Api::_()->getDbtable('verify', 'user');
                $verify_row = $verify_table->createRow();
                $verify_row->user_id = $user->getIdentity();
                $verify_row->code = md5($user->email
                        . $user->creation_date
                        . $settings->getSetting('core.secret', 'staticSalt')
                        . (string) rand(1000000, 9999999));
                $verify_row->date = $user->creation_date;
                $verify_row->save();

                $token = base64_encode(time() . ":" . $user->getIdentity());
                $mailType = ($random ? 'core_verification_password' : 'core_verification');

                $mailParams['object_link'] = Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
                    'action' => 'verify',
                    'email' => $user->email,
                    'token' => $token,
                    'verify' => $verify_row->code
                        ), 'user_signup', true);

                if ($emailadmin) {
                    $mailAdminType = 'notify_admin_user_signup';

                    $mailAdminParams = array(
                        'host' => $_SERVER['HTTP_HOST'],
                        'email' => $user->email,
                        'date' => date("F j, Y, g:i a"),
                        'recipient_title' => $super_admin->displayname,
                        'object_title' => $user->getTitle(),
                        'object_link' => $user->getHref(),
                    );
                }
                break;

            default:
                // do nothing
                break;
        }

        if (!empty($mailType)) {
            // Moved to User_Plugin_Signup_Fields
            Engine_Api::_()->getApi('mail', 'core')->sendSystem(
                    $user, $mailType, $mailParams
            );
        }

        if (!empty($mailAdminType)) {
            // Moved to User_Plugin_Signup_Fields
            Engine_Api::_()->getApi('mail', 'core')->sendSystem(
                    $user, $mailAdminType, $mailAdminParams
            );
        }
        return $user;
    }

    /**
     *  @param Get Field Structure 
     *  @param Get Form Data 
     * @return User _Search Data here.  
     */
    public function setFieldSearchStructure($result) {
        foreach ($result as $key => $value) {
            if (strstr($key, '_first_name')) {
                $fieldStructure['first_name'] = $value;
            } else if (strstr($key, '_last_name')) {
                $fieldStructure['last_name'] = $value;
            } else if (strstr($key, '_gender')) {
                $fieldStructure['gender'] = $value;
            } else if (strstr($key, '_birthdate')) {
                $fieldStructure['birthdate'] = $value;
            }
        }

        return $fieldStructure;
    }

}
