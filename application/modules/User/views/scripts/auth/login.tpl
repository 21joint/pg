<div class="prg-auth d-flex holder-form holder-forgot h-100">

  <!-- place for partial  -->
  <div class="col-sm-6 d-none d-sm-block p-0 left-side">
    <?php echo $this->partial(
      'login_partial/_left-side.tpl', 'sdparentalguide'
    ); ?>
  </div>

  <!-- form  and tip message-->
  <div class="col-sm-6 position-relative right-side">

    <!-- close btn -->
    <a
      class="d-flex position-absolute prg-auth--close align-items-center justify-content-center p-0 close"
      href='<?php echo $this->baseUrl(); ?>'>
      <i class="fa fa-times"></i>
    </a>

    <div class="d-flex align-items-center mx-auto h-100 p-4 p-md-5">
      <?php if (empty($this->sent)): ?>


        <?php echo $this->form->render($this) ?>

      <?php else: ?>

        <div class="tip">
        <span>
          <?php echo $this->translate(
            "USER_VIEWS_SCRIPTS_AUTH_FORGOT_DESCRIPTION"
          ) ?>
        </span>
        </div>

      <?php endif; ?>
    </div>

  </div>

</div>


