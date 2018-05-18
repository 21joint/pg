<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<a href='<?php echo $this->url(array('module' => 'sitepage', 'controller' => 'settings'), 'admin_default', true) ?>' class="icon_sitepage_admin_back buttonlink fright"><?php echo $this->translate('BACK_TO_PAGEPLUGIN_ADMIN_PANEL') ?></a>
<h2><?php echo $this->translate('Directory / Pages - Reviews and Ratings Extension'); ?></h2>
<div class='tabs'>
  <?php
  // Render the menu
  echo $this->navigation()
          ->menu()
          ->setContainer($this->navigation)
          ->render();
  ?>
</div>
<?php include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_upgrade_messages.tpl'; ?>

<div class='clear sitepage_settings_form'>
  <div class='settings'>
    <?php echo $this->form->render($this) ?>
  </div>
</div>
<script type="text/javascript">
  $$('input[type=radio]:([name=sitepagereview_proscons])').addEvent('click', function(e){
    $(this).getParent('.form-wrapper').getAllNext(':([id^=sitepagereview_limit_proscons-element])').setStyle('display', ($(this).get('value')>0?'none':'none'));
  });
  if($('sitepagereview_proscons-1')){	
    $('sitepagereview_proscons-1').addEvent('click', function(){
      $('sitepagereview_limit_proscons-wrapper').setStyle('display', ($(this).get('value')>0?'block':'none'));
    });
  }
  if($('sitepagereview_proscons-0')){	
    $('sitepagereview_proscons-0').addEvent('click', function(){
      $('sitepagereview_limit_proscons-wrapper').setStyle('display', ($(this).get('value')>0?'block':'none'));
    });
  }
	
  window.addEvent('domready', function() { 
    if($('sitepagereview_proscons-1')) {
      var e4 = $('sitepagereview_proscons-1');
      $('sitepagereview_limit_proscons-wrapper').setStyle('display', (e4.checked ?'block':'none'));
    }
    if($('sitepagereview_proscons-0')) {
      var e5 = $('sitepagereview_proscons-0');
      $('sitepagereview_limit_proscons-wrapper').setStyle('display', (e5.checked?'none':'block'));
    }
  });
	
</script>