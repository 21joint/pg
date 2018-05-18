<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: store_product.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
  $ratingValue = 'rating_avg'; 
  $ratingShow = 'small-star';
  $ratingType = 'overall';
?>
<?php $getIntrestedMemberCount = Engine_Api::_()->getDbtable('notifyemails', 'sitestoreproduct')->getNotifyEmail($item->product_id, 'COUNT(notifyemail_id)')->query()->fetchColumn(); ?>
	<?php if (!empty($item->sponsored)): ?>
	<li class="sitestoreproduct_q_v_wrap list_sponsered b_medium">
	<?php else: ?>
	<li class="sitestoreproduct_q_v_wrap b_medium">
	<?php endif; ?>
	<div class='sr_sitestoreproduct_browse_list_photo b_medium'>
		<?php $product_id = $item->product_id; ?>
		<?php $quickViewButton = true; ?>
		<?php include APPLICATION_PATH . '/application/modules/Sitestoreproduct/views/scripts/_quickView.tpl'; ?>
		<?php if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitestoreproduct.fs.markers', 1)): ?>
			<?php if ($item->featured): ?>
				<i class="sr_sitestoreproduct_list_featured_label" title="<?php echo $this->translate('Featured'); ?>"></i>
			<?php endif; ?>
			<?php if ($item->newlabel): ?>
				<i class="sr_sitestoreproduct_list_new_label" title="<?php echo $this->translate('New'); ?>"></i>
			<?php endif; ?>
		<?php endif; ?>

		<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal', '', array('align' => 'center'))) ?>

		<?php if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitestoreproduct.fs.markers', 1)): ?>
		<?php if (!empty($item->sponsored)): ?>
			<div class="sr_sitestoreproduct_list_sponsored_label" style="background: <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitestoreproduct.sponsoredcolor', '#FC0505');
						; ?>">
			<?php echo $this->translate('SPONSORED'); ?>                 
			</div>
		<?php endif; ?>
	<?php endif; ?>
	</div>
	<div class='sr_sitestoreproduct_browse_list_info'>  

		<div class="sr_sitestoreproduct_browse_list_show_rating fright">
			<?php if($ratingValue == 'rating_both'): ?>
				<?php echo $this->showRatingStarSitestoreproduct($item->rating_editor, 'editor', $ratingShow); ?>
				<br/>
				<?php echo $this->showRatingStarSitestoreproduct($item->rating_users, 'user', $ratingShow); ?>
			<?php else: ?>
				<?php echo $this->showRatingStarSitestoreproduct($item->$ratingValue, $ratingType, $ratingShow); ?>
			<?php endif; ?>
		</div>
		
		<div class='sr_sitestoreproduct_browse_list_info_header o_hidden'>
			<div class="sr_sitestoreproduct_list_title">
				<?php echo $this->htmlLink($item->getHref(), Engine_Api::_()->seaocore()->seaocoreTruncateText($item->getTitle(), $this->title_truncation), array('title' => $item->getTitle())); ?>
			</div>
			<div class="clear"></div>
		</div>
		<div class='sr_sitestoreproduct_browse_list_info_stat seaocore_txt_light'>
			<a href="<?php echo $this->url(array('category_id' => $item->category_id, 'categoryname' => $item->getCategory()->getCategorySlug()), "" . $this->categoryRouteName . ""); ?>"><?php echo $item->getCategory()->getTitle(true) ?>
			</a>
		</div>
		<?php 
			// CALLING HELPER FOR GETTING PRICE INFORMATIONS
			echo $this->getProductInfo($item, $this->identity, 'list_view', 1, 1, true, 0); 
    ?>
		<div class='sr_sitestoreproduct_browse_list_info_stat seaocore_txt_light'>
			<?php 
				$statistics = '';
				$statistics .= $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)).', ';
				$statistics .= $this->partial(
				'_showReview.tpl', 'sitestoreproduct', array('sitestoreproduct'=> $item)).', ';
				$statistics .= $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)).', ';
				$statistics .= $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)).', ';
				
				$statistics = trim($statistics);
				$statistics = rtrim($statistics, ',');
				echo $statistics;
			?>
			<?php
			if (!empty($getIntrestedMemberCount)):
				echo $this->translate(array('%s buyer intrested.', '%s buyers intrested.', $getIntrestedMemberCount), $this->locale()->toNumber($getIntrestedMemberCount)) . '<br />';
			endif;
			?>
		</div>
		<div class='sr_sitestoreproduct_browse_list_info_blurb'>
			<?php echo substr(strip_tags($item->body), 0, 350); if (strlen($item->body)>349) echo $this->translate("...");?>
		</div>
	</div>
</li>