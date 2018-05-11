<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagenotes
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
  
  function selectAll(){
    var i;
    var multidelete_form = $('multidelete_form');
    var inputs = multidelete_form.elements;

    for (i = 1; i < inputs.length - 1; i++) {
     if (!inputs[i].disabled) {
       inputs[i].checked = inputs[0].checked;
     }
    }
	 }  

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
  
  function multiDelete(){
    return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete selected notes?")) ?>');
  }  
</script>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2><?php echo $this->translate('Directory / Pages - Notes Extension'); ?></h2>

<?php if (count($this->navigation)): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<h3><?php echo $this->translate('Manage Page Notes'); ?></h3>
<p>
  <?php echo $this->translate('Here, you can see all the notes your users have created for the Pages on your site. Here you can monitor these notes and delete offensive ones if necessary. You can also make page notes featured / un-featured by clicking on the corresponding icon.'); ?>
</p>
<br />

<div class='admin_members_results'>
  <?php
  if (!empty($this->paginator)) {
    $counter = $this->paginator->getTotalItemCount();
  }
  if (!empty($counter)):
    ?>
    <div class="">
      <?php echo $this->translate(array('%s page note found.', '%s page notes found.', $counter), $this->locale()->toNumber($counter)) ?>
    </div>
  <?php else: ?>
    <div class="tip"><span>
        <?php echo $this->translate("No results were found.") ?></span>
    </div>
  <?php endif; ?> 
</div>

<div class='admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<br />

<?php if (!empty($counter)): ?>
  <form id='multidelete_form' method="post" action="<?php echo $this->url(array('action' => 'multi-delete'));?>" onSubmit="return multiDelete()">
  <table class='admin_table seaocore_admin_table'>
    <thead>
      <tr>
        <th style='width: 1%;' class='admin_table_short' align="left"><input onclick="selectAll()" type='checkbox' class='checkbox' /></th>
        <th style='width: 1%;' align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('note_id', 'DESC');"><?php echo $this->translate('ID'); ?></a></th>
        <th style='width: 5%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate('Title'); ?></a></th>
        <th style='width: 5%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('username', 'ASC');"><?php echo $this->translate('Owner'); ?></a></th>
        <th style='width: 5%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('sitepage_title', 'ASC');"><?php echo $this->translate('Page Title'); ?></a></th>
        <th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('featured', 'ASC');"><?php echo $this->translate('Featured'); ?></a></th>
        <th style='width: 1%;' align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('view_count', 'ASC');"><?php echo $this->translate('Views'); ?></a></th>
        <th style='width: 1%;' align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('comment_count', 'ASC');"><?php echo $this->translate('Comments'); ?></a></th>
        <th style='width: 1%;' align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('like_count', 'ASC');"><?php echo $this->translate('Likes'); ?></a></th>
        <th style='width: 5%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('total_photos', 'ASC');"><?php echo $this->translate('Total Photos'); ?></a></th>        
        <th style='width: 5%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'DESC');"><?php echo $this->translate('Creation Date'); ?></a></th>
        <th style='width: 1%;' class='admin_table_options' align="left"><?php echo $this->translate('Options'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($counter)): ?>
        <?php foreach ($this->paginator as $item): ?>
          <tr>            
            <td><input name='delete_<?php echo $item->note_id; ?>' type='checkbox' class='checkbox' value="<?php echo $item->note_id ?>"/></td>   

            <td class="admin_table_centered"><?php echo $item->note_id ?></td>
            <?php
            $truncation_limit = 13;
            $tmpBody = strip_tags($item->title);
            $item_title = ( Engine_String::strlen($tmpBody) > $truncation_limit ? Engine_String::substr($tmpBody, 0, $truncation_limit) . '..' : $tmpBody );
            ?>

            <td style="white-space: nowrap;" class="admin_table_bold"><?php echo $this->htmlLink($item->getHref(), $item_title, array('title' => $item->title, 'target' => '_blank')) ?></td> 

            <td class="admin_table_bold"><?php echo $this->htmlLink($this->item('user', $item->owner_id)->getHref(), $item->truncateOwner($this->user($item->owner_id)->username), array('title' => $item->username, 'target' => '_blank')) ?></td>
            <?php
            $truncation_limit = 13;
            $tmpBodytitle = strip_tags($item->sitepage_title);
            $item_sitepagetitle = ( Engine_String::strlen($tmpBodytitle) > $truncation_limit ? Engine_String::substr($tmpBodytitle, 0, $truncation_limit) . '..' : $tmpBodytitle );
            ?>          

            <td class="admin_table_bold"><?php echo $this->htmlLink($this->item('sitepage_page', $item->page_id)->getHref(), $item_sitepagetitle, array('title' => $item->sitepage_title, 'target' => '_blank')) ?></td>
						<?php if($item->featured == 1):?>
							<td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'sitepagenote_featurednote', 'id' => $item->note_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/featured.png', '', array('title'=> $this->translate('Featured')))) ?> 
							</td>       
						<?php else: ?>  
							<td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'sitepagenote_featurednote', 'id' => $item->note_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/unfeatured.png', '', array('title'=> $this->translate('Un-featured')))) ?>
							</td>
						<?php endif; ?>

            <td align="center" class="admin_table_centered"><?php echo $item->view_count ?></td>

            <td align="center" class="admin_table_centered"><?php echo $item->comment_count ?></td>

            <td align="center" class="admin_table_centered"><?php echo $item->like_count ?></td>

            <td align="center"  class="admin_table_centered"><?php echo $item->total_photos ?></td>            

            <td align="center"><?php echo gmdate('M d,Y g:i A', strtotime($item->creation_date)) ?></td>      

            <td class='admin_table_options' style="white-space: nowrap;">							 
              <?php echo $this->htmlLink($item->getHref(), $this->translate('view'), array('target' => '_blank')) ?>
              |
              <?php
              echo $this->htmlLink(array('route' => 'sitepagenoteadmin_delete', 'id' => $item->note_id), $this->translate('delete'), array(
                  'class' => 'smoothbox',
              ))
              ?> 
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  <div class='buttons'>
    <button type='submit'><?php echo $this->translate('Delete Selected'); ?></button>
  </div>       
  </form>
  <br />
  <?php echo $this->paginationControl($this->paginator); ?><br  />
<?php endif; ?>