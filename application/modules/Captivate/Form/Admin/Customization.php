<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Customization.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Form_Admin_Customization extends Engine_Form {

    public function init() {

        $this->setTitle(sprintf(Zend_Registry::get('Zend_Translate')->_("Theme Customization")));

        $this->addElement('Radio', 'captivate_theme_customization', array(
            'label' => 'Select Theme Color',
            'multiOptions' => array(
                0 => 'DEFAULT',
                1 => 'PINK',
                2 => 'GREEN',
                4 => 'DARK SKY BLUE',
                5 => 'VOILET',
                6 => 'ORANGE',
                7 => 'SUNFLOWER',
                8 => 'YELLOW',
                9 => 'EMERALD',
                10 => 'DARK PINK',
                3 => 'Custom Colors (Choosing this option will enable you to customize your theme according to your site.)'
            ),
            'onchange' => 'changeThemeCustomization();',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.theme.customization', 0),
        ));


        $this->addElement('Text', 'captivate_theme_color', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_themeColor.tpl',
                        'class' => 'form element'
                    )))
        ));

        $this->addElement('Text', 'captivate_theme_button_border_color', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_themeButtonBorderColor.tpl',
                        'class' => 'form element'
                    )))
        ));

        $this->addElement('Text', 'captivate_landingpage_signinbtn', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_themeLandingPageSigninButtonColor.tpl',
                        'class' => 'form element'
                    )))
        ));

        $this->addElement('Text', 'captivate_landingpage_signupbtn', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_themeLandingPageSignupButtonColor.tpl',
                        'class' => 'form element'
                    )))
        ));

        $this->addElement('Radio', 'captivate_theme_choose_website_image_color', array(
            'label' => 'Website\'s Body Background Image / Color',
            'description' => 'Do you want to display Background Image or Background Color in body background of your website ?',
            'multiOptions' => array(
                0 => 'Body Background Image',
                1 => 'Body Background Color'
            ),
            'onchange' => 'changeWebsiteImage(this.value);',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.theme.choose.website.image.color', 1),
        ));

        // Get available files
        $logoOptions = array('' => 'Text-only (No logo)');
        $imageExtensions = array('gif', 'jpg', 'jpeg', 'png');

        $it = new DirectoryIterator(APPLICATION_PATH . '/public/admin/');
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

        $this->addElement('Select', 'captivate_theme_website_body_background_image', array(
            'label' => 'Website\'s Body Background Image',
            'description' => 'Choose the Website\'s Body Background Image for your website. (You can upload a new file from: "Layout" > "File & Media Manager")',
            'multiOptions' => $logoOptions,
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.theme.website.body.background.image', 0),
        ));
        $this->addElement('Text', 'captivate_theme_background_color', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_themeBackgroundColor.tpl',
                        'class' => 'form element'
                    )))
        ));



        $this->addElement('Text', 'captivate_theme_containers_background_color', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_themeContainersBackgroundColor.tpl',
                        'class' => 'form element'
                    )))
        ));

        $this->addElement('Text', 'captivate_navigation_background_color', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_navigationBackgroundColor.tpl',
                        'class' => 'form element'
                    )))
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
