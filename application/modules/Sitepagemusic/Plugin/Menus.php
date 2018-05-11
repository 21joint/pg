<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Plugin_Menus {

  public function canViewMusics() {

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.music.show.menu', 1)) {
      return false;
    }

    $table = Engine_Api::_()->getDbTable('playlists', 'sitepagemusic');
    $rName = $table->info('name');
    $table_pages = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $rName_pages = $table_pages->info('name');
    $select = $table->select()
                    ->setIntegrityCheck(false)
                    ->from($rName_pages, array('photo_id', 'title as sitepage_title'))
                    ->join($rName, $rName . '.page_id = ' . $rName_pages . '.page_id')
                    ->where($rName . '.search = ?', '1');
    $select = $select
                    ->where($rName_pages . '.closed = ?', '0')
                    ->where($rName_pages . '.approved = ?', '1')
                    ->where($rName_pages . '.search = ?', '1')
                    ->where($rName_pages . '.declined = ?', '0')
                    ->where($rName_pages . '.draft = ?', '1');
    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $select->where($rName_pages . '.expiration_date  > ?', date("Y-m-d H:i:s"));
    }
    $row = $table->fetchAll($select);
    $count = count($row);
    if (empty($count)) {
      return false;
    }
    return true;
  }

  
   //SITEMOBILE PAGE MUSIC MENUS
   public function onMenuInitialize_SitepagemusicAdd($row) { 
    $subject = Engine_Api::_()->core()->getSubject();

    $playlist_id = $subject->getIdentity();

    $playlist = Engine_Api::_()->getItem('sitepagemusic_playlist', $playlist_id);
    $page_id = $playlist->page_id;
    if (empty($playlist)) {
      return false;
    }
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'smcreate');
    if (empty($isManageAdmin)) {
      $can_create = 0;
    } else {
      $can_create = 1;
    }
    //CHECKS FOR UPLOAD MUSIC
    if (empty($can_create)) {
      return false;
    }
    return array(
        'label' => 'Upload Music',
        'route' => 'sitepagemusic_create',
        'class' => 'ui-btn-action',
        'params' => array(
            'page_id' => $page_id,
            'tab' => Zend_Controller_Front::getInstance()->getRequest()->getParam('tab')
        )
    );
  }
  
   public function onMenuInitialize_SitepagemusicEdit($row) { 
    $playlist = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist');
    if (empty($playlist)) {
      return false;
    }

    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $owner_id = $playlist->owner_id;
    $page_id = $playlist->page_id;

    $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
    } else {
      $can_edit = 1;
    }
    //CHECKS FOR EDIT PLAYLIST
    if ($owner_id != $viewer_id && empty($can_edit)) {
      return false;
    }

    return array(
        'label' => 'Edit Playlist',
        'route' => 'sitepagemusic_playlist_specific',
        'class' => 'ui-btn-action',
        'params' => array(
            'action' => 'edit',
            'page_id' => $page_id,
            'playlist_id' => $playlist->playlist_id,
            'slug' => $playlist->getTitle(),
            'tab' => Zend_Controller_Front::getInstance()->getRequest()->getParam('tab')
        )
    );
  }

  public function onMenuInitialize_SitepagemusicDelete($row) {
    $playlist = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist');
    if (empty($playlist)) {
      return false;
    }

    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $owner_id = $playlist->owner_id;
    $page_id = $playlist->page_id;

    $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
    } else {
      $can_edit = 1;
    }
    //CHECKS FOR DELETE PLAYLIST
    if ($owner_id != $viewer_id && empty($can_edit)) {
      return false;
    }
    return array(
        'label' => 'Delete Playlist',
        'route' => 'sitepagemusic_playlist_specific',
        'class' => 'ui-btn-danger smoothbox',
        'params' => array(
            'action' => 'delete',
            'page_id' => $page_id,
            'playlist_id' => $playlist->playlist_id,
            'slug' => $playlist->getTitle(),
            'tab' => Zend_Controller_Front::getInstance()->getRequest()->getParam('tab')
        )
    );
  }

  public function onMenuInitialize_SitepagemusicShare($row) {
    $playlist = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist');
    if (empty($playlist)) {
      return false;
    }
    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    //CHECKS FOR SHARE PLAYLIST
    if (empty($viewer_id)) {
      return false;
    }

    return array(
        'label' => 'Share',
        'route'=>'default',
        'class' => 'ui-btn-action smoothbox',
        'params' => array(
            'module'=>'activity',
            'controller'=>'index',
	          'action'=>'share',
            'type'=>'sitepagemusic_playlist',
            'id' => $playlist->getIdentity(),
        )
    );
  }

  public function onMenuInitialize_SitepagemusicReport($row) {
    $playlist = Engine_Api::_()->core()->getSubject('sitepagemusic_playlist');
    if (empty($playlist)) {
      return false;
    }
    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    //CHECKS FOR REPORT PLAYLIST
    if (empty($viewer_id)) {
      return false;
    }

    return array(
        'label' => 'Report',
        'route' => 'default',
        'class' => 'ui-btn-action smoothbox',
        'params' => array(
            'module' => 'core',
            'controller' => 'report',
            'action' => 'create',
            'subject' => $playlist->getGuid(),
        )
    );
  }

}