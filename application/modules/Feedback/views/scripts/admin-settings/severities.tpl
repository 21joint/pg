<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: severities.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('Feedback Plugin');?></h2>

<?php if( count($this->navigation) ): ?>
	<div class='seaocore_admin_tabs'> <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?> </div>
<?php endif; ?>

<h3>
  <?php echo $this->translate("Feedback Severities") ?>
</h3>

<p>
  <?php echo $this->translate("Create new severities below for the Feedback on your site. Below, you can set the sequence of items in the order in which they should appear to users in the create / edit form. To do so, drag-and-drop the items vertically and click on 'Save Order' to save the sequence.") ?>
</p>

<br />

<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'settings', 'action' => 'add-severity'), $this->translate('Add New Severity'), array('class' => 'smoothbox buttonlink seaocore_icon_add')) ?>

<br /><br />

<form id='saveorder_form' method='post' action='<?php echo $this->url(array('action' =>'severities')) ?>' style="overflow:hidden;">
	<input type='hidden'  name='order' id="order" value=''/>
	<div class="seaocore_admin_order_list" style="width:50%;">
		<div class="list_head">     

			<div style="width:30%;">
				<?php echo $this->translate("Severity Name") ?>
			</div>

			<div style="width:30%;" class="admin_table_centered">
				<?php echo $this->translate("Number of Times Used") ?>
			</div>

			<div style="width:30%;" class="admin_table_centered">
				<?php echo $this->translate("Options") ?>
			</div>

		</div>
		<div id='order-element'>
			<ul>
				<?php foreach ($this->severities as $severity): ?>
					<li>
						<input type='hidden'  name='order[]' value='<?php echo $severity->severity_id; ?>'>
						<div style="width:30%;">
							<td align="left"><?php echo $severity->severity_name?></td>
						</div>

						<div style="width:30%;" class="admin_table_centered">
							<td align="left"><?php echo $severity->getUsedCount()?></td>
						</div>

						<div style="width:30%;" class="admin_table_centered">
							<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'settings', 'action' => 'edit-severity', 'id' =>$severity->severity_id), $this->translate('edit'), array(
		                			'class' => 'smoothbox',
		              				)) ?> | <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'settings', 'action' => 'delete-severity', 'id' => $severity->severity_id), $this->translate('delete'), array(
		                			'class' => 'smoothbox',
		              				)) ?>
						</div>
					</li>
				<?php endforeach; ?>
	    </ul>
    </div>
  </div>
</form>
<br />
<button onClick="javascript:saveOrder(true);" type='submit' class="clear">
	<?php echo $this->translate("Save Order") ?>
</button>

<script type="text/javascript">

  var saveFlag=false;
  var origOrder;
	var changeOptionsFlag = false;

	function saveOrder(value){
		saveFlag = value;
		var finalOrder = [];
		var li = $('order-element').getElementsByTagName('li');
		for (i = 1; i <= li.length; i++)
			finalOrder.push(li[i]);

		$('order').value = finalOrder;
		$('saveorder_form').submit();
	}

  window.addEvent('domready', function(){
			var initList = [];
			var li = $('order-element').getElementsByTagName('li');
			for (i = 1; i <= li.length; i++)
					initList.push(li[i]);
			origOrder = initList;
			var temp_array = $('order-element').getElementsByTagName('ul');
			temp_array.innerHTML = initList;
			new Sortables(temp_array);
	});

	window.onbeforeunload = function(event){
		var finalOrder = [];
		var li = $('order-element').getElementsByTagName('li');
		for (i = 1; i <= li.length; i++)
			finalOrder.push(li[i]);

		for (i = 0; i <= li.length; i++){
			if(finalOrder[i]!=origOrder[i])
			{
				changeOptionsFlag = true;
				break;
			}
		}
	}
</script>