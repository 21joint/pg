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
$songs = $this->playlist->getSongs();
?>
<?php 
  if(file_exists(APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl'))
    include APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/Adintegration.tpl';
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/payment_navigation_views.tpl'; ?>
<?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.communityads', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.admusicedit', 3) && $page_communityad_integration && Engine_Api::_()->sitepage()->showAdWithPackage($this->sitepage)): ?>
  <div class="layout_right" id="communityad_musicedit">

		<?php
			echo $this->content()->renderWidget("communityad.ads", array( "itemCount"=>Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.admusicedit', 3),"loaded_by_ajax"=>0,'widgetId'=>"page_musicedit")); 			 
		?>
  </div>
<?php endif; ?>

<div class="sitepage_viewpages_head">
  <?php echo $this->htmlLink($this->sitepage->getHref(), $this->itemPhoto($this->sitepage, 'thumb.icon', '', array('align' => 'left'))) ?>
  <h2>	
    <?php echo $this->sitepage->__toString(); ?>	
    <?php echo $this->translate('&raquo; '); ?>
   <?php echo $this->htmlLink($this->sitepage->getHref(array('tab'=>$this->tab_selected_id)), $this->translate('Music')) ?>
  </h2>
</div>
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
<script type="text/javascript">
//<![CDATA[
  en4.core.runonce.add(function(){

    //$('save-wrapper').inject($('art-wrapper'), 'after');

    // IMPORT SONGS INTO FORM
    if ($$('#music_songlist li.file').length) {
      $$('#music_songlist li.file').inject($('demo-list'));
      $$('#demo-list li span.file-name').setStyle('cursor', 'move');
      $('demo-list').show()
    }



    // SORTABLE PLAYLIST
    new Sortables('demo-list', {
      contrain: false,
      clone: true,
      handle: 'span',
      opacity: 0.5,
      revert: true,
      onComplete: function(){
        new Request.JSON({
          url: '<?php echo $this->url(array('controller'=>'playlist','action'=>'sort'), 'sitepagemusic_extended') ?>',
          noCache: true,
          data: {
            'format': 'json',
            'playlist_id': <?php echo $this->playlist->playlist_id ?>,
            'order': this.serialize().toString()
          }
        }).send();
      }
    });
    


    // RENAME SONG
    $$('a.song_action_rename').addEvent('click', function(){
      var origTitle = $(this).getParent('li').getElement('.file-name').get('text')
          origTitle = origTitle.substring(0, origTitle.length-6);
      var newTitle  = prompt('<?php echo $this->translate('What is the title of this song?') ?>', origTitle);
      var song_id   = $(this).getParent('li').id.split(/_/);
          song_id   = song_id[ song_id.length-1 ];

      if (newTitle && newTitle.length > 0) {
        newTitle = newTitle.substring(0, 60);
        $(this).getParent('li').getElement('.file-name').set('text', newTitle);
        new Request({
          url: '<?php echo $this->url(array('controller'=>'song', 'action'=>'rename'), 'sitepagemusic_extended') ?>',
          data: {
            'format': 'json',
            'song_id': song_id,
            'playlist_id': <?php echo $this->playlist->playlist_id ?>,
            'title': newTitle
          },
        }).send();
      }
      return false;
    });



    // REMOVE/DELETE SONG FROM PLAYLIST
    $$('a.song_action_remove').addEvent('click', function(){
      var song_id  = $(this).getParent('li').id.split(/_/);
          song_id  = song_id[ song_id.length-1 ];

      
      $(this).getParent('li').destroy();
      new Request.JSON({
        url: '<?php echo $this->url(array('controller'=>'song','action'=>'delete'), 'sitepagemusic_extended') ?>',
        data: {
          'format': 'json',
          'song_id': song_id,
          'playlist_id': <?php echo $this->playlist->playlist_id ?>
        }
      }).send();

      return false;
    });

});
//]]>
</script>
