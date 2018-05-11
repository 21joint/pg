<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>
<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()->appendStylesheet($baseUrl . 'application/modules/Sitecredit/externals/styles/style_sitecredit.css');
?>
<form enctype="application/x-www-form-urlencoded" class="credit_form" id="BuyCreditForm" method="post">
  <div>
    <div>
      <div class="form-elements">
        <div id = "credit_form">
          <div id="credit_offer-wrapper" class="form-wrapper">
            <div id="credit_offer-label" class="form-label">
              <label for="credit_offer" class="optional"><?php echo $this->translate("How do you want to buy ".$GLOBALS['credits']."?");?></label>
            </div>
            <div id="credit_offer-element" class="form-element">
              <ul class="form-options-wrapper">
                <li>
                  <input type="radio" name="credit_offer" id="credit_offer-1" value="1" onchange="onOfferChange(this.value)"> 
                  <label for="credit_offer-1"><?php echo $this->translate("Using Offers");?></label>
                </li>
                <li>
                  <input type="radio" name="credit_offer" id="credit_offer-0" value="0" checked="checked" onchange="onOfferChange(this.value)">
                  <label for="credit_offer-0"><?php echo $this->translate("Direct");?></label>
                </li>
              </ul>
            </div>
          </div>
          <div id="credit_point_2-wrapper" class="form-wrapper">
            <div id="credit_point_2-label" class="form-label">
              <label for="credit_point_2" class="optional"><?php echo $this->translate(ucfirst($GLOBALS['credit'])." Values");?></label>
            </div>
            <div id="credit_point_2-element" class="form-element">
                <input type="text" name="credit_point_2" id="credit_point_2"  placeholder="<?php echo ucfirst($GLOBALS['credit'])?> Values" autocomplete="off" onkeypress="return isNumberKey(event)" >
            </div>
          </div>
          <div id="price-wrapper" class="form-wrapper">
            <div id="price-element" class="form-element">
            </div>
          </div>
          <div id="offers-wrapper" class="form-wrapper">
          <div id="offers-label" class="form-label">
          <label for="offers" class="optional">
          <?php echo empty($this->offerArray)?$this->translate("There are currently no offers to display."):$this->translate("Available offers");?>
          </label></div>
          <div id="offers-element" class="form-element">

            <ul class="form-options-wrapper">
              <?php foreach($this->offerArray as $key => $value):?>
                <li><input type="radio" name="offers" id="offers-<?php echo $key?>" value="<?php echo $key?>"><label for="offers-<?php echo $key?>"> <?php echo $value?></label></li>

              <?php endforeach; ?>
            </ul>
          </div>
          </div>
          <div id="error-div" style="display:none;"></div>
          <div id="payment_method-wrapper" class="form-wrapper"><div id="payment_method-label" class="form-label">&nbsp;</div>
          <div id="payment_method-element" class="form-element">
          <button type='button' onclick="validateForm()"><?php echo $this->translate("Choose Payment Method") ?></button>
          </div>
          </div>
        </div>
      </div>
      <div id="payment-form">
         <h3><?php echo $this->translate("Choose Payment Method");?> :</h3>
         <a class="back_btn" onclick="toggelview()"><img src="<?php echo $baseUrl; ?>application/modules/Sitecredit/externals/images/icons/go_back.png" alt="" /> Back</a>
         <div class="payment_form_value">
            <div><strong><?php echo ucfirst($GLOBALS['credits']) ?> : </strong><span id="payment_form_credit_value"></span></div>
            <div><strong>Amount to Pay : </strong><span id="payment_form_credit_price"></span></div>
         </div>
         <br/>
          <?php
          if($this->nogateway): ?>
             <div class="tip"><?php echo $this->translate("* Payment Gateway is not enabled.");?> </div>
           <?php else :
           foreach($this->EnabledGateways as $gateway) :
            if(!(in_array($gateway->title,$this->gatewaysEnabled)))
              continue;
            if( count($this->gatewaysEnabled) == 1 ):
              $selected = "checked = checked style='display:none'";
            else:
              $selected = "";
            endif;
            $paymentGatewayId = $gateway->gateway_id;
            $paymentGatewayTitle = strtolower($gateway->title);
            $paymentGatewayTitleUC = ucfirst($gateway->title);
            echo '<div class="sitecredit_payment_method"><input id="'.$paymentGatewayTitle.'" type="radio" name="payment_method" value="'.$paymentGatewayId.'" ' . $selected . ' ><label for="'.$paymentGatewayTitle.'" class="mbot5">';
            
            if($gateway->plugin == "Payment_Plugin_Gateway_PayPal")
            echo'<img src="' . $baseUrl . 'application/modules/Sitecredit/externals/images/'.$paymentGatewayTitle.'.png" title="'.$paymentGatewayTitleUC.'" /></label></div>';
            else
             echo '<img src="' . $baseUrl . 'application/modules/Sitegateway/externals/images/'.$paymentGatewayTitle.'.png" title="'.$paymentGatewayTitleUC.'" /></label></div>';
            ?>
          <?php endforeach; ?>
          <div id="error-div-Payment" style="display:none;"></div>
          
          <input type="hidden" name="confirm" value="<?php echo $this->offer_id?>"/>
          <br/>
          <?php if(!($this->nogateway)): ?>
          <button type='button' onclick="validatePaymentMethod()"><?php echo $this->translate("Buy ".ucfirst($GLOBALS['credits'])) ?></button>
          <?php endif;?>
          <?php endif;?>
        
      </div>
    </div>
  </div>
