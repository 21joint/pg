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
<?php if($this->paginator->getTotalItemCount()):?>
  <form id='filter_form_page' class='global_form_box' method='get' action='<?php echo $this->url(array(), 'sitepageoffer_browse', true) ?>' style='display: none;'>
    <input type="hidden" id="page" name="page"  value=""/>
  </form>
	<div class='layout_middle'>
	
		<ul class="seaocore_browse_list">
			<?php foreach ($this->paginator as $sitepage): ?>
				<li>
					<div class="seaocore_browse_list_photo"> 
          <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id);?>
					<?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
									$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $sitepage->page_id, $layout);?>
          <?php if(!empty($sitepage->photo_id)):?>
						<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $sitepage->owner_id, 'offer_id' =>  $sitepage->offer_id,'tab' => $tab_id,'slug' => $sitepage->getOfferSlug($sitepage->title)), $this->itemPhoto($sitepage, 'thumb.normal'),array('title' => $sitepage->getTitle())) ?>
					<?php else:?>
						<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $sitepage->owner_id, 'offer_id' =>  $sitepage->offer_id,'tab' => $tab_id,'slug' => $sitepage->getOfferSlug($sitepage->title)), "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/nophoto_offer_thumb_normal.png' alt='' />",array('title' => $sitepage->getTitle())) ?>
					<?php endif;?>
						</div>
					<div class='seaocore_browse_list_info'>
						<div class='seaocore_browse_list_info_title'>
             <span>
              <?php if (!empty($sitepage->hotoffer)):?>
								<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/images/icons/hot-offer.png', '', array('class' => 'icon', 'title' => $this->translate('Hot Offer'))) ?>
              <?php endif;?>
              <?php if (($sitepage->price>0)): ?>
								<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/images/sponsored.png', '', array('class' => 'icon', 'title' => $this->translate('Sponsored'))) ?>
						<?php endif; ?>
						<?php if ($sitepage->sticky == 1): ?>
							<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/images/sitepage_goldmedal1.gif', '', array('class' => 'icon', 'title' => $this->translate('Featured'))) ?>
						<?php endif; ?>
              </span>
              <h3><?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $sitepage->owner_id, 'offer_id' =>  $sitepage->offer_id,'tab' => $tab_id,'slug' => $sitepage->getOfferSlug($sitepage->title)), $sitepage->title,array('title' => $sitepage->title)); ?></h3>
						</div>
						<div class="seaocore_browse_list_info_date">
							<?php $item = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id); ?>
							<?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepage->page_id, $sitepage->owner_id, $item->getSlug()),  $sitepage->sitepage_title) ?>
						</div>

						<div class="seaocore_browse_list_info_date">
							<span><?php echo $this->translate('End date:');?></span>
							<?php if($sitepage->end_settings == 1):?><span><?php echo $this->translate( gmdate('M d, Y', strtotime($sitepage->end_time))) ?></span><?php else:?><span><?php echo $this->translate('Never Expires');?></span><?php endif;?>
								<?php $today = date("Y-m-d H:i:s"); ?>
								<?php $claim_value = Engine_Api::_()->getDbTable('claims','sitepageoffer')->getClaimValue($this->viewer_id,$sitepage->offer_id,$sitepage->page_id);?>
						</div>
            <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>
						<?php if($sitepage->claim_count == -1 && ($sitepage->end_time > $today || $sitepage->end_settings == 0)):?>
							<?php $show_offer_claim = 1;?>
						<?php elseif($sitepage->claim_count > 0 && ($sitepage->end_time > $today || $sitepage->end_settings == 0)):?>
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
										<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $sitepage->offer_id),$this->translate('Get Offer'),array('class' => 'smoothbox'));
										?>
								  <?php else:?>
										<?php 
										$offer_tabinformation = $this->url(array( 'action' => 'getoffer', 'id' => $sitepage->offer_id,'param' => $param,'request_url'=>$request_url['1']), 'sitepageoffer_general')."?"."return_url=".$return_url.$_SERVER['HTTP_HOST'].$currentUrl;
										$title = $this->translate('Get Offer');
										echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'."<a href=$offer_tabinformation>$title</a>";
										?>
								  <?php endif;?>
								</span>	
							<?php elseif(!empty($claim_value) && !empty($show_offer_claim) || ($sitepage->claim_count == 0 && $sitepage->end_time > $today && !empty($claim_value))):?>
								<span>
									<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $sitepage->offer_id),Zend_Registry::get('Zend_Translate')->_('Resend Offer'),array('class' => 'smoothbox'));?>
								</span>	
							<?php else:?>
								<span>
									<b><?php echo $this->translate('Expired');?></b>
								</span>	
							<?php endif;?>
							<?php echo '<span><b>&middot;</b></span><span>' .$sitepage->claimed.' '.$this->translate('claimed') .'</span>'; ?>
              <?php if($sitepage->claim_count != -1):?>
              	<span><b>&middot;</b></span>
              	<span>
									<?php echo $sitepage->claim_count.' '.$this->translate('claims left') ?>
								</span>	
              <?php endif;?>
						</div> 
            <?php endif; ?>  
            <?php $description = strip_tags($sitepage->description);?>
            <?php   if (!empty($description)):?>
							<?php $truncate_description = ( Engine_String::strlen($description) > 190 ? Engine_String::substr($description, 0, 190) . '...' : $description );?>
              <?php if(Engine_String::strlen($description) > 190):?>
								<?php $truncate_description .= $this->htmlLink($sitepage->getHref(array('tab' => $tab_id)), $this->translate('More &raquo;'));?>
              <?php endif;?>
              <?php echo $truncate_description;?>
            <?php endif;?>
                                            
                                            <?php 
                                             $fieldStructure = Engine_Api::_()->fields()->getFieldsStructurePartial($sitepage);
//                                             $custom_field_values = $this->fieldValueLoop($sitepage, $fieldStructure); ?>
	<?php echo htmlspecialchars_decode($custom_field_values); ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php echo $this->paginationControl($this->paginator, null, array("pagination/pagination.tpl", "sitepageoffer"), array("orderby" => $this->orderby)); ?>
	</div>
<?php else: ?>
	<div class="tip">
		<span>
			<?php echo $this->translate($this->message);?>
		</span>
	</div>
<?php endif;?>

<?php if(!empty($viewer_id)):?>
	<?php date_default_timezone_set($oldTz);?>
<?php endif;?>

<script type="text/javascript">
  var pageAction = function(page){
     var form;
     if($('filter_form')) {
       form=document.getElementById('filter_form');
      }else if($('filter_form_page')){
				form=$('filter_form_page');
			}
    form.elements['page'].value = page;
    
		form.submit();
  } 
</script>