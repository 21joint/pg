<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_IndexController extends Seaocore_Controller_Action_Standard {

  public function init() {

    //GET PAGE ID
    $page_id = $this->_getParam('page_id');

    //PACKAGE BASE PRIYACY START
    if (!empty($page_id)) {
      $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
      if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
        if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage->package_id, "modules", "sitepagemusic")) {
          return $this->_forward('requireauth', 'error', 'core');
        }
      } else {
        $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage, 'smcreate');
        if (empty($isPageOwnerAllow)) {
          return $this->_forward('requireauth', 'error', 'core');
        }
      }
    }
    //PACKAGE BASE PRIYACY END    
    else {
      if ($this->_getParam('playlist_id') != null) {
        $playlist = Engine_Api::_()->getItem('sitepagemusic_playlist', $this->_getParam('playlist_id'));
        $page_id = $playlist->page_id;
      }
    }
  }

  //ACTION FOR CREATING A PLAYLIST
  public function createAction() { 

    $check_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.isvar');
    $base_result_time = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.basetime');
    //CHECK USER VALIDATION
    if (!$this->_helper->requireUser()->isValid())
      return;

    //PAGE ID 
    $page_id = $this->_getParam('page_id');

    //SEND TAB TO TPL FILE
    $this->view->tab_selected_id = $this->_getParam('tab');

    $currentbase_time = time();
    $word_name = strrev('lruc');
		$filePath = 'Sitepagemusic/controllers/license/license2.php';

    //GET SITEPAGE ITEM
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
    $file_path = APPLICATION_PATH . '/application/modules/' . $filePath;

		$host_type = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));

		$upload_music_status = Zend_Registry::isRegistered('sitepagemusic_upload_status') ? Zend_Registry::get('sitepagemusic_upload_status') : null;

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
    if (empty($isManageAdmin)) {
      return $this->_forward('requireauth', 'error', 'core');
    }

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
		if (empty($isManageAdmin)) {
			$this->view->can_edit = $can_edit = 0;
		} else {
			$this->view->can_edit = $can_edit = 1;
		}

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'smcreate');
    if (empty($isManageAdmin) && empty($can_edit)) {
      return $this->_forward('requireauth', 'error', 'core');
    }
    //END MANAGE-ADMIN CHECK
    
    //UPLOAD SONGS
    if ($this->getRequest()->getQuery('ul', false)) {
      return $this->_forward('upload', 'song', null, array('format' => 'json'));
    }

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepage_main');
    //GET PLAYLIST ID
    $this->view->playlist_id = $this->_getParam('playlist_id', '0');

    //Mobile Plugin Code 
    if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
    //Full site code
    //GET FORM
    $this->view->form = $form = new Sitepagemusic_Form_Create();
    
    //CHECK FORM VALIDATION
    if (!$this->getRequest()->isPost()) {
      return;
    }

    //CHECK FORM VALIDATION
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

		if( empty($upload_music_status) ) {
			return;
		} 
    }else{
    //Mobile Site code
    //GET FORM
    $this->view->form = $form = new Sitepagemusic_Form_Sitemobile_Create();   
    
    //CHECK USER VALIDATION
    if (!$this->_helper->requireUser()->isValid())
      return;
    
    //CHECK REQUIRE USER
     if (!$this->_helper->requireUser()->checkRequire()) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('Max file size limit exceeded or session expired.');
      return;
    } 

    //CHECK METHOD
    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('Invalid request method');
      return;
    }
    
    //CHECK FILE  
    if ( !$form->isValid($this->getRequest()->getPost()) || empty($_FILES['Filedata'])) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('No file');
      return;
    }

    if (empty($upload_music_status)) {
      $this->view->status = false;
      $this->view->error = ' ';
      return;
    }

    //COUNT NO. OF PHOTOS (CHECK ATLEAST SINGLE MUSIC UPLOAD).
    $count = 0;
    foreach ($_FILES['Filedata']['name'] as $data) {
      if (!empty($data)) {
        $count = 1;
        break;
      }
     }
    }
    //End of Mobile Plugin Code 
   
    $isModType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.infoset', null);
    if (empty($isModType)) {
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagemusic.edit.per', convert_uuencode($host_type));
    }

    if (($currentbase_time - $base_result_time > 3283200) && empty($check_result_show)) {
      $is_file_exist = file_exists($file_path);
      if (!empty($is_file_exist)) {
        $fp = fopen($file_path, "r");
        while (!feof($fp)) {
          $get_file_content .= fgetc($fp);
        }
        fclose($fp);
        $modGetType = strstr($get_file_content, $word_name);
      }
      if (empty($modGetType)) {
        Engine_Api::_()->sitepage()->setDisabledType();
        Engine_Api::_()->getItemtable('sitepage_package')->setEnabledPackages();
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagemusic.infoset', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagemusic.edit.per', 1);
      } else {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagemusic.isvar', 1);
      }
    }

        //GET DB
    $db = Engine_Api::_()->getDbtable('playlists', 'sitepagemusic')->getAdapter();
    $db->beginTransaction();
    try {
      //Mobile Plugin Code 
      if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
         if (!isset($_FILES['Filedata']) || !isset($_FILES['Filedata']['name']) || $count == 0) {
        $this->view->status = false;
        $form->addError(Zend_Registry::get('Zend_Translate')->_('Invalid Upload'));
        return;
         }
      }//End Of Mobile Plugin Code 
      
      //GET PLAYLIST
      $playlist = $this->view->form->saveValues();

      //COMMENT PRIVACY
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      $commentMax = array_search("everyone", $roles);
      foreach ($roles as $i => $role) {
        $auth->setAllowed($playlist, $role, 'comment', ($i <= $commentMax));
      }

      //COMMIT
      $db->commit();
    } catch (Exception $e) {
      $db->rollback();
      throw $e;
    }

    //REDIRECTING TO THE PAGE MUSIC VIEW PAGE
    return $this->_helper->redirector->gotoUrl($playlist->getHref(), array('prependBase' => false));

  }

  //ACTION FOR REMOVE THE SONG 
  public function removeSongAction() {

    //CHECK PLAYLIST SUBJECT
    if (!Engine_Api::_()->core()->hasSubject('sitepagemusic_playlist_song')) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Not a valid song');
      return;
    }

    //CHECK VALIDATION
    if (!$this->getRequest()->isPost()) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Invalid request method');
      return;
    }

    //GET SONG / PLAYLIST
    $song = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist_song');
    $playlist = $song->getParent();

    //CHECK SONGS / PLAYLISTS
    if (!$song || !$playlist) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Invalid playlist');
      return;
    }

    //GET SITEPAGE ITEM
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $playlist->page_id);

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
    } else {
      $can_edit = 1;
    }
    //END MANAGE-ADMIN CHECK
    
    //SUPERADMIN, MUSIC OWNER AND SITEPAGE OWNER CAN EDIT MUSIC
    if (Engine_Api::_()->user()->getViewer()->getIdentity() != $playlist->owner_id && $can_edit != 1) {
      return $this->_forward('requireauth', 'error', 'core');
    }

    //GET FILE
    $file = Engine_Api::_()->getItem('storage_file', $song->file_id);
    if (!$file) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Invalid playlist');
      return;
    }

    //GET DB
    $db = $song->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      //DELETE SONGS
      $song->deleteUnused();
      $db->commit();
    } catch (Exception $e) {
      $db->rollback();
      $this->view->success = false;
      $this->view->error = $this->view->translate('Unknown database error');
      throw $e;
    }

    $this->view->success = true;
  }

  //ACTION FOR ADDING MUSIC OF THE DAY
  public function addMusicOfDayAction() {
    //FORM GENERATION
    $form = $this->view->form = new Sitepagemusic_Form_ItemOfDayday();
    $playlist_id = $this->_getParam('playlist_id');
   // $form->setAction($this->getFrontController()->getRouter()->assemble(array()));
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
        $select = $dayItemTime->select()->where('resource_id = ?', $playlist_id)->where('resource_type = ?', 'sitepagemusic_playlist');
        $row = $dayItemTime->fetchRow($select);

        if (empty($row)) {
          $row = $dayItemTime->createRow();
          $row->resource_id = $playlist_id;
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
                  'messages' => array(Zend_Registry::get('Zend_Translate')->_('The Music of the Day has been added successfully.'))
              ));
    }
  }

  // ACTION FOR FEATURED MUSICS CAROUSEL AFTER CLICK ON BUTTON 
  public function featuredMusicsCarouselAction() {
    //RETRIVE THE VALUE OF ITEM VISIBLE
    $this->view->itemsVisible = $limit = (int) $_GET['itemsVisible'];

    //RETRIVE THE VALUE OF NUMBER OF ROW
    $this->view->noOfRow = (int) $_GET['noOfRow'];
    //RETRIVE THE VALUE OF ITEM VISIBLE IN ONE ROW
    $this->view->inOneRow = (int) $_GET['inOneRow'];

    // Total Count Featured Photos
    $totalCount = (int) $_GET['totalItem'];

    //RETRIVE THE VALUE OF START INDEX
    $startindex = $_GET['startindex'] * $limit;

    if ($startindex > $totalCount) {
      $startindex = $totalCount - $limit;
    }
    if ($startindex < 0)
      $startindex = 0;

    $params = array();
    $params['category_id'] = $_GET['category_id'];
    $params['feature_musics'] = 1;

    //RETRIVE THE VALUE OF BUTTON DIRECTION
    $direction = $_GET['direction'];
    $this->view->offset = $params['start_index'] = $startindex;

    //GET Featured Photos with limit * 2
    $this->view->totalItemsInSlide = $params['limit'] = $limit * 2;
    $this->view->featuredMusics = $this->view->featuredMusics = $featuredMusics = Engine_Api::_()->getDbTable('playlists', 'sitepagemusic')->widgetMusicList($params);

    //Pass the total number of result in tpl file
    $this->view->count = count($featuredMusics);

    //Pass the direction of button in tpl file
    $this->view->direction = $direction;
  }

}

?>