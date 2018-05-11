<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Create Answer
 */
class Ggcommunity_Form_Answer_Create extends Engine_Form
{
  
    public function init() {
        $this->setAttrib('id', 'create-answer-form')
            ->setAttrib('class','global_form_front')
            
        ;
        $viewer = Engine_Api::_()->user()->getViewer();
        $permissions = Engine_Api::_()->ggcommunity()->getPermission($viewer);
       
        //Create Tinymce textarea(with this way you allow using default textareas on the same page)
        $this->addElement('TinyMce', 'body_create', array(
            'disableLoadDefaultDecorators' => true,
            'class'=>'mceEditor createAnswerEditor',
            'required' => true,
            'allowEmpty' => false,
            'label'=> 'Description',
            'decorators' => array(
                'ViewHelper',
            ),
            'editorOptions' => array(
                'toolbar1' => array('|' , 'bold', 'italic','underline','|', 'alignleft', 'aligncenter', 'alignright', 'alignjustify', '|', 'blockquote'), 
                'toolbar2' => array(),
                'tollbar3' => array(),
                'editor_selector' => 'mceEditor'
            ),
            'filters' => array(
                new Engine_Filter_Censor(),
                new Engine_Filter_Html(array('AllowedTags' => 'strong, em, br, span, p, blockquote, emoticons, b'),
                array('AllowedAttributes' => 'alt, name, value'))
            ),
        ));

       /*  $this->addElement('File', 'answer_photo', array(
            'label' => 'Include Photo'
        ));
        $this->answer_photo->addValidator('Extension', false, 'jpg,png,gif,jpeg'); */

      
        if($permissions['answer_question'] == 0) {
            // Element: submit
            $this->addElement('Button', 'submit', array(
                'label' => 'Submit',
                'type' => 'submit',
                'class' => 'submit-comment btn large disabled',
                'disabled' => "disabled",
                'decorators' => array('ViewHelper'),
            ));
        } else {
            // Element: submit
            $this->addElement('Button', 'submit', array(
                'label' => 'Submit',
                'type' => 'submit',
                'class' => 'btn large primary active',
                'decorators' => array('ViewHelper'),
            ));
        }
        
        $this->addElement('Cancel', 'cancel', array(
            'label' => 'Cancel',
            'link' => true,
            'class' => 'btn large ghost blue',
            'href' => '',
            'onclick' => 'en4.ggcommunity.answer.cancel();',
            'decorators' => array(
              'ViewHelper'
            )
        ));
        $this->addDisplayGroup(array('cancel','submit'), 'buttons');
        $button_group = $this->getDisplayGroup('buttons');
        
       
    }
  
}