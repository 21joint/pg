<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: answer.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php if($this->self_question == 1):?>
	<div class="global_form_popup">	
		<div class="tip">
			<span>
				<?php echo $this->translate('Sorry, you can not answer this question as this question is asked by you.'); ?>
			</span>
		</div>
		<button onclick='javascript:parent.Smoothbox.close()'><?php echo $this->translate("Close");?></button>
	</div>
	<?php return; ?>
<?php endif;?>

<div class="global_form_popup faq_answer_popup">
  <?php echo $this->form->render($this) ?>
</div>
<?php if( @$this->closeSmoothbox ): ?>
	<script type="text/javascript">
		TB_close();
	</script>
<?php endif; ?>