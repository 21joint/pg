<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div <?php if ($this->id): ?>id="<?php echo $this->id ?>" <?php endif; ?> class="seao_icons_toolbar_wappper <?php if ($this->class): echo $this->class;
endif;
?>" >
  <div class="icons-toolbar-container seao_icons_toolbar_translate-y">
    <div data-reactroot="" height="52" class="icons-button-wapper">
      <div class="icons-button-container" role="toolbar">
          <?php foreach ($this->icons as $icon): ?>
          <span  class="icon-button-wapper" data-subject="<?php echo $icon['subject'] ?>" data-target="<?php echo $icon['target'] ?>" data-type="<?php echo $icon['type'] ?>" data-title="<?php echo $this->translate($icon['caption']) ?>"
                 data-icon="<?php echo $icon['icon'] ?>">
              <?php if (!empty($icon['href'])): ?>
              <a href = '<?php echo $icon['href'] ?>' <?php if (!empty($icon['class'])): ?>class="<?php echo $icon['class'] ?>"<?php endif; ?> <?php if (!empty($icon['blank'])): ?>target="_blank"<?php endif; ?> >
  <?php endif; ?>
              <div class="icon-button">
                <div class="icon-wapper">
                  <i class="icon" style="background-image: url(<?php echo $icon['icon'] ?>)"></i>
                  <div class="caption-wapper">
                    <div class="caption"><?php echo $this->translate($icon['caption']) ?></div>
                  </div>
                </div>
              </div>
            <?php if (!empty($icon['href'])): ?>
              </a>
          <?php endif; ?>
          </span>
<?php endforeach; ?>
      </div>
      <div class="icons-toolbar-background"></div>
    </div>
  </div>
</div>
