<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Form_Signup_Interests extends Engine_Form
{
    public function init(){
        $translate = Zend_Registry::get("Zend_Translate");
        $this->setTitle("Tell Us About Your Interests");
        $this->setDescription($translate->translate("Sdparentalguide_Form_Signup_Interests_Description"));
        $this->setAttrib("id","SignupForm");
        $this->setAttrib("class","global_form sd-signup-interests extfox-auth w-100");
        
        $this->addElement("MultiCheckbox","categories",array(
            'label' => 'Categories',
            'required' => true,
            'allowEmpty' => false,
            'decorators' => array(
                array('ViewScript', array(
                  'viewScript' => '_userInterests.tpl',
                  'viewModule' => 'sdparentalguide',
                  'listingTypes' => $this->getListingTypes(),
                  'savedCategories' => $this->getSavedPreferences()
                ))
            ),
            'validators' => array(
                array('NotEmpty', true),
            ),
        ));
        
        $this->categories->getValidator('NotEmpty')->setMessage('Please select categories from below.', 'isEmpty');
        $this->categories->setRegisterInArrayValidator(false);
        
        
        $this->addElement("Checkbox",'show_all',array(
            'label' => "Forget it - Show me Everything!",
            'onchange' => 'showAllCategories(this);',
        ));
        
        $this->addElement('Hidden', 'nextStep', array(
            'order' => 333
        ));

        $this->addElement('Hidden', 'skip', array(
            'order' => 4444
        ));
       
        
        // Element: skip
        $this->addElement('Cancel', 'skip-link', array(
          'label' => 'Skip',
          'class' => 'btn btn-outline-dark  py-2 text-uppercase  text-uppercase',
          'link' => true,
          'href' => 'javascript:void(0);',
          'onclick' => 'skipForm(); return false;',
          'decorators' => array(
            'ViewHelper',
          ),
        ));

         
        $this->addElement("Button","continue",array(
            'label' => "Continue",
            'class' => 'btn btn-success text-white py-2  text-uppercase  text-uppercase',
            'type' => "submit",
            'ignore' => true,
            'decorators' => array(
                'ViewHelper',
            ),
        ));
        

        // DisplayGroup: buttons
        $this->addDisplayGroup(array('continue', 'skip-link'), 'buttons', array(

        ));
    }
    
    public function getListingTypes(){
        $catTable = Engine_Api::_()->getDbTable("categories","sitereview");
        $catTableName = $catTable->info("name");
        $listingTypesTable = Engine_Api::_()->getDbTable("listingtypes","sitereview");
        $select = $listingTypesTable->select()
                ->where("visible = ?",1);
        return $listingTypesTable->fetchAll($select);
    }
    
    public static function getSavedPreferences(){
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity()){
            return array();
        }
        
        $prefTable = Engine_Api::_()->getDbTable("preferences","sdparentalguide");
        $preferences = $prefTable->fetchAll($prefTable->select()->where('user_id = ?',$viewer->getIdentity()));
        if(count($preferences) <= 0){
            return array();
        }
        $preferencesArray = array();
        foreach($preferences as $preference){
            $preferencesArray[] = $preference->category_id;
        }
        return $preferencesArray;
    }
}