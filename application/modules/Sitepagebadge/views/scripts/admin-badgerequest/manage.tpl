<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction)
  {  
    if( order == currentOrder ) { 
      $('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } 
    else { 
      $('order').value = order;
      $('order_direction').value = default_direction;
    }
    $('filter_form').submit();
  }
  
  en4.core.runonce.add(function(){$$('th.admin_table_short input[type=checkbox]').addEvent('click', function(){ $$('input[type=checkbox]').set('checked', $(this).get('checked', false)); })});



	function multiDelete()
	{
		return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure that you want to delete the selected badge requests?")) ?>');
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
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2>
  <?php echo $this->translate('Directory / Pages - Badges Extension') ?>
</h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      echo $this->navigation()->menu()->setContainer($this->navigation)->render();
    ?>
  </div>
<?php endif; ?>

<h3>
  <?php echo $this->translate('Manage Badge Requests') ?>
</h3>
<p>
  <?php echo $this->translate("All the badge requests sent by admins of Directory Items / Pages have ben listed below. You can manage them by taking appropriate actions or delete them if necessary. You can also assign badges to the Pages corresponding to these requests.") ?>
</p><br />

<div class='admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>

<div class='clear'>			
	<?php if($this->paginator->getTotalItemCount()): ?>

		<div class='admin_members_results'>
			<div>
				<?php echo $this->translate(array('%s badge request found.', '%s badge requests found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
			</div>
			<?php echo $this->paginationControl($this->paginator); ?>
			</div>
		<br />

		<form id='multidelete_form' method="post" action="<?php echo $this->url(array('action'=>'multi-delete'));?>" onSubmit="return multiDelete()">
			<table class='admin_table' width="100%">
				<thead>
					<tr>				
						<th class='admin_table_short'><input type='checkbox' class='checkbox' /></th>

						<th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('engine4_sitepagebadge_badgerequests.page_id', 'DESC');"><?php echo $this->translate('Page ID'); ?></a></th>

						<th align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('engine4_sitepage_pages.title', 'DESC');"><?php echo $this->translate('Page Title'); ?></a></th>

						<th align="left"><a href="javascript:void(0);" title="<?php echo $this->translate('Requester Name') ?>" onclick="javascript:changeOrder('engine4_users.displayname', 'DESC');"><?php echo $this->translate('Name'); ?></a></th>

						<th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('engine4_users.user_id', 'DESC');"><?php echo $this->translate('Member Id'); ?></a></th>

						<th align="left"><?php echo $this->translate('Comments'); ?></th>

						<th align="left"><?php echo $this->translate('Email'); ?></th>

							<th align="left"><?php echo $this->translate('Contact No.'); ?></th>

						<th class="admin_table_centered"><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'DESC');" title="<?php echo $this->translate('Requested Date'); ?>"><?php echo $this->translate('Requested Date'); ?></a></th>					

						<th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('engine4_sitepagebadge_badgerequests.status', 'DESC');"><?php echo $this->translate('Status'); ?></a></th>

						<th align="left"><?php echo $this->translate('Options'); ?></th>

					</tr>	
				</thead>								
				<tbody>
					<?php foreach ($this->paginator as $item): ?>
						<tr>

							<td><input name='delete_<?php echo $item->badgerequest_id;?>' type='checkbox' class='checkbox' value="<?php echo $item->badgerequest_id ?>"/></td>	

							<td class='admin_table_centered admin-txt-normal'><?php echo $item->page_id;?>	</td>

							<td class='admin-txt-normal'><?php echo $this->htmlLink($this->item('sitepage_page', $item->page_id)->getHref(), Engine_Api::_()->sitepage()->truncation($item->title,10), array('title' => $item->title, 'target' => '_blank')) ?></td>

							<td title="<?php echo $this->item('user', $item->user_id)->getTitle()?>">
								<?php
									$display_name = $this->item('user', $item->user_id)->getTitle();
									$display_name = Engine_Api::_()->sitepage()->truncation($display_name,10);
									echo $this->htmlLink($this->item('user', $item->user_id)->getHref(), $display_name, array('target' => '_blank'))
								?>
							</td>		
		
							<td class="admin_table_centered admin-txt-normal"><?php echo $item->user_id;?>	</td>

							<td class="admin-txt-normal" title="<?php echo $item->user_comment;?>">
								<?php 
									if(Engine_String::strlen($item->user_comment) > 10) 
										echo $this->translate(Engine_Api::_()->sitepage()->truncation($item->user_comment,10));
									else 
										echo $item->user_comment;
								?>
							</td>

							<td class="admin-txt-normal" title="<?php echo $item->email;?>"><?php echo Engine_Api::_()->sitepage()->truncation($item->email, 16);?>	</td>

							<?php if(!empty($item->contactno)):?>
								<td class="admin-txt-normal"><?php echo $item->contactno;?>	</td>	
							<?php else : ?>
								<td class="admin_table_centered" ><?php echo "-" ?>	</td>	
							<?php endif;?>

							<td align="center" class="admin_table_centered"><?php echo $this->translate(gmdate('M d,Y',strtotime($item->creation_date))) ?></td>							
							<?php if($item->status == 1 ):?>
								<?php $status = 'Approved';?>
							<?php elseif($item->status == 2 ):?>
								<?php $status = 'Declined';?>
							<?php elseif($item->status == 3 ):?>
								<?php $status = 'Pending';?>
							<?php elseif($item->status == 4 ):?>
								<?php $status = 'Hold';?>
							<?php endif;?>
							<td class="admin_table_centered"><?php echo $status;?>	</td>			
							
							<td>
							<?php if($item->status == 1 || $item->status == 2): ?>
								<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitepagebadge', 'controller' => 'badgerequest', 'action' => 'change-status', 'badgerequest_id'=> $item->badgerequest_id), $this->translate('details'), array(
								'class' => 'smoothbox',
								)) ?> |

								<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitepagebadge', 'controller' => 'admin-manage', 'action' => 'assign-badge', 'page_id' => $item->page_id), $this->translate('change badge'), array(
								'class' => 'smoothbox',
								)) ?> |
							<?php else: ?>
								<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitepagebadge', 'controller' => 'badgerequest', 'action' => 'change-status', 'badgerequest_id'=> $item->badgerequest_id), $this->translate('take action'), array(
								'class' => 'smoothbox',
								)) ?> |
								
								<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitepagebadge', 'controller' => 'admin-manage', 'action' => 'assign-badge', 'page_id' => $item->page_id), $this->translate('assign badge'), array(
                'class' => 'smoothbox',
                )) ?> |
							<?php endif; ?>

							<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitepagebadge', 'controller' => 'badgerequest', 'action' => 'delete', 'badgerequest_id' => $item->badgerequest_id), $this->translate('delete'), array(
														'class' => 'smoothbox',
													)) ?> 	              
							</td>

						</tr>
					<?php  endforeach; ?>
				</tbody>			
			</table>
			<br />
			<div class='buttons'>
				<button type='submit'><?php echo $this->translate('Delete Selected'); ?></button>
			</div>
		</form>
	<?php else:?>         
		<div class="tip">
			<span><?php  echo $this->translate("No badge requests are found."); ?></span> 
		</div>  		
	<?php endif;?>
</div>