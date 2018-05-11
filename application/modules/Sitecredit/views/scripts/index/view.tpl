<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="transaction_view_popup">
  <h3><?php echo $this->translate("Details :"); ?> 
    <span><a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'> <?php echo $this->translate("close") ?></a></span>
  </h3>
  <div class="transaction_view_popup_details">
  <?php switch($this->result->type) {
    case 'activity_type' :  $activity=Engine_Api::_()->getDbtable('activitycredits','sitecredit')->fetchRow(array('activitycredit_id = ?' => $this->result->type_id));
    
    $column='language_'.$this->language;
    
    if(empty($activity->$column)){ 
      $activity_type= $this->translate('ADMIN_ACTIVITY_TYPE_' . strtoupper($activity->activity_type));
      if(!empty($activity_type)){
        $activity_type = str_replace("(subject)","",$activity_type);
        $activity_type = str_replace("(object)","",$activity_type);
        
      }
    }
    else { $activity_type= $activity->$column; }
    ?> 
      <table class="table" width="100%">
        <tr>
          <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
          <td width="60%"><?php echo $this->translate('Earned by performing Activity') ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Activity Type') ?> : </strong></td>
          <td width="60%"><?php echo $activity_type; ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Values') ?> :</strong></td>
          <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
          <td width="60%"><?php echo $this->translate('Amount Credited') ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
          <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
        </tr>
      </table> 
      
      <?php break; case 'upgrade_request' :  $request=Engine_Api::_()->getDbtable('upgraderequests','sitecredit')->fetchRow(array('upgraderequest_id = ?' => $this->result->type_id)); ?>
      
      <table class="table" width="100%">
        <tr>
          <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
          <td width="60%"><?php echo $this->translate('Member Level Upgraded') ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Previous Level') ?> : </strong></td>
          <td width="60%"><?php $level = Engine_Api::_()->getItem('authorization_level', $request->current_level); echo $level->title; ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Upgraded To') ?> : </strong></td>
          <td width="60%"><?php $level = Engine_Api::_()->getItem('authorization_level', $request->requested_level); echo $level->title; ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Values') ?> :</strong></td>
          <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
          <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
          <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
        </tr>
      </table>  
      
      <?php break; case 'buy' :  $order=Engine_Api::_()->getDbtable('orders','sitecredit')->fetchRow(array('order_id = ?' => $this->result->type_id)); ?>
      
      <table class="table" width="100%">
        <tr>
          <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
          <td width="60%"><?php echo $this->translate('Purchased Credits')?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Amount')?> : </strong></td>
          <td width="60%">
            <?php 
            $currentCurrency =Engine_Api::_()->getDbtable('transactions','sitecredit')->fetchRow(array('order_id = ?' => $order->order_id))->currency;
            echo Zend_Registry::get('Zend_View')->locale()->toCurrency($order->grand_total,$currentCurrency);
            ?>
          </td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Values') ?> :</strong></td>
          <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Payment Gateway')?> :</strong></td>
          <td width="60%"><?php $gateway=Engine_Api::_()->getDbtable('gateways','payment')->fetchRow(array('gateway_id = ?' => $order->gateway_id));  echo $gateway->title;?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
          <td width="60%"><?php echo $this->translate('Amount Credited')?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
          <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
        </tr>
        <tr><td> <br/>
         <?php echo $this->htmlLink($this->url(array('action' => 'print-invoice', 'credit_id' =>$this->result->credit_id ), 'credit_general', true), $this->translate('Print Invoice'), array('target' => '_blank'));?>
       </td></tr>
     </table>  
     
     <?php break;
     case 'sent_to_friend' : 
     if($this->result->type_id){
      $user=Engine_Api::_()->user()->getUser($this->result->type_id);
     } 
     ?>
     
     <table class="table" width="100%">
      <tr>
        <td valign="top"><?php if(!empty($user)) echo $this->itemPhoto($user, 'thumb.icon')?></td>
        <td><table width="100%">
          <tr>
            <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
            <td width="60%"><?php echo $this->translate('Sent To Friend') ?></td>
          </tr>
          <tr>
            <td width="40%"><strong><?php echo $this->translate('Sent To') ?> :</strong></td>
            <td width="60%"><?php if(!empty($user))  echo $this->htmlLink($user->getOwner()->getHref(), $this->string()->stripTags($user->getOwner()->getTitle()), array('title' => $user->getOwner()->getTitle(), 'target' => '_blank')); 
              else echo "Deleted User"; ?></td>
          </tr>
          <tr>
            <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Values') ?> :</strong></td>
            <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
          </tr>
          <tr>
            <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
            <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
          </tr>
          <tr>
            <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
            <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
          </tr>

        <?php if(!empty($this->result->reason)):?>
        <tr>
            <td width="40%"><strong><?php echo $this->translate('Note') ?> :</strong></td>
            <td width="60%"><?php echo $this->result->reason; ?></td>
          </tr>
        <?php endif; ?>  
        </table>
      </td>

    </tr>
  </table>
  
  <?php
  break;
  case 'received_from_friend' : 
    if($this->result->type_id){
      $user=Engine_Api::_()->user()->getUser($this->result->type_id);
     }  
  ?>
  <table class="table" width="100%">
    <tr>
      <td valign="top"><?php if(!empty($user)) echo $this->itemPhoto($user, 'thumb.icon')?></td>
      <td>
       <table width="100%">
        <tr>
          <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
          <td width="60%"><?php echo $this->translate('Received From Friend')?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Received From')?> :</strong></td>
          <td width="60%"><?php if(!empty($user))  echo $this->htmlLink($user->getOwner()->getHref(), $this->string()->stripTags($user->getOwner()->getTitle()), array('title' => $user->getOwner()->getTitle(), 'target' => '_blank')); 
          else echo "Deleted User"; ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Values') ?> :</strong></td>
          <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
          <td width="60%"><?php echo $this->translate('Amount Credited') ?></td>
        </tr>
        <tr>
          <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
          <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
        </tr>
        <?php if(!empty($this->result->reason)):?>
        <tr>
            <td width="40%"><strong><?php echo $this->translate('Note') ?> :</strong></td>
            <td width="60%"><?php echo $this->result->reason; ?></td>
          </tr>
        <?php endif; ?>  
      </table>
    </td>
  </tr>
</table>

<?php
break;
case 'deduction' :  $activity=Engine_Api::_()->getDbtable('activitycredits','sitecredit')->fetchRow(array('activitycredit_id = ?' => $this->result->type_id)); 
$column='language_'.$this->language;

if(empty($activity->$column)){
  $activity_type= $this->translate('ADMIN_ACTIVITY_TYPE_' . strtoupper($activity->activity_type));
  if(!empty($activity_type)){
    $activity_type = str_replace("(subject)","",$activity_type);
    $activity_type = str_replace("(object)","",$activity_type);
    
  }
}
else {
  $activity_type= $activity->$column;
}
?>

<table class="table" width="100%">
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Deletion of activity') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Activity Type') ?> : </strong></td>
    <td width="60%"><?php echo $activity_type; ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Values') ?> :</strong></td>
    <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Deleted By') ?> :</strong></td>
    <td width="60%"><?php if($this->result->reason == "activity deleted by admin") echo "Admin"; else echo "You"; ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
    <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
  </tr>
