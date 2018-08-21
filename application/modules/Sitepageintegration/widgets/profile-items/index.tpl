<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 	
	$pieces = explode("_", $this->resource_type);
	if ($this->resource_type == 'document_0' || $this->resource_type == 'quiz_0' || $this->resource_type == 'folder_0') {
		$listingTypeId = $pieces[1];
		$resource_type = $pieces[0];
	} else {
		$listingTypeId = $pieces[2];
		$resource_type = $pieces[0] . '_' . $pieces[1];
	}
?>
<?php
if ($resource_type == 'sitestoreproduct_product') :
  $this->headLink()->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitestoreproduct/externals/styles/style_sitestoreproduct.css');
endif;
?>
<?php 
if ($resource_type == 'sitefaq_faq') :
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/styles/style_sitefaq.css');
endif;
if ($resource_type == 'sitetutorial_tutorial') :
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitetutorial/externals/styles/style_sitetutorial.css');
endif;
?>
<?php
	if ($resource_type == 'sitereview_listing') :
		$this->headLink()->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitereview/externals/styles/style_sitereview.css');
	endif;
?>

<?php 
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
if($resource_type == 'sitebusiness_business') {
	include_once APPLICATION_PATH . '/application/modules/Sitebusiness/views/scripts/common_style_css.tpl';
}
if($resource_type == 'sitegroup_group') {
	include_once APPLICATION_PATH . '/application/modules/Sitegroup/views/scripts/common_style_css.tpl';
} 
?>

