<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: append.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class='global_form_popup'>
    <?php if( isset($this->success) ): ?>
      <div class="global_form_popup_message" style="text-align:left;width:300px;">

      <?php if( $this->success ): ?>
       <p><?php echo $this->message ?></p>
       <br />
       
       <button onclick="parent.Smoothbox.close();" class="fleft mright10">
         &laquo; <?php echo $this->translate('Close') ?>
       </button>

       <button onclick="parent.window.location.href='<?php echo $this->playlist->getHref() ?>'" class="fleft">
         <?php echo $this->translate('Go to my playlist') ?> &raquo;
       </button>
       
      <?php elseif( !empty($this->error) ): ?>
        <pre style="text-align:left"><?php echo $this->error ?></pre>
      <?php else: ?>
        <p><?php echo $this->translate('There was an error processing your request.  Please try again later.') ?></p>
      <?php endif; ?>
      </div>
    <?php return; endif; ?>

    <?php echo $this->form->render($this) ?>
</div>

<script type="text/javascript">
  function updateTextFields() {
    if ($('playlist_id').value == 0) {
      $('title-wrapper').show();
    } else {
      $('title-wrapper').hide();
    }
    parent.Smoothbox.instance.doAutoResize();
  }
  en4.core.runonce.add(updateTextFields);
</script>