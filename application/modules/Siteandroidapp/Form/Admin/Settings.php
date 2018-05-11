<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteandroidapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Settings.php 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteandroidapp_Form_Admin_Settings extends Engine_Form {

    // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
    public $_SHOWELEMENTSBEFOREACTIVATE = array(
        "environment_mode",
        "submit_lsetting"
    );

    public function init() {

        $this->setTitle('Global Settings')
                ->setDescription('These settings affect all members in your community.');


        $coreSettingsApi = Engine_Api::_()->getApi('settings', 'core');
        // ELEMENT FOR LICENSE KEY
        $this->addElement('Text', 'siteandroidapp_lsettings', array(
            'label' => 'Enter License key',
            'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('siteandroidapp.lsettings'),
        ));

        if (APPLICATION_ENV == 'production') {
            $this->addElement('Checkbox', 'environment_mode', array(
                'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few stores of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
                'description' => 'System Mode',
                'value' => 1,
            ));
        } else {
            $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
        }

        $this->addElement('Button', 'submit_lsetting', array(
            'label' => 'Activate Your Plugin Now',
            'type' => 'submit',
            'ignore' => true
        ));


        if (!Engine_Api::_()->getApi('Core', 'siteapi')->isRootFileValid()) {
            $this->addElement('Radio', 'siteapi_valid_root_file', array(
                'label' => 'Modify Root File',
                'description' => 'Modify Root File',
                'multiOptions' => array(
                    1 => 'Yes',
                    0 => 'No'
                ),
                'value' => 1
            ));
        }


        $linkApiKey = '<a href="http://developer.android.com/google/gcm/gs.html" class="buttonlink icon_help" target="_blank"></a>';
        $this->addElement('Text', 'siteandroidapp_google_server_api_key', array(
            'label' => 'Android API Key',
            'description' => "This API key will be used for push notifications. If you've already created Android project in <a href='https://console.developers.google.com' target='_blank'>Google Account</a>, then <a href='https://youtu.be/wK95X4RLVYw' target='_blank'>click here</a> to see steps to create API key for your app. If you haven't yet created your Android project, then <a href='https://youtu.be/0PNvUN9YfZY' target='_blank'>click here</a> for the steps to create API key.",
            'required' => false,
            'allowEmpty' => true,
            'style' => 'width: 300px',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("siteandroidapp.google.server.api.key")
        ));
        $this->getElement('siteandroidapp_google_server_api_key')->getDecorator('Description')->setEscape(false);

        $this->addElement('Radio', 'browse_as_guest', array(
            'label' => 'Enable Browse as Guest',
            'description' => 'Do you want to allow Non Logged-in Users (guests) to browse your app? If disabled, then only Logged-in members will be able to use / browse your app.',
            'required' => true,
            'allowEmpty' => false,
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("siteandroidapp.browse.guest", 1)
        ));

        $this->getElement('browse_as_guest')->getDecorator('Description')->setEscape(false);

        $this->addElement('Radio', 'siteandroidapp_video_quality', array(
            'label' => 'Quality of Recorded Video',
            'description' => 'You can now Shoot a Video while uploading video from Status Box and Video creation form. Select the quality of video recording in App. [Note: 10 seconds of video recorded with Low Quality takes 1 MB and takes 15 MB size when created with High Quality.]',
            'required' => true,
            'allowEmpty' => false,
            'multiOptions' => array(
                0 => 'Low (Suggested)',
                1 => 'High',
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("siteandroidapp.video.quality", 0)
        ));

        $this->getElement('siteandroidapp_video_quality')->getDecorator('Description')->setEscape(false);

        $this->addElement('Radio', 'siteandroidapp_sound_enable', array(
            'label' => 'Enable Sound',
            'description' => 'Do you want to enable sound effects, when user performs various actions in your app? [Example - like button, back button, pull-to-refresh etc.]',
            'required' => true,
            'allowEmpty' => false,
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("siteandroidapp.sound.enable", 1)
        ));

        $this->getElement('siteandroidapp_sound_enable')->getDecorator('Description')->setEscape(false);

        $this->addElement('Radio', 'android_enable_location', array(
            'label' => 'Enable Location',
            'description' => 'Do you want to enable location feature in your App? If enabled you will be able to see the location based results on browse and category pages, else all the content will get displayed. [This feature will work only with SEAO plugins.]',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("android.enable.location", 1)
        ));
        $this->getElement('android_enable_location')->getDecorator('Description')->setEscape(false);

        $getHost = Engine_Api::_()->getApi('Core', 'siteapi')->getHost();

        $getHost = Engine_Api::_()->getApi('core', 'siteapi')->getHost();
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $baseUrl = @trim($baseUrl, "/");

        $getHost = $getHost . '/' . $baseUrl . '/admin/seaocore/settings/map';
        $this->addElement('Radio', 'siteandroid_autodetect_enable', array(
            'label' => 'Auto Detect Location',
            'description' => 'Enabled Auto-detection of location.[Note: Location Auto-detection in app will work only when you have set "Enable Specific Locations" to No from "Location & Maps".  <a href=' . $getHost . ' target="_blank">click here</a>]',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("siteandroid.autodetect.enable", 0),
            'onchange' => 'enablelocation()'
        ));
        $this->getElement('siteandroid_autodetect_enable')->getDecorator('Description')->setEscape(false);


        $this->addElement('Radio', 'siteandroid_change_location', array(
            'label' => 'Change Location',
            'description' => 'Do you want users to change their location manually ?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("siteandroid.change.location", 0),
        ));
        $this->getElement('siteandroid_change_location')->getDecorator('Description')->setEscape(false);

        $this->addElement('Radio', 'android_member_view', array(
            'label' => 'Browse Members Page View',
            'description' => 'Selected view should be displayed in the app by default.',
            'multiOptions' => array(
                1 => 'List View',
                0 => 'Map View'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("android.member.view", 1)
        ));
        $this->getElement('android_member_view')->getDecorator('Description')->setEscape(false);

        $this->addElement('Radio', 'android_popup_enable', array(
            'label' => 'Enable Pop-up',
            'description' => 'Enabled Pop-up for App Upgrade.',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("android.popup.enable", 0),
            'onchange' => 'popupenableType()'
        ));
        $this->getElement('android_popup_enable')->getDecorator('Description')->setEscape(false);

        $this->addElement('Text', 'siteandroidapp_version_upgrade', array(
            'label' => "App Version",
            'description' => "Users using lower App version than the selected one will be shown a popup.",
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('siteandroidapp.version.upgrade')
        ));
        $this->getElement('siteandroidapp_version_upgrade')->getDecorator('Description')->setEscape(false);


        $this->addElement('Textarea', 'siteandroidapp_version_description', array(
            'label' => 'Description',
            'description' => '',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('siteandroidapp.version.description')
        ));


        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true,
            'order' => 500,
        ));
    }

}
