<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

	if( !empty($this->isModsSupport) ):
		foreach( $this->isModsSupport as $modName ) {
			echo "<div class='tip'><span>" . $this->translate("Note: You do not have the latest version of the '%s'. Please upgrade it to the latest version to enable its integration with Responsive Luminous Theme.", ucfirst($modName)) . "</span></div>";
		}
	endif;
?>

<?php 
$luminousThemeActivated = true;
$themeInfo = Zend_Registry::get('Themes', null);
if(!empty($themeInfo)):
  foreach($themeInfo as $key => $value):
    if($key != "luminous"):
      $luminousThemeActivated = false;
    endif;
  endforeach;
endif;

if((Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.isActivate', 0)) && empty($luminousThemeActivated)):
?>
  <div class="seaocore_tip">
    <span>
      <?php echo "Please activate the 'Luminous Theme' from 'Layout' >> 'Theme Editor', available in the admin panel of your site." ?>
    </span>
  </div>
<?php endif; ?>


<?php 
if(Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.isActivate', 0)):
  include APPLICATION_PATH . '/application/modules/Siteluminous/views/scripts/_theme_message.tpl'; 
endif;
?>

<h2>
  <?php echo $this->translate('Responsive Luminous Theme'); ?>
</h2>

<div class='tabs'>
  <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
</div>

<div class='seaocore_settings_form'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>