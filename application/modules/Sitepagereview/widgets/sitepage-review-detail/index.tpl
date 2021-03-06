<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<ul class="sitepage_sidebar_list sitepagereview_sidebar mbot15">
	<?php $ratingData = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->profileRatingbyCategory($this->sitepagereview->review_id); ?>
	<?php foreach($ratingData as $reviewcat): ?>
		<li class="sitepagereview_overall_rating">
			<?php if(!empty($reviewcat['reviewcat_name'])): ?>
				<?php 
					$showRatingImage = Engine_Api::_()->sitepagereview()->showRatingImage($reviewcat['rating'], 'box');
					$rating_value = $showRatingImage['rating_value'];
				?>
			<?php else:?>
				<?php
					$showRatingImage = Engine_Api::_()->sitepagereview()->showRatingImage($reviewcat['rating'], 'star');
					$rating_value = $showRatingImage['rating_value'];
					$rating_valueTitle = $showRatingImage['rating_valueTitle'];
				?>
			<?php endif; ?>
			<?php if(!empty($reviewcat['reviewcat_name'])): ?>
				<div class="review_cat_rating">
					<ul class='rating-box-small <?php echo $rating_value; ?>'>
						<li id="1" class="rate one">1</li>
						<li id="2" class="rate two">2</li>
						<li id="3" class="rate three">3</li>
						<li id="4" class="rate four">4</li>
						<li id="5" class="rate five">5</li>
					</ul>
				</div>
			<?php else:?>
				<div class="review_cat_rating">
					<ul title="<?php echo $rating_valueTitle.$this->translate(" rating"); ?>" class='rating <?php echo $rating_value; ?>'>
						<li id="1" class="rate one">1</li>
						<li id="2" class="rate two">2</li>
						<li id="3" class="rate three">3</li>
						<li id="4" class="rate four">4</li>
						<li id="5" class="rate five">5</li>
					</ul>
				</div>
			<?php endif;?>
			<?php if(!empty($reviewcat['reviewcat_name'])): ?>
				<div class="review_cat_title">
					<?php echo $this->translate($reviewcat['reviewcat_name']); ?>
				</div>
			<?php else:?>
				<div class="review_cat_title">
					<?php echo $this->translate("Overall Rating");?>
				</div>	
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
	<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.proscons', 1)):?>
		<li>
			<?php echo "<b>".$this->translate("Pros: ")."</b>".$this->viewMore($this->sitepagereview->pros) ?>
		</li>
		<li>	
			<?php echo "<b>".$this->translate("Cons: ")."</b>".$this->viewMore($this->sitepagereview->cons) ?>
		</li>	
	<?php endif;?>
	<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.recommend', 1)):?>
		<li>
		<?php if($this->sitepagereview->recommend):?>
			<?php echo $this->translate("Member's Recommendation: <b>Yes</b>"); ?>
		<?php else: ?>
			<?php echo $this->translate("Member's Recommendation: <b>No</b>"); ?>
		<?php endif;?>
	<?php endif;?>
	</li>
</ul>