<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: SongController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_SongController extends Seaocore_Controller_Action_Standard {

  public function init() {

    //GET VIEWER INFORMATION
    $this->view->viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    //GET SUBJECT
    if (null !== ($song_id = $this->_getParam('song_id')) &&
            null !== ($song = Engine_Api::_()->getItem('sitepagemusic_playlist_song', $song_id)) &&
            $song instanceof Sitepagemusic_Model_PlaylistSong) {
      Engine_Api::_()->core()->setSubject($song);
    }
  }

  //ACTION FOR RENAME THE SONG
  public function renameAction() {

    //CHECK SUBJECT
    if (!Engine_Api::_()->core()->hasSubject('sitepagemusic_playlist_song')) {
      $this->view->success = false;
      $this->view->error = $translate->_('Not a valid song');
      return;
    }

    //CHECK METHOD
    if (!$this->getRequest()->isPost()) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Invalid request method');
      return;
    }

    //GET SONG
    $song = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist_song');

    //GET PLAYLIST
    $playlist = $song->getParent();

    //GET SITEPAGE ITEM
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $playlist->page_id);

    //CHECK SONGS / PLAYLIST
    if (!$song || !$playlist) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Invalid playlist');
      return;
    }

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

    //PROCESS
    $db = $song->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      //SET SONG TITLE
      $song->setTitle($this->_getParam('title'));
      $db->commit();
    } catch (Exception $e) {
      $db->rollback();
      $this->view->success = false;
      $this->view->error = $translate->_('Unknown database error');
      throw $e;
    }

    $this->view->success = true;
  }

  //ACTION FOR DELETE THE SONG
  public function deleteAction() {

    //CHECK SUBJECT
    if (!Engine_Api::_()->core()->hasSubject('sitepagemusic_playlist_song')) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Not a valid song');
      return;
    }

    //CHECK METHOD
    if (!$this->getRequest()->isPost()) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Invalid request method');
      return;
    }

    //GET SONG / PLAYLIST
    $song = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist_song');
    $playlist = $song->getParent();

    //GET SITEPAGE ITEM
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $playlist->page_id);

    //CHECK SONG / PLAYLIST
    if (!$song || !$playlist) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Invalid playlist');
      return;
    }

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
    } else {
      $can_edit = 1;
    }
    //END MANAGE-ADMIN CHECK   
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
      //DELETE SONG
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

  //ACTION FOR TALLY THE SONG MEANS INCREMENT IN THE VIEWS OF PLAYLIST AND SONGS
  public function tallyAction() {

    //CHECK SUBJECT
    if (!Engine_Api::_()->core()->hasSubject('sitepagemusic_playlist_song')) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('Not a valid song');
      return;
    }

    //GET SONG / PLAYLIST
    $song = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist_song');
    $playlist = $song->getParent();

    //CHECK SONG / PLAYLIST
    if (!$song || !$playlist) {
      $this->view->success = false;
      $this->view->error = $this->view->translate('invalid song_id');
      return;
    }

    //GET DB
    $db = $song->getTable()->getAdapter();
    $db->beginTransaction();

    try {
      //INCREMENT FOR SONGS
      $song->play_count++;
      $song->save();

      //INCREMENT FOR PLAYLIST
      $playlist->play_count++;
      $playlist->save();

      //COMMIT
      $db->commit();
    } catch (Exception $e) {
      $db->rollback();
      $this->view->success = false;
      return;
    }

    $this->view->success = true;
    $this->view->song = $song->toArray();
    $this->view->play_count = $song->playCountLanguagified();
  }

  //ACTION FOR APPEND THE SONGS TO THE PLAYLIST OR CREATE NEW PLAYLIST 

  public function appendAction() {
    //CHECK AUTH
    if (!$this->_helper->requireUser()->isValid()) {
      return;
    }

    //CHECK CREATE PRIVACY FOR MUSIC
    if (!$this->_helper->requireAuth()->setAuthParams('music_playlist', null, 'create')->isValid()) {
      return;
    }

    //GET SONG ITEM
    $songItem = Engine_Api::_()->getItem('sitepagemusic_playlist_song', $this->_getParam('song_id'));

    $viewer = Engine_Api::_()->user()->getViewer();


    //GET FORM
    $this->view->form = $form = new Sitepagemusic_Form_Song_Append();

    //POPULATE FORM
    $playlistTable = Engine_Api::_()->getDbtable('playlists', 'music');
    $playlists = $playlistTable->select()
            ->from($playlistTable, array('playlist_id', 'title'))
            ->where('owner_id = ?', $viewer->getIdentity())
            ->query()
            ->fetchAll();
    foreach ($playlists as $playlist) {
      if ($playlist['playlist_id'] != $songItem->playlist_id) {
        $form->playlist_id->addMultiOption($playlist['playlist_id'], $playlist['title']);
      }
    }

    //CHECK METHOD / DATA
    if (!$this->getRequest()->isPost()) {
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }


    //GET VALUES
    $values = $form->getValues();
    if (empty($values['playlist_id']) && empty($values['title'])) {
      return $form->addError('Please enter a title or select a playlist.');
    }


    //PROCESS
    $db = $songItem->getTable()->getAdapter();
    $db->beginTransaction();

    try {

      //Existing Playlist
      if (!empty($values['playlist_id'])) {
        $playlist = Engine_Api::_()->getItem('music_playlist', $values['playlist_id']);
        $songTable = Engine_Api::_()->getDbTable('PlaylistSongs', 'music');
        // ALREADY EXISTS IN PLAYLIST
        $select = $songTable->select()
                ->from($songTable->info('name'), 'song_id')
                ->where('playlist_id = ?', $playlist->getIdentity())
                ->where('file_id = ?', $songItem->playlist_file_id)
                ->limit(1);

        $alreadyExists = $songTable->fetchRow($select);

        if ($alreadyExists) {
          return $form->addError('This playlist already has this song.');
        }
      }

      // NEW PLAYLIST
      else {
        $playlist = $playlistTable->createRow();
        $playlist->title = trim($values['title']);
        $playlist->owner_id = $viewer->getIdentity();
        $playlist->search = 1;
        $id = $playlist->save();

        // ADD ACTION AND ATTACHMENTS
        $auth = Engine_Api::_()->authorization()->context;
        $auth->setAllowed($playlist, 'registered', 'comment', true);
        foreach (array('everyone', 'registered', 'member') as $role) {
          $auth->setAllowed($playlist, $role, 'view', true);
        }

        // ONLY CREATE ACTIVITY FEED ITEM IF "SEARCH" IS CHECKED
        if ($playlist->search) {
          $activity = Engine_Api::_()->getDbtable('actions', 'activity');
          $action = $activity->addActivity(Engine_Api::_()->user()->getViewer(), $playlist, 'music_playlist_new');
          if ($action) {
            $activity->attachActivity($action, $playlist);
          }
        }
      }

      $file_id = $songItem->playlist_file_id;
      if ($file_id == 0) {
        $storageItem = Engine_Api::_()->getItem('storage_file', $songItem->file_id);
        $song = Engine_Api::_()->getApi('core', 'music')->createSong($storageItem->storage_path);
        $latestSong = Engine_Api::_()->getItem('storage_file', $song->file_id);
        $latestSong->mime_major = $storageItem->mime_major;
        $latestSong->mime_minor = $storageItem->mime_minor;
        $latestSong->name = $storageItem->name;
        $latestSong->save();

        $songItem->playlist_file_id = $song->file_id;
        $songItem->save();
        $file_id = $songItem->playlist_file_id;
      }

      $playlist->addSong($file_id);

      // RESPONSE
      $this->view->success = $success = true;
      $this->view->message = $message = $this->view->translate('Your changes have been saved.');
      $this->view->playlist = $playlist;
      $db->commit();
    } catch (Sitepagemusic_Model_Exception $e) {
      $this->view->success = $success = false;
      $this->view->error = $error = $this->view->translate($e->getMessage());
      $form->addError($e->getMessage());

      $db->rollback();
    } catch (Exception $e) {
      $this->view->success = $success = false;
      $db->rollback();
    }


    if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
      if (isset($success)) {
        if ($success) {
          $showMessage = $message;
        } elseif (!empty($error)) {
          $showMessage = $error;
        } else {
          $showMessage = 'There was an error processing your request. Please try again later.';
        }
        return $this->_forwardCustom('success', 'utility', 'core', array(
                    'smoothboxClose' => 2,
                    'messages' => array(Zend_Registry::get('Zend_Translate')->_($showMessage)),
                    'layout' => 'default-simple',
                ));
      }
    }
  }

  //ACTION FOR UPLOAD THE SONGS TO THE PLAYLIST
  public function uploadAction() {

    global $upload_music;
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
    $values = $this->getRequest()->getPost();
    if (empty($values['Filename']) || empty($_FILES['Filedata'])) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('No file');
      return;
    }

		if( empty($upload_music) ) {
			$this->view->status = false;
			$this->view->error = ' ';
			return;
		}


    //GET DB
    $db = Engine_Api::_()->getDbtable('playlists', 'sitepagemusic')->getAdapter();
    $db->beginTransaction();

    try {
      $song = Engine_Api::_()->getDbTable('playlistSongs', 'sitepagemusic')->createSong($_FILES['Filedata']);
      $this->view->status = true;
      $this->view->song = $song;
      $this->view->song_id = $song->getIdentity();
      $this->view->song_url = $song->getHref();
      $db->commit();
    } catch (Sitepagemusic_Model_Exception $e) {
      $db->rollback();
      $this->view->status = false;
      $this->view->message = $this->view->translate($e->getMessage());
    } catch (Exception $e) {
      $db->rollback();
      $this->view->status = false;
      $this->view->message = $this->view->translate('Upload failed by database query');
      throw $e;
    }
  }

}

?>