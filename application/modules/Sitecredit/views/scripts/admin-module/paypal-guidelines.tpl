<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitecoupon
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: guidelines.tpl 2010-11-18 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>
  <?php echo $this->translate('Credits, Reward Points and Virtual Currency - User Engagement Plugin') ?>
</h2>
<?php if (count($this->navigation)): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>

<div class='clear'>
  <a href="<?php echo $this->url(array('module' => 'sitecredit', 'controller' => 'module', 'action' => 'index'), 'admin_default', true) ?>" class="buttonlink" style="background-image:url(<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/back.png);padding-left:23px;"><?php echo $this->translate("Back to Module Integration Settings"); ?></a>

  <div class='' style="margin-top:15px;">
    <h3><?php echo $this->translate("Guidelines for minor modification for credit redemption to work with PayPal enabled on your website.") ?></h3>
    <p><?php echo $this->translate("Please follow the steps below to do the minor modifications required to make credit redemption work with PayPal payment gateway enabled on your website.<br />Note: You need to do these changes, when you have created recurring Subscription Plans on your website. ");?>
    <br /><br />
		<div class='tip'>
			<span>
				<?php echo $this->translate('NOTE: Whenever you will upgrade SocialEngine Core for your site, these changes will be overwritten and they will then have to be re-applied in the respective files as mentioned below.'); ?>
			</span>
		</div>
    </p>
    <div class="admin_seaocore_guidelines_wrapper">
      <ul class="admin_seaocore_guidelines">
        <li>	
          <div class="steps">
            <a href="javascript:void(0);" onClick="guideline_show('step-1');"><?php echo $this->translate("Step 1") ?></a>
            <div id="step-1" style='display: none;'>
              <p>
                <?php echo $this->translate("a)  Open the file: '/application/modules/Payment/Plugin/Gateway/PayPal.php'."); ?><br />
                <?php echo $this->translate('b) Find function in file :  <b>public function onSubscriptionTransactionReturn(
      Payment_Model_Order $order, array $params = array())</b>'); ?><br /><br />
              </p>
            </div>
          </div>
        </li>
        <li>	
          <div class="steps">
            <a href="javascript:void(0);" onClick="guideline_show('step-2');"><?php echo $this->translate("Step 2") ?></a>              
            <div id="step-2" style='display: none;'>
							<p>
								<?php echo $this->translate("Now, replace the above function with the new block of code below: <br /> <br />"); ?>
                <br/>
                <button onclick="copyToClipboard('modified_code')" ><?php echo $this->translate("Copy Code");?></button>
                <br/>
      <pre> <div name="modified_code" id="modified_code" >public function onSubscriptionTransactionReturn(
      Payment_Model_Order $order, array $params = array())
  {
    // Check that gateways match
    if( $order->gateway_id != $this->_gatewayInfo->gateway_id ) {
      throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }
    
    // Get related info
    $user = $order->getUser();
    $subscription = $order->getSource();
    $package = $subscription->getPackage();

    if (Engine_Api::_()->getDbtable( "modules" , "core" )->isModuleEnabled("sitecoupon") && !empty($params["code"])) {

      $result = Engine_Api::_()->getDbtable("coupons", "sitecoupon")->getCode($params["code"]);
      $discount_value = 0;
      if ($result->discount_type == "price") {
        $discount_value = $result->discount_value;
      } else if ($result->discount_type == "percentage") {
        $discount_value = ($package->price ) * ($result->discount_value / 100);
      }
        $totalValue = $package->price - $discount_value;
        $package->price = $totalValue;
    }

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitecredit')) {
        //SESSION SET OF CREDITS.
        $creditSession = new Zend_Session_Namespace('payment_subscription_credit');

        if (!empty($creditSession->paymentSubscriptionCreditDetail)) {
                $creditDetail = unserialize($creditSession->paymentSubscriptionCreditDetail);
                $package->price = $package->price - $creditDetail['credit_amount'];
        }
    }

    
    // Check subscription state
    if( $subscription->status == 'active' ||
        $subscription->status == 'trial') {
      return 'active';
    } else if( $subscription->status == 'pending' ) {
      return 'pending';
    }

    // Check for cancel state - the user cancelled the transaction
    if( $params['state'] == 'cancel' ) {
      // Cancel order and subscription?
      $order->onCancel();
      $subscription->onPaymentFailure();
      // Error
      throw new Payment_Model_Exception('Your payment has been cancelled and ' .
          'not been charged. If this is not correct, please try again later.');
    }
    
    // Check params
    if( empty($params['token']) ) {
      // Cancel order and subscription?
      $order->onFailure();
      $subscription->onPaymentFailure();
      // This is a sanity error and cannot produce information a user could use
      // to correct the problem.
      throw new Payment_Model_Exception('There was an error processing your ' .
          'transaction. Please try again later.');
    }

    // Get details
    try {
      $data = $this->getService()->detailExpressCheckout($params['token']);
    } catch( Exception $e ) {
      // Cancel order and subscription?
      $order->onFailure();
      $subscription->onPaymentFailure();
      // This is a sanity error and cannot produce information a user could use
      // to correct the problem.
      throw new Payment_Model_Exception('There was an error processing your ' .
          'transaction. Please try again later.');
    }
    
    // Let's log it
    $this->getGateway()->getLog()->log('ExpressCheckoutDetail: '
        . print_r($data, true), Zend_Log::INFO);


    // One-time
    if( $package->isOneTime() ) {

      // Do payment
      try {
        $rdata = $this->getService()->doExpressCheckoutPayment($params['token'],
              $params['PayerID'], array(
          'PAYMENTACTION' => 'Sale',
          'AMT' => $data['AMT'],
          'CURRENCYCODE' => $this->getGateway()->getCurrency(),
        ));
      } catch( Exception $e ) {
        // Log the error
        $this->getGateway()->getLog()->log('DoExpressCheckoutPaymentError: '
            . $e->__toString(), Zend_Log::ERR);
        
        // Cancel order and subscription?
        $order->onFailure();
        $subscription->onPaymentFailure();
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
            'transaction. Please try again later.');
      }

      // Let's log it
      $this->getGateway()->getLog()->log('DoExpressCheckoutPayment: '
          . print_r($rdata, true), Zend_Log::INFO);

      // Get payment state
      $paymentStatus = null;
      $orderStatus = null;
      switch( strtolower($rdata['PAYMENTINFO'][0]['PAYMENTSTATUS']) ) {
        case 'created':
        case 'pending':
          $paymentStatus = 'pending';
          $orderStatus = 'complete';
          break;

        case 'completed':
        case 'processed':
        case 'canceled_reversal': // Probably doesn't apply
          $paymentStatus = 'okay';
          $orderStatus = 'complete';
          break;

        case 'denied':
        case 'failed':
        case 'voided': // Probably doesn't apply
        case 'reversed': // Probably doesn't apply
        case 'refunded': // Probably doesn't apply
        case 'expired':  // Probably doesn't apply
        default: // No idea what's going on here
          $paymentStatus = 'failed';
          $orderStatus = 'failed'; // This should probably be 'failed'
          break;
      }
      
      // Update order with profile info and complete status?
      $order->state = $orderStatus;
      $order->gateway_transaction_id = $rdata['PAYMENTINFO'][0]['TRANSACTIONID'];
      $order->save();

      // Insert transaction
      $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'payment');
      $transactionsTable->insert(array(
        'user_id' => $order->user_id,
        'gateway_id' => $this->_gatewayInfo->gateway_id,
        'timestamp' => new Zend_Db_Expr('NOW()'),
        'order_id' => $order->order_id,
        'type' => 'payment',
        'state' => $paymentStatus,
        'gateway_transaction_id' => $rdata['PAYMENTINFO'][0]['TRANSACTIONID'],
        'amount' => $rdata['AMT'], // @todo use this or gross (-fee)?
        'currency' => $rdata['PAYMENTINFO'][0]['CURRENCYCODE'],
      ));

      // Get benefit setting
      $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'payment')
          ->getBenefitStatus($user);
          
      // Check payment status
      if( $paymentStatus == 'okay' ||
          ($paymentStatus == 'pending' && $giveBenefit) ) {

        // Update subscription info
        $subscription->gateway_id = $this->_gatewayInfo->gateway_id;
        $subscription->gateway_profile_id = $rdata['PAYMENTINFO'][0]['TRANSACTIONID'];

        // Payment success
        $subscription->onPaymentSuccess();

        // send notification
        if( $subscription->didStatusChange() ) {
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_active', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
          ));
        }
        
        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitecredit')) {
          //SESSION SET OF CREDITS.
          $creditSession = new Zend_Session_Namespace('payment_subscription_credit');

          if (!empty($creditSession->paymentSubscriptionCreditDetail)) {
              $creditDetail = unserialize($creditSession->paymentSubscriptionCreditDetail);
              try {

                if($creditDetail['credit_points']){
                  $param['type_id']=$order->order_id;
                  $param['credit_point']=-$creditDetail['credit_points'];
                  $param['type']='subscription';
                  $param['user_id']=$user->getIdentity();
                  $param['reason']='used credits for subscription';
                  $credit_table = Engine_Api::_()->getDbtable('credits','sitecredit');    
                  $credit_table->insertCredit($param);
                }

              }catch (Exception $e) {
                throw $e;
              }
              $creditSession->paymentSubscriptionCreditDetail = null;
          }
        }



        return 'active';
      }
      else if( $paymentStatus == 'pending' ) {

        // Update subscription info
        $subscription->gateway_id = $this->_gatewayInfo->gateway_id;
        $subscription->gateway_profile_id = $rdata['PAYMENTINFO'][0]['TRANSACTIONID'];

        // Payment pending
        $subscription->onPaymentPending();

        // send notification
        if( $subscription->didStatusChange() ) {
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_pending', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
          ));
        }
        
        return 'pending';
      }
      else if( $paymentStatus == 'failed' ) {
        // Cancel order and subscription?
        $order->onFailure();
        $subscription->onPaymentFailure();
        // Payment failed
        throw new Payment_Model_Exception('Your payment could not be ' .
            'completed. Please ensure there are sufficient available funds ' .
            'in your account.');
      }
      else {
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
            'transaction. Please try again later.');
      }
    }

    // Recurring
    else {

      // Check for errors
      if( empty($data) ) {
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
            'transaction. Please try again later.');
      } else if( empty($data['BILLINGAGREEMENTACCEPTEDSTATUS']) ||
          '0' == $data['BILLINGAGREEMENTACCEPTEDSTATUS'] ) {
        // Cancel order and subscription?
        $order->onCancel();
        $subscription->onPaymentFailure();
        // Error
        throw new Payment_Model_Exception('Your payment has been cancelled and ' .
            'not been charged. If this in not correct, please try again later.');
      } else if( !isset($data['PAYMENTREQUESTINFO'][0]['ERRORCODE']) ||
          '0' != $data['PAYMENTREQUESTINFO'][0]['ERRORCODE'] ) {
        // Cancel order and subscription?
        $order->onFailure();
        $subscription->onPaymentFailure();
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
            'transaction. Please try again later.');
      }

      // Create recurring payments profile
      $desc = $package->getPackageDescription();
      if( strlen($desc) > 127 ) {
        $desc = substr($desc, 0, 124) . '...';
      } else if( !$desc || strlen($desc) <= 0 ) {
        $desc = 'N/A';
      }
      if( function_exists('iconv') && strlen($desc) != iconv_strlen($desc) ) {
        // PayPal requires that DESC be single-byte characters
        $desc = @iconv("UTF-8", "ISO-8859-1//TRANSLIT", $desc);
      }
      $rpData = array(
        'TOKEN' => $params['token'],
        'PROFILEREFERENCE' => $order->order_id,
        'PROFILESTARTDATE' => $data['TIMESTAMP'],
        'DESC' => $desc,
        'BILLINGPERIOD' => ucfirst($package->recurrence_type),
        'BILLINGFREQUENCY' => $package->recurrence,
        'INITAMT' => 0,
        'AMT' => $package->price,
        'CURRENCYCODE' => $this->getGateway()->getCurrency(),
      );

      $count = $package->getTotalBillingCycleCount();
      if( $count ) {
        $rpData['TOTALBILLINGCYCLES'] = $count;
      }

      // Create recurring payment profile
      try {
        $rdata = $this->getService()->createRecurringPaymentsProfile($rpData);
      } catch( Exception $e ) {
        // Cancel order and subscription?
        $order->onFailure();
        $subscription->onPaymentFailure();
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
            'transaction. Please try again later.');
      }

      // Let's log it
      $this->getGateway()->getLog()->log('CreateRecurringPaymentsProfile: '
          . print_r($rdata, true), Zend_Log::INFO);

      // Check returned profile id
      if( empty($rdata['PROFILEID']) ) {
        // Cancel order and subscription?
        $order->onFailure();
        $subscription->onPaymentFailure();
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
            'transaction. Please try again later.');
      }
      $profileId = $rdata['PROFILEID'];

      // Update order with profile info and complete status?
      $order->state = 'complete';
      $order->gateway_order_id = $profileId;
      $order->save();

      // Get benefit setting
      $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'payment')
          ->getBenefitStatus($user);

      // Check profile status
      if( $rdata['PROFILESTATUS'] == 'ActiveProfile' ||
          ($rdata['PROFILESTATUS'] == 'PendingProfile' && $giveBenefit) ) {
        // Enable now
        $subscription->gateway_id = $this->_gatewayInfo->gateway_id;
        $subscription->gateway_profile_id = $rdata['PROFILEID'];
        $subscription->onPaymentSuccess();

        // send notification
        if( $subscription->didStatusChange() ) {
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_active', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
          ));
        }


          if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitecredit')) {
          //SESSION SET OF CREDITS.
          $creditSession = new Zend_Session_Namespace('payment_subscription_credit');

          if (!empty($creditSession->paymentSubscriptionCreditDetail)) {
              $creditDetail = unserialize($creditSession->paymentSubscriptionCreditDetail);
              try {

                if($creditDetail['credit_points']){
                  $param['type_id']=$order->order_id;
                  $param['credit_point']=-$creditDetail['credit_points'];
                  $param['type']='subscription';
                  $param['user_id']=$user->getIdentity();
                  $param['reason']='used credits for subscription';
                  $credit_table = Engine_Api::_()->getDbtable('credits','sitecredit');    
                  $credit_table->insertCredit($param);
                }

              }catch (Exception $e) {
                throw $e;
              }
              $creditSession->paymentSubscriptionCreditDetail = null;
          }
        }

        return 'active';

      } else if( $rdata['PROFILESTATUS'] == 'PendingProfile' ) {
        // Enable later
        $subscription->gateway_id = $this->_gatewayInfo->gateway_id;
        $subscription->gateway_profile_id = $rdata['PROFILEID'];
        $subscription->onPaymentPending();

        // send notification
        if( $subscription->didStatusChange() ) {
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_pending', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
          ));
        }

        return 'pending';

      } else {
        // Cancel order and subscription?
        $order->onFailure();
        $subscription->onPaymentFailure();
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
            'transaction. Please try again later.');
      }
    }
  }</div></pre>
								<br /><br />
							</p>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div> 

<script type="text/javascript">
  function guideline_show(id) {
    if($(id).style.display == 'block') {
      $(id).style.display = 'none';
    } else {
      $(id).style.display = 'block';
    }
  }

function copyToClipboard(elementId) {

  // Create a "hidden" input
  var aux = document.createElement("input");

  // Assign it the value of the specified element
  aux.setAttribute("value", document.getElementById(elementId).innerHTML);

  // Append it to the body
  document.body.appendChild(aux);

  // Highlight its content
  aux.select();

  // Copy the highlighted text
  document.execCommand("copy");

  // Remove it from the body
  document.body.removeChild(aux);

}
</script>