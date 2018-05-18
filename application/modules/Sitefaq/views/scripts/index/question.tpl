<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: question.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<div class="global_form_popup">
  <?php echo $this->form->render($this) ?>
</div>
<style type="text/css">
.global_form div.form-label {
  width: 50px;
}
.global_form_popup #submit-wrapper, .global_form_popup #cancel-wrapper{
  float:none;
}
.global_form input[type="text"] {width:304px;}
.global_form textarea{width:350px;}
</style>