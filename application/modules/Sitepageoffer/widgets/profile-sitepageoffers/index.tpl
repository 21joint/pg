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
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<?php 
  include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';
?>
<?php if (empty($this->isajax)) : ?>
  <div id="id_<?php echo $this->content_id; ?>">
<?php endif;?>

<script type="text/javascript" >
	function owner(thisobj) { 
		var Obj_Url = thisobj.href ;
		Smoothbox.open(Obj_Url);
	}

	function getPopup( popupPath ) {
		Smoothbox.open(popupPath);
	}
</script>

<?php $viewer_id = $this->viewer->getIdentity();?>
<?php if(!empty($viewer_id)):?>
	<?php $oldTz = date_default_timezone_get();?>
	<?php date_default_timezone_set($this->viewer->timezone);?>
<?php endif;?>


<?php if (!empty($this->show_content)) : ?>
  <?php if($this->showtoptitle == 1):?>
	<div class="layout_simple_head" id="layout_offer" style="display:none;">	
    <?php echo $this->translate($this->sitepage->getTitle());?><?php echo $this->translate("'s Offers");?>
	</div>
	<?php endif; ?>
	<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adofferwidget', 3) && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage)):?>
			<div class="layout_right" id="communityad_offer">

				<?php
					echo $this->content()->renderWidget("communityad.ads", array( "itemCount"=>Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adofferwidget', 3),"loaded_by_ajax"=>1,'widgetId'=>"page_offer")); 			 
				?>
			</div>
	  <div class="layout_middle">
	<?php endif;?>


 	<?php if($this->can_create_offer): ?>
		<div class="seaocore_add">
			<?php echo $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'create','page_id'=>$this->sitepage->page_id, 'tab'=>$this->identity_temp), $this->translate('Add an Offer'), array(
						'class' => 'buttonlink seaocore_icon_create',
			)) ?>
    </div>
	<?php endif; ?>

