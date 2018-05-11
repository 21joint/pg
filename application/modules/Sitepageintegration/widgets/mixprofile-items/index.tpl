<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<?php if ($this->resource_type != 'document') : ?>
	<script type="text/javascript">
		en4.core.runonce.add(function(){

			<?php if( !$this->renderOne ): ?>
				var anchor = $('mixprofile_sitepages').getParent();
				$('mixprofile_sitepage_previous').style.display = '<?php echo ( $this->paginator->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>';
				$('mixprofile_sitepage_next').style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>';

				$('mixprofile_sitepage_previous').removeEvents('click').addEvent('click', function(){
					en4.core.request.send(new Request.HTML({
						url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
						data : {
							format : 'html',
							subject : en4.core.subject.guid,
							page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() - 1) ?>,
							is_ajax : 1
						}
					}), {
						'element' : anchor
					})
				});

				$('mixprofile_sitepage_next').removeEvents('click').addEvent('click', function(){
					en4.core.request.send(new Request.HTML({
						url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
						data : {
							format : 'html',
							subject : en4.core.subject.guid,
							page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>,
							is_ajax : 1
						}
					}), {
						'element' : anchor
					})
				});
			<?php endif; ?>
		});
	</script>
	<?php if (count($this->paginator) > 0): ?>
	<ul id="mixprofile_sitepages"  class="sitepages_profile_tab">
			<?php foreach ($this->paginator as $item): ?>
				<li <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.fs.markers', 1)):?><?php if($item->featured):?> class="lists_highlight"<?php endif;?><?php endif;?>>
				<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.fs.markers', 1)):?>
					<?php if($item->featured):?>
						<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/images/featured-label.png', '',  array('title' => 'Featured','class' => 'sitepage_featured_label')) ?>
					<?php endif;?>
				<?php endif;?>
				<div class='sitepages_profile_tab_photo'>
					<?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($item->page_id, $item->owner_id, $item->getSlug()), $this->itemPhoto($item, 'thumb.normal')) ?>
					<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.fs.markers', 1)):?>
						<?php if (!empty($item->sponsored)): ?>
							<?php $sponsored = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.sponsored.image', 1);
							if (!empty($sponsored)) { ?>
								<div class="sitepage_sponsored_label" style='background: <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.sponsored.color', '#fc0505'); ?>;'>
									<?php echo $this->translate('SPONSORED'); ?>                 
								</div>
							<?php } ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
					<div class="sitepages_profile_tab_info">
						<div class="sitepages_profile_tab_title">
								<?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($item->page_id, $item->owner_id, $item->getSlug()),  Engine_Api::_()->seaocore()->seaocoreTruncateText($item->getTitle(), $this->title_truncation), array('title' => $item->getTitle())) ?>
								<div class="fright">
								<?php if ($item->closed): ?>
									<span>
										<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/images/close.png', '', array('class' => 'icon', 'title' => $this->translate('Closed'))) ?>
									</span>
								<?php endif; ?>
								<span>
									<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.fs.markers', 1)) :?>
										<?php if ($item->sponsored == 1): ?>
												<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/images/sponsored.png', '', array('class' => 'icon', 'title' => $this->translate('Sponsored'))) ?>
											<?php endif; ?>
											<?php if ($item->featured == 1): ?>
												<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/images/sitepage_goldmedal1.gif', '', array('class' => 'icon', 'title' => $this->translate('Featured'))) ?>
										<?php endif; ?>
									<?php endif; ?>
								</span>
								<div class="clr"></div>
							</div>
						</div>
						<div class='sitepages_browse_info_date seaocore_txt_light'>
							<?php if (is_array($this->showContent) && in_array('postedDate', $this->showContent)): ?>
								<?php echo $this->timestamp(strtotime($item->creation_date)) ?>&nbsp;&nbsp;&nbsp;
							<?php endif; ?>
							
							<?php if (is_array($this->showContent) && in_array('postedBy', $this->showContent)): ?>
								<?php echo $this->translate('posted by '); ?><?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>&nbsp;&nbsp;&nbsp;
							<?php endif; ?>
							
							<?php if (is_array($this->showContent) && in_array('commentCount', $this->showContent)): ?>
								<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>&nbsp;&nbsp;&nbsp;
							<?php endif; ?>
							
							<?php if (is_array($this->showContent) && in_array('reviewCreate', $this->showContent)): ?>
								<?php $sitepagereviewEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepagereview'); ?>
								<?php if ($sitepagereviewEnabled): ?>
									<?php echo $this->translate(array('%s review', '%s reviews', $item->review_count), $this->locale()->toNumber($item->review_count)) ?>&nbsp;&nbsp;&nbsp;
								<?php endif; ?>
							<?php endif; ?>
							
							<?php if (is_array($this->showContent) && in_array('viewCount', $this->showContent)): ?>
								<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>&nbsp;&nbsp;&nbsp;
							<?php endif; ?>
							
							<?php if (is_array($this->showContent) && in_array('likeCount', $this->showContent)): ?>
								<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>
							<?php endif; ?>
							
						</div>
						<?php if (!empty($item->body)): ?>
							<div class="sitepages_browse_info_blurb">
								<?php echo $this->viewMore($item->body) ?>
							</div>
						<?php elseif (!empty($item->description)): ?>
							<div class="sitepages_browse_info_blurb">
								<?php echo $this->viewMore($item->description) ?>
							</div>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<div>
		<div id="mixprofile_sitepage_previous" class="paginator_previous">
			<?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
				'onclick' => '',
				'class' => 'buttonlink icon_previous'
			)); ?>
		</div>
		<div id="mixprofile_sitepage_next" class="paginator_next">
			<?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
				'onclick' => '',
				'class' => 'buttonlink_right icon_next'
			)); ?>
		</div>
	</div>
	<?php else: ?>
		<div class="tip" id='sitepageintg_search'>
			<span>
				<?php echo $this->translate('No content have been intregrated in this Page yet.'); ?>
			</span>
		</div>
	<?php endif; ?>
<?php else: ?>

	<?php include_once APPLICATION_PATH . '/application/modules/Sitepageintegration/views/scripts/document_integration.tpl'; ?>
	
<?php endif; ?>