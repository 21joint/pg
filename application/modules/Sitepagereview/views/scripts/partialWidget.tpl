<?php
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: partialWidget.tpl 6590 2010-12-31 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
	$this->headLink()
        ->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/styles/sitepage-tooltip.css');
?>
<?php $photo_review = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.photo', 1);?>
<?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $this->review->page_id);?>
<?php $user = Engine_Api::_()->getItem('user', $this->review->owner_id); ?>
    <li class="sitepagereview_show_tooltip_wrapper">
			<div class="sitepagereview_show_tooltip">
				<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepage/externals/images/tooltip_arrow.png" alt="" class="arrow" />
				<?php echo Engine_Api::_()->sitepagereview()->truncateText($this->review->body, 100) ?>
			</div>
      <?php if(!empty($photo_review)):?>
      <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon'), array('title' => $user->getTitle())) ?>
      <?php else:?>
        <?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepage_object->page_id, $sitepage_object->owner_id, $sitepage_object->getSlug()), $this->itemPhoto($sitepage_object, 'thumb.icon'), array('title' => $sitepage_object->getTitle()));?>
      <?php endif;?>
      
      <div class='sitepage_sidebar_list_info'>
        <div class='sitepage_sidebar_list_title'>
          <?php
          $truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.truncation.limit', 13);
          $this->review_title = Engine_Api::_()->sitepagereview()->truncateText($this->review->title, $truncation_limit);

          $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
          $tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $this->review->page_id, $layout);
          ?>
          <?php echo $this->htmlLink($this->review->getHref(), $this->review_title, array('title' => $this->review->title)) ?>
        </div>

        <div class='sitepage_sidebar_list_details'>