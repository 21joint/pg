<?php
class Engine_Form_Decorator_FormDayOfBirth extends Zend_Form_Decorator_Abstract
{
  public function render($content)
  {
    
    $data = $this->getElement()->getAttrib('data');
    if( $data ) {
      $this->getElement()->setAttrib('data', null);
    }

    $view = $this->getElement()->getView();
    return $view->partial('_birth-input.tpl', 'sdparentalguide', array(
      'name' => $this->getElement()->getName(),
      'data' => $data,
      'element' => $this->getElement()
    ));
  }
}
