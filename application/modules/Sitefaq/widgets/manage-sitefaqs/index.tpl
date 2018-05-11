<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
  	              . 'application/modules/Sitefaq/externals/styles/style_sitefaq.css');
?>

<script type="text/javascript">
  var pageAction =function(page){
    $('page').value = page;
    $('filter_form').submit();
  }
</script>

<?php if( count($this->paginator) > 0 ): ?>
	<ul class='faq_list'>
		<?php foreach( $this->paginator as $sitefaq ): ?>
			<li>
				<div class='faq_list_photo'>
					<?php echo $this->htmlLink($sitefaq->getOwner()->getHref(), $this->itemPhoto($sitefaq->getOwner(), 'thumb.icon')) ?>
				</div>
				<div class="faq_list_options">
					<?php if(!empty($this->can_edit)): ?>
						<?php if(!empty($sitefaq->draft)): ?>
							<?php echo $this->htmlLink(array('route' => 'sitefaq_specific', 'action' => 'publish', 'faq_id' => $sitefaq->getIdentity()), $this->translate('Publish FAQ'), array('class' => 'buttonlink smoothbox icon_sitefaq_publish')) ?>
						<?php endif; ?>
						<?php echo $this->htmlLink(array('route' => 'sitefaq_specific', 'action' => 'edit', 'faq_id' => $sitefaq->getIdentity()), $this->translate('Edit FAQ'), array('class' => 'buttonlink icon_sitefaq_edit')) ?>
					<?php endif; ?>

					<?php if(!empty($this->can_delete)): ?>
						<?php echo $this->htmlLink(array('route' => 'sitefaq_specific', 'action' => 'delete', 'faq_id' => $sitefaq->getIdentity(), 'format' => 'smoothbox'), $this->translate('Delete FAQ'), array(
						'class' => 'buttonlink smoothbox icon_sitefaq_delete'));?>
					<?php endif; ?>

				</div>
				<div class="faq_list_info">
					<div class="faq_list_info_top">
						<?php if($sitefaq->featured == 1): ?>
							<span class="sitefaq_icon sitefaq_icon_featured" title="<?php echo $this->translate('Featured'); ?>"></span>
						<?php endif;?>
						<?php if($sitefaq->approved != 1): ?>
							<span class="sitefaq_icon sitefaq_icon_disapprove" title="<?php echo $this->translate('Not Approved'); ?>"></span>
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
									</span>
								<?php endfor; ?>
								<?php if((round($sitefaq->rating) - $sitefaq->rating) > 0):?>
									<span class="rating_star_generic rating_star_half" title="<?php echo $this->translate('%1.1f rating', $finalRatingValue); ?>">
									</span>
								<?php endif; ?>
							</span>	
						<?php endif; ?>
						<div class="faq_list_manage_title">
							<?php echo $this->htmlLink($sitefaq->getHref(), $sitefaq->getTitle()) ?>
						</div>
					</div>
					<div class="seaocore_browse_list_info_date">
						<?php if(!empty($this->modified_date)): ?>
							<?php echo $this->translate('Updated %s', $this->timestamp($sitefaq->modified_date)) ?>	
							<br />
						<?php endif;?>

						<?php if($this->statisticsComment):?>
							<?php echo $this->translate(array('%s comment', '%s comments', $sitefaq->comment_count), $this->locale()->toNumber($sitefaq->comment_count)) ?>,
							<?php echo $this->translate(array('%s like', '%s likes', $sitefaq->like_count), $this->locale()->toNumber($sitefaq->like_count)) ?><?php if($this->statisticsView): ?>,<?php endif; ?>
						<?php endif;?>

						<?php if($this->statisticsView):?>
							<?php echo $this->translate(array('%s view', '%s views', $sitefaq->view_count), $this->locale()->toNumber($sitefaq->view_count)) ?><?php if($this->statisticsHelpful && $sitefaq->helpful >= 0): ?>,<?php endif; ?>
						<?php endif; ?>
						<?php if($this->statisticsHelpful && $sitefaq->helpful >= 0): ?>
							<?php echo '<b>'.$sitefaq->helpful.'%</b>'.$this->translate(' helpful.');?>
						<?php endif; ?>
					</div>
					<div class="sitefaq_faq_body">
						<?php echo Engine_Api::_()->sitefaq()->truncateText($sitefaq->getFullDescription(), 180); ?>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php if( count($this->paginator) > 1 ): ?>
		<div class="seaocore_pagination">
			<?php echo $this->paginationControl($this->paginator, null, null, array('query' => $this->formValues,'pageAsQuery' => true,)); ?>
		</div>
	<?php endif; ?>
<?php elseif(count($this->paginator) <= 0 && isset($this->formValues['search_form']) && !empty($this->formValues['search_form'])): ?>
	<div class="tip">
		<span>
			<?php echo $this->translate('No FAQs matching with that criteria could be found. Please try a different search.');?>
			<?php if(!empty($this->can_create)): ?>
				<?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sitefaq_general").'">', '</a>'); ?>
			<?php endif; ?>
		</span>
	</div>
<?php else: ?>
	<div class="tip">
		<span>
			<?php echo $this->translate('You have not created a FAQ yet.');?>
			<?php if(!empty($this->can_create)): ?>
				<?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sitefaq_general").'">', '</a>'); ?>
			<?php endif; ?>
		</span>
	</div>
<?php endif; ?>