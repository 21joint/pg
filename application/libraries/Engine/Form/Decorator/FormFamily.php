<?php
class Engine_Form_Decorator_FormFamily extends Zend_Form_Decorator_Abstract
{
  public function render($content)
  {
    
    $data = $this->getElement()->getAttrib('data');
    if( $data ) {
      $this->getElement()->setAttrib('data', null);
    }

    $view = $this->getElement()->getView();
    return $view->partial('_family-input.tpl', 'sdparentalguide', array(
      'name' => $this->getElement()->getName(),
      'name_hidden' => 'family',
      'data' => $data,
      'element' => $this->getElement()
    ));
  }
}
