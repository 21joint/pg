<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Widget_BuyCreditsController extends Engine_Content_Widget_Abstract
{
	  public function indexAction() {
		
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer)
            return $this->setNoRender();
   
        if ( !Engine_Api::_()->authorization()->isAllowed('sitecredit_credit', $viewer, 'buy'))
            return $this->setNoRender();
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $currentCurrency = $settings->getSetting('payment.currency', 'USD');

        $credit_point=$settings->getSetting('sitecredit.value',0);
        
        if(empty($credit_point))
         return $this->setNoRender();   
          
        $this->view->costOnecredit=$costOnecredit=1/$credit_point;

        $offerArray=array();
        //get valid offers
        $result = Engine_Api::_()->getDbtable('offers', 'sitecredit')->getValidoffers();
    
        $sitecreditBuyCredit = Zend_Registry::isRegistered('sitecreditBuyCredit') ? Zend_Registry::get('sitecreditBuyCredit') : null;
        if (empty($sitecreditBuyCredit))
            return $this->setNoRender();
    
        foreach ($result as $data => $value) {
            $priceStr = Zend_Registry::get('Zend_View')->locale()->toCurrency($value->value,$currentCurrency); 
            $offerArray[$value->offer_id]= " ".$value->credit_point." ".ucfirst($GLOBALS['credits'])." in ".$priceStr;
            $offerValueArray[$value->offer_id]["credit_point"]=$value->credit_point;
            $offerValueArray[$value->offer_id]["price"]=$priceStr;
        }
    
        $this->view->offerArray=$offerArray;
        $this->view->offerValueArray=$offerValueArray;
    
        try { 
            $normalGateways = Zend_Json_Decoder::decode($settings->getSetting('sitecredit.allowed.payment.gateway'));
        }catch (Exception $ex) {
            $normalGateways = $settings->getSetting('sitecredit.allowed.payment.gateway');
        }

        $this->view->nogateway=false; 
        if (empty($normalGateways)) {
            $this->view->nogateway=true;
        }else  {
      
            $default_payment_gateway=array();
            $stripeConnect=$settings->getSetting('sitegateway.stripeconnect',0);
            $paymentGatewayTable=Engine_Api::_()->getDbtable('gateways','payment');

            $select=$paymentGatewayTable->select()->where('plugin LIKE "Payment_Plugin_Gateway_PayPal"')->where('enabled = 1');
            $result_gateway_enabled=$paymentGatewayTable->fetchRow($select);
            if (!empty($result_gateway_enabled)) {
                $default_payment_gateway[$result_gateway_enabled->title]=$result_gateway_enabled->title;
            }
            $normalGateway = array();
            $isSitegatewayEnabled = Engine_Api::_()->hasModuleBootstrap('sitegateway');
            if ($isSitegatewayEnabled) {
                $getEnabledGateways = Engine_Api::_()->sitegateway()->getAdditionalEnabledGateways(array('pluginLike' => 'Sitegateway_Plugin_Gateway_'));
                foreach ($getEnabledGateways as $getEnabledGateway) {
                    $gatewayKey = strtolower($getEnabledGateway->title);
                    if (!($getEnabledGateway->plugin == 'Sitegateway_Plugin_Gateway_PayPalAdaptive' || $getEnabledGateway->plugin == 'Sitegateway_Plugin_Gateway_MangoPay' ||($getEnabledGateway->plugin == 'Sitegateway_Plugin_Gateway_Stripe' && $stripeConnect))) {
                        $normalGateway[$getEnabledGateway->title] = $getEnabledGateway->title;
                    }
                }
            }
            $normalGateway = array_merge($default_payment_gateway, $normalGateway);
            $gatewaysEnabled=array_intersect ($normalGateways,$normalGateway);
            if (empty($gatewaysEnabled)) {
                $this->view->nogateway=true;
            }else {
                $this->view->gatewaysEnabled=$gatewaysEnabled;
                $table=Engine_Api::_()->getDbtable('gateways', 'payment');
                $select=$table->select()->where("enabled=1");
                $this->view->EnabledGateways=$table->fetchAll($select); 
            }
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $params = $request->getParams() ;
        if( $request->isPost() && (!empty($params['credit_point_2']) || !empty($params['credit_offer']))) {
            $order_id;
            $gateway_id;
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                $param=$_POST;
                $param['costonecredit']=$costOnecredit;
                $this->view->gateway_id=$gateway_id=$_POST['payment_method'];
                $this->view->order_id=$order_id=Engine_Api::_()->getDbtable('orders','sitecredit')->getOrderid($param);
                $this->_session = new Zend_Session_Namespace('Payment_Sitecredit');            
                $this->_session->user_order_id = $order_id;
                $db->commit();
            }catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }

            $hostType = "http://";
            if ((!empty($_SERVER["HTTPS"])) && (@$_SERVER["HTTPS"] == "on")) {
                $hostType = "https://";
            }
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->gotoUrl($hostType . $_SERVER['HTTP_HOST'] .  $this->view->url(array('action' => 'process','order_id'=>$order_id,'gateway_id'=>$gateway_id), 'credit_payment', true));
        }

    }

}
