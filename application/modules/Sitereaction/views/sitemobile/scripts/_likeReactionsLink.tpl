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
<span class="seao_view_likes_link_wappper">
   <?php foreach ($this->popularity as $icon): ?>
  <i style="background-image: url(<?php echo $icon['icon'] ?>)" data-target="<?php echo $icon['type'] ?>" data-count="<?php echo $icon['count']?>" title="<?php echo $icon['count']?>"></i>
  <?php endforeach; ?>
</span>
