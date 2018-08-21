<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('Feedback Plugin');?></h2>
<?php if( count($this->navigation) ): ?>
	<div class='seaocore_admin_tabs'> <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?> </div>
<?php endif; ?>
<?php include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_upgrade_messages.tpl'; ?>
<div class='clear seaocore_settings_form'>
  <div class='settings'> <?php echo $this->form->render($this) ?> </div>
</div>
<script type="text/javascript">
$('input[type=radio]:([name=feedback_post])').addEvent('click', function(e){
	    $(this).getParent('.form-wrapper').getAllNext(':([id^=feedback_option_post-element])').setStyle('display', ($(this).get('value')>0?'none':'none'));
	});
	
	$('feedback_post-1').addEvent('click', function(){
	    $('feedback_option_post-wrapper').setStyle('display', ($(this).get('value') == '1'?'none':'block'));
	});
	$('feedback_post-0').addEvent('click', function(){
	    $('feedback_option_post-wrapper').setStyle('display', ($(this).get('value') == '0'?'block':'none'));
	});
	
	window.addEvent('domready', function() { 
	 var e4 = $('feedback_post-1');
	 $('feedback_option_post-wrapper').setStyle('display', (e4.checked ?'none':'block'));
	 var e5 = $('feedback_post-0');
	 $('feedback_option_post-wrapper').setStyle('display', (e5.checked?'block':'none'));
	});

	$('input[type=radio]:([name=feedback_show_browse])').addEvent('click', function(e){
	    $(this).getParent('.form-wrapper').getAllNext(':([id^=feedback_default_visibility-element])').setStyle('display', ($(this).get('value')>0?'none':'none'));
	});
	
	$('feedback_show_browse-1').addEvent('click', function(){
	    $('feedback_default_visibility-wrapper').setStyle('display', ($(this).get('value') == '1'?'block':'none'));
	});
	$('feedback_show_browse-0').addEvent('click', function(){
	    $('feedback_default_visibility-wrapper').setStyle('display', ($(this).get('value') == '0'?'none':'block'));
	});
	
	window.addEvent('domready', function() { 
	 var e4 = $('feedback_show_browse-1');
	 $('feedback_default_visibility-wrapper').setStyle('display', (e4.checked ?'block':'none'));
	 var e5 = $('feedback_show_browse-0');
	 $('feedback_default_visibility-wrapper').setStyle('display', (e5.checked?'none':'block'));
	});

	$('input[type=radio]:([name=feedback_show_browse])').addEvent('click', function(e){
	    $(this).getParent('.form-wrapper').getAllNext(':([id^=feedback_public-element])').setStyle('display', ($(this).get('value')>0?'none':'none'));
	});
	
	$('feedback_show_browse-1').addEvent('click', function(){
	    $('feedback_public-wrapper').setStyle('display', ($(this).get('value') == '1'?'block':'none'));
	});
	$('feedback_show_browse-0').addEvent('click', function(){
	    $('feedback_public-wrapper').setStyle('display', ($(this).get('value') == '0'?'none':'block'));
	});
	
	window.addEvent('domready', function() { 
	 var e4 = $('feedback_show_browse-1');
	 $('feedback_public-wrapper').setStyle('display', (e4.checked ?'block':'none'));
	 var e5 = $('feedback_show_browse-0');
	 $('feedback_public-wrapper').setStyle('display', (e5.checked?'none':'block'));
	});
</script>
