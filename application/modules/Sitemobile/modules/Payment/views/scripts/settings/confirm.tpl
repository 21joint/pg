<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: confirm.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John Boehr <j@webligo.com>
 */
?>
<div class="subscription-form">
  <form method="post" action="<?php echo $this->escape($this->url()) ?>?package_id=<?php echo $this->package->package_id ?>"
        class="global_form" enctype="application/x-www-form-urlencoded">
    <div>
      <div>
        <h3>
          <?php echo $this->translate('Confirm Subscription') ?>
        </h3>
        <p class="form-description">
          <?php echo $this->translate('You are about to subscribe to the plan: ' .
              '%1$s', '<strong>' .
              $this->translate($this->package->title) . '</strong>') ?>
          <br />
          <?php echo $this->translate('Are you sure you want to do this? You ' .
              'will be charged: %1$s',
              '<strong>' . $this->package->getPackageDescription()
              . '</strong>') ?>
        </p>
        <p style="padding-top:5px 0;">
          <?php echo $this->translate('If yes, click the button below and you ' .
              'will be taken to a payment page. When you have completed your ' .
              'payment, please remember to click the button that takes you back ' .
              'to our site.') ?>
        </p>
        <p style="padding:5px 0;">
          <?php echo $this->translate('Please note that no refund will be ' .
              'provided for any unused portion of your current plan.') ?>
        </p>
        <div class="form-elements">
          <div class="form-wrapper" id="execute-wrapper">
            <div class="form-element" id="execute-element">
              <button data-theme="b" type="submit" id="execute" name="execute"><?php echo $this->translate('Subscribe') ?></button>
              <?php echo $this->translate(' or ') ?>
              <?php echo $this->htmlLink(array('action' => 'index',
                'package_id' => null), $this->translate('cancel'), array('class'=>'ui-btn')) ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="gateway_id" id="gateway_id" value="" />
  </form>
</div>