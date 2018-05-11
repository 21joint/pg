<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    _themeButtonColor.tpl 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$getWebsiteName = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
$websiteStr = str_replace(".", "-", $getWebsiteName);
$directoryName = 'ios-' . $websiteStr . '-app-builder';
$appBuilderBaseFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/' . $directoryName . '/settings.php';
$colorPrimary = '#2979FF';
if (file_exists($appBuilderBaseFile)) {
    include $appBuilderBaseFile;
    if (isset($appBuilderParams['button_color']))
        $colorPrimary = $appBuilderParams['button_color'];
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

        var r = new MooRainbow('myRainbow7', {
            id: 'myDemo7',
            'startColor': hexcolorTonumbercolor("<?php echo $colorPrimary; ?>"),
            'onChange': function (color) {
                $('button_color').value = color.hex;
                $('button_color').style.backgroundColor = color.hex;
            }
        });
//    showfeatured("<?php // echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitestore.feature.image', 1)    ?>")
    });
</script>
<?php
echo '
	<div id="button_color-wrapper" class="form-wrapper">
		<div id="button_color-label" class="form-label">
			<label for="button_color" class="optional">
				' . $this->translate('Button Color') . ' <span style="color:RED">*</span>
			</label>
		</div>
		<div id="button_color-element" class="form-element">
                <p class="description">This will be the color of the navigation buttons</p>
			<input onkeyup="changeBGColor1()" style="background-color:' . $colorPrimary . ';" name="button_color" id="button_color" value=' . $colorPrimary . ' type="text">
			<input name="myRainbow7" id="myRainbow7" src="' . $this->layout()->staticBaseUrl . 'application/modules/Siteiosapp/externals/images/rainbow.png" link="true" type="image">
		</div>
	</div>
'
?>

<script type="text/javascript">
    function changeBGColor1() {
        $('button_color').style.backgroundColor = $('button_color').value;
    }
</script>
