<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
 $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl.'application/modules/Seaocore/externals/scripts/mooRainbow.js');
$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl.'application/modules/Seaocore/externals/styles/mooRainbow.css');
?>
<script type="text/javascript">
  window.addEvent('domready', function() {
    var s = new MooRainbow('myRainbow1', {
      id: 'myDemo1',
      'startColor': hexcolorTonumbercolor("<?php echo $this->bgcolor ?>"),
      'onChange': function(color) {
        $('background_color').value = color.hex;
      }
    });

  });
</script>

<?php
echo '
	<div id="background_color-wrapper" class="form-wrapper">
		<div id="background_color-label" class="form-label">
			<label for="background_color" class="optional">
				' . $this->translate('Background Color') . '
			</label>
		</div>
		<div id="background_color-element" class="form-element">
			<p class="description">' . $this->translate('Choose the background color of the search option block (Click on the rainbow below to choose your color.)') . '</p>
			<input name="background_color" id="background_color" value=' . $this->bgcolor . ' type="text">
			<input name="myRainbow1" id="myRainbow1" src="'. $this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/rainbow.png" link="true" type="image">
		</div>
	</div>
'
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



</script>
