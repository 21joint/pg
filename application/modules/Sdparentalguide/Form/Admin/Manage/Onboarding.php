<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Form_Admin_Manage_Onboarding extends Engine_Form
{
    public function init(){
        $this->setTitle('Settings Onboarding');
        
        $order = 30;        
        $this->addElement('Radio', 'enable', array(
            'label' => 'Use Default SocialEngine Onboarding',
            'order' => $order++,
            'multiOptions' => array(
                '1' => 'Yes',
                '0' => 'No'
            ),
            'value' => Engine_Api::_()->getDbTable("settings","sdparentalguide")->getSetting('gg.use.default.onboarding',1)
        ));
        
        
        
        // Add submit button
        $this->addElement('Button', 'save', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true,
            'order' => $order++,
        ));
    }
}
