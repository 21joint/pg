<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Edit Answer
 */

class Ggcommunity_Form_Answer_Edit extends Engine_Form
{
  public function init()
  {
    
    $this
      ->setMethod('POST')
      ->setAttrib('class', 'global_form_edit_item')
    ;

    // $this->addElement('Text', 'body', array(
    //   'filters' => array(
    //     new Engine_Filter_Censor(),
    //     new Engine_Filter_Html(array('AllowedTags' => 'style,strong,em,span,p,img'),
    //     array('AllowedAttributes' => 'href, src, alt, name, value')),
    //   ),
    // ));
    $uploadUrl = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module'=>'ggcommunity', 'controller'=>'answer-index','action' => 'upload-photo'), 'default', true);
    //Create Tinymce textarea(with this way you allow using default textareas on the same page)
    $this->addElement('TinyMce', 'body', array(
      'disableLoadDefaultDecorators' => true,
      'class'=>'mceEditor',
      'required' => true,
      'allowEmpty' => false,
      'label'=>'Description',
      
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

    $this->addElement('hidden', 'action_id');

    // Buttons
    $this->addElement('Button', 'submit', array(
      'label' => 'Edit',
      'type' => 'submit',
      'class' => 'btn small',
      'ignore' => true,
      'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'Cancel',
      'link' => true,
      'class' => 'feed-edit-content-cancel',
      'style' => 'margin-left: .5rem; font-size: 12px; color: #93A4A6;',
      'href' => 'javascript:void(0);',
      'decorators' => array(
        'ViewHelper'
      )
    ));

    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
  }
}
