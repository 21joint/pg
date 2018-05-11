<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: option-edit.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
$this->headLink()
        ->appendStylesheet($this->layout()->staticBaseUrl
                . 'application/modules/Sitepageform/externals/styles/style_sitepageform.css')
?>
<?php if ($this->form): ?>
  <?php echo $this->form->render($this) ?>
<?php else: ?>
  <div class="global_form_popup_message">
    <?php echo $this->translate("Changes saved.") ?>
  </div>

  <script type="text/javascript">
    parent.onOptionEdit(
  <?php echo Zend_Json::encode($this->option) ?>,
  <?php echo Zend_Json::encode($this->htmlArr) ?>
      );
        (function() { parent.Smoothbox.close(); }).delay(1000);
  </script>
<?php endif; ?>