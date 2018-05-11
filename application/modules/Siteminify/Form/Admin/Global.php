<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteminify_Form_Admin_Global extends Engine_Form {

    public $_SHOWELEMENTSBEFOREACTIVATE = array(
        "submit_lsetting", "environment_mode"
    );

    public function init() {
        $productType = 'siteminify';
        // ELEMENT FOR LICENSE KEY
        $this->addElement('Text', $productType . '_lsettings', array(
            'label' => 'Enter License key',
            'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting($productType . '.lsettings'),
        ));

        if (APPLICATION_ENV == 'production') {
            $this->addElement('Checkbox', 'environment_mode', array(
                'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few stores of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
                'description' => 'System Mode',
                'value' => 1,
            ));
        } else {
            $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
            $this->addError('Note: Minify Requests are disabled when your site is in development mode.');
        }

        $this->addElement('Button', 'submit_lsetting', array(
            'label' => 'Activate Your Plugin Now',
            'type' => 'submit',
            'ignore' => true
        ));
        $this->setTitle('Global Settings');
        $url = (_ENGINE_SSL ? 'https://' : 'http://')
                . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getBaseUrl();


        $description = $this->getTranslator()->translate('SITEMINIFY_FORM_ADMIN_GLOBAL_DESCRIPTION');
        $description = vsprintf($description, array(
            'http://gtmetrix.com?url=' . $url,
            'https://developers.google.com/speed/pagespeed/insights/?url=' . $url
        ));

        $this->setDescription($description);


        $this->loadDefaultDecorators();
        $this->getDecorator('Description')->setOption('escape', false);
        $settings = Engine_Api::_()->getApi('settings', 'core');

        //add css text
        $this->addElement('text', 'siteminify_css_combine_eachrequest', array(
            'label' => 'Minify CSS Request',
            'description' => 'Enter the number of CSS files which you want to compress / combine into the single request to improve website\'s performance? Note: If you donot want to enable it then please enter 0.',
            'required' => true,
            'value' => $settings->getSetting('siteminify.css.combine.eachrequest', 5),
        ));

        // add js text
        $this->addElement('text', 'siteminify_js_combine_eachrequest', array(
            'label' => 'Minify JS Request',
            'description' => 'Enter the number of JS files which you want to compress / combine into the single request to improve website\'s performance? Note: If you donot want to enable it then please enter 0.',
            'required' => true,
            'value' => $settings->getSetting('siteminify.js.combine.eachrequest', 5),
        ));

        $this->addElement('Textarea', 'siteminify_js_ignore', array(
          'label' => 'Ignore JS File Name',
          'description' => 'If you want to ignore some js from minify then you can add js file name. Multiple js file name can be added here separated by `,` . Example: "/plugin/externals/scripts,google,min.js,DIRECTORY NAME"',
            'value' => $settings->getSetting('siteminify.js.ignore', ''),
          ));
        $this->siteminify_js_ignore->getDecorator('Description')->setOption('placement', 'append');

        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));
    }

}
