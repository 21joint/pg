<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: delete.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';?>

<div class="headline">
  <h2> <?php $this->translate('Pages'); ?> </h2>
  <div class="tabs">
    <?php
    // Render the menu
    echo $this->navigation()
            ->menu()
            ->setContainer($this->navigation)
            ->render();
    ?>
  </div>
</div>
<?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adofferlist', 3) && $page_communityad_integration): ?>
  <div class="layout_right" id="communityad_offerlist">

		<?php
			echo $this->content()->renderWidget("communityad.ads", array( "itemCount"=>Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adofferlist', 3),"loaded_by_ajax"=>0,'widgetId'=>"page_offerlist")); 			 
		?>
  </div>
<?php endif; ?>

<?php if($this->paginator->getTotalItemCount()):?>
	<div class='layout_middle'>
		<h3 class="sitepage_mypage_head"><?php echo $this->translate('Offers');?></h3>
		<ul class="seaocore_browse_list">
			<?php foreach ($this->paginator as $sitepage): ?>
				<li>
					<div class="seaocore_browse_list_photo"> 
              <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id);?>
              <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
                    $tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $sitepage->page_id, $layout);?>
							<?php if(!empty($sitepage->photo_id)):?>
								<?php echo $this->htmlLink($sitepage_object->getHref(array('tab'=> $tab_id)), $this->itemPhoto($sitepage, 'thumb.normal', $sitepage->getTitle()), array('title' => $sitepage->getTitle())) ?>   
							<?php else:?>
                <?php echo $this->htmlLink($sitepage_object->getHref(array('tab'=> $tab_id)), "<img src='". $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' />", array('title' => $sitepage->getTitle())) ?>   
							<?php endif;?>
						</div>
					<div class='seaocore_browse_list_info'>
						<div class='seaocore_browse_list_info_title'>
							<h3>   <?php echo $item_title = $this->htmlLink($sitepage_object->getHref(array('tab'=> $tab_id)), $sitepage->title, array('title' => $sitepage->title)); ?></h3>
						</div>
						<div class="seaocore_browse_list_info_date">
							<?php $item = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id); ?>
							<?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepage->page_id, $sitepage->owner_id, $item->getSlug()),  $sitepage->sitepage_title) ?>
						</div>
						<div class="seaocore_browse_list_info_date">
							<span><?php echo $this->translate('End date:');?></span>
							<?php if($sitepage->end_settings == 1):?>
								<span><?php echo $this->translate( gmdate('M d, Y', strtotime($sitepage->end_time))) ?></span>
							<?php else:?>
								<span><?php echo $this->translate('Never Expires');?></span>
							<?php endif;?>
							<?php if(!empty($sitepage->url)):?><?php echo '| ' .$this->translate('URL:');?>
								<a href = "<?php echo "http://".$sitepage->url ?>" target="_blank" title="<?php echo "http://".$sitepage->url ?>"><?php echo "http://".$sitepage->truncate20Url(); ?></a>
							<?php endif;?>
						</div> 
						<?php if(!empty($sitepage->coupon_code)):?>
							<div class="seaocore_browse_list_info_date">
								<?php echo $this->translate('Coupon Code:');?>
								<?php echo $sitepage->coupon_code;?>
							</div>
			      <?php endif;?>
						<div class='seaocore_browse_list_info_blurb'>
							<?php echo $this->viewMore($sitepage->description); ?><br />
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php echo $this->paginationControl($this->paginator); ?>
	</div>
<?php else: ?>
	<div class="tip">
		<span>
			<?php echo $this->translate('No offers available.');?>
		</span>
	</div>
<?php endif;?>
