<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepage
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<div id="sitepageprofile_music_player">
  
  
  <?php foreach ($this->paginator as $this->playlist): ?>
  <?php echo $this->partial('application/modules/Sitepagemusic/views/scripts/_Player.tpl', array(
    'playlist' => $this->playlist,
    'id' => 'music_profile_player',
    'hideStats' => true,
  )) ?>
  <?php endforeach;?>
  <script type="text/javascript">
  en4.core.runonce.add(function() {
    if( !$type(soundManager) || !$type(en4.sitepagemusic) ) {
      Asset.javascript('/externals/soundmanager/script/soundmanager2-nodebug-jsmin.js');
      Asset.javascript('/application/modules/Sitepagemusic/externals/scripts/core.js');
      Asset.javascript('/application/modules/Sitepagemusic/externals/scripts/player.js');
    }

    en4.sitepagemusic.player.enablePlayers();
    
    en4.core.shutdown.add(function() {
      soundManager.stopAll();
    });
  });
  </script>
</div>
