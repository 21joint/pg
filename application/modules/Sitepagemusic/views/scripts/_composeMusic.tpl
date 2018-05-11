<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: _composeMusic.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php  if (Engine_Api::_()->core()->hasSubject() && in_array($this->subject()->getType(),array('sitepage_page','sitepageevent_event', 'siteevent_event'))):?>
<?php $subject = $this->subject();
 if(in_array($subject->getType(),array('siteevent_event'))):
    $subject = $this->subject()->getParent();
    if($subject->getType() != 'sitepage_page')
			return;
 endif;
?>
<style type="text/css">
 /*
ACTIVITY FEED COMPOSER  MUSIC
These styles are used for the attachment composer above the
main feed.
*/
#compose-music-activator,
#compose-music-menu span
{
 display: none !important;
}
</style>
 <?php
 if(in_array($subject->getType(),array('sitepageevent_event'))):
    $subject = Engine_Api::_()->getItem('sitepage_page', $subject->page_id);
 endif;
  //PACKAGE BASE PRIYACY START
    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      if (!Engine_Api::_()->sitepage()->allowPackageContent($subject->package_id, "modules", "sitepagemusic")) {
        return;
      }
    } else {
      $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($subject, 'smcreate');
      if (empty($isPageOwnerAllow)) {
        return;
      }
    }
 if (!Engine_Api::_()->sitepage()->isManageAdmin($subject, 'edit') &&!Engine_Api::_()->sitepage()->isManageAdmin($subject,'smcreate') ):
    return;
  endif; ?>
<?php
  $this->headScript()
       ->appendFile($this->layout()->staticBaseUrl . 'externals/soundmanager/script/soundmanager2'
           . (APPLICATION_ENV == 'production' ? '-nodebug-jsmin' : '' ) . '.js')
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitepagemusic/externals/scripts/core.js')
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitepagemusic/externals/scripts/player.js')
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitepagemusic/externals/scripts/composer_music.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/fancyupload/Swiff.Uploader.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/fancyupload/Fx.ProgressBar.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/fancyupload/FancyUpload2.js');
  $this->headLink()
    ->appendStylesheet($this->layout()->staticBaseUrl . 'externals/fancyupload/fancyupload.css');
  $this->headTranslate(array(
    'Overall Progress ({total})', 'File Progress', 'Uploading "{name}"',
    'Upload: {bytesLoaded} with {rate}, {timeRemaining} remaining.', '{name}',
    'Remove', 'Click to remove this entry.', 'Upload failed',
    '{name} already added.',
    '{name} ({size}) is too small, the minimal file size is {fileSizeMin}.',
    '{name} ({size}) is too big, the maximal file size is {fileSizeMax}.',
    '{name} could not be added, amount of {fileListMax} files exceeded.',
    '{name} ({size}) is too big, overall filesize of {fileListSizeMax} exceeded.',
    'Server returned HTTP-Status <code>#{code}</code>',
    'Security error occurred ({text})',
    'Error caused a send or load operation to fail ({text})',
  ));
?>
<script type="text/javascript">
  en4.core.runonce.add(function() {

    if (!Composer.Plugin.SitepageMusic)
      return;

    var type = 'wall';
    if (composeInstance.options.type) type = composeInstance.options.type;

    Asset.javascript('<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepagemusic/externals/scripts/composer_music.js', {
      onLoad:  function() {
				composeInstance.addPlugin(new Composer.Plugin.SitepageMusic({
					title : '<?php echo $this->string()->escapeJavascript($this->translate('Add Music')) ?>',
					lang : {
						'Add Music' : '<?php echo $this->string()->escapeJavascript($this->translate('Add Music')) ?>',
						'Select File' : '<?php echo $this->string()->escapeJavascript($this->translate('Select File')) ?>',
						'cancel' : '<?php echo $this->string()->escapeJavascript($this->translate('cancel')) ?>',
						'Loading...' : '<?php echo $this->string()->escapeJavascript($this->translate('Loading...')) ?>',
						'Loading song, please wait...': '<?php echo $this->string()->escapeJavascript($this->translate('Loading song, please wait...')) ?>',
						'Unable to upload music. Please click cancel and try again': '<?php echo $this->string()->escapeJavascript($this->translate('Unable to upload music. Please click cancel and try again')) ?>',
						'Song got lost in the mail. Please click cancel and try again': '<?php echo $this->string()->escapeJavascript($this->translate('Song got lost in the mail. Please click cancel and try again')) ?>'
					},
					requestOptions : {
						'url'  : en4.core.baseUrl  + 'sitepagemusic/playlist/add-song/format/json?ul=1'+'&type='+type+'&page_id='+<?php echo $subject->getIdentity() ?>
					},
					fancyUploadOptions : {
						'url'  : en4.core.baseUrl  + 'sitepagemusic/playlist/add-song/format/json?ul=1'+'&type='+type+'&page_id='+<?php echo $subject->getIdentity() ?>,
						'path' : en4.core.basePath + 'externals/fancyupload/Swiff.Uploader.swf',
						'verbose' : <?php echo ( APPLICATION_ENV == 'development' ? 'true' : 'false') ?>,
						'appendCookieData' : true,
						'typeFilter' : {
							'<?php echo $this->translate('Music') ?> (*.mp3,*.m4a,*.aac,*.mp4)' : '*.mp3; *.m4a; *.aac; *.mp4'
						}}
				})
			)}
		})
	});
</script>
<?php endif; ?>