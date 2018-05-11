<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    index.tpl 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>
    <?php echo $this->translate('iOS Mobile Application - iPhone and iPad') ?>
</h2>
<?php if (count($this->navigation)): ?>
<div class='seaocore_admin_tabs'>
        <?php
        // Render the menu
        echo $this->navigation()->menu()->setContainer($this->navigation)->render()
        ?>
</div>
<?php endif; ?>
<?php $db = Engine_Db_Table::getDefaultAdapter();
    $initialTranslateAdapter = $db->select()
      ->from('engine4_core_settings', 'value')
      ->where('`name` = ?', 'core.translate.adapter')
      ->query()
      ->fetchColumn();
    if($initialTranslateAdapter != 'array'): ?>
<div class="seaocore_tip">
    <span>
        Enable "Translation Performance" from <a href="admin/core/settings/performance">here</a> to improve performance of your website and Mobile apps.  
    </span>
      <?php if (!Engine_Api::_()->getApi('Core', 'siteapi')->isRootFileValid()): ?>
    <span>
        API calling is not working as you have not "Modified Root File". Please configure it from <a href="admin/siteapi/settings">here</a> to start API calling for your website.
    </span>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php include_once APPLICATION_PATH . '/application/modules/Siteapi/views/scripts/_web_view_message.tpl'; ?>

<?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemobileiosapp') || (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemobileandroidapp') && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemobileapp'))): ?>
<div class="seaocore_tip">
    <span>
            <?php echo 'You are still using old version of our App, which can be deleted now, please <a href="' . $this->url(array('action' => 'delete-existing-app'), 'admin_default', false) . '" class="smoothbox">click here</a> to know more.'; ?>
    </span>
</div>
<?php endif; ?>
<div class="seaocore_settings_form">
    <a href="https://www.socialengineaddons.com/page/things-take-care-avoid-ios-app-rejection-apple-review-team"
       class="buttonlink" style="background-image:url(<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/help.gif);padding-left:23px;"><?php
               echo
               $this->translate("Guidelines to avoid app rejection from Apple Team")
               ?></a>
    <div class='settings'>
        <?php echo $this->form->render($this); ?>
    </div>
</div>


<script type="text/javascript" >
    window.addEvent('domready', function () {
       enablelocation();
    });

    function enablelocation() {
       if ($('siteios_autodetect_enable-1') && $('siteios_autodetect_enable-1').checked) {
          $('siteios_change_location-wrapper').style.display = 'block';
       } else
       {
          $('siteios_change_location-wrapper').style.display = 'none';
       }
    }

</script>
