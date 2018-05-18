<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: PlaylistSongs.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Model_DbTable_PlaylistSongs extends Engine_Db_Table {

  protected $_name = 'sitepagemusic_playlist_songs';
  protected $_primary = 'song_id';
  protected $_rowClass = 'Sitepagemusic_Model_PlaylistSong';

  /**
   * Gets song id
   *
   * @param int $playlist_id
   * @param int $file_id
   * @return songs
   */
  public function getSong($playlist_id, $file_id) {
    
    $song_id = $this->select()
            ->from($this->info('name'), 'song_id')
            ->where('playlist_id = ?', $playlist_id)
            ->where('file_id = ?', $file_id)
            ->limit(1)
            ->query()
            ->fetchColumn();
    return $song_id;
  }

  /**
   * Get song detail
   *
   * @param array $params : contain desirable song info
   * @param array $file
   * @return  object of song
   */
  public function createSong($file, $params = array()) {

  	$viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    if (is_array($file)) {
      if (!is_uploaded_file($file['tmp_name'])) {
        throw new Sitepagemusic_Model_Exception('Invalid upload or file too large');
      }
      $filename = $file['name'];
    } else if (is_string($file)) {
      $filename = $file;
    } else {
      throw new Sitepagemusic_Model_Exception('Invalid upload or file too large');
    }

    if (!preg_match('/\.(mp3|m4a|aac|mp4)$/iu', $filename)) {
      throw new Sitepagemusic_Model_Exception('Invalid file type');
    }

    $params = array_merge(array(
        'type' => 'song',
        'name' => $filename,
        'parent_type' => 'sitepagemusic_song',
        'parent_id' => $viewer_id,
        'user_id' => $viewer_id,
        'extension' => substr($filename, strrpos($filename, '.') + 1),
            ), $params);

    $song = Engine_Api::_()->storage()->create($file, $params);
    return $song;
  }
  
}

?>