<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php if (count($this->paginator) > 0): ?>
  <?php if ($this->can_create): ?>
    <div class="profile-content-top-button" data-role="controlgroup" data-type="horizontal">	
      <a data-role="button" data-icon="plus" data-iconpos="left" data-inset = 'false' data-mini="true" data-corners="true" data-shadow="true" href='<?php echo $this->url(array('page_id' => $this->sitepageSubject->page_id, 'tab' => $this->identity), 'sitepagemusic_create', true) ?>' class='buttonlink icon_sitepagemusic_new'><?php echo $this->translate('Upload Music'); ?></a>
    </div>
  <?php endif; ?>

  <div class="sm-content-list">
    <ul data-role="listview" data-inset="false" >
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
      echo $this->paginationAjaxControl(
              $this->paginator, $this->identity, 'profile_sitepagemusic');
      ?>
    <?php endif; ?>
  </div>
<?php else: ?>	
  <div class="tip" id='sitepagemusic_search'>
    <span>
      <?php echo $this->translate('No music has been posted in this Page yet.'); ?>
      <?php if ($this->can_create): ?>
        <?php echo $this->translate('Be the first to %1$spost%2$s one!', '<a href="' . $this->url(array('page_id' => $this->sitepageSubject->page_id, 'tab' => $this->identity), 'sitepagemusic_create') . '">', '</a>'); ?>
      <?php endif; ?>
    </span>
  </div>	
<?php endif; ?>

