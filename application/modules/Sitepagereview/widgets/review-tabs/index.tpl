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
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<?php $photo_review = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.photo', 1);?>
<?php if(empty($this->is_ajax)): ?>
<div class="layout_core_container_tabs">
<div class="tabs_alt tabs_parent">

  <ul id="main_tabs">
   
		<?php if(in_array('recent', $this->visibility)): ?>
			<li id = 'sitepagereview_recent_tab' class = 'active' >
				<a href='javascript:void(0);'  onclick="tabSwitchSitepagereview('recent');"><?php echo $this->translate('Recent') ?></a>
			</li>
		<?php endif; ?>

		<?php if(in_array('popular', $this->visibility) && !in_array('recent', $this->visibility)): ?>
			<li id = 'sitepagereview_popular_tab' class = 'active' >
				<a href='javascript:void(0);'  onclick="tabSwitchSitepagereview('popular');"><?php echo $this->translate('Popular') ?></a>
			</li>
		<?php elseif(in_array('popular', $this->visibility)): ?>
			<li id = 'sitepagereview_popular_tab' >
				<a href='javascript:void(0);'  onclick="tabSwitchSitepagereview('popular');"><?php echo $this->translate('Popular') ?></a>
			</li>
		<?php endif; ?>

		<?php if(in_array('reviewer', $this->visibility) && !in_array('popular', $this->visibility) && !in_array('recent', $this->visibility)): ?>
			<li id = 'sitepagereview_reviewer_tab' class = 'active'>
				<a href='javascript:void(0);'  onclick="tabSwitchSitepagereview('reviewer');"><?php echo $this->translate('Top Reviewers') ?></a>
			</li>
		<?php elseif(in_array('reviewer', $this->visibility)): ?>	
			<li id = 'sitepagereview_reviewer_tab'>
				<a href='javascript:void(0);'  onclick="tabSwitchSitepagereview('reviewer');"><?php echo $this->translate('Top Reviewers') ?></a>
			</li>
		<?php endif; ?>
  </ul>

</div>
<div id="sitepagereview_ajax_tabs">
<?php endif; ?>

