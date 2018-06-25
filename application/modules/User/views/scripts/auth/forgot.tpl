<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: forgot.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<div class="holder-form holder-forgot d-flex w-100 h-100">

  <!-- place for partial  -->
  <div class="left-side col-xl-6 col-lg-6 pl-0 pr-0  d-none d-sm-block">
      <?php echo $this->partial('login_partial/_left-side.tpl', 'sdparentalguide'); ?>
  </div>

  <!-- form  and tip message-->
  <div class="right-side col-xl-6 col-lg-6 pl-0">

    <div class="col-xl-6 col-lg-6 mx-auto h-100 w-100 d-flex align-items-center ">
      <?php if( empty($this->sent) ): ?>


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

</div>
