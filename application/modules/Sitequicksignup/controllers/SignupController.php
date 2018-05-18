<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: SignupController.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_SignupController extends Core_Controller_Action_Standard {

    public function init() {
        
    }

    public function indexAction() {
        // Render
        $this->_helper->content
                ->setContentName("user_signup_index");

        $disableContent = $this->_getParam('disableContent', 0);
        if (!$disableContent) {
            $this->_helper->content
                    ->setEnabled()
            ;
        }
        // Get settings
        $settings = Engine_Api::_()->getApi('settings', 'core');

        // If the user is logged in, they can't sign up now can they?
        if (Engine_Api::_()->user()->getViewer()->getIdentity()) {
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }

        $formSequenceHelper = $this->_helper->formSequence;
        $singupStepsConfig = array(
          'Payment_Plugin_Signup_Subscription' => 998, 'Sitesubscription_Plugin_Signup_Subscription' => 999, 'Siteotpverifier_Plugin_Signup_Otpverify' => 100,
        );
        $singupSteps = array(
          array('class'  =>'Sitequicksignup_Plugin_Signup_Fields', 'order' => 1)
        );
        foreach( Engine_Api::_()->getDbtable('signup', 'user')->fetchAll() as $row ) {
          if( $row->enable == 1 && isset($singupStepsConfig[$row->class])) {
            $singupSteps[] = array('class' => $row->class, 'order' => $singupStepsConfig[$row->class]);
          }
        }
        foreach( $singupSteps as $step ) {
          $class = $step['class'];
          $formSequenceHelper->setPlugin(new $class, $step['order']);
        }

    // This will handle everything until done, where it will return true
        if (!$this->_helper->formSequence()) {
            return;
        }

        // Get viewer
        $viewer = Engine_Api::_()->user()->getViewer();

        // Run post signup hook
        $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('onUserSignupAfter', $viewer);
        $responses = $event->getResponses();
        if ($responses) {
            foreach ($event->getResponses() as $response) {
                if (is_array($response)) {
                    // Clear login status
                    if (!empty($response['error'])) {
                        Engine_Api::_()->user()->setViewer(null);
                        Engine_Api::_()->user()->getAuth()->getStorage()->clear();
                    }
                    // Redirect
                    if (!empty($response['redirect'])) {
                        return $this->_helper->redirector->gotoUrl($response['redirect'], array('prependBase' => false));
                    }
                }
            }
        }

        // Handle subscriptions
        if (Engine_Api::_()->hasModuleBootstrap('payment')) {
            // Check for the user's plan
            $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
            if (!$subscriptionsTable->check($viewer)) {

                // Handle default payment plan
                $defaultSubscription = null;
                try {
                    $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                    if ($subscriptionsTable) {
                        $defaultSubscription = $subscriptionsTable->activateDefaultPlan($viewer);
                        if ($defaultSubscription) {
                            // Re-process enabled?
                            $viewer->enabled = true;
                            $viewer->save();
                        }
                    }
                } catch (Exception $e) {
                    // Silence
                }

                if (!$defaultSubscription) {
                    // Redirect to subscription page, log the user out, and set the user id
                    // in the payment session
                    $subscriptionSession = new Zend_Session_Namespace('Payment_Subscription');
                    $subscriptionSession->user_id = $viewer->getIdentity();

                    Engine_Api::_()->user()->setViewer(null);
                    Engine_Api::_()->user()->getAuth()->getStorage()->clear();

                    if (!empty($subscriptionSession->subscription_id)) {
                        return $this->_helper->redirector->gotoRoute(array('module' => 'payment',
                                    'controller' => 'subscription', 'action' => 'gateway'), 'default', true);
                    } else {
                        return $this->_helper->redirector->gotoRoute(array('module' => 'payment',
                                    'controller' => 'subscription', 'action' => 'index'), 'default', true);
                    }
                }
            }
        }
        // Overriding the current object of viewer, just for assurance 
        $viewer = Engine_Api::_()->user()->getUser($viewer->getIdentity());
        // Handle email verification or pending approval
        if (!$viewer->enabled) {
            Engine_Api::_()->user()->setViewer(null);
            Engine_Api::_()->user()->getAuth()->getStorage()->clear();

            $confirmSession = new Zend_Session_Namespace('Signup_Confirm');
            $confirmSession->approved = $viewer->approved;
            $confirmSession->verified = $viewer->verified;
            $confirmSession->enabled = $viewer->enabled;
            return $this->_helper->_redirector->gotoRoute(array('action' => 'confirm'), 'user_signup', true);
        }

        // Handle normal signup
        else {
            Engine_Api::_()->user()->getAuth()->getStorage()->write($viewer->getIdentity());
            Engine_Hooks_Dispatcher::getInstance()
                    ->callEvent('onUserEnable', array('user' => $viewer, 'shouldSendEmail' => false));
        }

        // Set lastlogin_date here to prevent issues with payment
        if ($viewer->getIdentity()) {
            $viewer->lastlogin_date = date("Y-m-d H:i:s");
            if ('cli' !== PHP_SAPI) {
                $ipObj = new Engine_IP();
                $viewer->lastlogin_ip = $ipObj->toBinary();
            }
            $viewer->save();
        }

        return $this->_helper->_redirector->gotoRoute(array('action' => 'home'), 'user_general', true);
    }

    public function checkCaptchaAction() {

        $captchaId = $this->_getParam('captcha_id');
        $captchaInput = $this->_getParam('captcha_input');
        $captchaSession = new Zend_Session_Namespace('Zend_Form_Captcha_' . $captchaId);
        $captchaIterator = $captchaSession->getIterator();
        print_r($captchaIterator);
        die;
        $captchaTrue = 0;
        if ($captchaIterator['word'] == $captchaInput) {
            $captchaTrue = 1;
        } else {
            $captchaTrue = 0;
        }

        echo Zend_Json::encode(array('captchaTrue' => $captchaTrue));
        exit();
    }

}
