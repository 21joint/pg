<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_AuthController extends Pgservicelayer_Controller_Action_Api {
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
        $siteapiUserLoginAuthentication = 1;
        $viewer = Engine_Api::_()->user()->getViewer();
        if ($viewer->getIdentity()){
            Engine_Api::_()->user()->getAuth()->clearIdentity();
        }

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
            $siteapiGlobalView = 1;
            $hostType = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
            $siteapiManageType = 1;
            $siteapiGlobalType = 1;
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

            
            if (false) {
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

}
