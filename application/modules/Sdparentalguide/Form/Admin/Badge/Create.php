<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Form_Admin_Badge_Create extends Engine_Form
{
    public function init(){
        $this->setTitle("Add Badge");
        $this->setAttrib("class","global_form_popup");
        
        $this->addElement("Text",'name',array(
            'label' => 'Badge Name',
            'allowEmpty' => false,
            'required' => true
        ));
        
//        $listingtypes = Engine_Api::_()->getDbTable("listingtypes","sitereview")->getListingTypesArray();
//        $listingtypes['0'] = 'All';
//        ksort($listingtypes);
//        $this->addElement("Select",'listingtype_id',array(
//            'label' => 'Category',
//            'required' => true,
//            'allowEmpty' => false,
//            'multiOptions' => $listingtypes
//        ));
        
        $this->addElement("Text",'topic',array(
            'label' => 'Topic Name',
            'allowEmpty' => false,
            'required' => true
        ));
        
        $this->addElement("Textarea",'description',array(
            'label' => 'Description',
            'allowEmpty' => false,
            'required' => true
        ));
        
        $this->addElement("Select",'type',array(
            'label' => 'Badge Type',
            'required' => true,
            'allowEmpty' => false,
            'multiOptions' => Engine_Api::_()->sdparentalguide()->getBadgeTypes()
        ));
        
//        $this->addElement("Select",'level',array(
//            'label' => 'Level',
//            'required' => true,
//            'allowEmpty' => false,
//            'multiOptions' => Engine_Api::_()->sdparentalguide()->getBadgeLevels()
//        ));
        
        $this->addElement("Radio",'active',array(
            'label' => 'Status',
            'multiOptions' => array(
                '1' => 'Active',
                '0' => 'Inactive'
            ),
            'value' => 1
        ));
        
        $this->addElement("Radio",'profile_display',array(
            'label' => 'Displayed On Profile',
            'multiOptions' => array(
                '1' => 'Yes',
                '0' => 'No'
            ),
            'value' => 1
        ));
        
        $this->addElement("File",'photo',array(
            'label' => 'Large Icon',
            'description' => 'Uploaded image must be square.',
            'required' => true,
            'allowEmpty' => false,
            'onchange' => 'previewBadgeIcon(this);',
            'destination' => APPLICATION_PATH.'/public/temporary/',
            'validators' => array(
                array('Count', false, 1),
                array('Extension', false, 'jpg,png,gif,jpeg'),
            ),
        ));
        $this->photo->getDecorator('Description')->setOptions(array('placement' => 'APPEND'));
        
        $dimentionValidator = new Engine_Validate_Callback(array($this, 'checkDimentions'), $this->photo);
        $dimentionValidator->setMessage("Please upload square image.");
        $this->photo->addValidator($dimentionValidator);
        
//        $this->addElement("File",'small_icon',array(
//            'label' => 'Small Icon',
//            'required' => true,
//            'allowEmpty' => false,
//            'onchange' => 'previewBadgeIcon(this);'
//        ));
        
        $this->addElement('Hidden', 'topic_id', array(
            'label' => "Topic",
            'order' => 10005,
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(
              array('NotEmpty', true),
            ),
        ));
        
        $this->topic_id->getValidator('NotEmpty')->setMessage('Please select a valid topic.', 'isEmpty');
        
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
    
    public function checkDimentions($file){
        return true;
        $valid = false;
        try{
            if(empty($file)){
                return true;
            }
            $image = Engine_Image::factory();
            $image->open($file);
            if($image->height == $image->width){
                $valid = true;
            }
        } catch (Exception $ex) {
            $valid = false;
        }
        return $valid;
    }
}