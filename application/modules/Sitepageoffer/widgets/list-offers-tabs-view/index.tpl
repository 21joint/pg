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
?>
<?php if(empty($this->is_ajax)): ?>
<div class="layout_core_container_tabs">
<div class="tabs_alt tabs_parent">
  <ul id="main_tabs">
    <?php foreach ($this->tabs as $tab): ?>
    <?php $class = $tab->name == $this->activTab->name ? 'active' : '' ?>
      <li class = '<?php echo $class ?>'  id = '<?php echo 'sitepageoffer_' . $tab->name.'_tab' ?>'>
        <a href='javascript:void(0);'  onclick="tabSwitchSitepageoffer('<?php echo$tab->name; ?>');"><?php echo $this->translate($tab->getTitle()) ?></a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
<div id="hideResponse_div" style="display: none;"></div>
<div id="sitepagelbum_offers_tabs">   
   <?php endif; ?>
   <?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
      <?php if($this->is_ajax !=2): ?>
     <ul class="seaocore_browse_list" id="sitepageoffer_list_tab_offer_content">
       <?php endif; ?>
      <?php foreach( $this->paginator as $offer ): ?>

        <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $offer->page_id);?>
        <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
						$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $offer->page_id, $layout);?>
        <li>
					<div class="seaocore_browse_list_photo">
						<?php if(!empty($offer->photo_id)):?>
							<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $offer->owner_id, 'offer_id' =>  $offer->offer_id,'tab' => $tab_id,'slug' => $offer->getOfferSlug($offer->title)), $this->itemPhoto($offer, 'thumb.normal'),array('title' => $offer->getTitle())) ?>
						<?php else:?>
							<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $offer->owner_id, 'offer_id' =>  $offer->offer_id,'tab' => $tab_id,'slug' => $offer->getOfferSlug($offer->title)), "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/nophoto_offer_thumb_normal.png' alt='' />",array('title' => $offer->getTitle())) ?>
						<?php endif;?>
					</div>
					<div class="seaocore_browse_list_info">
						<div class="seaocore_browse_list_info_title">
							<div class="seaocore_title">
								<?php echo $this->htmlLink($offer->getHref(array('tab' => $tab_id)), $this->string()->chunk($this->string()->truncate($offer->getTitle(), 45), 10),array('title' => $offer->getTitle()));?>
							</div>
            </div>
						<div class="seaocore_browse_list_info_date">
							<?php
							$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
							$tmpBody = strip_tags($sitepage_object->title);
							$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
							?>
							<?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($offer->page_id, $offer->owner_id, $offer->getSlug()),  $page_title,array('title' => $sitepage_object->title)) ?>      
						</div>
						<div class="seaocore_browse_list_info_date">
							<?php if( $this->activTab->name == 'viewed_pageoffers' ): ?>
								<?php echo $this->translate(array('%s view', '%s views', $offer->view_count), $this->locale()->toNumber($offer->view_count)) ?>
							<?php elseif( $this->activTab->name == 'commented_pageoffers' ): ?>
								<?php echo $this->translate(array('%s comment', '%s comments', $offer->comment_count), $this->locale()->toNumber($offer->comment_count)) ?>
							<?php elseif( $this->activTab->name == 'liked_pageoffers' ): ?>
								<?php echo $this->translate(array('%s like', '%s likes', $offer->like_count), $this->locale()->toNumber($offer->like_count)) ?>
							<?php endif; ?>
						</div>
						<div class="seaocore_browse_list_info_date">
							<span><?php echo $this->translate('End date:');?></span>
							<?php if($offer->end_settings == 1):?><span><?php echo $this->translate( gmdate('M d, Y', strtotime($offer->end_time))) ?></span><?php else:?><span><?php echo $this->translate('Never Expires');?></span><?php endif;?>
							<?php $today = date("Y-m-d H:i:s"); ?>
						</div>
              
            <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>  
						<?php $claim_value = Engine_Api::_()->getDbTable('claims','sitepageoffer')->getClaimValue($this->viewer_id,$offer->offer_id,$offer->page_id);?>
						<?php if($offer->claim_count == -1 && ($offer->end_time > $today || $offer->end_settings == 0)):?>
							<?php $show_offer_claim = 1;?>
						<?php elseif($offer->claim_count > 0 && ($offer->end_time > $today || $offer->end_settings == 0)):?>
							<?php $show_offer_claim = 1;?>
						<?php else:?>
							<?php $show_offer_claim = 0;?>
						<?php endif;?>
  

						<div class="sitepage_offer_date seaocore_txt_light" style="margin-top:2px;">
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
										<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $offer->offer_id),$this->translate('Get Offer'),array('onclick' => 'owner(this);return false'));
										?>
									<?php else:?>
										<?php 
										$offer_tabinformation = $this->url(array( 'action' => 'getoffer', 'id' => $offer->offer_id,'param' => $param,'request_url'=>$request_url['1']), 'sitepageoffer_general')."?"."return_url=".$return_url.$_SERVER['HTTP_HOST'].$currentUrl;
										$title = $this->translate('Get Offer');
										echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'."<a href=$offer_tabinformation>$title</a>";
										?>
									<?php endif;?>
								</span>	
							<?php elseif(!empty($claim_value) && !empty($show_offer_claim) || ($offer->claim_count == 0 && $offer->end_time > $today && !empty($claim_value))):?>
								<span>
									<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $offer->offer_id),Zend_Registry::get('Zend_Translate')->_('Resend Offer'),array('onclick' => 'owner(this);return false'));?>
								</span>	
							<?php else:?>
								<span>
									<b><?php echo $this->translate('Expired');?></b>
								</span>	
							<?php endif;?>
							<?php echo '<span><b>&middot;</b></span><span>' .$offer->claimed.' '.$this->translate('claimed') .'</span>'; ?>
              <?php if($offer->claim_count != -1):?>
              	<span><b>&middot;</b></span>
              	<span>
									<?php echo $offer->claim_count.' '.$this->translate('claims left') ?>
								</span>	
              <?php endif;?>
						</div>
            <?php endif; ?>  
            <?php $description = strip_tags($offer->description);?>
            <?php   if (!empty($description)):?>
							<?php $truncate_description = ( Engine_String::strlen($description) > 110 ? Engine_String::substr($description, 0, 110) . '...' : $description );?>
              <?php if(Engine_String::strlen($description) > 110):?>
								<?php $truncate_description .= $this->htmlLink($offer->getHref(array('tab' => $tab_id)), $this->translate('More &raquo;'));?>
              <?php endif;?>
              <?php echo $truncate_description;?>
            <?php endif;?>
            <?php 
                $fieldStructure = Engine_Api::_()->fields()->getFieldsStructurePartial($offer);
                $custom_field_values = $this->fieldValueLoop($offer, $fieldStructure); 
            ?>
            <?php echo htmlspecialchars_decode($custom_field_values); ?>
					</div>
        </li>
      <?php endforeach;?>
       <?php if($this->is_ajax !=2): ?>  
    </ul>  
      <?php endif; ?>
  <?php else: ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('No offers have been created yet.');?>
      </span>
    </div>
  <?php endif; ?>   
