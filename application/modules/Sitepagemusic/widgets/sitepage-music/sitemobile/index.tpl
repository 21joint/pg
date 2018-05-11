<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php if ($this->paginator->getTotalItemCount()): ?>

  <div class="sm-content-list">
    <ul data-role="listview" data-inset="false" id="browse_sitepagemusic">
      <?php foreach ($this->paginator as $playlist): ?>
        <li data-icon="arrow-r">
          <a href="<?php echo $playlist->getHref(); ?>">
            <p class="ui-li-aside">
              <b><?php echo $this->translate(array('%s play', '%s plays', $playlist->play_count), $this->locale()->toNumber($playlist->play_count)) ?></b>
            </p> 
          <?php
            if ($playlist->photo_id) :
              echo $this->itemPhoto($playlist, 'thumb.icon');
            else :?>
             <img   class="thumb.icon" alt="" src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagemusic/externals/images/nophoto_playlist_main.png" />
            <?php endif; ?>
            <h3><?php echo $playlist->getTitle() ?></h3>
            <p><?php echo $this->translate("in "); ?><strong><?php echo $playlist->page_title; ?></strong></p>
            <p>
              <?php echo $this->translate('Created by '); ?><b><?php echo $playlist->getOwner()->getTitle() ?></b>
            </p>
            <p>
              <?php echo $this->timestamp($playlist->creation_date) ?>
              -    
              <?php
              //count no. of tracks in a playlist
              $songs = (isset($this->songs) && !empty($this->songs)) ? $this->songs : $playlist->getSongs();

              $songCount = count($songs);
              ?>
    <?php echo $this->translate(array("%s track", "%s tracks", $songCount), $this->locale()->toNumber($songCount)) ?>
            </p>
          </a>
        </li>
    <?php endforeach; ?>
    </ul>
    <?php if ($this->paginator->count() > 1): ?>
      <?php 
      echo $this->paginationControl($this->paginator, null, null, array(
      'query' => $this->formValues,
    )); ?>
  <?php endif; ?>
  </div>
<?php else: ?>
  <div class="tip">
    <span>
  <?php echo $this->translate('There are no search results to display.'); ?>
    </span>
  </div>
<?php endif; ?>
