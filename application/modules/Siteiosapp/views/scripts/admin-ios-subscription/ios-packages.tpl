<?php

/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John Boehr <j@webligo.com>
 */
?>

<h2>
    <?php echo $this->translate('iOS Mobile Application - iPhone and iPad'); ?>
</h2>

<?php if (count($this->navigation)): ?>
<div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
</div>
<?php endif; ?>

<?php if (count($this->subnavigation)): ?>
<div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->subnavigation)->render() ?>
</div>
<?php endif; ?>
<h4>
    <?php echo $this->translate('Below you can view all subscription plans which will be available to the iOS App Users on Signup Process.</br> [Note 1: iOS follows in-app purchase for signup subscriptions, so you need to create the below subscription plans in your iTunes account. Your plan details on iTunes should match to the below plan details. Please refer <a href="' . $this->url(array('module' => 'siteiosapp', 'controller' => 'ios-subscription', 'action' => 'faq'), 'admin_default', true) . '">faq</a> for getting info regarding Plan creation in iTunes Account] </br>[Note 2: In case the details of any subscription plan gets mismatched then that plan will not get displayed on signup process.]'); ?>
</h4>
</br>
<div class='admin_results'>
    <div>
    <?php $count = count($this->packagesInfo) ?>
    <?php echo $this->translate(array("%s plan found", "%s plans found", $count), $count) ?>
    </div>
</div>

<br />


<?php if( count($this->packagesInfo) > 0 ): ?>
<table class='admin_table'>
    <thead>
        <tr>
            <th style='width: 1%;'>
            <?php echo $this->translate("ID") ?>
            </th>

            <th style='width: 1%;'>
            <?php echo $this->translate("Plan Title") ?>
            </th>

            <th style='width: 1%;'>
            <?php echo $this->translate("Product Id") ?>
            </th>

            <th style='width: 1%;'>
          <?php echo $this->translate("Recurrence") ?>
            </th>

            <th style='width: 1%;'>
            <?php echo $this->translate("Duration") ?>
            </th>
        </tr>
    </thead>
    <tbody>
      <?php foreach( $this->packagesInfo as $packageInfo ): ?>
        <tr>
            <td><?php echo $packageInfo['package_id'] ?></td>
            <td class='admin_table_bold'>
            <?php  echo (isset($packageInfo['title']) && !empty($packageInfo['title'])) ? $packageInfo['title'] : "-"; ?>
            </td>
            <td>
            <?php echo (isset($packageInfo['iTunesId']) && !empty($packageInfo['iTunesId'])) ? $packageInfo['iTunesId'] : "-"; ?>
            </td>
            <td>
            <?php echo (isset($packageInfo['recurrence']) && !empty($packageInfo['recurrence'])) ? $packageInfo['recurrence'] : "-";  ?>
            </td>
            <td>
            <?php echo (isset($packageInfo['duration']) && !empty($packageInfo['duration'])) ? $packageInfo['duration'] : "-"; ?>
            </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>