<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: _Player.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php 
  
  $this->headScript()
       ->appendFile($this->layout()->staticBaseUrl . 'externals/soundmanager/script/soundmanager2'
           . (APPLICATION_ENV == 'production' ? '-nodebug-jsmin' : '' ) . '.js')
       ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitepagemusic/externals/scripts/core.js')
       ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitepagemusic/externals/scripts/player.js');
  
  $this->headTranslate(array(
    'Disable from Page Profile',
    'Play on Page Profile',
  ));
?>
<?php 
  $playlist = $this->playlist;
  $songs    = (isset($this->songs) && !empty($this->songs))
            ? $this->songs
            : $playlist->getSongs();

  $random   = '';
  for ($i=0; $i<6; $i++) { $d=rand(1,30)%2; $random .= ($d?chr(rand(65,90)):chr(rand(48,57))); }
?>

<?php if (0): ?>
  <div class="tip">
    <?php echo $this->translate('This playlist is private.') ?>
  </div>
<?php return; elseif (empty($songs) || empty($songs[0])): ?>
    <br />
    <div class="tip">
      <span>
        <?php echo $this->translate('There are no songs uploaded yet.') ?>
        <?php if($playlist->owner_id == $this->viewer_id || $this->can_edit == 1): ?>
          <?php echo $this->htmlLink($playlist->getHref(array(
            'route' => 'sitepagemusic_playlist_specific',
            'action' => 'edit',
          )), $this->translate('Why don\'t you add some?')) ?>
        <?php endif; ?>
      </span>
    </div>
    <br />
<?php return; endif; ?>
    
