<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class='clear seaocore_settings_form'>
  <div class='settings'>

    <?php echo $this->form->render($this); ?>
  </div>
</div>

<script type="text/javascript">

	window.addEvent('domready',function () {
		if ($("is_submenu-1").checked) {
   		if ($("parent_id-wrapper")) {
    		$("parent_id-wrapper").show();
  		} 
	} else {
  		if ($("parent_id-wrapper")) {
    		$("parent_id-wrapper").hide();
  		} 
	}

	onSubMenuChange();

   	});
 function onSubMenuChange(){
 	if ($("is_submenu-1").checked) {
   		if ($("parent_id-wrapper")) {
    		$("parent_id-wrapper").show();
  		} 
	} else {
  		if ($("parent_id-wrapper")) {
    		$("parent_id-wrapper").hide();
  		} 
	}
 }
</script>