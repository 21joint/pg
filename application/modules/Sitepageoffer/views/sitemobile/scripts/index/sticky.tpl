<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: sticky.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<form method="post" class="global_form_popup">
  <div>
    <?php if (empty($this->sticky)): ?>
      <h3><?php echo $this->translate('Featured Offer'); ?></h3>
    <?php else: ?>
      <h3><?php echo $this->translate('Remove as Featured Offer'); ?></h3>
    <?php endif; ?>
    <?php if (empty($this->sticky)): ?>
      <p>
        <?php echo $this->translate('Are you sure you want to make this offer as featured? Only one offer can be made featured, and this offer will then be shown alongside your Page\'s entry in the listing of all Pages of this community. It will also be shown on top of all your offers on your Page profile.'); ?>
      </p>
    <?php else: ?>
      <p>
        <?php echo $this->translate('Are you sure you want to remove this offer as featured?'); ?>
      </p>
    <?php endif; ?>
    <br />
    <p>
      <input type="hidden" name="confirm" value="<?php echo $this->offer_id ?>"/>
      <?php if (empty($this->sticky)): ?>
        <button type='submit' data-theme="b"><?php echo $this->translate('Make Featured'); ?></button>
      <?php else: ?>
        <button type='submit' data-theme="b"><?php echo $this->translate('Remove as Featured'); ?></button>
      <?php endif; ?>
    <div style="text-align: center"><?php echo $this->translate('or'); ?> </div>
    <a href="#" data-rel="back" data-role="button">
      <?php echo $this->translate('Cancel') ?>
    </a>
    </p>
  </div>
</form>

<?php if (@$this->closeSmoothbox): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>