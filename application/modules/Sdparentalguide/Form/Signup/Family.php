<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Form_Signup_Family extends Engine_Form
{
    public function init(){
        $translate = Zend_Registry::get("Zend_Translate");
        $this->setTitle("Tell Us About Your Family");
        $this->setDescription($translate->translate("Sdparentalguide_Form_Signup_FAMILY_Description"));
        $this->setAttrib("id","SignupForm");
        $this->setAttrib("class","global_form sd-signup-interests");
        
        $this->addElement("MultiCheckbox","members",array(
            'label' => 'Family Members',
            'required' => true,
            'allowEmpty' => false,
            'decorators' => array(
                array('ViewScript', array(
                  'viewScript' => '_familyMembers.tpl',
                  'viewModule' => 'sdparentalguide',
                  'members' => $this->getFamilyMembers()
                ))
            ),
        ));
        
        $this->members->setRegisterInArrayValidator(false);
        
        $this->addElement('Hidden', 'nextStep', array(
            'order' => 333
        ));

        $this->addElement('Hidden', 'skip', array(
            'order' => 4444
        ));
        
        
        $this->addElement("Button","continue",array(
            'label' => "Continue",
            'type' => "submit",
            'ignore' => true,
            'decorators' => array(
                'ViewHelper',
            ),
        ));
        
        // Element: skip
        $this->addElement('Cancel', 'skip-link', array(
          'label' => 'skip',
          'prependText' => ' or ',
          'link' => true,
          'href' => 'javascript:void(0);',
          'onclick' => 'skipForm(); return false;',
          'decorators' => array(
            'ViewHelper',
          ),
        ));

        // DisplayGroup: buttons
        $this->addDisplayGroup(array('continue', 'skip-link'), 'buttons', array(

        ));
    }
    
    public function getFamilyMembers(){
        if(!Engine_Api::_()->core()->hasSubject()){
            return array();
        }
        $user = Engine_Api::_()->core()->getSubject();
        $table = Engine_Api::_()->getDbtable('familyMembers', 'sdparentalguide');
        return $table->fetchAll($table->select()->where('owner_id = ?',$user->getIdentity()));
    }
}