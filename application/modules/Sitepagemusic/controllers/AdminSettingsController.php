<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: AdminSettings.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_AdminSettingsController extends Core_Controller_Action_Admin {

    public function __call($method, $params) {
        /*
         * YOU MAY DISPLAY ANY ERROR MESSAGE USING FORM OBJECT.
         * YOU MAY EXECUTE ANY SCRIPT, WHICH YOU WANT TO EXECUTE ON FORM SUBMIT.
         * REMEMBER:
         *    RETURN TRUE: IF YOU DO NOT WANT TO STOP EXECUTION.
         *    RETURN FALSE: IF YOU WANT TO STOP EXECUTION.
         */
        if (!empty($method) && $method == 'Sitepagemusic_Form_Admin_Global') {

        }
        return true;
    }
    
  //ACTION FOR GLOBAL SETTINGS
  public function indexAction() {
    $pluginName = 'sitepagemusic';
    if (!empty($_POST[$pluginName . '_lsettings']))
      $_POST[$pluginName . '_lsettings'] = @trim($_POST[$pluginName . '_lsettings']);
    
    include APPLICATION_PATH . '/application/modules/Sitepagemusic/controllers/license/license1.php';
  }

  //ACTION FOR WIDGET SETTINGS
  public function widgetAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepagemusic_admin_main', array(), 'sitepagemusic_admin_widget_settings');
    $this->view->subNavigation = $subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepagemusic_admin_submain', array(), 'sitepagemusic_admin_submain_music_tab');
    $this->view->tabs = Engine_Api::_()->getItemTable('seaocore_tab')->getTabs(array('module' => 'sitepagemusic', 'type' => 'musics'));
  }  

  // ACTION FOR CHANGE SETTINGS OF TABBED MUSIC WIDZET TAB
  public function editTabAction() {
    //FORM GENERATION
    $this->view->form = $form = new Sitepagemusic_Form_Admin_EditTab();
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

  //ACTION FOR UPDATE ORDER  OF MUSICS WIDGTS TAB
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
    $this->_redirect('admin/sitepagemusic/settings/widget');
  }

   //ACTION FOR MUSIC OF THE DAY
  public function manageDayItemsAction() {

		//TAB CREATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepagemusic_admin_main', array(), 'sitepagemusic_admin_widget_settings');
    $this->view->subNavigation = $subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepagemusic_admin_submain', array(), 'sitepagemusic_admin_submain_dayitems');
   
    //FORM GENERATION
    $this->view->formFilter = $formFilter = new Sitepagemusic_Form_Admin_Manage_Filter();
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

    $this->view->musicOfDaysList = $musicOfDay = Engine_Api::_()->getDbtable('itemofthedays', 'sitepage')->getItemOfDayList($values, 'playlist_id', 'sitepagemusic_playlist');
    $musicOfDay->setItemCountPerPage(50);
    $musicOfDay->setCurrentPageNumber($page);
  }

  //ACTION FOR ADDING MUSIC OF THE DAY
  public function addMusicOfDayAction() {

    //SET LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

    //FORM GENERATION
    $form = $this->view->form = new Sitepagemusic_Form_Admin_ItemOfDayday();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));
    $form->setTitle('Add an Music of the Day')
            ->setDescription('Select a start date and end date below and the corresponding Music from the auto-suggest Music field. The selected Music will be displayed as "Music of the Day" for this duration and if more than one musics are found to be displayed in the same duration then they will be dispalyed randomly one at a time.');
    $form->getElement('title')->setLabel('Music Name');

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
        $select = $dayItemTime->select()->where('resource_id = ?', $values["resource_id"])->where('resource_type = ?', 'sitepagemusic_playlist');
        $row = $dayItemTime->fetchRow($select);

        if (empty($row)) {
          $row = $dayItemTime->createRow();
          $row->resource_id = $values["resource_id"];
        }
        $row->start_date = $values["starttime"];
        $row->end_date = $values["endtime"];
				$row->resource_type = 'sitepagemusic_playlist';
        $row->save();

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_forward('success', 'utility', 'core', array(
                  'smoothboxClose' => 10,
                  'parentRefresh' => 10,
                  'messages' => array(Zend_Registry::get('Zend_Translate')->_('The Music of the Day has been added successfully.'))
              ));
    }
  }

  //ACTION FOR MUSIC SUGGESTION DROP-DOWN
  public function getMusicAction() {
    $title = $this->_getParam('text', null);
    $limit = $this->_getParam('limit', 40);
    $pageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $pageTableName = $pageTable->info('name');
    $allowTable = Engine_Api::_()->getDbtable('allow', 'authorization');
    $allowName = $allowTable->info('name');
    $musicTable = Engine_Api::_()->getDbtable('playlists', 'sitepagemusic');
    $musicName = $musicTable->info('name');
    $data = array();
    $select = $musicTable->select()
													->setIntegrityCheck(false)
													->from($musicName)
                          ->join($pageTableName, $pageTableName . '.page_id = '. $musicName . '.page_id',array('title AS page_title', 'photo_id as page_photo_id'))
													->join($allowName, $allowName . '.resource_id = '. $pageTableName . '.page_id', array('resource_type','role'))
													->where($allowName.'.resource_type = ?', 'sitepage_page')
													->where($allowName.'.role = ?', 'registered')
													->where($allowName.'.action = ?', 'view')
													->where($musicName.'.search = ?', 1)
													->where($musicName.'.title  LIKE ? ', '%' . $title . '%')
													->limit($limit)
													->order($musicName.'.creation_date DESC');
    $select = $select
              ->where($pageTableName . '.closed = ?', '0')
              ->where($pageTableName . '.approved = ?', '1')
              ->where($pageTableName . '.declined = ?', '0')
              ->where($pageTableName . '.draft = ?', '1');
    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $select->where($pageTableName . '.expiration_date  > ?', date("Y-m-d H:i:s"));
    }
    $musics = $musicTable->fetchAll($select);

    foreach ($musics as $music) {
      $content_photo = $this->view->itemPhoto($music, 'thumb.normal');
      $data[] = array(
          'id' => $music->playlist_id,
          'label' => $music->title,
          'photo' => $content_photo
      );
    }
    return $this->_helper->json($data);
  }

  //ACTION FOR DELETE MUSIC OF DAY ENTRY
  public function deleteMusicOfDayAction() {
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

  //ACTION FOR MULTI DELETE MUSIC ENTRIES
  public function multiDeleteMusicAction() {
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

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sitepagemusic_admin_main', array(), 'sitepagemusic_admin_main_faq');
  }

  public function readmeAction() {
    
  }

}

?>