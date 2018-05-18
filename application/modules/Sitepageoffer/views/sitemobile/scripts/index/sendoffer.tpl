<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: sendoffer.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<div class="global_form_popup">
  <?php $email =  Engine_Api::_()->user()->getViewer()->email;?>
	<h4><?php echo $this->translate("Offer Resent");?></h4>
	<div class="clr" style="overflow:hidden;">
		<div class="fleft" style="margin-right:10px;">
			<?php echo "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/mail.png' alt='' class='fleft' />" ?>
		</div>
		<div style="padding-top:5px;">
			<?php $email = "<b>$email</b>";?>
			<?php echo $this->translate("We’ve resent you this offer at $email. Please check spam folder if you do not see the offer in your inbox.");?>
		</div>
	</div>	
	<div class="clr" style="margin-top:10px;">
        <a href="#" data-rel="back" data-role="button">
          <?php echo $this->translate('Okay') ?>
        </a>
	</div>	
</div>