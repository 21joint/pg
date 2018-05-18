<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2><?php echo $this->translate('Directory / Pages - Offers Extension') ?></h2>

<?php if (count($this->navigation)): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<?php include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_upgrade_messages.tpl'; ?>

<script type="text/javascript">
function dismiss1(modName) {
	document.cookie= modName + "_dismiss_offer" + "=" + 1;
	$('dismiss_modules_offer').style.display = 'none';
}
</script>

<?php 
	$moduleName = 'sitepageoffer';
	if( !isset($_COOKIE[$moduleName . '_dismiss_offer']) ):
?>
<div id="dismiss_modules_offer">
	<div class="seaocore-notice">
		<div class="seaocore-notice-icon">
			<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/notice.png" alt="Notice" />
		</div>
<div style="float:right;">
	<button onclick="dismiss1('<?php echo $moduleName; ?>');"><?php echo $this->translate('Dismiss'); ?></button>
</div>
		<div class="seaocore-notice-text ">
			<?php echo $this->translate('We have moved these "Widget Settings" to "Layout Editor". You can change the desired settings of the respective widgets from "Layout Editor" by clicking on the "edit" link.');?>
		</div>	
	</div>
</div>
<?php endif; ?>


<div class='clear sitepage_settings_form'>
  <div class='settings'>
    <?php echo $this->form->render($this) ?>
  </div>
</div>