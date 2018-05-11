<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: change-status.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<form method="post" class="global_form_popup">
  <div>
    <?php if($this->status=="approved") : ?>
      <h3><?php echo $this->translate("Approve Request?") ?></h3>
      <p>
        <?php echo $this->translate("Are you sure that you want to upgrade level for this member") ?>
      </p>
      <br />
      <p>
        <input type="hidden" name="confirm" value="<?php echo $this->id?>"/>
        <button type='submit'><?php echo $this->translate("Approve Request") ?></button>
        <?php echo $this->translate(" or ") ?> 
        <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
          <?php echo $this->translate("cancel") ?></a>
        </p>
      <?php else : ?>
        <h3><?php echo $this->translate("Cancel Request?") ?></h3>
        <p>
          <?php echo $this->translate("Are you sure that you want to cancel upgrade request for this member") ?>
        </p>
        <br />
        <p>
          <input type="hidden" name="confirm" value="<?php echo $this->id?>"/>
          <button type='submit'><?php echo $this->translate("Cancel Request") ?></button>
          <?php echo $this->translate(" or ") ?> 
          <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
            <?php echo $this->translate("cancel") ?></a>
          </p>
        <?php endif; ?>
      </div>
    </form>

    <?php if( @$this->closeSmoothbox ): ?>
      <script type="text/javascript">
        TB_close();
      </script>
    <?php endif; ?>
