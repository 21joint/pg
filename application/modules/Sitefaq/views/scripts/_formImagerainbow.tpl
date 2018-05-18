<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _formImagerainbow.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
	$this->headScript()
			->appendFile($this->layout()->staticBaseUrl.'application/modules/Seaocore/externals/scripts/mooRainbow.js');
	$this->headLink()
			->prependStylesheet($this->layout()->staticBaseUrl.'application/modules/Seaocore/externals/styles/mooRainbow.css');
?>	
<script type="text/javascript">
	window.addEvent('domready', function() { 
		var r = new MooRainbow('myRainbow1', { 
			id: 'myDemo1',
			'startColor': [58, 142, 246],
			'onChange': function(color) { 
				$('featured_color').value = color.hex;
			}
		});
	});	
</script>

<?php
echo '
	<div id="featured_color-wrapper" class="form-wrapper">
		<div id="featured_color-element" class="form-element">
			<p class="description">'.$this->translate('Select the color of the "FEATURED" label. (Click on the rainbow below to choose your color.)').'</p>
			<input name="featured_color" id="featured_color" value=' . '#EC2415' . ' type="text">
			<input name="myRainbow1" id="myRainbow1" src="' . $this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/rainbow.png" link="true" type="image">
		</div>
	</div>
'
?>