<?php if(empty($this->is_ajax)): ?>    
</div>
<?php if (!empty($this->showViewMore)): ?>
<div class="seaocore_view_more" id="sitepageoffer_offers_tabs_view_more" onclick="viewMoreTabOffer()">
  <?php
  echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array(
      'id' => 'feed_viewmore_link',
      'class' => 'buttonlink icon_viewmore'
  ))
  ?>
</div>
<div class="seaocore_loading" id="sitepageoffer_offers_tabs_loding_image" style="display: none;">
  <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' alt="" />
  <?php echo $this->translate("Loading ...") ?>
</div>
<?php endif; ?>
</div>
<?php endif; ?>

<?php if(!empty($viewer_id)):?>
	<?php date_default_timezone_set($oldTz);?>
<?php endif;?>

<?php if(empty($this->is_ajax)): ?>
<script type="text/javascript">
  
  var tabSwitchSitepageoffer = function (tabName) {
 <?php foreach ($this->tabs as $tab): ?>
  if($('<?php echo 'sitepageoffer_'.$tab->name.'_tab' ?>'))
        $('<?php echo 'sitepageoffer_' .$tab->name.'_tab' ?>').erase('class');
  <?php  endforeach; ?>

 if($('sitepageoffer_'+tabName+'_tab'))
        $('sitepageoffer_'+tabName+'_tab').set('class', 'active');
   if($('sitepagelbum_offers_tabs')) {
      $('sitepagelbum_offers_tabs').innerHTML = '<center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepage/externals/images/loader.gif" class="sitepage_tabs_loader_img" /></center>';
    }   
    if($('sitepageoffer_offers_tabs_view_more'))
    $('sitepageoffer_offers_tabs_view_more').style.display =  'none';
    var request = new Request.HTML({
     method : 'post',
      'url' : en4.core.baseUrl + 'widget/index/mod/sitepageoffer/name/list-offers-tabs-view',
      'data' : {
        format : 'html',
        isajax : 1,
        category_id : '<?php echo $this->category_id?>',
        tabName: tabName,
        margin_photo : '<?php echo $this->marginPhoto ?>'
      },
      onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
            $('sitepagelbum_offers_tabs').innerHTML = responseHTML;
            <?php if(!empty ($this->showViewMore)): ?>
              hideViewMoreLinkSitepageOfferOffer();
             <?php endif; ?> 
      }
    });

    request.send();
  }
