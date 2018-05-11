<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<!--ADD NAVIGATION-->
<?php include APPLICATION_PATH . '/application/modules/Sitereaction/views/scripts/_adminNav.tpl'; ?>
<?php
$needToUpdate = array(
    'luminous' => array('moduleName' => 'siteluminous', 'version' => '4.8.12'),
    'spectacular' => array('moduleName' => 'spectacular', 'version' => '4.8.12'),
    'shoppinghub' => array('moduleName' => 'sitetheme', 'version' => '4.8.12'),
    'captivate' => array('moduleName' => 'captivate', 'version' => '4.8.12'),
);
$activeTheme = $this->layout()->themes[0];

if (isset($needToUpdate[$activeTheme])):
  $moduleInfo = $needToUpdate[$activeTheme];
  $module = Engine_Api::_()->getDbtable('modules', 'core')->getModule($moduleInfo['moduleName']);
  if ($module && $module->version < $moduleInfo['version']):
    ?>
    <div>
      <div class="seaocore-notice">
        <div class="seaocore-notice-icon">
          <img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/notice.png" alt="Notice" />
        </div>
        <div class="seaocore-notice-text ">
          You are having SocialEngineAddons "<?php echo $module->title ?>" activated on your site.
          So, if you are getting a theme issue in this plugin then you can either upgrade this theme from <a href="admin/seaocore/settings/upgrade" >here</a> or in case if you have done any customization in the theme then you can contact to our Support Team from the SocialEngineAddOns account area to suggest your changes for this theme.
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>
<div class="settings">
  <?php echo $this->form->render($this) ?>
</div>
<style type="text/css">
  .settings .form-element .description {
    max-width: 100%;
  }
  .settings .form-element .description .seaocore_icon_view {
    margin-left: 5px;
  }
</style>
