<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
  include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';
?>
<?php 
	$this->headLink()
  ->appendStylesheet($this->layout()->staticBaseUrl
    . 'application/modules/Sitepagereview/externals/styles/style_sitepagereview.css')
	->prependStylesheet($this->layout()->staticBaseUrl.'application/modules/Sitepagereview/externals/styles/show_star_rating.css');
?>
<div class="sitepage_viewpages_head">
	<?php echo $this->htmlLink($this->sitepage->getHref(), $this->itemPhoto($this->sitepage, 'thumb.icon', '', array('align' => 'left'))) ?>
	<h2>
	  <?php echo $this->sitepage->__toString() ?>
	  <?php echo $this->translate('&raquo; ');?>
	  <?php echo $this->htmlLink($this->sitepage->getHref(array('tab'=>$this->tab_selected_id)), $this->translate('Reviews')) ?>
	  <?php echo $this->translate('&raquo; ');?>
	  <?php echo $this->sitepagereview->title ?>
	</h2>
</div>
<div class='layout_left'>
  <div class='sitepagereviews_gutter'>
  	<?php echo $this->htmlLink($this->owner->getHref(), $this->itemPhoto($this->owner)) ?>
    <?php echo $this->htmlLink($this->owner->getHref(), $this->owner->getTitle(), array('class' => 'sitepagereviews_gutter_name')) ?>
  </div>  
  <ul class="sitepagereviews_gutter_options quicklinks">
		<li>
			<?php echo $this->htmlLink($this->sitepage->getHref(array('tab'=>$this->tab_selected_id)), $this->translate('Back to Page'),array('class'=>'buttonlink  icon_sitepagereview_back')) ?>
		</li>
    <?php if($this->viewer_id == $this->sitepagereview->owner_id): ?>
    	<li>  
      	<?php echo $this->htmlLink(array('route' => 'sitepagereview_edit', 'review_id' => $this->sitepagereview->review_id, 'page_id' => $this->sitepagereview->page_id, 'slug' => $this->sitepage_slug, 'tab' => $this->tab_selected_id), $this->translate('Edit Review'), array('class' => 'buttonlink icon_sitepages_edit')) ?>
    	</li>
		<?php endif; ?>
		<?php if($this->viewer_id == $this->sitepagereview->owner_id || $this->level_id == 1): ?>
    	<li>
    		<?php echo $this->htmlLink(array('route' => 'sitepagereview_delete', 'review_id' => $this->sitepagereview->review_id, 'page_id' => $this->sitepagereview->page_id, 'slug' => $this->sitepage_slug, 'tab' => $this->tab_selected_id), $this->translate('Delete Review'), array('class'=>'buttonlink  icon_sitepages_delete')) ?>
    	</li>
    <?php endif; ?>

		<?php if($this->review_report == 1 && !empty($this->viewer_id)): ?>
			<li>
				<?php echo $this->htmlLink(Array('module'=> 'core', 'controller' => 'report', 'action' => 'create', 'route' => 'default', 'subject' => $this->report->getGuid(), 'format' => 'smoothbox'), $this->translate("Report"), array('class' => 'smoothbox buttonlink seaocore_icon_report')); ?>
			</li>
		<?php endif;?>	

		<!-- Suggestion work start from here -->
		<?php if( !empty($this->reviewSuggLink) ): ?>	
			<li>
				<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'suggestion', 'controller' => 'index', 'action' => 'popups', 'sugg_id' => $this->sitepagereview->review_id, 'sugg_type' => 'page_review'), $this->translate('Suggest to Friends'), array(
					'class'=>'buttonlink  icon_page_friend_suggestion smoothbox')) ?>
			</li>
		<?php endif; ?>
		<!-- Suggestion work end from here -->
	</ul>
