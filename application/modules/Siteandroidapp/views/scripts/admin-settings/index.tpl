<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteandroidapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    index.tpl 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2>
    <?php echo $this->translate('Android Mobile Application') ?>
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
<?php if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemobileandroidapp') || (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemobileiosapp') && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemobileapp'))): ?>
<div class="seaocore_tip">
    <?php echo 'You are still using old version of our App, which can be deleted now, please <a href="' . $this->url(array('action' => 'delete-existing-app'), 'admin_default', false) . '" class="smoothbox">click here</a> to know more.'; ?>            
</div>
<?php endif; ?>
<div class="seaocore_settings_form">
    <div class='settings'>
<?php echo $this->form->render($this); ?>
    </div>
</div>


<script type="text/javascript" >
    window.addEvent('domready', function () {
       enablelocation();
    });

    function enablelocation() {
       if ($('siteandroid_autodetect_enable-1') && $('siteandroid_autodetect_enable-1').checked) {
          $('siteandroid_change_location-wrapper').style.display = 'block';
       } else
       {
          $('siteandroid_change_location-wrapper').style.display = 'none';
       }
    }

</script>


<script type="text/javascript" >
    window.addEvent('domready', function () {
       popupenableType();
    });

    function popupenableType() {
       if ($('android_popup_enable-1') && $('android_popup_enable-1').checked) {
          $('siteandroidapp_version_upgrade-wrapper').style.display = 'block';
          $('siteandroidapp_version_description-wrapper').style.display = 'block';
       } else
       {
          $('siteandroidapp_version_upgrade-wrapper').style.display = 'none';
          $('siteandroidapp_version_description-wrapper').style.display = 'none';
       }
    }

</script>


