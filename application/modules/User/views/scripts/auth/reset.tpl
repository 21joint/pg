<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: reset.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>


<div class="holder-form holder-forgot d-flex w-100 h-100">

<!-- place for partial  -->
<div class="left-side col-xl-6 col-lg-6 pl-0 pr-0 d-none d-sm-block">
    <?php echo $this->partial('login_partial/_left-side.tpl', 'sdparentalguide'); ?>
</div>

<!-- form  and tip message-->
<div class="right-side col-xl-6 col-lg-6">

  <div class="col-xl-6 col-lg-6 mx-auto h-100 w-100 d-flex align-items-center px-0">
    <?php if( empty($this->reset) ): ?>

      <?php echo $this->form->render($this) ?>

      <?php else: ?>

      <div class="tip">
        <span>
          <?php echo $this->translate("Your password has been reset. Click %s to sign-in.", $this->htmlLink(array('route' => 'user_login'), $this->translate('here'))) ?>
        </span>
      </div>

    <?php endif; ?>
  </div>

</div>

</div>