<script type="text/javascript">
  en4.core.runonce.add(function() {
    if( !$type(soundManager) || !$type(en4.sitepagemusic) ) {
      Asset.javascript('<?php echo $this->layout()->staticBaseUrl . 'externals/soundmanager/script/soundmanager2'
           . (APPLICATION_ENV == 'production' ? '-nodebug-jsmin' : '' ) . '.js' ?>');
      Asset.javascript('<?php echo $this->layout()->staticBaseUrl . 'application/modules/Sitepagemusic/externals/scripts/core.js' ?>');
      Asset.javascript('<?php echo $this->layout()->staticBaseUrl . 'application/modules/Sitepagemusic/externals/scripts/player.js' ?>');
    }

    en4.sitepagemusic.player.enablePlayers();
    
    en4.core.shutdown.add(function() {
      soundManager.stopAll();
    });
  });
</script>

<div class="music_player_wrapper" id="music_player_<?php echo $random ?>">

    <div class="music_player" <?php if (isset($this->id)) echo "id='{$this->id}'" ?> <?php if ($this->short_player): ?>style="display:none;"<?php endif; ?>>
      <div class="music_player_top">
        <div class="music_player_art">
          <?php echo $this->itemPhoto($playlist, null, $playlist->getTitle()) ?>
        </div>
        <div class="music_player_info">
          <div class="music_player_controls_wrapper">
            <div class="music_player_controls_right">
              <span class="music_player_button_launch_wrapper">
                <div class="music_player_button_launch_tooltip"><?php echo $this->translate('Pop-out Player') ?></div>
                  <?php echo $this->htmlLink($playlist->getHref(array(
                    'popout' => true
                  )), '', array('class' => 'music_player_button_launch')) ?>
              </span>
            </div>
            <div class="music_player_controls_left">
              <span class="music_player_button_prev"></span>
              <span class="music_player_button_play"></span>
              <span class="music_player_button_next"></span>

              <div class="music_player_controls_volume">
                <span class="music_player_controls_volume_toggle"></span>
                <span class="music_player_controls_volume_bar"><span class="volume_bar_1"></span></span>
                <span class="music_player_controls_volume_bar"><span class="volume_bar_2"></span></span>
                <span class="music_player_controls_volume_bar"><span class="volume_bar_3"></span></span>
                <span class="music_player_controls_volume_bar"><span class="volume_bar_4"></span></span>
                <span class="music_player_controls_volume_bar"><span class="volume_bar_5"></span></span>
              </div>
            </div>
          </div>
          <div class="music_player_trackname"></div>
          <div class="music_player_scrub">
            <div class="music_player_scrub_cursor"></div>
            <div class="music_player_scrub_downloaded"></div>
          </div>
          <div class="music_player_time">
            <div class="music_player_time_elapsed"></div>
            <div class="music_player_time_total"></div>
          </div>
        </div>
      </div>
      <ul class="music_player_tracks playlist_<?php echo $playlist->getIdentity() ?>">
        
        <?php foreach( $songs as $song ): if( !empty($song) ): ?>
        <li>

          <div class="music_player_tracks_add_wrapper">
            <div class="music_player_tracks_add_tooltip"><?php echo $this->translate('Add to my Playlist') ?></div>
            <?php if( $this->viewer()->getIdentity() && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('music')  && Engine_Api::_()->authorization()->isAllowed('music_playlist', null, 'create')): ?>
              <?php echo $this->htmlLink(array(
                'route' => 'sitepagemusic_song_specific',
                'action' => 'append',
                'song_id' => $song->song_id,
                'page_id' => $this->page_id
              ), '', array('class' => 'music_player_tracks_add', 'onclick' => 'appendPlaylist(this);return false') ) ?>
            <?php endif; ?>
          </div>
          <div class="music_player_tracks_name" title="<?php echo $song->getTitle() ?>">
            <?php echo $this->htmlLink($song->getFilePath(),
              $this->string()->truncate($song->getTitle(), 50),
              array(
                'class' => 'music_player_tracks_url',
                'type' => 'audio',
                'rel' => $song->song_id
            )) ?>
            <span class="music_player_tracks_plays">
              (<span><?php echo $song->playCountLanguagified() ?></span>)
            </span>

          </div>
        </li>
        <?php endif; endforeach; ?>

      </ul>
    </div>

    <?php if ($this->short_player): ?>
      <div class="music_player playlist_short_player">
        <div class="music_player_top">
          <div class="music_player_info">
            <div class="music_player_controls_wrapper">
              <div class="music_player_controls_left">
                <span class="music_player_button_play"></span>
                <div class="playlist_short_player_title">
                  <?php if (!empty($songs) && !empty($songs[0])) echo $songs[0]->getTitle() ?>
                </div>
                <div class="playlist_short_player_tracks">
                  <?php $songCount = count($songs); ?>
                  <?php echo $this->translate(array("%s track", "%s tracks", $songCount), $this->locale()->toNumber($songCount)) ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

<?php if ($this->popout): ?>
    <div class="sitepagemusic_playlist_info_date seaocore_gry_txt">
	    <?php echo $this->translate('Created %s ', $this->timestamp($this->playlist->creation_date)) ?>
	    -
      <?php echo $this->translate(array('%s play', '%s plays', $this->playlist->play_count), $this->locale()->toNumber($this->playlist->play_count)) ?>
      -
      <?php echo $this->translate(array('%s view', '%s views', $this->playlist->view_count), $this->locale()->toNumber($this->playlist->view_count)) ?>
      -
			<?php echo $this->translate(array('%s like', '%s likes', $this->playlist->like_count ), $this->locale()->toNumber($this->playlist->like_count )) ?>	      
    </div>
		<?php if( !empty($this->viewer_id) ): ?>
    <div class="sitepagemusic_playlist_options">
      <?php if( empty($this->hideLinks) ): ?>
      <?php echo $this->htmlLink(array(
        'module'=>'activity',
        'controller'=>'index',
        'action'=>'share',
        'route'=>'default',
        'type'=>'sitepagemusic_playlist',
        'id' => $this->playlist->getIdentity(),
        'format' => 'smoothbox'
      ), $this->translate("Share"), array('class' => 'smoothbox buttonlink icon_sitepages_share')); 
     ?>
      &nbsp;|&nbsp;
      <?php echo $this->htmlLink(array(
        'module'=>'core',
        'controller'=>'report',
        'action'=>'create',
        'route'=>'default',
        'subject'=>$this->playlist->getGuid(),
        'format' => 'smoothbox'
      ), $this->translate("Report"), array('class' => 'smoothbox buttonlink icon_sitepages_report')); ?>
      <?php endif; ?>
    </div>  
		<?php endif; ?>
    <?php  endif; ?>  
</div>

<script type="text/javascript">	
	function appendPlaylist(thisobj) 
	{
		var Obj_Url = thisobj.href;
		Smoothbox.open(Obj_Url);
	}
</script>