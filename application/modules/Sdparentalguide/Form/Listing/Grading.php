<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Form_Listing_Grading extends Engine_Form
{
  protected $_listingId = 0;
  protected $_item = null;
  public function setItem($item){
      $this->_item = $item;
  }
  public function init()
  {
    $this
      ->clearDecorators()
      ->addDecorator('FormElements')
      ->addDecorator('Form')
      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
      ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'))
      ;
    
    $item = $this->_item;
    if(empty($item)){
        return;
    }
    
    $listing_id = $item->getIdentity();

    $this
      ->setAttribs(array(
        'class' => 'global_form_box sd_listing_search',
      ))
      ->setMethod('GET');

    
    $mark_all = new Engine_Form_Element_Checkbox('mark_all');
    $mark_all
      ->setLabel('Select All')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field picture_quality'))
      ->setAttrib("id","mark_all".$listing_id)
      ->setAttrib("onchange","markAllGrading(this);");
    
    $picture_quality = new Engine_Form_Element_Checkbox('picture_quality');
    $picture_quality
      ->setLabel('Picture Quality')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field picture_quality'))
      ->setValue($item->gg_grading_picquality)
      ->setAttrib("id","picture_quality".$listing_id);
    
    $picture_quantity = new Engine_Form_Element_Checkbox('picture_quantity');
    $picture_quantity
      ->setLabel('Picture Quantity')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field picture_quantity'))
      ->setValue($item->gg_grading_picquantity)
      ->setAttrib("id","picture_quantity".$listing_id);
    
    $description = new Engine_Form_Element_Checkbox('description');
    $description
      ->setLabel('Description')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field description'))
      ->setValue($item->gg_grading_description)
      ->setAttrib("id","description".$listing_id);
    
    $spelling = new Engine_Form_Element_Checkbox('spelling');
    $spelling
      ->setLabel('Spelling & Grammar')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field spelling'))
      ->setValue($item->gg_grading_grammar)
      ->setAttrib("id","spelling".$listing_id);
    
    $categorization = new Engine_Form_Element_Checkbox('categorization');
    $categorization
      ->setLabel('Categorization')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field categorization'))
      ->setValue($item->gg_grading_categorization)
      ->setAttrib("id","categorization".$listing_id);
    
    $disclosure = new Engine_Form_Element_Checkbox('disclosure');
    $disclosure
      ->setLabel('Disclosure')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field disclosure'))
      ->setValue($item->gg_grading_safetyguidelines)
      ->setAttrib("id","disclosure".$listing_id);
    
    $safety = new Engine_Form_Element_Checkbox('safety');
    $safety
      ->setLabel('Safety Guidelines')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field safety'))
      ->setValue($item->gg_grading_safetyguidelines)
      ->setAttrib("id","safety".$listing_id);
    
    $this->addElements(array(
        $mark_all,
        $picture_quality,
        $picture_quantity,
        $description,
        $spelling,
        $categorization,
        $disclosure,
        $safety,        
    ));
    
    $complete = new Engine_Form_Element_Checkbox('complete');
    $complete
      ->setLabel('Grading Complete')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field complete'))
      ->setValue($item->gg_grading_complete)
      ->setAttrib("id","complete".$listing_id);
    
    $approved = new Engine_Form_Element_Checkbox('approved');
    $approved
      ->setLabel('Approved')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div','class' => 'sd_inline_field approved'))
      ->setValue($item->approved)
      ->setAttrib("id","approved".$listing_id);
    
    $this->addElements(array(
        $complete,
        $approved,     
    ));
    
    $this->addDisplayGroup(array('mark_all','picture_quality','picture_quantity','description','spelling','categorization','disclosure','safety','complete','approved'),'grp1');
    
    
    $this->addElement("TinyMce",'notes',array(
        'label' => 'Grading Notes',
        'id' => 'notes_'.$listing_id,
        'value' => $item->gg_graded_by_comments,
        'editorOptions' => array(
            'mode' => 'exact',
            'elements' => array('notes_'.$listing_id),
            'toolbar1' => array('undo', 'redo', 'removeformat', 'pastetext','fontselect', 'fontsizeselect', 'bold', 'italic', 'underline',
            'alignleft','aligncenter', 'alignright', 'alignjustify','bullist','numlist', 'outdent', 'indent', 'blockquote'),
            'toolbar2' => array(),
            'plugins' => array('table', 'fullscreen', 'preview', 'paste',
        'code', 'textcolor', 'link', 'lists', 'autosave',
        'colorpicker', 'imagetools', 'advlist', 'searchreplace', 'emoticons', 'codesample')
        )
    ));
    
    
//    $this->addDisplayGroup(array('notes','complete','approved'),'grp2');
    
    $this->addElement("Hidden",'listing_id',array('value' => $listing_id,'order' => 39932828));

    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }
}