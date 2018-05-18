<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2>
  <?php echo $this->translate('Directory / Pages - Badges Extension') ?>
</h2>
<div class='tabs'>
  <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
</div>

<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitepagebadge', 'controller' => 'manage', 'action' => 'manage'), $this->translate('Back to Manage Badges'), array('class' => 'buttonlink', 'style' => 'background-image: url('.$this->layout()->staticBaseUrl.'application/modules/Sitepagebadge/externals/images/back.png);')) ?>
<br /><br />

<div class='seaocore_settings_form'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>