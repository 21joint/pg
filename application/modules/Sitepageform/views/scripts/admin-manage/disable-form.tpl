<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: disableform.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class='global_form_popup'>
  <form method="POST" action="<?php echo $this->url() ?>">
    <div>
      <?php if ($this->status == 0): ?>
        <h3><?php echo $this->translate('Enable Form'); ?></h3>
        <p>
          <?php echo $this->translate('Are you sure you want to enable the Form for this Page? If enabled, the visitors to this Page will see the Form tab.'); ?>
        </p>
        <p>&nbsp;
        </p>
        <p>
          <input type="hidden" name="form_id" value="<?php echo $this->form_id ?>"/>
          <button type='submit'><?php echo $this->translate('Enable'); ?></button>
          <?php echo $this->translate(' or ') ?> <a href="javascript:void(0);" onclick="parent.Smoothbox.close();"><?php echo $this->translate('cancel') ?></a>
        </p>
      <?php elseif ($this->status == 1): ?>
        <h3><?php echo $this->translate('Disable Form'); ?></h3>
        <p>
          <?php echo $this->translate('Are you sure you want to disable the Form for this Page? If disabled, the visitors to this Page will not see the Form tab.'); ?>
        </p>
        <p>&nbsp;
        </p>
        <p>
          <input type="hidden" name="form_id" value="<?php echo $this->form_id ?>"/>
          <button type='submit'><?php echo $this->translate('Disable'); ?></button>
          <?php echo $this->translate(' or ') ?> <a href="javascript:void(0);" onclick="parent.Smoothbox.close();"><?php echo $this->translate('cancel') ?></a>
        </p>
      <?php endif; ?>
    </div>
  </form>
</div>

<?php if (@$this->closeSmoothbox): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>