<?php
class Engine_Form_Element_DayOfBirth extends Zend_Form_Element_Hidden
{
  public function getValue()
  {
    return explode(" ", trim($this->_value));
  }
  
  public function render(Zend_View_Interface $view = null)
  {
      if (null !== $view) {
          $this->setView($view);
      }

      $content = '';
      foreach ($this->getDecorators() as $decorator) {
            $decorator->setElement($this);
            $content = $decorator->render($content);
      }

      return $content;
  }
  
  /**
   * Load default decorators
   *
   * @return void
   */
  public function loadDefaultDecorators()
  {
    if( $this->loadDefaultDecoratorsIsDisabled() )
    {
      return;
    }

    $decorators = $this->getDecorators();
    if( empty($decorators) )
    {
      $this->addDecorator('FormDayOfBirth');
      Engine_Form::addDefaultDecorators($this);
    }
  }
}
