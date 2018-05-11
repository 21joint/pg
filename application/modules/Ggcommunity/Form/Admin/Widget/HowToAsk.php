<?php

/**
 * EXTFOX
 *
 * @package    Ggcommunity How to Ask Admin Widget Form
 * @author     EXTFOX
 */
class Ggcommunity_Form_Admin_Widget_HowToAsk extends Engine_Form
{
  public function init()
  {
    $this->loadDefaultDecorators();
    $this->setAttrib('class', 'global_form_popup global_form global_form_ggcommunity_howtoask_settings')
      ->setDisableTranslator(true);

    $this->addElement('Text', 'title', array(
        'label' => 'Title',
    ));

    $this->addElement('Text', 'description', array(
        'label' => 'Description',
    ));

    $this->addElement('Tinymce', 'body', array(
        'label' => 'Body',
    ));
    
  }

}