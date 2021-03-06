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

		var previewFileForceOpen;
		var previewFile = function(event)
		{
			event = new Event(event);
			element = $(event.target).getParent('.admin_file').getElement('.admin_file_preview');
			
			// Ignore ones with no preview
			if( !element || element.getChildren().length < 1 ) {
				return;
			}

			if( event.type == 'click' ) {
				if( previewFileForceOpen ) {
					previewFileForceOpen.hide();
					previewFileForceOpen = false;
				} else {
					previewFileForceOpen = element;
					previewFileForceOpen.setStyle('display', 'block');
				}
			}
			if( previewFileForceOpen ) {
				return;
			}

			var targetState = ( event.type == 'mouseover' ? true : false );
			element.setStyle('display', (targetState ? 'block' : 'none'));
		}

		$(window).on('load', function() {
			$$('.slideshow-image-preview').addEvents({
				click : previewFile,
				mouseout : previewFile,
				mouseover : previewFile
			});
			$$('.admin_file_preview').addEvents({
				click : previewFile
			});
		});


	var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction){
    // Just change direction
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

  var delectSelected =function(){
    var checkboxes = $$('input[type=checkbox]');
    var selecteditems = [];

    checkboxes.each(function(item, index){
      var checked = item.get('checked', false);
      var value = item.get('value', false);
      if (checked == true && value != 'on'){
        selecteditems.push(value);
      }
    });

    $('ids').value = selecteditems;
    $('delete_selected').submit();
  }

	function multiDelete()
	{
		return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete selected badegs?")) ?>');
	}


</script>

<div class='admin_search'>
 <?php echo $this->formFilter->render($this) ?>
</div>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2>
  <?php echo $this->translate('Directory / Pages - Badges Extension') ?>
</h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<h3>
  <?php echo $this->translate('Manage Badges') ?>
</h3>

<p>
  <?php echo $this->translate("This page lists all the badges for Directory Items / Pages on your site. Here, you can monitor, edit and delete them.") ?>
</p><br />

<?php if( count($this->paginator->getTotalItemCount()) ): ?>	
	<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitepagebadge', 'controller' => 'manage', 'action' => 'create'), $this->translate('Add New Badge'), array('class'=> 'buttonlink', 'style'=> 'background-image: url('.$this->layout()->staticBaseUrl.'application/modules/Sitepagebadge/externals/images/add_badge.png);')) ?>
<br /><br />
<?php endif; ?>

<?php if( $this->paginator->getTotalItemCount() ): ?>	

	<div>
		<?php echo $this->translate(array('%s badge found.', '%s badges found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
	</div>
	<br />

<div class="admin_files_wrapper sitepagebadge_manage_wrapper">
	<ul class="admin_files">
		<li>
			<form id='multidelete_form' method="post" action="<?php echo $this->url(array('action'=>'multi-delete'));?>" onSubmit="return multiDelete()">
				<table class='admin_table admin_table_list_badge'>
			    <thead>
			      <tr>
							<th  style='width: 1%;' class='admin_table_short'><input type='checkbox' class='checkbox' /></th>
							<th style='width: 1%;' class='admin_table_centered'><a href="javascript:void(0);" onclick="javascript:changeOrder('badge_id', 'ASC');"><?php echo $this->translate('ID');?></a></th>
							<th  style='width: 10%;' align="left"><?php echo $this->translate('Badge'); ?></th>
							<th style='width: 10%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate("Title") ?></a></th>
							<th style='width: 10%;' align="left"><?php echo $this->translate("Description") ?></th>
							<th align="left"><?php echo $this->translate("Options") ?></th>
			      </tr>
			    </thead>
					<tbody>
						<?php foreach ($this->paginator as $item): $i = 0; $i++; $id = 'admin_file_' . $i;?>
							<tr>
								<td><input name='delete_<?php echo $item->badge_id;?>' type='checkbox' class='checkbox' value="<?php echo $item->badge_id ?>"/></td>
								<td class='admin_table_centered'><?php echo $item->badge_id ?></td>
			
								<td>
									<?php if(!empty($item->badge_main_id)): ?>
										<?php $main_path = Engine_Api::_()->storage()->get($item->badge_main_id, '')->getPhotoUrl();?>
										<?php if(!empty($main_path)): ?>
											<li class="admin_file admin_file_type_image" id="<?php echo $id ?>">
												<div class="slideshow-image-preview">
													<?php echo'<img src="'. $main_path .'" class="photo" width="50" />'; ?>
												</div>
												<div class="admin_file_preview admin_file_preview_image" style="display:none;">
													<?php echo '<img src="'. $main_path .'" class="photo sitepagebadge_img_pre" />'; ?>
												</div>
											</li>
										<?php endif; ?>
									<?php endif; ?>
								</td>
			
								<td class="admin_table_bold">
									<?php if(!empty($item->title)): ?>
										<?php echo $item->title ?>
									<?php else: ?>
										<?php echo "---" ?>
									<?php endif; ?>
								</td>

								<td title="<?php echo $item->description ?>">
									<?php if(!empty($item->description)): ?>
										<?php $truncate_description = Engine_String::strlen($item->description) > 20 ? Engine_String::substr($item->description, 0, 20) . '..' : $item->description; ?>
										<?php echo $truncate_description ?>
									<?php else: ?>
										<?php echo "---" ?>
									<?php endif; ?>
								</td>
			
								<td width="10%" class="admin_table_options">
									<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitepagebadge', 'controller' => 'manage', 'action' => 'edit', 'id' => $item->badge_id), $this->translate('edit'), array()) ?>
										| 
									<?php echo $this->htmlLink(
									array('route' => 'admin_default', 'module' => 'sitepagebadge', 'controller' => 'manage', 'action' => 'delete', 'id' => $item->badge_id),
									$this->translate('delete'),
									array('class' => 'smoothbox')) ?>
								</td>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
			  </table>
				<br/>
				<div class='buttons'>
				<button type='submit'><?php echo $this->translate('Delete Selected');?></button>
				</div>
			</form>
		</li>
	</ul>	
</div>
<?php else:?>
	<div class="tip">
		<span>
			<?php echo $this->translate('You have not created any badges yet. Get started by ').$this->htmlLink(array(
							'route' => 'admin_default', 'module' => 'sitepagebadge', 'controller' => 'manage', 'action' => 'create'
						), $this->translate('creating')). $this->translate(" one.");; ?>
		</span>
  </div>	
<?php endif; ?>
<?php echo $this->paginationControl($this->paginator); ?>
