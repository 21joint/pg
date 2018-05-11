<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 6590 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */ 
?>
<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.tag', 0) && !(empty($this->can_create) && !empty($this->viewer_id))):?>
<!-- Auto completer script for Tag field-->
<script type="text/javascript">

sm4.core.runonce.add(function() { 
		sm4.core.Module.autoCompleter.attach("tags", '<?php echo $this->url(array('module' => 'core', 'controller' => 'tag', 'action' => 'suggest'), 'default', true) ?>', {'singletextbox': true, 'limit':10, 'minLength': 1, 'showPhoto' : false, 'search' : 'text'}, 'toValues'); 

   <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitemobile.tinymceditor', 0)):?>
			setTimeout(function() {
				sm4.core.tinymce.showTinymce();
			}, 100);
   <?php endif;?>
	});

</script>
<?php endif;?>
<?php if(!empty($this->feedback_post) && empty($this->viewer_id) && empty($this->feedback_public)): ?> 
		<div class="tip" style="margin:10px 0 0 100px;">
			<span>
				<?php echo $this->translate("Please ") ?>
				<?php echo $this->htmlLink(Array('module'=> 'user', 'controller' => 'auth', 'action' => 'login', 'route' => 'default'), $this->translate("login"), array('target' => '_parent')); ?>
				<?php echo $this->translate(" to view and create feedback") ?>
			</span>
		</div>
<?php else: ?>
		<?php if(empty($this->can_create) && !empty($this->viewer_id)): ?>
				<div class="tip" style="margin:150px 0 0 15px;">
					<span>
						<?php echo $this->translate('You have been blocked by the site admin from creating feedback.'); ?>
					</span>
				</div>
		<?php elseif($this->feedback_post == 1 && empty($this->viewer_id)):?>
				<div class="tip" style="margin:150px 0 0 60px;">
					<span>
						<?php echo $this->translate("Please ") ?>
						<?php echo $this->htmlLink(Array('module'=> 'user', 'controller' => 'auth', 'action' => 'login', 'route' => 'default'), $this->translate("login"), array('target' => '_parent')); ?>
						<?php echo $this->translate(" to create feedback") ?>
					</span>	
				</div>
		<?php else:?>
			<?php echo $this->form->render($this) ?> 	
		<?php endif; ?>
            <?php endif; ?>        
