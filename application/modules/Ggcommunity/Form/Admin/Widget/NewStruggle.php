<?php

/**
 * EXTFOX
 *
 * @package    Ggcommunity New Struggle Admin Widget Form
 * @author     EXTFOX
 */
class Ggcommunity_Form_Admin_Widget_NewStruggle extends Engine_Form
{
  public function init()
  {
    $this->loadDefaultDecorators();
    $this->setAttrib('class', 'global_form_popup global_form global_form_ggcommunity_newstruggle_settings')
      ->setDisableTranslator(true);

    $this->addElement('Text', 'title', array(
    'label' => 'Title',
    'value' => 'New Struggle',
    ));

    $this->addElement('Select', 'new_struggle', array(
      'label' => 'How do you want to look New Struggle link?',
      'multiOptions' => array(
        1 => 'Link',
        0 => 'Button'
      ),
      'value' => 1,
    ));
    
  }

}
