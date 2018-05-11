<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: print.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
	$this->headLink()
			->prependStylesheet($this->layout()->staticBaseUrl.'application/modules/Seaocore/externals/styles/style_print.css');
?>

<link href="<?php echo $this->layout()->staticBaseUrl.'application/modules/Seaocore/externals/styles/style_print.css'?>" type="text/css" rel="stylesheet" media="print">

<div class="seaocore_print_page">
	<div class="seaocore_print_title">	
    <span class="left">
      <?php echo $this->sitepageoffer->getTitle().' in '.$this->sitepage->title; ?>
    </span>
		<span class="right">
			<?php echo $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title'));?>
		</span>
	</div>
	<div class='seaocore_print_profile_fields'>
		<div class="seaocore_print_photo">
			<?php if(!empty($this->sitepageoffer->photo_id)):?>
				<?php echo $this->itemPhoto($this->sitepageoffer, 'thumb.normal'); ?>
      <?php else:?>
        <?php echo "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' />" ?>
      <?php endif;?>  
			<div id="printdiv" class="seaocore_print_button">
				<a href="javascript:void(0);" style="background-image: url('<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepage/externals/images/printer.png');" class="buttonlink" onclick="printData()" align="right"><?php echo $this->translate('Take Print') ?></a>
			</div>
		</div>
		<div class="seaocore_print_details">	      
			<h4>
				<?php echo $this->translate('Page Offer Information') ?>
			</h4>
			<ul>
        <li>
					<span><?php echo $this->translate('End date:'); ?></span>
					<?php if($this->sitepageoffer->end_settings == 1):?>
						<span><?php echo $this->translate( gmdate('M d, Y', strtotime($this->sitepageoffer->end_time))) ?></span>
					<?php else:?>
						<span><?php echo $this->translate('Never Expires') ?></span>
					<?php endif;?>
				</li>
				<li>
					<span><?php echo $this->translate('Description:'); ?></span>
					<span><?php echo $this->translate(''); ?> <?php echo $this->sitepageoffer->description ?></span>
				</li>
			</ul>        
		</div>	
	</div>
</div>

<script type="text/javascript">
	function printData() {
		document.getElementById('printdiv').style.display = "none";

		window.print();
		setTimeout(function() {
			document.getElementById('printdiv').style.display = "block";
		}, 500);
	}
</script>
