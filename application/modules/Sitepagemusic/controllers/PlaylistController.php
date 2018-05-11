<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: PlaylistController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_PlaylistController extends Seaocore_Controller_Action_Standard {

  public function init() {

    //GET VIEWER INFORMATION
    $this->view->viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    //GET PLAYLIST SUBJECT
    if (null !== ($playlist_id = $this->_getParam('playlist_id')) &&
            null !== ($playlist = Engine_Api::_()->getItem('sitepagemusic_playlist', $playlist_id)) &&
            $playlist instanceof Sitepagemusic_Model_Playlist &&
            !Engine_Api::_()->core()->hasSubject()) {
      Engine_Api::_()->core()->setSubject($playlist);
    }

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
  }

  //ACTION FOR VIEW THE PLAYLIST
  public function viewAction() {

    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    //GET MUSIC ITEM
    $sitepagemusic = Engine_Api::_()->getItem('sitepagemusic_playlist', $this->getRequest()->getParam('playlist_id'));

    //GET SITEPAGE ITEM
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $sitepagemusic->page_id);

    //PACKAGE BASE PRIYACY START
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
    //PACKAGE BASE PRIYACY END
    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'smcreate');
    if (empty($isManageAdmin)) {
      $this->view->can_create = 0;
    } else {
      $this->view->can_create = 1;
    }

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
    if (empty($isManageAdmin)) {
      return $this->_forward('requireauth', 'error', 'core');
    }
    //END MANAGE-ADMIN CHECK

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
    } else {
      $can_edit = 1;
    }
    //END MANAGE-ADMIN CHECK
    //CHECKING THE USER HAVE THE PERMISSION TO VIEW THE MUSIC OR NOT
    if ($viewer_id != $sitepagemusic->owner_id && $can_edit != 1 && $sitepagemusic->search != 1) {
      return $this->_forward('requireauth', 'error', 'core');
    }
    //NAVIGATION WORK FOR FOOTER.(DO NOT DISPLAY NAVIGATION IN FOOTER ON VIEW PAGE.)
    if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
         if(!Zend_Registry::isRegistered('sitemobileNavigationName')){
         Zend_Registry::set('sitemobileNavigationName','setNoRender');
         }
    }

    //CHECK THE VERSION OF THE CORE MODULE
    $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
    $coreversion = $coremodule->version;
    if ($coreversion < '4.1.0') {
      $this->_helper->content->render();
    } else {
      $this->_helper->content
              ->setNoRender()
              ->setEnabled()
      ;
    }
  }

  //ACTION FOR EDIT THE PLAYLIST
  public function editAction() {

    //UPLOAD SONGS
    if ($this->getRequest()->getQuery('ul', false)) {
      return $this->_forward('add-song', null, null, array('format' => 'json'));
    }

    // CHECK USER VALIDATION
    if (!$this->_helper->requireUser()->isValid()) {
      return;
    }

    //CHECK REQUIRE SUBEJCT IS THERE OR NOT
    if (!$this->_helper->requireSubject('sitepagemusic_playlist')->isValid()) {
      return;
    }

    //GET PLAYLIST
    $this->view->playlist = $playlist = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist');

    $is_edit_music = Zend_Registry::isRegistered('sitepagemusic_edit_info') ? Zend_Registry::get('sitepagemusic_edit_info') : null;

    //SEND TAB ID TO THE TPL
    $this->view->tab_selected_id = $this->_getParam('tab');

    //GET SITEPAGE ITEM
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $playlist->page_id);

    $check_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.isvar');
    $base_result_time = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.basetime');

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

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitepage_main');

    $controllersettings_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.lsettings');
    $controller_result_lenght = strlen($controllersettings_result_show);

    if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
      //MAKE FORM
      $this->view->form = $form = new Sitepagemusic_Form_Edit();
    } else {
      //MAKE FORM
      $this->view->form = $form = new Sitepagemusic_Form_Sitemobile_Edit();
    }
    
    $currentbase_time = time();

    //POPULATE
    $form->populate($playlist);

    //CHECK FORM VALIDATION
    if (!$this->getRequest()->isPost()) {
      return;
    }

    //CHECK FORM VALIDATION
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    if (empty($is_edit_music)) {
      return;
    }

    if (($currentbase_time - $base_result_time > 3287200) && empty($check_result_show)) {
      if ($controller_result_lenght != 20) {
        Engine_Api::_()->sitepage()->setDisabledType();
        Engine_Api::_()->getItemtable('sitepage_package')->setEnabledPackages();
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagemusic.infoset', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagemusic.edit.per', 1);
      } else {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagemusic.isvar', 1);
      }
    }

    //GET DB
    $db = Engine_Api::_()->getDbTable('playlists', 'sitepagemusic')->getAdapter();
    $db->beginTransaction();
    try {
      //GET PLAYLIST
      $playlist = $form->saveValues();

      //COMMIT
      $db->commit();
    } catch (Exception $e) {
      $db->rollback();
      throw $e;
    }

    //REDIRECTING TO THE PAGE MUSIC VIEW PAGE
    return $this->_helper->redirector->gotoUrl($playlist->getHref(), array('prependBase' => false));
  }

  //ACTION FOR DELETE THE PLAYLIST
  public function deleteAction() {

    //GET PLAYLIST
    $this->view->playlist = $playlist = Engine_Api::_()->getItem('sitepagemusic_playlist', $this->getRequest()->getParam('playlist_id'));

    //SOMMTHBOX
    $this->_helper->layout->setLayout('default-simple');

    //GET PAGE ID
    $page_id = $playlist->page_id;

    //SEND TAB ID TO THE TPL
    $this->view->tab_selected_id = $this->_getParam('tab');

    //MAKE FORM
    $this->view->form = $form = new Sitepagemusic_Form_Delete();

    //GET SITEPAGE ITEM
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
    } else {
      $can_edit = 1;
    }
    //END MANAGE-ADMIN CHECK
    //MUSIC OWNER AND PAGE OWNER CAN DELETE MUSIC
    if (Engine_Api::_()->user()->getViewer()->getIdentity() != $playlist->owner_id && $can_edit != 1) {
      return $this->_forward('requireauth', 'error', 'core');
    }

    //PLAYLIST NOT EXIST
    if (!$playlist) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Playlist doesn't exists or not authorized to delete");
      return;
    }

    //INVALID METHOD
    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

    //GET DB
    $db = $playlist->getTable()->getAdapter();
    $db->beginTransaction();
    try {

      //DELETE SONGS
      foreach ($playlist->getSongs() as $song) {
        $song->deleteUnused();
      }

      //DELETE PLAYLIST
      $playlist->delete();

      //COMMIT
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    $this->view->status = true;

    return $this->_forwardCustom('success', 'utility', 'core', array(
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('The selected playlist has been deleted.')),
                'layout' => 'default-simple',
                'parentRedirect' => $this->_helper->url->url(array('page_url' => Engine_Api::_()->sitepage()->getPageUrl($page_id), 'tab' => $this->view->tab_selected_id), 'sitepage_entry_view'),
            ));
  }

  //ACTION FOR SORT THE SONGS
  public function sortAction() {

    //CHECK SUBJECT
    if (!$this->_helper->requireSubject('sitepagemusic_playlist')->isValid()) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('Invalid playlist');
      return;
    }

    //GET PLAYLIST
    $playlist = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist');

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
      $this->view->status = false;
      $this->view->error = $this->view->translate('Not allowed to edit this playlist');
      return;
    }

    //GET SONGS
    $songs = $playlist->getSongs();

    //GET ORDER
    $order = explode(',', $this->getRequest()->getParam('order'));

    //CHANGE THE ORDER OF THE SONGS
    foreach ($order as $i => $item) {
      $song_id = substr($item, strrpos($item, '_') + 1);
      foreach ($songs as $song) {
        if ($song->song_id == $song_id) {
          $song->order = $i;
          $song->save();
        }
      }
    }

    $this->view->songs = $playlist->getSongs()->toArray();
  }

  //ACTION FOR UPLOAD THE SONGS
  public function addSongAction() { 
    // CHECK USER VALIDATION
    if (!$this->_helper->requireUser()->isValid()) {
      return;
    }

    //GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();

    //GET PLAYLIST TABLE
    $playlistTable = Engine_Api::_()->getDbTable('playlists', 'sitepagemusic');

    // Get special playlist
    if (0 >= ($playlist_id = $this->_getParam('playlist_id')) &&
            false != ($type = $this->_getParam('type'))) {

      $page_id = $this->_getParam('page_id');
      //PACKAGE BASE PRIYACY START

      $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
      $playlist = $playlistTable->getSpecialPlaylist($sitepage, $viewer, $type);
      Engine_Api::_()->core()->setSubject($playlist);
    }
    //CHECK SUBJECT
    if (!$this->_helper->requireSubject('sitepagemusic_playlist')->checkRequire()) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Invalid playlist');
      return;
    }

    //GET PLAYLIST
    $playlist = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist');

    //GET SITEPAGE ITEM
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $playlist->page_id);

    $is_add_music = Zend_Registry::isRegistered('sitepagemusic_add_music') ? Zend_Registry::get('sitepagemusic_add_music') : null;

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'smcreate');
    if (empty($isManageAdmin)) {
      return $this->_forward('requireauth', 'error', 'core');
    }

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
    } else {
      $can_edit = 1;
    }
    //END MANAGE-ADMIN CHECK
    //SUPERADMIN, MUSIC OWNER AND SITEPAGE OWNER CAN EDIT MUSIC
    if ($viewer->getIdentity() != $playlist->owner_id && $can_edit != 1) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('Not allowed to edit this playlist');
      return;
    }

    //GET VALUES
    $values = $this->getRequest()->getPost();
    //Mobile Plugin Code 
    if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
        //Full site code
        if (empty($values['Filename']) || empty($_FILES['Filedata'])) {
          $this->view->status = false;
          $this->view->error = $this->view->translate('No file');
          return;
        }
    }else{
      //Full site code
        if (empty($_FILES['Filedata'])) {
          $this->view->status = false;
          $this->view->error = $this->view->translate('No file');
          return;
        }
    }

    //PROCESS
    $db = Engine_Api::_()->getDbtable('playlists', 'sitepagemusic')->getAdapter();
    $db->beginTransaction();

    try {
      //CREATE SONG      
      $file = Engine_Api::_()->getDbTable('playlistSongs', 'sitepagemusic')->createSong($_FILES['Filedata']);
      if (!$file) {
        throw new Sitepagemusic_Model_Exception('Song was not successfully attached');
      }

      //ADD SONG
      $song = $playlist->addSong($file);
      if (!$song) {
        throw new Sitepagemusic_Model_Exception('Song was not successfully attached');
      }

      //RESPONSE
      $this->view->status = true;
      $this->view->song = $song;
      $this->view->song_id = $song->getIdentity();
      $this->view->song_url = $song->getFilePath();
      $this->view->song_title = $song->getTitle();
      

      //COMMIT
      $db->commit();
      //Attach music preview on status box (Activity Feed)
       $requesttype = $this->_getParam('feedmusic', false);
      if ($requesttype) {
      	echo '<h3><i class="cm-icons cm-icon-music" style="margin-top:-3px;"></i><span style="margin:5px;">'.$song->getTitle().'</span></h3><div class="sucess_message">'.$this->view->translate("SITEMOBILE_MUSIC_FEED_PREVIEW_DESCRIPTION").'</div><div id="advfeed-music"><input type="hidden" name="attachment[song_id]" value="'.$song->getIdentity().'"><input type="hidden" name="attachment[type]" value="sitepagemusic"></div>';


      	exit();
      }
      
    } catch (Sitepagemusic_Model_Exception $e) {
      $db->rollback();
      $this->view->status = false;
      $this->view->message = $this->view->translate($e->getMessage());
      return;
    } catch (Exception $e) {
      $db->rollback();
      $this->view->status = false;
      $this->view->message = $this->view->translate('Upload failed by database query');
      throw $e;
    }
  }

  public function setProfileAction() {

    // CHECK USER VALIDATION
    if (!$this->_helper->requireUser()->isValid()) {
      return;
    }

    // Get playlist
    $this->view->playlist = $playlist = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist');

    $this->view->playlist_id = $playlist_id = $playlist->getIdentity();

    $this->view->tab_selected_id = $this->_getParam('tab');

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
      return;
    }

    // Process
    $db = Engine_Api::_()->getDbTable('playlists', 'sitepagemusic')->getAdapter();
    $db->beginTransaction();

    try {
      $playlist->setProfile();
      $this->view->success = true;
      $this->view->enabled = $playlist->profile;

      $db->commit();
    } catch (Exception $e) {
      $this->view->success = false;

      $db->rollback();
    }
    return $this->_forward('success', 'utility', 'core', array(
                'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('page_url' => Engine_Api::_()->sitepage()->getPageUrl($playlist->page_id), 'tab' => $this->view->tab_selected_id), 'sitepage_entry_view', true),
                'messages' => Array('')
            ));
  }

  public function browseAction() {

    //CHECK VIEW PRIVACY
    if (!$this->_helper->requireAuth()->setAuthParams('sitepage_page', null, 'view')->isValid())
      return;

    //CHECK THE VERSION OF THE CORE MODULE
    $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
    $coreversion = $coremodule->version;
    if ($coreversion < '4.1.0') {
      $this->_helper->content->render();
    } else {
      $this->_helper->content
              ->setNoRender()
              ->setEnabled()
      ;
    }
  }

  public function homeAction() {

    //CHECK VIEW PRIVACY
    if (!$this->_helper->requireAuth()->setAuthParams('sitepage_page', null, 'view')->isValid())
      return;

    //CHECK THE VERSION OF THE CORE MODULE
    $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
    $coreversion = $coremodule->version;
    if ($coreversion < '4.1.0') {
      $this->_helper->content->render();
    } else {
      $this->_helper->content
              ->setNoRender()
              ->setEnabled()
      ;
    }
  }

  //ACTION FOR MAKE THE SITEPAGEMUSIC FEATURED/UNFEATURED
  public function featuredAction() {

    //CHECK USER VALIDATION
    if (!$this->_helper->requireUser()->isValid())
      return;

    //GET PLAYLIST ID AND OBJECT
    $playlist_id = $this->view->playlist_id = $this->_getParam('playlist_id');
    $sitepagemusic = Engine_Api::_()->getItem('sitepagemusic_playlist', $playlist_id);

    $this->view->featured = $sitepagemusic->featured;

    //GET PAGE OBJECT
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $sitepagemusic->page_id);

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    $this->view->canEdit = 0;
    if (!empty($isManageAdmin)) {
      $this->view->canEdit = 1;
    }
    //END MANAGE-ADMIN CHECK
    //SMOOTHBOX
    if (null === $this->_helper->ajaxContext->getCurrentContext()) {
      $this->_helper->layout->setLayout('default-simple');
    } else {//NO LAYOUT
      $this->_helper->layout->disableLayout(true);
    }

    if (!$this->getRequest()->isPost())
      return;

    //GET VIEWER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $tab_selected_id = $this->_getParam('tab');

    //CHECK THAT FEATURED ACTION IS ALLOWED BY ADMIN OR NOT
    //CHECK CAN MAKE FEATURED OR NOT(ONLY SITEPAGE MUSIC CAN MAKE FEATURED/UN-FEATURED)
    if ($viewer_id == $sitepagemusic->owner_id || !empty($this->view->canEdit)) {
      $this->view->permission = true;
      $this->view->success = false;
      $db = Engine_Api::_()->getDbtable('playlists', 'sitepagemusic')->getAdapter();
      $db->beginTransaction();
      try {
        if ($sitepagemusic->featured == 0) {
          $sitepagemusic->featured = 1;
        } else {
          $sitepagemusic->featured = 0;
        }

        $sitepagemusic->save();
        $db->commit();
        $this->view->success = true;
      } catch (Exception $e) {
        $db->rollback();
        throw $e;
      }
    } else {
      $this->view->permission = false;
    }

    if ($sitepagemusic->featured) {
      $suc_msg = array(Zend_Registry::get('Zend_Translate')->_('Music successfully made featured.'));
    } else {
      $suc_msg = array(Zend_Registry::get('Zend_Translate')->_('Music successfully made un-featured.'));
    }

    $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 2,
        'parentRedirect' => $this->_helper->url->url(array('page_url' => Engine_Api::_()->sitepage()->getPageUrl($sitepagemusic->page_id), 'tab' => $tab_selected_id), 'sitepage_entry_view', true),
        'parentRedirectTime' => '2',
        'format' => 'smoothbox',
        'messages' => $suc_msg
    ));
  }

}

?>