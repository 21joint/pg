<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2><?php echo $this->translate('Directory / Pages - Form Extension') ?></h2>

<?php if (count($this->navigation)): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<?php include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_upgrade_messages.tpl'; ?>

<div class='clear sitepage_settings_form'>
  <div class='settings'>
    <?php echo $this->form->render($this) ?>
  </div>
</div>

<script type="text/javascript">
  $$('input[type=radio]:([name=sitepageform_formtabseeting])').addEvent('click', function(e){
    $(this).getParent('.form-wrapper').getAllNext(':([id^=sitepageform_captcha-element])').setStyle('display', ($(this).get('value')>0?'none':'none'));
  });
  if($('sitepageform_formtabseeting-1')) {
    $('sitepageform_formtabseeting-1').addEvent('click', function(){
      $('sitepageform_captcha-wrapper').setStyle('display', ($(this).get('value')>0?'block':'none'));
    });
  }
  if($('sitepageform_formtabseeting-0')) {	
    $('sitepageform_formtabseeting-0').addEvent('click', function(){
      $('sitepageform_captcha-wrapper').setStyle('display', ($(this).get('value')>0?'block':'none'));
    });
  }
	
  window.addEvent('domready', function() { 
    if($('sitepageform_formtabseeting-1')) {
      var e4 = $('sitepageform_formtabseeting-1');
      $('sitepageform_captcha-wrapper').setStyle('display', (e4.checked ?'block':'none'));
    }
    if($('sitepageform_formtabseeting-0')) {
      var e5 = $('sitepageform_formtabseeting-0');
      $('sitepageform_captcha-wrapper').setStyle('display', (e5.checked?'none':'block'));
    }
  });
</script>