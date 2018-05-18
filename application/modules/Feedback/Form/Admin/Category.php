<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Category.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Form_Admin_Category extends Engine_Form
{
  protected $_field;

  public function init()
  {
			$this
			->setMethod('post')
			->setAttrib('class', 'global_form_box')
			;

			$label = new Zend_Form_Element_Text('label');
			$label->setLabel('Category Name')
				->addValidator('NotEmpty')
				->setRequired(true)
				->setAttrib('class', 'text');

			$id = new Zend_Form_Element_Hidden('id');

			$this->addElements(array(
				$label,
				$id
			));
			
			$this->addElement('Button', 'submit', array(
				'label' => 'Add Category',
				'type' => 'submit',
				'ignore' => true,
				'decorators' => array('ViewHelper')
			));

			$this->addElement('Cancel', 'cancel', array(
				'label' => 'cancel',
				'link' => true,
				'prependText' => ' or ',
				'href' => '',
				'onClick'=> 'javascript:parent.Smoothbox.close();',
				'decorators' => array(
					'ViewHelper'
				)
			));
			$this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
			$button_group = $this->getDisplayGroup('buttons');
	}

  public function setField($category)
  {
    $this->_field = $category;

    $this->label->setValue($category->category_name);
    $this->id->setValue($category->category_id);
    $this->submit->setLabel('Edit Category');

 	}
}