</table> 

<?php break; case 'bonus' : ?>

<table class="table" width="100%">
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Bonus') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Values') ?> :</strong></td>
    <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Sent By') ?> : </strong></td>
    <td width="60%"><?php echo $this->translate('Admin') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Reason') ?> :</strong></td>
    <td width="60%"><?php echo $this->result->reason; ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Amount Credited') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
    <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
  </tr>
</table>

<?php break;
case 'affiliate' : 
if($this->result->type_id){
      $user=Engine_Api::_()->user()->getUser($this->result->type_id);
     } 
?>

<table class="table" width="100%">
  <tr>
    <td valign="top"><?php if(!empty($user)) echo $this->itemPhoto($user, 'thumb.icon')?></td>
    <td>
     <table width="100%">
      <tr>
        <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
        <td width="60%"><?php echo $this->translate('By Referral Signups') ?></td>
      </tr>
      <tr>
        <td width="40%"><strong><?php echo $this->translate('User Sign Up with Your link') ?> : </strong></td>
        <td width="60%"><?php if(!empty($user))  echo $this->htmlLink($user->getOwner()->getHref(), $this->string()->stripTags($user->getOwner()->getTitle()), array('title' => $user->getOwner()->getTitle(), 'target' => '_blank')); 
          else echo "Deleted User"; ?></td>
      </tr>
      <tr>
        <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Values') ?> :</strong></td>
        <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
      </tr>
      <tr>
        <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
        <td width="60%"><?php echo $this->translate('Amount Credited') ?></td>
      </tr>
      <tr>
        <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
        <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
      </tr>
    </table>
  </td>
