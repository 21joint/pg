<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_IndexController extends Core_Controller_Action_Standard {

    public function init() {
        // Get viewer
        $viewer = Engine_Api::_()->user()->getViewer();
        if ($viewer) {
            Engine_Api::_()->core()->setSubject($viewer);
        }
    }

    public function indexAction() {
        // Render
        $this->_helper->content->setEnabled();

        if (!$this->_helper->requireUser()->isValid())
            return;
    }

    public function transactionAction() {
        // Render
        $this->_helper->content->setEnabled();
        if (!$this->_helper->requireUser()->isValid())
            return;
    }

    public function earncreditAction() {
        // Render
        $this->_helper->content->setEnabled();

        if (!$this->_helper->requireUser()->isValid())
            return;
    }

    public function upgradeAction() {
        //  Save upgrade request for future refrences and admin approval 
        $this->view->form = $form = new Sitecredit_Form_Sendtofriend();
        $form->removeElement('friend_name');
        $form->removeElement('credit_point');
        $form->removeElement('friend_id');
        $form->removeElement('reason');
        $sitecreditUpgrade = Zend_Registry::isRegistered('sitecreditUpgrade') ? Zend_Registry::get('sitecreditUpgrade') : null;

        $form->setTitle('Upgrade Request')
                ->setDescription('Your member level will be upgraded after admin approval. Once Admin approve your request your level will be upgraded');
        $form->sendcredit->setLabel("Upgrade Level");
        $form->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => '',
            'onClick' => 'javascript:parent.Smoothbox.close();',
            'decorators' => array(
                'ViewHelper'
            )
        ));
        $form->addDisplayGroup(array('submit', 'cancel'), 'buttons');
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $params = $request->getParams();
        if (empty($sitecreditUpgrade))
            return;
        if (isset($params['sendcredit']) && $form->isValid($request->getParams())) {

            $param['user_id'] = Engine_Api::_()->user()->getViewer()->getIdentity();
            $param['current_level'] = Engine_Api::_()->user()->getViewer()->level_id;
            $param['requested_level'] = $this->_getParam('level_id');
            $param['status'] = 'pending';
            $param['creation_date'] = new Zend_Db_Expr('NOW()');
            try {

                $upgradetable = Engine_Api::_()->getDbtable('upgraderequests', 'sitecredit');

                $row = $upgradetable->createRow();
                $row->setFromArray($param);
                $row->save();
            } catch (Expression $e) {
                throw $e;
            }
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('')
            ));
        }
    }

    public function viewAction() {
        // display data to user corresponding to credit type - browse transaction widget
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('id');
        // Check post
        $this->view->language = Zend_Registry::get('Locale')->getLanguage();
        $this->view->result = $credit = Engine_Api::_()->getItem('credit', $id);
    }

    public function successAction() {
        //check payment status
        if (!$this->_helper->requireUser()->isValid())
            return;

        $this->view->viewer_id = $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

        $session = new Zend_Session_Namespace('Sitecredit_Order_Payment_Detail');
        $parent_id = $session->sitecreditOrderPaymentDetail['success_id'];
        $this->view->state = $state = $session->sitecreditOrderPaymentDetail['state'];
        $this->view->error = $error = $session->sitecreditOrderPaymentDetail['errorMessage'];
        $this->view->order_id = $parent_id;

        $parent_order_obj = Engine_Api::_()->getItem('sitecredit_order', $parent_id);

        if (empty($parent_id) || empty($parent_order_obj)) {
            return $this->_forward('notfound', 'error', 'core');
        }
        $sitecreditSuccess = Zend_Registry::isRegistered('sitecreditSuccess') ? Zend_Registry::get('sitecreditSuccess') : null;

        $order_table = Engine_Api::_()->getDbtable('orders', 'sitecredit');
        $credit_table = Engine_Api::_()->getDbtable('credits', 'sitecredit');

        if ($state == 'active') {
            $order_table->update(array('payment_status' => $state, 'order_status' => 'complete'), array('order_id = ?' => $parent_order_obj->order_id));

            $param = array();
            $param['type_id'] = $parent_order_obj->order_id;
            $param['credit_point'] = $parent_order_obj->credit_point;
            $param['type'] = 'buy';
            $param['user_id'] = $viewer_id;
            $param['reason'] = 'buy credits';
            $credit_id = $credit_table->insertCredit($param);
            /*    if(!empty($credit_id)){
              $value= $credit_table->fetchRow(array('credit_id = ?' => $credit_id));
              Engine_Api::_()->getApi('core', 'sitecredit')->sendEmailToUser($value,"credits_purchased_by_member");
              } */
            $success_message = '<b>' . $this->view->translate("Thanks for purchasing Credits! These credits will be added in your credit balance.") . '</b><br/><br/>';
            $this->view->success_message = $success_message;
        } else if ($state == 'pending') {
            $order_table->update(array('payment_status' => $state), array('order_id = ?' => $parent_order_obj->order_id));
        } else {
            $order_table->update(array('payment_status' => $state, 'order_status' => 'failed'), array('order_id = ?' => $parent_order_obj->order_id));
        }
        if (empty($sitecreditSuccess))
            return;
        $this->view->viewerOrders = $parent_order_obj->order_id;
    }

    public function signupAction() {
        // redirect user to core signup url after fetching parameters.
        $hash = $this->_getParam('affiliate');
        $code = $this->_getParam('code');
        $email = $this->_getParam('email');
        $LinkTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
        $select = $LinkTable->select()->where('Affiliate_link =? ', $hash);
        $user = $LinkTable->fetchRow($select);
        $Affiliate_session = new Zend_Session_Namespace('Affiliate_Link_Sitecredit');
        unset($Affiliate_session->user_id);
        unset($Affiliate_session->hash);
        if (!empty($user)) {
            $Affiliate_session->user_id = $user->user_id;
            $Affiliate_session->hash = $hash;
        }

        $hostType = "http://";
        if ((!empty($_SERVER["HTTPS"])) && (@$_SERVER["HTTPS"] == "on")) {
            $hostType = "https://";
        }
        //redirect
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        if ((Engine_Api::_()->getApi('settings', 'core')->getSetting('user.signup.inviteonly') != 0) && !empty($code) && !empty($email)) { //redirect
            $redirector->gotoUrl($hostType . $_SERVER['HTTP_HOST'] . $this->view->url(array('module' => 'invite',
                        'controller' => 'signup', 'code' => $code, 'email' => $email), 'default', true));
        } else {
            //redirect
            $redirector->gotoUrl($hostType . $_SERVER['HTTP_HOST'] . $this->view->url(array('module' => 'user',
                        'controller' => 'signup', 'action' => 'index'), 'user_signup', true));
        }
    }

    public function applyCreditStoreAction() {

        // apply credit discount in admin driven stores.
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $credit_code = $this->_getparam('credit_code');
        $cart_store_id = $this->_getparam('store_id');
        $cart_info = json_decode($this->_getparam('cart_info'), true);
        //$ProductInfo = Engine_Api::_()->getDbtable('otherinfo','sitestoreproduct')->getOtherinfo(5);
        if (empty($credit_code) || empty($cart_info)) {
            $this->view->credit_error_msg = $this->view->translate("Please enter valid ".$GLOBALS['credits'].".");
            return;
        }
        // fetch credits of user
        $param['user_id'] = $viewer_id;
        $param['basedon'] = 0;
        $param['count'] = 1;
        $credits = Engine_Api::_()->getDbtable('credits', 'sitecredit')->Credits($param);
        $totalCredits = $credits->credit;

        if ($credit_code > $totalCredits) {
            $this->view->credit_error_msg = $this->view->translate("You have only " . $totalCredits . " ".$GLOBALS['credits']);
            return;
        }
        //create session
        $coupon_session = new Zend_Session_Namespace('sitestoreproduct_cart_coupon');
        if (!empty($coupon_session->sitestoreproductCartCouponDetail)) {
            $coupon_session->sitestoreproductCartCouponDetail = null;
        }
        $sitecreditApplyCredit = Zend_Registry::isRegistered('sitecreditApplyCredit') ? Zend_Registry::get('sitecreditApplyCredit') : null;

        $discount_amount = $total_product_price = $directPayment = 0;
        $Store_subtotal = array();
        $siteadminBasePayment = true;
        if (empty($sitecreditApplyCredit))
            return;
        foreach ($cart_info as $store => $value) {
            $store_id[] = $store;
            $store_info = $cart_info[$store];
            $store_product_ids = array_keys($store_info['product_ids']); // Have Product Id's, which are in cart for respective store.
            $store_product_ids_price = $store_info['product_ids']; // Have Product Id's with price, which are in cart for respective store.
            $product_count = $store_info['qty']; // Total cart quantity for respective store.
            $store_products_sub_total = $store_info['sub_total']; // Total cart sub-total for respective store.

            foreach ($store_product_ids_price as $product_id => $product_info) {
                $Store_subtotal[$store]+=$product_info['sub_total'];
                $total_product_price += $product_info['sub_total'];
            }
        }

        foreach ($Store_subtotal as $store => $subtotal) {
            $subtotal_per[$store] = ($subtotal / $total_product_price) * 100;
        }
        // check store - admin driven or not
        $isAdminDrivenStore = Engine_Api::_()->getApi('settings', 'core')->getSetting('is.sitestore.admin.driven', 0);
        if (empty($isAdminDrivenStore)) {
            $this->view->credit_error_msg = $this->view->translate(ucfirst($GLOBALS['credits']). "can be used only for admin driven stores");
            return;
        }
        //check store module is enabled or not in credit plugin settings
        $CreditModuleTable = Engine_Api::_()->getDbtable('modules', 'sitecredit');
        $select = $CreditModuleTable->select()->where('name = ?', 'sitestore')->where('flag = ?', 'product');
        $creditModuleAllow = $CreditModuleTable->fetchRow($select);
        if (empty($creditModuleAllow->integrated)) {
            $this->view->credit_error_msg = $this->view->translate(ucfirst($GLOBALS['credits'])." are not allowed to use for store checkout");
            return;
        }
//check for minimium checkout limit
        $minimum_checkout_total = $creditModuleAllow->minimum_checkout_total;
        if (!empty($minimum_checkout_total)) {
            if ($minimum_checkout_total > $total_product_price) {
                $this->view->credit_error_msg = $this->view->translate("Checkout total should be greater than " . $minimum_checkout_total . " for using ".$GLOBALS['credits']);
                return;
            }
        }

        $creditValue = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.value', 0);
        if (empty($creditValue)) {
            $this->view->credit_error_msg = $this->view->translate("No valid ".$GLOBALS['credits']." are set for ".$GLOBALS['credit']." value. Please contact site admin.");
            return;
        }

        $creditRedemptionPrefrence = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.store.credit.redemption', 'all_store');
        if ($creditRedemptionPrefrence == 'all_store') {

            $validPercentage = $creditModuleAllow->percentage_checkout;

            if (empty($validPercentage)) {
                $this->view->credit_error_msg = $this->view->translate("No valid percentage is set for checkout limit. Please contact site admin.");
                return;
            }

            $maxAmountDeduction = ( ($total_product_price * $validPercentage ) / 100 );
            $maxCredits = $creditValue * $maxAmountDeduction;

            if ($credit_code > $maxCredits) {
                $this->view->credit_error_msg = $this->view->translate("You can use only upto " . @round($maxCredits) . " ".$GLOBALS['credits']);
                return;
            }

            foreach ($subtotal_per as $store => $value) {
                $store_credits = ($credit_code * $value) / 100;
                $store_credit[$store] = @round(($store_credits / $creditValue) * $creditValue);
            }

            foreach ($store_credit as $store => $value) {
                $amountToDeduct[$store] = ($value) / $creditValue;
            }

            $ToalamountToDeduct = ($credit_code / $creditValue);
            $ToalamountToDeduct = ($credit_code / $creditValue);

            $discount_amount = @round($ToalamountToDeduct, 2);

            $credit_details_array = array('credit_points' => $credit_code, 'credit_amount' => $discount_amount, 'store_id' => $store_id, 'Store_subtotal' => $Store_subtotal, 'Store_credit' => $store_credit, 'store_deduct' => $amountToDeduct);
        } else if ($creditRedemptionPrefrence == 'store_wise') {

            $session = new Zend_Session_Namespace('sitestoreproduct_cart_credit');

            $creditDetail = unserialize($session->sitestoreproductCartCreditDetail);

            $storeinfo = Engine_Api::_()->getItem('sitestore_store', $cart_store_id);

            if (empty($storeinfo->credit_limit)) {
                $this->view->credit_error_msg = $this->view->translate(ucfirst($GLOBALS['credit'])." redemption not allowed for this store.");
                return;
            }
            $validPercentage = $storeinfo->credit_limit;
            $maxAmountDeduction = ( ($Store_subtotal[$cart_store_id] * $validPercentage ) / 100 );
            $maxCredits = $creditValue * $maxAmountDeduction;
            if ($credit_code > $maxCredits) {
                $this->view->credit_error_msg = $this->view->translate("You can use only upto " . @round($maxCredits) . " ".$GLOBALS['credits']);
                return;
            }
            $store_credit = $creditDetail['Store_credit'];
            $store_credit[$cart_store_id] = $credit_code;
            $amountToDeduct = $creditDetail['store_deduct'];
            $amountToDeduct[$cart_store_id] = @round(($credit_code / $creditValue), 2);
            $ToalamountToDeduct = 0;
            foreach ($store_id as $key => $value) {
                $ToalamountToDeduct += $amountToDeduct[$value];
            }
            $credit_code = 0;
            foreach ($store_credit as $key => $value) {
                $credit_code += $value;
            }

            $discount_amount = @round($ToalamountToDeduct, 2);
            $credit_details_array = array('credit_points' => $credit_code, 'credit_amount' => $discount_amount, 'store_id' => $store_id, 'Store_subtotal' => $Store_subtotal, 'Store_credit' => $store_credit, 'store_deduct' => $amountToDeduct);
        } else if ($creditRedemptionPrefrence == 'product_wise') {

            $maxCredits = 0;
            $maxAmountDeduction = 0;
            foreach ($cart_info[$cart_store_id]['product_ids'] as $key => $value) {

                $ProductInfo = Engine_Api::_()->getDbtable('otherinfo', 'sitestoreproduct')->getOtherinfo($key);
                if (empty($ProductInfo->credit_handling_type))
                    continue;
                $limit = $ProductInfo->credit_limit;
                $maxAmountDeduction+=($cart_info[$cart_store_id]['product_ids'][$key]['sub_total'] * $limit) / 100;
            }
            $maxCredits = @round($creditValue * $maxAmountDeduction);

            if ($credit_code > $maxCredits) {
                $this->view->credit_error_msg = $this->view->translate("You can use only upto " . @round($maxCredits) . " ".$GLOBALS['credits']);
                return;
            }

            $session = new Zend_Session_Namespace('sitestoreproduct_cart_credit');

            $creditDetail = unserialize($session->sitestoreproductCartCreditDetail);

            $store_credit = $creditDetail['Store_credit'];
            $store_credit[$cart_store_id] = $credit_code;
            $amountToDeduct = $creditDetail['store_deduct'];
            $amountToDeduct[$cart_store_id] = @round(($credit_code / $creditValue), 2);
            $ToalamountToDeduct = 0;
            foreach ($store_id as $key => $value) {
                $ToalamountToDeduct += $amountToDeduct[$value];
            }
            $credit_code = 0;
            foreach ($store_credit as $key => $value) {
                $credit_code += $value;
            }

            $discount_amount = @round($ToalamountToDeduct, 2);
            $credit_details_array = array('credit_points' => $credit_code, 'credit_amount' => $discount_amount, 'store_id' => $store_id, 'Store_subtotal' => $Store_subtotal, 'Store_credit' => $store_credit, 'store_deduct' => $amountToDeduct);
        }
        $session = new Zend_Session_Namespace('sitestoreproduct_cart_credit');
        $session->sitestoreproductCartCreditDetail = serialize($credit_details_array);
        $this->view->cart_credit_applied = true;
    }

    public function applyCreditEventAction() {
        //LOGGED IN USER VALIDATION
        if (!$this->_helper->requireUser()->isValid())
            return;
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $credit_code = $this->_getparam('credit_code');
        $cart_event_id = $this->_getparam('event_id');
        $cart_info = json_decode($this->_getparam('cart_info'), true);
        $totalQuantityArray = $this->_getParam('totalQuantityArray');
        $dynamic_subtotal = $this->_getParam('dynamic_subtotal');

        if (empty($credit_code) || empty($cart_info)) {
            $this->view->credit_error_msg = $this->view->translate("Please enter valid ".$GLOBALS['credits'].".");
            return;
        }

        $param['user_id'] = $viewer_id;
        $param['basedon'] = 0;
        $param['count'] = 1;
        $credits = Engine_Api::_()->getDbtable('credits', 'sitecredit')->Credits($param);
        $totalCredits = $credits->credit;

        if ($credit_code > $totalCredits) {
            $this->view->credit_error_msg = $this->view->translate("You have only " . $totalCredits . " ".$GLOBALS['credits']);
            return;
        }

        $cart_info = $cart_info[$cart_event_id];

        $eventTicketIdsPrice = $cart_info['ticket_ids'];

        $finalTicektIds = $intersectTicektIds = $eventTicketIdsPrice;

        $successMessageTitle = '';
        foreach ($intersectTicektIds as $key => $ticket_id) {
            $ticket = Engine_Api::_()->getItem('siteeventticket_ticket', $ticket_id);

            $validTicketId = false;
            if (!empty($ticket) && $ticket->status != 'hidden' && $ticket->status != 'closed' && date("Y-m-d H:i:s") > $ticket->sell_starttime && date("Y-m-d H:i:s") < $ticket->sell_endtime) {
                $validTicketId = true;
                $successMessageTitle .= "'" . trim($ticket->getTitle()) . "',";
            }

            if (empty($validTicketId)) {
                unset($finalTicektIds[$key]);
            }
        }

        $successMessageTitle = rtrim($successMessageTitle, ',');

        if (Count($finalTicektIds) <= 0) {
            $this->view->credit_error_msg = $this->view->translate("Sorry you cant use ".$GLOBALS['credits']." on this order.");
            return;
        }

        $totalAmount = $totalQuantity = 0;
        foreach ($finalTicektIds as $eventTicketId) {

            $totalQuantity = $totalQuantity + $totalQuantityArray[$eventTicketId];
            $totalAmount = $totalAmount + ($totalQuantityArray[$eventTicketId] * $cart_info['unitPrice'][$eventTicketId]);
        }

        if ($totalQuantity <= 0) {
            $this->view->credit_error_msg = $this->view->translate("Please buy at least one ticket to use ".$GLOBALS['credits'].".");
            return;
        }

        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
        //check event module is enabled or not in cerdit plugin settings
        $CreditModuleTable = Engine_Api::_()->getDbtable('modules', 'sitecredit');
        $select = $CreditModuleTable->select()->where('name = ?', 'siteeventticket')->where('flag = ?', 'product');
        $creditModuleAllow = $CreditModuleTable->fetchRow($select);
        if (empty($creditModuleAllow->integrated)) {
            $this->view->credit_error_msg = $this->view->translate($GLOBALS['credits']." are not allowed to use for event checkout.");
            return;
        }
        //check  minimum checkout limit
        $minimum_checkout_total = $creditModuleAllow->minimum_checkout_total;
        if (!empty($minimum_checkout_total)) {
            if ($minimum_checkout_total > $totalAmount) {
                $this->view->credit_error_msg = $this->view->translate("Checkout total should be greater than " . $minimum_checkout_total . " for using ".$GLOBALS['credits']);
                return;
            }
        }

        $validPercentage = $creditModuleAllow->percentage_checkout;
        if (empty($validPercentage)) {
            $this->view->credit_error_msg = $this->view->translate("No valid percentage is set for checkout limit. Please contact site admin.");
            return;
        }

        $maxAmountDeduction = ( ($totalAmount * $validPercentage ) / 100 );

        $creditValue = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.value', 0);
        if (empty($creditValue)) {
            $this->view->credit_error_msg = $this->view->translate("No valid ".$GLOBALS['credits']." are set for ".$GLOBALS['credit']." value. Please contact site admin.");
            return;
        }

        $maxCredits = $creditValue * $maxAmountDeduction;

        if ($credit_code > $maxCredits) {
            $this->view->credit_error_msg = $this->view->translate("You can use only upto " . $maxCredits . " ".$GLOBALS['credits'].".");
            return;
        }

        $ToalamountToDeduct = ($credit_code / $creditValue);

        $discount_amount = @round($ToalamountToDeduct, 2);
        $credit_details_array = array('credit_points' => $credit_code, 'credit_amount' => $discount_amount, 'event_id' => $cart_event_id, 'event_subtotal' => $totalAmount, 'event_tickets' => $finalTicektIds);
        // create session 
        $session = new Zend_Session_Namespace('siteeventticket_cart_credit');
        $session->siteeventticketCreditDetail = serialize($credit_details_array);
        $this->view->cart_credit_applied = true;
        $this->view->ticketIds = $credit_details_array;

        if ($totalQuantity) {
            $this->view->credit_success_msg = $this->view->translate("Successful.");
        } else {
            $this->view->credit_error_msg = $this->view->translate("To avail this discount you must to purchase atleast one ticket.");
        }
    }

    public function cancelCreditStoreAction() {
        //destroy session
        if (!$this->_helper->requireUser()->isValid())
            return;
        $store_id = $this->_getparam('store_id');
        $creditRedemptionPrefrence = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.store.credit.redemption', 'all_store');
        if ($creditRedemptionPrefrence == 'all_store') {
            $creditsession = new Zend_Session_Namespace('sitestoreproduct_cart_credit');
            if (!empty($creditsession->sitestoreproductCartCreditDetail)) {
                $creditsession->sitestoreproductCartCreditDetail = null;
            }
        } else {
            $creditsession = new Zend_Session_Namespace('sitestoreproduct_cart_credit');
            if (!empty($creditsession->sitestoreproductCartCreditDetail)) {
                $creditDetail = unserialize($creditsession->sitestoreproductCartCreditDetail);

                $store_deduct = $creditDetail['store_deduct'][$store_id];
                $Store_credit = $creditDetail['Store_credit'][$store_id];
                $creditDetail['credit_points'] = $creditDetail['credit_points'] - $Store_credit;
                $creditDetail['credit_amount'] = $creditDetail['credit_amount'] - $store_deduct;
                unset($creditDetail['store_deduct'][$store_id]);
                unset($creditDetail['Store_credit'][$store_id]);
                $creditsession->sitestoreproductCartCreditDetail = serialize($creditDetail);
            }
        }
        $this->view->cart_credit_unset = true;
    }

    public function cancelCreditEventAction() {

        if (!$this->_helper->requireUser()->isValid())
            return;

        $creditsession = new Zend_Session_Namespace('siteeventticket_cart_credit');
        if (!empty($creditsession->siteeventticketCreditDetail)) {
            $creditsession->siteeventticketCreditDetail = null;
        }
        $this->view->cart_credit_unset = true;
    }

    public function viewActivityAction() {

        if (!$this->_helper->requireUser()->isValid())
            return;
        $this->view->language = Zend_Registry::get('Locale')->getLanguage();
        $viewer = Engine_Api::_()->user()->getViewer();
        $param['user_id'] = $viewer->getIdentity();
        $param['basedon'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.ranking', 0);
        $param['count'] = 1;

        $this->view->rawData = Engine_Api::_()->getDbtable('credits', 'sitecredit')->getCreditByTypeID($param);
    }

    public function printInvoiceAction() {
// generate invoice for credit purchased orders
        if (!$this->_helper->requireUser()->isValid())
            return;
        $this->view->credit_id = $credit_id = $this->_getParam('credit_id', null);
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

        if (empty($credit_id)) {
            $this->view->sitecredit_print_invoice_no_permission = true;
            return;
        }

        $credit_item = Engine_Api::_()->getDbtable('credits', 'sitecredit')->fetchRow(array('credit_id = ?' => $credit_id));
        $this->view->orderObj = $orderObj = Engine_Api::_()->getDbtable('orders', 'sitecredit')->fetchRow(array('order_id = ?' => $credit_item->type_id));

        $this->view->currentCurrency = Engine_Api::_()->getDbtable('transactions', 'sitecredit')->fetchRow(array('order_id = ?' => $orderObj->order_id))->currency;

        $this->_helper->layout->setLayout('default-simple');
        $this->view->site_title = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', '');

        if (!empty($orderObj->user_id)) {
            $user_table = Engine_Api::_()->getDbtable('users', 'user');
            $select = $user_table->select()->from($user_table->info('name'), array("email", "displayname"))->where('user_id =?', $orderObj->user_id);
            $this->view->user_detail = $user_table->fetchRow($select);
        }

        $gateway = Engine_Api::_()->getDbtable('gateways', 'payment')->fetchRow(array('gateway_id = ?' => $orderObj->gateway_id));
        $this->view->gatewayTitle = $gateway->title;
        // FETCH SITE LOGO OR TITLE
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $select = new Zend_Db_Select($db);
        $select->from('engine4_core_pages')->where('name = ?', 'header')->limit(1);

        $info = $select->query()->fetch();
        if (!empty($info)) {
            $page_id = $info['page_id'];

            $select = new Zend_Db_Select($db);
            $select->from('engine4_core_content', array("params"))
                    ->where('page_id = ?', $page_id)
                    ->where("name LIKE '%core.menu-logo%'")
                    ->limit(1);
            $info = $select->query()->fetch();
            $params = json_decode($info['params']);

            if (!empty($params->logo))
                $this->view->logo = $params->logo;
        }
    }

    public function viewDetailAction() {
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('id');

        $this->view->badge = $badge = Engine_Api::_()->getItem('badge', $id);
    }

    public function getallusersAction() {
        // for suggestions of users
        $text = $this->_getParam('search');
        $friend_ids = $this->_getParam('friend_ids', null);
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $limit = $this->_getParam('limit', 40);
        $tableName = Engine_Api::_()->getDbTable('users', 'user');
        try {
            $select = $tableName->select()->where('displayname  LIKE ? ', '%' . $text . '%');

            if (!empty($friend_ids)) {
                $select->where("user_id NOT IN ($friend_ids)");
            }
            $select->order('displayname ASC')->limit($limit);
            $userObjects = $tableName->fetchAll($select);
            $data = array();
            //FETCH RESULTS
            foreach ($userObjects as $users) {
                if ($viewer_id == $users->user_id)
                    continue;
                $data[] = array(
                    'id' => $users->user_id,
                    'label' => $users->getTitle(),
                    'photo' => $this->view->itemPhoto($users, 'thumb.icon'),
                );
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $this->_helper->json($data);
    }

}
