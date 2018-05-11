<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _themeColor.tpl 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<script type="text/javascript">
  function hexcolorTonumbercolor(hexcolor) {
    var hexcolorAlphabets = "0123456789ABCDEF";
    var valueNumber = new Array(3);
    var j = 0;
    if(hexcolor.charAt(0) == "#")
      hexcolor = hexcolor.slice(1);
    hexcolor = hexcolor.toUpperCase();
    for(var i=0;i<6;i+=2) {
      valueNumber[j] = (hexcolorAlphabets.indexOf(hexcolor.charAt(i)) * 16) + hexcolorAlphabets.indexOf(hexcolor.charAt(i+1));
      j++;
    }
    return(valueNumber);
  }

  window.addEvent('domready', function() {

    var r = new MooRainbow('myRainbow1', {
    
      id: 'myDemo1',
      'startColor':hexcolorTonumbercolor("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.theme.color', '#3FC8F4') ?>"),
      'onChange': function(color) {
        $('siteluminous_theme_color').value = color.hex;
      }
    });
//    showfeatured("<?php // echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitestore.feature.image', 1) ?>")
  });	
</script>

<?php
echo '
	<div id="siteluminous_theme_color-wrapper" class="form-wrapper">
		<div id="siteluminous_theme_color-label" class="form-label">
			<label for="siteluminous_theme_color" class="optional">
				' . $this->translate('Theme Color') . '
			</label>
		</div>
		<div id="siteluminous_theme_color-element" class="form-element">
			<p class="description">' . $this->translate('Select the theme color for your site. (Click on the rainbow below to choose your color.)') . '</p>
			<input name="siteluminous_theme_color" id="siteluminous_theme_color" value=' . Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.theme.color', '#3FC8F4') . ' type="text">
			<input name="myRainbow1" id="myRainbow1" src="'.$this->layout()->staticBaseUrl.'application/modules/Siteluminous/externals/images/rainbow.png" link="true" type="image">
		</div>
	</div>
'
?>

<script type="text/javascript">
//  function showfeatured(option) {
//    if(option == 1) {
//      $('siteluminous_theme_color-wrapper').style.display = 'block';
//    }
//    else {
//      $('siteluminous_theme_color-wrapper').style.display = 'none';
//    }
//  }
</script>