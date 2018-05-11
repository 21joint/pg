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
<span class="comment_item_option_reaction seao_icons_toolbar_attach" data-subject-type="<?php echo $this->subject->getType() ?>" data-subject-id="<?php echo $this->subject->getIdentity() ?>">
    <?php echo $this->partial('_iconsToolBarTip.tpl', 'sitereaction', $this->toolbar);
    ?>
  <a id="reaction_like_comments_<?php echo $this->subject->getGuid() ?>" data-current-reaction="<?php echo $this->like->reaction; ?>"
    <?php if (empty($this->toolbar['unlikeDisable'])) : ?>  data-action="unlike" <?php endif; ?>
    class="<?php echo !$this->like ?'dnone': '' ?>" >
    <?php if ($this->reaction) : ?>
      <i class="ui-icon" style="background-image: url(<?php echo $this->reaction['icon'] ?>)" ></i>
    <?php else: ?>
      <i class="ui-icon" style="background-image: url(application/modules/Nestedcomment/externals/images/like.png)" > </i>
    <?php endif; ?>
    <?php echo $this->translate($this->caption) ?>
  </a>


  <a id="reaction_like_default_comments_<?php echo $this->subject->getGuid() ?>" href="javascript:void(0);"
    data-action="like"  class="<?php echo $this->like ?'dnone': '' ?>"
     >
      <i class="ui-icon" style="background-image: url(application/modules/Nestedcomment/externals/images/like.png)" ></i>
    <?php echo $this->translate($this->likeReactionCaption)?>
  </a>
</span>
