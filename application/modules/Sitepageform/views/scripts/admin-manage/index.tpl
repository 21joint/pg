<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php	$layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);?>
<script type="text/javascript">
  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction) {
    if( order == currentOrder ) {
      $('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } 
    else {
      $('order').value = order;
      $('order_direction').value = default_direction;
    }
    $('filter_form').submit();
  }
</script>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2><?php echo $this->translate('Directory / Pages - Form Extension'); ?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<h3><?php echo $this->translate('Manage Forms in Pages'); ?></h3>
<p>
  <?php echo $this->translate('Here, you can monitor and manage the forms created by Page admins for their pages using the Form Extension. You can also disable form for a Page.Entering criteria into the filter fields will help you find specific page form entries. Leaving the filter fields blank will show all the page form entries on your social network. ');?>
</p>
<br />

<div class="admin_search sitepage_admin_search">
  <div class="search">
    <form method="post" class="global_form_box" action="<?php echo $this->url(array('module' => 'sitepageform', 'controller' => 'manage', 'action' => 'index'),'admin_default', true) ?>">
      <div>
	       <label>
          <?php echo  $this->translate("Page Title") ?>
	       </label>
	       <?php if( empty($this->page_title)):?>
          <input type="text" name="page_title" /> 
	       <?php else: ?>
	      	  <input type="text" name="page_title" value="<?php echo $this->translate($this->page_title)?>"/>
	       <?php endif;?>
      </div>
      <div>
	       <label>
	      	  <?php echo  $this->translate("Form Title") ?>
	       </label>
	       <?php if( empty($this->form_title)):?>
	      	  <input type="text" name="form_title" /> 
	       <?php else: ?>
	      	  <input type="text" name="form_title" value="<?php echo $this->translate($this->form_title)?>"/>
	       <?php endif;?>
      </div>
      <div>
	    	  <label>
	      	  <?php echo  $this->translate("Status") ?>
	       </label>
        <select id="" name="pageform_status">
          <option value="0" ></option>
          <option value="1" <?php if( $this->pageform_status == 1) echo "selected";?> ><?php echo $this->translate("Activated") ?></option>
          <option value="2" <?php if( $this->pageform_status == 2) echo "selected";?> ><?php echo $this->translate("De-activated") ?></option>
        </select>
      </div>
      <div class="sitepage_search_button">
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
			<?php echo $this->translate(array('%s page form found.', '%s page forms found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
		</div>
</div>
	<br />
  <table class='admin_table' width="100%">
    <thead>
      <tr>
        <th align="center" style="width:1%;"><a href="javascript:void(0);" onclick="javascript:changeOrder('page_id', 'DESC');"><?php echo $this->translate('ID'); ?></a></th>
				<th align="left" style="width:2%;"><a href="javascript:void(0);" onclick="javascript:changeOrder('sitepage_title', 'ASC');"><?php echo $this->translate('Page Title');?></a></th>
        <th align="left" style="width:2%;"><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate('Form Title'); ?></a></th>
        <th align="left" style="width:5%;"><a href="javascript:void(0);" onclick="javascript:changeOrder('description', 'ASC');"><?php echo $this->translate('Description'); ?></a></th>
        <th align="left" style="width:2%;"><a href="javascript:void(0);" onclick="javascript:changeOrder('status', 'ASC');"><?php echo $this->translate('Status');?></a></th>        
        <th class='admin_table_options' align="left" style="width:2%;"><?php echo $this->translate('Options'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($counter)): ?>
				<?php foreach( $this->paginator as $item ):
						$sitepage = Engine_Api::_()->getItem('sitepage_page', $item->page_id);
						$isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'form');
					if(empty($isManageAdmin)):
						continue;
					endif;
						?>
					<tr>            
			
						<td class="admin_table_centered"><?php echo $item->page_id ?></td>           
						
						<?php             
							$truncation_limit = 13;
							$tmpBodytitle = strip_tags($item->sitepage_title);
							$item_sitepagetitle = ( Engine_String::strlen($tmpBodytitle) > $truncation_limit ? Engine_String::substr($tmpBodytitle, 0, $truncation_limit) . '..' : $tmpBodytitle );             
						?>          

            <td class='admin_table_bold'><?php echo $this->htmlLink($this->item('sitepage_page', $item->page_id)->getHref(), $item_sitepagetitle, array('title' => $item->sitepage_title, 'target' => '_blank')) ?></td>							
		
						<?php             
							$truncation_limit = 13;
							$tmpBody = strip_tags($item->title);
							$item_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );             
						?>
						
						<td title="<?php echo $item->title ;?>">
							<?php echo $item_title ;?>
						</td>
						<?php             
							$truncation_limit_decription = 25;
							$tmpBody_decription = strip_tags($item->description);
							$item_description = ( Engine_String::strlen($tmpBody_decription) > $truncation_limit_decription ? Engine_String::substr($tmpBody_decription, 0, $truncation_limit_decription) . '..' : $tmpBody_decription );             
						?>
						<?php if(!empty($item->description)):?> 
						<td title="<?php echo $item->description ;?>">
							<?php echo $item_description ;?>
						</td>
						<?php else: ?>
							<td><?php echo '-' ?></td>
						<?php endif; ?>
						<?php if($item->status == 0): ?>
							<td><?php echo $this->translate('Disabled'); ?></td>
						<?php elseif($item->pageformactive == 1 ): ?>
							<td><?php echo $this->translate('Activated'); ?></td>
						<?php else: ?>
							<td><?php echo $this->translate('De-activated'); ?></td>
						<?php endif; ?>
						
						<?php $tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageform.sitepage-viewform', $item->page_id, $layout);	?>				
							<td class='admin_table_options' style="white-space: nowrap;">	
								<?php if($item->status == 1):?>							 
									<?php echo $this->htmlLink($this->item('sitepage_page', $item->page_id)->getHref(array('tab'=> $tab_id)), $this->translate('view form'), array('target' => '_blank')) ?>
										|
								<?php endif;?>
							<?php if($item->status == 1):?> 
								<?php echo $this->htmlLink(array('route' => 'sitepageform_disable', 'id' => $item->page_id),$this->translate('disable form'), array(
								'class' => 'smoothbox')) ?>
							<?php else: ?>
							<?php echo $this->htmlLink(array('route' => 'sitepageform_disable', 'id' => $item->page_id), $this->translate('enable form'), array(
								'class' => 'smoothbox')) ?> 
							<?php endif;?> 
						</td>
					</tr>
				<?php endforeach; ?>
		  <?php endif; ?>
    </tbody>
  </table>
  <?php  echo $this->paginationControl($this->paginator); ?><br  />
<?php else: ?>
	<div class="tip">
		<span>
			<?php echo $this->translate('No results were found.');?>
		</span>
	</div>
<?php endif; ?>
<br />