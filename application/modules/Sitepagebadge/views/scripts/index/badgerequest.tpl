<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: badgerequest.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">

	function removeBadge(thisobj)
  {
   var Obj_Url = thisobj.href;
   Smoothbox.open(Obj_Url);
  }

	function showHideForm(badgeRequestForm_id)
	{
		var el = document.getElementById(badgeRequestForm_id);
		if ( el.style.display != 'none' ) {
			el.style.display = 'none';
		}
		else {
			el.style.display = '';
		}
	}
</script>

<?php if (empty($this->is_ajax)) : ?>
	<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/payment_navigation_views.tpl'; ?>

	<div class="layout_middle">
		<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/edit_tabs.tpl'; ?>
		<div class="sitepage_edit_content">
			<div class="sitepage_edit_header" style="margin:0px;border:none;"> 
				<a href='<?php echo $this->url(array('page_url' => Engine_Api::_()->sitepage()->getPageUrl($this->page_id)), 'sitepage_entry_view', true) ?>'><?php echo $this->translate('View Page'); ?></a>
				<h3><?php echo $this->translate('Dashboard: '.$this->sitepage->title); ?></h3>
			</div>
	<div id="show_tab_content">
<?php endif; ?>
		
		<?php if(!empty($this->sitepage->badge_id)): ?>
			<div class="sitepage_form mbot15">
				<div>
					<div>
						<h3><?php echo $this->translate("Currently assigned Badge for your Page");?></h3>
						<ul class="sitepage_badgerequest mtop15">
							<li>
								<div class="sitepage_badgerequest_img">
									<?php if($this->sitepagebadges_value == 1 || $this->sitepagebadges_value == 2): ?>
										<?php
											if(!empty($this->sitepagebadge->badge_main_id)) {
												$main_path = Engine_Api::_()->storage()->get($this->sitepagebadge->badge_main_id, '')->getPhotoUrl();
												if(!empty($main_path)) {
													echo '<img src="'. $main_path .'" />';
												}
											}
										?>
									<?php endif; ?>		
								</div>
								<div class="sitepage_badgerequest_detail">
									<?php if(empty($this->sitepagebadges_value) || $this->sitepagebadges_value == 2): ?>
										<b class="title"><?php echo $this->translate($this->sitepagebadge->title);?></b>
									<?php endif; ?>
							
									<?php if(!empty($this->sitepagebadge->description)): ?>
										<p class="des">
											<?php echo $this->translate($this->sitepagebadge->description) ?>
										</p>
									<?php endif; ?>
								</div>	
							</li>
						</ul>
					</div>
				</div>		
			</div>
				<div class="sitepage_badge_buttons">
			<?php if($this->previous_request_status != 3 && $this->previous_request_status != 4 && $this->badgeCount > 1):?>

					<a href="javascript:void(0);" onclick="showHideForm('badgerequest_form_id');"><?php echo $this->translate('Request a different Badge');?></a>

			<?php endif; ?>

			<?php echo $this->htmlLink(array('route' => 'sitepagebadge_remove', 'page_id' => $this->sitepage->page_id), $this->translate('Remove Badge'), array('onclick' => 'removeBadge(this);return false')) ?>
					</div>
		<?php endif; ?>
			
	 	<?php if($this->badgeCount == 0):?>
			<div class="tip">
				<span>
					<?php echo $this->translate('No badges have been added by admin yet.'); ?>
				</span>
			</div>	
		<?php endif; ?>

		<?php if($this->previous_request_status == 3):?>
			<div class="tip">
				<span>
					<?php echo $this->translate('Your request for badge is awaiting admin approval. Current status is: PENDING. You will receive an email when the administrator takes an action on your request.'); ?>
				</span>
			</div>	
		<?php endif; ?>

		<?php if($this->previous_request_status == 4):?>
			<div class="tip">
				<span>
					<?php echo $this->translate('Your request for badge is awaiting admin approval. Current status is: HOLD. You will receive an email when the administrator takes an action on your request.'); ?>
				</span>
			</div>	
		<?php endif; ?>

		<?php if(($this->badgeCount == 0 || $this->previous_request_status == 3 || $this->previous_request_status == 4 || !empty($this->previous_badge_id) || ($this->badgeCount <= 1 && !empty($this->sitepage->badge_id))) && empty($this->form_error)): ?>
			<div id="badgerequest_form_id" style="display:none;">
		<?php else: ?>
			<div id="badgerequest_form_id">
		<?php endif; ?>

			<form id="badge_requst" class="global_form" method="post" action="<?php echo Zend_Controller_Front::getInstance()->getRouter()->assemble(array()) ?>">
				<div>
					<div>
						<h3><?php echo $this->translate("Request a Badge");?></h3>

						<?php $site_title =  Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', 'Advertisement')?>

						<p class="form-description"><?php echo $this->translate("Badges are showcased on your Page Profile and enhance the identity of your Page. The final badge assignment for your Page is done by the administrator of %s. Below, you can request a badge for your Page. Your request will then go to the administrator.",$site_title) ?></p>

						<ul class="form-errors">
						<?php if($this->error_contactno): ?>
							<li><?php echo $this->translate("Your Contact Number") ?><ul class="errors"><li><?php echo $this->translate("Please complete this field - it is required.") ?></li></ul></li>
						<?php endif; ?>

						<?php if($this->error_comment): ?>
							<li><?php echo $this->translate("Comments") ?><ul class="errors"><li><?php echo $this->translate("Please complete this field - it is required.") ?></li></ul></li>
						<?php endif; ?>

						<?php if($this->error_badge_id): ?>
							<li><?php echo $this->translate("Badge") ?><ul class="errors"><li><?php echo $this->translate("Please select radio button for badge - it is required.") ?></li></ul></li>
						<?php endif; ?>

						<?php if($this->error_same_badge): ?>
							<li><ul class="errors"><li><?php echo $this->translate("You can not request for the badge which is already assigned to your page.") ?></li></ul></li>
						<?php endif; ?>

						<?php if($this->previous_request_status == 3): ?>
							<li><ul class="errors"><li><?php echo $this->translate("Your previous badge request is in PENDING status, so you can not send your next request.") ?></li></ul></li>
						<?php elseif($this->previous_request_status == 4): ?>
							<li><ul class="errors"><li><?php echo $this->translate("Your previous badge request is in HOLD status, so you can not send your next request.") ?></li></ul></li>
						<?php endif; ?>
						</ul>

						<div class="form-elements">
							<div id="contactno-wrapper" class="form-wrapper">
								<div id="contactno-label" class="form-label">
									<label for="contactno" class="required"><?php echo $this->translate("Your Contact Number") ?></label>
								</div>

								<div id="contactno-element" class="form-element">
									<input type="text" name="contactno" id="contactno" value="<?php echo $this->posted_value['contactno'] ?>" />
									<p class="description"><?php echo $this->translate("We might need to contact you for confirmation.");?></p>
								</div>
							</div>

							<div id="user_comment-wrapper" class="form-wrapper">
								<div id="user_comment-label" class="form-label">
									<label for="user_comment" class="required"><?php echo $this->translate("Comments") ?></label>
								</div>
								<div id="user_comment-element" class="form-element">
									<textarea name="user_comment" id="user_comment" cols="45" rows="6"><?php echo $this->posted_value['user_comment'] ?></textarea>
								</div>
							</div>
							<?php if(Count($this->badgeData)): ?>
								<div class="form-wrapper">
									<div class="form-label" style="padding-top:0px;">
										<label for="badge"><?php echo $this->translate("Select Badge") ?></label>
									</div>
									<div class="form-element" style="width:450px;">
										<ul class="sitepage_badgerequest">
											<?php foreach ($this->badgeData as $item):?>
												<li>
													<div class="badge_input">
														<?php if($this->previous_badge_id == $item->badge_id || $this->posted_value['badge_id'] == $item->badge_id): ?>
															<input type="radio" name="badge_id" value="<?php echo $item->badge_id?>" checked="checked"/>
														<?php else: ?>
															<input type="radio" name="badge_id" value="<?php echo $item->badge_id?>"/>
														<?php endif; ?>
													</div>	
													<?php if($this->sitepagebadges_value == 1 || $this->sitepagebadges_value == 2): ?>
														<div class="sitepage_badgerequest_img">
															<?php	if(!empty($item->badge_main_id)) {
																	$thumb_path = Engine_Api::_()->storage()->get($item->badge_main_id, '')->getPhotoUrl();
																	if(!empty($thumb_path)) {
																		echo '<img src="'. $thumb_path .'" />';
																	}
																}
															?>
														</div>	
													<?php endif; ?>
													<div class="sitepage_badgerequest_detail">
														<?php if(empty($this->sitepagebadges_value) || $this->sitepagebadges_value == 2): ?>
															<b class="title">
																<?php echo $this->translate($item->title);?>
															</b>
														<?php endif; ?>
														<?php if(!empty($item->description)): ?>
															<p class="des"><?php echo $this->translate($item->description); ?></p>
														<?php endif; ?>
													</div>	
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							<?php endif;?>
							<div id="submit-wrapper" class="form-wrapper">
								<div id="submit-label" class="form-label">&nbsp;</div>
								<div id="submit-element" class="form-element">
									<button name="submit" id="submit" type="submit"><?php echo $this->translate("Send Request") ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
<?php if (empty($this->is_ajax)) : ?>
		  </div>
	  </div>
  </div>
<?php endif; ?>