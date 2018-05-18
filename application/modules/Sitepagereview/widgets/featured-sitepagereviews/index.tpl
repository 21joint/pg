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
<?php
	$this->headLink()
        ->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/styles/sitepage-tooltip.css');
?>

<script type="text/javascript">
  function viewMoreReview()
  {
    $('review_view_more').style.display ='none';
    $('review_loding_image').style.display = 'block';
    en4.core.request.send(new Request.HTML({
      method : 'post',
      'url' : en4.core.baseUrl + 'widget/index/mod/sitepagereview/name/featured-sitepagereviews',
      'data' : {
        format : 'html',
        isajax : 1,
				itemCount : '<?php echo $this->itemCount; ?>',
				page: getNextReviewPage()
      },
      onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
				$('review_loding_image').style.display = 'none';
				$('review_view_more').style.display ='block';
        $('reviews_layout').innerHTML = responseHTML;
				var review_content = $('reviews_layout').getElement('.layout_sitepagereview_featured_sitepagereviews').innerHTML;
				$('reviews_layout').innerHTML = review_content;
      }
    }));

    return false;
  }  

	function getNextReviewPage(){
		var next_page = '<?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>';
		var total_page = '<?php echo $this->total_page ?>';
		if(total_page >= next_page) {
			return next_page;
		}
	}
</script>

<?php if(empty($this->is_ajax)):?>
	<ul class="sitepage_sidebar_list" id="reviews_layout">
<?php endif; ?>
<?php $photo_review = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.photo', 1);?>
<?php foreach ($this->paginator as $review): ?>
  <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $review->page_id);?>
	<li class="sitepagereview_show_tooltip_wrapper">
		<div class="sitepagereview_show_tooltip">
			<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepage/externals/images/tooltip_arrow.png" alt="" class="arrow" />
			<?php echo Engine_Api::_()->sitepagereview()->truncateText($review->body, 100) ?>
		</div>
		<?php $user = Engine_Api::_()->getItem('user', $review->owner_id); ?>
		<?php if(!empty($photo_review)):?>
      <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon'), array('title' => $user->getTitle())) ?>
      <?php else:?>
        <?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepage_object->page_id, $sitepage_object->owner_id, $sitepage_object->getSlug()), $this->itemPhoto($sitepage_object, 'thumb.icon'), array('title' => $sitepage_object->getTitle()));?>
    <?php endif;?>
		<div class='sitepage_sidebar_list_info'>
			<div class='sitepage_sidebar_list_title'>
				<?php $review_title = Engine_Api::_()->sitepagereview()->truncateText($review->title, 28);?>
				<?php echo $this->htmlLink($review->getHref(), $review_title, array('title' => $review->title)) ?>
			</div>

			<div class='sitepage_sidebar_list_details'>
				<?php $page_title = Engine_Api::_()->sitepagereview()->truncateText($review->page_title, 18); ?>
				<?php echo $this->translate("on ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($review->page_id, $review->owner_id, $review->getSlug()), $page_title, array('title' => $review->page_title,'class' => 'bold')) ?>
			</div>

			<div class='sitepage_sidebar_list_details'>
				<span title="<?php echo $review->rating . $this->translate(' rating'); ?>">
					<?php if (($review->rating > 0)): ?>
						<?php for ($x = 1; $x <= $review->rating; $x++): ?>
							<span class="rating_star_generic rating_star"></span>
						<?php endfor; ?>
						<?php if ((round($review->rating) - $review->rating) > 0): ?>
							<span class="rating_star_generic rating_star_half"></span>
						<?php endif; ?>
					<?php endif; ?>
				</span>
			</div>
		</div>
	</li>
<?php endforeach; ?>

<?php if($this->total_page > 1): ?>
	<li>
		<div id="review_view_more" onclick="viewMoreReview()" class="sitepage_sidebar_list_seeall">
			<?php echo $this->htmlLink('javascript:void(0);', $this->translate('More &raquo;'), array(
				'id' => 'review_feed_viewmore_link'
			)) ?>
		</div>

		<div id="review_loding_image" style="display:none;" class="sitepage_sidebar_list_seeall">
			<img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' alt="" />
			<?php echo $this->translate("Loading...") ?>
		</div>
	</li>
<?php endif?>

<?php if(empty($this->is_ajax)):?>
	</ul>
<?php endif; ?>