 <?php
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2010-11-04 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
	$this->headLink()
        ->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/styles/sitepage-tooltip.css');
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>

<?php $viewer_id = $this->viewer->getIdentity();?>
<?php if(!empty($viewer_id)):?>
	<?php $oldTz = date_default_timezone_get();?>
	<?php date_default_timezone_set($this->viewer->timezone);?>
<?php endif;?>

<?php  $id = $this->id; ?>
<?php if(!empty ($this->showViewMore)): ?>
	<script type="text/javascript">
			en4.core.runonce.add(function() {
			hideViewMoreLinkSitepageOffer();  
			});
			function getNextPageSitepageoffer(){
				return <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
			}
			function hideViewMoreLinkSitepageOffer(){
				if($('sitepage_offer_tabs_view_more'))
					$('sitepage_offer_tabs_view_more').style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>';
			}
			
			function viewMoreTabOffers()
			{
			$('sitepage_offer_tabs_view_more').style.display ='none';
			$('sitepage_offers_tabs_loding_image').style.display ='';
			en4.core.request.send(new Request.HTML({
				method : 'post',
				'url' : en4.core.baseUrl + 'widget/index/mod/sitepageoffer/name/sitepage-dateoffer',
				'data' : {
					format : 'html', 
					isajax : 2,
					tab_show : '<?php echo $this->active_tab ?>',
          category_id : '<?php echo $this->category_id ?>',
          itemCount : '<?php  echo $this->totaloffers?>',
					page: getNextPageSitepageoffer()
				},
				onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) { 
				$('hideResponse_div').innerHTML=responseHTML;      
					var offercontainer = $('hideResponse_div').innerHTML;
					$('sitepageoffer_global_content').innerHTML = responseHTML;
					$('sitepage_offers_tabs_loding_image').style.display ='none';
					$('hideResponse_div').innerHTML="";  
					$('sitepage_offers_tabs_loding_image').style.display ='none';
				  $('hideResponse_div').innerHTML="";
				}
			}));

			return false;

		}  
	</script>
<?php endif; ?>

<script type="text/javascript">

	var show_duration_offers = function (module_tab_id, module_active_tab, content_html_id, module_name) {
    if($('sitepage_offer_tabs_view_more'))
    $('sitepage_offer_tabs_view_more').style.display =  'none';
		if (module_active_tab == 1) {
			$('sitepageoffer_offers_tab' + '2').erase('class');
			$('sitepageoffer_offers_tab' + '3').erase('class');
			$('sitepageoffer_offers_tab' + '1').set('class', 'active');
					
		}
		else if (module_active_tab == 2) {
			$('sitepageoffer_offers_tab' + '1').erase('class');
			$('sitepageoffer_offers_tab' + '3').erase('class');
			$('sitepageoffer_offers_tab' + '2').set('class', 'active');
					
		}
					
		else if(module_active_tab == 3) {
			$('sitepageoffer_offers_tab' + '1').erase('class');
			$('sitepageoffer_offers_tab' + '2').erase('class');
			$('sitepageoffer_offers_tab' + '3').set('class', 'active');
					
		}
		if($(content_html_id) != null) {
			$(content_html_id).innerHTML = '<center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif" /></center>';
		}
		
		var request = new Request.HTML({
			'url' : en4.core.baseUrl + 'widget/index/mod/sitepageoffer/name/sitepage-dateoffer',
			'data' : {
				'format' : 'html',
				'isajax' : 1,
        'category_id' : '<?php echo $this->category_id ?>',
				'tab_show' : module_active_tab,
        'itemCount' : '<?php  echo $this->totaloffers?>',
			// 'table' : table_name
			},
			onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
				$(content_html_id).innerHTML = responseHTML;
				<?php if(!empty ($this->showViewMore)): ?>
								hideViewMoreLinkSitepageOffer();
				<?php endif; ?> 
			}
		});

		request.send();
	}
</script>

