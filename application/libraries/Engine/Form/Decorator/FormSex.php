<?php

class Engine_Form_Decorator_FormSex extends Zend_Form_Decorator_Abstract
{
  public function render($content)
  {

    $data = $this->getElement()->getAttrib('data');
    if ($data) {
      $this->getElement()->setAttrib('data', null);
    }

    $view = $this->getElement()->getView();
    return $view->partial('_sex-input.tpl', 'sdparentalguide', array(
      'name' => $this->getElement()->getName(),
      'name_hidden' => 'sex',
      'data' => $data,
      'element' => $this->getElement()
    ));
  }
}
