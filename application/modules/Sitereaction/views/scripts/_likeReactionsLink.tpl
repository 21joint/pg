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
    <a href="<?php
    echo $this->url(array('action' => 'likes', 'module' => 'sitereaction',
      'controller' => 'index', 'subject_type' => $this->subject->getType(), 'subject_id' => $this->subject->getIdentity(),
      'reaction' => $icon['type']), 'default', true)
    ?>"  class="seao_smoothbox" title="<?php echo $this->translate($icon['caption']) . " (" . $icon['count'] . ")" ?>">
      <i style="background-image: url(<?php echo $icon['icon'] ?>)" ></i>
    </a>
<?php endforeach; ?>
</span>
