<?php 
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: document_intrgration.tpl 6590 2010-08-11 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<ul class="sitepage_sidebar_list">
	<?php foreach ($this->paginator as $item): ?>
		<li>
			<?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($item->page_id, $item->owner_id, $item->getSlug()), $this->itemPhoto($item, 'thumb.icon'), array('title' => $item->getTitle())) ?>
			<div class="sitepage_sidebar_list_info">
				<div class="sitepage_sidebar_list_title">
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
				
				<?php if (!empty($this->showContent)) : ?>
					<div class='sitepage_sidebar_list_details'>
					<?php 
						$statistics = '';
						if(in_array('postedDate', $this->showContent)) {
							$statistics .= $this->timestamp(strtotime($item->creation_date)).' | ';
						}
						if(in_array('postedBy', $this->showContent)) {
							$statistics .= $this->translate('posted by ') . $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()).' | ';
						}
						if(in_array('likeCount', $this->showContent)) {
							$statistics .= $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)).' | ';
						}
						if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepagereview') && !empty($item->review_count) && in_array('reviewCreate', $this->showContent) && !empty($this->ratngShow)) {
							$statistics .= $this->translate(array('%s review', '%s reviews', $item->review_count), $this->locale()->toNumber($item->review_count)).' | ';
						}
						if(in_array('commentCount', $this->showContent)) {
							$statistics .= $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)).' | ';
						}
						if(in_array('viewCount', $this->showContent)) {
							$statistics .= $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)).' | ';
						}
						$statistics = trim($statistics);
						$statistics = rtrim($statistics, ' |');
					?>
					<?php echo $statistics; ?>
					</div>
				<?php endif; ?>
			</div>
		</li>
	<?php endforeach; ?>
</ul>
<style>
.layout_sitepageintegration_mixprofile_items {
clear:both;
}
</style>