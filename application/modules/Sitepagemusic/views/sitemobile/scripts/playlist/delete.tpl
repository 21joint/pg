<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: delete.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */ 
?>

<div class='global_form_popup'>
  <form method="POST" class="global_form" action="<?php echo $this->url() ?>">
    <div>
      <h3><?php echo $this->translate('Delete Playlist?') ?></h3>
      <p>
        <?php echo $this->translate('Are you sure that you want to delete the page playlists titled "%1$s" last modified %2$s? It will not be recoverable after being deleted.', $this->playlist->title, $this->timestamp($this->playlist->modified_date)) ?>
      </p>
      <br />
      <p>
        <input type="hidden" name="playlist_id" value="<?php echo $this->playlist_id?>"/>
        <button type='submit' data-theme="b" data-inline="true"><?php echo $this->translate('Delete') ?></button>
        <?php echo $this->translate("or") ?> 
          <a href="#" data-rel="back" data-role="button" data-inline="true">
            <?php echo $this->translate('Cancel') ?>
          </a>
      </p>
    </div>
  </form>
</div>

