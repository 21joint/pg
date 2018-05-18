<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2 class="fleft">
	<?php echo $this->translate('Credits, Reward Points and Virtual Currency - User Engagement Plugin');?>
</h2>
<?php if( count($this->navigation) ): ?>
	<div class='seaocore_admin_tabs clr'>
		<?php
    // Render the menu
    //->setUlClass()
		echo $this->navigation()->menu()->setContainer($this->navigation)->render()
		?>
	</div>

<?php endif; ?>

<h3>
	<?php echo $this->translate("Manage Modules") ?>
</h3>
<p>
	<?php echo $this->translate('Here, you can manage various modules to enable users to earn, spend and purchase credits for that module using this plugin. If you do not want users to earn, spend and purchase credits for a particular module, then simply disable that module from here.'); ?>
</p>
<br/>
<a href="<?php echo $this->url(array('module' => 'sitecredit', 'controller' => 'module', 'action' => 'paypal-guidelines'), 'admin_default', true) ?>" class="buttonlink" style="background-image:url(<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/help.gif);padding-left:23px;"><?php echo $this->translate("Guidelines for minor modification for credit redemption to work with PayPal enabled on your website in case of user subscription module."); ?></a>
<br/>
<?php if(count($this->modulesList)): ?>
	<form id='manage_module_form' method="post" action="<?php echo $this->url();?>" onsubmit="return validateForm()" >
		<div id="error_box" style="color:red;"></div>
		<table class='admin_table' width="100%">
			<thead>
				<tr>
					<th><?php echo $this->translate("Module Name") ?></th>
					<th align="center"><?php echo $this->translate("Enabled") ?></th>
					<th align="center"><?php echo $this->translate("Minimum Credit Balance") ?></th>
					<th align="center"><?php echo $this->translate("Minimum Checkout Total") ?></th>
					<th align="center"><?php echo $this->translate("Limit for Credit Use (%)") ?></th>

				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->modulesList as $item): ?>
					<tr>
						<td><?php echo ucfirst($item->title); ?></td>
	
						<!--        If module enable then display disable and vice-versa.-->
						<td class="admin_table_centered">
							<?php echo ( ($item->integrated) ? $this->htmlLink(array('reset' => false, 'action' => 'enable-module', 'enable_module' => '0', 'name' => $item->name,'flag'=> $item->flag, 'integrated' => $item->integrated), $this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/enable.png', '', array('title' => $this->translate('Disable Module for Credits'))), array()) : $this->htmlLink(array('reset' => false, 'action' => 'enable-module', 'enable_module' => '1', 'name' => $item->name,'flag'=> $item->flag,'integrated' => $item->integrated), $this->htmlImage($this->layout()->staticBaseUrl .'application/modules/Sitecredit/externals/images/disable.png', '', array('title' => $this->translate('Enable Module for Credits'))))) ?>

						</td>
						<td class="admin_table_centered">
							<div id='<?php echo $item->name.'_'.$item->flag?>_minimum_credit_balance-wrapper' class="form-wrapper" style="display: block;">

								<div id='<?php echo $item->name.'_'.$item->flag?>_minimum_credit_balance-element' class="form-element">
									<input type="text" name='<?php echo $item->name.'_'.$item->flag?>_minimum_credit_balance' id='<?php echo $item->name.'_'.$item->flag?>_minimum_credit_balance'  value="<?php echo $item->minimum_credit ?>" onkeypress="return isNumberKey(event)" style="width: 7em">
								</div>
							</div>
						</td>
						<td class="admin_table_centered">
							<div id='<?php echo $item->name.'_'.$item->flag?>_minimum_checkout_balance-wrapper' class="form-wrapper" style="display: block;">

								<div id='<?php echo $item->name.'_'.$item->flag?>_minimum_checkout_balance-element' class="form-element">
									<input type="text" name='<?php echo $item->name.'_'.$item->flag?>_minimum_checkout_balance' id='<?php echo $item->name.'_'.$item->flag?>_minimum_checkout_balance'  value="<?php echo $item->minimum_checkout_total ?>" onkeypress="return isNumberKey(event)" style="width: 7em"> <?php echo Engine_Api::_()->getApi('core', 'sitecredit')->getCurrencySymbol();?>
								</div>
							</div>

						</td>
					<?php if(($item->name=='sitestore') && ($item->flag=='product') && ( Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.store.credit.redemption')!='all_store')): ?>
						<td class="admin_table_centered">
							<div id='<?php echo $item->name.'_'.$item->flag?>_percentage_checkout-wrapper' class="form-wrapper" style="display: none;">

								<div id='<?php echo $item->name.'_'.$item->flag?>_percentage_checkout-element' class="form-element">
									<input type="text" name='<?php echo $item->name.'_'.$item->flag?>_percentage_checkout' id='<?php echo $item->name.'_'.$item->flag?>_percentage_checkout'  value="<?php echo $item->percentage_checkout ?>" onkeypress="return isNumberDotKey(event)" style="width: 7em"> %
								</div>
							</div>
						</td>
					<?php else: ?>	
						<td class="admin_table_centered">
							<div id='<?php echo $item->name.'_'.$item->flag?>_percentage_checkout-wrapper' class="form-wrapper" style="display: block;">

								<div id='<?php echo $item->name.'_'.$item->flag?>_percentage_checkout-element' class="form-element">
									<input type="text" name='<?php echo $item->name.'_'.$item->flag?>_percentage_checkout' id='<?php echo $item->name.'_'.$item->flag?>_percentage_checkout'  value="<?php echo $item->percentage_checkout ?>" onkeypress="return isNumberDotKey(event)" style="width: 7em"> %
								</div>
							</div>
						</td>
					<?php endif; ?>					
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<center>
			<div id="submit-wrapper" class="form-wrapper">
				<div id="submit-label" class="form-label">&nbsp;</div>
				<div id="submit-element" class="form-element">
					<button name="submit" id="submit" type="submit">Save Changes</button>
				</div>
			</div>
		</center>
		<br />
	</form>
<?php else : ?>
	<div class="tip">
		<span>
			<?php echo $this->translate("Currently No module available for integration") ?>
		</span>
	</div>
<?php endif; ?>
<script type="text/javascript">
	function validateForm(){
		
		<?php  foreach ($this->modulesList as $item): ?>
		var first= document.getElementById("<?php echo $item->name.'_'.$item->flag?>_percentage_checkout").value;
		if(!(parseInt(first)>=0 && parseInt(first)<=100) && first!="")
		{
			document.getElementById("error_box").innerHTML="Limit for credit use should be a valid percentage ( between 0 to 100 ).";
			return false; 
		}
	<?php endforeach; ?>
	return true;
}	

function isNumberKey(evt) { 
	var charCode = (evt.charCode) ? evt.which : event.keyCode

	if (charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 46) 
		return false; 
	
	return true; 
} 
function isNumberDotKey(evt){
	var charCode = (evt.charCode) ? evt.which : event.keyCode

	if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) 
		return false; 

	return true; 
}    
</script>