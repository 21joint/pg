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

<?php if (count($this->paginator) > 0): ?>
<div class="sm-content-list">
  <ul data-role="listview" data-inset="false" >
    <?php foreach ($this->paginator as $item): ?>
      <li data-icon="arrow-r">
        <a href="<?php echo $item->getHref(); ?>" >
          <?php echo $this->itemPhoto($item, 'thumb.icon') ?>
          <h3><?php echo $item->getTitle(); ?></h3>
          <?php if (is_array($this->showContent) && $this->showContent): ?>
            <p>
              <?php if (in_array('postedDate', $this->showContent)): ?>
                <?php echo $this->timestamp(strtotime($item->creation_date)) ?>&nbsp;
              <?php endif; ?>
              <?php if (in_array('postedBy', $this->showContent)): ?>
                <?php echo $this->translate('posted by '); ?>
                <b><?php echo $item->getOwner()->getTitle() ?></b>
              <?php endif; ?>
            </p>
            <p>
            <?php if (in_array('commentCount', $this->showContent)): ?>
              <?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>&nbsp;
            <?php endif; ?>

            <?php if (in_array('reviewCreate', $this->showContent)): ?>
              <?php $sitepagereviewEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepagereview'); ?>
              <?php if ($sitepagereviewEnabled): ?>
                <?php echo $this->translate(array('%s review', '%s reviews', $item->review_count), $this->locale()->toNumber($item->review_count)) ?>&nbsp;
              <?php endif; ?>
            <?php endif; ?>

            <?php if (in_array('viewCount', $this->showContent)): ?>
              <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>&nbsp;
            <?php endif; ?>

            <?php if (in_array('likeCount', $this->showContent)): ?>
              <?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>
            <?php endif; ?>
            </p>
          <?php endif; ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
  </div>
<?php else: ?>
  <div class="tip" id='sitepageintg_search'>
    <span>
      <?php echo $this->translate('No content have been intregrated in this Page yet.'); ?>
    </span>
  </div>
<?php endif; ?>