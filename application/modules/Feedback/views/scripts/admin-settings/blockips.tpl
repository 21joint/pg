<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: blockips.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<script type="text/javascript">
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
</script>

<div class='admin_search'>
	<?php echo $this->formFilter->render($this) ?>
</div>

<h2><?php echo $this->translate('Feedback Plugin');?></h2>
<?php if( count($this->navigation) ): ?>
	<div class='seaocore_admin_tabs'> <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?> </div>
<?php endif; ?>

<div class='clear'>
  <div class='settings'>
    <form class="global_form" style="width:60%">
      <div>
        <h3><?php echo $this->translate('Block IP Addresses');?></h3>
        <p class="description"> <?php echo $this->translate('Here, you can block feedback creation and commenting on feedback based on IP Addresses of users.');?> </p>
        <table class='admin_table' width="100%">
          <thead>
            <tr>
              <th align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('blockip_address', 'DESC');"><?php echo $this->translate("IP Address") ?></a></th>
              <th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('blockip_feedback', 'DESC');"><?php echo $this->translate('Feedback Posting');?></a></th>
              <th align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('blockip_comment', 'DESC');"><?php echo $this->translate('Comment Posting');?></a></th>
              <th align="center"><?php echo $this->translate('Options');?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($this->blockips as $blockip): ?>
	            <tr> 
	              <td align="left"><?php echo $blockip->blockip_address?></td>
	              <?php if($blockip->blockip_feedback == 0):?>
	              	<td class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'blockip-feedback', 'id' => $blockip->blockip_id, 'page' => $this->page), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/feedback_approved1.gif', '', array('title'=> $this->translate('Click to block feedback from this IP Address')))) ?> </td>
	              <?php else: ?>
	              <td class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'blockip-feedback', 'id' => $blockip->blockip_id, 'page' => $this->page), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/block_feedback.png', '', array('title'=> $this->translate('Click to allow feedback from this IP Address')))) ?> </td>
	              <?php endif; ?>
	              <?php if($blockip->blockip_comment == 0):?>
	              <td class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'blockip-comment', 'id' => $blockip->blockip_id, 'page' => $this->page), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/feedback_approved1.gif', '', array('title'=> $this->translate('Click to block comments from this IP Address')))) ?> </td>
	              <?php else: ?>
	              <td class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'blockip-comment', 'id' => $blockip->blockip_id, 'page' => $this->page), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/block_feedback.png', '', array('title'=> $this->translate('Click to allow comments from this IP Address')))) ?> </td>
	              <?php endif; ?>
	              <td class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'settings', 'action' => 'edit-blockip', 'id' =>$blockip->blockip_id), $this->translate('edit'), array(
				                		'class' => 'smoothbox',
				              			)) ?> | <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'settings', 'action' => 'delete-blockip', 'id' =>$blockip->blockip_id), $this->translate('delete'), array(
				                		'class' => 'smoothbox',
				              			)) ?>
				        </td>
	            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br/>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'feedback', 'controller' => 'settings', 'action' => 'add-blockip'), $this->translate('Add New IP Address'), array(
		      	'class' => 'smoothbox buttonlink',
		      	'style' => 'background-image: url(application/modules/Core/externals/images/admin/new_category.png);')) ?>
		  </div>
    </form>
  </div>
</div>