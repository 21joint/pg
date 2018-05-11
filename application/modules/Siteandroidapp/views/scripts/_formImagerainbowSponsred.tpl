<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteevent
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _formImageraionbowSponsored.tpl 6590 2014-01-02 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
$this->headScript()
        ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/scripts/mooRainbow.js');

$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/mooRainbow.css');
?>

<?php $siteandroidapp_menucolor = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteandroidapp.menucolor',''); ?>
<?php
        
?>

<script type="text/javascript">
    window.addEvent('domready', function () {
       var s = new MooRainbow('myRainbow2', {
          id: 'myDemo2',
          'startColor': hexcolorTonumbercolor("<?php echo $siteandroidapp_menucolor ?>"),
          'onChange': function (color) {
             $('siteandroidapp_menucolor').setStyles({'background-color': color.hex});
             $('siteandroidapp_menucolor').value = color.hex;
          }
       });
    });
</script>

<?php
echo '
	<div id="siteandroidapp_menucolor-wrapper" class="form-wrapper">
		<div id="siteandroidapp_menucolor-label" class="form-label">
			<label for="siteandroidapp_menucolor" class="optional">
				' . $this->translate('Menu Label Backgroud Color') . '
			</label>
		</div>
		<div id="siteandroidapp_menucolor-element" class="form-element">
			<p class="description">' . $this->translate('Select the color of the "MENU" labels. (Click on the rainbow below to choose your color. Please do not select more bright color)') . '</p>
                        <input name="myRainbow2" id="myRainbow2" src="' . $this->layout()->staticBaseUrl . 'application/modules/Siteevent/externals/images/rainbow.png" link="true" type="image">
			<input name="siteandroidapp_menucolor" id="siteandroidapp_menucolor" value="'.$siteandroidapp_menucolor.'" type="text"  style="background-color:'.$siteandroidapp_menucolor.';">
			
		</div>
	</div>
'
?>
