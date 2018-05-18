<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageinvite
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: global.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/pluginLink.tpl'; ?>
<h2><?php echo $this->translate('Directory / Pages - Inviter Extension'); ?></h2>
<div class='tabs'>
  <?php
  // Render the menu
  echo $this->navigation()
          ->menu()
          ->setContainer($this->navigation)
          ->render();
  ?>
</div>
<?php include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_upgrade_messages.tpl'; ?>

<div class='clear'>
  <a href="<?php echo $this->url(array('module' => 'seaocore', 'controller' => 'admin-settings', 'action' => 'help-invite'), 'default', true) ?>" class="buttonlink" style="background-image:url(<?php echo $this->layout()->staticBaseUrl;?>application/modules/Sitepageinvite/externals/images/admin/help.gif);"><?php echo $this->translate("Guidelines to configure the applications for Contact Importer Settings") ?></a>
  <br /><br />
  <div class='settings'>
    <?php echo $this->form->render($this) ?>
  </div>
</div>

<script type="text/javascript">
if (<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('pageinvite.friend.invite.enable', 1);?> == 0) {
  $('pageinvite_show_webmail-wrapper').style.display = 'none';		
  
}
  if ($('pageinvite_friend_invite_enable-1')) {
  $('pageinvite_friend_invite_enable-1').addEvent('click', function () {
  		$('pageinvite_show_webmail-wrapper').style.display = 'block';		
  })
}

if ($('pageinvite_friend_invite_enable-0')) {
  $('pageinvite_friend_invite_enable-0').addEvent('click', function () {
  		$('pageinvite_show_webmail-wrapper').style.display = 'none';		
  })
}
</script>