</script>
<?php endif; ?>
<?php if(!empty ($this->showViewMore)): ?>
<script type="text/javascript">
    en4.core.runonce.add(function() {
    hideViewMoreLinkSitepageOfferOffer();  
    });
    function getNextPageSitepageOfferOffer(){
      return <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
    }
    function hideViewMoreLinkSitepageOfferOffer(){
      if($('sitepageoffer_offers_tabs_view_more'))
        $('sitepageoffer_offers_tabs_view_more').style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>';
    }
        
    function viewMoreTabOffer()
  {
    $('sitepageoffer_offers_tabs_view_more').style.display ='none';
    $('sitepageoffer_offers_tabs_loding_image').style.display ='';
    en4.core.request.send(new Request.HTML({
      method : 'post',
      'url' : en4.core.baseUrl + 'widget/index/mod/sitepageoffer/name/list-offers-tabs-view',
      'data' : {
        format : 'html', 
        isajax : 2,
        category_id : '<?php echo $this->category_id?>',
        tabName : '<?php echo $this->activTab->name ?>',
        margin_photo : '<?php echo $this->marginPhoto ?>',
        page: getNextPageSitepageOfferOffer()
      },
      onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {   
        $('hideResponse_div').innerHTML=responseHTML;     
        console.log($('hideResponse_div').getElement('.layout_sitepageoffer_list_offers_tabs_view'));
        var photocontainer = $('hideResponse_div').getElement('.layout_sitepageoffer_list_offers_tabs_view').innerHTML;alert(photocontainer);
        $('sitepageoffer_list_tab_offer_content').innerHTML = $('sitepageoffer_list_tab_offer_content').innerHTML + photocontainer;
        $('sitepageoffer_offers_tabs_loding_image').style.display ='none';
        $('hideResponse_div').innerHTML="";        
      }
    }));

    return false;

  }  
</script>
<?php endif; ?>

<script type="text/javascript" >
	function owner(thisobj) {
		var Obj_Url = thisobj.href ;
		Smoothbox.open(Obj_Url);
	}
</script>
