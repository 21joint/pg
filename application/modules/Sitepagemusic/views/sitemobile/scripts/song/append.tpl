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
    <?php echo $this->form->render($this) ?>
</div>

<script type="text/javascript">
  function updateTextFields() {
    if ($.mobile.activePage.find('#playlist_id').val() == 0) {
      $.mobile.activePage.find('#title-wrapper').show();
    } else {
      $.mobile.activePage.find('#title-wrapper').hide();
    }
  }
  sm4.core.runonce.add(updateTextFields);
</script>