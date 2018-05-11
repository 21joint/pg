<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _formImagerainbow1.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Feedback/externals/scripts/mooRainbow.js');
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Feedback/externals/styles/mooRainbow.css'); 
?> 
<script type="text/javascript">
	window.addEvent('domready', function() { 
		var r = new MooRainbow('myRainbow1', { 
			id: 'myDemo1',
			'startColor': [58, 142, 246],
			'onChange': function(color) {
				$('feedback_button_color1').value = color.hex;
			}
		});
	});	
</script>

<?php
echo '
	<div id="feedback_button_color1-wrapper" class="form-wrapper">
		<div id="feedback_button_color1-label" class="form-label">
			<label for="feedback_button_color1" class="optional">
				'.$this->translate('Customize feedback button color').'
			</label>
		</div>
		<div id="feedback_button_color1-element" class="form-element">
			<p class="description">'.$this->translate('Select the color of the feedback button. (Click on the rainbow below to choose your color.)').'</p>
			<input name="feedback_button_color1" id="feedback_button_color1" value=' . Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.button.color1', '#0267cc') . ' type="text">
			<input name="myRainbow1" id="myRainbow1" src="'.$this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/rainbow.png" link="true" type="image">
		</div>
	</div>
'
?>
