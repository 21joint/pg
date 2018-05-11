<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _formImagerainbow2.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
	window.addEvent('domready', function() { 
		
		var s = new MooRainbow('myRainbow2', { 
			id: 'myDemo2',
			'startColor': [58, 142, 246],
			'onChange': function(color) {
				$('feedback_button_color2').value = color.hex;
			}
		});
	});
	
</script>

<?php
echo '
	<div id="feedback_button_color2-wrapper" class="form-wrapper">
		<div id="feedback_button_color2-label" class="form-label">
			<label for="feedback_button_color2" class="optional">
				'. $this->translate('Customize feedback button hover color').'
			</label>
		</div>
		<div id="feedback_button_color2-element" class="form-element">
			<p class="description">'.$this->translate('Select the hover color of the feedback button. (Click on the rainbow below to choose your color.)').'</p>
			<input name="feedback_button_color2" id="feedback_button_color2" value=' . Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.button.color2', '#ff0000') . ' type="text">
			<input name="myRainbow2" id="myRainbow2" src="'.$this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/rainbow.png" link="true" type="image">
		</div>
	</div>
'
?>
