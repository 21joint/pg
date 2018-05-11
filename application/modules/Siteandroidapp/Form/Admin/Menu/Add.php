<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteandroidapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Add.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteandroidapp_Form_Admin_Menu_Add extends Engine_Form {

    public function init() {
        $this->addElement('Select', 'type', array(
            'label' => 'Type',
            'multiOptions' => array(
                'menu' => 'Menu',
                'category' => 'Category'
            ),
            'onchange' => 'manageTypeFormElements();',
            'value' => 'menu',
            'order' => 799
        ));

        $this->addElement('Text', 'dashboard_label', array(
            'label' => 'Dashboard Title',
            'required' => true,
            'order' => 800,
        ));

        $this->addElement('Text', 'header_label', array(
            'label' => 'Header Title',
            'order' => 802,
        ));

        $this->addElement('Text', 'url', array(
            'label' => 'URL',
            'description' => "Clicking on this menu will open the below URL in the app. Try to put the URL of a responsive webpage here for a seamless user experience.",
            'order' => 803,
        ));

        $this->addElement('Text', 'icon', array(
            'label' => 'Icon',
            'description' => 'Please add the "Unicode" of the font-icon that will get displayed beside this menu in your dashboard. You may get the Unicode of a desired icon from the below URL.<br />  <a href="http://fortawesome.github.io/Font-Awesome/icons" target="_blank">http://fortawesome.github.io/Font-Awesome/icons</a>',
            'order' => 804,
        ));
        $this->icon->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
        
        $this->addElement('Text', 'siteandroidapp_menucolor', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => '_formImagerainbowSponsred.tpl',
                        'class' => 'form element'
                    ))),
             'order' => 806,
        ));

//        $this->addElement('Text', 'siteandroidapp_menucolor', array(
//            'label' => 'Menu Color',
//            'description' => 'Select the color of the "MENU" labels. (Click on the rainbow below to choose your color.)',
//             'decorators' => array(array('ViewScript', array(
//                        'viewScript' => '_formImagerainbowSponsred.tpl',
//                        'class' => 'form element'
//                    ))),
//            'order' => 806,
//        ));
        //$this->siteandroidapp_menucolor->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
        
        $this->addElement('Select', 'show', array(
            'label' => 'Visible to Users',
            'multiOptions' => array(
                'both' => 'Both Logged-in & Logged-out',
                'login' => 'Only Logged-in',
                'logout' => 'Only Logged-out'
            ),
            'order' => 807,
        ));

        $this->addElement('Checkbox', 'status', array(
            'label' => 'Enable',
            'value' => 1,
            'order' => 808
        ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Save',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper'),
            'order' => 809,
        ));

        // Element: cancel
        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'prependText' => ' or ',
            'ignore' => true,
            'link' => true,
            'onclick' => 'parent.Smoothbox.close();',
//            'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage')),
            'decorators' => array('ViewHelper'),
            'order' => 810,
        ));

        // DisplayGroup: buttons
        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
            'decorators' => array(
                'FormElements',
                'DivDivDivWrapper',
            ),
            'order' => 811,
        ));
    }

}
