<?php
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminFieldsController.php 6590 2010-09-01 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_AdminFieldsController extends Fields_Controller_AdminAbstract
{
  protected $_fieldType = 'feedback';

  protected $_requireProfileType = false;

	//ACTION FOR MANAGING CUSTOM FIELDS
  public function indexAction()
  {
		//GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('feedback_admin_main', array(), 'feedback_admin_main_fields');

    parent::indexAction();
  }

	//ACTION FOR CREATING NEW FIELDS
  public function fieldCreateAction(){

		include_once(APPLICATION_PATH ."/application/modules/Feedback/controllers/license/license2.php");

		//FORM GENERATION
    $form = $this->view->form;

    if($form){
      $form->setTitle('Add Feedback Question');

      $display = $form->getElement('display');
      $display->setLabel('Show on feedback page?');
      $display->setOptions(array('multiOptions' => array(
          1 => 'Show on feedback page',
          0 => 'Hide on feedback page'
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
      $form->setTitle('Edit Feedback Question');

      $display = $form->getElement('display');
      $display->setLabel('Show on feedback page?');
      $display->setOptions(array('multiOptions' => array(
          1 => 'Show on feedback page',
          0 => 'Hide on feedback page'
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
