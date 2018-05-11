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
<ul class="sitefaq_side_list">
	<?php foreach($this->paginator as $sitefaq):?>
		<li>
			<div class='sitefaq_title'>
				<?php $item_title = Engine_Api::_()->sitefaq()->truncateText($sitefaq->getTitle(), $this->truncation);?>
				<?php echo $this->htmlLink($sitefaq->getHref(), $item_title, array('title' => $sitefaq->getTitle())) ?>
			</div>
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
				<div class="sitefaq_stats">
					<?php for($x = 1; $x <= $sitefaq->rating; $x++): ?>
						<span class="rating_star_generic rating_star" title="<?php echo $this->translate('%1.1f rating', $finalRatingValue); ?>">
						</span>
					<?php endfor; ?>
					<?php if((round($sitefaq->rating) - $sitefaq->rating) > 0):?>
						<span class="rating_star_generic rating_star_half" title="<?php echo $this->translate('%1.1f rating', $finalRatingValue); ?>">
						</span>
					<?php endif; ?>
				</div>	
			<?php endif; ?>
			<div class='sitefaq_stats seaocore_txt_light'>
				<?php if($this->statisticsComment):?>
					<?php echo $this->translate(array('%s comment', '%s comments', $sitefaq->comment_count), $this->locale()->toNumber($sitefaq->comment_count)) ?>,
					<?php echo $this->translate(array('%s like', '%s likes', $sitefaq->like_count), $this->locale()->toNumber($sitefaq->like_count)) ?><?php if($this->statisticsView): ?>,<?php endif;?>
				<?php endif;?>

				<?php if($this->statisticsView):?>
					<?php echo $this->translate(array('%s view', '%s views', $sitefaq->view_count), $this->locale()->toNumber($sitefaq->view_count)) ?>
				<?php endif; ?>
			</div>		
		</li>
	<?php endforeach; ?>
</ul>