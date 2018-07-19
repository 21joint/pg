<div class="row holder-form holder-forgot h-100">

  <!-- place for partial  -->
  <div class="left-side col-xl-6 col-lg-6 d-none d-sm-block">
      <?php echo $this->partial('login_partial/_left-side.tpl', 'sdparentalguide'); ?>
  </div>

  <!-- form  and tip message-->
  <div class="right-side col-xl-6 col-lg-6 postion-relative">

    <!-- close btn -->
    <a class="position-absolute close-btn-x" href='<?php echo $this->baseUrl(); ?>'>
      <i class="fa fa-times"></i>
    </a>

      <?php if (empty($this->sent)): ?>


          <?php echo $this->form->render($this) ?>

      <?php else: ?>

        <div class="tip">
        <span>
          <?php echo $this->translate("USER_VIEWS_SCRIPTS_AUTH_FORGOT_DESCRIPTION") ?>
        </span>
        </div>

      <?php endif; ?>

  </div>

</div>
