<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: PlaylistSong.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Model_PlaylistSong extends Core_Model_Item_Abstract {

  /**
   * Return a title
   *
   * @return title
   * */
  public function getTitle() {
    
    if (!empty($this->title)) {
      return $this->title;
    } else {
      $translate = Zend_Registry::get('Zend_Translate');
      return $translate->translate('Untitled Song');
    }
  }

  /**
   * Sets a title
   * 
   * @param char $newTitle
   * @return title
   * */
  public function setTitle($newTitle) {
    
    $this->title = $newTitle;
    $this->save();
    return $this;
  }

  /**
   * Gets filepath
   *
   * @return path
   * */
  public function getFilePath() {
    
    $file = Engine_Api::_()->getItem('storage_file', $this->file_id);
    if ($file) {
      return $file->map();
    }
  }

  /**
   * Gets item of playlists
   *
   * @return playlist object
   * */
  public function getParent($recurseType = null) {
    
    if($recurseType == null) $recurseType = 'sitepagemusic_playlist';
    
    return Engine_Api::_()->getItem($recurseType, $this->playlist_id);
  }

  /**
   * Make format for activity feed
   * 
   * @param char $view
   * @param array $params
   * @return activity feed content
   */
  public function getRichContent($view = false, $params = array()) {
    
    $playlist = $this->getParent();
    $musicEmbedded = '';
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $playlist->page_id);

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
    } else {
      $can_edit = 1;
    }
    //END MANAGE-ADMIN CHECK

    if ($view == false) {
      if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
      $title = "<a href='" . $this->getHref($params) . "' class='sea_add_tooltip_link' rel='" . $this->getType().' '.$this->getIdentity()."'>$playlist->title</a>";
      }else{ //Redirect to Playlist (Music) profile page.
      $title = "<a href='" . $playlist->getHref(). "' rel='" . $this->getType().' '.$this->getIdentity()."'>$playlist->title</a>";  
      }
      
      $desc = strip_tags($playlist->description);
      $desc = "<div class='music_desc'>" .  $title  . '<br />' . (Engine_String::strlen($desc) > 255 ? Engine_String::substr($desc, 0, 255) . '...' : $desc) . "</div>";
      $zview = Zend_Registry::get('Zend_View');
      $zview->playlist = $playlist;
      $zview->songs = array($this);
      $zview->short_player = true;
      $zview->page_id = $playlist->page_id;
      $zview->can_edit = $can_edit;
      
       if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
          $musicEmbedded = $desc . $zview->render('application/modules/Sitepagemusic/views/scripts/_Player.tpl');
       }else{ //Mobile Jplayer
          $musicEmbedded = $desc . $zview->render('application/modules/Sitepagemusic/views/scripts/_sitemobilePlayer.tpl');
       }
    }

    return $musicEmbedded;
  }

  /**
   * Returns languagified play count
   */
  public function playCountLanguagified() {
    
    return vsprintf(Zend_Registry::get('Zend_Translate')->_(array('%s play', '%s plays', $this->play_count)), Zend_Locale_Format::toNumber($this->play_count)
    );
  }

  /**
   * Deletes songs from the Storage engine if no other playlists are
   * using the file, and from the playlist
   *
   * @return null
   */
  public function deleteUnused() {
    
    $file = Engine_Api::_()->getItem('storage_file', $this->file_id);
    if ($file) {
      $table = Engine_Api::_()->getDbtable('playlistSongs', 'sitepagemusic');
      $count = $table->select()
              ->from($table->info('name'), 'count(*) as count')
              ->where('file_id = ?', $file->getIdentity())
              ->query()
              ->fetchColumn(0);
      if ($count <= 1) {
        try {
          $file->remove();
        } catch (Exception $e) {
          
        }
      }
    }
    $this->delete();
  }

  /**
   * Return song (we setting the playlistsong_id into the song_id)
   *
   * @return song
   * */
  public function getShortType($inflect = false) {
    
    if($inflect) return 'Song';
    
    return 'song';
  }

}

?>