<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: visibility.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<form method="post" class="global_form_popup">
  <div>
    <h3><?php echo $this->translate('Change Feedback visibility'); ?></h3>
    <p> <?php if($this->visibility == 'public') echo $this->translate('Are you sure you want to change the visibility of this Feedback to PRIVATE ?'); 
    					else echo $this->translate('Are you sure you want to change the visibility of this Feedback to PUBLIC ?'); ?> 
    </p>
    <br />
    <p>
      <input type="hidden" name="confirm" value="<?php echo $this->feedback_id?>"/>
      <button type='submit'><?php if($this->visibility == 'public') echo $this->translate('Make Private'); else echo $this->translate('Make Public');?></button>
      or <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'><?php echo $this->translate('cancel');?></a> </p>
  </div>
</form>

<?php if( @$this->closeSmoothbox ): ?>
	<script type="text/javascript">
  		TB_close();
	</script>
<?php endif; ?>