<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Form_Admin_Search_Create extends Engine_Form
{
    public function init(){
        $this->setTitle("New Search Term");
        $this->setAttrib("class","global_form_popup");
        
        $this->addElement("Text",'name',array(
            'label' => 'Search Term',
            'placeholder' => 'Search Term',
            'allowEmpty' => false,
            'required' => true
        ));
        
       
        $this->addElement('Button', 'save', array(
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
            'onClick' => 'javascript:parent.Smoothbox.close();',
            'decorators' => array(
                'ViewHelper'
            )
        ));
        $this->addDisplayGroup(array('save', 'cancel'), 'buttons');
    }
    
}