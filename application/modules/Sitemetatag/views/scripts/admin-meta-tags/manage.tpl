<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sitemetatag/externals/scripts/admin/core.js'); ?>
<h2>Social Meta Tags Plugin – Open Graph for Facebook, Google+, Pinterest and Twitter Cards</h2>
<?php if (count($this->navigation)): ?>
	<div class='seaocore_admin_tabs clr'>
		<?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
	</div>
<?php endif; ?>
<?php if (count($this->subNavigation)): ?>
	<div class='seaocore_admin_tabs clr'>
		<?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render() ?>
	</div>
<?php endif; ?>
<p>All the widgetized pages of your website are listed here. If you need to search for a specific page, enter your search criteria in the fields below. Here, you can set titles and meta descriptions for your pages.</p>
<div>
	<b><?php echo "Note: "; ?></b>
	<?php echo "You can either click on the pencil icon beside the field to edit them or can click on Edit link on right side of the row."; ?>
</div>
<br />
<script type="text/javascript">
	var currentOrder = '<?php echo $this->order ?>';
	var currentOrderDirection = '<?php echo $this->order_direction ?>';
	var changeOrder = function(order, default_direction) {
		// Just change direction
		if (order == currentOrder) {
			$('order_direction').value = (currentOrderDirection == 'ASC' ? 'DESC' : 'ASC');
		} else {
			$('order').value = order;
			$('order_direction').value = default_direction;
		}
		$('filter_form').submit();
	}
</script>
<?php $settings = Engine_Api::_()->getApi('settings', 'core'); ?>
<?php if(empty($settings->getSetting("sitemetatag.opengraph.enable", 1))): ?>
	<div class="tip">
		<span>You have disabled open graph from Global settings.</span>
	</div>
<?php endif; ?>
<?php if(empty($settings->getSetting("sitemetatag.twittercards.enable", 1))): ?>
	<div class="tip">
		<span>You have disabled twitter cards from Global settings.</span>
	</div>
<?php endif; ?>

<div class='admin_search sm_admin_search'>
	<?php echo $this->formFilter->render($this) ?>
</div>
<br />
<?php $storageTable = Engine_Api::_()->getItemTable('storage_file'); ?>
<?php if (count($this->paginator) > 0): ?>
	<div class="admin_table_form">
		<div class='admin_results'>
			<div>
				<?php $count = $this->paginator->getTotalItemCount() ?>
				<?php echo $this->translate(array("%s page found.", "%s pages found.", $count), $this->locale()->toNumber($count))
				?>
			</div>
		</div><br />
		<table class='admin_table' id="manage-meta-tags">
			<thead>
				<tr>
					<th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('page_id', 'DESC');">Page ID</a></th>
					<th style='width: 15%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('displayname', 'ASC');">Display Name</a></th>
					<th style='width: 15%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');">Page Title</a></th>
					<th style='width: 30%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('description', 'ASC');">Meta Description</a></th>
					<th style='width: 5%;' class='admin_table_image'>Meta Image</th>
					<th style='width: 5%;' class='admin_table_centered'>OG</th>
					<th style='width: 5%;' class='admin_table_centered'>TC</th>
					<th style='width: 5%;' class='admin_table_options'>Options</th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($this->paginator)): ?>
					<?php foreach ($this->paginator as $pageObject): ?>
						<tr data-id="<?php echo $pageObject->getIdentity(); ?>">
							<td class="admin_table_centered"><?php echo $pageObject->getIdentity() ?></td>
							<td class='admin_table_bold'>
								<a href="admin/content?page=<?php echo $pageObject->page_id; ?>" target="_blank">
									<?php echo $pageObject->displayname; ?>
								</a>
							</td>
							<td class='inline_edit_column'><?php echo $pageObject->getTitle(); ?></td>
							<td class='inline_edit_column'>
								<?php echo $pageObject->getDescription(); ?>
							</td>
							<td class="admin_table_centered">
								<?php if(!empty($pageObject->photo_id)): ?>
									<?php $url = $storageTable->getFile($pageObject->photo_id, 'thumb.icon')->map(); ?>
									<a href="<?php echo $url; ?>" target="_blank">
										<img src="<?php echo $url; ?>" class="custom_image">
									</a>
								<?php else: ?>
									<?php echo ' - '; ?>
								<?php endif; ?>
							</td>
							
							<td class="admin_table_centered">
								<?php $url = $this->url(array('action' => 'edit-open-graph','page_id' => $pageObject->getIdentity())); ?>
								<?php if($pageObject->enable_opengraph === 0) :?>
									<a title="Disable Open Graph" href="<?php echo $url ?>">
										<img src="application/modules/Seaocore/externals/images/disapproved.gif" />
									</a>
								<?php else:?>
									<a title="Enable Open Graph" href="<?php echo $url ?>">
										<img src="application/modules/Seaocore/externals/images/approved.gif" />
									</a>
								<?php endif;?>
							</td>

							<td class="admin_table_centered">
								<?php $url = $this->url(array('action' => 'edit-twitter-cards','page_id' => $pageObject->getIdentity())); ?>
								<?php if($pageObject->enable_twittercards === 0) :?>
									<a title="Disable Twitter Cards" href="<?php echo $url ?>">
										<img src="application/modules/Seaocore/externals/images/disapproved.gif" />
									</a>
								<?php else:?>
									<a title="Enable Twitter Cards" href="<?php echo $url ?>">
										<img src="application/modules/Seaocore/externals/images/approved.gif" />
									</a>
								<?php endif;?>
							</td>

							<td class='admin_table_centered'>
								<a class='smoothbox' href='<?php echo $this->url(array('action' => 'edit', 'page_id' => $pageObject->getIdentity())); ?>'>Edit</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<br />
		<div>
			<div>
				<?php echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true, 'query' => $this->formValues)); ?>
			</div>
		</div>
	</div>
<?php else: ?>
	<div class="tip"><span>No pages were found.</span>
	</div>
<?php endif; ?>

<script>
// EDIT TABLE INLINE 
var data = {
	table: $('manage-meta-tags') ,
	editableColumns: {
		3: 'title',
		4: 'description',
	},
	primaryKey : ['data-id'],
	url: '<?php echo $this->url(array('action' => 'edit-inline')); ?>'
};
var inlineEdit = new InlineEdit(data);
</script>