<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: requireuser.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<div class="prg-auth">
  <div class="holder-form holder-forgot d-flex w-100 h-100">
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
        href="<?php echo $this->baseUrl(); ?>" data-dismiss="modal">
        <i aria-hidden="true" class="fa fa-times"></i>
      </a>
      <div class="d-flex align-items-center mx-auto h-100 p-4 p-md-5">
        <?php if ($this->form): ?>
          <?php echo $this->form->render($this); ?>
        <?php else: ?>
          <?php echo $this->translate('Please sign in to continue.'); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
