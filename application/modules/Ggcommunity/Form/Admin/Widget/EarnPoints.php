<?php

/**
 * EXTFOX
 *
 * @package    Ggcommunity Earn Points Widget Form
 * @author     EXTFOX
 */
class Ggcommunity_Form_Admin_Widget_EarnPoints extends Engine_Form
{
  public function init()
  {
    $this->loadDefaultDecorators();
    $this->setAttrib('class', 'global_form_popup global_form global_form_ggcommunity_earnpoints_settings')
      ->setDisableTranslator(true);

    $this->addElement('Text', 'title', array(
        'label' => 'Title',
        'value' => 'How Can I earn points?',
    ));

    $this->addElement('Text', 'rule_one', array(
        'label' => 'Define Rule N1',
        'value' => 'Awarded a best Answer',
    ));
    $this->addElement('Text', 'points_rule_one', array(
        'label' => 'How many points for Rule N1',
        'value' => '10',
    ));
    $this->addElement('Text', 'rule_two', array(
        'label' => 'Define Rule N2',
        'value' => 'Answer question',
    ));
    $this->addElement('Text', 'points_rule_two', array(
        'label' => 'How many points for Rule N2',
        'value' => '2',
    ));
    $this->addElement('Text', 'rule_three', array(
        'label' => 'Define Rule N3',
        'value' => 'Choose a Best Answer',
    ));
    $this->addElement('Text', 'points_rule_three', array(
        'label' => 'How many points for Rule N3',
        'value' => '3',
    ));

    
  }

}