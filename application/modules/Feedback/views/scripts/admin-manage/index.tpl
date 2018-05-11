<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('Manage Feedback');?></h2>

<?php if( count($this->navigation) ): ?>
	<div class='seaocore_admin_tabs'>
    	<?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  	</div>
<?php endif; ?>

<p>
	<?php echo $this->translate('This page lists all the feedback posted by the users. Here, you can monitor feedback, delete them, make feedback featured / un-featured and set status for feedback.');?> 
</p>
<br />

<script type="text/javascript">
	var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction){

    if( order == currentOrder ) {
      	$('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } 
    else {
      	$('order').value = order;
      	$('order_direction').value = default_direction;
    }
    $('filter_form').submit();
  }

	function multiDelete()
	{
		return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete selected feedback?")) ?>');
	}

	function selectAll()
	{
		var i;
  	var multidelete_form = $('multidelete_form');
  	var inputs = multidelete_form.elements;
  	for (i = 1; i < inputs.length - 1; i++) {
    	if (!inputs[i].disabled) {
      		inputs[i].checked = inputs[0].checked;
    	}
  	}
	}
</script>

<div class='admin_search'>
	<?php echo $this->formFilter->render($this) ?>
</div>

<?php if( count($this->paginator) ): ?>

	<div class='admin_members_results'>
		<div>
				<?php echo $this->translate(array('%s feedback found.', '%s feedbacks found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
		</div>

		<?php echo $this->paginationControl($this->paginator, null, null, array('query' => $this->formValues,'pageAsQuery' => true,)); ?>
	</div>
	<br />

	<form id='multidelete_form' method="post" action="<?php echo $this->url(array('action'=>'multi-delete'));?>" onSubmit="return multiDelete()">
		<table class='admin_table feedback_admin_table'>
			<thead>
					<tr>
						<th style='width: 1%;' align="left"><input onclick="selectAll()" type='checkbox' class='checkbox'></th>
						<th style='width: 1%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('feedback_id', 'ASC');"><?php echo $this->translate('ID');?></a></th>
						<th style='width: 1%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('feedback_title', 'ASC');"><?php echo $this->translate('Title');?></a></th>
						<th style='width: 1%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('username', 'ASC');"><?php echo $this->translate('Owner');?></a></th>
						<th style='width: 1%;' class='admin_table_centered'><a href="javascript:void(0);" onclick="javascript:changeOrder('featured', 'ASC');"><?php echo $this->translate('Featured');?></a></th>
						<th style='width: 1%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('category_name', 'ASC');"><?php echo $this->translate('Category');?></a></th>
						<th style='width: 1%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('severity_name', 'ASC');"><?php echo $this->translate('Severity');?></a></th>
						<th style='width: 1%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('feedback_private', 'ASC');"><?php echo $this->translate('Visibility');?></a></th>
						<th style='width: 1%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('stat_name', 'ASC');"><?php echo $this->translate('Status');?></a></th>
						<th style='width: 1%;' class='admin_table_centered'><a href="javascript:void(0);" onclick="javascript:changeOrder('total_votes', 'ASC');"><?php echo $this->translate('Votes');?></a></th>
						<th style='width: 1%;' class='admin_table_centered'><a href="javascript:void(0);" onclick="javascript:changeOrder('comment_count', 'ASC');"><?php echo $this->translate('Comments');?></a></th>
						<th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'DESC');"><?php echo $this->translate('Creation');?></a></th>
						<th style='width: 2%;' class='admin_table_options' align="left"><?php echo $this->translate('Options');?></th>
				</tr>
			</thead>
			<tbody>
				<?php if( count($this->paginator) ): ?>
					<?php foreach( $this->paginator as $item ): ?>
						<?php if(empty($item->owner_id)): ?>
							<tr style="background:#e1f1fa;">
						<?php else: ?>
							<tr>
						<?php endif; ?>
							<td><input name='delete_<?php echo $item->feedback_id;?>' type='checkbox' class='checkbox' value="<?php echo $item->feedback_id ?>"/></td>
							<td><?php echo $item->feedback_id; ?></td>
							<?php $truncateText = Engine_Api::_()->seaocore()->seaocoreTruncateText($item->feedback_title, 5);?>
							<td class='admin_table_bold'><?php echo $this->htmlLink($item->getHref(), $truncateText, array('target' => '_blank', 'title' => $item->getTitle())) ?></td> 
							<td>
								<?php if(!empty($item->owner_id)): ?>
									<?php $truncateText = Engine_Api::_()->seaocore()->seaocoreTruncateText($item->getOwner()->getTitle(), 9);?>
									<?php echo $this->htmlLink($this->item('user', $item->owner_id)->getHref()	, $truncateText, array('target' => '_blank')) ?>
								<?php else: ?>			
									<font color = red ><?php echo $this->translate('Anonymous');?></font>	
								<?php endif; ?>
							</td>
							<?php if(empty($item->owner_id)): ?>
								<td align="center" class="admin_table_centered"> --- </td>
							<?php else:?>
								<?php if($item->featured == 1):?>
									<td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'featured', 'id' => $item->feedback_id, 'page' => $this->page), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/feedback_goldmedal1.gif', '', array('title'=> $this->translate('Remove as Featured')))) ?>            
								<?php else: ?>  
									<td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'featured', 'id' => $item->feedback_id, 'page' => $this->page), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/feedback_goldmedal0.gif', '', array('title'=> $this->translate('Make Featured')))) ?>
									</td>
								<?php endif; ?>
							<?php endif; ?>
							<td align="center">
								<?php $select_category = 0; ?>
								<?php foreach ($this->categories as $categories): ?>        
									<?php if($item->category_id == $categories->category_id):?>
										<?php echo $categories->category_name ?> 
										<?php $select_category = 1; ?> 
										<?php break; ?>
									<?php endif;?>
								<?php endforeach;?>
								<?php if($select_category == 0):?>
									---
								<?php endif;?>
							</td>
							
							<td align="center">
								<?php $select_severity = 0; ?>
								<?php foreach ($this->severities as $severities): ?>        
									<?php if($item->severity_id == $severities->severity_id):?>
										<?php echo $severities->severity_name ?> 
										<?php $select_severity = 1; ?> 
										<?php break; ?>
									<?php endif;?>
								<?php endforeach;?>
								<?php if($select_severity == 0):?>
									---
								<?php endif;?>
							</td>
							<?php if(!empty($item->owner_id)): ?>
								<?php if($item->feedback_private == 'public'): ?>
									<td><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'visibility', 'id' => $item->feedback_id, 'visible' => $item->feedback_private), $this->translate('Public') , array(
												'class' => 'smoothbox', 'title' => $this->translate('Make Private'))) ?></td>
								<?php else: ?>
									<td><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'visibility', 'id' => $item->feedback_id, 'visible' => $item->feedback_private), $this->translate('Private') , array(
												'class' => 'smoothbox', 'title' => $this->translate('Make Public'))) ?></td>
								<?php endif; ?>
							<?php else: ?>
									<td><?php echo $this->translate("Private");?></td>
							<?php endif; ?>		
							<td align="center">
								<?php $select_status = 0; ?>
								<?php foreach ($this->status as $status): ?>        
									<?php if($item->stat_id == $status->stat_id):?>
										<?php $str = $this->translate('(Edit)'); ?>
										<font color="<?php echo $status->stat_color ?>"><?php echo $status->stat_name; ?></font>
										<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'manage', 'action' => 'change-stat', 'id' => $item->feedback_id), $str , array(
											'class' => 'smoothbox')) ?>
										<?php $select_status = 1; ?>
										<?php break; ?>
									<?php endif;?>
								<?php endforeach;?>
								<?php if($select_status == 0):?>
									<?php $add = $this->translate('Add Status'); ?>
									<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'manage', 'action' => 'change-stat', 'id' => $item->feedback_id), $add , array('class' => 'smoothbox',)) ?>
								<?php endif;?>
							</td>
							<?php if(!empty($item->owner_id)): ?>          
								<td align="center" class="admin_table_centered"><?php echo $item->total_votes ?></td>
								<td align="center" class="admin_table_centered"><?php echo $item->comment_count ?></td>
							<?php else: ?>  
								<td align="center" class="admin_table_centered"> --- </td>
								<td align="center" class="admin_table_centered"> --- </td>
							<?php endif; ?>
							<?php  $truncateText = Engine_Api::_()->seaocore()->seaocoreTruncateText($item->creation_date, 10);?>
							<td align="center"><?php echo $truncateText; ?></td>
							
							<td class='admin_table_options'>
								<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'detail', 'id' => $item->feedback_id), $this->translate('Details'), array(
										'class' => 'smoothbox')) ?>
								|
								<?php echo $this->htmlLink(array('route' => 'feedback_detail_view', 'user_id' => $item->owner_id, 'feedback_id' => $item->feedback_id), $this->translate('view') , array('target'=>'_blank')
								) ?>
								|
								<?php echo $this->htmlLink(array('route' => 'feedback_edit', 'module' => 'feedback', 'controller' => 'index', 'action' => 'edit', 'feedback_id' => $item->feedback_id), $this->translate('edit'), array(
										)) ?>
								| 
								<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'delete', 'id' => $item->feedback_id), $this->translate('delete'), array(
									'class' => 'smoothbox',
								)) ?>   
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<br />
		<div class='buttons'>
				<button type='submit'><?php echo $this->translate('Delete Selected');?></button>
		</div>
	</form>
<?php else: ?>
	<div class="tip">
		<span>
			<?php echo $this->translate('No results were found.');?>
		</span>
	</div>
<?php endif; ?>
