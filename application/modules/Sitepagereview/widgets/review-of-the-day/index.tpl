<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
	$this->headLink()
        ->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/styles/sitepage-tooltip.css');
        
	$this->headLink()
        ->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitepagereview/externals/styles/style_sitepagereview.css');
?>
<?php $photo_review = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.photo', 1);?>
<ul class="sitepage_sidebar_list">
	<li>
    <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
		$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $this->reviewOfDay->page_id, $layout);?>
    <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $this->reviewOfDay->page_id);?>
		<?php $user = Engine_Api::_()->getItem('user', $this->reviewOfDay->owner_id); ?>
		<?php if(!empty($photo_review)):?>
      <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon'), array('title' => $user->getTitle())) ?>
    <?php else:?>
			<?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($sitepage_object->page_id, $sitepage_object->owner_id, $sitepage_object->getSlug()), $this->itemPhoto($sitepage_object, 'thumb.icon'), array('title' => $sitepage_object->getTitle()));?>
    <?php endif;?>
		<div class="sitepage_sidebar_list_info">
			<div class="sitepage_sidebar_list_title">
				<?php
					$truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.truncation.limit', 13);
					$review_title = Engine_Api::_()->sitepagereview()->truncateText($this->reviewOfDay->title, $truncation_limit);
				?>
				<?php echo $this->htmlLink($this->reviewOfDay->getHref(), $review_title, array('title' => $this->reviewOfDay->title)) ?>
	
			</div>
      <?php $page = Engine_Api::_()->sitepagereview()->getLinkedPage($this->reviewOfDay->review_id); ?>  
      <div class="sitepage_sidebar_list_details">
				<?php echo $this->translate("on ").$this->htmlLink(Engine_Api::_()->sitepage()->getHref($page->page_id, $page->owner_id), Engine_Api::_()->sitepage()->truncation($page->title), array('title' => $page->title, 'class' => 'bold')); ?>
			</div>
      <div class='sitepage_sidebar_list_details'>
				<span title="<?php echo $page->rating . $this->translate(' rating'); ?>">
					<?php if (($page->rating > 0)): ?>
						<?php for ($x = 1; $x <= $page->rating; $x++): ?>
							<span class="rating_star_generic rating_star"></span>
						<?php endfor; ?>
						<?php if ((round($page->rating) - $page->rating) > 0): ?>
							<span class="rating_star_generic rating_star_half"></span>
						<?php endif; ?>
					<?php endif; ?>
				</span>
			</div>  
		</div>
		<div class="clr sitepage_review_code">
			<b class="c-l fleft"></b>
			<?php echo Engine_Api::_()->sitepagereview()->truncateText($this->reviewOfDay->body, 100) ?>
			<b class="c-r fright"></b>
		</div>
	</li>
  <li class="sitepage_sidebar_list_seeall">
    <?php echo $this->htmlLink($this->reviewOfDay->getHref(array('tab' => $tab_id)), $this->translate('More &raquo;'));?>
  </li>
</ul>