<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php $viewer_id = $this->viewer->getIdentity();?>
<?php if(!empty($viewer_id)):?>
	<?php $oldTz = date_default_timezone_get();?>
	<?php date_default_timezone_set($this->viewer->timezone);?>
<?php endif;?>


<?php 
  //include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';
?>
<?php 
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';

	$this->headLink()
  ->appendStylesheet($this->layout()->staticBaseUrl
    . 'application/modules/Sitepageoffer/externals/styles/style_sitepageoffer.css')
?>

<div class="sitepage_viewpages_head">
	<?php echo $this->htmlLink($this->sitepage->getHref(), $this->itemPhoto($this->sitepage, 'thumb.icon', '', array('align' => 'left'))) ?>
	<h2>
	  <?php echo $this->sitepage->__toString() ?>
	  <?php echo $this->translate('&raquo;');?>
     <?php echo $this->htmlLink($this->sitepage->getHref(array('tab'=>$this->tab_selected_id)), $this->translate('Offers')) ?>
	  <?php echo $this->translate('&raquo;');?>
	  <?php echo $this->offer->title ?>
	</h2>
</div>

<div class="sitepageoffer_view">
  <!--FACEBOOK LIKE BUTTON START HERE-->
	<?php  $fbmodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('facebookse');
	 if (!empty ($fbmodule)) :
	  $enable_facebookse = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('facebookse'); 
	  if (!empty ($enable_facebookse) && !empty($fbmodule->version)) :
	    $fbversion = $fbmodule->version; 
	    if (!empty($fbversion) && ($fbversion >= '4.1.5')) { ?>
	       <div class="sitepageoffer_fb_like">
	          <script type="text/javascript">
	              var fblike_moduletype = 'sitepageoffer_offer';
	              var fblike_moduletype_id = '<?php echo $this->offer->offer_id ?>';
	           </script>
	          <?php echo Engine_Api::_()->facebookse()->isValidFbLike(); ?>
	        </div>
	    
	    <?php } ?>
   	<?php endif; ?>
   <?php endif; ?>
   
	<div class="sitepage_offer_block" style="margin-bottom:10px;margin-top:10px;">
		<div class="sitepage_offer_photo">
			<?php if(!empty($this->offer->photo_id)):?>
				<?php echo $this->itemPhoto($this->offer, 'thumb.normal'); ?>
			<?php else:?>
				<?php echo "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' />" ?>
			<?php endif;?>
		</div>
		<div class="sitepage_offer_details">
			<div class="sitepage_offer_title">
				<?php echo $this->offer->title;?>
			</div>
			
		  <div class="sitepage_offer_date seaocore_txt_light">
				<span><?php echo $this->translate('End date:'); ?></span>
        <?php if($this->offer->end_settings == 1):?>
					<span><?php echo $this->translate( gmdate('M d, Y', strtotime($this->offer->end_time))) ?></span>
				<?php else:?>
					<span><?php echo $this->translate('Never Expires') ?></span>
				<?php endif;?>
					<?php $today = date("Y-m-d H:i:s");?>
					<?php $claim_value = Engine_Api::_()->getDbTable('claims','sitepageoffer')->getClaimValue($this->viewer_id,$this->offer->offer_id,$this->sitepage->page_id);?>
				<?php if(!empty($this->offer->url)):?><?php echo '| '.$this->translate('URL:');?>
					<a href = "<?php echo "http://".$this->offer->url ?>" target="_blank"><?php echo "http://".$this->offer->url; ?></a>
				<?php endif;?>
			</div>
      <?php if(!empty($this->offer->coupon_code)):?>
				<div class="sitepage_offer_date seaocore_txt_light">
					<?php echo $this->translate('Coupon Code:');?>
					<?php echo $this->offer->coupon_code;?>
				</div>
			<?php endif;?>
		  
		  <div class="sitepage_offer_stats">
		    <?php echo nl2br($this->offer->description);?>
		  </div>
                  <?php $custom_field_values = $this->fieldValueLoop($this->offer, $this->fieldStructure); ?>
	          <?php echo htmlspecialchars_decode($custom_field_values); ?>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>
        <?php if($this->offer->claim_count == -1 && ($this->offer->end_time > $today || $this->offer->end_settings == 0)):?>
          <?php $show_offer_claim = 1;?>
        <?php elseif($this->offer->claim_count > 0 && ($this->offer->end_time > $today || $this->offer->end_settings == 0)):?>
          <?php $show_offer_claim = 1;?>
        <?php else:?>
          <?php $show_offer_claim = 0;?>
        <?php endif;?>

        <div class="sitepage_offer_date seaocore_txt_light">
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
                <?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $this->offer->offer_id),$this->translate('Get Offer'),array('class' => 'smoothbox'));
                ?>
              <?php else:?>
                <?php 
                $offer_tabinformation = $this->url(array( 'action' => 'getoffer', 'id' => $this->offer_id,'param' => $param,'request_url'=>$request_url['1']), 'sitepageoffer_general')."?"."return_url=".$return_url.$_SERVER['HTTP_HOST'].$currentUrl;
                $title = $this->translate('Get Offer');
                echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'."<a href=$offer_tabinformation>$title</a>";
                ?>
              <?php endif;?>
            </span>
          <?php elseif(!empty($claim_value) && !empty($show_offer_claim) || ($this->offer->claim_count == 0 && $this->offer->end_time > $today && !empty($claim_value))):?>
            <span>
              <?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />'.$this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $this->offer->offer_id),Zend_Registry::get('Zend_Translate')->_('Resend Offer'),array('class' => 'smoothbox'));?>
            </span>
          <?php else:?>
            <span>
              <b><?php echo $this->translate('Expired');?></b>
            </span>
          <?php endif;?>
          <?php echo '<span><b>&middot;</b></span><span>' .$this->offer->claimed.' '.$this->translate('claimed') . '</span>'; ?>
          <?php if($this->offer->claim_count != -1):?>
            <span><b>&middot;</b></span>
            <span>
              <?php echo $this->translate(array('%1$s claim left', '%1$s claims left', $this->offer->claim_count), $this->locale()->toNumber($this->offer->claim_count)) ?>
            </span>	
          <?php endif;?>
        </div>
      <?php endif; ?>  
		 </div>
		</div>  

  <?php if(!empty($this->showContent) && is_array($this->showContent)):?>
    <?php $sepHyphen = '';?>
    <div class="sitepageoffer_view_stat seaocore_txt_light">
      <?php if(in_array('postedBy', $this->showContent)): ?> 
        <?php $sepHyphen = '-';?>
        <?php echo $this->translate('Posted');?> <?php echo $this->timestamp($this->offer->creation_date) ?>
      <?php endif; ?>
       <span class="offer_views"><?php if(in_array('commentCount', $this->showContent)): ?><?php echo $sepHyphen; $sepHyphen = '-';?> <?php echo $this->translate(array('%s comment', '%s ', $this->offer->comments()->getCommentCount()),$this->locale()->toNumber($this->offer->comments()->getCommentCount())) ?><?php endif; ?>	<?php if(in_array('viewCount', $this->showContent)): ?><?php echo $sepHyphen; $sepHyphen = '-';?>  
        <?php echo $this->translate(array('%s view', '%s views', $this->offer->view_count ), $this->locale()->toNumber($this->offer->view_count )) ?><?php endif; ?>
       <?php if(in_array('likeCount', $this->showContent)): ?><?php echo $sepHyphen; $sepHyphen = '-';?> <?php echo $this->translate(array('%s like', '%s likes', $this->offer->likes()->getLikeCount()),$this->locale()->toNumber($this->offer->likes()->getLikeCount())) ?><?php endif; ?>
       </span>
    </div>
  <?php endif; ?>
  
  <?php if(!empty($this->showLinks) && is_array($this->showLinks)):?>
    <?php $sepPipe = '';?>
    <div class='sitepageoffer_view_options' style="margin-bottom:15px;">

      <!--  Start: Suggest to Friend link show work -->
      <?php if( !empty($this->offerSuggLink) && in_array('suggest', $this->showLinks)): ?>				<?php $sepPipe = '&nbsp; | &nbsp;';?>
        <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'suggestion', 'controller' => 'index', 'action' => 'popups', 'sugg_id' => $this->offer->offer_id, 'sugg_type' => 'page_offer'), $this->translate('Suggest to Friends'), array(
        'class'=>'buttonlink icon_page_friend_suggestion smoothbox')); ?> 			
      <?php endif; ?>					
      <!--  End: Suggest to Friend link show work -->

      <?php if($this->can_create_offer):?>
          <?php if(in_array('add', $this->showLinks)): ?>
            <?php echo $sepPipe; $sepPipe = '&nbsp; | &nbsp;';?>
            <?php echo $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'create','page_id'=>$this->sitepage->page_id, 'tab'=>$this->tab_selected_id), $this->translate('Add an Offer'), array(
              'class' => 'buttonlink seaocore_icon_create',
        )) ?>
          <?php endif; ?>
          <?php if(in_array('edit', $this->showLinks)): ?>
            <?php echo $sepPipe; $sepPipe = '&nbsp; | &nbsp;';?>
            <?php echo $this->htmlLink(array('route' => 'sitepageoffer_general','action' => 'edit', 'offer_id' => $this->offer->offer_id,'page_id'=>$this->sitepage->page_id,'tab'=>$this->tab_selected_id), $this->translate('Edit Offer'), array(
              'class' => 'buttonlink seaocore_icon_edit'
            )) ?>
          <?php endif; ?>  
          <?php if(in_array('delete', $this->showLinks)): ?>
            <?php echo $sepPipe; $sepPipe = '&nbsp; | &nbsp;';?>
            <?php echo $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'delete','page_id'=>$this->sitepage->page_id,'offer_id'=>$this->offer->offer_id, 'tab'=>$this->identity_temp), $this->translate('Delete Offer'), array(
              'class' => 'buttonlink seaocore_icon_delete',
              )) ?>
          <?php endif; ?>    
          <?php if(in_array('featured', $this->showLinks)): ?>
            <?php echo $sepPipe; $sepPipe = '&nbsp; | &nbsp;';?>
            <?php if($this->offer->sticky == 1):?>
                <?php echo $this->htmlLink(array('route' => 'sitepageoffer_general','action' => 'sticky', 'offer_id' => $this->offer->offer_id,'page_id'=>$this->offer->page_id, 'tab'=>$this->tab_selected_id), $this->translate('Remove as Featured'),array('class' => 'smoothbox buttonlink seaocore_icon_unfeatured')) ?>
            <?php else: ?>
                <?php echo $this->htmlLink(array('route' => 'sitepageoffer_general','action' => 'sticky', 'offer_id' => $this->offer->offer_id,'page_id'=>$this->offer->page_id, 'tab'=>$this->tab_selected_id), $this->translate('Make Featured'), array('class' => 'smoothbox buttonlink seaocore_icon_featured')
          ) ?>
            <?php endif; ?>
        <?php endif; ?>    
      <?php endif; ?>   

      <?php if($this->allowView && in_array('dayOffer', $this->showLinks)): ?> 
        <?php echo $sepPipe; $sepPipe = '&nbsp; | &nbsp;';?>
        <?php echo $this->htmlLink(array('route' => 'default','module'=> 'sitepageoffer', 'controller'=>'index','action' => 'add-offer-of-day', 'offer_id' => $this->offer->offer_id, 'format' => 'smoothbox'), $this->translate('Make Offer of the Day'), array(
        'class' => 'buttonlink smoothbox item_icon_sitepageoffer_offer'
      )) ?>
        
      <?php endif;?>

        <?php if(in_array('print', $this->showLinks)): ?> 
            <?php echo $sepPipe; $sepPipe = '&nbsp; | &nbsp;';?>
            <?php echo $this->htmlLink(array('route' => 'sitepageoffer_general','action' => 'print', 'offer_id' => $this->offer->offer_id,'page_id'=>$this->offer->page_id), $this->translate('Print Offer'), array('target' => '_blank',' class' => 'buttonlink icon_sitepages_print')) ?>
        <?php endif; ?>
        <?php if(in_array('share', $this->showLinks)): ?>
            <?php echo $sepPipe; $sepPipe = '&nbsp; | &nbsp;';?>
            <?php echo $this->htmlLink(Array('module'=> 'activity', 'controller' => 'index', 'action' => 'share', 'route' => 'default', 'type' => 'sitepageoffer_offer', 'id' => $this->offer->getIdentity(), 'format' => 'smoothbox'), $this->translate("Share"), array('class' => 'smoothbox buttonlink seaocore_icon_share')); ?>
        <?php endif; ?>
        <?php if(in_array('report', $this->showLinks)): ?>  
            <?php echo $sepPipe; $sepPipe = '&nbsp; | &nbsp;';?>
            <?php echo $this->htmlLink(Array('module'=> 'core', 'controller' => 'report', 'action' => 'create', 'route' => 'default', 'subject' =>  $this->offer->getGuid(), 'format' => 'smoothbox'), $this->translate("Report"), array('class' => 'smoothbox buttonlink seaocore_icon_report')); ?>
        <?php endif; ?>
      
    </div>
  <?php endif; ?>
  <?php if($this->commentEnabled): ?>
    <?php 
        include_once APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_listComment.tpl';
    ?>
  <?php endif; ?>
</div>

<?php if(!empty($viewer_id)):?>
	<?php date_default_timezone_set($oldTz);?>
<?php endif;?>

<style type="text/css">
.fb_edge_widget_with_comment {
    position: relative !important;
}
</style>
<script type="text/javascript">

 var share_offer =  '<?php echo $this->share_offer;?>';
 var offer_id =  '<?php echo $this->offer_id;?>';

 if(share_offer != '') {
  var url = en4.core.baseUrl + 'activity/index/share/type/sitepageoffer_offer/id/'+ offer_id +'/format/smoothbox';
  Smoothbox.open(url);
 }

</script>