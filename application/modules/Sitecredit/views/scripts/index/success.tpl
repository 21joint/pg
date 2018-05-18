<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: success.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>
   <?php if( count($this->navigation) ): ?>
<div class='seaocore_admin_tabs clr'>
    <?php
    // Render the menu
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
</div>

<?php endif; ?>
<div >
  <?php if( empty($this->state) || ($this->state == 'active') ): ?>
    <p>
       <?php echo $this->success_message; ?>
    </p>

  <?php elseif( $this->state == 'pending' ): ?>
    <h3>
      <?php echo $this->translate('Payment Pending') ?>
    </h3>
    <p>
      <?php echo $this->translate('Thank you for submitting your payment. Your payment is currently pending - your order will be placed when we are notified that the payment has completed successfully.') ?>
    </p>

  <?php else:?>
    <h3>
      <?php echo $this->translate('Payment Failed') ?>
    </h3>
    <p>
      <?php echo $this->translate('There was an error processing your transaction for the %s: %s.', $this->translate(array('order', 'orders', $this->indexNo)), $this->viewerOrders) ?>
      <?php if( !empty($this->viewer_id) ) : ?>
        <?php echo $this->translate('We suggest that you please try again with another payment method after clicking on "Buy Credits" from my credits page.') ?>
      <?php endif; ?>
    </p>

  <?php endif; ?>
</div>
<?php if( !empty($this->viewer_id) ) : ?>
  <button class="mtop10" onclick="viewYourOrder()">
    <?php echo $this->translate('Go to My '.ucfirst($GLOBALS['credits'])) ?>
  </button>
<?php endif; ?>

<script type="text/javascript">
  function viewYourOrder()
  {
    window.location.href = '<?php echo $this->url(array('action' => 'index', ), 'credit_general', true) ?>';
  }
</script>
    