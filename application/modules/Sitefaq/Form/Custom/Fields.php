<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Fields.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Form_Custom_Fields extends Fields_Form_Standard
{
  public $_error = array();

	protected $_name = 'fields';

  protected $_elementsBelongTo = 'fields';

  public function init()
  { 
    // custom sitefaq fields
    if( !$this->_item ) {
      $sitefaq_item = new Sitefaq_Model_Faq(array());
      $this->setItem($sitefaq_item);
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