<?php $modNameKey = str_replace("_", ".", $this->resource_type); ?>
<?php $adsDisplay = Engine_Api::_()->getApi('settings', 'core')->getSetting("sitepage.ad." .$modNameKey, 3);?>
<?php
if (file_exists(APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl'))
  include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';
?>
<?php if (!empty($this->show_content)) : ?>
	<script type="text/javascript">	
		function showsearchnotecontent () {
			var url = en4.core.baseUrl + 'widget/index/mod/sitepageintegration/name/profile-items'; 
			$('sitepage_intg_search_input_text').addEvent('keypress', function(e) {
				if( e.key != 'enter' ) return;
					if($('sitepageintg_search') != null) {
						$('sitepageintg_search').innerHTML = '<center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagenote/externals/images/spinner_temp.gif" /></center>'; 
					}
					en4.core.request.send(new Request.HTML({
						'url' : url,
						'data' : {
							'format' : 'html',
							'subject' : en4.core.subject.guid,
							'search' : $('sitepage_intg_search_input_text').value,
							'selectbox' : $('sitepage_intg_search_input_selectbox').value,
							'isajax' : '1', 
							'tab' : '<?php echo $this->identity_temp ?>',
							'resource_type' : '<?php echo $this->resource_type  ?>',
							'title_truncation' : '<?php echo $this->title_truncation ?>',
							'show_posted_date' : '<?php echo $this->show_posted_date ?>'
						}
					}), {
					'element' : $('id_' + <?php echo $this->identity_temp ?>)
					});
			});
		}

		function Orderintselect()	{
			var sitepageNotesSearchSelectbox = '<?php echo $this->selectbox ?>';
			var pageNotePage = <?php echo sprintf('%d', $this->contentResults->getCurrentPageNumber()) ?>;
			var url = en4.core.baseUrl + 'widget/index/mod/sitepageintegration/name/profile-items'; 
			if($('sitepageintg_search') != null) {
				$('sitepageintg_search').innerHTML = '<center><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagenote/externals/images/spinner_temp.gif" /></center>'; 
			}  
			en4.core.request.send(new Request.HTML({ 
				'url' : url,
				'data' : {
					'format' : 'html',
					'subject' : en4.core.subject.guid,
						'search' : $('sitepage_intg_search_input_text').value,
						'selectbox' : $('sitepage_intg_search_input_selectbox').value,
						'isajax' : '1', 
						'tab' : '<?php echo $this->identity_temp ?>',
						'resource_type' : '<?php echo $this->resource_type  ?>',
						'title_truncation' : '<?php echo $this->title_truncation ?>',
						'show_posted_date' : '<?php echo $this->show_posted_date ?>'
					}
			}), {
				'element' : $('id_' + <?php echo $this->identity_temp ?>)
			});
		}
	</script>	
<?php endif; ?>

<?php if (empty($this->isajax)) : ?>
  <div id="id_<?php echo $this->identity_temp; ?>">
<?php endif; ?>
<?php if (!empty($this->show_content)) : ?>
<?php

	$Params = Engine_Api::_()->sitepageintegration()->integrationParams($resource_type, $listingTypeId, $this->sitepage->page_id);
	
	if(isset($Params['URL'])) 
		$URL = $Params['URL'];
	if(isset($Params['manage_url'])) 
		$manage_url= $Params['manage_url'];
	if(isset($Params['icon_name'])) 
		$icon_name = $Params['icon_name'];
	if(isset($Params['ul_class'])) 
		$ul_class = $Params['ul_class'];
	if(isset($Params['store_count'])) 
		$store_count = $Params['store_count'];

	if($resource_type == 'sitereview_listing') {
		$ratingValue = 'rating_avg';
		$ratingShow = 'small-star';
		if ($this->ratingType == 'rating_editor') {$ratingType = 'editor';} elseif ($this->ratingType == 'rating_avg') {$ratingType = 'overall';} else { $ratingType = 'user';}
	}
?>
	<?php $itemTitle = Engine_Api::_()->getDbtable('mixsettings', 'sitepageintegration')->getItemsTitle($resource_type, $listingTypeId); ?>
	<?php if($this->showtoptitle == 1):?>
		<div class="layout_simple_head" id="layout_poll">
			<?php echo $this->translate($this->sitepage->getTitle());?><?php echo $this->translate("'s $itemTitle");?>
		</div>
	<?php endif; ?>
	<?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && $adsDisplay && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage)): ?>
		<div class="layout_right" id="communityad_<?php echo $this->resource_type; ?>">
		<?php
			echo $this->content()->renderWidget("communityad.ads", array( "itemCount"=>$adsDisplay,"loaded_by_ajax"=>1,'widgetId'=>"page_integration_$this->resource_type")); 			 
			?>
		</div>
			<div class="layout_middle">
	<?php endif; ?>
	<div class="seaocore_add clear">

		  <?php if (Engine_Api::_()->sitepage()->isManageAdmin($this->sitepage, 'edit')): ?>
      <?php
        if($listingTypeId) {
            $listingType = Engine_Api::_()->getItem('sitereview_listingtype',$listingTypeId);
            $titlePluUc = ucfirst($listingType->title_plural);
        }
      ?>
      
        <?php  if($listingTypeId):?>
            <a href='<?php echo $manage_url ?>' class="buttonlink item_icon_<?php echo $icon_name ?>"><?php echo $this->translate("Manage $titlePluUc");?></a>
         <?php else:?>
            <a href='<?php echo $manage_url ?>' class="buttonlink item_icon_<?php echo $icon_name ?>"><?php echo $this->translate("Manage %s", $itemTitle);?></a>
         <?php endif;?>
        
        
				<?php if(!empty($this->createPrivacy)) : ?>
				  <?php if(isset($store_count) && $store_count != 1 && $resource_type == 'sitestoreproduct_product') : ?>
						<?php echo  $this->htmlLink(array('route' => 'default', 'module' => 'sitepageintegration', 'controller' => 'index', 'action' => 'storeintegration', 'resource_id' => $this->sitepage->page_id), $this->translate("Create %s", $itemTitle), array('class' => 'buttonlink seaocore_icon_add', 'onclick' => 'owner(this);return false')) ; ?>
				  
				  <?php //echo $this->htmlLink(array('route' => 'default', 'module' => 'sitepageintegration', 'controller' => 'index', 'action' => 'storeintegration', 'resource_id' => $this->sitepage->page_id), $this->translate("Create %s", $itemTitle), array('class' => 'buttonlink seaocore_icon_add smoothbox')); ?>
				  <?php elseif(!empty($URL)): ?>
          
            <?php  if($listingTypeId):?>
                <a href='<?php echo $URL ?>' class="buttonlink seaocore_icon_add"><?php echo $this->translate("Create $titlePluUc");?></a>
            <?php else:?>
              <a href='<?php echo $URL ?>' class="buttonlink seaocore_icon_add"><?php echo $this->translate("Create %s", $itemTitle);?></a>
            <?php endif; ?>
				  <?php endif; ?>
					
				<?php endif; ?>
			<?php endif; ?>
			
		  <?php //if (count($this->contentResults) > 0): ?>
		  <div class="fright">
				<?php echo $this->translate("Search: ");?>
				<input id="sitepage_intg_search_input_text" type="text" value="<?php echo $this->search; ?>" onkeyup = "showsearchnotecontent()" />
				&nbsp;&nbsp;&nbsp;
				<?php echo $this->translate('Browse by:');?>	
				<select name="default_visibility" id="sitepage_intg_search_input_selectbox" onchange = "Orderintselect()">
					<?php if($this->selectbox == 'creation_date'): ?>
						<option value="creation_date" selected='selected'><?php echo $this->translate("Most Recent"); ?></option>
					<?php else:?>
						<option value="creation_date"><?php echo $this->translate("Most Recent"); ?></option>
					<?php endif;?>
					<?php if($this->selectbox == 'comment_count'): ?>
						<option value="comment_count" selected='selected'><?php echo $this->translate("Most Commented"); ?></option>
					<?php else:?>
						<option value="comment_count"><?php echo $this->translate("Most Commented"); ?></option>
					<?php endif;?>		
					<?php if($this->selectbox == 'view_count'): ?>
						<option value="view_count" selected='selected'><?php echo $this->translate("Most Viewed"); ?></option>
					<?php else:?>
						<option value="view_count"><?php echo $this->translate("Most Viewed"); ?></option>
					<?php endif;?>		
				  <?php if($this->selectbox == 'like_count'): ?>
						<option value="like_count" selected='selected'><?php echo $this->translate("Most Liked"); ?></option>
					<?php else:?>
						<option value="like_count"><?php echo $this->translate("Most Liked"); ?></option>
					<?php endif;?>
				</select>
			</div>
			 <?php //endif; ?>
		</div>	 

      <div id='sitepageintg_search'>
        <?php if (count($this->contentResults) > 0): ?>
        <ul class="<?php echo $ul_class ?>" >
          	<?php foreach ($this->contentResults as $item): ?>
          	<?php if($resource_type == 'sitestoreproduct_product'): ?>
						<?php include APPLICATION_PATH . '/application/modules/Sitepageintegration/views/scripts/integration/store_product.tpl'; ?>
          	<?php elseif($resource_type == 'sitetutorial_tutorial'): ?>
						<?php include APPLICATION_PATH . '/application/modules/Sitepageintegration/views/scripts/integration/tutorial.tpl'; ?>
          	<?php elseif($resource_type == 'sitefaq_faq'): ?>
						<?php include APPLICATION_PATH . '/application/modules/Sitepageintegration/views/scripts/integration/faq.tpl'; ?>
					<?php elseif($resource_type == 'quiz'): ?>
						<li>
							<div class='seaocore_profile_list_photo'>
								<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal')) ?>
							</div>
							<div class='seaocore_profile_list_info'>
								<div class='seaocore_profile_list_title'>
									<p>
										<?php echo $this->htmlLink($item->getHref(),  Engine_Api::_()->seaocore()->seaocoreTruncateText($item->getTitle(), $this->title_truncation), array('title' => $item->getTitle())) ?>
									</p>	
								</div>
								<div class='seaocore_profile_info_date'>
									<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>
								</div>
								<div class='seaocore_profile_info_blurb'>
									<?php echo $item->getDescription(true); ?>
								</div>
							</div>
						</li>
          	<?php elseif ($resource_type == 'folder') : ?>
						<li>
							<?php //if ($item->showphoto): ?>
								<div class="seaocore_profile_list_photo">
									<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'));?>
								</div>
							<?php //endif; ?>
							<div class="seaocore_profile_list_info">
								<div class="seaocore_profile_list_title">
									<?php echo $item->toString()?>
								</div>
								<?php //if ($this->showmeta): ?>
									<div class="seaocore_profile_info_date">
										<?php echo $this->partial('index/_meta.tpl', 'folder', array('folder' => $item, 'show_date'=>true, 'show_files'=>true, 'show_comments'=>true, 'show_likes'=>true, 'show_views'=>true))?>
									</div>
								<?php //endif; ?>         
								<?php //if ($this->showdescription && $item->getDescription()): ?>
									<div class="seaocore_profile_info_blurb">
										<?php echo $this->partial('index/_description.tpl', 'folder', array('folder' => $item))?>
									</div>
								<?php //endif; ?>
							</div>
						</li>
          	<?php elseif ($resource_type == 'document') :?>
						<li>
								<div class='seaocore_profile_list_photo'>
								  <?php if(!empty($item->photo_id)): ?>
										<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'), array('title' => $item->document_title)) ?>
									<?php elseif(!empty($item->thumbnail)): ?>
										<?php echo $this->htmlLink($item->getHref(), '<img src="'. $item->thumbnail .'" class="thumb_normal" />', array('title' => $item->document_title)) ?>
									<?php else: ?>
										<?php echo $this->htmlLink($item->getHref(), '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Document/externals/images/document_thumb.png" class="thumb_normal" />', array('title' => $item->document_title)) ?>
									<?php endif;?>
								</div>
								<div class='seaocore_profile_list_info'>
									<div class='seaocore_profile_list_title'>
										<span>
											<?php if($item->featured == 1): ?>
												<?php echo $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Seaocore/externals/images/featured.gif', '', array('class' => 'icon', 'title' => $this->translate('Featured'))) ?>
											<?php endif;?>
										</span>

										<span>
											<?php if($item->sponsored == 1): ?>
												<?php echo $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Seaocore/externals/images/sponsored.png', '', array('class' => 'icon', 'title' => $this->translate('Sponsored'))) ?>
											<?php endif;?>
										</span>

										<?php if(($item->rating > 0) && ($this->show_rate == 1)):?>
											<span class="list_rating_star">
												<?php 
													$currentRatingValue = $item->rating;
													$difference = $currentRatingValue- (int)$currentRatingValue;
													if($difference < .5) {
														$finalRatingValue = (int)$currentRatingValue;
													}
													else {
														$finalRatingValue = (int)$currentRatingValue + .5;
													}	
												?>
												<?php for($x = 1; $x <= $item->rating; $x++): ?>
													<span class="rating_star_generic rating_star" title="<?php echo $finalRatingValue.$this->translate(' rating'); ?>">
													</span>
												<?php endfor; ?>
												<?php if((round($item->rating) - $item->rating) > 0):?>
													<span class="rating_star_generic rating_star_half" title="<?php echo $finalRatingValue.$this->translate(' rating'); ?>>">
													</span>
												<?php endif; ?>
											</span>
										<?php endif; ?>
										
										<p>
											<?php
												$truncation = Engine_Api::_()->getApi('settings', 'core')->getSetting('document.title.truncation', 0);
												$item_title = $item->document_title;
												if(empty($truncation)) {
													$item_title = Engine_Api::_()->document()->truncateText($item_title, 60);
												}
											?>
											<?php echo $this->htmlLink($item->getHref(), $item_title, array('title' => $item->document_title)) ?>
										</p>
									</div>
									<div class='seaocore_profile_info_date'>
										<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>, 
										<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>,
										<?php if($item->category_id): ?>
											<?php $category = Engine_Api::_()->getDbtable('categories', 'document')->getCategory($item->category_id); ?>
											<?php echo $this->translate('Category:');?> <?php echo $category->category_name ?>
										<?php endif; ?> 
									</div>
									<div class='seaocore_profile_info_blurb'>
										<?php echo Engine_Api::_()->document()->truncateText($item->document_description, 560); ?>
									</div>
								</div>
							</li>
              <?php elseif($resource_type == 'list_listing'): ?>
								<li>
									<div class='seaocore_profile_list_photo'>
										<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal')) ?>
									</div>
									<div class='seaocore_profile_list_info'>
										<div class='seaocore_profile_list_title'>
											<span>
												<?php if ($item->featured == 1): ?>
													<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/List/externals/images/list_goldmedal1.gif', '', array('class' => 'icon', 'title' => $this->translate('Featured'))) ?>
												<?php endif; ?>
												</span>
											<span>
												<?php if ($item->sponsored == 1): ?>
													<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/List/externals/images/sponsored.png', '', array('class' => 'icon', 'title' => $this->translate('Sponsored'))) ?>
												<?php endif; ?>
											</span>
											<?php if( $item->closed ): ?>
												<span>
													<img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/List/externals/images/close.png'/>
												</span>
											<?php endif;?>
											<?php if (($item->rating > 0) && $this->ratngShow): ?>
												<span title="<?php echo $item->rating.$this->translate(' rating'); ?>" class="list_rating_star">
													<?php for ($x = 1; $x <= $item->rating; $x++): ?>
														<span class="rating_star_generic rating_star" ></span>
													<?php endfor; ?>
													<?php if ((round($item->rating) - $item->rating) > 0): ?>
														<span class="rating_star_generic rating_star_half" ></span>
													<?php endif; ?>
												</span>
											<?php endif; ?>
											<p>
												<?php echo $this->htmlLink($item->getHref(),  Engine_Api::_()->seaocore()->seaocoreTruncateText($item->getTitle(), $this->title_truncation), array('title' => $item->getTitle())) ?>
											</p>	
										</div>
										<div class='seaocore_profile_info_date'>
										  <?php if (!empty($this->show_posted_date)) : ?>
												<?php echo $this->timestamp(strtotime($item->creation_date)) ?> - <?php echo $this->translate('posted by'); ?>
												<?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>,
											<?php endif; ?>
											<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>,
											<?php echo $this->translate(array('%s review', '%s reviews', $item->review_count), $this->locale()->toNumber($item->review_count)) ?>,
											<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>,
											<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>
										</div>
										<div class='seaocore_profile_info_blurb'>
											<?php echo substr(strip_tags($item->body), 0, 350); if (strlen($item->body)>349) echo $this->translate("...");?>
										</div>
									</div>
								</li>
							<?php elseif($resource_type == 'sitebusiness_business') : ?>
								<li <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebusiness.fs.markers', 1)):?><?php if($item->featured):?> class="lists_highlight"<?php endif;?><?php endif;?>>
									<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebusiness.fs.markers', 1)):?>
											<?php if($item->featured):?>
												<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitebusiness/externals/images/featured-label.png', '',  array('title' => 'Featured','class' => 'sitebusiness_featured_label')) ?>
										<?php endif;?>
									<?php endif;?>
									<div class='sitebusinesses_profile_tab_photo'>
										<?php echo $this->htmlLink(Engine_Api::_()->sitebusiness()->getHref($item->business_id, $item->owner_id, $item->getSlug()), $this->itemPhoto($item, 'thumb.normal', '', array('align' => 'left'))) ?>
										<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebusiness.fs.markers', 1)):?>
											<?php if (!empty($item->sponsored)): ?>
												<?php $sponsored = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebusiness.sponsored.image', 1);
												if (!empty($sponsored)) { ?>
													<div class="sitebusiness_sponsored_label" style='background: <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebusiness.sponsored.color', '#fc0505'); ?>;'>
														<?php echo $this->translate('SPONSORED'); ?>                 
													</div>
												<?php } ?>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<div class='sitebusinesses_profile_tab_info'>
										<div class='sitebusinesses_profile_tab_title'>
											<?php echo $this->htmlLink(Engine_Api::_()->sitebusiness()->getHref($item->business_id, $item->owner_id, $item->getSlug()), $item->getTitle()) ?>
											<div class="fright">
												<?php  $ratngShow = (int) Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessreview');
												if ($ratngShow): ?>
													<?php if (($item->rating > 0)): ?>

														<?php
														$currentRatingValue = $item->rating;
														$difference = $currentRatingValue - (int) $currentRatingValue;
														if ($difference < .5) {
															$finalRatingValue = (int) $currentRatingValue;
														} else {
															$finalRatingValue = (int) $currentRatingValue + .5;
														}
														?>

														<span title="<?php echo $finalRatingValue . $this->translate(' rating'); ?>">
															<?php for ($x = 1; $x <= $item->rating; $x++): ?>
																<span class="rating_star_generic rating_star" ></span>
															<?php endfor; ?>
															<?php if ((round($item->rating) - $item->rating) > 0): ?>
																<span class="rating_star_generic rating_star_half" ></span>
															<?php endif; ?>
														</span>
													<?php endif; ?>
												<?php endif; ?>

												<?php if ($item->closed): ?>
													<span>
														<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitebusiness/externals/images/close.png', '', array('class' => 'icon', 'title' => $this->translate('Closed'))) ?>
													</span>
												<?php endif; ?>
												<span>
														<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebusiness.fs.markers', 1)) :?>
															<?php if ($item->sponsored == 1): ?>
																<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitebusiness/externals/images/sponsored.png', '', array('class' => 'icon', 'title' => $this->translate('Sponsored'))) ?>
															<?php endif; ?>
															<?php if ($item->featured == 1): ?>
																<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitebusiness/externals/images/sitebusiness_goldmedal1.gif', '', array('class' => 'icon', 'title' => $this->translate('Featured'))) ?>
															<?php endif; ?>
														<?php endif; ?>
												</span>
											</div>
											<div class="clr"></div>
										</div>
										<div class='sitebusinesses_browse_info_date seaocore_txt_light'>
											<?php if (!empty($this->show_posted_date)) : ?>
												<?php echo $this->timestamp(strtotime($item->creation_date)) ?> - <?php echo $this->translate('posted by'); ?>
												<?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>,
											<?php endif; ?>
											<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>,

											<?php $sitebusinessreviewEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessreview'); ?>
											<?php if ($sitebusinessreviewEnabled): ?>
												<?php echo $this->translate(array('%s review', '%s reviews', $item->review_count), $this->locale()->toNumber($item->review_count)) ?>,
											<?php endif; ?>

											<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>,
											<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>
										</div>
										<div class='sitebusinesses_browse_info_blurb'>
											<?php
											// Not mbstring compat
											echo substr(strip_tags($item->body), 0, 350);
											if (strlen($item->body) > 349)
												echo $this->translate("...");
											?>
										</div>
									</div>
								</li>
							<?php elseif($resource_type == 'sitegroup_group') : ?>
								<li <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.fs.markers', 1)):?><?php if($item->featured):?> class="lists_highlight"<?php endif;?><?php endif;?>>
									<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.fs.markers', 1)):?>
											<?php if($item->featured):?>
												<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitegroup/externals/images/featured-label.png', '',  array('title' => 'Featured','class' => 'sitegroup_featured_label')) ?>
										<?php endif;?>
									<?php endif;?>
									<div class='sitegroups_profile_tab_photo'>
										<?php echo $this->htmlLink(Engine_Api::_()->sitegroup()->getHref($item->group_id, $item->owner_id, $item->getSlug()), $this->itemPhoto($item, 'thumb.normal', '', array('align' => 'left'))) ?>
										<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.fs.markers', 1)):?>
											<?php if (!empty($item->sponsored)): ?>
												<?php $sponsored = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.sponsored.image', 1);
												if (!empty($sponsored)) { ?>
													<div class="sitegroup_sponsored_label" style='background: <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.sponsored.color', '#fc0505'); ?>;'>
														<?php echo $this->translate('SPONSORED'); ?>                 
													</div>
												<?php } ?>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<div class='sitegroups_profile_tab_info'>
										<div class='sitegroups_profile_tab_title'>
											<?php echo $this->htmlLink(Engine_Api::_()->sitegroup()->getHref($item->group_id, $item->owner_id, $item->getSlug()), $item->getTitle()) ?>
											<div class="fright">
												<?php  $ratngShow = (int) Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupreview');
												if ($ratngShow): ?>
													<?php if (($item->rating > 0)): ?>

														<?php
														$currentRatingValue = $item->rating;
														$difference = $currentRatingValue - (int) $currentRatingValue;
														if ($difference < .5) {
															$finalRatingValue = (int) $currentRatingValue;
														} else {
															$finalRatingValue = (int) $currentRatingValue + .5;
														}
														?>

														<span title="<?php echo $finalRatingValue . $this->translate(' rating'); ?>">
															<?php for ($x = 1; $x <= $item->rating; $x++): ?>
																<span class="rating_star_generic rating_star" ></span>
															<?php endfor; ?>
															<?php if ((round($item->rating) - $item->rating) > 0): ?>
																<span class="rating_star_generic rating_star_half" ></span>
															<?php endif; ?>
														</span>
													<?php endif; ?>
												<?php endif; ?>

												<?php if ($item->closed): ?>
													<span>
														<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitegroup/externals/images/close.png', '', array('class' => 'icon', 'title' => $this->translate('Closed'))) ?>
													</span>
												<?php endif; ?>
												<span>
														<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.fs.markers', 1)) :?>
															<?php if ($item->sponsored == 1): ?>
																<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitegroup/externals/images/sponsored.png', '', array('class' => 'icon', 'title' => $this->translate('Sponsored'))) ?>
															<?php endif; ?>
															<?php if ($item->featured == 1): ?>
																<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitegroup/externals/images/sitegroup_goldmedal1.gif', '', array('class' => 'icon', 'title' => $this->translate('Featured'))) ?>
															<?php endif; ?>
														<?php endif; ?>
												</span>
											</div>
											<div class="clr"></div>
										</div>
										<div class='sitegroups_browse_info_date seaocore_txt_light'>
											<?php if (!empty($this->show_posted_date)) : ?>
												<?php echo $this->timestamp(strtotime($item->creation_date)) ?> - <?php echo $this->translate('posted by'); ?>
												<?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>,
											<?php endif; ?>
											<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>,

											<?php $sitegroupreviewEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupreview'); ?>
											<?php if ($sitegroupreviewEnabled): ?>
												<?php echo $this->translate(array('%s review', '%s reviews', $item->review_count), $this->locale()->toNumber($item->review_count)) ?>,
											<?php endif; ?>

											<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>,
											<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>
										</div>
										<div class='sitegroups_browse_info_blurb'>
											<?php
											// Not mbstring compat
											echo substr(strip_tags($item->body), 0, 350);
											if (strlen($item->body) > 349)
												echo $this->translate("...");
											?>
										</div>
									</div>
								</li>
              <?php else: ?>
								<li class="b_medium">
									<div class='sr_browse_list_photo b_medium'>
										<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.fs.markers', 1)):?>
											<?php if($item->featured):?>
												<i class="sr_list_featured_label" title="<?php echo $this->translate('Featured'); ?>"></i>
									    <?php endif;?>
											<?php if($item->newlabel):?>
												<i class="sr_list_new_label" title="<?php echo $this->translate('New'); ?>"></i>
											<?php endif;?>
										<?php endif;?>
										<?php echo $this->htmlLink($item->getHref(array('profile_link' => 1)), $this->itemPhoto($item, 'thumb.normal', '', array('align' => 'center'))) ?>
										<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.fs.markers', 1)):?>
											<?php if (!empty($item->sponsored)): ?>
												<div class="sr_list_sponsored_label" style="background: <?php echo $this->listingType->sponsored_color; ?>">
													<?php echo $this->translate('SPONSORED'); ?>                 
												</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<div class='sr_browse_list_info'>
										<div class="sr_browse_list_show_rating fright">
											<?php if($this->listingType->reviews == 3): ?>
												<?php echo $this->showRatingStar($item->rating_editor, 'editor', $ratingShow, $item->listingtype_id); ?>
												<br/>
												<?php echo $this->showRatingStar($item->rating_users, 'user', $ratingShow, $item->listingtype_id); ?>
												<?php elseif($this->listingType->reviews == 2): ?>
												<?php echo $this->showRatingStar($item->rating_users, 'user', $ratingShow, $item->listingtype_id); ?>
												<?php elseif($this->listingType->reviews == 1): ?>
												<?php echo $this->showRatingStar($item->rating_editor, 'editor', $ratingShow, $item->listingtype_id); ?>
											<?php endif; ?>
										</div>
									<div class='sr_browse_list_info_header'>
										<div class="sr_list_title_small o_hidden">
												<?php echo $this->htmlLink($item->getHref(),  Engine_Api::_()->seaocore()->seaocoreTruncateText($item->getTitle(), $this->title_truncation), array('title' => $item->getTitle())) ?>
											</div>
										</div>
										<div class='sr_browse_list_info_stat seaocore_txt_light'>
										<?php if (!empty($this->show_posted_date)) : ?>
											<?php echo $this->timestamp(strtotime($item->creation_date)) ?> - <?php echo $this->translate('posted by'); ?>
											<?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>,
											<?php endif; ?>
											<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>,
											<?php echo $this->translate(array('%s review', '%s reviews', $item->review_count), $this->locale()->toNumber($item->review_count)) ?>,
											<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>,
											<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>
										</div>
										<div class='sr_browse_list_info_blurb'>
											<?php echo substr(strip_tags($item->body), 0, 350); if (strlen($item->body)>349) echo $this->translate("...");?>
										</div>
										<div class="sr_browse_list_info_footer clr o_hidden mtop5">
											<div class="sr_browse_list_info_footer_icons"> 
												<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.fs.markers', 1)) :?>
													<?php if ($item->sponsored == 1): ?>
														<i title="<?php echo $this->translate('Sponsored');?>" class="sr_icon seaocore_icon_sponsored"></i>
													<?php endif; ?>
													<?php if ($item->featured == 1): ?>
														<i title="<?php echo $this->translate('Featured');?>" class="sr_icon seaocore_icon_featured"></i>
													<?php endif; ?>
												<?php endif;?>
												<?php if( $item->closed ): ?>
													<i class="sr_icon icon_sitereviews_close" title="<?php echo $this->translate('Closed'); ?>"></i>
												<?php endif;?>
											</div>
										</div>
									</div>
								</li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="tip" id='sitepageintg_search'>
            <span>
              <?php echo $this->translate('No listings were found matching your search criteria.'); ?>
            </span>
          </div>
      <?php endif; ?>
      </div>
				<?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && $adsDisplay && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage)): ?>
      </div>
		<?php endif; ?>
