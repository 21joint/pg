<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Form_Family_Add extends Engine_Form
{
    public function init(){
        $translate = Zend_Registry::get("Zend_Translate");
        $this->setTitle("Family Member");
        $this->setAttrib("class","global_form_popup");
        
        $this->addElement("Select","relationship",array(
            'label' => "Relationship",
            'required' => true,
            'allowEmpty' => false,
            'multiOptions' => Engine_Api::_()->getDbTable("relationships","sdparentalguide")->getMultiOptions()
        ));
        
        $this->addElement("Select","gender",array(
            'label' => "Gender",
            'required' => true,
            'allowEmpty' => false,
            'multiOptions' => array(
                '' => '',
                '1' => 'Male',
                '2' => 'Female',
                '3' => 'Prefer Not to Answer'
            )
        ));
        
        $this->addElement("CalendarDateTime","birthdate",array(
            'label' => "Birthdate",
            'required' => true,
            'allowEmpty' => false,
        ));
        
        $this->addElement('Button', 'cancel', array(
          'label' => 'Cancel',
          'type' => "button",
          'ignore' => true,
          'onclick' => 'window.parent.Smoothbox.close();',
          'decorators' => array(
              'ViewHelper',
          ),
        ));
        
        $this->addElement("Button","continue",array(
            'label' => "Save & Close",
            'type' => "submit",
            'ignore' => true,
            'decorators' => array(
                'ViewHelper',
            ),
        ));
        
        $this->addElement('Button', 'remove', array(
          'label' => 'Remove',
          'type' => "button",
          'ignore' => true,
          'onclick' => 'deleteMember(this);',
          'decorators' => array(
              'ViewHelper',
          ),
        ));

        // DisplayGroup: buttons
        $this->addDisplayGroup(array('cancel','continue', 'remove'), 'buttons', array(

        ));
    }
}