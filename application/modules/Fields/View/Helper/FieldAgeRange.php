<?php
/**
 * EXTFOX
*/
class Fields_View_Helper_FieldAgeRange extends Fields_View_Helper_FieldAbstract
{
  public function fieldAgeRange($subject, $field, $value)
  {
    
    $age = $value->value;
    if($age == 18)
      $age = $this->view->translate('Under 18');
    else if ($age == 65)
      $age = $this->view->translate('65+');
    return $age;

  }
}