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
<?php if (count($this->subNavigation)): ?>
	<div class='seaocore_admin_tabs clr'>
		<?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render() ?>
	</div>
<?php endif; ?>

<div class='clear'>
  <div class='settings sitemetatag_nonwidgetized_form'>
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
		if($('sitemetatag_nonwidgetized_image'))
			updateTextFields($('sitemetatag_nonwidgetized_image').value);
	});
</script>