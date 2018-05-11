<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemobile
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _activityText.tpl 6590 2013-06-03 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
    if(!isset($this->showAsLike) && Engine_Api::_()->seaocore()->checkEnabledNestedComment('advancedactivity')) {
      include_once APPLICATION_PATH . '/application/modules/Nestedcomment/views/sitemobile/scripts/_activitySettings.tpl';
  ;
      $this->showAsLike = $showAsLike;
      $this->showLikeWithoutIcon = $showLikeWithoutIcon;
    }
?>
<?php if (!Engine_Api::_()->seaocore()->checkEnabledNestedComment('advancedactivity')): ?>
<?php $this->showAsLike = 1; ?>
<?php endif; ?>
<?php $action = $this->action; ?>
<?php $commentCount = $action->getComments(true, true); ?>
<?php $isAddSep = false; ?>
<?php $likeCount = $action->likes()->getLikeCount(); ?>
<?php if ($this->showAsLike): ?>
    <?php if ($likeCount > 0 && (count($action->likes()->getAllLikesUsers()) > 0)): ?>
        <a href="javascript:void(0);" onclick='ActivityAppCommentPopup("<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'viewcomment', 'action_id' => $action->getIdentity()), 'default', 'true'); ?>", "feedsharepopup", <?php echo $action->getIdentity(); ?>)' class="feed_likes">
          <span data-target="reactions" data-total-like="<?php echo $this->locale()->toNumber($likeCount) ?>">
              <?php if ($this->allowReaction): ?>
                  <?php echo $this->likeReactionsLink($action); ?>
                <span><?php echo $this->locale()->toNumber($likeCount) ?></span>
            <?php else: ?>
                <?php $isAddSep = true; ?>
                <?php echo $this->translate(array('%s like', '%s likes', $likeCount), $this->locale()->toNumber($likeCount)); ?>
            <?php endif; ?>
          </span>
        </a>
    <?php endif; ?>
    <div class="<?php if ($this->allowReaction): echo 'fright'; endif; ?>">
        <?php if ($commentCount > 0) : ?>
          <?php if ($isAddSep): ?>
            <span class="sep">-</span>
          <?php endif; ?>
          <a href="javascript:void(0);" onclick='ActivityAppCommentPopup("<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'viewcomment', 'action_id' => $action->getIdentity(), 'showLikeWithoutIcon' => $this->showLikeWithoutIcon), 'default', 'true'); ?>", "feedsharepopup", <?php echo $action->getIdentity(); ?>)' class="feed_comments">

            <span><?php echo $this->translate(array('%s comment', '%s comments', $commentCount), $this->locale()->toNumber($commentCount)); ?>
            </span>
          </a>
    <?php endif; ?>
    </div>
<?php else: ?>
    <?php if ($likeCount > 0 && (count($action->likes()->getAllLikesUsers()) > 0)): ?>
        <a href="javascript:void(0);" class="<?php
        if ($this->allowReaction && $this->showLikeWithoutIcon != 3): echo 'fleft';
        endif;
        ?>" onclick='ActivityAppCommentPopup("<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'viewcomment', 'action_id' => $action->getIdentity(), 'showLikeWithoutIcon' => $this->showLikeWithoutIcon), 'default', 'true'); ?>", "feedsharepopup", <?php echo $action->getIdentity(); ?>)' class="feed_likes">
            <?php if ($this->allowReaction && $this->showLikeWithoutIcon != 3): ?>
              <span data-target="reactions" data-total-like="<?php echo $this->locale()->toNumber($likeCount) ?>">
            <?php echo $this->likeReactionsLink($action); ?>
                <span><?php echo $this->locale()->toNumber($likeCount) ?></span>
              </span>
          <?php else: ?>
              <?php $isAddSep = true; ?>
              <?php if ($this->showLikeWithoutIcon != 3): ?>
                  <span><?php echo $this->translate(array('%s like', '%s likes', $likeCount), $this->locale()->toNumber($likeCount)); ?></span>
              <?php else: ?>
                  <span><?php echo $this->translate(array('%s vote up', '%s vote ups', $likeCount), $this->locale()->toNumber($likeCount)); ?></span>
              <?php endif; ?>
        <?php endif; ?>
        </a>
    <?php endif; ?>
    <div class="<?php if ($this->allowReaction && $this->showLikeWithoutIcon!= 3): echo 'fright'; endif; ?>">
      <?php $dislikeCount = $action->dislikes()->getDislikePaginator()->getTotalItemCount(); ?>
        <?php if ($dislikeCount > 0): ?>
          <?php if ($isAddSep): ?>
            <span class="sep">-</span>
          <?php endif; ?>
          <a href="javascript:void(0);" onclick='ActivityAppCommentPopup("<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'viewcomment', 'action_id' => $action->getIdentity(), 'showLikeWithoutIcon' => $this->showLikeWithoutIcon), 'default', 'true'); ?>", "feedsharepopup", <?php echo $action->getIdentity(); ?>)' class="feed_dislikes">
            <?php if ($this->showLikeWithoutIcon != 3): ?>
                <span><?php echo $this->translate(array('%s dislike', '%s dislikes', $dislikeCount), $this->locale()->toNumber($dislikeCount)); ?></span>
            <?php else: ?>
                <span><?php echo $this->translate(array('%s vote down', '%s vote downs', $dislikeCount), $this->locale()->toNumber($dislikeCount)); ?></span>
          <?php endif; ?>
          </a>
          <?php $isAddSep = true; ?>
      <?php endif; ?>

      <?php if ($commentCount > 0) : ?>
          <?php if ($isAddSep): ?>
              <span class="sep">-</span>
        <?php endif; ?>
          <a href="javascript:void(0);" onclick='ActivityAppCommentPopup("<?php echo $this->url(array('module' => 'advancedactivity', 'controller' => 'index', 'action' => 'viewcomment', 'action_id' => $action->getIdentity(), 'showLikeWithoutIcon' => $this->showLikeWithoutIcon), 'default', 'true'); ?>", "feedsharepopup", <?php echo $action->getIdentity(); ?>)' class="feed_comments">

            <span><?php echo $this->translate(array('%s comment', '%s comments', $commentCount), $this->locale()->toNumber($commentCount)); ?>
            </span>
          </a>
    <?php endif; ?>
    </div>
<?php endif; ?>
