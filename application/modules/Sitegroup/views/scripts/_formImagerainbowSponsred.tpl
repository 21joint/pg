<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegroup
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _formImagerainbowSponsred.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitegroup/externals/scripts/mooRainbow.js');
$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitegroup/externals/styles/mooRainbow.css');
?>
<script type="text/javascript">
  window.addEvent('domready', function() { 
    var s = new MooRainbow('myRainbow2', { 
      id: 'myDemo2',
      'startColor': hexcolorTonumbercolor("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.sponsored.color', '#FC0505') ?>"),
      'onChange': function(color) {
        $('sitegroup_sponsored_color').value = color.hex;
      }
    });
		
    showsponsored("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.sponsored.image', 1) ?>")
		
  });
</script>

<?php
echo '
	<div id="sitegroup_sponsored_color-wrapper" class="form-wrapper">
		<div id="sitegroup_sponsored_color-label" class="form-label">
			<label for="sitegroup_sponsored_color" class="optional">
				' . $this->translate('Sponsored Label Color') . '
			</label>
		</div>
		<div id="sitegroup_sponsored_color-element" class="form-element">
			<p class="description">' . $this->translate('Select the color of the "SPONSORED" labels. (Click on the rainbow below to choose your color.)') . '</p>
			<input name="sitegroup_sponsored_color" id="sitegroup_sponsored_color" value=' . Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroup.sponsored.color', '#FC0505') . ' type="text">
			<input name="myRainbow2" id="myRainbow2" src="'. $this->layout()->staticBaseUrl . 'application/modules/Sitegroup/externals/images/rainbow.png" link="true" type="image">
		</div>
	</div>
'
?>

<script type="text/javascript">
  function showsponsored(option) {
    if(option == 1) {
      $('sitegroup_sponsored_color-wrapper').style.display = 'block';
    }
    else {
      $('sitegroup_sponsored_color-wrapper').style.display = 'none';
    }
  }
</script>