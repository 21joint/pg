<?php

/**
 * EXTFOX
 *
 * @package    Ggcommunity Top Theorist Admin Widget Form
 * @author     EXTFOX
 */
class Ggcommunity_Form_Admin_Widget_TopTheorist extends Engine_Form
{
  public function init()
  {
    $this->loadDefaultDecorators();
    $this->setAttrib('class', 'global_form_popup global_form global_form_ggcommunity_toptheorist_settings')
      ->setDisableTranslator(true);

    $this->addElement('Text', 'title', array(
    'label' => 'Title',
    'value' => 'Top Theorist',
    ));

    $this->addElement('Select', 'more', array(
      'label' => 'Where do you want to show view more button?',
      'multiOptions' => array(
        1 => 'On Top',
        0 => 'On Bottom'
      ),
      'value' => 0,
    ));
    
  }

}
