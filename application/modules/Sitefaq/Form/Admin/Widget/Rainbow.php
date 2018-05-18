<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Rainbow.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitefaq_Form_Admin_Widget_Rainbow extends Engine_Form
{
  public function init()
  {

		$this->addElement('Text', 'featured_color', array(
			'decorators' => array(array('ViewScript', array(
				'viewScript' => 'application/modules/Sitefaq/views/scripts/_formImagerainbow.tpl',
				'class'      => 'form element'
			)))
		));

	}

}