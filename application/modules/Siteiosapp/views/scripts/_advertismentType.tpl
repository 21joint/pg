<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteandroidapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    _facebookAccessPermission.tpl 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
$isSelected = false;
$getWebsiteName = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
$websiteStr = str_replace(".", "-", $getWebsiteName);
$dirName = 'ios-' . $websiteStr . '-app-builder';
$appBuilderBaseFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/' . $dirName . '/settings.php';
if (file_exists($appBuilderBaseFile)) {
    include $appBuilderBaseFile;
    if (isset($appBuilderParams['google_ad_placement_id']) && !empty($appBuilderParams['google_ad_placement_id']))
        $isSelected = $appBuilderParams['google_ad_placement_id'];
}
?>
<div class="form-wrapper" id="google_ad_placement_id-wrapper" style="display: block;">
    <div id="google_ad_placement_id-label" class="form-label">
       Advertising - Google Ad Unit ID
    </div>
    <div class="form-element" id="google_ad_placement_id-element">
        <input type="hidden" value="" name="google_ad_placement_id">
        <span class="description"><?php echo ('You can now monetize your app through Admob. To enable this advertising, enter the Unit ID for your app. [To get this ID, please follow this <a href = https://youtu.be/OdNJwxyw778  target = "_blank">video tutorial</a>. You can configure advertising in your app from the “Advertising” section.]'); ?></span>
        <?php if (!empty($isSelected)): ?>
        <input type="text" value=<?php echo $appBuilderParams['google_ad_placement_id']?> id="google_ad_placement_id" name="google_ad_placement_id" style="margin-top:10px">
        <?php else: ?>
        <input type="text" value="" id="google_ad_placement_id" name="google_ad_placement_id" style="margin-top:10px">
         <?php endif; ?>

    </div>
</div>