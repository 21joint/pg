<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: addoffer.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class='global_form_popup'>
  <?php echo $this->form->render($this); ?>
</div>


<script type="text/javascript">
	
	window.addEvent('domready',function () {

    if ($('expiry_date-minute')) {
      $('expiry_date-minute').hide();
    }
    if ($('expiry_date-ampm') ) {
      $('expiry_date-ampm').hide();
    }
    if ($('expiry_date-hour')) {
      $('expiry_date-hour').hide();
    }
    onEndDateChange();
    
  });
 function onEndDateChange()
 {
  
  if ($("end_date-0").checked) {
   if ($("expiry_date-wrapper")) {
    $("expiry_date-wrapper").show();
  } 
} else {
  if ($("expiry_date-wrapper")) {
    $("expiry_date-wrapper").hide();
  } 
}

}
</script>