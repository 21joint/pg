<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: print-view.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
	$this->headLink()
			->prependStylesheet($this->layout()->staticBaseUrl.'application/modules/Sitefaq/externals/styles/style_sitefaq_print.css');
?>
<link href="<?php echo $this->layout()->staticBaseUrl.'application/modules/Sitefaq/externals/styles/style_sitefaq_print.css'?>" type="text/css" rel="stylesheet" media="print" />

<div class="sitefaq_print">
	<div class='sitefaq_print_header'>
		<span>
			<?php $site_title =  Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', 'Advertisement'); echo $this->translate($site_title).' - '.$this->translate('FAQs');?>
		</span>
		<div id="printdiv">
			<a href="javascript:void(0);" class="buttonlink" onclick="printData()" align="right"><?php echo $this->translate('Take Print') ?></a>
		</div>
	</div>
	<div class='sitefaq_print_view_title'>
		<span><?php echo $this->sitefaq->getTitle(); ?></span>
	</div>


<?php if($this->sitefaq->rating > 0 && $this->statisticsRating): ?>
		<div class="sitefaq_stats">
			<?php 
				$currentRatingValue = $this->sitefaq->rating;
				$difference = $currentRatingValue- (int)$currentRatingValue;
				if($difference < .5) {
					$finalRatingValue = (int)$currentRatingValue;
				}
				else {
					$finalRatingValue = (int)$currentRatingValue + .5;
				}
			?>
			<div id="sitefaq_rating" class="rating sitefaq_rating" >
				<?php for($x = 1; $x <= $this->sitefaq->rating; $x++): ?>
					<span class="rating_star_big_generic rating_star_big" title="<?php echo $this->translate('%f rating', $finalRatingValue); ?>"></span>
				<?php endfor; ?>
				<?php if((round($this->sitefaq->rating) - $this->sitefaq->rating) > 0):?>
					<span class="rating_star_big_generic rating_star_big_half" title="<?php echo $this->translate('%1.0f rating', $finalRatingValue); ?>"></span>
				<?php endif; ?>
				<span id="rating_text" class="rating_text"><?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?></span>
			</div>
		</div>
		
	<?php endif; ?>	


  
  <div class="sitefaq_print_view_des">
  	<?php echo $this->sitefaq->getFullDescription(); ?>
  </div>

	<!--CUSTOM FIELD WORK -->
	<?php echo $this->fieldValueLoop($this->sitefaq, $this->fieldStructure) ?>
	<!--END CUSTOM FIELD WORK -->
	
	<?php if($this->statisticsHelpful && $this->sitefaq->helpful >= 0): ?>
		<div class="sitefaq_helpful_content">
			<div>
				<?php echo '<b>'.$this->sitefaq->helpful.'%</b>'.$this->translate(' users marked this FAQ as helpful.');?><span class="sfhc-sep"></span>
			</div>
		</div>
	<?php endif; ?>

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