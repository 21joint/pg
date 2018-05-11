<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: delete.tpl 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>

<form method="post" class="global_form_popup">
	<div>
		<h3><?php echo $this->translate("Delete Image?") ?></h3>
		<p>
			<?php echo $this->translate("Are you sure that you want to delete this Image ? It will not be recoverable after being deleted.") ?>
		</p>
		<br />
		<p>
			<input type="hidden" name="confirm" value="<?php echo $this->listing_id?>"/>
			<button type='submit'><?php echo $this->translate("Delete") ?></button>
			<?php echo $this->translate(" or ") ?> 
			<a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
			<?php echo $this->translate("cancel") ?></a>
		</p>
	</div>
</form>

<?php if( @$this->closeSmoothbox ): ?>
	<script type="text/javascript">
		TB_close();
	</script>
<?php endif; ?>
