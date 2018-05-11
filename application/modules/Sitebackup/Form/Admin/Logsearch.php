<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: Logsearch.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
class Sitebackup_Form_Admin_Logsearch extends Engine_Form
{
  public function init()
  {
    $this->clearDecorators()
      ->addDecorator('FormElements')->addDecorator('Form')
      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search meta_search'));
    $this->setAttribs(array(
        'id' => 'filter_form',
        'class' => 'global_form_box',
      ))
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
      ->setMethod('GET')
    ;

    $type_prepared = array('0' => '', '1' => 'Database', '2' => 'File');
    $method_prepared = array('0' => '', '1' => 'Manual', '2' => 'Automatic');
    $status_prepared = array('0' => '', '1' => 'Success', '2' => 'Fail');

    $type = new Zend_Form_Element_Select('type', array(
      'label' => 'Backup Type',
      'multiOptions' => $type_prepared,
    ));

    $method = new Zend_Form_Element_Select('method', array(
      'label' => 'Backup Mode',
      'multiOptions' => $method_prepared,
    ));

    $status = new Zend_Form_Element_Select('status', array(
      'label' => 'Status',
      'multiOptions' => $status_prepared,
    ));

    $submit = new Zend_Form_Element_Button('submit', array(
      'type' => 'submit',
      'label' => 'View Log'
    ));

    $clear = new Zend_Form_Element_Button('clear', array(
      'label' => 'Clear Log',
      'type' => 'submit',
      'onclick' => 'emptyLog();return false;'
    ));

    $elements = array($type, $method, $status, $submit, $clear);
    foreach( $elements as $element ) {
      $element->clearDecorators()->addDecorator('ViewHelper')
        ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
        ->addDecorator('HtmlTag', array('tag' => 'div'));
    }
    $submit->removeDecorator('Label')->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'buttons sm_search_button'))
      ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear_button'));
    $clear->removeDecorator('Label')->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'buttons sm_search_button'))
      ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear_button'));

    $this->addElements($elements);

    $params = array();
    foreach( array_keys($this->getValues()) as $key ) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }

}
