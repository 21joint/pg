<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _themeLandingPageSignupButtonColor.tpl 2014-10-09 00:00:00Z SocialEngineAddOns $
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

    var r = new MooRainbow('myRainbow4', {
    
      id: 'myDemo4',
      'startColor':hexcolorTonumbercolor("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.landingpage.signupbtn', 'rgba(255,95,63,.5)') ?>"),
      'onChange': function(color) {
        $('siteluminous_landingpage_signupbtn').value = color.hex;
      }
    });
//    showfeatured("<?php // echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitestore.feature.image', 1) ?>")
  });	
</script>

<?php
echo '
	<div id="siteluminous_landingpage_signupbtn-wrapper" class="form-wrapper">
		<div id="siteluminous_landingpage_signupbtn-label" class="form-label">
			<label for="siteluminous_landingpage_signupbtn" class="optional">
				' . $this->translate('Landing Page Sign Up Button') . '
			</label>
		</div>
		<div id="siteluminous_landingpage_signupbtn-element" class="form-element">
			<p class="description">' . $this->translate('Select the color of the sign up button available on the landing page.  (Click on the rainbow below to choose your color.)<br>[Note:  If you will change the sign up button color from here then transparency in color will not be visible in the button as it is looking on our demo, but if you want to do so, please read our FAQ section.]') . '</p>
			<input name="siteluminous_landingpage_signupbtn" id="siteluminous_landingpage_signupbtn" value=' . Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.landingpage.signupbtn', 'rgba(255,95,63,.5)') . ' type="text">
			<input name="myRainbow4" id="myRainbow4" src="'.$this->layout()->staticBaseUrl.'application/modules/Siteluminous/externals/images/rainbow.png" link="true" type="image">
		</div>
	</div>
'
?>

<script type="text/javascript">
//  function showfeatured(option) {
//    if(option == 1) {
//      $('siteluminous_landingpage_signupbtn-wrapper').style.display = 'block';
//    }
//    else {
//      $('siteluminous_landingpage_signupbtn-wrapper').style.display = 'none';
//    }
//  }
</script>