<?php
/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Sitegifplayer
* @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: faq.tpl 2017-05-15 00:00:00Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/
?>


<h2 class="fleft">
  <?php echo $this->translate('GIF Player Plugin');?>
</h2>

<?php if (count($this->navigation)): ?>
<div class='seaocore_admin_tabs'>
  <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
</div>
<?php endif; ?>
<?php
include_once APPLICATION_PATH .
'/application/modules/Sitegifplayer/views/scripts/admin-settings/faq_help.tpl';
?>

