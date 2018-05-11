<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminFieldsController.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_AdminFieldsController extends Fields_Controller_AdminAbstract
{
  protected $_fieldType = 'sitefaq_faq';

  protected $_requireProfileType = false;

	//ACTION FOR MANAGING CUSTOM FIELDS
  public function indexAction()
  {
		//GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitefaq_admin_main', array(), 'sitefaq_admin_main_fields');

    parent::indexAction();
  }

	//ACTION FOR CREATING NEW FIELDS
  public function fieldCreateAction(){

	include APPLICATION_PATH . '/application/modules/Sitefaq/controllers/license/license2.php';

		//FORM GENERATION
    $form = $this->view->form;

    if($form){
			
			$form->removeElement('show');
			$form->addElement('hidden', 'show', array('value' => 0));			
			
      $form->setTitle('Add FAQs Question');

      $display = $form->getElement('display');
      $display->setLabel('Show on FAQs page?');
      $display->setOptions(array('multiOptions' => array(
          1 => 'Show on FAQs page',
          0 => 'Hide on FAQs page'
        )));

      $search = $form->getElement('search');
      $search->setLabel('Show on the search options?');
      $search->setOptions(array('multiOptions' => array(
          0 => 'Hide on the search options',
          1 => 'Show on the search options'
        )));
    }
  }

	//ACTION FOR EDIT CREATED FIELDS
  public function fieldEditAction(){

    parent::fieldEditAction();

    //FORM GENERATION
    $form = $this->view->form;

    if($form){
			
			$form->removeElement('show');
			$form->addElement('hidden', 'show', array('value' => 0));
			
      $form->setTitle('Edit FAQ Question');

      $display = $form->getElement('display');
      $display->setLabel('Show on FAQs page?');
      $display->setOptions(array('multiOptions' => array(
          1 => 'Show on FAQs page',
          0 => 'Hide on FAQs page'
        )));

      $search = $form->getElement('search');
      $search->setLabel('Show on the search options?');
      $search->setOptions(array('multiOptions' => array(
          0 => 'Hide on the search options',
          1 => 'Show on the search options'
        )));
    }
  }

}