<?php if( count($this->paginator) > 0 ): ?>
	<ul class="sitepage_profile_list">
		<?php foreach ($this->paginator as $item): ?>
			<?php if($item->sticky == 1):?>
				<li class="sitepageoffer_show">
			<?php else: ?>
				<li>
			<?php endif;?>
			<div class="sitepage_offer_photo">
				<?php if(!empty($item->photo_id)):?>
					<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $item->owner_id, 'offer_id' =>  $item->offer_id,'tab' => $this->identity_temp,'slug' => $item->getOfferSlug($item->title)), $this->itemPhoto($item, 'thumb.icon')) ?>
	      <?php else:?>
					<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $item->owner_id, 'offer_id' =>  $item->offer_id,'tab' => $this->identity_temp,'slug' => $item->getOfferSlug($item->title)), "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' />") ?>
	      <?php endif;?>
	    </div>  
			<div class='sitepage_profile_list_options'>
					<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $item->owner_id, 'offer_id' =>  $item->offer_id,'tab' => $this->identity_temp,'slug' => $item->getOfferSlug($item->title)), $this->translate('View Offer'), array(
							'class' => 'buttonlink item_icon_sitepageoffer_offer'
					)) ?>
					<?php if($this->can_create_offer): ?>
						<?php echo $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'edit','page_id'=>$this->sitepage->page_id,'offer_id'=>$item->offer_id, 'tab'=>$this->identity_temp), $this->translate('Edit Offer'), array(
						'class' => 'buttonlink seaocore_icon_edit'
						)) ?>	
						<?php echo $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'delete','page_id'=>$this->sitepage->page_id,'offer_id'=>$item->offer_id, 'tab'=>$this->identity_temp), $this->translate('Delete Offer'), array(
						'class' => 'buttonlink seaocore_icon_delete',
						)) ?>

					<?php if($item->sticky == 1):?>
						<?php echo $this->htmlLink(array('route' => 'sitepageoffer_general','action' => 'sticky', 'offer_id' => $item->offer_id,'page_id'=>$item->page_id, 'tab'=>$this->identity_temp), $this->translate('Remove as Featured'),array(
							'onclick' => 'owner(this);return false', ' class' => 'buttonlink seaocore_icon_unfeatured')) ?>
					<?php else: ?>
						<?php echo $this->htmlLink(array('route' => 'sitepageoffer_general','action' => 'sticky', 'offer_id' => $item->offer_id,'page_id'=>$item->page_id, 'tab'=>$this->identity_temp), $this->translate('Make Featured'), array(
							'onclick' => 'owner(this);return false',' class' => 'buttonlink seaocore_icon_featured')
						) ?>
					<?php endif; ?>
        <?php endif;?>

        <?php echo $this->htmlLink(array('route' => 'sitepageoffer_general','action' => 'print', 'offer_id' => $item->offer_id,'page_id'=>$item->page_id), $this->translate('Print Offer'), array('target' => '_blank',' class' => 'buttonlink icon_sitepages_print')) ?>
  
				<?php
					if ( !empty($this->is_moduleEnabled) ) {
						Engine_Api::_()->getApi('suggestion', 'sitepage')->deleteSuggestion($this->viewer->getIdentity(), 'page_offer', $item->offer_id, 'page_offer_suggestion');
					}
					if( !empty($this->offerSuggLink) ): ?>		
						<?php 
							$link = $this->url(array('module' => 'suggestion', 'controller' => 'index', 'action' => 'popups', 'sugg_id' => $item->offer_id, 'sugg_type' => 'page_offer'), 'default', true);

							echo '<a href="javascript:void(0);" onclick = "getPopup(\'' . $link . '\');" class ="buttonlink icon_page_friend_suggestion">' . $this->translate('Suggest to Friends') . '</a>';
						?>
				<?php endif; ?>
					</div>

				<div class='sitepage_profile_list_info'>
					<div class='sitepage_profile_list_title'>
	        	<?php if (!empty($item->hotoffer)):?>
							<span>
								<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/images/icons/hot-offer.png', '', array('class' => 'icon', 'title' => $this->translate('Hot Offer'))) ?>
							</span>
						<?php endif; ?>
						<div class="list_title">
							<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $item->owner_id, 'offer_id' =>  $item->offer_id,'tab' => $this->identity_temp,'slug' => $item->getOfferSlug($item->title)), $item->title,array('title' => $item->title)) ?>
						</div>
					</div>
					<div class="sitepage_offer_date seaocore_txt_light">
						<span><?php echo $this->translate('End date:'); ?></span>
						<?php if($item->end_settings == 1):?>
						  <span><?php echo $this->translate( gmdate('M d, Y', strtotime($item->end_time))) ?></span>
						<?php else:?>
						  <span><?php echo $this->translate('Never Expires') ?></span>
						<?php endif;?>
            <?php if(!empty($item->url)):?><?php echo '| '.$this->translate('URL:');?>
							<a href = "<?php echo "http://".$item->url ?>" target="_blank" title="<?php echo "http://".$item->url ?>"><?php echo "http://".$item->truncate20Url(); ?></a>
            <?php endif;?>
					</div>
          <?php if(!empty($item->coupon_code)):?>
						<div class="sitepage_offer_date seaocore_txt_light">
							<?php echo $this->translate('Coupon Code:');?>
							<?php echo $item->coupon_code;?>
					  </div>
			    <?php endif;?>
          <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>  
					<div class="sitepage_offer_date seaocore_txt_light">
            <?php $today = date("Y-m-d H:i:s"); ?>
            <?php $claim_value = Engine_Api::_()->getDbTable('claims','sitepageoffer')->getClaimValue($this->viewer_id,$item->offer_id,$item->page_id);?>

            <?php if($item->claim_count == -1 && ($item->end_time > $today || $item->end_settings == 0)):?>
              <?php $show_offer_claim = 1;?>
            <?php elseif($item->claim_count > 0 && ($item->end_time > $today || $item->end_settings == 0)):?>
              <?php $show_offer_claim = 1;?>
            <?php else:?>
              <?php $show_offer_claim = 0;?>
            <?php endif;?>

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
									<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $item->offer_id),$this->translate('Get Offer'),array('onclick' => 'owner(this);return false'));
									?>
								<?php else:?>
									<?php 
									$offer_tabinformation = $this->url(array( 'action' => 'getoffer', 'id' => $item->offer_id,'param' => $param,'request_url'=>$request_url['1']), 'sitepageoffer_general')."?"."return_url=".$return_url.$_SERVER['HTTP_HOST'].$currentUrl;
									$title = $this->translate('Get Offer');
									echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'."<a href=$offer_tabinformation>$title</a>";
									?>
								<?php endif;?>
							</span>	
						<?php elseif(!empty($claim_value) && !empty($show_offer_claim) || ($item->claim_count == 0 && $item->end_time > $today && !empty($claim_value))):?>
							<span>
								<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $item->offer_id),Zend_Registry::get('Zend_Translate')->_('Resend Offer'),array('onclick' => 'owner(this);return false'
								));?>
							</span>
            <?php else:?>
              <span>
              	<b><?php echo $this->translate('Expired');?></b>
              </span>
            <?php endif;?>
						<?php echo '<span><b>&middot;</b></span><span>' .$item->claimed.' '.$this->translate('claimed') . '</span>'; ?>
            <?php if($item->claim_count != -1):?>
            	<span><b>&middot;</b></span>
            	<span>
                <?php echo $this->translate(array('%1$s claim left', '%1$s claims left', $item->claim_count), $this->locale()->toNumber($item->claim_count)) ?>
							</span>	
            <?php endif;?>
			    </div>	
          <?php $today = date("Y-m-d H:i:s");?>
					<?php if($item->end_settings == 1 && ($item->end_time < $today)):?><br />
						<div class="tip" id='sitepagenoffer_search'>
							<span>
								<?php echo $this->translate('This offer has expired.');?>
								<?php if($this->can_create_offer): ?>
									<?php echo $this->translate('If you want this offer to be displayed again, then please %1$sedit it%2$s to change its expiry date.', '<a href="'.$this->url(array('action' => 'edit','page_id'=>$this->sitepage->page_id,'offer_id'=>$item->offer_id,'tab'=>$this->identity_temp), 'sitepageoffer_general').'">', '</a>'); ?>
								<?php endif;?>
							</span>
						</div> 
					<?php endif;?>
          <?php endif;?>
				</div>
			</li>
		<?php  endforeach; ?>
	</ul>
	<?php if( $this->paginator->count() > 1 ): ?>
    <div>
      <?php if( $this->paginator->getCurrentPageNumber() > 1 ): ?>
        <div id="user_sitepage_members_previous" class="paginator_previous">
          <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
            'onclick' => 'paginateSitepageOffers(sitepageOfferPage - 1)',
            'class' => 'buttonlink icon_previous'
          )); ?>
        </div>
      <?php endif; ?>
      <?php if( $this->paginator->getCurrentPageNumber() < $this->paginator->count() ): ?>
        <div id="user_sitepage_members_next" class="paginator_next">
          <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next') , array(
            'onclick' => 'paginateSitepageOffers(sitepageOfferPage + 1)',
            'class' => 'buttonlink_right icon_next'
          )); ?>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
