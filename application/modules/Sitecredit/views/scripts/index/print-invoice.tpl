<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: print-invoice.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>
<style>
/*-----------------PRINT INVOICE CSS START FROM HERE-----------------*/
.fleft
{
  float: left;
}
.fright
{
  float: right;
}
.invoice_wrap
{
  margin: 0 auto;
  width: 600px;
}
.invoice_head_wrap
{
  background: none repeat scroll 0 0 #EAEAEA;
  border: 1px solid #CCCCCC;
  height: 40px;
  line-height: 40px;
  padding: 2px 10px;
}
.invoice_head .name
{
  font-size: 13pt;
}
.invoice_head .logo
{
  width: 300px;
  height: 40px;
}
.invoice_head .logo img
{
  max-height: 40px;
}
.invoice_details_wrap
{
  border: 1px solid #CCCCCC;
  overflow: hidden;
}
.invoice_add_details_wrap
{
  border-right: 1px solid #CCCCCC;
  width: 299px;
  float: left;
}
.invoice_order_details_wrap li, .invoice_ttlamt_box_wrap
{
  padding: 10px;
}
.invoice_order_details_wrap
{
  list-style: none;
  margin-left: -1px;
  border-left: 1px solid #CCCCCC;
}
.invoice_order_details_wrap li
{
  list-style: none;
  border-bottom: 1px solid #CCCCCC;
}
 .invoice_order_info
{
  width: 128px;
}

.invoice_ttlamt_box_wrap
{
  background: none repeat scroll 0 0 #EAEAEA;
  border: 1px solid #CCCCCC;
}
.invoice_ttlamt_box
{
  margin-bottom: 5px;
}
.invoice_ttlamt_box_wrap h2
{
  margin: 5px 0 0 0;
}
.invoice_order_details_wrap  ul{
  margin:0;
  padding: 0;
}
/* Print invoice CSS Stop from here */

/*======================= Responsive work for print invoice page============================= */

@media only screen and (max-width:920px) {
/* In voice container */
.invoice_wrap
{
  box-sizing: border-box;
  padding: 10px;
  width: 100%;
}
.invoice_head .logo
{
  width: 60%;
}
.invoice_head .logo img
{
  width: 100%;
}
.invoice_add_details_wrap, .invoice_order_details_wrap
{
  box-sizing: border-box;
  width: 50%;
}
}
</style>



<?php if( !empty($this->sitecredit_print_invoice_no_permission) ) : ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("You don't have permission to print the invoice of this order.") ?>
    </span>
  </div>
<?php return; endif; ?>


<div class="invoice_wrap">
  <div class="invoice_head_wrap">
    <div class="invoice_head">
        <div class="logo fleft"><b><?php echo ($this->logo) ? $this->htmlImage($this->logo) : $this->site_title; ?></b></div>
        <div class="name fright">  <strong><?php echo $this->translate('INVOICE') ?></strong>   </div>
    </div>
  </div>

      <div class="invoice_order_details_wrap">
        <ul> 
           <?php if( !empty($this->orderObj->user_id) ): ?>
            <li>
              <div class="invoice_order_info fleft"><b><?php echo $this->translate("Name"); ?></b></div>
              <div>: &nbsp;<?php echo $this->user_detail->getTitle(); ?></div>
            </li>
           <?php endif; ?>
           <li>
            <div class="invoice_order_info fleft"><b><?php echo $this->translate("Order Id:")?></b></div> 
            <div>: &nbsp;<?php  echo $this->translate("#%s", $this->orderObj->order_id);?></div>
          </li>
          <li>
           <div class="invoice_order_info fleft"> <b><?php echo $this->translate('Status'); ?> </b> </div>
           <div>: &nbsp;<?php  echo $this->orderObj->order_status . '<br/>';?> </div>
          </li>
          <li>
            <div class="invoice_order_info fleft"> <b><?php echo $this->translate('Placed on'); ?></b> </div>
            <div class="o_hidden">: &nbsp;<?php  echo $this->locale()->toDateTime($this->orderObj->creation_date) . '<br/>';?> </div>
          </li>
          <li>
            <div class="invoice_order_info fleft"> <b><?php echo $this->translate('Payment Method'); ?></b> </div>
            <div>: &nbsp;<?php  echo $this->gatewayTitle.'<br/>';?> </div>
          </li>
         </ul>
      </div>
     <b class="dblock clr mtop10 mbot5 fleft"><?php echo $this->translate("Order") ?></b>
      <div class="invoice_ttlamt_box_wrap mtop10 mbot5 fright">
        <div class="invoice_ttlamt_box fleft">
          <div class="clr">
            <div class="invoice_order_info fleft"><?php echo $this->translate(ucfirst($GLOBALS['credits']).' value'); ?></div>
            <div class="fright"><?php  echo $this->orderObj->credit_point . '<br/>';?></div>
          </div>
           </div>
        <div>
          <div  class="clr">
            <div class="fleft"><strong><?php echo $this->translate('Grand Total'); ?>&nbsp;&nbsp;</strong></div>
            <div class="fright"><strong><?php  echo Zend_Registry::get('Zend_View')->locale()->toCurrency($this->orderObj->grand_total,$this->currentCurrency);?></strong></div>
          </div>
        </div>
      </div> 
  </div>

</div>
 
    
<script type="text/javascript">
  window.print();
</script>
