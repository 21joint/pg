<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_AdminSettingsController extends Core_Controller_Action_Admin {

    public function __call($method, $params) {
        /*
         * YOU MAY DISPLAY ANY ERROR MESSAGE USING FORM OBJECT.
         * YOU MAY EXECUTE ANY SCRIPT, WHICH YOU WANT TO EXECUTE ON FORM SUBMIT.
         * REMEMBER:
         *    RETURN TRUE: IF YOU DO NOT WANT TO STOP EXECUTION.
         *    RETURN FALSE: IF YOU WANT TO STOP EXECUTION.
         */
        if (!empty($method) && $method == 'Sitepageoffer_Form_Admin_Global') {

        }
        return true;
    }
    
  //ACTINO FOR GLOBAL SETTINGS
  public function indexAction() {

    if ($this->getRequest()->isPost()) {
      $sitepageKeyVeri = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.lsettings', null);
      if (!empty($sitepageKeyVeri)) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepage.lsettings', trim($sitepageKeyVeri));
      }
      if ($_POST['sitepageoffer_lsettings']) {
        $_POST['sitepageoffer_lsettings'] = trim($_POST['sitepageoffer_lsettings']);
      }
    }
    include APPLICATION_PATH . '/application/modules/Sitepageoffer/controllers/license/license1.php';
  }

  //ACTION FOR WIDGET SETTINGS
  public function widgetAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepageoffer_admin_main', array(), 'sitepageoffer_admin_main_offer_tab');
    $this->view->tabs = Engine_Api::_()->getItemTable('seaocore_tab')->getTabs(array('module' => 'sitepageoffer', 'type' => 'offers'));
  } 

  //ACTION FOR OFFER OF THE DAY
  public function manageDayItemsAction() {

		//TAB CREATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepageoffer_admin_main', array(), 'sitepageoffer_admin_main_dayitems');
   
    //FORM GENERATION
    $this->view->formFilter = $formFilter = new Sitepageoffer_Form_Admin_Manage_Filter();
    $page = $this->_getParam('page', 1);

    $values = array();
    if ($formFilter->isValid($this->_getAllParams())) {
      $values = $formFilter->getValues();
    }

    $values = array_merge(array(
        'order' => 'start_date',
        'order_direction' => 'DESC',
            ), $values);

    $this->view->assign($values);

    $this->view->offerOfDaysList = $offerOfDay = Engine_Api::_()->getDbtable('itemofthedays', 'sitepage')->getItemOfDayList($values, 'offer_id', 'sitepageoffer_offer');
    $offerOfDay->setItemCountPerPage(50);
    $offerOfDay->setCurrentPageNumber($page);
  }

  //ACTION FOR ADDING OFFER OF THE DAY
  public function addOfferOfDayAction() {

    //SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

    //FORM GENERATION
    $form = $this->view->form = new Sitepageoffer_Form_Admin_ItemOfDayday();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));
    $form->setTitle('Add an Offer of the Day')
            ->setDescription('Select a start date and end date below and the corresponding Offer from the auto-suggest Offer field. The selected Offer will be displayed as "Offer of the Day" for this duration and if more than one offers are found to be displayed in the same duration then they will be dispalyed randomly one at a time.');
    $form->getElement('title')->setLabel('Offer Name');

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
        $select = $dayItemTime->select()->where('resource_id = ?', $values["resource_id"])->where('resource_type = ?', 'sitepageoffer_offer');
        $row = $dayItemTime->fetchRow($select);

        if (empty($row)) {
          $row = $dayItemTime->createRow();
          $row->resource_id = $values["resource_id"];
        }
        $row->start_date = $values["starttime"];
        $row->end_date = $values["endtime"];
				$row->resource_type = 'sitepageoffer_offer';
        $row->save();

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_forward('success', 'utility', 'core', array(
                  'smoothboxClose' => 10,
                  'parentRefresh' => 10,
                  'messages' => array(Zend_Registry::get('Zend_Translate')->_('The Offer of the Day has been added successfully.'))
              ));
    }
  }

   // ACTION FOR CHANGE SETTINGS OF TABBED OFFER WIDZET TAB
  public function editTabAction() {

		$this->view->tabs = $tabs = Engine_Api::_()->getItemTable('seaocore_tab')->getTabs(array('module' => 'sitepageoffer', 'type' => 'offers', 'enabled' => 1));
    //FORM GENERATION
    $this->view->form = $form = new Sitepageoffer_Form_Admin_EditTab();
    $id = $this->_getParam('tab_id');

    $tab = Engine_Api::_()->getItem('seaocore_tab', $id);
    //CHECK POST
    if (!$this->getRequest()->isPost()) {
      $values = $tab->toarray();
      $form->populate($values);
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    $values = $form->getValues();
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $tab->setFromArray($values);
      $tab->save();
      $db->commit();
      return $this->_forward('success', 'utility', 'core', array(
                  'smoothboxClose' => 10,
                  'parentRefresh' => 10,
                  'messages' => array(Zend_Registry::get('Zend_Translate')->_('Edit Tab Settings Sucessfully.'))
              ));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  //ACTION FOR UPDATE ORDER  OF OFFERS WIDGTS TAB
  public function updateOrderAction() {
    //CHECK POST
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      $values = $_POST;
      try {
        foreach ($values['order'] as $key => $value) {
          $tab = Engine_Api::_()->getItem('seaocore_tab', (int) $value);
          if (!empty($tab)) {
            $tab->order = $key + 1;
            $tab->save();
          }
        }
        $db->commit();
        $this->_helper->redirector->gotoRoute(array('action' => 'widget'));
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }
  }

  //ACTION FOR MAKE TAB ENABLE/DISABLE
  public function enabledAction() {
    $id = $this->_getParam('tab_id');
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    $tab = Engine_Api::_()->getItem('seaocore_tab', $id);
    try {
      $tab->enabled = !$tab->enabled;
      $tab->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->_redirect('admin/sitepageoffer/settings/widget');
  }

  //ACTION FOR OFFER SUGGESTION DROP-DOWN
  public function getOfferAction() {
    $title = $this->_getParam('text', null);
    $limit = $this->_getParam('limit', 40);
    $pageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $pageTableName = $pageTable->info('name');
    $allowTable = Engine_Api::_()->getDbtable('allow', 'authorization');
    $allowName = $allowTable->info('name');
    $offerTable = Engine_Api::_()->getDbtable('offers', 'sitepageoffer');
    $offerName = $offerTable->info('name');
    $data = array();
    $select = $offerTable->select()
													->setIntegrityCheck(false)
													->from($offerName)
                          ->join($pageTableName, $pageTableName . '.page_id = '. $offerName . '.page_id',array('title AS page_title', 'photo_id as page_photo_id'))
													->join($allowName, $allowName . '.resource_id = '. $pageTableName . '.page_id', array('resource_type','role'))
													->where($allowName.'.resource_type = ?', 'sitepage_page')
													->where($allowName.'.role = ?', 'registered')
													->where($allowName.'.action = ?', 'view')
													->where($offerName.'.title  LIKE ? ', '%' . $title . '%')
													->limit($limit)
													->order($offerName.'.creation_date DESC');
    $select = $select
              ->where($pageTableName . '.closed = ?', '0')
              ->where($pageTableName . '.approved = ?', '1')
              ->where($pageTableName . '.declined = ?', '0')
              ->where($pageTableName . '.draft = ?', '1');
    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $select->where($pageTableName . '.expiration_date  > ?', date("Y-m-d H:i:s"));
    }
    $offers = $offerTable->fetchAll($select);

    foreach ($offers as $offer) {
      if($offer->photo_id) {
				$content_photo = $this->view->itemPhoto($offer, 'thumb.normal');
      }
      else {
				$content_photo = "<img src='". $this->view->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' />";
      }
      $data[] = array(
          'id' => $offer->offer_id,
          'label' => $offer->title,
          'photo' => $content_photo
      );
    }
    return $this->_helper->json($data);
  }
  //ACTION FOR DELETE OFFER OF DAY ENTRY
  public function deleteOfferOfDayAction() {
    $this->view->id = $this->_getParam('id');
    if ($this->getRequest()->isPost()) {
      Engine_Api::_()->getDbtable('itemofthedays', 'sitepage')->delete(array('itemoftheday_id =?' => $this->_getParam('id')));
      return $this->_forward('success', 'utility', 'core', array(
                  'smoothboxClose' => 10,
                  'parentRefresh' => 10,
                  'messages' => array(Zend_Registry::get('Zend_Translate')->_(''))
              ));
    }
    $this->renderScript('admin-settings/delete.tpl');
  }

  //ACTION FOR MULTI DELETE OFFER ENTRIES
  public function multiDeleteOfferAction() {
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

  //ACTION FOR FAQ
  public function faqAction() {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                    ->getNavigation('sitepageoffer_admin_main', array(), 'sitepageoffer_admin_main_faq');
  }

  public function readmeAction() {
    
  }

}
?>