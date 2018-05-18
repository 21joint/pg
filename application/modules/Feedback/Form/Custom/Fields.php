<?php
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Fields.php 6590 2010-09-01 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Form_Custom_Fields extends Fields_Form_Standard
{
  public $_error = array();

	protected $_name = 'fields';

  protected $_elementsBelongTo = 'fields';

  public function init()
  { 
    // custom feedback fields
    if( !$this->_item ) {
      $feedback_item = new Feedback_Model_Feedback(array());
      $this->setItem($feedback_item);
    }
    parent::init();

    $this->removeElement('submit');
  }

  public function loadDefaultDecorators()
  {
    if( $this->loadDefaultDecoratorsIsDisabled() )
    {
      return;
    }

    $decorators = $this->getDecorators();
    if( empty($decorators) )
    {
      $this
        ->addDecorator('FormElements')
        ; //->addDecorator($decorator);
    }
  }
}