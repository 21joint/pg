<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemobile
 * @copyright  Copyright 2014-2015 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: viewreply.tpl 6590 2013-06-03 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php $likeCount = $this->likes->getTotalItemCount(); ?>
<?php $likeUrl = $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'get-all-like-user', 'type' => $this->subject()->getType(), 'id' => $this->subject()->getIdentity()), 'default', 'true'); ?>
<?php if ($this->showAsLike || $this->showLikeWithoutIcon != 3): ?>
    <div class="comments_likes fleft">
      <?php if ($likeCount): // LIKES ------------- ?>
        <?php if ($this->allowReaction): ?>
          <a href="javascript:void(0);" onclick='sm4.activity.openPopup("<?php echo $likeUrl; ?>", "feedsharepopup")' class="f_normal">
            <span class="like_icon"><?php echo $this->likeReactionsLink($this->subject()); ?></span>
            <span class="like_count"><?php echo $this->locale()->toNumber($likeCount) ?></span>
          </a>
        <?php else: ?>
            <a href="javascript:void(0);" onclick='sm4.activity.openPopup("<?php echo $likeUrl; ?>", "feedsharepopup")' class="comments_likes_count f_normal">
              <?php echo $this->translate(array('%s likes', '%s like', $likeCount), $this->locale()->toNumber($likeCount)) ?>
            </a>
        <?php endif; ?>
        <span class="sep">-</span>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php if (!$this->showAsLike && $this->showLikeWithoutIcon != 3): ?>
    <div class="comments_dislikes fleft">
        <?php $disLikeCount = Engine_Api::_()->getDbtable('dislikes', 'nestedcomment')->getDislikeCount($this->subject()); ?>
        <?php if ($disLikeCount): // LIKES ------------- ?>
            <?php if ($this->showDislikeUsers): ?>
              <a href="javascript:void(0);" onclick='sm4.activity.openPopup("<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'get-all-dislike-user', 'type' => $this->subject()->getType(), 'id' => $this->subject()->getIdentity()), 'default', 'true'); ?>", "feedsharepopup")' class="comments_dislikes_count">
                <?php echo $this->translate(array('%s dislikes', '%s dislike', $disLikeCount), $this->locale()->toNumber($disLikeCount)) ?></a>
          <?php else: ?>
              <a class="comments_dislikes_count"><?php echo $this->translate(array('%s dislikes', '%s dislike', $disLikeCount), $this->locale()->toNumber($disLikeCount)) ?></a>
          <?php endif; ?>
          <span class="sep">-</span>
      <?php endif; ?>
    </div>
<?php endif; ?>
<div><span id="comments_count"><?php echo $this->translate(array('%s comment', '%s comments', $this->comments->getTotalItemCount()), $this->locale()->toNumber($this->comments->getTotalItemCount())) ?></span></div>
