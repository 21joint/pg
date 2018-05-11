<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: blockuser.tpl 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate("Block Users") ?></h2>
<?php if( count($this->navigation) ): ?>
	<div class='seaocore_admin_tabs'>
  	<?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
	</div>
<?php endif; ?>

<p> <?php echo $this->translate("Here, you can block users from creating feedback, and from commenting on feedback. You may search for users to block using the form below.") ?> </p>

<br />

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

<div class='admin_search'> <?php echo $this->formFilter->render($this) ?> </div>
<br />
<div class='admin_members_results'>
  <div>
    <?php $memberCount = $this->paginator->getTotalItemCount() ?>
    <?php echo $this->translate("%s members found", $memberCount); ?>
  </div>
  <?php echo $this->paginationControl($this->paginator); ?>
</div>
<br />
<form id='multidelete_form' method="post" action="<?php echo $this->url(array('action'=>'multi-delete'));?>" onSubmit="multiDelete()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('user_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
        <th align="left" style="width:5%;"><a href="javascript:void(0);" onclick="javascript:changeOrder('username', 'ASC');"><?php echo $this->translate("Username") ?></a></th>
        <th style='width: 5%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('block_feedback', 'ASC');"><?php echo $this->translate("Feedback Posting") ?></a></th>
        <th style='width: 5%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('block_comment', 'ASC');"><?php echo $this->translate("Comment Posting") ?></a></th>
        <th align="left" style='width: 5%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('email', 'ASC');"><?php echo $this->translate("Email") ?></a></th>
        <th style='width: 5%;' class='admin_table_centered'><a href="javascript:void(0);" onclick="javascript:changeOrder('level_id', 'ASC');"><?php echo $this->translate("User Level") ?></a></th>
        <th align="left" style='width: 5%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'DESC');"><?php echo $this->translate("Signup Date") ?></a></th>
      </tr>
    </thead>
    <tbody>
      <?php if( count($this->paginator) ): ?>
	      <?php foreach( $this->paginator as $item ): ?>
		      <tr>
		        <td><?php echo $item->user_id ?></td>
		        <td class='admin_table_bold'><?php echo $this->htmlLink($this->item('user', $item->user_id)->getHref(), $this->item('user', $item->user_id)->getTitle(), array('target' => '_blank')) ?></td>
		        <?php if($item->block_feedback == 0):?>
		        	<td align="center" class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'blockfeedback', 'id' => $item->user_id, 'page' => $this->page), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/feedback_approved1.gif', '', array('title'=> $this->translate('Click to block feedback from this user')))) ?>
		        <?php else: ?>
							<td align="center" class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'blockfeedback', 'id' => $item->user_id, 'page' => $this->page), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/block_comment.png', '', array('title'=> $this->translate('Click to allow feedback from this user')))) ?> </td>
		        <?php endif; ?>
		        <?php if($item->block_comment == 0):?>
		        	<td align="center" class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'block-comment', 'id' => $item->user_id, 'page' => $this->page), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/feedback_approved1.gif', '', array('title'=> $this->translate('Click to block comments from this user')))) ?>
		        <?php else: ?>
		        	<td align="center" class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'feedback', 'controller' => 'admin', 'action' => 'block-comment', 'id' => $item->user_id, 'page' => $this->page), $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/block_comment.png', '', array('title'=> $this->translate('Click to allow comments from this user')))) ?> </td>
						<?php endif; ?>
		        <td><a href='#'><?php echo $item->email ?></a></td>
		        <td class='admin_table_centered'><?php echo $this->translate(Engine_Api::_()->getItem('authorization_level', $item->level_id)->getTitle()) ?></td>
		        <td><?php echo $item->creation_date ?></td>
		      </tr>
	      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</form>