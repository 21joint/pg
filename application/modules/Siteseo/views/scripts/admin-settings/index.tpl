<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>Ultimate SEO / Sitemaps Plugin</h2>
<?php if (count($this->navigation)): ?>
    <div class='seaocore_admin_tabs clr'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>
<?php if ($this->isActivated): ?>
	<?php if (empty($this->googleIntegrated)): ?>
		<?php $here = '<a href="admin/siteseo/settings/support/target/3" target="_blank">here</a>'; ?>
		<div class="tip"><span><?php echo "You have not integrated Google Search Console with this plugin. Please integrate google search console from $here." ?></span></div>
	<?php endif; ?>
	<?php if (empty($this->hasSitemetatag)): ?>
		<?php $thisLink = '<a href="https://www.socialengineaddons.com/socialengine-social-meta-tags-plugin" target="_blank">this link</a>'; ?>
		<div class="tip"><span><?php echo "You do not have Social Meta Tags Plugin plugin. Please install it through $thisLink." ?></span></div>
	<?php elseif(empty($this->isSitemetatagActivated)): ?>
		<div class="tip"><span><?php echo "You have installed the Social Meta Tags plugin but have not activated it on your site. Please activate it first." ?></span></div>
	<?php endif; ?>
<?php endif; ?>
<div class='clear seaocore_settings_form'>
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

	// SHOW APPEND META TAGS SETTINGS ONLY WHEN OVERWRITE SETTING IS ENABLED
	function toggleOverwriteFields(value) {
		el = $('overwrite_fields-wrapper');
		value == 0 ? el.hide() : el.show();
	}

	en4.core.runonce.add(function() {
		toggleOverwriteFields('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting("siteseo.metatags.overwrite", 1); ?>');
	});
</script>