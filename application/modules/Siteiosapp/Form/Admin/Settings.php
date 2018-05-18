<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Settings.php 2015-10-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteiosapp_Form_Admin_Settings extends Engine_Form {

    // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
    public $_SHOWELEMENTSBEFOREACTIVATE = array(
        "environment_mode",
        "submit_lsetting"
    );

    public function init() {
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $URL = $view->baseUrl() . "/admin/files";
        $click = '<a href="' . $URL . '" target="_blank">file manager</a>';
        $customBlocks = sprintf("The below chosen Apple APN certificate will be used to send push notifications from your server. The SocialEngineAddOns Support Team will upload the appropriate certificate from %s at the time of app building, and will choose it from below. [Note: Please do not change this value unless you are sure of it. This field is for the SocialEngineAddOns Support Team that will build your app.]", $click);
        $imageExtensions = array('pem');
        $it = new DirectoryIterator(APPLICATION_PATH . '/public/admin/');
        $apnPath = $settings->getSetting('siteiosapp.apple.server.apn.key', '');
        $tempApn = @explode("public/admin/", $apnPath);
        if (!empty($tempApn[1]))
            $apnVal = "public/admin/" . $tempApn[1];
        else
            $apnVal = "";
        foreach ($it as $file) {
            if ($file->isDot() || !$file->isFile())
                continue;
            $basename = basename($file->getFilename());
            if (!($pos = strrpos($basename, '.')))
                continue;
            $ext = strtolower(ltrim(substr($basename, $pos), '.'));
            if (!in_array($ext, $imageExtensions))
                continue;
            $logoOptions['public/admin/' . $basename] = $basename;
        }
        if (empty($logoOptions))
            $logoOptions['new'] = "no pem file";
        $this->setTitle('Global Settings')
                ->setDescription('These settings affect all members in your community.');
        $coreSettingsApi = Engine_Api::_()->getApi('settings', 'core');
        // ELEMENT FOR LICENSE KEY
        $this->addElement('Text', 'siteiosapp_lsettings', array(
            'label' => 'Enter License key',
            'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('siteiosapp.lsettings'),
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
        if (isset($_REQUEST['updateCertificate']) && !empty($_REQUEST['updateCertificate'])) {
            $this->addElement('Select', 'apple_server_apn_key', array(
                'label' => 'Apple APN Server Certificate',
                'description' => $customBlocks,
                'multiOptions' => $logoOptions,
                'value' => $apnVal,
            ));
            $this->getElement('apple_server_apn_key')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
            $this->addElement('Password', 'password', array(
                'label' => 'APN Certificate Password',
                'description' => 'The SocialEngineAddOns Support Team will set this password for you at the time of app building. [Note: Please do not change this value unless you are sure of it. This field is for the SocialEngineAddOns Support Team that will build your app.]',
                'required' => true,
                'allowEmpty' => false,
                'values' => $settings->getSetting('siteiosapp.password', '')
            ));
            $this->addElement('Radio', 'siteiosapp_apn_mode', array(
                'label' => 'Select Push Notification Mode',
                'description' => 'Select the push notifiction mode. The SocialEngineAddOns Support Team will set this password for you at the time of app building. [Note: Please do not change the password value]',
                'multiOptions' => array(
                    1 => 'Production',
                    0 => 'Development'
                ),
                'value' => $settings->getSetting('siteiosapp_apn_mode', 1)
            ));
        }

        $this->addElement('Text', 'siteiosapp_shared_secret', array(
            'label' => "iTune's Shared Secret Key",
            'description' => "To see how to get your iTunes's shared secret key, please see the video <a href='https://youtu.be/oMqn4ZSRbrY' target='_blank'>here</a>.",
            'required' => true,
            'allowEmpty' => false,
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('siteiosapp.shared.secret')
        ));
        $this->getElement('siteiosapp_shared_secret')->getDecorator('Description')->setEscape(false);

        $this->addElement('Radio', 'siteiosapp_current_mode', array(
            'label' => 'In-App Purchase Environment',
            'description' => "Choose In-App Purchase environment from below, by default your app will remain in sandbox mode (test mode), so to enable payments through live accounts, please choose Production mode. [Note: App should be in sandbox mode, if your app has been sent for iTune's approval.]",
            'required' => true,
            'allowEmpty' => false,
            'multiOptions' => array(
                0 => 'Production Mode',
                1 => 'Sandbox Mode',
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('siteiosapp.current.mode', 1)
        ));
        $this->getElement('siteiosapp_current_mode')->getDecorator('Description')->setEscape(false);

        $this->addElement('Radio', 'browse_as_guest', array(
            'label' => 'Enable Browse As Guest',
            'description' => 'Do you want to allow "Non-Logged In" users(guests) to browse your app? If disabled, only "Logged In" members will be able to use/browse your app.',
            'required' => true,
            'allowEmpty' => false,
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('siteiosapp.browse.guest', 1)
        ));
        $this->getElement('browse_as_guest')->getDecorator('Description')->setEscape(false);

        $this->addElement('Radio', 'ios_enable_location', array(
            'label' => 'Enable Location',
            'description' => 'Do you want to enable location feature in your App? If enabled you will be able to see the location based results on browse and category pages, else all the content will get displayed. [This feature will work only with SEAO plugins.]',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("ios.enable.location", 1)
        ));
        $this->getElement('ios_enable_location')->getDecorator('Description')->setEscape(false);
        $getHost = Engine_Api::_()->getApi('Core', 'siteapi')->getHost();

        $getHost = Engine_Api::_()->getApi('core', 'siteapi')->getHost();
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $baseUrl = @trim($baseUrl, "/");

        $getHost = $getHost . '/' . $baseUrl . '/admin/seaocore/settings/map';

        $this->addElement('Radio', 'siteios_autodetect_enable', array(
            'label' => 'Auto Detect Location',
            'description' => 'Enabled Auto-detection of location.[Note: Location Auto-detection in app will work only when you have set "Enable Specific Locations" to No from "Location & Maps".  <a href=' . $getHost . ' target="_blank">click here</a>]',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("siteios.autodetect.enable", 0),
            'onchange' => 'enablelocation()'
        ));
        $this->getElement('siteios_autodetect_enable')->getDecorator('Description')->setEscape(false);


        $this->addElement('Radio', 'siteios_change_location', array(
            'label' => 'Change Location',
            'description' => 'Do you want users to change their location manually ?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("siteios.change.location", 0),
        ));
        $this->getElement('siteios_change_location')->getDecorator('Description')->setEscape(false);

        $this->addElement('Radio', 'siteios_member_view', array(
            'label' => 'Browse Members Page View',
            'description' => 'Selected view should be displayed in the app by default.',
            'multiOptions' => array(
                1 => 'List View',
                0 => 'Map View'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting("ios.member.view", 1)
        ));
        $this->getElement('siteios_member_view')->getDecorator('Description')->setEscape(false);


        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true,
            'order' => 500,
        ));
    }

}
