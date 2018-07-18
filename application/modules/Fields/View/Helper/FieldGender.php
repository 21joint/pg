<?php
/**
 * EXTFOX
*/
class Fields_View_Helper_FieldGender extends Fields_View_Helper_FieldAbstract
{
  public function fieldGender($subject, $field, $value)
  {
    
    $gender = $value->value;
    if($gender == 1)
      $gender = $this->view->translate('Male');
    else if($gender ==2)
      $gender = $this->view->translate('Female');
    else
      $gender = $this->view->translate('Unknown');
    return $gender;

  }
}