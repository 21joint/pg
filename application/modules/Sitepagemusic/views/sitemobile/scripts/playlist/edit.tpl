<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: edit.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<script type="text/javascript">

  sm4.core.runonce.add(function() { 
    if (DetectAllWindowsMobile() || DetectAllIos()) {
      $.mobile.activePage.find('#form-upload-music').css('display', 'none');
      $.mobile.activePage.find('#show_supported_message').css('display', 'block');
    } else {
      $.mobile.activePage.find('#form-upload-music').css('display', 'block');
      $.mobile.activePage.find('#show_supported_message').css('display', 'none');
    } 
  });

</script>

<?php 
$songs = $this->playlist->getSongs();

$breadcrumb = array(
    array("href"=>$this->sitepage->getHref(),"title"=>$this->sitepage->getTitle(),"icon"=>"arrow-r"),
    array("href"=>$this->sitepage->getHref(array('tab' => $this->tab_selected_id)),"title"=>"Music","icon"=>"arrow-d"));

echo $this->breadcrumb($breadcrumb);
?>
<div class="layout_middle">
	<?php echo $this->form->render($this) ?>
	<div style="display:none;">
	  <?php if (!empty($songs)): ?>
	    <ul id="music_songlist">
	      <?php foreach ($songs as $song): ?>
	      <li id="song_item_<?php echo $song->song_id ?>" class="file file-success">
	        <a href="javascript:void(0)" class="song_action_remove file-remove"><?php echo $this->translate('Remove') ?></a>
	        <span class="file-name">
	          <?php echo $song->getTitle() ?>
	        </span>
	        (<a href="javascript:void(0)" class="song_action_rename file-rename"><?php echo $this->translate('rename') ?></a>)
	      </li>
	      <?php endforeach; ?>
	    </ul>
	  <?php endif; ?>
	</div>
</div>


<div style="display:none" id="show_supported_message" class='tip'>

  <span><?php echo $this->translate("Sorry, due to copyright laws and Apple restrictions, music cannot be uploaded from your device. You can edit a playlist from your Desktop."); ?><span>

</div>