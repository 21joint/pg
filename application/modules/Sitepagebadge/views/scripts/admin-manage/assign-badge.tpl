<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: badge.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php if($this->previous_request_status == 3 || $this->previous_request_status == 4):?>
	<div class="sitepage_admin_popup">
		<div>
			<div class="tip">
				<span>
					<?php if($this->previous_request_status == 3): ?>
						<?php echo $this->translate('The admin of this Page has sent a request for Badge assignment. This request has been put by you in PENDING status. You can assign a badge to this Page only after declining or approving that request.'); ?>
					<?php else: ?>
						<?php echo $this->translate('The admin of this Page has sent a request for Badge assignment. This request has been put by you in HOLD status. You can assign a badge to this Page only after declining or approving that request.'); ?>
					<?php endif; ?>
				</span>
		  </div>
			<button onclick='javascript:parent.Smoothbox.close()' class="clear"><?php echo $this->translate("Close") ?></button>
		</div>
	</div>	
<?php elseif(Count($this->badgeData)): ?>
	<div class="settings sitepage_admin_list_badge_popup">
		<form method="post" class="global_form">
			<div>
				<?php if($this->previous_badge_id): ?>
					<h3><?php echo $this->translate('Assign / Remove a Badge');?></h3>
					<p><?php echo $this->translate("Assign a badge to this Page by selecting its radio button. Below, you can also remove the badge which is already assigend to this Page by selecting the radio button corresponding to the last item titled as 'Remove Badge' and then click on 'Assign/Remove Badge' button to save it.");?></p>
				<?php else:?>
					<h3><?php echo $this->translate('Assign a Badge');?></h3>
					<p><?php echo $this->translate('Assign a badge to this Page by selecting its radio button.');?></p>
				<?php endif; ?>

				<ul class="sitepage_admin_list_badge">
					<?php foreach ($this->badgeData as $item):?>
						<?php if($this->previous_badge_id == $item->badge_id): ?>
							<li class="selected">
						<?php else: ?>
							<li>	
						<?php endif; ?>

							<?php
								if(!empty($item->badge_main_id)) {
									$thumb_path = Engine_Api::_()->storage()->get($item->badge_main_id, '')->getPhotoUrl();
									if(!empty($thumb_path)) {
										echo '<center class="sitepage_badge_show_tooltip_wrapper"><img src="'. $thumb_path .'" />';?><?php if(!empty($item->description)): ?><div class="sitepage_badge_show_tooltip"><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagebadge/externals/images/tooltip_arrow_top.png" alt="" /><div><?php echo $item->description; ?></div></div><?php endif; ?></center> <?php ;
									}
								}
							?>

							<div class="badge_name sitepage_badge_show_tooltip_wrapper"><?php echo $item->title;?><?php if(!empty($item->description)): ?><div class="sitepage_badge_show_tooltip"><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagebadge/externals/images/tooltip_arrow_top.png" alt="" /><div><?php echo $item->description; ?></div></div><?php endif; ?></div>
							
							<?php if($this->previous_badge_id == $item->badge_id): ?>
								<input type="radio" name="badge_id" value="<?php echo $item->badge_id?>" checked="checked"/>
							<?php else: ?>
								<input type="radio" name="badge_id" value="<?php echo $item->badge_id?>"/>
							<?php endif; ?>

						</li>
					<?php endforeach; ?>

					<?php if($this->previous_badge_id): ?>
						<li>
							<center class="sitepage_badge_show_tooltip_wrapper">
								<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagebadge/externals/images/remove_badge_icon.png" />
								<div class="sitepage_badge_show_tooltip">
									<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagebadge/externals/images/tooltip_arrow_top.png" alt="" />
									<div>
										<?php echo $this->translate('Please select this radion button to remove badge from this Page.');?>
									</div>
								</div>
							</center> 
									
							<div class="badge_name sitepage_badge_show_tooltip_wrapper">
								<?php echo $this->translate("Remove Badge");?>
								<div class="sitepage_badge_show_tooltip">
									<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagebadge/externals/images/tooltip_arrow_top.png" alt="" />
									<div>
										<?php echo $this->translate('Please select this radion button to remove badge from this Page.');?>
									</div>
								</div>
							</div>
								
							<input type="radio" name="badge_id" value="0"/>
						</li>
					<?php endif; ?>

				</ul>
				<div>
					<p>
						<input type="hidden" name="page_id" value="<?php echo $this->page_id?>"/>
						
						<?php if($this->previous_badge_id): ?>
							<button type='submit'><?php echo $this->translate("Assign/Remove Badge") ?></button>
						<?php else:?>
							<button type='submit'><?php echo $this->translate("Assign Badge") ?></button>
						<?php endif; ?>
						<?php echo $this->translate(" or ") ?> 
						<a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
						<?php echo $this->translate("cancel") ?></a>
					</p>
				</div>
			</div>
		</form>
	</div>
<?php else:?>
	<div class="sitepage_admin_popup">
		<div>
			<div class="tip">
				<span>
					<?php echo $this->translate('You have not created any badges yet. Get started by ').$this->htmlLink(array(
									'route' => 'admin_default', 'module' => 'sitepagebadge', 'controller' => 'manage', 'action' => 'create'
								), $this->translate('creating'), array('target' => '_blank')). $this->translate(" one."); ?>
				</span>
		  </div>
			<button onclick='javascript:parent.Smoothbox.close()' class="clear"><?php echo $this->translate("Close") ?></button>
		</div>
	</div>		
<?php endif;?>

<?php if( @$this->closeSmoothbox ): ?>
<script type="text/javascript">
	TB_close();
</script>
<?php endif; ?>