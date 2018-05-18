<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: print.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
	$baseUrl = $this->layout()->staticBaseUrl;
	$this->headLink()
			->prependStylesheet($baseUrl.'application/modules/Sitefaq/externals/styles/style_sitefaq_print.css');
?>
<link href="<?php echo $baseUrl.'application/modules/Sitefaq/externals/styles/style_sitefaq_print.css'?>" type="text/css" rel="stylesheet" media="print" />

<div class="sitefaq_print">
	<div class='sitefaq_print_header' style="margin-bottom:0px;">
		<span>
			<?php $site_title =  Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', 'Advertisement'); echo $this->translate($site_title).' - '.$this->translate('FAQs');?>
		</span>
		<div id="printdiv">
			<a href="javascript:void(0);" class="buttonlink" onclick="printData()" align="right"><?php echo $this->translate('Take Print') ?></a>
		</div>
	</div>
	<?php if( count($this->paginator) > 0 ): ?>

		<ul class='faq_list'>
			<?php foreach( $this->paginator as $sitefaq ): ?>
				<li>
					<div class="faq_list_info">
						<div class="faq_list_info_top">
							<?php if($sitefaq->featured == 1): ?>
								<span>
									<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/featured.gif', '', array('class' => 'icon', 'title' => $this->translate('Featured'))) ?>
								</span>
							<?php endif;?>
							<?php if(($sitefaq->rating > 0) && $this->statisticsRating):?>
								<?php 
									$currentRatingValue = $sitefaq->rating;
									$difference = $currentRatingValue- (int)$currentRatingValue;
									if($difference < .5) {
										$finalRatingValue = (int)$currentRatingValue;
									}
									else {
										$finalRatingValue = (int)$currentRatingValue + .5;
									}	
								?>
								<span class="list_rating_star">
									<?php for($x = 1; $x <= $sitefaq->rating; $x++): ?>
										<span class="rating_star_generic rating_star" title="<?php echo $this->translate('%1.1f rating', $finalRatingValue); ?>">
											<img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Seaocore/externals/images/star.png" alt />
										</span>
									<?php endfor; ?>
									<?php if((round($sitefaq->rating) - $sitefaq->rating) > 0):?>
										<span class="rating_star_generic rating_star_half" title="<?php echo $this->translate('%1.1f rating', $finalRatingValue); ?>">
											<img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Seaocore/externals/images/star_half.png" alt />
										</span>
									<?php endif; ?>
								</span>	
							<?php endif; ?>
							<div class="faq_list_title">
								<a href="javascript:void(0);"><?php echo $sitefaq->getTitle();?></a>
							</div>
						</div>

						<div id='<?php echo "faq_expand_$sitefaq->faq_id"?>' class="faq_details_expand sitefaq_faq_body">

							<?php echo $sitefaq->getFullDescription(); ?>

							<div class="faq_list_info_links">
	
								<?php if($this->statisticsComment):?>
									<?php echo $this->translate(array('%s comment', '%s comments', $sitefaq->comment_count), $this->locale()->toNumber($sitefaq->comment_count)) ?>,
									<?php echo $this->translate(array('%s like', '%s likes', $sitefaq->like_count), $this->locale()->toNumber($sitefaq->like_count)) ?><?php if($this->statisticsView): ?>,<?php endif;?>
								<?php endif;?>

								<?php if($this->statisticsView):?>
									<?php echo $this->translate(array('%s view', '%s views', $sitefaq->view_count), $this->locale()->toNumber($sitefaq->view_count)) ?>
								<?php endif; ?>

	         		</div>

							<?php if($this->statisticsHelpful && $sitefaq->helpful >= 0): ?>
								<div class="sitefaq_helpful_content">
									<div>
										<?php echo '<b>'.$sitefaq->helpful.'%</b>'.$this->translate(' users marked this FAQ as helpful.');?><span class="sfhc-sep"></span>
									</div>
								</div>
							<?php endif; ?>
						
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
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