<div id="sitelbum_albums_tabs">

  <?php if( Count($this->paginator) > 0 && $this->tabName == 'reviewer'): ?>

		<ul class="seaocore_browse_list">
			<?php foreach( $this->paginator as $user ): ?>
				<li>
					<div class="seaocore_browse_list_photo">
						<?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.profile'), array('class' => 'popularmembers_thumb', 'title' => $user->getTitle())) ?>
					</div>
					<div class='seaocore_browse_list_info'>
						<div class='seaocore_browse_list_info_title'>
							<?php echo $this->htmlLink($user->getHref(), $user->getTitle(), array('title' => $user->getTitle())) ?>
						</div>
						<div class='seaocore_browse_list_info_date'>
							<?php echo $this->translate("No. of Reviews: %d", $user->review_count); ?>
						</div>

						<?php $page = Engine_Api::_()->sitepagereview()->getLinkedPage($user->max_review_id); ?>

						<div class='seaocore_browse_list_info_date'>
							<?php echo $this->translate("Latest on: ").$this->htmlLink(Engine_Api::_()->sitepage()->getHref($page->page_id, $page->owner_id), Engine_Api::_()->sitepage()->truncation($page->title), array('title' => $page->title,'class' => 'bold')); ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php elseif( Count($this->paginator) > 0 && ($this->tabName == 'recent' || $this->tabName == 'popular')): ?>

		<ul class="seaocore_browse_list">
			<?php foreach ($this->paginator as $review): ?>
			  <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $review->page_id);?>
				<?php $user = Engine_Api::_()->getItem('user', $review->owner_id); ?>
		    <li>
          <?php if(!empty($photo_review)):?>
						<div class="seaocore_browse_list_photo">
							<?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.profile'), array('title' => $user->getTitle())) ?>
		        </div>
				  <?php else:?>
						<div class="seaocore_browse_list_photo"><?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepage_object->page_id, $sitepage_object->owner_id, $sitepage_object->getSlug()), $this->itemPhoto($sitepage_object, 'thumb.normal'), array('title' => $sitepage_object->getTitle())); ?></div>
				  <?php endif;?>	
		      <div class='seaocore_browse_list_info'>
		        <div class='seaocore_browse_list_info_title'>
		          <?php
		          $truncation_limit = 60;//Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.truncation.limit', 65);
		          $review_title = Engine_Api::_()->sitepagereview()->truncateText($review->title, $truncation_limit);
		
		          $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
		          $tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $review->page_id, $layout);
		          ?>
		          <?php echo $this->htmlLink($review->getHref(), $review_title, array('title' => $review->title)) ?>
		        </div>
						<span title="<?php echo $review->rating . $this->translate(' rating'); ?>" class="clear">
							<?php if (($review->rating > 0)): ?>
								<?php for ($x = 1; $x <= $review->rating; $x++): ?>
									<span class="rating_star_generic rating_star"></span>
								<?php endfor; ?>
								<?php if ((round($review->rating) - $review->rating) > 0): ?>
									<span class="rating_star_generic rating_star_half"></span>
								<?php endif; ?>
							<?php endif; ?>
						</span> 

						<div class='seaocore_browse_list_info_date'>
							<?php $page_title = Engine_Api::_()->sitepagereview()->truncateText($review->page_title, 60); ?>
							<?php echo $this->translate("on ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($review->page_id, $review->owner_id, $review->getSlug()), $page_title, array('title' => $review->page_title,'class' => 'bold')) ?>
						</div>

		        <div class='seaocore_browse_list_info_date'>
							<?php echo $this->translate(array('%s comment', '%s comments', $review->comment_count), $this->locale()->toNumber($review->comment_count)) ?>,
							<?php echo $this->translate(array('%s view', '%s views', $review->view_count), $this->locale()->toNumber($review->view_count)) ?>,
							<?php echo $this->translate(array('%s like', '%s likes', $review->like_count), $this->locale()->toNumber($review->like_count)) ?>
						</div>
						<div class="seaocore_browse_list_info_blurb">
							<?php echo Engine_Api::_()->sitepagereview()->truncateText($review->body, 125); ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

  <?php else: ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('No reviews have been posted yet.');?>
      </span>
    </div>
  <?php endif; ?>
<?php if(empty($this->is_ajax)): ?>
</div>
<?php endif; ?>


<?php if(empty($this->is_ajax)): ?>
	</div>
	</div>

	<script type="text/javascript">
		
		var tabSwitchSitepagereview = function (tabName) {

		if($('sitepagereview_recent_tab'))
					$('sitepagereview_recent_tab').erase('class');
		if($('sitepagereview_popular_tab'))
					$('sitepagereview_popular_tab').erase('class');
		if($('sitepagereview_reviewer_tab'))
					$('sitepagereview_reviewer_tab').erase('class');
		
	if($('sitepagereview_'+tabName+'_tab'))
					$('sitepagereview_'+tabName+'_tab').set('class', 'active');
		if($('sitepagereview_ajax_tabs')) {
				$('sitepagereview_ajax_tabs').innerHTML = '<center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepage/externals/images/loader.gif" class="sitepage_tabs_loader_img" /></center>';
			}

			var request = new Request.HTML({
			method : 'post',
				'url' : en4.core.baseUrl + 'widget/index/mod/sitepagereview/name/review-tabs',
				'data' : {
					format : 'html',
					isajax : 1,
          category_id : '<?php echo $this->category_id?>',
					tabName: tabName,
					itemCount: '<?php echo $this->itemCount; ?>',
					popularity: '<?php echo $this->popularity; ?>'
				},
				onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) { 
							$('sitepagereview_ajax_tabs').innerHTML = responseHTML;
				}
			});

			request.send();
		}
	</script>
<?php endif; ?>