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
<?php $class = $this->likeClass;
 $class .= !$this->like?  ' dnone' : '';
?>
  <?php
  $params = array(
      'id' => 'aaf_reaction_' . $this->action->action_id,
  );
  ?>
  <a id="<?php echo $params['id']; ?>" class="<?php echo $class; ?>" action-title="<?php echo $this->translate('Like') ?>" data-current-reaction="<?php echo $this->like ? $this->like->reaction: '' ?>"
     <?php if (empty($this->toolbar['unlikeDisable'])) : ?>  data-action="unlike" <?php endif; ?> href="javascript:void(0);" 
     >
     <i class="ui-icon" style="background-image: url(<?php echo $this->reaction ? $this->reaction['icon'] : 'application/modules/Sitereaction/externals/images/like.png' ?>)" ></i>
    <?php echo $this->translate($this->caption) ?>
  </a>

<?php $class = $this->likeClass;
 $class .= $this->like ?  ' dnone' : '';
?>

  <a id="<?php echo 'aaf_default_reaction_' . $this->action->action_id ?>" class="<?php echo $class; ?>" action-title="<?php echo $this->translate('Like') ?>" data-current-reaction="unlike"
    data-action="like" href="javascript:void(0);">
    <i class="ui-icon" style="background-image: url('application/modules/Sitereaction/externals/images/like.png')" ></i>
    <?php echo $this->translate($this->likeReactionCaption) ?>
  </a>

