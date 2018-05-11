<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<ul class="sitepage_sidebar_list">
	<?php foreach ($this->topRatedPages as $sitepage): ?>
		<li> 
			<?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepage->page_id, $sitepage->owner_id, $sitepage->getSlug()), $this->itemPhoto($sitepage, 'thumb.icon')) ?>
			<div class='sitepage_sidebar_list_info'>
				<div class='sitepage_sidebar_list_title'>
					<?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepage->page_id, $sitepage->owner_id, $sitepage->getSlug()), Engine_Api::_()->sitepage()->truncation($sitepage->getTitle()), array('title' => $sitepage->getTitle())) ?>
				</div>
				<div class='sitepage_sidebar_list_details'>
					<?php if (($sitepage->rating > 0)): ?>

						<?php
						$currentRatingValue = $sitepage->rating;
						$difference = $currentRatingValue - (int) $currentRatingValue;
						if ($difference < .5) {
							$finalRatingValue = (int) $currentRatingValue;
						} else {
							$finalRatingValue = (int) $currentRatingValue + .5;
						}
						?>

						<span title="<?php echo $finalRatingValue . $this->translate(' rating'); ?>">
							<?php for ($x = 1; $x <= $sitepage->rating; $x++): ?>
								<span class="rating_star_generic rating_star"></span>
							<?php endfor; ?>
							<?php if ((round($sitepage->rating) - $sitepage->rating) > 0): ?>
								<span class="rating_star_generic rating_star_half"></span>
							<?php endif; ?>
						</span>
					<?php endif; ?>          
				</div>  
			</div>
		</li>
	<?php endforeach; ?>
  <li class="sitepage_sidebar_list_seeall">
		<a href='<?php echo $this->url(array('action' => 'index','orderby'=> 'rating'), 'sitepage_general', true) ?>'><?php echo $this->translate('See All');?> &raquo;</a>
	</li>
</ul>