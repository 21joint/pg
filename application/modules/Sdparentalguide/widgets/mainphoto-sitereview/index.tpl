<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>
<?php $this->headLink()->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/style_board.css'); ?>
<?php $sitereview = $this->sitereview; ?>
<?php $photo_type = $this->listingType->photo_type;?>
<div class="sr_profile_cover_photo_wrapper">
	<?php if (!empty($this->sitereview->featured) && $this->show_featured): ?> 
		<div class="sr_profile_sponsorfeatured"  style='background: <?= $this->featured_color; ?>;'>
                    <?= $this->translate('FEATURED');?>
		</div>
	<?php endif; ?>
	<div class='sr_profile_cover_photo loool <?php if ($this->can_edit && ($photo_type == 'listing')):?>sr_photo_edit_wrapper<?php endif;?>'>
		<?php if (!empty($this->can_edit) && ($photo_type == 'listing')) : ?>
			<a class='sr_photo_edit' href="<?= $this->url(array('action' => 'change-photo', 'listing_id' => $this->sitereview->listing_id), "sitereview_dashboard_listtype_$this->listingtype_id", true) ?>">
				<i class="sr_icon"></i>
				<?= $this->translate('Change Picture'); ?>
			</a>
		<?php endif;?>
		<?php if($this->sitereview->newlabel):?>
			<i class="sr_list_new_label" title="<?= $this->translate('New'); ?>"></i>
		<?php endif;?>
		<?php if($this->listingType->photo_id == 0):?>
			<a href="<?= $this->sitereview->getHref(array('profile_link' => 1)); ?>"></a>
		<?php endif;?>
		<?= $this->itemPhoto($this->sitereview, 'thumb.profile', '' , array('align' => 'center')); ?>
                
	</div>
	<?php if (!empty($this->sitereview->sponsored) && $this->show_sponsered): ?>
		<div class="sr_profile_sponsorfeatured" style='background: <?= $this->sponsored_color; ?>;'>
			<?= $this->translate('SPONSORED'); ?>
		</div>
	<?php endif; ?>
	<?php if($this->ownerName): ?>
	  <div class='sr_profile_cover_name'>
	    <?= $this->htmlLink($this->sitereview->getOwner()->getHref(), $this->sitereview->getOwner()->getTitle()) ?>
	  </div>
	<?php endif; ?>
        <div class='sd_listing_profile_rating' style='margin-top: 15px;'>
            <?= $this->partial('_viewOwnerRating.tpl','sdparentalguide',array('rating' => $sitereview->gg_author_product_rating)); ?>
        </div>
</div>

<script type='text/javascript'>
function toggleListingSettings(element){
    var parent = $(element).getParent(".sr_profile_cover_photo_wrapper");
    var dropdown = parent.getElement(".sd_listing_setting_actions");
    if(dropdown){
        dropdown.toggleClass("sd_active");
    }
}    
</script>