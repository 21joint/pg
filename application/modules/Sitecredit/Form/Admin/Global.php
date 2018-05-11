<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Form_Admin_Global extends Engine_Form {
  public $_SHOWELEMENTSBEFOREACTIVATE = array(
    "submit_lsetting", "environment_mode"
    );
  public function init() {
    $productType = 'sitecredit';
    $coreSettings = Engine_Api::_()->getApi('settings', 'core');
    $this  ->setTitle('General Settings')
    ->setDescription('These settings affect all users in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        // ELEMENT FOR LICENSE KEY
    $this->addElement('Text', $productType . '_lsettings', array(
      'label' => 'Enter License key',
      'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
      'value' => $coreSettings->getSetting($productType . '.lsettings'),
      ));

    if (APPLICATION_ENV == 'production') {
      $this->addElement('Checkbox', 'environment_mode', array(
        'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few stores of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
        'description' => 'System Mode',
        'value' => 1,
        ));
    } else {
      $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
    }

    $this->addElement('Button', 'submit_lsetting', array(
      'label' => 'Activate Your Plugin Now',
      'type' => 'submit',
      'ignore' => true
      ));      
    $this->addElement('Text', 'credit_manifestUrlP', array(
      'label' => 'Credits URL Alternate Text for "credits"',
      'allowEmpty' => false,
      'required' => true,
      'description' => 'Please enter the text below which you want to display in place of "credits" in the URLs of this plugin.',
      'value' => $coreSettings->getSetting('credit.manifestUrlP', "credits"),
      ));
    
    $this->addElement('Text', 'credit_alternate', array(
      'label' => 'Alternate Text for "credit"',
      'allowEmpty' => false,
      'required' => true,
      'description' => 'Please enter the text below which you want to display in place of "credit" in the user end of this plugin.',
      'value' => $coreSettings->getSetting('credit.alternate', "credit"),
      ));

    $currentCurrency = $coreSettings->getSetting('payment.currency', 'USD');
      $creditValueDiscription ='What will be the credit value for ';//+$currentCurrency+'1?';
      $priceStr = Zend_Registry::get('Zend_View')->locale()->toCurrency(1,$currentCurrency);
      $creditValueDiscription .= $priceStr ;
      $creditValueDiscription.='? [For eg. $1.00 = 100 credits]';
      
      $this->addElement('Text','sitecredit_value',array(
        'label'=>'Credit Value',
        'allowEmpty'=>false,
        'required' =>true,
        'validators'=>array(
          array('NotEmpty', true),
          array('Int', true),
          new Engine_Validate_AtLeast(1),
          ),
        'description'=> $creditValueDiscription,
        'value'=>$coreSettings->getSetting('sitecredit.value', "100"),
        ));


      $paypalGAteway = Engine_Api::_()->getDbtable('gateways', 'payment')->fetchRow(array('title = ?' => "PayPal"));
      $sitecredit_allowed_payment_gateway=array();
      if(!empty($paypalGAteway))
        $sitecredit_allowed_payment_gateway['PayPal'] = 'PayPal';

      $stripeConnect=$coreSettings->getSetting('sitegateway.stripeconnect',0);
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
      $normalGateway = array_merge($sitecredit_allowed_payment_gateway, $normalGateway);
      if($normalGateway){

        try { $normalGateways = Zend_Json_Decoder::decode($coreSettings->getSetting('sitecredit.allowed.payment.gateway', Zend_Json_Encoder    ::encode($normalGateway)));
      } catch (Exception $ex) {
        $normalGateways = $coreSettings->getSetting('sitecredit.allowed.payment.gateway', Zend_Json_Encoder::encode($normalGateway));
      }

      $this->addElement('MultiCheckbox', 'sitecredit_allowed_payment_gateway', array(
        'label' => 'Payment Gateways',
        'description' => 'Select the payment gateway to be available for purchasing credits.',
        'multiOptions' => $normalGateway,
        'value' => $normalGateways,
        ));
    } else {
      $this->addError('No Payment gateway is enabled by you. Please enable payment gateway if you want to allow users to buy credits');
    }


       //SETTINGS FOR ENABLED AND DISABLED "AFFILIATE LINK" 

    $this->addElement('Radio', 'sitecredit_allow_affiliate_link', array(
      'label' => 'Referral Signups',
      'description' => 'Allow users to generate affiliate link for signups?',
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No'
        ),
      'value' => $coreSettings->getSetting('sitecredit.allow.affiliate.link', 1)
      ));

       // find allowed modules.
    $this->addElement('Radio', 'sitecredit_validity', array(
      'label' => 'Credits Validity',
      'description' => 'Set the credits validity for users. Users credit will reset to ‘0’ after the specified time period.[Note: This validity will be applicable from new validity lifecycle of user credits.
      LifeTime validity means credits are valid for next 25 years and after that it will get reset.]' ,
      'multiOptions' => array(
        0 => 'Lifetime',
        1 => 'Specific Time Interval',
        ),
      'onchange'=>'creditValidityChange(this.value)',
      'value'=> $coreSettings->getSetting('sitecredit.validity',0),
      ));


    $this->addElement('Select', 'sitecredit_year_validity', array(
      'description' => 'Years',

      'multiOptions' => array(0=>'0',1 => '1', 2=>'2', 3=>'3',4=>'4',5=>'5',6=>'6',7=>'7',8=>'8',9=>'9',10=>'10'),
      'value'=> $coreSettings->getSetting('sitecredit.year.validity',0),
      ));


    $this->addElement('Select', 'sitecredit_month_validity', array(
      'description' => 'Months',
      'multiOptions' => array(1 => '1', 2=>'2', 3=>'3',4=>'4',5=>'5',6=>'6',7=>'7',8=>'8',9=>'9',10=>'10',11=>'11',
        12 => '12'),
      'value'=> $coreSettings->getSetting('sitecredit.month.validity',1),
      ));

    if(Engine_Api::_()->getDbtable( "modules" , "core" )->isModuleEnabled("sitestore")){

      $this->addElement('Select', 'sitecredit_store_credit_redemption', array(
      'label' => 'Credits Redemption Method in Store / Marketplace plugin',
      'description' => 'On what basis do you want to allow credit redemption on checkout process of Store / Marketplace plugin',
      'multiOptions' => array('all_store' => 'All Stores','store_wise' =>'Store Wise', 'product_wise'=>'Product Wise'),
      'value'=> $coreSettings->getSetting('sitecredit.store.credit.redemption','all_store'),
      ));
    }
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true
      ));

  }

}


