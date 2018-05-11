<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminFieldsController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_AdminFieldsController extends Fields_Controller_AdminAbstract
{
  protected $_fieldType = 'sitepageoffer_offer';

  protected $_requireProfileType = false;

  public function indexAction()
  {
    // Make navigation
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitepageoffer_admin_main', array(), 'sitepageoffer_admin_main_fields');

    parent::indexAction();
  }

  public function fieldCreateAction(){
    parent::fieldCreateAction();

		//GET FORM
    $form = $this->view->form;

    if($form){
      //$form->setTitle('Add Document Question');

			$form->removeElement('show');
			$form->addElement('hidden', 'show', array('value' => 0));

      $display = $form->getElement('display');
      $display->setLabel('Show on pageoffer page?');
      $display->setOptions(array('multiOptions' => array(
          1 => 'Show on pageoffer page',
          0 => 'Hide on pageoffer page'
      )));

      $search = $form->getElement('search');
      $search->setLabel('Show on the search options?');
      $search->setOptions(array('multiOptions' => array(
          0 => 'Hide on the search options',
          1 => 'Show on the search options'
      )));
    }
  }

  public function fieldEditAction(){
    parent::fieldEditAction();
    
		//GET FORM
    $form = $this->view->form;

    if($form){
      $form->setTitle('Edit Pageoffer Question');

			$form->removeElement('show');
			$form->addElement('hidden', 'show', array('value' => 0));

      $display = $form->getElement('display');
      $display->setLabel('Show on pageoffer page?');
      $display->setOptions(array('multiOptions' => array(
          1 => 'Show on pageoffer page',
          0 => 'Hide on pageoffer page'
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