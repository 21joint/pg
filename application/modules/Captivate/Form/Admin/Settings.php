<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Settings.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Form_Admin_Settings extends Engine_Form {

    // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
    public $_SHOWELEMENTSBEFOREACTIVATE = array(
        "submit_lsetting", "environment_mode", "captivate_landing_page_layout"
    );

    public function init() {
        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        $this->setTitle(sprintf(Zend_Registry::get('Zend_Translate')->_("Global Settings")))
                ->setDescription(sprintf(Zend_Registry::get('Zend_Translate')->_("These settings affect all members in your community.")));

        //$this->loadDefaultDecorators();
        // ELEMENT FOR LICENSE KEY
        $this->addElement('Text', 'captivate_lsettings', array(
            'label' => 'Enter License key',
            'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
            'value' => $coreSettings->getSetting('captivate.lsettings'),
        ));

        $this->addElement('Radio', 'captivate_landing_page_layout', array(
            'label' => 'Change Landing Page Layout',
            'description' => "Do you want the layout of your home page to be changed as per the default set-up of this theme ? If you choose 'Yes' then your current layout of Home page will be replaced with a new one.<br />
[<strong>Recommendation</strong>: You need to place widgets related to content from ‘Layout Editor’, to beautifying your demo like us.]",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => 1,
        ));
        $this->captivate_landing_page_layout->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
        if (APPLICATION_ENV == 'production') {
            $this->addElement('Checkbox', 'environment_mode', array(
                'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few stores of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
                'description' => 'System Mode',
                'value' => 1,
            ));
        } else {
            $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
        }

        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $baseURL = $view->baseUrl();
        $captivateLendingBlockValue = $coreSettings->getSetting('captivate.lending.block', null);
        if (empty($captivateLendingBlockValue) || is_array($captivateLendingBlockValue)) {
            $captivateLendingBlockValue = '<div id="show_help_content" style="width:1200px;margin:0 auto;display:table;"><div>
<span style="font-size:48px;color:#292929;float:left;width:100%;text-align:center;margin:80px 0 0 0;position:absolute;top:0;left:0;right:0;clear:both;">How It Works !</span>
<div style="float: left; margin: 10px 0; opacity: 1; padding: 125px 0; text-align: center; width: 33.3%;"><a href="' . $baseURL . '/videos"><span style=" background-position: center bottom; background-repeat: no-repeat;  height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/modules/Captivate/externals/images/post-video.png); display: block;">&nbsp;</span></a> <a href="' . $baseURL . '/videos"> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 40px; text-align: center; width: 100%;">Post & Watch Videos</span> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Post the videos, watch the videos and share the videos.</span> </a></div>
<div style="float: left; margin: 10px 0; opacity: 1; padding: 125px 0; text-align: center; width: 33.3%;"><a href="' . $baseURL . '/channels"><span style=" background-position: center 50%; background-repeat: no-repeat;  height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/modules/Captivate/externals/images/create-channel.png); display: block;">&nbsp;</span></a> <a href="' . $baseURL . '/channels"> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 40px; text-align: center; width: 100%;">Create & Explore Channels</span> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Create your channel and explore the channels.</span></a></div>
<div style="float: left; margin: 10px 0; opacity: 1; padding: 125px 0; text-align: center; width: 33.3%;"><a href="' . $baseURL . '/videos/playlists/browse"><span style=" background-position: center 50%; background-repeat: no-repeat;  height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/modules/Captivate/externals/images/create-playlist.png); display: block;">&nbsp;</span></a> <a href="' . $baseURL . '/videos/playlists/browse"> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 40px; text-align: center; width: 100%;">Create and Share Playlists</span> <span style="color: #fff;text-shadow: 0 0 3px rgba(0, 0, 0, 0.4); float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Create playlists for your loved videos and share the playlists.</span></a></div>
<a style="text-indent: 100px; height: 20px; width: 20px; position: absolute; top: 12px; background-image: url(' . $baseURL . '/application/modules/Captivate/externals/images/close-icon.png);" href="#">.</a></div></div>';
        } else {
            $captivateLendingBlockValue = @base64_decode($captivateLendingBlockValue);
        }
        //WORK FOR MULTILANGUAGES START
        $localeMultiOptions = Engine_Api::_()->captivate()->getLanguageArray();

        $defaultLanguage = $coreSettings->getSetting('core.locale.locale', 'en');
        $total_allowed_languages = Count($localeMultiOptions);
        if (!empty($localeMultiOptions)) {
            foreach ($localeMultiOptions as $key => $label) {
                $lang_name = $label;
                if (isset($localeMultiOptions[$label])) {
                    $lang_name = $localeMultiOptions[$label];
                }

                $page_block_field = "captivate_lending_page_block_$key";
                $page_block_title_field = "captivate_lending_page_block_title_$key";

                if (!strstr($key, '_')) {
                    $key = $key . '_default';
                }

                $keyForSettings = str_replace('_', '.', $key);
                $captivateLendingBlockValueMulti = $coreSettings->getSetting('captivate.lending.block.languages.' . $keyForSettings, null);
                if (empty($captivateLendingBlockValueMulti)) {
                    $captivateLendingBlockValueMulti = $captivateLendingBlockValue;
                } else {
                    $captivateLendingBlockValueMulti = @base64_decode($captivateLendingBlockValueMulti);
                }

                $captivateLendingBlockTitleValueMulti = $coreSettings->getSetting('captivate.lending.block.title.languages.' . $keyForSettings, 'Get Started');
                if (empty($captivateLendingBlockTitleValueMulti)) {
                    $captivateLendingBlockTitleValueMulti = 'Get Started';
                } else {
                    $captivateLendingBlockTitleValueMulti = @base64_decode($captivateLendingBlockTitleValueMulti);
                }

                $page_block_label = sprintf(Zend_Registry::get('Zend_Translate')->_("Action Button's Slide Down Content in %s"), $lang_name);
                $page_block_title_label = sprintf(Zend_Registry::get('Zend_Translate')->_("Get Started Action Button's Slide Down Content in %s"), $lang_name);

                if ($total_allowed_languages <= 1) {
                    $page_block_field = "captivate_lending_page_block";
                    $page_block_title_field = "captivate_lending_page_block_title";
                    $page_block_label = "Action Button's Slide Down Content";
                    $page_block_title_label = "Action Button Title";
                } elseif ($label == 'en' && $total_allowed_languages > 1) {
                    $page_block_field = "captivate_lending_page_block";
                    $page_block_title_field = "captivate_lending_page_block_title";
                }

                $this->addElement('Text', $page_block_title_field, array(
                    'label' => $page_block_title_label,
                    'description' => "Choose a title for the action button, like 'Get Started', etc, that is currently getting displayed on the image rotator on the landing page. Clicking on this will attractively slide down related content that you can configure below. [This can be used to show useful information regarding your website like 'How it Works', 'Get Going', 'Tours', 'Contact Us', etc.] (Note: For this button to be displayed, it must be enabled in the settings of the widget: 'Responsive Captivate Theme - Landing Page Images'.)",
                    'value' => $captivateLendingBlockTitleValueMulti,
                    'filters' => array(
                        new Engine_Filter_Html(),
                        new Engine_Filter_Censor()),
                ));

                $editorOptions = Engine_Api::_()->seaocore()->tinymceEditorOptions();
                $editorOptions['height'] = '500px';

                $this->addElement('TinyMce', $page_block_field, array(
                    'label' => $page_block_label,
                    'description' => "Configure the content that gets shown in an attractive slide-down manner, when someone clicks on the Action Button. In this content, you can include important links of your website, or a quick overview of your website to enable users to get started.",
                    'attribs' => array('rows' => 24, 'cols' => 80, 'style' => 'width:200px; max-width:200px; height:240px;'),
                    'value' => $captivateLendingBlockValueMulti,
                    'filters' => array(
                        new Engine_Filter_Html(),
                        new Engine_Filter_Censor()),
                    'editorOptions' => $editorOptions,
                ));
            }
        }
        //WORK FOR MULTILANGUAGES END
        //GET DESCRIPTION
        $description = sprintf(Zend_Registry::get('Zend_Translate')->_(" You can change the columns width of any widgetized page by placing the 'Column Width' widget available at %s under SocialEngineAddOns Core widgets."), '<a href="admin/content" target="_blank">layout editor</a>');

        $this->addElement('Dummy', 'captivate_column_width', array(
            'label' => 'Change Column Width of Widgetized Page',
            'description' => $description
        ));
        $this->captivate_column_width->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));


        $this->addElement('Radio', 'captivate_floating_header', array(
            'label' => 'Floating Header',
            'description' => 'Do you want to enable floating header ?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.floating.header', 1),
        ));

        $this->addElement('Radio', 'captivate_circular_image', array(
            'label' => 'Member\'s Thumbnail Images in Circular',
            'description' => 'Do you want to display member\'s thumbnail images in circular shape ?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.circular.image', 0),
        ));

        //Add submit button
        $this->addElement('Button', 'submit_lsetting', array(
            'label' => 'Activate Your Plugin Now',
            'type' => 'submit',
            'ignore' => true
        ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Submit',
            'type' => 'submit',
            'decorators' => array(
                'ViewHelper',
            ),
        ));
    }

}
