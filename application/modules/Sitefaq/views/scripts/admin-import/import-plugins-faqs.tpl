<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: import-plugins-faqs.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
	function showLightbox() 
	{	
		$('import_form').innerHTML = "<div><center><b class='bold'>" + '<?php echo $this->string()->escapeJavascript($this->translate("Importing file content...")) ?>' + "</b></center><center class='mtop10'><img src='<?php echo $this->layout()->staticBaseUrl;?>application/modules/Sitefaq/externals/images/admin/loader.gif' alt='<?php echo $this->string()->escapeJavascript($this->translate("Importing file content...")) ?>' /></center></div>";
		$('import_form').style.display = 'none';
	}
</script>

<div id='import_form1' class="sitefaq_upload_csv_popup_loader"></div>

<div class='clear global_form_popup sitefaq_upload_csv_popup'>
  <div class='settings' id="import_form">
    <?php echo $this->form->render($this); ?>
  </div>
</div>