<?php endif; ?>
<?php if (empty($this->isajax)) : ?>
  </div>
<?php endif; ?>


<?php $routeStartS = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.manifestUrlS', "pageitem"); ?>
<?php if (empty($this->isajax)) : ?>
  <script type="text/javascript">
    var adwithoutpackage = '<?php echo Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage) ?>';

    var is_ajax_divhide = '<?php echo $this->isajax; ?>';
    var execute_Request_<?php echo $this->resource_type; ?> = '<?php echo $this->show_content; ?>';


    var exexcute_request_resource = execute_Request_<?php echo $this->resource_type; ?>;

    var show_widgets = '<?php echo $this->widgets ?>';
    
      var page_communityad_integration = '<?php echo $page_communityad_integration; ?>';
      if (<?php echo $this->identity_temp; ?> == '<?php echo $this->module_tabid; ?>') {    
        hideWidgetsForModule('sitepageintegration');
        prev_tab_id = '<?php echo $this->identity_temp; ?>';
        prev_tab_class ='layout_sitepageintegration_profile_items';
        exexcute_request_resource =  true;          
        hideLeftContainer ('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting("sitepage.ad." . $modNameKey, 3); ?>', page_communityad_integration,
        adwithoutpackage);
      }
      else if (is_ajax_divhide != 1) {
//        if($('global_content').getElement('.layout_sitepageintegration_profile_items')) {
//          $('global_content').getElement('.layout_sitepageintegration_profile_items').style.display = 'none';
//        }
      }

      $('.tab_<?php echo $this->identity_temp; ?>').addEvent('click', function() { 
       // if(prev_tab_id != <?php echo $this->identity_temp; ?>) {
          $('.layout_sitepageintegration_profile_items').hide();
       /// }
        $('id_' + <?php echo $this->identity_temp; ?>).parentNode.style.display = "block";

        if(prev_tab_id == <?php echo $this->identity_temp; ?>) {
          exexcute_request_resource = true;
        }

        //$('global_content').getElement('.layout_sitepageintegration_profile_items').style.display = 'block';
        if(page_showtitle != 0) {
          if($('profile_status') && show_widgets == 1) {
            $('profile_status').innerHTML = "<h2><?php echo $this->string()->escapeJavascript($this->sitepage->getTitle()) ?></h2>";	
          }
        } 	

        if (content_integration_tab_id == '') {
          content_integration_tab_id = this.className;
        }

        hideWidgetsForModule('sitepageintegration');

        if ($('id_' + prev_tab_id) != null && prev_tab_id != 0 && prev_tab_id != <?php echo $this->identity_temp ?>) {  


          $('id_' + <?php echo $this->identity_temp ?>).style.display = "block";
          //$('id_' + <?php //echo $this->identity_temp  ?>).innerHTML = '';
          
          $('id_' + prev_tab_id).style.display = "none";
          //$('id_' + prev_tab_id).innerHTML = '';
          //$('id_' + <?php //echo $this->identity_temp  ?>).style.display = 'none';
          $('id_' + prev_tab_id).parentNode.style.display = "none";
          exexcute_request_resource = true;
        
        }
        if (prev_tab_id != '<?php echo $this->identity_temp; ?>' || content_integration_tab_id != this.className) {
          content_integration_tab_id = this.className;    
          if(prev_tab_id != <?php echo $this->identity_temp; ?>) {
           exexcute_request_resource = false;
          }
          prev_tab_id = '<?php echo $this->identity_temp; ?>';      
          prev_tab_class = 'layout_sitepageintegration_profile_items';
        }
      
        if(exexcute_request_resource == false) { 
          ShowContent('<?php echo $this->identity_temp; ?>', exexcute_request_resource,
          '<?php echo $this->identity_temp; ?>', 'null', 'sitepageintegration', 'profile-items',
          page_showtitle, '<?php echo $routeStartS . '/' . Engine_Api::_()->sitepage()->getPageUrl($this->sitepage->page_id) ?>', '<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting("sitepage.ad.$modNameKey", 3) ?>', page_communityad_integration,
          adwithoutpackage, null,null,'<?php echo $this->resource_type ?>', null, '<?php echo $this->change_url ?>', '<?php echo $this->title_truncation ?>', '<?php echo $this->show_posted_date ?>');
          exexcute_request_resource = true;
        }

        if('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1); ?>' &&
          '<?php echo !Engine_Api::_()->getApi('settings', 'core')->getSetting("sitepage.ad.$modNameKey", 3) ?>')
{setLeftLayoutForPage(); }
      });
  </script>
<?php endif; ?>
  <script type="text/javascript" >
	function owner(thisobj) {
		var Obj_Url = thisobj.href ;
		Smoothbox.open(Obj_Url);
	}
</script>
