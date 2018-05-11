<?php

?>
<?php $active = 'advancedactivity_admin_main_tagcheckin';
  include APPLICATION_PATH . '/application/modules/Advancedactivity/views/scripts/_adminAAFNav.tpl'; ?> 

<div class="seaocore_admin_tabs">
  <ul class="navigation">
    <li class="active">
      <a href="<?php echo $this->baseUrl() . '/admin/sitetagcheckin/settings/readme' ?>" ><?php echo $this->translate('Please go through these important points and proceed by clicking the button at the bottom of this page.') ?></a>

    </li>
  </ul>
</div>

<?php include_once APPLICATION_PATH . '/application/modules/Sitetagcheckin/views/scripts/admin-settings/faq_help.tpl'; ?>
<br />
	<button onclick="form_submit();"><?php echo $this->translate('Proceed to enter License Key') ?> </button>
	
<script type="text/javascript" >

function form_submit() {
	
	var url='<?php echo $this->url(array('module' => 'sitetagcheckin', 'controller' => 'settings'), 'admin_default', true) ?>';
	window.location.href=url;
}
</script>
