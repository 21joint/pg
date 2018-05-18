<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    _themeNavigationTextColor.tpl 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$getWebsiteName = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
$websiteStr = str_replace(".", "-", $getWebsiteName);
$directoryName = 'ios-' . $websiteStr . '-app-builder';
$appBuilderBaseFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/' . $directoryName . '/settings.php';
$colorPrimary = '#2979FF';
if (file_exists($appBuilderBaseFile)) {
    include $appBuilderBaseFile;
    if (isset($appBuilderParams['navigation_text_color']))
        $colorPrimary = $appBuilderParams['navigation_text_color'];
}
?>
<script type="text/javascript">
    function hexcolorTonumbercolor(hexcolor) {
        var hexcolorAlphabets = "0123456789ABCDEF";
        var valueNumber = new Array(3);
        var j = 0;
        if (hexcolor.charAt(0) == "#")
            hexcolor = hexcolor.slice(1);
        hexcolor = hexcolor.toUpperCase();
        for (var i = 0; i < 6; i += 2) {
            valueNumber[j] = (hexcolorAlphabets.indexOf(hexcolor.charAt(i)) * 16) + hexcolorAlphabets.indexOf(hexcolor.charAt(i + 1));
            j++;
        }
        return(valueNumber);
    }

    window.addEvent('domready', function () {

        var r = new MooRainbow('myRainbow6', {
            id: 'myDemo6',
            'startColor': hexcolorTonumbercolor("<?php echo $colorPrimary; ?>"),
            'onChange': function (color) {
                $('navigation_text_color').value = color.hex;
                $('navigation_text_color').style.backgroundColor = color.hex;
            }
        });
//    showfeatured("<?php // echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitestore.feature.image', 1)    ?>")
    });
</script>
<?php
echo '
	<div id="navigation_text_color-wrapper" class="form-wrapper">
		<div id="navigation_text_color-label" class="form-label">                
			<label for="navigation_text_color" class="optional">
				' . $this->translate('Navigation Text Color') . ' <span style="color:RED">*</span>
			</label>
		</div>
		<div id="navigation_text_color-element" class="form-element">	
                <p class="description">This will be the color of the Text that is shown at the header</p>
			<input onkeyup="changeBGColor()" style="background-color:' . $colorPrimary . ';" name="navigation_text_color" id="navigation_text_color" value=' . $colorPrimary . ' type="text">
			<input name="myRainbow6" id="myRainbow6" src="' . $this->layout()->staticBaseUrl . 'application/modules/Siteiosapp/externals/images/rainbow.png" link="true" type="image">
		</div>
	</div>
'
?>

<script type="text/javascript">
    function changeBGColor() {
        $('navigation_text_color').style.backgroundColor = $('navigation_text_color').value;
    }
</script>