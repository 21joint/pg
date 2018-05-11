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

echo $this->partial(
  '_iconsToolBarTip.tpl', 'sitereaction', $this->toolbar
);
?>
<?php if ($this->like): ?>
  <?php
  $params = array(
      'onclick' => 'javascript:en4.sitereaction.unlikeReaction(this,' . $this->action->action_id . ');',
      'id' => 'aaf_reaction_' . $this->action->action_id,
  );
  ?>
  <a id="<?php echo $params['id']; ?>" class="<?php echo $this->likeClass; ?>" action-title="<?php echo $this->translate($this->likeReactionCaption) ?>" data-current-reaction="<?php echo $this->like->reaction ?>"
     <?php if (empty($this->toolbar['unlikeDisable'])) : ?>  onclick="<?php echo $params['onclick'] ?>" href="javascript:void(0);" <?php endif; ?>
     >
      <i class="ui-icon" style="background-image: url(<?php echo $this->reaction ? $this->reaction['icon'] : 'application/modules/Sitereaction/externals/images/like.png' ?>)" ></i>
    <?php echo $this->translate($this->caption) ?>
  </a>
<?php else: ?>
  <?php
  $params = array(
      'onclick' => 'javascript:en4.sitereaction.likeReaction(this,' . $this->action->action_id . ');',
  );
  ?>
  <a id="<?php echo 'aaf_reaction_' . $this->action->action_id ?>" class="<?php echo $this->likeClass; ?>" action-title="<?php echo $this->translate($this->likeReactionCaption) ?>" data-current-reaction="unlike"
     onclick="<?php echo $params['onclick'] ?>" href="javascript:void(0);">
    <i class="ui-icon" style="background-image: url('application/modules/Sitereaction/externals/images/like.png')" ></i>
    <?php echo $this->translate($this->likeReactionCaption) ?>
  </a>
<?php endif; ?>
