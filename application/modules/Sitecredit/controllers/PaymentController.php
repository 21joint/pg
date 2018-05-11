<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: PaymentController.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 
class Sitecredit_PaymentController extends Core_Controller_Action_Standard { 
 
    protected $_user;
    protected $_session;
    protected $_order;
    protected $_gateway;
    protected $_user_order;
    protected $_success;

    public function init() {

        // Get user and session
        $this->_session = new Zend_Session_Namespace('Payment_Sitecredit');

        // Check viewer and user
        if (!$this->_user_order) {
            if (!empty($this->_session->user_order_id)) {
                $this->_user_order = Engine_Api::_()->getItem('sitecredit_order', $this->_session->user_order_id);
            }
        }
    } 
    public function processAction() {

        if (!$this->_user_order) {
            $this->_session->unsetAll();
            return $this->_helper->redirector->gotoRoute(array(), 'credit_general', true);
        }


        $plugin = 'Payment_Plugin_Gateway_PayPal';
        if (Engine_Api::_()->hasModuleBootstrap('sitegateway')) {
            $gatewayObject = Engine_Api::_()->getItem('payment_gateway', $this->_user_order->gateway_id);
            if (!empty($gatewayObject)) {
                $plugin = $gatewayObject->plugin;
            }
        }

        $parent_order_id = $this->_session->user_order_id;

        // Process
        $ordersTable = Engine_Api::_()->getDbtable('orders', 'payment');
        if (!empty($this->_session->order_id)) {
            $previousOrder = $ordersTable->find($this->_session->order_id)->current();
            if ($previousOrder && $previousOrder->state == 'pending') {
                $previousOrder->state = 'incomplete';
                $previousOrder->save();
            }
        }
        $sitecreditPayment = Zend_Registry::isRegistered('sitecreditPayment') ? Zend_Registry::get('sitecreditPayment') : null;
        if (empty($sitecreditPayment))
            return;
        $sourceType = 'sitecredit_order';
        $sourceId = $parent_order_id;
        $gateway_id = $this->_user_order->gateway_id;

        // Create order
        $ordersTable->insert(array(
            'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
            'gateway_id' => $gateway_id,
            'state' => 'pending',
            'creation_date' => new Zend_Db_Expr('NOW()'),
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ));

        $this->_session->order_id = $order_id = $ordersTable->getAdapter()->lastInsertId();

        $gateway = Engine_Api::_()->getItem('sitecredit_gateway', $gateway_id);

        // Get gateway plugin
        $this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
        $plugin = $gateway->getPlugin();

        // Prepare host info
        $schema = 'http://';
        if (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) {
            $schema = 'https://';
        }
        $host = $_SERVER['HTTP_HOST'];

        // Prepare transaction
        $params = array();
//    $params['language'] = $this->_user->language;
        $params['vendor_order_id'] = $order_id;

        $params['return_url'] = $schema . $host
                . $this->view->url(array('action' => 'return', 'controller' => 'payment', 'module' => 'sitecredit'), 'default')
                . '?order_id=' . $order_id
                . '&gateway_id=' . $gateway_id
                . '&state=' . 'return';
        $params['cancel_url'] = $schema . $host
                . $this->view->url(array('action' => 'return', 'controller' => 'payment', 'module' => 'sitecredit'), 'default')
                . '?order_id=' . $order_id
                . '&gateway_id=' . $gateway_id
                . '&state=' . 'cancel';
        $params['ipn_url'] = $schema . $host
                . $this->view->url(array('action' => 'index', 'controller' => 'ipn', 'module' => 'payment'), 'default')
                . '?order_id=' . $order_id
                . '&gateway_id=' . $gateway_id;

        $params['source_type'] = $sourceType; 
 
        $isAllowedAmount = true;
        //IN CASE OF MOLLIE GATEWAY THERE IS A MINIMUM AMOUNT LIMIT
        if(property_exists($gatewayPlugin, '_minimumAllowedAmount') && $this->_user_order->grand_total < $gatewayPlugin->getAllowedAmount()) {
            $isAllowedAmount = false;
            $this->view->minimumAllowedAmount = $gatewayPlugin->getAllowedAmount();
        }
        $this->view->isAllowedAmount = $isAllowedAmount; 
        if($isAllowedAmount){
            // Process transaction
            $transaction = $plugin->createUserOrderTransaction($parent_order_id, $params, $this->_user);

            $this->view->transactionUrl = $transactionUrl = $gatewayPlugin->getGatewayUrl();
            $this->view->transactionMethod = $transactionMethod = $gatewayPlugin->getGatewayMethod();
            $this->view->transactionData = $transactionData = $transaction->getData();

            unset($this->_session->user_order_id);
            $this->view->transactionMethod = $transactionMethod;
            // Handle redirection
            if ($transactionMethod == 'GET' && !Engine_Api::_()->seaocore()->isSiteMobileModeEnabled()) {
                $transactionUrl .= '?' . http_build_query($transactionData);
                return $this->_helper->redirector->gotoUrl($transactionUrl, array('prependBase' => false));
            }
        } 
    }
 

