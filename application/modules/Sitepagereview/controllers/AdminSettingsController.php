<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_AdminSettingsController extends Core_Controller_Action_Admin {

    public function __call($method, $params) {
        /*
         * YOU MAY DISPLAY ANY ERROR MESSAGE USING FORM OBJECT.
         * YOU MAY EXECUTE ANY SCRIPT, WHICH YOU WANT TO EXECUTE ON FORM SUBMIT.
         * REMEMBER:
         *    RETURN TRUE: IF YOU DO NOT WANT TO STOP EXECUTION.
         *    RETURN FALSE: IF YOU WANT TO STOP EXECUTION.
         */
        if (!empty($method) && $method == 'Sitepagereview_Form_Admin_Settings_Global') {

        }
        return true;
    }
    
  //ACTION FOR GLOBAL SETTINGS
  public function indexAction() {
		if( $this->getRequest()->isPost() ) {
			$sitepageKeyVeri = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.lsettings', null);
			if( !empty($sitepageKeyVeri) ) {
				Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepage.lsettings', trim($sitepageKeyVeri));
			}
			if( $_POST['sitepagereview_lsettings'] ) {
				$_POST['sitepagereview_lsettings'] = trim($_POST['sitepagereview_lsettings']);
			}
		}
    include APPLICATION_PATH . '/application/modules/Sitepagereview/controllers/license/license1.php';
  }

 //ACTION FOR WIDGET SETTINGS
  public function widgetAction() {

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepagereview_admin_main', array(), 'sitepagereview_admin_main_widget');
  }

	//ACTION FOR LEVEL SETTINGS
  public function levelAction()
  {
		//GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitepagereview_admin_main', array(), 'sitepagereview_admin_main_level');

    //GET LEVEL ID
    if( null !== ($id = $this->_getParam('id')) ) {
      $level = Engine_Api::_()->getItem('authorization_level', $id);
    } else {
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
    }

		//LEVEL AUTHORIZATION
    if( !$level instanceof Authorization_Model_Level ) {
      throw new Engine_Exception('missing level');
    }

    $level_id = $id = $level->level_id;

    //CREATE FORM
    $this->view->form = $form = new Sitepagereview_Form_Admin_Settings_Level(array(
      'public' => ( in_array($level->type, array('public')) ),
      'moderator' => ( in_array($level->type, array('admin', 'moderator')) ),
    ));
    $form->level_id->setValue($level_id);

		//POPULATE DATA
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $form->populate($permissionsTable->getAllowed('sitepagereview_review', $level_id, array_keys($form->getValues())));

		//FORM VALIDATION
    if( !$this->getRequest()->isPost() )
    {
      return;
    }

		//FORM VALIDATION
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    //PROCESS
    $values = $form->getValues();

    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();

    try
    {
      $permissionsTable->setAllowed('sitepagereview_review', $level_id, $values);
      
      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }
    $form->addNotice('Your changes have been saved.');
  }

	//ACTION FOR REVIEW OF THE DAY
  public function manageDayItemsAction() {

		//TAB CREATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepagereview_admin_main', array(), 'sitepagereview_admin_main_dayitems');

		//FORM GENERATION
    $this->view->formFilter = $formFilter = new Sitepagereview_Form_Admin_Manage_Filter();
    $page = $this->_getParam('page', 1); 

    $values = array(); 
    if ($formFilter->isValid($this->_getAllParams())) {
      $values = $formFilter->getValues();
    }
    foreach ($values as $key => $value) {
      if (null == $value) {
        unset($values[$key]);
      }
    }
    $values = array_merge(array(
                'order' => 'start_date',
                'order_direction' => 'DESC',
            ), $values);

    $this->view->assign($values);

		//FETCH DATA
    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('itemofthedays', 'sitepage')->getItemOfDayList($values, 'review_id', 'sitepagereview_review');
    $this->view->paginator->setItemCountPerPage(50);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);
  }

	//ACTION FOR ADDING REVIEW OF THE DAY
  public function addDayItemAction() {

		//SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

    //FORM GENERATION
    $form = $this->view->form = new Sitepagereview_Form_Admin_Settings_AddDayItem();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

    //CHECK POST
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      //GET FORM VALUES
      $values = $form->getValues();

			//BEGIN TRANSACTION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        //GET ITEM OF THE DAY TABLE
        $dayItemTime = Engine_Api::_()->getDbtable('itemofthedays', 'sitepage');

				//FETCH RESULT FOR resource_id
        $select = $dayItemTime->select()->where('resource_id = ?', $values["resource_id"])->where('resource_type = ?', 'sitepagereview_review');
        $row = $dayItemTime->fetchRow($select);

        if (empty($row)) {
          $row = $dayItemTime->createRow();
          $row->resource_id = $values["resource_id"];
        }
        $row->start_date = $values["starttime"];
        $row->end_date = $values["endtime"];
				$row->resource_type = 'sitepagereview_review';
        $row->save();

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
              'smoothboxClose' => 10,
              'parentRefresh' => 10,
              'messages' => array(Zend_Registry::get('Zend_Translate')->_('The Review of the Day has been added successfully.'))
      ));
    }
  }

	//ACTION FOR REVIEW OF THE DAY SUGGESTION DROP-DOWN
  public function getDayItemAction() {

		$search_text = $this->_getParam('text', null);
		$limit = $this->_getParam('limit', 40);
		
    $data = array();

		//GET RESULTS
		$moduleContents = Engine_Api::_()->getItemTable('sitepagereview_review')->getDayItems($search_text, $limit);

    foreach ($moduleContents as $moduleContent) {

			$user = Engine_Api::_()->getItem('user', $moduleContent->owner_id);
			$content_photo = $this->view->itemPhoto($user, 'thumb.icon');

      $data[] = array(
              'id' => $moduleContent->review_id,
              'label' => $moduleContent->title,
              'photo' => $content_photo
      );
    }
    return $this->_helper->json($data);
  }

	//ACTION FOR DELETE REVIEW OF THE ENTRY
  public function deleteDayItemAction() {

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {

				//DELETE ITEM
        $itemofthedaysTable = Engine_Api::_()->getDbtable('itemofthedays', 'sitepage')->delete(array('itemoftheday_id =?' => $this->_getParam('id')));
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
              'smoothboxClose' => 10,
              'parentRefresh' => 10,
              'messages' => array(Zend_Registry::get('Zend_Translate')->_(''))
      ));
    }
    $this->renderScript('admin-settings/delete-day-item.tpl');
  }

  //ACTION FOR MULTI DELETE REVIEW OF THE DAY ENTRIES
  public function multiDeleteAction() {

    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $sitepageitemofthedays = Engine_Api::_()->getItem('sitepage_itemofthedays', (int) $value);
          if (!empty($sitepageitemofthedays)) {
            $sitepageitemofthedays->delete();
          }
        }
      }
    }
		return $this->_helper->redirector->gotoRoute(array('action' => 'manage-day-items'));
  }

  //SHOWING THE PLUGIN RELETED QUESTIONS AND ANSWERS
  public function faqAction() {

    //GET NAVIGATION
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepagereview_admin_main', array(), 'sitepagereview_admin_main_faq');
  }

  public function readmeAction() {
    
  }

}
?>