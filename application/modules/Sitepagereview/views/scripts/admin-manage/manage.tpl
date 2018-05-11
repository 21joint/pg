<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2><?php echo $this->translate('Directory / Pages - Reviews Extension'); ?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>

<h3><?php echo $this->translate('Manage Ratings & Reviews'); ?></h3>
<p>
  <?php echo $this->translate('Here, you can see all the Page reviews your users have posted for the Pages on your site. Here you can monitor these reviews and delete offensive ones if necessary. You can also make reviews featured / un-featured by clicking on the corresponding icons. ');?>
</p>
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
		return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete selected Page reviews ?")) ?>');
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

<br />

<div class="admin_search">
  <div class="search">
    <form method="post" class="global_form_box" action="">
      <div>
	      <label>
	      	<?php echo  $this->translate("Review Title") ?>
	      </label>
	      <input type="text" name="review_title" value="<?php echo $this->review_title; ?>"/>
      </div>
      
      <div>
	      <label>
	      	<?php echo  $this->translate("Page Title") ?>
	      </label>
	      <input type="text" name="sitepage_title" value="<?php echo $this->sitepage_title; ?>"/>
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

<br />

<?php if( count($this->paginator) ): ?>

	<div class='admin_members_results'>
		<div>
			<?php echo $this->translate(array('%s Page review found.', '%s Page reviews found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
		</div>
		<?php echo $this->paginationControl($this->paginator); ?>
	</div>

	<br />

	<form id='multidelete_form' method="post" action="<?php echo $this->url(array('action'=>'multi-delete'));?>" onSubmit="return multiDelete()">
		<table class='admin_table seaocore_admin_table'>
			<thead>
				<tr>
					<th style='width: 1%;' align="left"><input onclick="selectAll()" type='checkbox' class='checkbox'></th>
					<th style='width: 1%;' align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('review_id', 'DESC');"><?php echo $this->translate('ID'); ?></a></th>
					<th align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('sitepage_title', 'ASC');"><?php echo $this->translate('Page Title');?></a></th>
					<th align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate('Review Title'); ?></a></th>
					<th align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('review_rating', 'ASC');"><?php echo $this->translate('Overall Rating');?></a></th>
					<th style='width: 1%;' class='admin_table_centered'><a href="javascript:void(0);" onclick="javascript:changeOrder('featured', 'ASC');"><?php echo $this->translate('Featured'); ?></a></th>
					<th align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('username', 'ASC');"><?php echo $this->translate('Reviewer'); ?></a></th>
					<th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('view_count', 'ASC');"><?php echo $this->translate('Views'); ?></a></th>
					<th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('comment_count', 'ASC');"><?php echo $this->translate('Comments'); ?></a></th>
					<th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('like_count', 'ASC');"><?php echo $this->translate('Likes'); ?></a></th>
					<th align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'DESC');"><?php echo $this->translate('Date'); ?></a></th>
					<th class='admin_table_options' align="left"><?php echo $this->translate('Options'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if( count($this->paginator) ): ?>
					<?php foreach( $this->paginator as $item ): ?>
						<tr>
							
							<td><input name='delete_<?php echo $item->review_id;?>' type='checkbox' class='checkbox' value="<?php echo $item->review_id ?>"/></td>
							
							<td class="admin_table_centered"><?php echo $item->review_id ?></td>
							
							<td class='admin_table_bold'><?php echo $this->htmlLink($this->item('sitepage_page', $item->page_id)->getHref(), Engine_Api::_()->sitepagereview()->truncateText($item->sitepage_title, 10), array('title' => $item->sitepage_title, 'target' => '_blank')) ?></td>

							<td class='admin_table_bold'><?php echo $this->htmlLink($item->getHref(), Engine_Api::_()->sitepagereview()->truncateText($item->title, 13), array('title' => $item->title, 'target' => '_blank')) ?></td>

							<td>
								<div>
									<span title="<?php echo $item->review_rating.$this->translate('rating '); ?>">
										<?php if (($item->review_rating > 0)): ?>
											<?php for ($x = 1; $x <= $item->review_rating; $x++): ?>
												<span class="rating_star_generic rating_star"></span>
											<?php endfor; ?>
											<?php if ((round($item->review_rating) - $item->review_rating) > 0): ?>
												<span class="rating_star_generic rating_star_half"></span>
											<?php endif; ?>
										<?php endif; ?>
									</span>
								</div>
							</td>

							<?php if($item->featured == 1):?>
								<td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitepagereview', 'controller' => 'admin-manage', 'action' => 'featured', 'review_id' => $item->review_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/featured.png', '', array('title'=> $this->translate('Make Un-featured')))) ?> 
								</td>       
							<?php else: ?>  
								<td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitepagereview', 'controller' => 'admin-manage', 'action' => 'featured', 'review_id' => $item->review_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/unfeatured.png', '', array('title'=> $this->translate('Make Featured')))) ?>
								</td>
							<?php endif; ?>
							
							<td class='admin_table_bold'><?php echo $this->htmlLink($this->item('user', $item->owner_id)->getHref()	, Engine_Api::_()->sitepagereview()->truncateText($this->user($item->owner_id)->getTitle(), 10), array('title' => $item->username, 'target' => '_blank')) ?></td>

							<td class="admin_table_centered"><?php echo $item->view_count ?></td>
							
							<td class="admin_table_centered"><?php echo $item->comment_count ?></td>
							
							<td class="admin_table_centered"><?php echo $item->like_count ?></td>

							<td><?php echo $this->translate(gmdate('M d,Y g:i A',strtotime($item->creation_date))) ?></td>
							
							<td class='admin_table_options' align="left">
							<?php echo $this->htmlLink($item->getHref(), $this->translate('view'), array('target' => '_blank')) ?>
								|
								<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitepagereview', 'controller' => 'manage', 'action' => 'delete', 'review_id' => $item->review_id), $this->translate('delete'), array(
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