<?php
/**
 * SocialEngine
 *
 * @category   Engine
 * @package    Engine_Form
 * @author     Stars Developer
 */

class Engine_Form_Decorator_FormPGHTMLUpload extends Zend_Form_Decorator_Abstract
{
  public function render($content)
  {
    $data = $this->getElement()->getAttrib('data');
    if ($data) {
        $this->getElement()->setAttrib('data', null);
    }
    $element = $this->getElement();
    $view = $this->getElement()->getView();

    $view->headScript()->appendFile(
      $view->layout()->staticBaseUrl . 'externals/uploader/pg-uploader.js'
    );

    $view->headLink()->appendStylesheet(
      $view->layout()->staticBaseUrl . 'externals/uploader/pg-uploader.css'
    );

    $context = 'name="' . $element->getName() . '"';
    $context .= ' data-url="' . $element->url . '"';
    if (!empty($element->{'form'})) {
      $context .= ' data-form-id="' . $element->{'form'} . '"';
    }
    if (!empty($element->{'multi'})) {
      $context .= ' multiple="multiple"';
    }
    if (!empty($element->{'accept'})) {
      $context .= ' accept="' . $element->{'accept'} . '"';
    }

    return $view->partial('upload/upload.tpl', 'pgservicelayer', [
      'name' => $element->getName(),
      'data' => $data,
      'element' => $element,
      'context' => $context
    ]);
  }
}
