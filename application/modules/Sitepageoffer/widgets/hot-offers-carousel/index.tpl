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

<?php
  $this->headLink()
     ->prependStylesheet($this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/styles/sitepage_featured_carousel.css');

	$this->headLink()
  ->appendStylesheet($this->layout()->staticBaseUrl
    . 'application/modules/Sitepageoffer/externals/styles/style_sitepageoffer.css');
     
  $this->headScript()
		->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/scripts/sitepageslideitmoo-1.1_full_source.js');
?>

<a id="group_profile_members_anchor" style="position:absolute;"></a>
<script language="javascript" type="text/javascript">

  var module = 'Sitepageoffer';
</script>
<script language="javascript" type="text/javascript">
  var slideshowoffer;
  window.addEvents ({
    'domready': function() {
      slideshowoffer = new SocialengineSlideItMoo({
        fwdbck_click:1,
        slide_element_limit:1,
        startindex:-1,
        in_one_row:<?php echo  $this->inOneRow_offer?>,
        no_of_row:<?php echo  $this->noOfRow_offer?>,
        curnt_limit:<?php echo $this->totalItemShowoffer;?>,
        category_id:<?php echo $this->category_id;?>,
        total:<?php echo $this->totalCount_offer; ?>,
        limit:<?php echo $this->totalItemShowoffer*2;?>,
        module : 'Sitepageoffer',
        call_count:1,
        foward:'Sitepageoffer_SlideItMoo_forward',
        bck:'Sitepageoffer_SlideItMoo_back',
        overallContainer: 'Sitepageoffer_SlideItMoo_outer',
        elementScrolled: 'Sitepageoffer_SlideItMoo_inner',
        thumbsContainer: 'Sitepageoffer_SlideItMoo_items',
        slideVertical: <?php echo $this->vertical?>,
        itemsVisible:1,
        elemsSlide:1,
        duration:<?php echo  $this->interval;?>,
        itemsSelector: '.Sitepageoffer_SlideItMoo_element',
        itemWidth:<?php echo 146 * $this->inOneRow_offer?>,
        itemHeight:<?php echo 146 * $this->noOfRow_offer?>,
        showControls:1,
        startIndex:1,
        navs:{ /* starting this version, you'll need to put your back/forward navigators in your HTML */
				fwd:'.Sitepageoffer_SlideItMoo_forward', /* forward button CSS selector */
				bk:'.Sitepageoffer_SlideItMoo_back' /* back button CSS selector */
				},
        transition: Fx.Transitions.linear, /* transition */
        onChange: function(index) { slideshowoffer.options.call_count = 1;
        }
      });

      $('Sitepageoffer_SlideItMoo_back').addEvent('click', function () {slideshowoffer.sendajax(-1,slideshowoffer,'Sitepageoffer',"<?php echo $this->url(array('module' => 'sitepageoffer','controller' => 'index','action'=>'hot-offers-carousel'),'default',true); ?>");
        slideshowoffer.options.call_count = 1;

      });

      $('Sitepageoffer_SlideItMoo_forward').addEvent('click', function () { slideshowoffer.sendajax(1,slideshowoffer,'Sitepageoffer',"<?php echo $this->url(array('module' => 'sitepageoffer','controller' => 'index','action'=>'hot-offers-carousel'),'default',true); ?>");
        slideshowoffer.options.call_count = 1;
      });
     
      if((slideshowoffer.options.total -slideshowoffer.options.curnt_limit)<=0){
        // hidding forward button
       document.getElementById('Sitepageoffer_SlideItMoo_forward').style.display= 'none';
       document.getElementById('Sitepageoffer_SlideItMoo_back_disable').style.display= 'none';
      }
    }
  });
</script>

<?php $viewer_id = $this->viewer->getIdentity();?>

<?php
$offerSettings=  array();
$offerSettings['class'] = 'thumb';

?>
<ul class="Sitepagecontent_featured_slider">
  <li>
		<?php
    $module = 'Sitepageoffer';
    $extra_width=0;
    $extra_height=0;    
        if (empty($this->vertical)):
        $typeClass='horizontal';
         if ($this->totalCount_offer > $this->totalItemShowoffer):
          $extra_width = 60;
          endif;
          $prev='back';
          $next='forward';
        else:
        	$typeClass='vertical';
        if ($this->totalCount_offer > $this->totalItemShowoffer):
          $extra_height=50;
          endif;
          $prev='up';
          $next='down';
        endif;
     ?>
    <div id="Sitepageoffer_SlideItMoo_outer" class="Sitepagecontent_SlideItMoo_outer Sitepagecontent_SlideItMoo_outer_<?php echo $typeClass;?>" style="height:<?php echo 146*$this->heightRow+$extra_height;?>px; width:<?php echo (146*$this->inOneRow_offer)+$extra_width;?>px;">
      <div class="Sitepagecontent_SlideItMoo_back" id="Sitepageoffer_SlideItMoo_back" style="display:none;">
				<?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$prev.png", '', array('align'=>'', 'onMouseOver'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$prev.'-active.png";','onMouseOut'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$prev.'.png";', 'border'=>'0')) ?>
      </div>
      <div class="Sitepagecontent_SlideItMoo_back" id="Sitepageoffer_SlideItMoo_back_loding" style="display:none;">
        <?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Seaocore/externals/images/core/loading.gif", '', array('align'=>'', 'border'=>'0','class'=>'Sitepagecontent_SlideItMoo_loding'));  ?>
      </div>      
       <div class="Sitepagecontent_SlideItMoo_back_disable" id="Sitepageoffer_SlideItMoo_back_disable" style="display:block;cursor:default;">
				<?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$prev-disable.png", '', array('align'=>'', 'border'=>'0'));  ?>
      </div>
      <div id="Sitepageoffer_SlideItMoo_inner" class="Sitepagecontent_SlideItMoo_inner">
        <div id="Sitepageoffer_SlideItMoo_items" class="Sitepagecontent_SlideItMoo_items" style="height:<?php echo 146*$this->heightRow;?>px;">
          <div class="Sitepagecontent_SlideItMoo_element Sitepageoffer_SlideItMoo_element" style="width:<?php echo 146*$this->inOneRow_offer;?>px;">
              <div class="Sitepagecontent_SlideItMoo_contentList">
               <?php  $i=0; ?>
                  <?php foreach ($this->hotOffers as $offer):?>
                       <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $offer->page_id);?>
                       <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
												$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $offer->page_id, $layout);?>
                      	<div class="featured_thumb_content">
													<?php if(!empty($offer->photo_id)):?>
														<a class="thumb_img" href="<?php echo $offer->getHref(array('route' => 'sitepageoffer_view', 'user_id' => $offer->owner_id, 'offer_id' =>  $offer->offer_id,'tab' => $tab_id,'slug' => $offer->getOfferSlug($offer->title))); ?>">
																<span style="background-image: url(<?php echo $offer->getPhotoUrl('thumb.normal'); ?>);"></span>
														</a>
													<?php else:?>
														<a class="thumb_img" href="<?php echo $offer->getHref(array( 'page_id' => $offer->page_id, 'offer_id' => $offer->offer_id,'slug' => $offer->getOfferSlug($offer->title), 'tab' => $tab_id)); ?>">
														<span style="background-image: url('<?php echo $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/nophoto_offer_thumb_normal.png" ?>');"></span>
														</a>
													<?php endif;?>
                          <span class="show_content_des">
                            <?php
								              $owner = $offer->getOwner();
								              echo
								                  $this->htmlLink($offer->getHref(array('tab' => $tab_id)), $this->string()->chunk($this->string()->truncate($offer->getTitle(), 45), 10),array('title' => $offer->getTitle()));
														?>
														<?php
														$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.title.truncation', 18);
														$tmpBody = strip_tags($sitepage_object->title);
														$page_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
														?>
														<?php echo $this->translate("in ") . $this->htmlLink(Engine_Api::_()->sitepage()->getHref($offer->page_id, $offer->owner_id, $offer->getSlug()),  $page_title,array('title' => $sitepage_object->title)) ?>
                            <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>  
														<?php if(!empty($viewer_id)):?>
															<?php $oldTz = date_default_timezone_get();?>
															<?php date_default_timezone_set($this->viewer->timezone);?>
														<?php endif;?>
                          <?php $today = date("Y-m-d H:i:s"); ?>
													<?php $claim_value = Engine_Api::_()->getDbTable('claims','sitepageoffer')->getClaimValue($this->viewer_id,$offer->offer_id,$offer->page_id);?>
													<?php if($offer->claim_count == -1 && ($offer->end_time > $today || $offer->end_settings == 0)):?>
														<?php $show_offer_claim = 1;?>
													<?php elseif($offer->claim_count > 0 && ($offer->end_time > $today || $offer->end_settings == 0)):?>
														<?php $show_offer_claim = 1;?>
													<?php else:?>
														<?php $show_offer_claim = 0;?>
													<?php endif;?>
												<div class="sitepage_offer_date seaocore_txt_light" style="margin:3px 0 0;">
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
															<?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" style="margin-top:1px;" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $offer->offer_id),Zend_Registry::get('Zend_Translate')->_('Resend Offer'),array('onclick' => 'owner(this);return false'));?>
														</span>	
													<?php else:?>
														<span>
															<b><?php echo $this->translate('Expired');?></b>
														</span>	
													<?php endif;?>
												</div> 
                        <?php endif; ?>
                       </span>
                      </div>
                   <?php  $i++; ?>
                  <?php endforeach; ?>
                <?php for($i; $i<($this->heightRow *$this->inOneRow_offer);$i++):?>
                <div class="featured_thumb_content"></div>
                <?php endfor; ?>
              </div>
           </div>
        </div>
      </div>
      <?php $module = 'Sitepageoffer';?>
      <div class="Sitepagecontent_SlideItMoo_forward" id ="Sitepageoffer_SlideItMoo_forward">
      	<?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$next.png", '', array('align'=>'', 'onMouseOver'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$next.'-active.png";','onMouseOut'=>'this.src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepage/externals/images/photo/slider-'.$next.'.png";', 'border'=>'0')) ?>
      </div>
      <div class="Sitepagecontent_SlideItMoo_forward" id="Sitepageoffer_SlideItMoo_forward_loding"  style="display: none;">
        <?php echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Seaocore/externals/images/core/loading.gif", '', array('align'=>'', 'border'=>'0','class'=>'Sitepagecontent_SlideItMoo_loding'));  ?>
      </div>
      <div class="Sitepagecontent_SlideItMoo_forward_disable" id="Sitepageoffer_SlideItMoo_forward_disable" style="display:none;cursor:default;">
      	<?php  echo $this->htmlImage($this->layout()->staticBaseUrl . "application/modules/Sitepage/externals/images/photo/slider-$next-disable.png", '', array('align'=>'', 'border'=>'0'));  ?>
      </div>
    </div>
    <div class="clear"></div>
  </li>
</ul>

<?php if(!empty($viewer_id)):?>
	<?php date_default_timezone_set($oldTz);?>
<?php endif;?>

<script type="text/javascript" >
	function owner(thisobj) {
		var Obj_Url = thisobj.href ;
		Smoothbox.open(Obj_Url);
	}
</script>
