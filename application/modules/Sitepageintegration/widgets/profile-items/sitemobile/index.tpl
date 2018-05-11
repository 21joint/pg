<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="sm-content-list">
  <ul data-role="listview" data-inset="false" >
    <?php foreach ($this->contentResults as $item): ?>
      <li data-icon="arrow-r">
        <a href="<?php echo $item->getHref(); ?>" >
          <?php echo $this->itemPhoto($item, 'thumb.icon') ?>
          <h3><?php echo $item->getTitle(); ?></h3>
          <p>
            <?php echo $this->timestamp(strtotime($item->creation_date)) ?> - <?php echo $this->translate('posted by'); ?>
            <b><?php echo $item->getOwner()->getTitle() ?></b>
          </p>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>




