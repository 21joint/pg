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
<?php
echo $this->partial(
  '_iconsToolBarTip.tpl', 'sitereaction', $this->toolbar
);
?>

<script>
    en4.core.runonce.add(function() {
        en4.sitereaction.attachNestedCommentReaction(
        '<?php echo $this->subject->getType() ?>',
        '<?php echo $this->subject->getIdentity() ?>',
        0,
        '<?php echo $this->toolbar["order"] ?>',
        '<?php echo $this->toolbar["parent_comment_id"] ?>',
        'parent', 
        '<?php echo $this->toolbar["taggingContent"] ?>',
        '<?php echo $this->toolbar["showAsNested"] ?>',
        '<?php echo $this->toolbar["showAsLike"] ?>',
        '<?php echo $this->toolbar["showDislikeUsers"] ?>',
        '<?php echo $this->toolbar["showLikeWithoutIcon"] ?>',
        '<?php echo $this->toolbar["showLikeWithoutIconInReplies"] ?>');
    });
</script>

<?php if ($this->like): ?>
  <a id="like_comments_<?php echo $this->subject->getGuid() ?>"  <?php if($this->toolbar["showAsLike"]): ?> href="javascript:void(0);" 
     onclick="en4.nestedcomment.nestedcomments.unlike('<?php echo
  $this->subject->getType()?>', '<?php echo $this->subject->getIdentity() ?>', 0, '<?php echo $this->toolbar["order"] ?>', '<?php echo $this->toolbar["parent_comment_id"] ?>', 'parent', '<?php echo $this->toolbar["taggingContent"] ?>', '<?php echo $this->toolbar["showAsNested"] ?>', '<?php echo $this->toolbar["showAsLike"] ?>', '<?php echo $this->toolbar["showDislikeUsers"] ?>', '<?php echo $this->toolbar["showLikeWithoutIcon"] ?>', '<?php echo $this->toolbar["showLikeWithoutIconInReplies"] ?>');"
 <?php endif;?> data-current-reaction="<?php echo $this->like->reaction; ?>"
  >
      <?php if(!$this->toolbar["showLikeWithoutIcon"]):?>
        <?php if ($this->reaction) : ?>
          <img class="mbot5" width="16px" src="<?php echo $this->reaction['icon'] ?>" />
        <?php else: ?>
          <img class="mbot5" src="application/modules/Nestedcomment/externals/images/like.png" />
        <?php endif; ?>
      <?php endif; ?>
      <?php echo $this->translate($this->caption) ?></a>
   <?php else: ?>
  <a id="like_comments_<?php echo $this->subject->getGuid() ?>" href="javascript:void(0);" onclick="en4.nestedcomment.nestedcomments.like('<?php
     echo
     $this->subject->getType()
     ?>', '<?php echo $this->subject->getIdentity() ?>', 0, '<?php echo $this->toolbar["order"] ?>', '<?php echo $this->toolbar["parent_comment_id"] ?>', 'parent', '<?php echo $this->toolbar["taggingContent"] ?>', '<?php echo $this->toolbar["showAsNested"] ?>', '<?php echo $this->toolbar["showAsLike"] ?>', '<?php echo $this->toolbar["showDislikeUsers"] ?>', '<?php echo $this->toolbar["showLikeWithoutIcon"] ?>', '<?php echo $this->toolbar["showLikeWithoutIconInReplies"] ?>', '<?php echo 'like' ?>');">
    <?php if(!$this->toolbar["showLikeWithoutIcon"]):?>
      <img class="mbot5" src="application/modules/Nestedcomment/externals/images/like.png" />
      <?php endif; ?>
    <?php echo $this->translate($this->caption)?>
  </a>
<?php endif; ?>

