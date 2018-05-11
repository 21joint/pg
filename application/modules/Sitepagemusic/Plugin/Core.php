<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Plugin_Core {

  public function onUserDeleteBefore($event) {
    
    $payload = $event->getPayload();
    if ($payload instanceof User_Model_User) {
      // DELETE SONGS AND PLAYLIST
      $playlistTable = Engine_Api::_()->getDbtable('playlists', 'sitepagemusic');
      $playlistSelect = $playlistTable->select()->where('owner_id = ?', $payload->getIdentity());
      foreach ($playlistTable->fetchAll($playlistSelect) as $playlist) {
        foreach ($playlist->getSongs() as $song)
          $song->deleteUnused();
        $playlist->delete();
      }
    }
  }

  public function onItemDeleteAfter($event)
	{
		$front = Zend_Controller_Front::getInstance();
		$module = $front->getRequest()->getModuleName();
		$controller = $front->getRequest()->getControllerName();
		$action = $front->getRequest()->getActionName();
		if($module == 'music' && (($controller == 'playlist' && $action == 'delete') || ($controller == 'admin-manage' && $action == 'delete') || ($controller == 'admin-manage' && $action == 'index'))) {
			$payload = $event->getPayload();
			if($payload['type']=='storage_file') {
			  $file_id = $payload['identity'];
			  if(!empty($file_id)) {
		      $playlistTable = Engine_Api::_()->getDbtable('playlistSongs', 'sitepagemusic');
		      $playlistTable->update(array('playlist_file_id'=> 0), array('playlist_file_id = ?' => $file_id));
			  } 
			}
		}
	}
}

?>