<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>



<script type="text/javascript">
  function skipForm() {
    document.getElementById("skip").value = "skipForm";
    $('SignupForm').submit();
  }
  function finishForm() {
    document.getElementById("nextStep").value = "finish";
  }
</script>


<div class="holder-form holder-forgot d-flex w-100 h-100">

<!-- place for partial  -->
<div class="left-side col-xl-6 col-lg-6 pl-0 pr-0 d-none d-sm-block">
    <?php echo $this->partial('login_partial/_left-side.tpl', 'sdparentalguide'); ?>
</div>

<!-- form  and tip message-->
<div class="right-side col-xl-6 col-lg-6 ">

  <div class="col-xl-7 col-lg-7 mx-auto h-100 w-100 d-flex align-items-center px-0">
    <?php echo $this->partial($this->script[0], $this->script[1], array(
      'form' => $this->form
    )) ?>
  </div>

</div>

</div>
