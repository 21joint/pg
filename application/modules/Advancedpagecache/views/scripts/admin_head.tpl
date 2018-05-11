<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: admin_head.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>
Page Cache Plugin - Speed up your Website 
</h2>

<?php if( count($this->navigation) ): ?>
  <div class='seaocore_admin_tabs'>
    <?php
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>
<ul class="form-errors" >
<?php if( APPLICATION_ENV != 'production' ): ?>
  <ul class="form-errors" >
	<li>
  <ul class="errors">
    <li>
	 Note: Currently your site is in development mode hence page caching feature is disabled. Page caching features work only in Production mode. <a href="javascript:void(0)" onclick="changeEnvironmentMode('production', this);this.blur();this.getParent('.development_mode_warning').set('html', '<img src=\'application/modules/Core/externals/images/loading.gif\'>')">Click  here</a> to put your site in production mode.
	 </li>
  </ul>
  </li>
</ul>
<?php  endif; ?>  
<?php 
      $cache_SettingFile = APPLICATION_PATH . '/application/settings/cache.php';
      if( file_exists($cache_SettingFile) ) {
        $cache_Config = include $cache_SettingFile;
        $cache = $cache_Config['frontend']['core']['caching'];
      } else {
        $cache=true;
      }
if( empty($cache) ):?>
<ul class="form-errors" >
<li>
  <ul class="errors">
    <li>
	Note: You have disabled ‘Use Cache’ in the “Performance & Caching” settings. Please enable it from <?php echo $this->htmlLink(array('route'=>'admin_default','module'=>'core','controller'=>'settings','action'=>'performance'), $this->translate('here'), array('style' => "text-decoration: none;")) ?> before modifying the settings below.
	</li>
  </ul>
  </li>
  </ul>
<?php endif; ?>

