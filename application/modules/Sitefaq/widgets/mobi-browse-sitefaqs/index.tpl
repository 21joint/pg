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

<?php if( count($this->paginator) > 0 ): ?>

	<ul class='seaocore_browse_list'>
		<?php foreach( $this->paginator as $sitefaq ): ?>
			<li>
				<div class="seaocore_browse_list_info">
					<div class="seaocore_browse_list_info_title">
						<?php if($sitefaq->featured == 1): ?>
							<span class="sitefaq_icon sitefaq_icon_featured"></span>
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
						<div class="seaocore_title">
							<?php if(!empty($this->url_category_id)): ?>
								<?php echo $this->htmlLink($this->url(array('faq_id' => $sitefaq->faq_id, 'slug' => $sitefaq->getSlug(), 'category_id' => $this->url_category_id, 'subcategory_id' => $this->url_subcategory_id, 'subsubcategory_id' => $this->url_subsubcategory_id), 'sitefaq_view'), $sitefaq->getTitle()) ?>
							<?php else: ?>
								<?php echo $this->htmlLink($sitefaq->getHref(), $sitefaq->getTitle()) ?>
							<?php endif; ?>
						</div>
					</div>
					
					<div class="seaocore_browse_list_info_date">

						<?php if($this->statisticsComment):?>
							<?php echo $this->translate(array('%s comment', '%s comments', $sitefaq->comment_count), $this->locale()->toNumber($sitefaq->comment_count)) ?>,
							<?php echo $this->translate(array('%s like', '%s likes', $sitefaq->like_count), $this->locale()->toNumber($sitefaq->like_count)) ?><?php if($this->statisticsView): ?>,<?php endif;?>
						<?php endif;?>

						<?php if($this->statisticsView):?>
							<?php echo $this->translate(array('%s view', '%s views', $sitefaq->view_count), $this->locale()->toNumber($sitefaq->view_count)) ?>
						<?php endif; ?>

       		</div>

					<div class="sitefaq_faq_body">
						<?php if(!empty($this->truncation)): ?>
							<?php echo $this->sitefaq_api->truncateText($sitefaq->getFullDescription(), $this->truncation); ?>
						<?php else: ?>
							<?php echo strip_tags($sitefaq->getFullDescription()); ?>
						<?php endif; ?>
					</div>
					
					<?php if($this->statisticsHelpful && $sitefaq->helpful >= 0): ?>
						<div class="sitefaq_faq_body">
							<?php echo '<b>'.$sitefaq->helpful.'%</b>'.$this->translate(' users marked this FAQ as helpful.');?><span class="sfhc-sep"></span>
						</div>
					<?php endif; ?>
					
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php if( count($this->paginator) > 1 ): ?>
		<div class="seaocore_pagination">
			<?php echo $this->paginationControl($this->paginator, null, null, array('query' => $this->formValues,'pageAsQuery' => true,)); ?>
		</div>
	<?php endif; ?>
<?php elseif(count($this->paginator) <= 0 && ((isset($this->formValues['search_form']) && !empty($this->formValues['search_form'])))):?>
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
			<?php echo $this->translate('No FAQs has been created yet.');?>
			<?php if(!empty($this->can_create)): ?>
				<?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sitefaq_general").'">', '</a>'); ?>
			<?php endif; ?>
		</span>
	</div>
<?php endif; ?>