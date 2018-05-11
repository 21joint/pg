<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: showbadges.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
  include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';
?>
<?php 
	$this->headLink()
  ->appendStylesheet($this->layout()->staticBaseUrl
    . 'application/modules/Sitepagebadge/externals/styles/style_sitepagebadge.css')
?>
<script type="text/javascript">
  var pageAction = function(page){
    $('page').value = page;
    $('filter_form_badge').submit();
  }
  
  var badgeAction = function(badge_id){
    //$('page').value = 1;
    $('badge_id').value = badge_id;
    $('filter_form_badge').submit();
  } 
</script>

<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/payment_navigation_views.tpl'; ?>

<?php echo $this->form->render($this) ?>


<h3><?php echo $this->translate('Badges'); ?></h3>
<p><?php echo $this->translate('Below is the list of badges available on this site. Page owners can send request for badges to get them assigned to their pages for advertising, publicity and branding purposes. Clicking on a badge below will redirect you to the list of pages which have been asssigned that badge.'); ?></p>
<br />	
<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adbadgeview', 3) && $page_communityad_integration):?>
<div class="sitepagebadge_layout_right" id="communityad_badge">
	<?php
		echo $this->content()->renderWidget("communityad.ads", array( "itemCount"=>Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adbadgeview', 3),"loaded_by_ajax"=>1,'widgetId'=>'page_badge')); 			 
	?>
</div>
<div class="layout_middle">
<?php endif;?>
	<div id='photo_image' class="sitepage_badge_browse">
		<?php if(Count($this->badgeData)):?>
			<div class="global_form">
				<div>
					<div>
						<?php $dataCount = 0;?>
						<?php foreach ($this->badgeData as $item):?>
							<?php if($dataCount%2 == 0):?>
								<div class="sitepage_badge_browse_row">
							<?php endif; ?>
								<div class="sitepage_badge_browse_item" style="float:<?php echo $this->cycle(array("left", "right")) ->next()?>">
									<?php if($this->sitepagebadges_value == 1 || $this->sitepagebadges_value == 2): ?>
										<div class="sitepage_badge_browse_photo">
											<?php
												if(!empty($item->badge_main_id)) {
													$thumb_path = Engine_Api::_()->storage()->get($item->badge_main_id, '')->getPhotoUrl();
													if(!empty($thumb_path)) {
														echo '<a href="javascript:void(0);" onclick="javascript:badgeAction('.$item->badge_id.');"><img src="'. $thumb_path .'" /></a>';
													}
												}
											?>
										</div>
									<?php endif; ?>
									<div class="sitepage_badge_browse_detail">
										<?php if(empty($this->sitepagebadges_value) || $this->sitepagebadges_value == 2): ?>
											<div class="sitepage_badge_browse_title">
												<?php echo '<a href="javascript:void(0);" onclick="javascript:badgeAction('.$item->badge_id.');">'. $this->translate($item->title) .'</a>'; ?>
											</div>
										<?php endif; ?>
										
										<?php if(!empty($item->description)): ?>
											<div class="sitepage_badge_browse_description">
												<?php echo $this->translate($item->description); ?>
											</div>
										<?php endif; ?>
									</div>
								</div>	
								<?php $dataCount++;?>	
							<?php if($dataCount%2 == 0):  ?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>		
		<?php else: ?>
			<div class="tip">
				<span>
					<?php echo $this->translate('No Badges has been added by admin at yet.'); ?>
				</span>
		  </div>	
		<?php endif; ?>
	</div>
</div>	