<?php else:?>
<div class="tip" id='sitepageoffer_search'>
                <span>
                        <?php echo $this->translate('No offers have been created in this Page yet.'); ?>
                        <?php if($this->can_create_offer): ?>
                                <?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action' => 'create','page_id'=>$this->sitepage->page_id,'tab'=>$this->identity_temp), 'sitepageoffer_general').'">', '</a>'); ?>
                        <?php endif; ?>
                </span>
        </div>

<?php endif;?>


	<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adofferwidget', 3) && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage)):?>
		</div>
	<?php endif; ?>
<?php endif;?>

<?php if (empty($this->isajax)) : ?>
	</div>
<?php endif;?>

<?php if(!empty($viewer_id)):?>
	<?php date_default_timezone_set($oldTz);?>
<?php endif;?>

<script type="text/javascript">
  var adwithoutpackage = '<?php echo Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage) ?>';
	var offer_ads_display = '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adofferwidget', 3);?>';
	var is_ajax_divhide = '<?php echo $this->isajax;?>';
	var execute_Request_Offer = '<?php echo $this->show_content;?>';
	var sitepageOfferPage = <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber()) ?>;
	var show_widgets = '<?php echo $this->widgets ?>'; 
	var page_communityad_integration = '<?php echo $page_communityad_integration; ?>';
  //window.addEvent('domready', function () {
    var OffertabId = '<?php echo $this->module_tabid;?>';
    var OfferTabIdCurrent = '<?php echo $this->identity_temp; ?>';
    if (OfferTabIdCurrent == OffertabId) {
    	if(page_showtitle != 0) {
    		if($('profile_status') && show_widgets == 1) {
				  $('profile_status').innerHTML = "<h2><?php echo $this->string()->escapeJavascript($this->sitepage->getTitle())?><?php echo $this->translate(' &raquo; ');?><?php echo $this->translate('Offers');?></h2>";	
    		}
    		if($('layout_offer')) {
				  $('layout_offer').style.display = 'block';
				}
    	}  
      hideWidgetsForModule('sitepageoffer');
   	  prev_tab_id = '<?php echo $this->content_id; ?>'; 
   	  prev_tab_class = 'layout_sitepageoffer_profile_sitepageoffers';
   	  execute_Request_Offer = true;
   	  hideLeftContainer (offer_ads_display, page_communityad_integration, adwithoutpackage);	  
    }
    else if (is_ajax_divhide != 1) {  	
  		if($('global_content').getElement('.layout_sitepageoffer_profile_sitepageoffers')) {
				$('global_content').getElement('.layout_sitepageoffer_profile_sitepageoffers').style.display = 'none';
		  } 	
		} 
  //});

	$$('.tab_<?php echo $this->identity_temp; ?>').addEvent('click', function() {
		$('global_content').getElement('.layout_sitepageoffer_profile_sitepageoffers').style.display = 'block';
  	if(page_showtitle != 0) {
  		if($('profile_status') && show_widgets == 1) {
			  $('profile_status').innerHTML = "<h2><?php echo $this->string()->escapeJavascript($this->sitepage->getTitle())?><?php echo $this->translate(' &raquo; ');?><?php echo $this->translate('Offers');?></h2>";	
  		}
  	} 	
    hideWidgetsForModule('sitepageoffer');
		$('id_' + <?php echo $this->content_id ?>).style.display = "block";
    if ($('id_' + prev_tab_id) != null && prev_tab_id != 0 && prev_tab_id != '<?php echo $this->content_id; ?>') {
      $$('.'+ prev_tab_class).setStyle('display', 'none');
    }
		
		if (prev_tab_id != '<?php echo $this->content_id; ?>') {
			execute_Request_Offer = false;
			prev_tab_id = '<?php echo $this->content_id; ?>';			
		  prev_tab_class = 'layout_sitepageoffer_profile_sitepageoffers';    		
		}
		
		if(execute_Request_Offer == false) {
			ShowContent('<?php echo $this->content_id; ?>', execute_Request_Offer, '<?php echo $this->identity_temp?>', 'offer', 'sitepageoffer', 'profile-sitepageoffers', page_showtitle, 'null', offer_ads_display, page_communityad_integration, adwithoutpackage);
			execute_Request_Offer = true;    		
		}

		if('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1);?>' && offer_ads_display == 0)
{setLeftLayoutForPage();}	 
	});
  var paginateSitepageOffers = function(page) {

  var url = en4.core.baseUrl + 'widget/index/mod/sitepageoffer/name/profile-sitepageoffers';
    en4.core.request.send(new Request.HTML({
      'url' : url,
      'data' : {
        'format' : 'html',
        'subject' : en4.core.subject.guid,
        'page' : page,
         'isajax' : '1',
         'tab' : '<?php echo $this->content_id ?>'
      }
    }), {
      'element' : $('id_' + <?php echo $this->content_id ?>)
    });
  }
</script>