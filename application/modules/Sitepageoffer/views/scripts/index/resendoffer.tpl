<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: resendoffer.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript" >

function owner(thisobj) {
	var Obj_Url = thisobj.href  ;

	Smoothbox.open(Obj_Url);
}
</script>

<?php if(!empty($this->private_message)):?>
	<div class="tip global_form_popup">
		<span>
			<?php echo $this->translate("You are not authorized to get this offer."); ?>
		</span>
	</div>
	<?php return;?>
<?php endif;?>

<div class="global_form_popup">
	<h4><?php echo $this->translate("Resend Offer");?></h4>
	<div class="clr" style="overflow:hidden;">
		<div style="padding-top:5px;">
			<?php echo $this->translate("We suggest you to please check your mail's spam folders to make sure you haven't already received the offer we have emailed earlier.");?>
		</div>
	</div>	
	<div class="clr" style="margin-top:10px;">
		<button onclick="resendoffer('<?php echo $this->offer_id;?>')" type="button" id="resend" name="resend" style="margin-right:10px;"><?php echo $this->translate('Resend Offer'); ?></button>
    <?php echo $this->translate(" or "); ?> 
    <a onclick="javascript:parent.Smoothbox.close();" href="javascript:void(0);" id="cancel" name="cancel"><?php echo $this->translate('Cancel'); ?></a>
	</div>	
</div>

<script type="text/javascript" >
  function resendoffer(offer_id) {  
    var url = en4.core.baseUrl + 'sitepageoffer/index/sendoffer/id/'+ offer_id;
    window.location = url;
  }
</script>