<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: addstat.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Feedback/externals/scripts/mooRainbow.js');
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Feedback/externals/styles/mooRainbow.css'); 
?>

<script type="text/javascript">
	window.addEvent('domready', function() { 
		var r = new MooRainbow('myRainbow', { 
			'startColor': [58, 142, 246],
			'onChange': function(color) {
				$('myInput').value = color.hex;
			}
		});
	});
</script>

<form method="post" class="global_form_popup">
	<div style="width:450px;height:400px;">
	  <div>
	    <h3><?php echo $this->translate("Add Status") ?></h3>
	    <table style="clear:both;">
	    	<tr>
	    		<td style="padding:3px;"><?php echo $this->translate("Status :") ?></td>
	    		<td style="padding:3px;">
						<input name="id" value="" id="id" type="hidden">
      			<input name="label" id="label" value="" class="text" type="text">
	    		</td>
	    	</tr>
	    	<tr valign="top">
	    		<td style="padding:3px;">
	    			<?php echo $this->translate("Color :") ?>
	    		</td>
	    		<td style="padding:3px;">
	    		<img id="myRainbow" src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Feedback/externals/images/rainbow.png" alt="[r]" width="16" height="16" />&nbsp;&nbsp;
	    			<input id="myInput" name="myInput" type="text" size="13" /><br />
	    			<span style="font-size:11px;"><?php echo $this->translate('(To choose color click on rainbow)');?></span>
	    			
	    		</td>
	    	</tr>
	    	<tr>
	    		<td></td>
	    		<td style="padding:3px;">
	    			<button name="submit" id="submit" type="submit"><?php echo $this->translate('Add Status');?></button>
     				<?php echo $this->translate(" or ") ?> <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'> <?php echo $this->translate("cancel") ?></a>
	    		</td>
	    	</tr>
	    </table>		
	  </div>
  </div>
</form>

<?php if( @$this->closeSmoothbox ): ?>
	<script type="text/javascript">
  		TB_close();
	</script>
<?php endif; ?>