<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<iframe id='ajaxframe' name='ajaxframe' style='display: none;' src='javascript:false;'></iframe>
<h2>Ultimate SEO / Sitemaps Plugin</h2>
<?php if (count($this->navigation)): ?>
	<div class='seaocore_admin_tabs clr'>
		<?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
	</div>
<?php endif; ?>
<h3>Index Sitemap</h3>
<br>
<p>Index Sitemap file is a group of individual sitemaps, using an XML/GZIP format similar to a regular sitemap file. Below you can check date when it was last modified or submitted. You can perform various actions like: view, download, regenerate, submission and auto submission of Index Sitemap.</p>
<br>
<table style="width:100%;" id="sitemap">
	<?php if($this->hasGlobalSitemap): ?>
		<tr>
			<th><b>Date</b></th>
			<td>
				<b>Last Modified</b>:
				<?php echo $this->lastModifiedDate ? date('M d, Y', strtotime($this->lastModifiedDate)) : 'Never' ?>
			</td>
			<td>
				<b>Last Submit</b>:
				<?php echo $this->lastSubmitDate ? date('M d, Y', strtotime($this->lastSubmitDate)) : 'Never' ?>
			</td>
			<td></td>
		</tr>
		<tr>
			<th><b>Options</b></th>
			<td><?php echo $this->htmlLink($this->globalSitemapPath, 'View',array('target' => '_blank')); ?></td>
			<td><?php echo $this->htmlLink($this->url(array('action' => 'download')), 'Download XML File'); ?></td>
			<td><?php echo $this->htmlLink($this->globalSitemapPath.'.gz', 'Download GZIP File', array('target' => '_blank')); ?></td>
		</tr>
	<?php endif; ?>
	<tr>
		<th><b>Actions</b></th>
		<td><button onclick="Smoothbox.open('<?php echo $this->url(array('action' => 'build')); ?>')">
			<?php if($this->hasGlobalSitemap): ?>
				Regenerate
			<?php else: ?>
				Generate
			<?php endif; ?>
		</button></td>
		<td><button onclick="Smoothbox.open('<?php echo $this->url(array('action' => 'submit')); ?>')"> Submit </button></td>
		<td><button onclick="Smoothbox.open('<?php echo $this->url(array('action' => 'auto-submit-settings')); ?>')">Auto Submit Settings</button></td>
	</tr>
