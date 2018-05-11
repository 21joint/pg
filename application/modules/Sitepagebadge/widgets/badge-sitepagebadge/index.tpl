<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2010-08-11 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
	$this->headLink()
       ->appendStylesheet($this->layout()->staticBaseUrl
                . 'application/modules/Sitepagebadge/externals/styles/style_sitepagebadge.css')
       ->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/styles/sitepage-tooltip.css');

include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<ul class="sitepage_sidebar_list sitepage_list_badge">
  <li <?php if (!empty($this->sitepagebadge->description)): ?>class="sitepage_badge_show_tooltip_wrapper"<?php endif; ?>>
    <?php if (empty($this->sitepagebadges_value) || $this->sitepagebadges_value == 2): ?>
      <div class="sitepage_list_badge_title"><?php echo $this->translate($this->sitepagebadge->title); ?></div>
    <?php endif; ?>
    <?php if ($this->sitepagebadges_value == 1 || $this->sitepagebadges_value == 2): ?>
      <?php
      if (!empty($this->sitepagebadge->badge_main_id)) {
        $main_path = Engine_Api::_()->storage()->get($this->sitepagebadge->badge_main_id, '')->getPhotoUrl();
        if (!empty($main_path)) {
          echo '<img src="' . $main_path . '" />';
        }
      }
      ?>
    <?php endif; ?>
    <?php if (!empty($this->sitepagebadge->description)): ?>
      <div class="sitepage_badge_show_tooltip">
        <img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagebadge/externals/images/tooltip_arrow_top.png" alt="" />
        <div><?php echo $this->translate($this->sitepagebadge->description) ?></div>
      </div>
    <?php endif; ?>
  </li>
</ul>