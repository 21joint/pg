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
<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()->appendStylesheet($baseUrl . 'application/modules/Advancedpagecache/externals/styles/main.css');
?>
<?php include APPLICATION_PATH . "/application/modules/Advancedpagecache/views/scripts/admin_head.tpl";?>
<h3>
    <?php echo $this->translate("Manage Single User Caching") ?>
</h3>

<br/>

<div class="mbot10">
    <p>
        Single User caching will store the full page cache at the server respective to all the browsers.
In case you think the content for some of the pages change after every short interval, hence caching is not required. You can exclude those URLs by adding them below (for an idea you can refer default added URLs).
    </p>
</div>
<br/>
<div class="mbot10">
    <?php 
      echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'advancedpagecache', 'controller' => 'page-caching', 'action' => 'add-url'), $this->translate('Exclude URLs'), array('class' => 'smoothbox link_button'));
    ?>
</div>
<br/>
<?php if( empty($this->currentCache['ignoreUrl']) ): ?>

	<div class='tip'>
 		<span> No Url is added.</span>
 	</div>
<?php else: ?>
		
<div class="seaocore_cache_url">
  <div class='seaocore_cache'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>  	
	<?php endif; ?>
<script type="text/javascript">
function multiModify()
{ 
   return Smoothbox.open('<div class="aaf_show_popup"><h3>Remove URLs?</h3><br/><p>' + en4.core.language.translate('Are you sure that you want to delete selected URLs? This action cannot be undone.') + '</p><br/><button onclick="submitform()">' + en4.core.language.translate('Delete') + '</button> or <a href="javascript:void(0);" onclick="parent.Smoothbox.close();">' +en4.core.language.translate('cancel') + '</a></div>');
    
}
function submitform(){
  console.log(document.getElementById('multiignore_form'));
  document.getElementById('multiignore_form').submit();
  parent.Smoothbox.close();
  
}
</script>