<?php if (empty($this->ajaxrequest)) : ?>
<ul id="sitepage_offer_dyanamic_code" class="layout_seaocore_sidebar_tabbed_widget">
  <li>
    <div class="seaocore_tabs_alt">
      <ul>
				<?php if ($this->active_tab == 1) {  ?>
					<li class = 'active' id = 'sitepageoffer_offers_tab1' onclick="javascript:show_duration_offers('sitepageoffer_offers_tab' , 1, 'sitepageoffer_global_content', 'sitepageoffer');">
				<?php } else { ?>
					<li class = '' id = 'sitepageoffer_offers_tab1' onclick="javascript:show_duration_offers('sitepageoffer_offers_tab' , 1, 'sitepageoffer_global_content', 'sitepageoffer');">
				<?php }?>
				<?php
						//PRINT FOR LINK
						echo "<a href='javascript:void(0);'>".$this->translate('This Week')."</a>";
				?>
					</li>
				
					<?php if ($this->active_tab == 2) {  ?>
					<li class = 'active' id = 'sitepageoffer_offers_tab2' onclick="javascript:show_duration_offers('sitepageoffer_offers_tab' , 2, 'sitepageoffer_global_content', 'sitepageoffer');">
				<?php } else { ?>
					<li class = '' id = 'sitepageoffer_offers_tab2' onclick="javascript:show_duration_offers('sitepageoffer_offers_tab' , 2, 'sitepageoffer_global_content', 'sitepageoffer');">
				<?php }?>
				<?php
				//PRINT FOR LINK
				echo "<a href='javascript:void(0);'>".$this->translate('This Month')."</a>";
				?>
				</li>
					<?php  if ($this->active_tab == 3) { ?>
					<li class = 'active' id = 'sitepageoffer_offers_tab3' onclick="javascript:show_duration_offers('sitepageoffer_offers_tab' , 3, 'sitepageoffer_global_content', 'sitepageoffer');">
				<?php } else {  ?>
					<li class = '' id = 'sitepageoffer_offers_tab3' onclick="javascript:show_duration_offers('sitepageoffer_offers_tab' , 3, 'sitepageoffer_global_content', 'sitepageoffer');">
				<?php }?>
				<?php
						//ECHO FOR LINK
				echo "<a href='javascript:void(0);'>".$this->translate('Overall')."</a>";

				?>
					</li>
			
      </ul>
    </div>
  </li>
<li id="hideResponse_div" style="display: none;"></li>
<li id="sitepageoffer_global_content">
	<ul>
<?php endif; ?>
  <?php $counter = 1;?>
  <?php if( count($this->paginator) > 0  ) {  ?>
   <?php foreach ($this->paginator as $sitepage): ?>
    <li class="seaocore_sidebar_listing">
    	<div class="seaocore_thumb">
      <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id);?>
			<?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
						$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $sitepage->page_id, $layout);?>
      <?php if(!empty($sitepage->photo_id)):?>
				<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $sitepage->owner_id, 'offer_id' =>  $sitepage->offer_id,'tab' => $tab_id,'slug' => $sitepage->getOfferSlug($sitepage->title)), $this->itemPhoto($sitepage, 'thumb.icon'),array('title' => $sitepage->getTitle())) ?>
			<?php else:?>
				<?php echo $this->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $sitepage->owner_id, 'offer_id' =>  $sitepage->offer_id,'tab' => $tab_id,'slug' => $sitepage->getOfferSlug($sitepage->title)), "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' />",array('title' => $sitepage->getTitle())) ?>
      <?php endif;?>
			</div>
      <div class='seaocore_info'>
				<div class='seaocore_title sitepageoffer_show_tooltip_wrapper'>
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

        <div class='seaocore_stats' style="margin:3px 0;">
          <?php
          $truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.truncation.limit', 13);
          $tmpBody = strip_tags($sitepage->sitepage_title);
          $item_sitepage_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
          ?>
          <?php $item = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id); ?>
          <?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepage->page_id, $sitepage->owner_id, $item->getSlug()), $item_sitepage_title, array('title' => $sitepage->sitepage_title)) ?>
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
				<div class="sitepage_offer_date seaocore_txt_light clr">
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
						<b><?php echo $this->translate('Expired');?></b>
					<?php endif;?>
	      </div>
        <?php endif;?>  
	    </div>  
    </li>
  <?php endforeach; ?>
  
  <?php }
  else
  { ?>
   <li><div class="tip"><span><?php echo $this->translate(' No entry could be found.') ?></span></div></li>
  <?php
  }
?>

<?php if (empty($this->ajaxrequest)) :?>
	</ul>
 </li>
<?php if (!empty($this->showViewMore)): ?>
<li class="seaocore_more">
	<div class="seaocore_sidebar_more_link" id="sitepage_offer_tabs_view_more" >
		<a href="javascript:void(0);" onclick="viewMoreTabOffers()" id="feed_viewmore_link"><?php echo $this->translate('See More');?> &raquo;</a>
		<?php
//		echo $this->htmlLink('javascript:void(0);', $this->translate('See More'), array(
//				'id' => 'feed_viewmore_link'
//		))
		?>
	</div>
	<div class="seaocore_sidebar_more_link" id="sitepage_offers_tabs_loding_image" style="display: none;">
		<img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' alt="" class="seaocore_sidebar_loader_img" />
	</div>
	</li>
<?php endif; ?>
</ul>
<?php endif;?>

<?php if(!empty($viewer_id)):?>
	<?php date_default_timezone_set($oldTz);?>
<?php endif;?>

