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
	$this->headLink()
  ->appendStylesheet($this->layout()->staticBaseUrl
    . 'application/modules/Sitepagereview/externals/styles/style_sitepagereview.css')
	->prependStylesheet($this->layout()->staticBaseUrl.'application/modules/Sitepagereview/externals/styles/show_star_rating.css');
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<?php $photo_review = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.photo', 1);?>
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
		<?php if(!empty($photo_review)):?>
			<?php echo $this->htmlLink($this->owner->getHref(), $this->itemPhoto($this->owner)) ?>
		<?php else:?>
			<?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($this->sitepage->page_id, $this->sitepage->owner_id, $this->sitepage->getSlug()), $this->itemPhoto($this->sitepage, 'thumb.normal')) ?>
		<?php endif;?>
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
                  <script type="text/javascript">
                    var fblike_moduletype = 'sitepagereview_review';
		                var fblike_moduletype_id = '<?php echo $this->sitepagereview->review_id ?>';
                  </script>
                  <?php echo Engine_Api::_()->facebookse()->isValidFbLike(); ?>
                </div>
            
            <?php } ?>
          <?php endif; ?>
     <?php endif; ?>
       
			<div class="sitepagereviews_view_body">
      	<?php echo nl2br($this->sitepagereview->body) ?>
      </div>
      
		  <div class="tip">
		  	<span>
		  	<?php echo $this->translate("Like this review if you find it useful."); ?>
		  	</span>
		  </div>	
      
		</li>
  </ul>
	<?php 
        include_once APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_listComment.tpl';
    ?>

</div>

<style type="text/css">
.rating{background-image: url(<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitepagereview/externals/images/show-star-matrix.png);}
</style>