<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php include APPLICATION_PATH . "/application/modules/Advancedpagecache/views/scripts/admin_head.tpl";?>
<?php if(!empty(Engine_Api::_()->getApi('settings', 'core')->getSetting('advancedpagecache.isActivate'))) : ?>
<?php if(!$this->isRootFileValid && $this->browserEnabled):?>
  <div class='tip'>
    <span style="width:100%;">For increasing page response speed, please customize the code from <?php 
      echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'advancedpagecache', 'controller' => 'settings', 'action' => 'edit-root-file'), $this->translate('here'), array('class' => 'smoothbox'));
    ?> .</span>
  </div>
<?php endif;?>


<div id="advancedpagecache_outer_div">
<div class="advancedpagecache_delete_caching">
<h3>Clear Cache</h3>
 <p>
  <?php if( !empty($this->freeSpace) ) : ?>
    <?php echo $this->freeSpace ?> GB</b> of the storage space is available. 
  <?php endif; ?>
 If you want to clear Single Users Cache and Multiple User Cache separately. Please do it from the buttons below: 
 </p>

    <div id="dismiss_modules">
                 <?php 
      echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'advancedpagecache', 'controller' => 'settings', 'action' => 'delete-partial'), $this->translate('Clear Multiple Users Cache'), array('class' => 'smoothbox link_button'));
      ?>
                <?php 
      echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'advancedpagecache', 'controller' => 'settings', 'action' => 'delete-browser'), $this->translate('Clear Single User Cache'), array('class' => 'smoothbox link_button'));
      ?>
      </div>
      </div>
</div>
<?php endif;?>

<div class="seaocore_settings_form">
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>	

<script type="text/javascript">
//<![CDATA[
function updateFields() {
  $('div[id$=-wrapper][id^=file_]').hide();
  $('div[id$=-wrapper][id^=memcache_]').hide();
  $('div[id$=-wrapper][id^=xcache_]').hide();
  $('div[id$=-wrapper][id^=redis_]').hide();
  $('div[id$=-wrapper][id^=utilization_space]').hide();
  $('div[id$=-wrapper][id^=automatic_clear]').hide();
  var enabled_browser = $('input[name=disable_browse]:checked')[0].get('value');
  var new_value = $('input[name=type]:checked')[0].get('value');
  if ('File' == new_value && enabled_browser == 1) {
    $('div[id$=-wrapper][id^=file_]').show();
    $('div[id$=-wrapper][id^=utilization_space]').show();
    $('div[id$=-wrapper][id^=automatic_clear]').show();
  } else if ('Memcached' == new_value && enabled_browser == 1)
    $('div[id$=-wrapper][id^=memcache_]').show();
  else if ('Xcache' == new_value && enabled_browser == 1)
    $('div[id$=-wrapper][id^=xcache_]').show();
  else if ('Engine_Cache_Backend_Redis' == new_value)
    $('div[id$=-wrapper][id^=redis_]').show();
}
function updatebrowserFields(){
  $('div[id$=-wrapper][id^=browser_lifetime]').hide();
  $('div[id$=-wrapper][id^=type]').hide();
  $('div[id$=-wrapper][id^=cache_id_prefix]').hide();
var new_value = $('input[name=disable_browse]:checked')[0].get('value');
if(new_value == 1){
  $('div[id$=-wrapper][id^=cache_id_prefix]').show();
  $('div[id$=-wrapper][id^=browser_lifetime]').show();
  $('div[id$=-wrapper][id^=type]').show();
}
updateFields();
}
function updatepartialFields() {
  $('div[id$=-wrapper][id^=partial_lifetime]').hide();
  var new_value = $('input[name=disable_partial]:checked')[0].get('value');
  if(new_value == 1){
    $('div[id$=-wrapper][id^=partial_lifetime]').show();
  }
}
$(window).on('load', function(){
  updatepartialFields();
  updatebrowserFields();
  updateFields();
  <?php if ($this->isPost): ?>
  if ($('message').get('text').length) {
      $('message').show();
      $('message').inject( $('div.form-elements')[0], 'before');
  }
  <?php endif; ?>
   $('input[name=type]:disabled').getParent('li').addClass('disabled');
});

function showlightbox(){
  return Smoothbox.open('<div class="aaf_show_popup"><h3>Remove URLs?</h3><br/><p>' + en4.core.language.translate('You Want to enable default settings for this plugin.') + '</p><br/><button href='+'<?php echo $this->url(array('module' => 'advancedpagecache', 'controller' => 'settings', 'action' => 'default'), 'default', true) ?>'+'>' + en4.core.language.translate('Yes') + '</button> or <a href="javascript:void(0);" onclick="parent.Smoothbox.close();">' +en4.core.language.translate('cancel') + '</a></div>');
}
//]]>
</script>
