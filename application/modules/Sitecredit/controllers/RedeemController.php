<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: RedeemController.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_RedeemController extends Core_Controller_Action_Standard {

    /**
     * @var User_Model_User
     */
    protected $_user;

    /**
     * @var Zend_Session_Namespace
     */
    protected $_session;

    public function init() {
        $this->_session = new Zend_Session_Namespace('Payment_Subscription');
        // Get user and session
        $this->_user = Engine_Api::_()->user()->getViewer();

        // Check viewer and user
        if (!$this->_user->getIdentity()) {
            if (!empty($this->_session->user_id)) {
                $this->_user = Engine_Api::_()->getItem('user', $this->_session->user_id);
            }
        }
    }

    public function indexAction() {

        $credit_code = $this->_getParam('code');
        if (empty($credit_code)) {
            $this->view->error_message = $this->view->translate("Please enter valid ".$GLOBALS['credits'].".");
            return;
        }
        $module_name = $this->_getParam('package_type');

        if ($module_name == 'payment') {
            $sessionName = 'Payment_Subscription';
            $itemId = 'subscription_id';
            $itemType = 'payment_subscription';
        } else
            return;
        $this->view->credit_error_msg = '';
        //HERE WE CREATE SESSION
        $session = new Zend_Session_Namespace($sessionName);
        $user_id = $session->user_id;

        //HERE WE GET ID 
        $resourceId = $session->$itemId;

        //HERE WE GET OBJECT ACCRODING TO THE ITEM TYPE AND RESOURCE ID.
        $itemObject = Engine_Api::_()->getItem($itemType, $resourceId);
        $package = $itemObject->getPackage();

        $packageType = $package->getType();

        $package_id = $itemObject->package_id;

        $param = array('user_id' => $user_id,
            'basedon' => 0,
            'count' => 1,
        );
        $credits = Engine_Api::_()->getDbtable('credits', 'sitecredit')->Credits($param);
        $totalCredits = $credits->credit;
        if (empty($totalCredits)) {
            $this->view->credit_error_msg = $this->view->translate("Sorry !! You don't have ".$GLOBALS['credits']." to redeem.");
            return;
        }

        $CreditModuleTable = Engine_Api::_()->getDbtable('modules', 'sitecredit');
        $select = $CreditModuleTable->select()->where('name = ?', 'payment');
        $creditModuleAllow = $CreditModuleTable->fetchRow($select);

        if (empty($creditModuleAllow->integrated)) {
            $this->view->credit_error_msg = $this->view->translate("Sorry !! You can't redeem ".$GLOBALS['credits']." here.");
            return;
        }

        if (!empty($creditModuleAllow->minimum_credit) && ($totalCredits < $creditModuleAllow->minimum_credit)) {
            $this->view->credit_error_msg = $this->view->translate("Sorry !! You should have minimum " . $creditModuleAllow->minimum_credit . " ".$GLOBALS['credit']." balance.");
            return;
        }

        if (!empty($package_id)) {
            $packageResults = Engine_Api::_()->getItem($packageType, $package_id);
        }

        if (empty($packageResults)) {
            $this->view->credit_error_msg = $this->view->translate("Please select a valid Plan.");
            return;
        }

        if (!empty($creditModuleAllow->minimum_checkout_total) && ($packageResults->price < $creditModuleAllow->minimum_checkout_total)) {

            $this->view->credit_error_msg = $this->view->translate("Sorry !! You should have minimum " . $creditModuleAllow->minimum_checkout_total . " checkout balance.");
            return;
        }

        $creditValue = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.value', 0);
        if (empty($creditValue)) {
            $this->view->credit_error_msg = $this->view->translate("No valid ".$GLOBALS['credit']." are set for ".$GLOBALS['credit']." value. Please contact site admin.");
            return;
        }

        $validPercentage = $creditModuleAllow->percentage_checkout;
        if (empty($validPercentage)) {
            $this->view->credit_error_msg = $this->view->translate("No valid percentage is set for checkout limit. Please contact site admin.");
            return;
        }

        $maxAmountDeduction = ( ($packageResults->price * $validPercentage ) / 100 );

        $maxCredits = $creditValue * $maxAmountDeduction;

        if ($credit_code > $maxCredits) {
            $this->view->credit_error_msg = $this->view->translate("You can use only upto " . $maxCredits . " ".$GLOBALS['credits'].".");
            return;
        }

        $ToalamountToDeduct = ($credit_code / $creditValue);

        $discount_amount = @round($ToalamountToDeduct, 2);

        $body = '<ul style="margin:0px 0px 5px;" class="form-notices">
<li style="margin:0px;"> These '.$GLOBALS['credits'].' are valid.</li></ul>';
        $body.= '<p>Discount Price:' . Engine_Api::_()->sitecredit()->getPriceWithCurrency($discount_amount) . '</b></p>';

        $body.='Please setup your subscription to continue after discount:' . Engine_Api::_()->sitecredit()->getPriceWithCurrency(($packageResults->price - $discount_amount));

        //CREATE SESSION 
        //create session
        $session = new Zend_Session_Namespace('payment_subscription_credit');
        if (!empty($session->paymentSubscriptionCreditDetail)) {
            $session->paymentSubscriptionCreditDetail = null;
        }

        $credit_details_array = array('credit_points' => $credit_code, 'credit_amount' => $discount_amount, 'package_id' => $package_id, 'package_type' => $packageType);

        $session->paymentSubscriptionCreditDetail = serialize($credit_details_array);

        $this->_helper->contextSwitch->initContext();
        return $this->_helper->json(array('status' => true, 'body' => $body));
    }

    //FUNCTION FOR REDIRECT TO PAYMENT PAGE.
    public function processAction() {

        //GET THE MODULE NAME.
        $module_name = $this->_getParam('package_type');

        //SET THE VARIABLE ACCRODING TO THE MODULE NAME.
        if ($module_name == 'payment') {
            $session_name = 'Payment_Subscription';
            $gateway_name = 'payment_gateway';
            $module_id = 'subscription_id';
            $itemType = 'payment_subscription';
        } else
            return;

        //MAKE SESSION ACCRODING TO THE MODULE NAME.
        $session = new Zend_Session_Namespace($session_name);

        //SESSION OF USER AND GET THE USER ID AND OBJECT OF USER.
        //CHECK VIEWER AND USER
        if (!$this->_user || !$this->_user->getIdentity()) {
            if (!empty($session->user_id)) {
                $this->_user = Engine_Api::_()->getItem('user', $session->user_id);
            }
        }

        $user_id = $this->_user->user_id;
        $user = Engine_Api::_()->getItem('user', $user_id);

        //GET GATEWAY
        $gatewayId = $this->_getParam('gateway_id', $session->gateway_id);
        if (!$gatewayId ||
                !($gateway = Engine_Api::_()->getItem($gateway_name, $gatewayId)) ||
                !($gateway->enabled)) {
            return $this->_helper->redirector->gotoRoute(array('action' => 'gateway'));
        }

        $this->view->gateway = $gateway;

        //GET SUBSCRIPTION
        $subscriptionId = $this->_getParam($module_id, $session->$module_id);
        if (!$subscriptionId ||
                !($subscription = Engine_Api::_()->getItem($itemType, $subscriptionId))) {
            if ($module_name == 'payment') {
                return $this->_helper->redirector->gotoRoute(array('action' => 'choose'));
            }
        }
        $this->view->subscription = $subscription;

        //GET PACKAGE
        $package = $subscription->getPackage();
        if (!$package || $package->isFree()) {
            if ($module_name == 'payment') {
                return $this->_helper->redirector->gotoRoute(array('action' => 'choose'));
            }
        }
        $this->view->package = $package;

        //SESSION SET OF CREDITS.
        $creditSession = new Zend_Session_Namespace('payment_subscription_credit');

        if (!empty($creditSession->paymentSubscriptionCreditDetail)) {
            $creditDetail = unserialize($creditSession->paymentSubscriptionCreditDetail);
        } else
            return;


        $discount_value = $creditDetail['credit_amount'];
        $credit_value = $creditDetail['credit_points'];

        $totalValue = $package->price - $discount_value;
        $package->price = @round($totalValue, 2);
        
        
       
        // PROCESS
        // CREATE ORDER
        $ordersTable = Engine_Api::_()->getDbtable('orders', 'payment');
        if (!empty($session->order_id)) {
            $previousOrder = $ordersTable->find($session->order_id)->current();
            if ($previousOrder && $previousOrder->state == 'pending') {
                $previousOrder->state = 'incomplete';
                $previousOrder->save();
            }
        }
        $ordersTable->insert(array(
            'user_id' => $user->getIdentity(),
            'gateway_id' => $gateway->gateway_id,
            'state' => 'pending',
            'creation_date' => new Zend_Db_Expr('NOW()'),
            'source_type' => $itemType,
            'source_id' => $subscription->$module_id,
        ));
        $session->order_id = $order_id = $ordersTable->getAdapter()->lastInsertId();
      
                //UNSET CERTAIN KEYS
        unset($session->package_id);
        unset($session->$module_id);
        unset($session->gateway_id);

        //GET GATEWAY PLUGIN
        $this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
        $plugin = $gateway->getPlugin();
        
        if(empty($package->price)){
                    $order = Engine_Api::_()->getItem('payment_order', $order_id);        
                    $sub_user = $order->getUser();
                    $sub_sub = $order->getSource();
                    $sub_package = $sub_sub->getPackage();
                    $order->state = 'complete';
                    $order->save();
                    $sub_sub->onPaymentSuccess();
                    if ($sub_sub->didStatusChange()) {
                        Engine_Api::_()->getApi('mail', 'core')->sendSystem($sub_user, 'payment_subscription_active', array(
                            'subscription_title' => $sub_package->title,
                            'subscription_description' => $sub_package->description,
                            'subscription_terms' => $sub_package->getPackageDescription(),
                            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                            Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
                        ));
                    }
                    $param['type_id']=$order->order_id;
                    $param['credit_point']=-$creditDetail['credit_points'];
                    $param['type']='subscription';
                    $param['user_id']=$sub_user->getIdentity();
                    $param['reason']='used '.$GLOBALS['credits'].' for subscription';
                    $credit_table = Engine_Api::_()->getDbtable('credits','sitecredit');    
                    $credit_table->insertCredit($param);
                    $creditSession->packagePaymentCreditDetail = null;
                    
                    $viewer = Engine_Api::_()->user()->getViewer();
                    $user = $this->_user;
                    $state='active';
                     // No user?
                    if( !$this->_user ) {
                        return $this->_helper->redirector->gotoRoute(array(), 'default', true);
                    }
                    // Log the user in, if they aren't already
                    if( ($state == 'active' || $state == 'free') &&
                        $this->_user &&
                        !$this->_user->isSelf($viewer) &&
                        !$viewer->getIdentity() ) {
                      Zend_Auth::getInstance()->getStorage()->write($this->_user->getIdentity());
                      Engine_Api::_()->user()->setViewer();
                      $viewer = $this->_user;
                    }
                    // Handle email verification or pending approval
                    if( $viewer->getIdentity() && !$viewer->enabled ) {
                      Engine_Api::_()->user()->setViewer(null);
                      Engine_Api::_()->user()->getAuth()->getStorage()->clear();
                      $confirmSession = new Zend_Session_Namespace('Signup_Confirm');
                      $confirmSession->approved = $viewer->approved;
                      $confirmSession->verified = $viewer->verified;
                      $confirmSession->enabled  = $viewer->enabled;
                      return $this->_helper->_redirector->gotoRoute(array('action' => 'confirm'), 'user_signup', true);
                    }
    
                    // Clear session
                    $errorMessage = $this->_session->errorMessage;
                    $userIdentity = $this->_session->user_id;
                    $this->_session->unsetAll();
                    $this->_session->user_id = $userIdentity;
                    $this->_session->errorMessage = $errorMessage;
                    return $this->_helper->redirector->gotoRoute(array('module'=>'payment','controller'=>'subscription','action' => 'finish', 'state' => 'active'));
        }

         if(empty($package->price)){
                    $order = Engine_Api::_()->getItem('payment_order', $order_id);        
                    $sub_user = $order->getUser();
                    $sub_sub = $order->getSource();
                    $sub_package = $sub_sub->getPackage();
                    $order->state = 'complete';
                    $order->save();
                    $sub_sub->onPaymentSuccess();
                    if ($sub_sub->didStatusChange()) {
                        Engine_Api::_()->getApi('mail', 'core')->sendSystem($sub_user, 'payment_subscription_active', array(
                            'subscription_title' => $sub_package->title,
                            'subscription_description' => $sub_package->description,
                            'subscription_terms' => $sub_package->getPackageDescription(),
                            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                            Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
                        ));
                    }
                    $param['type_id']=$order->order_id;
                    $param['credit_point']=-$creditDetail['credit_points'];
                    $param['type']='subscription';
                    $param['user_id']=$sub_user->getIdentity();
                    $param['reason']='used credits for subscription';
                    $credit_table = Engine_Api::_()->getDbtable('credits','sitecredit');    
                    $credit_table->insertCredit($param);
                    $creditSession->packagePaymentCreditDetail = null;
                    
                    $viewer = Engine_Api::_()->user()->getViewer();
                    $user = $this->_user;
                    $state='active';
                     // No user?
                    if( !$this->_user ) {
                        return $this->_helper->redirector->gotoRoute(array(), 'default', true);
                    }

                    // Log the user in, if they aren't already
                    if( ($state == 'active' || $state == 'free') &&
                        $this->_user &&
                        !$this->_user->isSelf($viewer) &&
                        !$viewer->getIdentity() ) {
                      Zend_Auth::getInstance()->getStorage()->write($this->_user->getIdentity());
                      Engine_Api::_()->user()->setViewer();
                      $viewer = $this->_user;
                    }

                    // Handle email verification or pending approval
                    if( $viewer->getIdentity() && !$viewer->enabled ) {
                      Engine_Api::_()->user()->setViewer(null);
                      Engine_Api::_()->user()->getAuth()->getStorage()->clear();

                      $confirmSession = new Zend_Session_Namespace('Signup_Confirm');
                      $confirmSession->approved = $viewer->approved;
                      $confirmSession->verified = $viewer->verified;
                      $confirmSession->enabled  = $viewer->enabled;
                      return $this->_helper->_redirector->gotoRoute(array('action' => 'confirm'), 'user_signup', true);
                    }
    
                    // Clear session
                    $errorMessage = $this->_session->errorMessage;
                    $userIdentity = $this->_session->user_id;
                    $this->_session->unsetAll();
                    $this->_session->user_id = $userIdentity;
                    $this->_session->errorMessage = $errorMessage;

                    return $this->_helper->redirector->gotoRoute(array('module'=>'payment','controller'=>'subscription','action' => 'finish', 'state' => 'active'));

        }
        //PREPARE HOST INFO
        $schema = 'http://';
        if (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) {
            $schema = 'https://';
        }
        $host = $_SERVER['HTTP_HOST'];


        //PREPARE TRANSACTION
        $params = array();
        $params['language'] = $user->language;
        $localeParts = explode('_', $user->language);
        if (count($localeParts) > 1) {
            $params['region'] = $localeParts[1];
        }
        $params['vendor_order_id'] = $order_id;

        if ($module_name == 'payment') {

            $params['return_url'] = $schema . $host
                    . $this->view->url(array('action' => 'return', 'controller' => 'subscription', 'module' => 'payment'), 'default')
                    . '?order_id=' . $order_id
                    //. '?gateway_id=' . $this->_gateway->gateway_id
                    //. '&subscription_id=' . $this->_subscription->subscription_id
                    . '&state=' . 'return';
            $params['cancel_url'] = $schema . $host
                    . $this->view->url(array('action' => 'return', 'controller' => 'subscription', 'module' => 'payment'), 'default')
                    . '?order_id=' . $order_id
                    //. '?gateway_id=' . $this->_gateway->gateway_id
                    //. '&subscription_id=' . $this->_subscription->subscription_id
                    . '&state=' . 'cancel';
            $params['ipn_url'] = $schema . $host
                    . $this->view->url(array('action' => 'index', 'controller' => 'ipn', 'module' => 'payment'), 'default')
                    . '?order_id=' . $order_id;
            //. '?gateway_id=' . $this->_gateway->gateway_id
            //. '&subscription_id=' . $this->_subscription->subscription_id;
            // Process transaction
            $transaction = $plugin->createSubscriptionTransaction($user, $subscription, $package, $params);
        } else
            return;

        //PULL TRANSACTION PARAMS
        $this->view->transactionUrl = $transactionUrl = $gatewayPlugin->getGatewayUrl();
        $this->view->transactionMethod = $transactionMethod = $gatewayPlugin->getGatewayMethod();
        $this->view->transactionData = $transactionData = $transaction->getData();

        //SEND COUPON CODE FOR DECREASE THE VALUE OF PACKAGE.
        $transactionData['credit_discount'] = $discount_value;

        //HANDLE REDIRECTION
        if ($transactionMethod == 'GET') {
            $transactionUrl .= '?' . http_build_query($transactionData);
            return $this->_helper->redirector->gotoUrl($transactionUrl, array('prependBase' => false));
        }
        //POST WILL BE HANDLED BY THE VIEW SCRIPT
    }

    public function cancelPaymentSubscriptionAction() {
        //destroy session
        if (!$this->_helper->requireUser()->isValid())
            return;
        $creditSession = new Zend_Session_Namespace('payment_subscription_credit');

        if (!empty($creditSession->paymentSubscriptionCreditDetail)) {
            $creditSession->paymentSubscriptionCreditDetail = null;
        }
        $this->view->cart_credit_unset = true;
    }

    public function packagePurchaseAction() {
        //get package type
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $package_type = $this->_getparam('package_type');
        $package_id = $this->_getparam('package_id');
        $credit_code = $this->_getparam('credit_code');


        if (empty($credit_code) || empty($package_id) || empty($package_type)) {
            $this->view->credit_error_msg = $this->view->translate("Please enter valid ".$GLOBALS['credits'].".");
            return;
        }
        // fetch credits of user
        $param = array('user_id' => $viewer_id,
            'basedon' => 0,
            'count' => 1,);
        $credits = Engine_Api::_()->getDbtable('credits', 'sitecredit')->Credits($param);
        $totalCredits = $credits->credit;
        //check credit_code
        if ($credit_code > $totalCredits) {
            $this->view->credit_error_msg = $this->view->translate("You have only " . $totalCredits . " ".$GLOBALS['credits']);
            return;
        }

        $discount_amount = 0;
        $flag = 'package';
        if ($package_type == 'Siteeventpaid_package') {
            $package = Engine_Api::_()->getItem('siteeventpaid_package', $package_id);
            $module = 'Siteeventpaid';
        } else if ($package_type == 'sitestore_package') {
            $package = Engine_Api::_()->getItem('sitestore_package', $package_id);
            $module = 'sitestore';
        } else if ($package_type == 'sitepage_package') {
            $package = Engine_Api::_()->getItem('sitepage_package', $package_id);
            $module = 'sitepage';
        } else if ($package_type == 'sitereviewpaidlisting_package') {
            $package = Engine_Api::_()->getItem('sitereviewpaidlisting_package', $package_id);
            $module = 'sitereviewpaidlisting';
        } else if ($package_type == 'sitegroup_package') {
            $package = Engine_Api::_()->getItem('sitegroup_package', $package_id);
            $module = 'sitegroup';
        } else if ($package_type == 'communityad_package') {
            $package = Engine_Api::_()->getItem('package', $package_id);
            $module = 'communityad';
        }

        if (empty($package)) {
            $this->view->credit_error_msg = $this->view->translate("Please select a valid package.");
            return;
        }
        if (empty($package->price)) {
            $this->view->credit_error_msg = $this->view->translate("Free Package. You can't redeem ".$GLOBALS['credits']." here.");
            return;
        }
        //check store module is enabled or not in credit plugin settings
        $CreditModuleTable = Engine_Api::_()->getDbtable('modules', 'sitecredit');
        $select = $CreditModuleTable->select()->where('name = ?', $module)->where('flag = ?', $flag);
        $creditModuleAllow = $CreditModuleTable->fetchRow($select);
        if (empty($creditModuleAllow->integrated)) {
            $this->view->credit_error_msg = $this->view->translate("Sorry !! You can't redeem ".$GLOBALS['credits']." here.");
            return;
        }
        if (!empty($creditModuleAllow->minimum_credit) && ($totalCredits < $creditModuleAllow->minimum_credit)) {
            $this->view->credit_error_msg = $this->view->translate("Sorry !! You should have minimum " . $creditModuleAllow->minimum_credit . " ".$GLOBALS['credits']." balance.");
            return;
        }
        //get checkout limit and other details from credit module
        //check for minimium checkout limit
        $minimum_checkout_total = $creditModuleAllow->minimum_checkout_total;
        if (!empty($minimum_checkout_total)) {
            if ($minimum_checkout_total > $package->price) {
                $this->view->credit_error_msg = $this->view->translate("Checkout total should be greater than " . $minimum_checkout_total . " for using ".$GLOBALS['credits']);
                return;
            }
        }

        $validPercentage = $creditModuleAllow->percentage_checkout;

        if (empty($validPercentage)) {
            $this->view->credit_error_msg = $this->view->translate("No valid percentage is set for checkout limit. Please contact site admin.");
            return;
        }

        $maxAmountDeduction = ( ($package->price * $validPercentage ) / 100 );

        $creditValue = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.value', 0);
        if (empty($creditValue)) {
            $this->view->credit_error_msg = $this->view->translate("No valid ".$GLOBALS['credits']." are set for ".$GLOBALS['credit']." value. Please contact site admin.");
            return;
        }

        $maxCredits = $creditValue * $maxAmountDeduction;

        if ($credit_code > $maxCredits) {
            $this->view->credit_error_msg = $this->view->translate("You can use only upto " . @round($maxCredits) . " ".$GLOBALS['credits'].".");
            return;
        }

        $ToalamountToDeduct = ($credit_code / $creditValue);

        $discount_amount = @round($ToalamountToDeduct, 2);

        $credit_details_array = array('credit_points' => $credit_code, 'credit_amount' => $discount_amount, 'package_type' => $package->getType(), 'package_id' => $package_id);
// create session 
        $session = new Zend_Session_Namespace('credit_package_payment_' . $package->getType());
        if (!empty($session->packagePaymentCreditDetail)) {
            $session->packagePaymentCreditDetail = null;
        }
        $session->packagePaymentCreditDetail = serialize($credit_details_array);
        $this->view->credit_applied = true;
        $this->view->credit_success_msg = $this->view->translate('Please proceed payment for package purchase after discount : ' . Engine_Api::_()->sitecredit()->getPriceWithCurrency(($package->price - $discount_amount)));
        // on  
    }

    public function cancelPackagePurchaseAction() {

        $package_type = $this->_getparam('package_type');
        $package_id = $this->_getparam('package_id');

        if ($package_type == 'Siteeventpaid_package') {
            $package = Engine_Api::_()->getItem('siteeventpaid_package', $package_id);
        } elseif ($package_type == 'sitestore_package') {
            $package = Engine_Api::_()->getItem('sitestore_package', $package_id);
        } elseif ($package_type == 'sitepage_package') {
            $package = Engine_Api::_()->getItem('sitepage_package', $package_id);
        } elseif ($package_type == 'sitereviewpaidlisting_package') {
            $package = Engine_Api::_()->getItem('sitereviewpaidlisting_package', $package_id);
        } elseif ($package_type == 'sitegroup_package') {
            $package = Engine_Api::_()->getItem('sitegroup_package', $package_id);
        } elseif ($package_type == 'communityad_package') {
            $package = Engine_Api::_()->getItem('package', $package_id);
        }

        if (empty($package)) {
            return;
        }

        $session = new Zend_Session_Namespace('credit_package_payment_' . $package->getType());
        if (!empty($session->packagePaymentCreditDetail)) {
            $session->packagePaymentCreditDetail = null;
        }
        return true;
    }

}
