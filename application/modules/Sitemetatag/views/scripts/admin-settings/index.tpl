<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>Social Meta Tags Plugin – Open Graph for Facebook, Google+, Pinterest and Twitter Cards</h2>
<?php if (count($this->navigation)): ?>
    <div class='seaocore_admin_tabs clr'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>
<div class='clear'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?> 
  </div>
</div>
<script>
	function updateTextFields(option) {

		if (option && $('image_photo_preview-element')) {
			$('image_photo_preview-wrapper').show();
			$('image_photo_preview-element').innerHTML = "<a href='" + option + "' target='_blank'><img src='" + option + "' style='max-width:300px;' ></a>";
		} else {
			$('image_photo_preview-wrapper').hide();
		}
	}
	en4.core.runonce.add(function() {
		if($('sitemetatag_default_image'))
			updateTextFields($('sitemetatag_default_image').value);
		toggleExtraTwitterCardFields('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting("sitemetatag.twittercards.enable", 1); ?>');
	});

	function toggleExtraTwitterCardFields(value) {
		el = $('sitemetatag_twitter_sitename-wrapper');
		value == 0 ? el.hide() : el.show();
	}
</script>