<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
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
?>
<ul class="sitepage_sidebar_list">
  <?php foreach ($this->recentlyview as $sitepage): ?>
    <li>
      <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id);?>
			<?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
						$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $sitepage->page_id, $layout);?>
      <?php if(!empty($sitepage->photo_id)):?>
        <?php echo $this->htmlLink($sitepage_object->getHref(array('tab'=> $tab_id)), $this->itemPhoto($sitepage, 'thumb.icon', $sitepage->getTitle()), array('title' => $sitepage->getTitle())) ?>   
      <?php else:?>  
        <?php echo $this->htmlLink($sitepage_object->getHref(array('tab'=> $tab_id)), "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' class='list_thumb' />", array('title' => $sitepage->getTitle())) ?>
      <?php endif;?>
      <div class='sitepage_sidebar_list_info'>
				<div class='sitepage_sidebar_list_title sitepageoffer_show_tooltip_wrapper'>
					<?php echo $item_title = $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $sitepage->owner_id, 'offer_id' =>  $sitepage->offer_id,'tab' => $tab_id,'slug' => $sitepage->getOfferSlug($sitepage->title)), $sitepage->title,array('title' => $sitepage->title)); ?>
					<?php
					$truncation_limit_desc = 500;
					$tmpBody = strip_tags($sitepage->description);
					$item_description = ( Engine_String::strlen($tmpBody) > $truncation_limit_desc ? Engine_String::substr($tmpBody, 0, $truncation_limit_desc) . '..' : $tmpBody );
					?>
					<div class="sitepageoffer_show_tooltip">
						<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepage/externals/images/tooltip_arrow.png" alt="" class="arrow" />
						<?php echo $item_description; ?>
					</div>
				</div>

        <div class='sitepage_sidebar_list_details'>
          <?php
          $truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.truncation.limit', 13);
          $tmpBody = strip_tags($sitepage->sitepage_title);
          $item_sitepage_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
          ?>
          <?php $item = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id); ?>
          <?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepage->page_id, $sitepage->owner_id, $item->getSlug()), $item_sitepage_title, array('title' => $sitepage->sitepage_title)) ?>
        </div>
      </div>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>  
      <?php $today = date("Y-m-d H:i:s"); ?>
			<?php if($sitepage->claim_count == -1 && ($sitepage->end_time > $today || $sitepage->end_settings == 0)):?>
				<?php $show_offer_claim = 1;?>
			<?php elseif($sitepage->claim_count > 0 && ($sitepage->end_time > $today || $sitepage->end_settings == 0)):?>
				<?php $show_offer_claim = 1;?>
			<?php else:?>
				<?php $show_offer_claim = 0;?>
			<?php endif;?>

			<div class="sitepage_offer_date seaocore_txt_light">
        <?php $claim_value = Engine_Api::_()->getDbTable('claims','sitepageoffer')->getClaimValue($this->viewer_id,$sitepage->offer_id,$sitepage->page_id);?>
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
					<?php if(!empty($this->viewer_id)):?>
						<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $sitepage->offer_id),$this->translate('Get Offer'),array('class' => 'smoothbox'));
						?>
					<?php else:?>
						<?php 
						$offer_tabinformation = $this->url(array( 'action' => 'getoffer', 'id' => $sitepage->offer_id,'param' => $param,'request_url'=>$request_url['1']), 'sitepageoffer_general')."?"."return_url=".$return_url.$_SERVER['HTTP_HOST'].$currentUrl;
						$title = $this->translate('Get Offer');
						echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'."<a href=$offer_tabinformation>$title</a>";
						?>
					<?php endif;?>
				<?php elseif(!empty($claim_value) && !empty($show_offer_claim) || ($sitepage->claim_count == 0 && $sitepage->end_time > $today && !empty($claim_value))):?>
					<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $sitepage->offer_id),Zend_Registry::get('Zend_Translate')->_('Resend Offer'),array(
								'class' => 'smoothbox',
					));?>
				<?php else:?>
					<?php echo $this->translate('Expired');?>
				<?php endif;?>
			</div>
			<div class="sitepage_offer_date seaocore_txt_light">
				<?php echo $sitepage->claimed.' '.$this->translate('claimed') ?>
				<?php if($sitepage->claim_count != -1):?>
					<?php echo $sitepage->claim_count.' '.$this->translate(array('claim left', 'claims left', $sitepage->claim_count ), $this->locale()->toNumber($sitepage->claim_count)) ?>
				<?php endif;?>
			</div>
      <?php endif; ?>  
    </li>
  <?php endforeach; ?>
	<li class="sitepage_sidebar_list_seeall">
		<a href='<?php echo $this->url(array('sponsoredoffer'=> 1), 'sitepageoffer_browse', true) ?>'><?php echo $this->translate('See All');?> &raquo;</a>
	</li>
</ul>

<?php if(!empty($viewer_id)):?>
	<?php date_default_timezone_set($oldTz);?>
<?php endif;?>