</form>

<?php 
  $currentCurrency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
  $priceStr = Zend_Registry::get('Zend_View')->locale()->toCurrency(1,$currentCurrency);
?>
<script type="text/javascript">
  window.addEvent('domready',function () {
    $('payment-form').hide();
    onOfferChange();
  });
  function isNumberKey(evt) { 
    var charCode = (evt.charCode) ? evt.which : event.keyCode

    if (charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 46) 
      return false; 
             
    return true; 
  } 
  function validateForm() {

    if($("credit_offer-0").checked) {
      if($('credit_point_2').value == "" )  {
        $('error-div').innerHTML="<?php echo $this->translate('* '.ucfirst($GLOBALS['credit']).' count is required') ?>"; 
        $('error-div').style.display="block";
        return false;
      } 
      if($('credit_point_2').value < 1) {
        $('error-div').innerHTML="<?php echo $this->translate('* Please enter valid '.$GLOBALS['credit'].' values') ?>";
        $('error-div').style.display="block"; 
        return false;
      }
    } else 
          if (!( $$("input[name='offers']:checked").length > 0) ){
            $('error-div').innerHTML="* Select an Offer"; 
            $('error-div').style.display="block";
            return false;
          }
    if($("credit_offer-0").checked) {
      $('payment_form_credit_value').innerHTML=" "+$('credit_point_2').value;
      $('payment_form_credit_price').innerHTML=" " +$('price-element').innerHTML;
       
    }else{
        var key = document.querySelector('input[name="offers"]:checked').value;
         <?php foreach ($this->offerValueArray as $key => $value): ?>
          
          if( key == <?php echo empty($key)?0:$key; ?> )
          {
            $('payment_form_credit_value').innerHTML="<?php echo $value['credit_point']; ?>";
            $('payment_form_credit_price').innerHTML="<?php echo $value['price']; ?>";  
          }

        <?php  endforeach;?> 
    }
    $('error-div').style.display="none"; 
    $('credit_form').hide();
    $('payment-form').show();
  } 

  function validatePaymentMethod()  {
    if (!( $$("input[name='payment_method']:checked").length > 0) ) {
      $('error-div-Payment').innerHTML="<?php echo $this->translate('* Select a Payment method to proceed') ?>"; 
      $('error-div-Payment').style.display="block";
    } else {
      $('error-div-Payment').style.display="none";
      $('BuyCreditForm').submit();
    }
  }

  function toggelview(){
    $('credit_form').show();
    $('payment-form').hide();
  }
  
  function onOfferChange()  {
    if ($("credit_offer-0").checked) {
      if ($("credit_point_2-wrapper")) {
        $("credit_point_2-wrapper").show();
        $("price-wrapper").show(); 
      } 
      if ($("offers-wrapper")) {
        $("offers-wrapper").hide();
      } 
       $("payment_method-wrapper").show();
    } else {
      if ($("credit_point_2-wrapper")) {
        $("credit_point_2-wrapper").hide();
        $("price-wrapper").hide(); 
      } 
      if ($("offers-wrapper")) {
        $("offers-wrapper").show();
        if(<?php echo empty($this->offerArray)? 1 : 0; ?>) {
          $("payment_method-wrapper").hide();
          return;
        }
        $("payment_method-wrapper").show();
      }
    }
    $('error-div').style.display="none";
    $('error-div').innerHTML="";           
  }

  $('credit_point_2').addEvent("keyup",function(event) {
    var b= "<?php echo $priceStr ?>";
    b=b.split('1')[0];
    var a=$('credit_point_2').value;
    if(<?php echo $this->costOnecredit ?>) {
      Price = <?php echo $this->costOnecredit?>*a;
      Price = Price.toFixed(2);
      $('price-element').innerHTML=b+" "+Price;
    }else{
      $('error-div').innerHTML="<?php echo $this->translate('* set '.$GLOBALS['credit'].' value in Admin Panel') ?>";
    }
  });     
</script>   