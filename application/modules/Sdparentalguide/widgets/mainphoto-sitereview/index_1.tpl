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
		<div class="sr_profile_sponsorfeatured sd_sponsorfeatured_img"  style='background: <?= $this->featured_color; ?>;'>
                    <img src='<?= $this->layout()->staticBaseUrl; ?>application/modules/Sdparentalguide/externals/images/featured_small2.png'/>
		</div>
	<?php endif; ?>
	<div class='sr_profile_cover_photo <?php if ($this->can_edit && ($photo_type == 'listing')):?>sr_photo_edit_wrapper<?php endif;?>'>
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
                
                <?php if (!empty($this->show_buttons)): ?>
                    <div class="seaocore_board_list_action_links sd_listingview_action_links">
                      <?php $urlencode = urlencode(((!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $sitereview->getHref()); ?>
                      <?php if (in_array('wishlist', $this->show_buttons) && Zend_Registry::get('listingtypeArray' . $sitereview->listingtype_id)->wishlist): ?> 
                        <?= $this->addToWishlist($sitereview, array('classIcon' => 'seaocore_board_icon', 'classLink' => 'wishlist_icon', 'text' => $this->translate('') , 'title' => 'Wishlist'));?>
                      <?php endif; ?>


                      <?php if ((in_array('comment', $this->show_buttons) || in_array('like', $this->show_buttons)) && $alllowComment): ?>
                        <?php if (in_array('comment', $this->show_buttons)): ?>
                          <a href='javascript:void(0);' onclick="en4.seaocorepinboard.comments.addComment('<?= $sitereview->getGuid() . "_" . $this->identity ?>')" class="seaocore_board_icon comment_icon" title="Comment"><!--<?= $this->translate('Comment'); ?>--></a> 
                        <?php endif; ?>
                        <?php if (in_array('like', $this->show_buttons)): ?>
                          <a href="javascript:void(0)" title="Like" class="seaocore_board_icon like_icon <?= $sitereview->getGuid() ?>like_link" id="<?= $sitereview->getType() ?>_<?= $sitereview->getIdentity() ?>like_link" <?php if ($sitereview->likes()->isLike($this->viewer())): ?>style="display: none;" <?php endif; ?>onclick="en4.seaocorepinboard.likes.like('<?= $sitereview->getType() ?>', '<?= $sitereview->getIdentity() ?>');" ><!--<?= $this->translate('Like'); ?>--></a>

                          <a  href="javascript:void(0)" title="Unlike" class="seaocore_board_icon unlike_icon <?= $sitereview->getGuid() ?>unlike_link" id="<?= $sitereview->getType() ?>_<?= $sitereview->getIdentity() ?>unlike_link" <?php if (!$sitereview->likes()->isLike($this->viewer())): ?>style="display:none;" <?php endif; ?> onclick="en4.seaocorepinboard.likes.unlike('<?= $sitereview->getType() ?>', '<?= $sitereview->getIdentity() ?>');"><!--<?= $this->translate('Unlike'); ?>--></a> 
                        <?php endif; ?>
                      <?php endif; ?>

                      <?php if (in_array('share', $this->show_buttons)): ?>
                        <?= $this->htmlLink(array('module' => 'seaocore', 'controller' => 'activity', 'action' => 'share', 'route' => 'default', 'type' => $sitereview->getType(), 'id' => $sitereview->getIdentity(), 'not_parent_refresh' => '1', 'format' => 'smoothbox'), $this->translate(''), array('class' => 'smoothbox seaocore_board_icon share_icon' , 'title' => 'Share')); ?>
                      <?php endif; ?>

                      <?php if (in_array('facebook', $this->show_buttons)): ?>
                        <?= $this->htmlLink('http://www.facebook.com/share.php?u=' . $urlencode . '&t=' . $sitereview->getTitle(), $this->translate(''), array('class' => 'pb_ch_wd seaocore_board_icon fb_icon' , 'title' => 'Facebook')) ?>
                      <?php endif; ?>

                      <?php if (in_array('twitter', $this->show_buttons)): ?>
                        <?= $this->htmlLink('http://twitter.com/share?url=' . $urlencode . '&text=' . $sitereview->getTitle(), $this->translate(''), array('class' => 'pb_ch_wd seaocore_board_icon tt_icon' , 'title' => 'Twitter')) ?> 
                      <?php endif; ?>

                      <?php if (in_array('pinit', $this->show_buttons)): ?>
                        <a href="http://pinterest.com/pin/create/button/?url=<?= $urlencode; ?>&media=<?= urlencode((!preg_match("~^(?:f|ht)tps?://~i", $sitereview->getPhotoUrl('thumb.profile')) ? (((!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] ) : '') . $sitereview->getPhotoUrl('thumb.profile')); ?>&description=<?= $sitereview->getTitle(); ?>"  class="pb_ch_wd seaocore_board_icon pin_icon" title="Pin It" ><!--<?= $this->translate('Pin It') ?>--></a>
                      <?php endif; ?>

                      <?php if (in_array('tellAFriend', $this->show_buttons)): ?>
                        <?= $this->htmlLink(array('action' => 'tellafriend', 'route' => 'sitereview_specific_listtype_' . $sitereview->listingtype_id, 'type' => $sitereview->getType(), 'listing_id' => $sitereview->getIdentity()), $this->translate(''), array('class' => 'smoothbox seaocore_board_icon taf_icon' , 'title' => 'Tell a Friend')); ?>
                      <?php endif; ?>

                      <?php if (in_array('print', $this->show_buttons)): ?>
                        <?= $this->htmlLink(array('action' => 'print', 'route' => 'sitereview_specific_listtype_' . $sitereview->listingtype_id, 'type' => $sitereview->getType(), 'listing_id' => $sitereview->getIdentity()), $this->translate(''), array('class' => 'pb_ch_wd seaocore_board_icon print_icon' , 'title' => 'Print')); ?> 
                      <?php endif; ?>
                      <?php if ($compareButton): ?>
                        <?= $compareButton; ?>
                      <?php endif; ?>
                      
                      <?php if (in_array('setting', $this->show_buttons)): ?>
                          <a  href="javascript:void(0)" title="Settings" onclick='toggleListingSettings(this);' class="seaocore_board_icon setting_icon <?= $sitereview->getGuid() ?>setting_link fa fa-cog" id="<?= $sitereview->getType() ?>_<?= $sitereview->getIdentity() ?>setting_link"></a> 
                          <div class='sd_listing_setting_actions' style="display:none;">
                              <?php if ($this->gutterNavigation): ?>
                                <?php
                                  echo $this->navigation()
                                        ->menu()
                                        ->setContainer($this->gutterNavigation)
                                        ->setUlClass('sr_information_gutter_options b_medium clr')
                                        ->render();
                                ?>
                                <?php endif; ?>
                          </div>
                      <?php endif; ?>
                    </div>
                <?php endif; ?>
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