</div>
<div class='layout_right'>
	<div class="generic_layout_container">
		<h3><?php echo $this->translate("Review Details"); ?></h3>
		<ul class="sitepage_sidebar_list sitepagereview_sidebar mbot15">
			<?php $ratingData = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->profileRatingbyCategory($this->sitepagereview->review_id); ?>
			<?php foreach($ratingData as $reviewcat): ?>
				<li class="sitepagereview_overall_rating">
					<?php if(!empty($reviewcat['reviewcat_name'])): ?>
						<?php 
							$showRatingImage = Engine_Api::_()->sitepagereview()->showRatingImage($reviewcat['rating'], 'box');
							$rating_value = $showRatingImage['rating_value'];
						?>
					<?php else:?>
						<?php
							$showRatingImage = Engine_Api::_()->sitepagereview()->showRatingImage($reviewcat['rating'], 'star');
							$rating_value = $showRatingImage['rating_value'];
							$rating_valueTitle = $showRatingImage['rating_valueTitle'];
						?>
					<?php endif; ?>
					<?php if(!empty($reviewcat['reviewcat_name'])): ?>
						<div class="review_cat_rating">
							<ul class='rating-box-small <?php echo $rating_value; ?>'>
								<li id="1" class="rate one">1</li>
								<li id="2" class="rate two">2</li>
								<li id="3" class="rate three">3</li>
								<li id="4" class="rate four">4</li>
								<li id="5" class="rate five">5</li>
							</ul>
						</div>
					<?php else:?>
						<div class="review_cat_rating">
							<ul title="<?php echo $rating_valueTitle.$this->translate(" rating"); ?>" class='rating <?php echo $rating_value; ?>'>
								<li id="1" class="rate one">1</li>
								<li id="2" class="rate two">2</li>
								<li id="3" class="rate three">3</li>
								<li id="4" class="rate four">4</li>
								<li id="5" class="rate five">5</li>
							</ul>
						</div>
					<?php endif;?>
					<?php if(!empty($reviewcat['reviewcat_name'])): ?>
						<div class="review_cat_title">
							<?php echo $this->translate($reviewcat['reviewcat_name']); ?>
						</div>
					<?php else:?>
						<div class="review_cat_title">
							<?php echo $this->translate("Overall Rating");?>
						</div>	
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
			<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.proscons', 1)):?>
				<li>
					<?php echo "<b>".$this->translate("Pros: ")."</b>".$this->viewMore($this->sitepagereview->pros) ?>
				</li>
				<li>	
					<?php echo "<b>".$this->translate("Cons: ")."</b>".$this->viewMore($this->sitepagereview->cons) ?>
				</li>	
			<?php endif;?>
			<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.recommend', 1)):?>
				<li>
				<?php if($this->sitepagereview->recommend):?>
					<?php echo $this->translate("Member's Recommendation: <b>Yes</b>"); ?>
				<?php else: ?>
					<?php echo $this->translate("Member's Recommendation: <b>No</b>"); ?>
				<?php endif;?>
			<?php endif;?>
			</li>
		</ul>
	</div>	
	<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adreviewview', 3) && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage)):?>
	  <div id="communityad_reviewview">

<?php
			echo $this->content()->renderWidget("communityad.ads", array( "itemCount"=>Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.adreviewview', 3),"loaded_by_ajax"=>0,'widgetId'=>"page_reviewview")); 			 
		?>
		</div>
	<?php endif;?>

</div>
    
<div class='layout_middle'>
	<ul class="sitepagereviews_view">
    <li>
      <h3> 
      	<?php echo $this->sitepagereview->title; ?> 
      </h3>
      <div class="sitepagereviews_view_stats">
      
       
      	<?php echo $this->translate('Posted by %s %s', $this->sitepagereview->getOwner()->toString(), $this->timestamp($this->sitepagereview->creation_date)) ?>
      </div>
      <div class="sitepagereviews_view_stats"> 
      	<?php echo $this->translate(array('%s comment', '%s comments', $this->sitepagereview->comment_count), $this->locale()->toNumber($this->sitepagereview->comment_count)) ?>,
      	<?php echo $this->translate(array('%s view', '%s views', $this->sitepagereview->view_count), $this->locale()->toNumber($this->sitepagereview->view_count)) ?>,
				<?php echo $this->translate(array('%s like', '%s likes', $this->sitepagereview->like_count), $this->locale()->toNumber($this->sitepagereview->like_count)) ?>
      </div>
        <!--FACEBOOK LIKE BUTTON START HERE-->
       <?php  $fbmodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('facebookse');
        if (!empty ($fbmodule)) :
          $enable_facebookse = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('facebookse'); 
          if (!empty ($enable_facebookse) && !empty($fbmodule->version)) :
            $fbversion = $fbmodule->version; 
            if (!empty($fbversion) && ($fbversion >= '4.1.5')) { ?>
               <div class="mtop10">
                  <?php echo Engine_Api::_()->facebookse()->isValidFbLike(); ?>
                </div>
            
            <?php } ?>
          <?php endif; ?>
     <?php endif; ?>
       
			<div class="sitepagereviews_view_body">
      	<?php echo nl2br($this->sitepagereview->body) ?>
      </div>
		</li>
  </ul>
  <div class="tip">
  	<span>
  	<?php echo $this->translate("Like this review if you find it useful."); ?>
  	</span>
  </div>	

	<?php include_once APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_listComment.tpl'; ?>

</div>

<style type="text/css">
.rating{background-image: url(<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitepagereview/externals/images/show-star-matrix.png);}
</style>