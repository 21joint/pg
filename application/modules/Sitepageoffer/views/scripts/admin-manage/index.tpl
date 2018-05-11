<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2><?php echo $this->translate('Directory / Pages - Offers Extension'); ?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>

<h3>
  <?php echo $this->translate('Manage Page Offers'); ?>
</h3>

<p>
  <?php echo $this->translate('Here, you can see all the Page offers your users have created. You can use this page to monitor these offers and delete offensive ones if necessary. Here, you can also mark offers as Hot. Hot offers are shown in the Hot Page Offers widget.');?>
</p>

<br />

<script type="text/javascript">
  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction){

    if( order == currentOrder ) {
      $('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } else {
      $('order').value = order;
      $('order_direction').value = default_direction;
    }
    $('filter_form').submit();
  }

	function multiDelete()
	{
		return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete selected page offers ?")) ?>');
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

<div class="admin_search">
  <div class="search">
    <form method="post" class="global_form_box" action="">
      <div>
	      <label>
	      	<?php echo  $this->translate("Title") ?>
	      </label>
	      <?php if( empty($this->title)):?>
	      	<input type="text" name="title" /> 
	      <?php else: ?>
	      	<input type="text" name="title" value="<?php echo $this->translate($this->title)?>"/>
	      <?php endif;?>
      </div>
      <div>
      	<label>
      		<?php echo  $this->translate("Owner") ?>
      	</label>	
      	<?php if( empty($this->owner)):?>
      		<input type="text" name="owner" /> 
      	<?php else: ?> 
      		<input type="text" name="owner" value="<?php echo $this->translate($this->owner)?>" />
      	<?php endif;?>
      </div>
      <div>
	      <label>
	      	<?php echo  $this->translate("Page Name") ?>
	      </label>
	      <?php if( empty($this->sitepage_title)):?>
	      	<input type="text" name="sitepage_title" /> 
	      <?php else: ?>
	      	<input type="text" name="sitepage_title" value="<?php echo $this->translate($this->sitepage_title)?>"/>
	      <?php endif;?>
      </div>
      <div>
	    	<label>
	      	<?php echo  $this->translate("Hot Offers") ?>	
	      </label>
        <select id="" name="hotoffer">
          <option value="0" ><?php echo $this->translate("") ?></option>
          <option value="2" <?php if( $this->hotoffer == 2) echo "selected";?> ><?php echo $this->translate("Yes") ?></option>
          <option value="1" <?php if( $this->hotoffer == 1) echo "selected";?> ><?php echo $this->translate("No") ?></option>
         </select>
      </div>
      <div style="margin:10px 0 0 10px;">
        <button type="submit" name="search" ><?php echo $this->translate("Search") ?></button>
      </div>
    </form>
  </div>
</div>

<br />

<div class='admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>

  <?php 
  	if( !empty($this->paginator) ) {
  		$counter=$this->paginator->getTotalItemCount(); 
  	}
  	if(!empty($counter)): 
  
  ?>
	<div class='admin_members_results'>
		<div>
			<?php echo $this->translate(array('%s page offer found.', '%s page offers found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
		</div>
		<?php echo $this->paginationControl($this->paginator); ?>
	</div>
	<br />

	<form id='multidelete_form' method="post" action="<?php echo $this->url(array('action'=>'multi-delete'));?>" onSubmit="return multiDelete()">
		<table class='admin_table seaocore_admin_table' border="0">
			<thead>
				<tr>
					<th style='width: 1%;' align="left"><input onclick="selectAll()" type='checkbox' class='checkbox'></th>
					<th style='width: 4%;' align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('offer_id', 'DESC');"><?php echo $this->translate('ID'); ?></a></th>
					<th style='width: 4%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate('Title'); ?></a></th>
					<th style='width: 4%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('username', 'ASC');"><?php echo $this->translate('Owner');?></a></th>
					<th style='width: 4%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('sitepage_title', 'ASC');"><?php echo $this->translate('Page Name');?></a></th>
					<th style='width: 4%;' class='admin_table_centered'><a href="javascript:void(0);" onclick="javascript:changeOrder('hotoffer', 'ASC');"><?php echo $this->translate('Hot Offers'); ?></a></th>
					<th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('sticky', 'ASC');"><?php echo $this->translate('Featured'); ?></a></th>
					<th style='width: 4%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'DESC');"><?php echo $this->translate('Creation Date'); ?></a></th>
          <th style='width: 4%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('end_time', 'DESC');"><?php echo $this->translate('Expiration Date'); ?></a></th>
					<th style='width: 4%;' class='admin_table_options' align="left"><?php echo $this->translate('Options'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($counter)): ?>
					<?php foreach( $this->paginator as $item ): ?>
						<tr>        
							<td><input name='delete_<?php echo $item->offer_id;?>' type='checkbox' class='checkbox' value="<?php echo $item->offer_id ?>"/></td>
							<td class="admin_table_centered"><?php echo $item->offer_id ?></td>
							<?php             
								$truncation_limit = 16;
								$tmpBody = strip_tags($item->title);
								$item_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
							
							?>

							<td class='admin_table_bold' title="<?php echo $item->title; ?>"><?php echo $item_title; ?></td>
							
							<td class='admin_table_bold'><?php echo $this->htmlLink($this->item('user', $item->owner_id)->getHref()	, $item->truncateOwner($this->user($item->owner_id)->username), array('title' => $item->username, 'target' => '_blank')) ?></td>

							<?php             
								$truncation_limit = 16;
								$tmpBodytitle = strip_tags($item->sitepage_title);
								$item_sitepagetitle = ( Engine_String::strlen($tmpBodytitle) > $truncation_limit ? Engine_String::substr($tmpBodytitle, 0, $truncation_limit) . '..' : $tmpBodytitle );
							
							?>					
							<td class='admin_table_bold'><?php echo $this->htmlLink($this->item('sitepage_page', $item->page_id)->getHref(),$item_sitepagetitle, array('title' => $item->sitepage_title, 'target' => '_blank')) ?></td>

							<?php if($item->hotoffer == 1):?>
								<td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'sitepageoffer_hotoffer', 'id' => $item->offer_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepageoffer/externals/images/sitepageoffer_approved1.gif', '', array('title'=> $this->translate('Remove from Hot Offers')))) ?> 
								</td>       
							<?php else: ?>  
								<td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'sitepageoffer_hotoffer', 'id' => $item->offer_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitepageoffer/externals/images/sitepageoffer_approved0.gif', '', array('title'=> $this->translate('Add to Hot Offers')))) ?>
								</td>
							<?php endif; ?>
              <?php if($item->sticky == 1):?>
								<td align="center" class="admin_table_centered"> <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepage/externals/images/sitepage_goldmedal1.gif' title= '<?php echo $this->translate('Featured');?>' >
							<?php else: ?>  
								<td align="center" class="admin_table_centered"> <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitepage/externals/images/sitepage_goldmedal0.gif' title='<?php echo $this->translate('Un-featured');?>'>
								</td>
            <?php endif; ?>
							<td><?php echo $this->translate(gmdate('M d,Y g:i A',strtotime($item->creation_date))) ?></td>
              <?php $today = date("Y-m-d H:i:s");?>
              <?php if($item->end_settings == 1 && ($item->end_time >= $today)):?>
								<td><?php echo $this->translate(gmdate('M d,Y g:i A',strtotime($item->end_time))) ?></td>
								<?php elseif($item->end_settings == 0):?>
                  <td><?php echo $this->translate('Never Expires');?></td>
                <?php else:?>
								<td><?php echo $this->translate('Expired');?></td>
              <?php endif;?>
							<td class='admin_table_options'>
								<?php echo $this->htmlLink(
								array('route' => 'sitepageoffer_details', 'id' => $item->offer_id),
								$this->translate('details'), array('class' => 'smoothbox')) ?>
										|
								<?php echo $this->htmlLink(array('route' => 'sitepageoffer_delete', 'id' => $item->offer_id), $this->translate('delete'), array(
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
			<button type='submit'><?php echo $this->translate('Delete Selected'); ?></button>
		</div>
	</form>
<?php else: ?>
	<div class="tip">
		<span>
			<?php echo $this->translate('No results were found.');?>
		</span>
	</div>
<?php endif; ?>

<style type="text/css">
	table.admin_table tbody tr td {
		white-space: nowrap;
	}
.pages{margin-top:15px;}	
</style>