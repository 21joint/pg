<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Customization.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteluminous_Form_Admin_Customization extends Engine_Form {
  
  public function init() {

    $this->setTitle(sprintf(Zend_Registry::get('Zend_Translate')->_("Theme Customization")));

    $this->addElement('Radio', 'theme_customization', array(
        'label' => 'Select Theme Color',        
        'multiOptions' => array(
            0 => 'DEFAULT',
            1 => 'LIGHTORANGE',
            2 => 'LIGHTPINK',
            4 => 'LIGHTPURPLE',
            5 => 'LIGHTYELLOW',
            6 => 'LEAFGREEN',
            7 => 'FADEBLUE',
            8 => 'SEAGREEN',
            9 => 'PURPLE',
            10 => 'GREEN',
            11 => 'RED',
            12 => 'DARKBLUE',
            13 => 'MAGENTA',
            14 => 'YELLOW',
            15 => 'DARKPINK',
            3 => 'Custom Colors (Choosing this option will enable you to customize your theme according to your site.)'
        ),
        'onchange' => 'changeThemeCustomization();',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('theme.customization', 0),
    ));
    

    $this->addElement('Text', 'siteluminous_theme_color', array(
        'decorators' => array(array('ViewScript', array(
                    'viewScript' => '_themeColor.tpl',
                    'class' => 'form element'
            )))
    ));
    
    $this->addElement('Text', 'siteluminous_theme_button_border_color', array(
        'decorators' => array(array('ViewScript', array(
                    'viewScript' => '_themeButtonBorderColor.tpl',
                    'class' => 'form element'
            )))
    ));
    
    $this->addElement('Text', 'siteluminous_landingpage_signinbtn', array(
        'decorators' => array(array('ViewScript', array(
                    'viewScript' => '_themeLandingPageSigninButtonColor.tpl',
                    'class' => 'form element'
            )))
    ));
    
    $this->addElement('Text', 'siteluminous_landingpage_signupbtn', array(
        'decorators' => array(array('ViewScript', array(
                    'viewScript' => '_themeLandingPageSignupButtonColor.tpl',
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