</table>
<br><br><br><br>
<h3>Content Sitemaps</h3>
<br>
<p>Content Sitemap file consists of all the URLs belonging to a particular content. Below you can check various information related to the content and its Sitemap, go to ‘Edit Option’ to modify this information. You can view the current Sitemap for a particular content or can regenerate a new one based on any latest updates done in a content.</p>
<br>
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<?php if (count($this->contentTypes) > 0): ?>
	<div class="admin_table_form">
		<table class='admin_table'>
			<thead>
				<tr>
					<th style='width: 5%;'>&#9776;</th>
					<th style='width: 15%;'>Content Type</th>
					<th style='width: 7%;'>Frequency</th>
					<th style='width: 7%;'>Priority</th>
					<th style='width: 7%;'>Enabled</th>
					<th style='width: 10%;'>Last Modified</th>
					<th style='width: 15%;'>Sitemaps</th>
					<th style='width: 20%;'>Options</th>
				</tr>
			</thead>
			<tbody id="sitemap_content">
				<?php if (count($this->contentTypes)): ?>
					<?php foreach ($this->contentTypes as $type): ?>
						<tr id="content_<?php echo $type->getIdentity(); ?>">
							<td class="sitemap_sortable_rows">
								<img src="application/modules/Siteseo/externals/images/admin/sortable.png" alt="Sortable icon">
							</td>
							<?php $typeTitleText = ($this->translate(strtoupper('ITEM_TYPE_' . $type->type)) == strtoupper('ITEM_TYPE_' . $type->type) ) ?  str_replace('_', ' ', $type->type) : $this->translate(strtoupper('ITEM_TYPE_' . $type->type)); ?>
							<td><?php echo $typeTitleText;?></td>
							<td class='admin_table_centered'>
								<?php echo $type->changefreq; ?>
							</td>
							<td class="admin_table_centered">
								<?php echo $type->priority; ?>
							</td>
							<td class="admin_table_centered">
							<?php $url = $this->url(array('action' => 'enable-disable','contenttype_id' => $type->getIdentity())); ?>
								<?php if($type->enabled) :?>
									<a title="Enable" href="<?php echo $url ?>">
										<img src="application/modules/Seaocore/externals/images/approved.gif" />
									</a>
								<?php else:?>
									<a title="Disable" href="<?php echo $url ?>">
										<img src="application/modules/Seaocore/externals/images/disapproved.gif" />
									</a>
								<?php endif;?>
							</td>
							<td class="admin_table_centered">
								<?php echo $type->lastmod ? date('M d, Y',strtotime($type->lastmod)) : '-'; ?>
							</td>
							<td>
								<?php $sitemapPaths = $type->getPublicSitemapPath(); ?>
								<?php if(count($sitemapPaths) == 0): ?>
									<?php echo " Not Found "; ?>
								<?php elseif(count($sitemapPaths) == 1): ?>
									<a href="<?php echo reset($sitemapPaths); ?>" target="_blank">Sitemap</a>
								<?php else: ?>
									<div class="sitemap_list">
										<span class="fright status" title="Show / Hide Sitemaps">+</span>
										<?php foreach($sitemapPaths as $index => $path): ?>
											<div class="sitemap_list_elements">
												<a href="<?php echo $path; ?>" target="_blank">
													<?php echo 'Sitemap ' . ($index + 1) ; ?>
												</a>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</td>
							<td class='admin_table_options'>
								<a class='smoothbox' href='<?php echo $this->url(array('action' => 'edit', 'contenttype_id' => $type->getIdentity())); ?>'>
									Edit Options
								</a>
								<?php if($type->type == 'menu_urls'): ?>
									| <a href='<?php echo $this->url(array('action' => 'select-menu')); ?>' title="Select the menu you want to add to sitemap." class="smoothbox">
									Select Menus
									</a>
								<?php endif; ?>
								<br />
								<?php if(empty($sitemapPaths)): ?>
									<a class='smoothbox' href='<?php echo $this->url(array('action' => 'build', 'type' => $type->type )); ?>'>
										Generate Sitemap
									</a>
								<?php else: ?>
									<a class='smoothbox' href='<?php echo $this->url(array('action' => 'build', 'type' => $type->type )); ?>'>
										Regenerate Sitemap
									</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<div class="tip"><span>No Content Types were found.</span>
	</div>
<?php endif; ?>

<script>
	function createSortable(divId, handleClass) {
		new Sortables($(divId), {
			handle: handleClass, 
			onComplete: function() {
				changeorder(this.serialize(), divId);
			}
		});
	}
	Sortables.implement({
		serialize: function() {
			var serial = [];
			this.list.getChildren().each(function(el, i) {
				serial[i] = el.getProperty('id');
			}, this);
			return serial;
		}
	});

	window.addEvent('domready', function() {
		createSortable('sitemap_content', 'td.sitemap_sortable_rows');
	});

	function changeorder(contentTypeOrder, divId) {
		$('ajaxframe').src = '<?php echo $this->url(array('module' => 'siteseo', 'controller' => 'sitemap','action' => 'index'), 'admin_default', true) ?>?task=changeorder&contentTypeOrder=' + contentTypeOrder + '&divId=' + divId;
	}

	// SHOW / HIDE SITEMAPS IF THERE ARE MORE THAN ONE SITEMAPS
	$$('.sitemap_list .status').addEvent('click', function(event) {
		var status = event.target;
		var listDiv = status.getParent();
		if (listDiv.hasClass('sitmap_list_explanded')) {
			listDiv.removeClass('sitmap_list_explanded');
			status.set('html','+');
		} else {
			listDiv.addClass('sitmap_list_explanded');
			status.set('html','-');
		}
	});
</script>