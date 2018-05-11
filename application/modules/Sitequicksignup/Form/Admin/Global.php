<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_Form_Admin_Global extends Engine_Form {

    protected $_captcha_options = array(
        1 => 'Yes, make members complete the CAPTCHA form.',
        0 => 'No, do not show a CAPTCHA form.',
    );

    public function init() {

        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        $this
                ->setTitle('Global Settings')
                ->setDescription('These settings will affect all members in your community.');
        $this->addElement('Text', 'sitequicksignup_lsettings', array(
            'label' => 'Enter License key',
            'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
            'value' => $coreSettings->getSetting('sitequicksignup.lsettings'),
        ));

        if (APPLICATION_ENV == 'production') {
            $this->addElement('Checkbox', 'environment_mode', array(
                'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few pages of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
                'description' => 'System Mode',
                'value' => 1,
            ));
        } else {
            $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
        }

        $this->addElement('Radio', 'sitequicksignup_allow_quick_signup', array(
            'label' => 'Single Step Signup',
            'description' => "Do you want to allow users to do Single Step Signup on your website?",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'escape' => false,
            'value' => $coreSettings->getSetting('sitequicksignup.allow.quick.signup', 1),
            'onclick' => 'hideFields(this.value)'
        ));


        $this->addElement('Radio', 'sitequicksignup_allow_title', array(
            'label' => 'Signup Form Title',
            'description' => "Do you want to show Signup Form Title?",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => $coreSettings->getSetting('sitequicksignup.allow.title', 1),
            'onclick' => 'allowTitle(this.value);'
        ));

        $this->addElement('Text', 'sitequicksignup_title', array(
            'label' => 'Text for Signup Form Title',
            'description' => "Please enter the text below for Signup Form Title",
            'value' => $coreSettings->getSetting('sitequicksignup.title', 'Create Account')
        ));

        $this->addElement('Radio', 'sitequicksignup_allow_description', array(
            'label' => 'Signup Form Description',
            'description' => "Do you want to show Signup Form Description?",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => $coreSettings->getSetting('sitequicksignup.allow.description', 1),
            'onclick' => 'allowDescription(this.value);'
        ));

        $this->addElement('Text', 'sitequicksignup_description', array(
            'label' => 'Text for Signup Form Description',
            'description' => "Please enter the text below for Signup Form Description.",
            'value' => $coreSettings->getSetting('sitequicksignup.description', '')
        ));

        $this->addElement('Radio', 'sitequicksignup_field_description', array(
            'label' => 'Enable Fields Description',
            'description' => 'Do you want to show description for fields in the Signup Form? [Note: This setting is for default field descriptions only. For example, in case of Password field “Passwords must be at least 6 characters in length” is displayed, so if you select \'No\' this description will get removed from the bottom of Password field.]',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => $coreSettings->getSetting('sitequicksignup.field.description', 1),
        ));

        $this->addElement('Radio', 'sitequicksignup_confirm_email', array(
            'label' => 'Email Confirmation',
            'description' => "Do you want users to confirm email during Signup?",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'escape' => false,
            'value' => $coreSettings->getSetting('sitequicksignup.confirm.email', 0)
        ));

        $this->addElement('Radio', 'sitequicksignup_confirm_password', array(
            'label' => 'Password Confirmation',
            'description' => "Do you want users to confirm password during Signup?",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'escape' => false,
            'value' => $coreSettings->getSetting('sitequicksignup.confirm.password', 1)
        ));
        
        $this->addElement('Radio', 'sitequicksignup_subscription_enabled', array(
            'label' => 'Choose Subscription Plan',
            'description' => "Do you want your users to be able to choose a subscription plan upon signup? [Note: Subscription will be the second step after quick signup form. For subscription feature to work smoothly, plans should be enabled for your site. You can create and manage the plans from <a href='admin/payment/package' > here </a>.] ",
            'multiOptions' => array(
                1 => 'Yes, give users the option to choose upon signup.',
                0 => 'No, do not allow users to choose upon signup.'
            ),
            'value' => $coreSettings->getSetting('sitequicksignup.subscription.enabled', 0)
        ));
        $this->sitequicksignup_subscription_enabled->addDecorator('Description', array('placement' => 'PREPEND', 'class' => 'description', 'escape' => false));
        $this->addElement('Radio', 'sitequicksignup_welcome_popup_enabled', array(
            'label' => 'Welcome Popup',
            'description' => "Do you want to show welcome popup to the users just after their signup?",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'escape' => false,
            'value' => $coreSettings->getSetting('sitequicksignup.welcome.popup.enabled', 1)
        ));
        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));
    }

}