</tr>
</table>
<?php break;
case 'store' : $order_table = Engine_Api::_()->getDbtable('orders', 'sitestoreproduct');
$order_ids = $order_table->getOrderIds($this->result->type_id);
$viewer_orders=ucfirst($GLOBALS['credits'])." used in order ";
foreach ($order_ids as $order_id) {
  $tempViewUrl = $this->url(array('action' => 'account', 'menuType' => 'my-orders', 'subMenuType' => 'order-view', 'orderId' => $order_id['order_id']), 'sitestoreproduct_general', true);
  $viewer_orders .= ' <a target="_blank" href="' . $tempViewUrl . '">#' . $order_id['order_id'] . '</a>';
} 
?>

<table class="table" width="100%">
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate(ucfirst($GLOBALS['credits']).' redeemed to purchase store products') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Orders') ?> :</strong></td>
    <td width="60%"><?php echo $viewer_orders;?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credits']).' Redeemed') ?> : </strong></td>
    <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
    <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
  </tr>
</table>

<?php break;
case 'event' :
$order_obj = Engine_Api::_()->getItem('siteeventticket_order', $this->result->type_id); 
$viewer_orders=ucfirst($GLOBALS['credits'])." used in order ";

$tempViewUrl = $this->url(array('action' => 'view', 'order_id' => $this->result->type_id, 'event_id' => $order_obj->event_id), 'siteeventticket_order', true);
$viewer_orders = '<a target="_blank" href="' . $tempViewUrl . '">#' . $this->result->type_id . '</a>';
?>

<table class="table" width="100%">
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate(ucfirst($GLOBALS['credits']).' redeemed to purchase event tickets') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Orders') ?> :</strong></td>
    <td width="60%"><?php echo $viewer_orders;?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credits']).' Redeemed') ?> : </strong></td>
    <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
    <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
  </tr>
</table>

<?php break; case 'subscription' : 
?>

<table class="table" width="100%">
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate(ucfirst($GLOBALS['credits']).' redeemed for subscription') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credits']).' Redeemed') ?> : </strong></td>
    <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
    <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
  </tr>
</table>
<?php break; case 'siteeventpaid_package' : 
?>

<table class="table" width="100%">
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate(ucfirst($GLOBALS['credits']).' redeemed for package purchase in events') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credits']).' Redeemed') ?> : </strong></td>
    <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
    <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
  </tr>
</table>
<?php break; case 'sitestore_package' : 
?>

<table class="table" width="100%">
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate(ucfirst($GLOBALS['credits']).' redeemed for package purchase in stores') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credits']).' Redeemed') ?> : </strong></td>
    <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
    <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
  </tr>
</table>
<?php break; case 'sitepage_package' : 
?>

<table class="table" width="100%">
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate(ucfirst($GLOBALS['credits']).' redeemed for package purchase in Directory/Pages') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credits']).' Redeemed') ?> : </strong></td>
    <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
    <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
  </tr>
</table>
<?php break; case 'sitereviewpaidlisting_package' : 
?>

<table class="table" width="100%">
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate(ucfirst($GLOBALS['credits']).' redeemed for package purchase in Review & Rating') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credits']).' Redeemed') ?> : </strong></td>
    <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
    <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
  </tr>
</table>
<?php break; case 'sitegroup_package' : 
?>

<table class="table" width="100%">
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate(ucfirst($GLOBALS['credits']).' redeemed for package purchase in Groups / Communities') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate(ucfirst($GLOBALS['credits']).' Redeemed') ?> : </strong></td>
    <td width="60%"><?php echo abs($this->result->credit_point); ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Type') ?> :</strong></td>
    <td width="60%"><?php echo $this->translate('Amount Debited') ?></td>
  </tr>
  <tr>
    <td width="40%"><strong><?php echo $this->translate('Date') ?> :</strong></td>
    <td width="60%"><?php echo date('dS F Y ', strtotime($this->result->creation_date)) ?></td>
  </tr>
</table>
<?php break; 
default : echo "unknown Type "; 
 break;  
}  ?>

</div>
</div>
<?php if( @$this->closeSmoothbox ): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>