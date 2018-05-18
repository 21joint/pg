<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php $viewer_id = $this->viewer->getIdentity();?>
<?php if(!empty($viewer_id)):?>
	<?php $oldTz = date_default_timezone_get();?>
	<?php date_default_timezone_set($this->viewer->timezone);?>
<?php endif;?>

<?php 
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';

	$this->headLink()
  ->appendStylesheet($this->layout()->staticBaseUrl
    . 'application/modules/Sitepageoffer/externals/styles/style_sitepageoffer.css')
?>
<?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
						$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $this->offerOfDay->page_id, $layout);?>
<ul class="generic_list_widget generic_list_widget_large_photo">
	<li>
    <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $this->offerOfDay->page_id);?>
		<div class="photo">
			<?php if(!empty($this->offerOfDay->photo_id)):?>
				<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $this->offerOfDay->owner_id, 'offer_id' =>  $this->offerOfDay->offer_id,'tab' => $tab_id,'slug' => $this->offerOfDay->getOfferSlug($this->offerOfDay->title)), $this->itemPhoto($this->offerOfDay, 'thumb.profile'),array('title' => $this->offerOfDay->getTitle())) ?>
			<?php else:?>
				<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $this->offerOfDay->owner_id, 'offer_id' =>  $this->offerOfDay->offer_id,'tab' => $tab_id,'slug' => $this->offerOfDay->getOfferSlug($this->offerOfDay->title)), "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/nophoto_offer_thumb_profile.png' alt='' />",array('title' => $this->offerOfDay->getTitle())) ?>
			<?php endif;?>
		</div>
		<div class="info">
			<div class="title">
			  <?php echo $this->htmlLink($this->offerOfDay->getHref(array('tab' => $tab_id)), $this->string()->chunk($this->string()->truncate($this->offerOfDay->getTitle(), 45), 10),array('title' => $this->offerOfDay->getTitle())) ?>  
			</div>
	    <div class="owner seaocore_txt_light">
				<?php
				$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
				$tmpBody = strip_tags($sitepage_object->title);
				$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
				?>	
				<?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($this->offerOfDay->page_id, $this->offerOfDay->owner_id, $this->offerOfDay->getSlug()),  $page_title,array('title' => $sitepage_object->title)) ?>
      </div>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>  
			<?php $today = date("Y-m-d H:i:s"); ?>
			<?php $claim_value = Engine_Api::_()->getDbTable('claims','sitepageoffer')->getClaimValue($this->viewer_id,$this->offerOfDay->offer_id,$this->offerOfDay->page_id);?>

			<?php if($this->offerOfDay->claim_count == -1 && ($this->offerOfDay->end_time > $today || $this->offerOfDay->end_settings == 0)):?>
				<?php $show_offer_claim = 1;?>
			<?php elseif($this->offerOfDay->claim_count > 0 && ($this->offerOfDay->end_time > $today || $this->offerOfDay->end_settings == 0)):?>
				<?php $show_offer_claim = 1;?>
			<?php else:?>
				<?php $show_offer_claim = 0;?>
			<?php endif;?>
		</div>
		<div class="sitepage_offer_date seaocore_txt_light" style="margin-top:3px;">
			<?php if(!empty($show_offer_claim) && empty($claim_value)):?>
        <?php $request = Zend_Controller_Front::getInstance()->getRequest();
					$urlO = $request->getRequestUri();
					$request_url = explode('/',$urlO);
					$param = 1;
					if(empty($request_url['2'])) {
					$param = 0;
					}
					$return_url = (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) ? "https://":"http://";
					$currentUrl = urlencode($urlO);
				?>
				<span>
					<?php if(!empty($this->viewer_id)):?>
						<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $this->offerOfDay->offer_id),$this->translate('Get Offer'),array('class' => 'smoothbox'));
						?>
					<?php else:?>
						<?php 
						$offer_tabinformation = $this->url(array( 'action' => 'getoffer', 'id' => $this->offerOfDay->offer_id,'param' => $param,'request_url'=>$request_url['1']), 'sitepageoffer_general')."?"."return_url=".$return_url.$_SERVER['HTTP_HOST'].$currentUrl;
						$title = $this->translate('Get Offer');
						echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'."<a href=$offer_tabinformation>$title</a>";
						?>
					<?php endif;?>
				</span>
			<?php elseif(!empty($claim_value) && !empty($show_offer_claim) || ($this->offerOfDay->claim_count == 0 && $this->offerOfDay->end_time > $today && !empty($claim_value))):?>
				<span>
					<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $this->offerOfDay->offer_id),Zend_Registry::get('Zend_Translate')->_('Resend Offer'),array('class' => 'smoothbox'));?>
				</span>
			<?php else:?>
				<span>
					<b><?php echo $this->translate('Expired');?></b>
				</span>
			<?php endif;?>
		</div>
    <?php endif; ?>  
	</li>
</ul>	

<?php if(!empty($viewer_id)):?>
	<?php date_default_timezone_set($oldTz);?>
<?php endif;?>