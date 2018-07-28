<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Create Question
 */
class Ggcommunity_Form_Question_Create extends Engine_Form
{
  
  public function init()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $can_change = Engine_Api::_()->authorization()->isAllowed('ggcommunity', null, 'edit_close_date');

    $this->setAttrib('id', 'global_form_front') // id should include spaces 'global_form_front extfox_create'
      ->setAttrib('class','extfox_form')
    ;
         
    /* Question type */
    $this->addElement('Textarea', 'title', array(
      'Label' => 'Title',
      'allowEmpty' => false,
      'required' => true,
      'rows' => '1',
      'cols' => '45',
      'class' => 'm-b-10',
      'filters' => array(
        new Engine_Filter_Censor(),
        'StripTags',
        new Engine_Filter_StringLength(array('max' => '120')),
      ),
      'placeholder' => "",
      'autofocus' => 'autofocus',
    ));
    $this->title->getDecorator("Description")->setOption("placement", "append");

    // init to topic(use same logic as tags)
    $this->addElement('Text', 'tags', array(
    'label'=> 'SDPARENTALGUIDE_TOPIC',
    'autocomplete' => 'off',
    'allowEmpty' => false,
    'required' => true,
    'placeholder' => "Start typing to find the topic the best fits your struggle” instead of",
    'description' => "Topics are used to match you with the best experts in those areas",
    'filters' => array(
      'StripTags',
      new Engine_Filter_Censor(),
    ),
  ));
  $this->tags->getDecorator("Description")->setOption("placement", "append");
  $this->addElement("Hidden",'topic_id',array(
      'order' => 033393,
  ));
        
  //Create Tinymce textarea(with this way you allow using default textareas on the same page)
  $this->addElement('TinyMce', 'body', array(
    'disableLoadDefaultDecorators' => true,
    'class'=>'mceEditor',
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

  $this->addElement('PGHTMLUpload', 'Filedata', array(
    'label' => 'Include Photo',
    'form' => '#form-upload',
    'url' => $this->getView()->baseUrl()."/api/v1/photo",
    'accept' => 'image/*',
  ));
  $this->addElement("Hidden",'photo_id',array(
      'order' => 55998,
  ));

  if($can_change == 1) {
    // $this->addElement('CalendarDateTime', 'date_closed', array(
    //   'label' => 'Close Date',
    //   'allowEmpty' => true,
    //   'required' => false,
    //   'order' => 7
    // ));

    $this->addElement('Date', 'date_closed', array(
      'label' => 'Close Date',
      'format' => 'Y-m-d\TH:iP',
      'class' => 'required datepicker-date',
      'allowEmpty' => true,
      'required' => false,
      'order' => 7
    ));

  }
    
  // Element: submit
  $this->addElement('Button', 'submit', array(
    'label' => 'Submit',
    'type' => 'submit',
    'data-toggle' => 'tooltip',
    'class' => 'btn primary large '
  ));

  // element which keep infromation is draft button is clicked(1) or not(0)
  $this->addElement('Hidden', 'draft', array(
    'label' => 'Save',
    'required' => false,
    'allowEmpty' =>  true,
    'value' => 0,
  ));

  // hidden element approved, if has approved date that approved is set to 1 
  $this->addElement('Hidden', 'approved', array(
    'label' => 'Approved',
    'required' => false,
    'allowEmpty' =>  true,
    'order'=> 1
  ));

  // hidden element approved_date, if this memeber level can automatically approve his own question
  $this->addElement('Hidden', 'approved_date', array(
    'label' => 'Approved Date',
    'required' => false,
    'allowEmpty' =>  true,
    'order' => 2
  ));

  // hidden element open, if is saved as draft this must be 0
  $this->addElement('Hidden', 'open', array(
    'label' => 'Open',
    'required' => false,
    'allowEmpty' =>  true,
    'order' => 3
  ));

  // hidden element search, if is saved as draft this must be 0
  $this->addElement('Hidden', 'search', array(
    'label' => 'Search',
    'required' => false,
    'allowEmpty' =>  true,
    'order' => 4
  ));
  
  // Element save as draft
  $this->addElement('Button', 'submit_draft', array(
    'label' => 'Save as Draft',
    'type' => 'submit',
    'class' => 'btn ghost large',
  ));

  $this->addDisplayGroup( array('submit','submit_draft', 'draft' ), 'buttons', array(
    'decorators' => array(
      'FormElements',
      'DivDivDivWrapper',
    ),
  ));
    
  }

  
}
