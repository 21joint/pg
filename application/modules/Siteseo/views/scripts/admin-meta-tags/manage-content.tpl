<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage-content.tpl 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Siteseo/externals/scripts/admin/core.js'); ?>
<h2>Ultimate SEO / Sitemaps Plugin</h2>
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
<br />
<p>All the searchable content of your website is listed here. If you need to search for a specific content, enter your search criteria in the fields below. Here, you can set titles, meta keywords and meta descriptions for your content.</p>
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

<div class='admin_search sm_admin_search'>
	<?php echo $this->formFilter->render($this) ?>
</div>
<br />

<?php if (count($this->paginator) > 0): ?>
	<div class="admin_table_form">
		<div class='admin_results siteseo_admin_results'>
			<div>
				<?php $count = $this->paginator->getTotalItemCount() ?>
				<?php echo $this->translate(array("%s item found.", "%s items found.", $count), $this->locale()->toNumber($count))
				?>
			</div>
		</div>
		<table class='admin_table siteseo_admin_table' id="manage-content-meta-tags">
			<thead>
				<tr>
					<th style='width: 5%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('id', 'DESC');" title="Content ID">ID</a></th>
					<?php if(!$this->contentType): ?>
						<th style='width: 15%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('type', 'ASC');">Content Type</a></th>
					<?php endif; ?>
					<th style='width: 15%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');">Meta Title</a></th>
					<th style='width: 30%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('description', 'ASC');">Meta Description</a></th>
					<th style='width: 30%;' class='admin_table_centered'><a href="javascript:void(0);" onclick="javascript:changeOrder('keywords', 'ASC');">Meta Keywords</a></th>
					<th style='width: 9%;' class='admin_table_options'>Options</th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($this->paginator)): ?>
					<?php foreach ($this->paginator as $searchRow): ?>
						<?php if (!Engine_Api::_()->hasItemType($searchRow->type)) continue; ?>
						<?php $item = Engine_Api::_()->getItem($searchRow->type, $searchRow->id); ?>
						<?php if(empty($item)) continue; ?>
						<tr data-id="<?php echo $searchRow->id; ?>" data-type="<?php echo $searchRow->type; ?>">
							<td class="admin_table_centered"><?php echo $searchRow->id ?></td>
							<?php if(!$this->contentType): ?>
								<td><?php echo $this->translate(strtoupper('ITEM_TYPE_' . $searchRow->type)); ?></td>
							<?php endif; ?>
							<td class='admin_table_user <?php echo $searchRow->meta_title != $item->getTitle() ? 'modified' : ''; ?> inline_edit_column'>
								<?php $title = $searchRow->meta_title ? $searchRow->meta_title : $searchRow->title; ?>
								<?php echo $title; ?>

							</td>
							<td class="<?php echo $searchRow->meta_description != $item->getDescription() ? 'modified' : ''; ?> inline_edit_column">
								<?php $description = $searchRow->meta_description ? $searchRow->meta_description : $searchRow->description; ?>
								<?php echo $description; ?>
							</td>
							<td class="admin_table_centered <?php echo $searchRow->meta_keywords != $item->getKeywords() ? 'modified' : ''; ?> inline_edit_column">
								<?php $keywords = $searchRow->meta_keywords ? $searchRow->meta_keywords : $searchRow->keywords; ?>
								<?php echo $keywords; ?>
							</td>
							<td class='admin_table_options'>
								<a class='smoothbox' href='<?php echo $this->url(array('action' => 'edit-content', 'id' => $searchRow->id, 'type' => $searchRow->type )); ?>'>Edit</a>
								<?php if($item->getHref()): ?>|
								<a href='<?php echo $item->getHref() ?>' target="_blank">
									View
								</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<br />
		<div class='admin_results siteseo_admin_results'>
			<div>
				<?php echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true, 'query' => $this->formValues)); ?>
			</div>
		</div>
	</div>
<?php else: ?>
	<div class="tip">
		<span>No pages were found.</span>
	</div>
<?php endif; ?>

<script>
	<?php if($this->contentType): ?>
		var editableColumns = {
			2: 'meta_title',
			3: 'meta_description',
			4: 'meta_keywords'
		};
	<?php else: ?>
		var editableColumns = {
			3: 'meta_title',
			4: 'meta_description',
			5: 'meta_keywords'
		};
	<?php endif; ?>
	// EDIT TABLE INLINE 
	var data = {
		table: $('manage-content-meta-tags') ,
		editableColumns: editableColumns,
		primaryKey : ['data-id','data-type'],
		url: '<?php echo $this->url(array('action' => 'edit-inline-content')); ?>'
	};
	var inlineEdit = new InlineEdit(data);
</script>