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
<?php
$this->headLink()
        ->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/styles/sitepage-tooltip.css');

include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<?php $oldTz = date_default_timezone_get();?>

<ul class="sitepage_sidebar_list">
  <?php foreach ($this->recentlyview as $sitepage): ?>
    <li>
      <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id);?>
			<?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
						$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $sitepage->page_id, $layout);?>
      <?php if(!empty($sitepage->photo_id)):?>
				<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $sitepage->owner_id, 'offer_id' =>  $sitepage->offer_id,'tab' => $tab_id,'slug' => $sitepage->getOfferSlug($sitepage->title)), $this->itemPhoto($sitepage, 'thumb.icon'),array('title' => $sitepage->getTitle())) ?>
			<?php else:?>
				<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $sitepage->owner_id, 'offer_id' =>  $sitepage->offer_id,'tab' => $tab_id,'slug' => $sitepage->getOfferSlug($sitepage->title)), "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' />",array('title' => $sitepage->getTitle())) ?>
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
        <?php $viewer = Engine_Api::_()->user()->getViewer();?>
        <?php $viewer_id = $viewer->getIdentity();?>
        <?php if(!empty($viewer_id)):?>
					<?php 
					date_default_timezone_set($viewer->timezone);?>
				<?php endif;?>
        <?php $today = date("Y-m-d H:i:s"); ?>
				<?php if($sitepage->claim_count == -1 && ($sitepage->end_time > $today || $sitepage->end_settings == 0)):?>
					<?php $show_offer_claim = 1;?>
				<?php elseif($sitepage->claim_count > 0 && ($sitepage->end_time > $today || $sitepage->end_settings == 0)):?>
					<?php $show_offer_claim = 1;?>
				<?php else:?>
					<?php $show_offer_claim = 0;?>
				<?php endif;?>

        <?php 
          $viewer_id = $viewer->getIdentity();
					$claim_value = Engine_Api::_()->getDbTable('claims','sitepageoffer')->getClaimValue($viewer_id,$sitepage->offer_id,$sitepage->page_id);
        ?>

        <div class="sitepage_sidebar_list_details"> 
          <?php if($this->popularity == 'comment_count'):?>
						<?php echo $this->translate(array('%s comment', '%s comments', $sitepage->comment_count), $this->locale()->toNumber($sitepage->comment_count)) ?>,
            <?php echo $this->translate(array('%s like', '%s likes', $sitepage->like_count), $this->locale()->toNumber($sitepage->like_count)) ?>
          <?php elseif($this->popularity == 'like_count'):?>
						<?php echo $this->translate(array('%s like', '%s likes', $sitepage->like_count), $this->locale()->toNumber($sitepage->like_count)) ?>,
            <?php echo $this->translate(array('%s comment', '%s comments', $sitepage->comment_count), $this->locale()->toNumber($sitepage->comment_count)) ?>
          <?php elseif($this->popularity == 'view_count'):?>
						<?php echo $this->translate(array('%s view', '%s views', $sitepage->view_count), $this->locale()->toNumber($sitepage->view_count)) ?>,
            <?php echo $this->translate(array('%s like', '%s likes', $sitepage->like_count), $this->locale()->toNumber($sitepage->like_count)) ?>
          <?php endif;?>    
				</div>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>
            <div class="sitepage_sidebar_list_details" style="margin-top:3px;">
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
                <b><?php echo $this->translate('Expired');?></b>
              <?php endif;?>
            </div>
        <?php endif; ?>  
<!--				<div class="sitepage_sidebar_list_details">	
					<?php //echo $sitepage->claimed.' '.$this->translate('claimed') ?>
				</div>
				<?php //if($sitepage->claim_count != -1):?>
					<div class="sitepage_sidebar_list_details">	
						<?php //echo $sitepage->claim_count.' '.$this->translate(array('claim left', 'claims left', $sitepage->claim_count ), $this->locale()->toNumber($sitepage->claim_count)) ?>
					</div>	
				<?php //endif;?>-->
      </div>
    </li>
  <?php endforeach; ?>
	<li class="sitepage_sidebar_list_seeall">
    <?php if($this->popularity == 'comment_count'):?>
      <a href='<?php echo $this->url(array('orderby'=> 'comment'), 'sitepageoffer_browse', true) ?>'><?php echo $this->translate('See All');?> &raquo;</a>
    <?php elseif($this->popularity == 'like_count'):?>
      <a href='<?php echo $this->url(array('orderby'=> 'like'), 'sitepageoffer_browse', true) ?>'><?php echo $this->translate('See All');?> &raquo;</a>
    <?php elseif($this->popularity == 'view_count'):?>
      <a href='<?php echo $this->url(array('orderby'=> 'view'), 'sitepageoffer_browse', true) ?>'><?php echo $this->translate('See All');?> &raquo;</a>
    <?php elseif($this->popularity == 'popular'):?>
      <a href='<?php echo $this->url(array('orderby'=> 'popular'), 'sitepageoffer_browse', true) ?>'><?php echo $this->translate('See All');?> &raquo;</a>
    <?php else:?>
      <a href='<?php echo $this->url(array('hotoffer'=> $this->hotOffer), 'sitepageoffer_browse', true) ?>'><?php echo $this->translate('See All');?> &raquo;</a>
    <?php endif;?>
	</li>
</ul>

<?php date_default_timezone_set($oldTz); ?>