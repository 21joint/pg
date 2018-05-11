<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
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

<script type="text/javascript">

  var badgeAction = function(badge_id){
    //$('page').value = 1;
    $('badge_id').value = badge_id;
    $('filter_form_badge').submit();
  } 
</script>

<?php echo $this->form->render($this) ?>

<ul class="sitepage_sidebar_list sitepage_list_badge">
  <?php foreach ($this->badgeData as $item): ?>
    <li <?php if (!empty($item->description)): ?>class="sitepage_badge_show_tooltip_wrapper"<?php endif; ?>>
      <?php if (empty($this->sitepagebadges_value) || $this->sitepagebadges_value == 2): ?>
        <div class="sitepage_list_badge_title"><?php echo $this->translate($item->title); ?></div>
      <?php endif; ?>
      <?php if ($this->sitepagebadges_value == 1 || $this->sitepagebadges_value == 2): ?>
        <?php
        if (!empty($item->badge_main_id)) {
          $thumb_path = Engine_Api::_()->storage()->get($item->badge_main_id, '')->getPhotoUrl();
          if (!empty($thumb_path)) {
            //echo '<img src="' . $thumb_path . '" />';
						echo '<a href="javascript:void(0);" onclick="javascript:badgeAction('.$item->badge_id.');"><img src="'. $thumb_path .'" /></a>';
          }
        }
        ?>
      <?php endif; ?>
      <?php if (!empty($item->description)): ?>
        <div class="sitepage_badge_show_tooltip">
          <img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagebadge/externals/images/tooltip_arrow_top.png" alt="" />
          <div><?php echo $this->translate($item->description); ?></div>
        </div>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
  <li class="sitepage_sidebar_list_seeall">
    <?php echo $this->htmlLink($this->url(array('category_id' => $this->category_id), 'sitepagebadge_show'), $this->translate('See All &raquo;'), array()) ?>
  </li>
</ul>