<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitevideo
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: add-url.tpl 6590 2016-3-3 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php echo $this->form->setAttrib('class', 'global_form_popup')->render($this) ?>

<?php if( @$this->closeSmoothbox ): ?>
	<script type="text/javascript">
  		TB_close();
	</script>
<?php endif; ?>
<style type="text/css">
#module_name-label label{
	font-weight:bold;
}
#module_name-element select{
margin:3px 0 10px;
}
</style>