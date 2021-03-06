<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegroupmember
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2013-03-18 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitegroupmember/externals/styles/style_sitegroupmember.css'); ?>

<script type="text/javascript" >
	function owner(thisobj) {
		var Obj_Url = thisobj.href ;
		Smoothbox.open(Obj_Url);
	}
</script>

<?php if (!empty($this->friend)) : ?>
<?php if($this->friendpaginator->getTotalItemCount()): ?>
  <form id='filter_form_group' class='global_form_box' method='get' action='<?php echo $this->url(array(), 'sitegroupmember_browse', true) ?>' style='display: none;'>
    <input type="hidden" id="page" name="page"  value=""/>
  </form>
	
	<h3 class="sitegroup_mygroup_head sitegroup_member_browse_head"><span class=""><?php echo $this->translate('Friends');?></span></h3>
	<ul class="seaocore_browse_list">
		<?php foreach ($this->friendpaginator as $sitegroupmember): ?>
			<li id="sitegroupmember-item-<?php echo $sitegroupmember->member_id ?>">
				<div class="seaocore_browse_list_photo_small">
							<?php $user_object = Engine_Api::_()->getItem('user', $sitegroupmember->user_id);
							echo $this->htmlLink($user_object->getHref(), $this->itemPhoto($user_object->getOwner(), 'thumb.icon'));  ?>
				</div>
				<div class="seaocore_browse_list_options">
						<?php //FOR MESSAGE LINK
						$item = Engine_Api::_()->getItem('user', $sitegroupmember->user_id);
						if ((Engine_Api::_()->seaocore()->canSendUserMessage($item)) && (!empty($this->viewer_id))) : ?>
							<a href="<?php echo $this->base_url ?>/messages/compose/to/<?php echo $sitegroupmember->user_id ?>" target="_parent" class="buttonlink" style=" background-image: url(<?php echo $this->layout()->staticBaseUrl ?>application/modules/Messages/externals/images/send.png);"><?php echo $this->translate('Message'); ?></a>
						<?php endif; ?>
						<?php //Add friend link.
						$uaseFRIENFLINK = $this->userFriendshipAjax($this->user($sitegroupmember->user_id)); ?>
						<?php if (!empty($uaseFRIENFLINK)) : ?>
							<?php echo $uaseFRIENFLINK; ?>
						<?php endif; ?>
				</div>
				<div class='seaocore_browse_list_info'>
					<div class='seaocore_browse_list_info_title'>
						<h3><?php echo $this->htmlLink($this->item('user', $sitegroupmember->user_id)->getHref(), $this->user($sitegroupmember->user_id)->displayname, array('title' => $sitegroupmember->displayname, 'target' => '_parent')); ?></h4>
					</div>
					<div class="seaocore_browse_list_info_date">
						<?php //$count = Engine_Api::_()->getDbtable('membership', 'sitegroup')->countGroups($sitegroupmember->JOINP_COUNT);
						echo $this->htmlLink(array('route' => 'sitegroupmember_approve', 'action' => 'group-join', 'user_id' => $sitegroupmember->user_id), $this->translate(array('%s Group Joined', '%s Groups Joined', $sitegroupmember->JOINP_COUNT), $this->locale()->toNumber($sitegroupmember->JOINP_COUNT)), array('onclick' => 'owner(this);return false')); ?>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php //echo $this->paginationControl($this->paginator, null, array("pagination/pagination.tpl", "sitegroupmember"), array("orderby" => $this->orderby)); ?>
<?php endif;?>
<?php endif;?>

<?php if($this->paginator->getTotalItemCount()):?>
<br /> 
  <form id='filter_form_group' class='global_form_box' method='get' action='<?php echo $this->url(array(), 'sitegroupmember_browse', true) ?>' style='display: none;'>
    <input type="hidden" id="page" name="page"  value=""/>
  </form>
  <?php if(!empty($this->friend)) : ?>
		<h3 class="sitegroup_mygroup_head sitegroup_member_browse_head"><span><?php echo $this->translate('Other Members');?></span></h3>
	<?php endif; ?>
	<ul class="seaocore_browse_list">
		<?php foreach ($this->paginator as $sitegroupmember): ?>
			<li id="sitegroupmember-item-<?php echo $sitegroupmember->member_id ?>">
				<div class="seaocore_browse_list_photo_small"> 
							<?php $user_object = Engine_Api::_()->getItem('user', $sitegroupmember->user_id);
							echo $this->htmlLink($user_object->getHref(), $this->itemPhoto($user_object->getOwner(), 'thumb.icon'));  ?>
				</div>
				<div class="seaocore_browse_list_options seaocore_icon_done">
						<?php //FOR MESSAGE LINK
						$item = Engine_Api::_()->getItem('user', $sitegroupmember->user_id);
						if ((Engine_Api::_()->seaocore()->canSendUserMessage($item)) && (!empty($this->viewer_id))) : ?>
							<a href="<?php echo $this->base_url ?>/messages/compose/to/<?php echo $sitegroupmember->user_id ?>" target="_parent" class="buttonlink" style=" background-image: url(<?php echo $this->layout()->staticBaseUrl ?>application/modules/Messages/externals/images/send.png);"><?php echo $this->translate('Message'); ?></a>
						<?php endif; ?>
						<?php //Add friend link.
						$uaseFRIENFLINK = $this->userFriendshipAjax($this->user($sitegroupmember->user_id)); ?>
						<?php if (!empty($uaseFRIENFLINK)) : ?>
							<?php echo $uaseFRIENFLINK; ?>
						<?php endif; ?>
				</div>
				<div class='seaocore_browse_list_info'>
					<div class='seaocore_browse_list_info_title'>
						<h3><?php 
						echo $this->htmlLink($this->item('user', $sitegroupmember->user_id)->getHref(), $this->user($sitegroupmember->user_id)->displayname, array('title' => $sitegroupmember->displayname, 'target' => '_parent')); ?> </h3>
					</div>
					<div class="seaocore_browse_list_info_date">
						<?php //$count = Engine_Api::_()->getDbtable('membership', 'sitegroup')->countGroups($sitegroupmember->user_id);
						echo $this->htmlLink(array('route' => 'sitegroupmember_approve', 'action' => 'group-join', 'user_id' => $sitegroupmember->user_id), $this->translate(array('%s Group Joined', '%s Groups Joined', $sitegroupmember->JOINP_COUNT), $this->locale()->toNumber($sitegroupmember->JOINP_COUNT)), array('onclick' => 'owner(this);return false')); ?>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php echo $this->paginationControl($this->paginator, null, array("pagination/pagination.tpl", "sitegroupmember"), array("orderby" => $this->orderby)); ?>
<?php else: ?>
	<div class="tip">
		<span>
			<?php echo $this->translate('There are no search results to display.');?>
		</span>
	</div>
<?php endif;?>

<script type="text/javascript">
  var groupAction = function(group){
     var form;
     if($('filter_form')) {
       form=document.getElementById('filter_form');
      }else if($('filter_form_group')){
				form=$('filter_form_group');
			}
    form.elements['page'].value = group;
		form.submit();
  }
</script>