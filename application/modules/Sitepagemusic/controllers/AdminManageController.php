<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminmanageController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_AdminManageController extends Core_Controller_Action_Admin {

  //ACTION FOR MANAGE PLAYLISTS
  public function indexAction() {

    //GET NAVIGATION
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sitepagemusic_admin_main', array(), 'sitepagemusic_admin_main_manage');

    //HIDDEN SEARCH FORM CONTAIN ORDER AND ORDER DIRECTION  
    $this->view->formFilter = $formFilter = new Sitepagemusic_Form_Admin_Manage_Filter();

    //GET CURRENT PAGE NUMBER
    $page = $this->_getParam('page', 1);

    //GET USER TABLE NAME
    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');

    //GET SITEPAGE TABLE NAME
    $tableSitepageName = Engine_Api::_()->getItemTable('sitepage_page')->info('name');

    //GET PLAYLIST TABLE
    $tablePlaylist = Engine_Api::_()->getDbtable('playlists', 'sitepagemusic');
    $tablePlaylistName = $tablePlaylist->info('name');
    $select = $tablePlaylist->select()
            ->setIntegrityCheck(false)
            ->from($tablePlaylistName, array('playlist_id', 'page_id', 'owner_id', 'title', 'creation_date', 'view_count', 'comment_count', 'play_count', 'like_count', 'profile','featured'))
            ->join($tableUserName, "$tablePlaylistName.owner_id = $tableUserName.user_id", 'username')
            ->join($tableSitepageName, "$tablePlaylistName.page_id = $tableSitepageName.page_id", 'title AS sitepage_title');

    //SET VALUES
    $values = array();
    if ($formFilter->isValid($this->_getAllParams())) {
      $values = $formFilter->getValues();
    }
    foreach ($values as $key => $value) {
      if (null === $value) {
        unset($values[$key]);
      }
    }
    $values = array_merge(array('order' => 'playlist_id', 'order_direction' => 'DESC'), $values);

    //ASSIGN VALUES TO THE TPL
    $this->view->assign($values);
    $select->order((!empty($values['order']) ? $values['order'] : 'playlist_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
		include APPLICATION_PATH . '/application/modules/Sitepagemusic/controllers/license/license2.php';
  }

  //ACTION FOR DELETE THE PLAYLIST
  public function deleteAction() {

    //IN SMOOTHBOX
    $this->_helper->layout->setLayout('admin-simple');

    //GET PLAYLIST ID
    $this->view->playlist_id = $playlistId = $this->_getParam('id');

    //CHECK FORM VALIDATION
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        //GET PLAYLIST
        $playlist = Engine_Api::_()->getItem('sitepagemusic_playlist', $playlistId);

        //DELETEING THE SONGS RELATED TO THE PLAYLISTS
        foreach ($playlist->getSongs() as $song)
          $song->deleteUnused();

        //DELETE PLAYLISTS
        $playlist->delete();

        //COMMIT
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('')
      ));
    }

    //OUTPUT
    $this->renderScript('admin-manage/delete.tpl');
  }

  //ACTION FOR MULTI DELETE PLAYLIST
  public function multiDeleteAction() {

    //GET THE PLAYLIST IDS WHICH MUSIC WE WANT TO DELETE
    $this->view->ids = $playlist_ids = $this->_getParam('ids', null);

    //COUNTING THE PLAYLIST IDS
    $this->view->count = count(explode(",", $playlist_ids));

    //IF NOT POST OR FORM NOT VALID, RETURN
    if ($this->getRequest()->isPost()) {

      //MAKING THE PLAYLIST ID ARRAY.
      $playlist_ids_array = explode(",", $playlist_ids);

      foreach ($playlist_ids_array as $playlist_id) {

        //GET THE PLAYLIST ITEM
        $playlist = Engine_Api::_()->getItem('sitepagemusic_playlist', $playlist_id);

        //IF NOT EMPTY PLAYLIST THEN WE DELETE THE ENTRIES FROM THE DATABASE
        if ($playlist) {

          foreach ($playlist->getSongs() as $song)
            $song->deleteUnused();

          //DELETE PLAYLIST
          $playlist->delete();
        }
      }
      //REDIRECTING TO THE MANAGE PLAYLIST PAGE
      $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }
  }

  //ACTION FOR MAKE MUSIC FEATURED AND REMOVE FEATURED MUSIC 
  public function featuredmusicAction() {

    //GET OFFER ID
    $musicId = $this->_getParam('id');
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $sitepagemusic = Engine_Api::_()->getItem('sitepagemusic_playlist', $musicId);
      if ($sitepagemusic->featured == 0) {
        $sitepagemusic->featured = 1;
      } else {
        $sitepagemusic->featured = 0;
      }
      $sitepagemusic->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->_redirect('admin/sitepagemusic/manage');
  }

}

?>