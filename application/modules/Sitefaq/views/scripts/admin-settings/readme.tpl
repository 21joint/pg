<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('FAQs, Knowledgebase, Tutorials & Help Center Plugin') ?></h2>
<div class="tabs">
	<ul class="navigation">
    <li class="active">
       <a href="<?php echo $this->baseUrl() .'/admin/sitefaq/settings/readme'?>" ><?php echo $this->translate('Please go through these important points and proceed by clicking the button at the bottom of this page.') ?></a>
    </li>
	</ul>
</div>		

<?php include_once APPLICATION_PATH . '/application/modules/Sitefaq/views/scripts/admin-settings/faq_help.tpl'; ?>
<br />
	<button onclick="form_submit();"><?php echo $this->translate('Proceed to enter License Key') ?> </button>
	
<script type="text/javascript" >

function form_submit() {
	
	var url='<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'settings'), 'admin_default', true) ?>';
	window.location.href=url;
}

</script>