  public function returnAction() { 

        if (!($orderId = $this->_getParam('order_id', $this->_session->order_id)) ||
                !($order = Engine_Api::_()->getItem('payment_order', $orderId)) ||
                ($order->source_type != 'sitecredit_order') ||
                !($user_order = $order->getSource()) ||
                !($gateway = Engine_Api::_()->getItem('sitecredit_gateway', $order->gateway_id))) {
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }

        // Get gateway plugin

        $gateway_id = $this->_getParam('gateway_id');
        if (empty($gateway_id))
            $gateway = Engine_Api::_()->getItem('sitecredit_gateway', $order->gateway_id);
        else
            $gateway = Engine_Api::_()->getItem('sitecredit_gateway', $gateway_id);


        if (!$gateway)
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);

        // Get gateway plugin
        $plugin = $gateway->getPlugin();

        unset($this->_session->errorMessage);

        try {
            $params = $this->_getAllParams();
            $status = $plugin->onUserOrderTransactionReturn($order, $params);
        } catch (Payment_Model_Exception $e) {
            $status = 'failure';
            $this->_session->errorMessage = $e->getMessage();
        }
        $this->_success->succes_id = $user_order->order_id;
        print_r($this->_success->succes_id);

        return $this->_finishPayment($status);
    }

    protected function _finishPayment($state = 'active') {
        $success_id = $this->_success->succes_id;

        // Clear session
        $errorMessage = $this->_session->errorMessage;
        $this->_session->unsetAll();
        $this->_session->errorMessage = $errorMessage;
        // Redirect

        return $this->_helper->redirector->gotoRoute(array('action' => 'finish', 'state' => $state, 'success_id' => $success_id));
    }

    public function finishAction() {
        //do here
        $session = new Zend_Session_Namespace('Sitecredit_Order_Payment_Detail');

        if (!empty($session->sitecreditOrderPaymentDetail))
            $session->sitecreditOrderPaymentDetail = '';

        $paymentDetail = array('success_id' => $this->_getParam('success_id'), 'state' => $this->_getParam('state'), 'errorMessage' => $this->_session->errorMessage);

        $session->sitecreditOrderPaymentDetail = $paymentDetail;

        return $this->_helper->redirector->gotoRoute(array('action' => 'success'), 'credit_general', false);
    }

    public function detailTransactionAction() {
        $transaction_id = $this->_getParam('transaction_id', null);

        if (empty($transaction_id)) {
            return $this->_forward('notfound', 'error', 'core');
        }

        $transaction = Engine_Api::_()->getItem('sitestoreproduct_transaction', $transaction_id);
        $gateway = Engine_Api::_()->getItem('payment_gateway', $transaction->gateway_id);

        $link = null;
        if ($this->_getParam('show-parent')) {
            if (!empty($transaction->gateway_parent_transaction_id)) {
                $link = $gateway->getPlugin()->getTransactionDetailLink($transaction->gateway_parent_transaction_id);
            }
        } else {
            if (!empty($transaction->gateway_transaction_id)) {
                $link = $gateway->getPlugin()->getTransactionDetailLink($transaction->gateway_transaction_id);
            }
        }

        if ($link) {
            return $this->_helper->redirector->gotoUrl($link, array('prependBase' => false));
        } else {
            die();
        }
    }

}
