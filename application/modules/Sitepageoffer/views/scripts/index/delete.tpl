<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: delete.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php if(empty($this->offer_page)):?>
	<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/payment_navigation_views.tpl'; ?>
	<div class="sitepage_viewpages_head">
		 <?php echo $this->htmlLink($this->sitepage->getHref(), $this->itemPhoto($this->sitepage, 'thumb.icon', '', array('align' => 'left'))) ?>
		<h2>	
			<?php echo $this->sitepage->__toString() ?>	
			<?php echo $this->translate('&raquo; '); ?>
       <?php echo $this->htmlLink($this->sitepage->getHref(array('tab'=>$this->tab_selected_id)), $this->translate('Offers')) ?>
		</h2>
	</div>
<?php endif;?>

<?php if(!empty($this->offer_page)):?>
	<form method="post" class="global_form_popup">
		<div>
			<h3><?php echo $this->translate('Delete Page Offer ?'); ?></h3>
			<p>
				<?php echo $this->translate('Are you sure that you want to delete this offer? It will not be recoverable after being deleted.'); ?>
			</p>
			<br />
			<p>
				<input type="hidden" name="confirm" value="<?php echo $this->offer_id ?>"/>
				<button type='submit'><?php echo $this->translate('Delete'); ?></button>
				or <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'><?php echo $this->translate('cancel'); ?></a>
			</p>
		</div>
	</form>
<?php else:?>
	<form method="post" class="global_form">
		<div>
			<div>
				<h3><?php echo $this->translate('Delete Page Offer ?'); ?></h3>
				<p>
					<?php echo $this->translate('Are you sure that you want to delete this offer? It will not be recoverable after being deleted.'); ?>
				</p>
				<br />
				<p>
					<input type="hidden" name="confirm" value="<?php echo $this->offer_id ?>"/>
					<button type='submit'><?php echo $this->translate('Delete'); ?></button>
					<?php echo $this->translate('or'); ?> <?php echo $this->htmlLink($this->sitepage->getHref(array('tab'=>$this->tab_selected_id)),$this->translate('cancel')) ?>
				</p>
			</div>
		</div>
	</form>
<?php endif;?>

<?php if (@$this->closeSmoothbox): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>