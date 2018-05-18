<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Form_Admin_Edit extends Engine_Form {

    public function init() {

        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $this->setTitle('Edit')
             ->setAttrib('class', 'global_form_popup');

        $this->addElement('Text', 'label', array(
            'label' => 'Label',
            'required' => true,
            'allowEmpty' => false,
        ));
        $this->addElement('Radio', 'is_submenu', array(
            'label' => 'Do you want to use this menu item as a sub menu item?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'onchange' => 'onSubMenuChange();',
            'value' => '0',
        ));

       $this->addElement('Select', 'parent_id', array(
            'label' => 'Choose parent tab',
            'multiOptions' => array(
            ),
        ));
        $this->addElement('Button', 'submit', array(
            'label' => 'Save',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => '',
            'onclick' => 'parent.Smoothbox.close();',
            'decorators' => array('ViewHelper')
        ));
        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    }

}

