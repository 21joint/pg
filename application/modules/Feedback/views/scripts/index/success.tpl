<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: success.tpl 6590 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<div class='global_form' style="margin:15px 0 0 15px;">
  <form method="post" class="global_form" target='_parent'>
    <div>
      <div style="background:none;border:none;">
        <h3><?php echo $this->translate('Thank you for your Feedback');?></h3>
        <p> <?php echo $this->translate('Your Feedback was successfully posted. Would you like to add pictures to explain it better ?');?> </p>
        <br />
        <p>
          <input type="hidden" name="confirm" value="true"/>
          <button type='submit'><?php echo $this->translate('Add Pictures');?></button>
          <?php echo $this->translate('or');?>
          <?php if(!empty($this->owner_id)):?>
          	<?php echo $this->htmlLink(array('route' => 'feedback_manage', 'action' => 'manage'), $this->translate(' continue to my feedback'), array('target' => '_parent')) ?>
        	<?php elseif(!empty($this->show_browse)):?>
        		<?php echo $this->htmlLink(array('route' => 'feedback_browse', 'action' => 'browse', 'success_msg' => 1), $this->translate(' go to Feedback Forum'), array('target' => '_parent')) ?>
        	<?php else: ?>
        		<a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'><?php echo $this->translate('No, thank you.'); ?></a>
        	<?php endif;?>
        </p>
      </div>
    </div>
  </form>
</div>