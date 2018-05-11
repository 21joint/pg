<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: reason-view.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="global_form_popup reason_popup">
	<h3>Reason : <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
		<?php echo $this->translate("Close X") ?></a></h3>
		<p><?php echo $this->result->reason ?></p>
		<input type="hidden" name="confirm" value="<?php echo $this->id?>"/>
	</div>


	<?php if( @$this->closeSmoothbox ): ?>
		<script type="text/javascript">
			TB_close();
		</script>
	<?php endif; ?>