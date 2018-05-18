<?php

/**
 * EXTFOX
 *
 * @package    Ggcommunity Draft Question Admin Widget Form
 * @author     EXTFOX
 */
class Ggcommunity_Form_Admin_Widget_DraftQuestion extends Engine_Form
{
  public function init()
  {
    $this->loadDefaultDecorators();
    $this->setAttrib('class', 'global_form_popup global_form global_form_ggcommunity_draftquestion_settings')
      ->setDisableTranslator(true);

    $this->addElement('Text', 'title', array(
    'label' => 'Title',
    'value' => 'Draft',
    ));

    $this->addElement('Text', 'content', array(
      'label' => 'Content of your widget',   
      'value' => 'You arre currently in <b>draft</b> mode. Click on the button bellow to publish.',
      'filters' => array(
        new Engine_Filter_Censor(),
        new Engine_Filter_Html(array('AllowedTags' => 'b'))
      ),
    ));
    
  }

}
