<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sdparentalguide_Form_Admin_Topic_Create extends Engine_Form
{
    public function init(){
        $this->setTitle("Add Topic");
        $this->setAttrib("class","global_form_popup");
        
        $this->addElement("Text",'name',array(
            'label' => 'Topic Name (Singular)',
            'placeholder' => 'Topic Name (Singular)',
            'allowEmpty' => false,
            'required' => true
        ));
        
        $this->addElement("Text",'name_plural',array(
            'label' => 'Topic Name (Plural)',
            'placeholder' => 'Topic Name (Plural)',
            'allowEmpty' => false,
            'required' => true
        ));
        
        $this->addElement("Text",'description',array(
            'label' => 'Topic Description',
            'placeholder' => 'Topic Description',
            'allowEmpty' => false,
            'required' => true
        ));
        
        $this->addElement("TinyMce",'body',array(
        'label' => 'Topic Body',
        'editorOptions' => array(
            'mode' => 'exact',
            'elements' => array('body'),
            'toolbar1' => array('undo', 'redo', 'removeformat', 'pastetext','fontselect', 'fontsizeselect', 'bold', 'italic', 'underline',
            'alignleft','aligncenter', 'alignright', 'alignjustify','bullist','numlist', 'outdent', 'indent', 'blockquote'),
            'toolbar2' => array(),
            'plugins' => array('table', 'fullscreen', 'preview', 'paste',
        'code', 'textcolor', 'link', 'lists', 'autosave',
        'colorpicker', 'imagetools', 'advlist', 'searchreplace', 'emoticons', 'codesample')
        )
    ));
        
        $this->addElement("Radio","approved",array(
            'label' => 'Status',
            'allowEmpty' => false,
            'required' => true,
            'multiOptions' => array(
                '1' => 'Active',
                '0' => 'Inactive'
            ),
            'value' => 1
        ));
        
        $this->addElement("Radio","badges",array(
            'label' => 'Badges',
            'allowEmpty' => false,
            'required' => true,
            'multiOptions' => array(
                '1' => 'Allow Badges',
                '0' => 'No Badges'
            ),
            'value' => 1
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