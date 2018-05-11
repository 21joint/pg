<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<ul class="sitepage_sidebar_list">
  <?php foreach ($this->paginator as $sitepagemusic): ?>
    <?php  $this->partial()->setObjectKey('sitepagemusic');
        echo $this->partial('application/modules/Sitepagemusic/views/scripts/partialWidget.tpl', $sitepagemusic);
		?>
          <?php echo $this->translate(array('%s play', '%s plays', $sitepagemusic->play_count), $this->locale()->toNumber($sitepagemusic->play_count)) ?> |
          <?php echo $this->translate(array('%s view', '%s views', $sitepagemusic->view_count), $this->locale()->toNumber($sitepagemusic->view_count)) ?>
        </div>
      </div>
    </li>
  <?php endforeach; ?>
</ul>