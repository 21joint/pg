<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Searchmostvoted.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Form_Searchmostvoted extends Engine_Form
{
  public function init()
  {
    $this
    ->setAttribs(array(
      'id' => 'filter_form_mostvoted',
      'class' => 'global_form_box_mostvoted',
      'target' => '_parent'
    ))
    ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module'=>'feedback', 'controller'=>'index', 'action'=>'browse'), 'default'));

    $this->addElement('Hidden', 'orderby_mostvoted', array(
      'order' => 'total_votes'
    ));
  }
}
