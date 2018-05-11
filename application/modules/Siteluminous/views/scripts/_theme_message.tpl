<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _theme_message.tpl 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<script type="text/javascript">
function disable_error_message() {
	document.cookie= "siteluminous_disable_theme_error" + "=" + 1;
	$('disable_error_messagemodules').style.display = 'none';
}
</script>

<?php
	if( !isset($_COOKIE['siteluminous_disable_theme_error']) ):
?>
<div id="disable_error_messagemodules">
	<div class="seaocore-notice">
		<div class="seaocore-notice-icon">
			<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/notice.png" alt="Notice" />
		</div>
<div style="float:right;">
	<button onclick="disable_error_message();"><?php echo $this->translate('Dismiss'); ?></button>
</div>
    <div class="seaocore-notice-text ">
        <?php
        echo "If you want to do any colors customization or high level CSS changes then please read 'Q: I want to change the color scheme of this theme. Is it possible with this Theme?' & 'Q: Can I add my custom CSS in this theme? If yes then how I can add it so that my changes do not get lost in case of theme up-gradations ?' FAQ from <a href='admin/siteluminous/settings/faq'>here</a>.";
        ?>
      </div>	
	</div>
</div>
<?php endif; ?>
