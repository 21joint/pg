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
  <?php if ($this->success): ?>

    <script type="text/javascript">
      var item_id = 'music_playlist_item_<?php echo $this->playlist_id ?>';
      if (parent.$(item_id))
          parent.$(item_id).destroy();
      else
          parent.location.href = '<?php echo $this->url(array(), 'sitepagemusic_general') ?>';
      setTimeout(function() {
        parent.Smoothbox.close();
      }, 1000 );
    </script>
    <div class="global_form_popup_message">
      <?php echo $this->translate('The selected playlist has been deleted.') ?>
    </div>

  <?php else: // success == false ?>

  <form method="POST" action="<?php echo $this->url() ?>">
    <div>
      <h3><?php echo $this->translate('Delete Playlist?') ?></h3>
      <p>
        <?php echo $this->translate('Are you sure that you want to delete the page playlists titled "%1$s" last modified %2$s? It will not be recoverable after being deleted.', $this->playlist->title, $this->timestamp($this->playlist->modified_date)) ?>
      </p>

      <p>&nbsp;</p>

      <p>
        <input type="hidden" name="playlist_id" value="<?php echo $this->playlist_id?>"/>
        <button type='submit'><?php echo $this->translate('Delete') ?></button>
        <?php echo $this->translate("or") ?> <a href="javascript:void(0);" onclick="parent.Smoothbox.close();"><?php echo $this->translate("cancel") ?></a>
      </p>
    </div>
  </form>
  <?php endif; ?>

</div>

<?php if( @$this->closeSmoothbox ): ?>
<script type="text/javascript">
  TB_close();
</script>
<?php endif; ?>

