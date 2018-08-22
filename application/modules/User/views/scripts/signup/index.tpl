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


<style>
  form{
    max-height:100%!important;
  }
  .right-side {
   overflow-y: scroll;
  }
  @media(max-width:768px){ 

  }
</style>
<script type="text/javascript">
  function skipForm() {
    document.getElementById("skip").value = "skipForm";
    $('SignupForm').submit();
  }
  function finishForm() {
    document.getElementById("nextStep").value = "finish";
  }
</script>


<div class="row no-gutters h-100 holder-form holder-forgot">

<!-- place for partial  -->
<div class="left-side col-xl-6 col-lg-6 d-none d-sm-block">
    <?php echo $this->partial('login_partial/_left-side.tpl', 'sdparentalguide'); ?>
</div>

<!-- form  and tip message-->
<div class="right-side col-xl-6 col-lg-6 ">
  <!-- close btn -->
  <a class="position-absolute close-btn-x" href='<?php echo $this->baseUrl(); ?>'>
      <svg width="23px" aria-hidden="true" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-times fa-w-10 fa-9x"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg>
    </a>
  <div class="col-xl-7 col-lg-7 mx-auto h-100 w-100 d-flex  px-0">
    <?php echo $this->partial($this->script[0], $this->script[1], array(
      'form' => $this->form
    )) ?>
  </div>

</div>

</div>
<script>
var height = window.screen.width;

var holder =  document.getElementsByClassName('holder-form')[0];


if(height <  768){
  holder.classList.remove('h-100');
}



</script>