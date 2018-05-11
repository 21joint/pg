<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Playlist.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Model_Playlist extends Core_Model_Item_Abstract {

  /**
   * Return page object
   *
   * @return page object
   * */
  public function getParentType() {
    return Engine_Api::_()->getItem('sitepage_page', $this->page_id);
  }
  
	public function getMediaType() {
		return 'playlist';
	}
	
  /**
   * Return a title
   *
   * @return title
   * */
  public function getTitle() {
    
    if(!empty($this->title)) {
      return $this->title;
    }
  }

  /**
   * Return a truncate ownername
   *
   * @param int ownername 
   * @return truncate ownername
   * */
  public function truncateOwner($owner_name) {
    
    $tmpBody = strip_tags($owner_name);
    return ( Engine_String::strlen($tmpBody) > 10 ? Engine_String::substr($tmpBody, 0, 10) . '..' : $tmpBody );
  }

  /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
   */
  public function getHref($params = array()) {
    
    $slug = $this->getSlug();
    $tab_id='';
    $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
		if(!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
			$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagemusic.sitemobile-profile-sitepagemusic', $this->page_id, $layout);
		} else {
			$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagemusic.profile-sitepagemusic', $this->page_id, $layout);
		}
    $params = array_merge(array(
        'route' => 'sitepagemusic_playlist_view',
        'reset' => true,
        'playlist_id' => $this->playlist_id,
        'page_id' => $this->page_id,
        'tab' => $tab_id,
        'slug' => $slug,
            ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
            ->assemble($params, $route, $reset);
  }

  /**
   * Make format for activity feed
   * 
   * @param char $view 
   * @param array $params  
   * @return activity feed content
   */
  public function getRichContent($view = false, $params = array()) {
    
    $musicEmbedded = '';
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $this->page_id);

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $can_edit = 0;
    } else {
      $can_edit = 1;
    }
    //END MANAGE-ADMIN CHECK

    if (!$view) {
      $title = "<a href='" . $this->getHref($params) . "' class='sea_add_tooltip_link' rel='" . $this->getType().' '.$this->getIdentity()."' >$this->title</a>";
      $desc = strip_tags($this->description);
      $desc = "<div class='music_desc'>" .  $title  . '<br />'. (Engine_String::strlen($desc) > 255 ? Engine_String::substr($desc, 0, 255) . '...' : $desc) . "</div>";
      $zview = Zend_Registry::get('Zend_View');
      $zview->playlist = $this;
      $zview->songs = $this->getSongs();
      $zview->short_player = true;
      $zview->hideStats = true;
      $zview->page_id = $this->page_id;
      $zview->can_edit = $can_edit;
      $musicEmbedded = $desc . $zview->render('application/modules/Sitepagemusic/views/scripts/_Player.tpl');
    }

    if (!count($zview->songs) && 'production' == APPLICATION_ENV) {
      throw new Exception('Empty playlists show not be shown');
    }

    return $musicEmbedded;
  }

  /**
   * Gets a proxy object for the comment handler
   *
   * @return Engine_ProxyObject
   * */
  public function comments() {
    
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
  }

  /**
   * Gets a proxy object for the like handler
   *
   * @return Engine_ProxyObject
   * */
  public function likes() {
    
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
  }

  /**
   * Return playlist owner object
   *
   * @return owner object
   * */
  public function getParent($recurseType = null) {
    
    return $this->getOwner();
  }

  /**
   * Return songs
   *
   * @param int $file_id 
   * @return Zend_Db_Table_Select
   * */
  public function getSongs($file_id=null) {
    
    $table = Engine_Api::_()->getDbtable('playlistSongs', 'sitepagemusic');
    $select = $table->select()
            ->where('playlist_id = ?', $this->getIdentity())
            ->order('order ASC');
    if (!empty($file_id))
      $select->where('file_id = ?', $file_id);

    return $table->fetchAll($select);
  }

  /**
   * Gets song
   *
   * @param int $file_id
   * @return songs row
   */
  public function getSong($file_id) {
    
    return Engine_Api::_()->getDbtable('playlistSongs', 'sitepagemusic')->fetchRow(array(
        'playlist_id = ?' => $this->getIdentity(),
        'file_id = ?' => $file_id,
    ));
  }

  /**
   * Add song
   *
   * @param int $file_id
   */
  public function addSong($file_id) { 
    
    if ($file_id instanceof Sitepagemusic_Model_PlaylistSong) {
      $file_id = $file_id->file_id;
    }
    if ($file_id instanceof Storage_Model_File) {
      $file = $file_id;
    } else {
      $file = Engine_Api::_()->getItem('storage_file', $file_id);
    }

    if ($file) {
      $playlist_song = Engine_Api::_()->getDbtable('playlistSongs', 'sitepagemusic')->createRow();
      $playlist_song->playlist_id = $this->getIdentity();
      $playlist_song->file_id = $file->getIdentity();
      $playlist_song->title = preg_replace('/\.(mp3|m4a|aac|mp4)$/i', '', $file->name);
      $playlist_song->order = count($this->getSongs());
      $playlist_song->save();
      return $playlist_song;
    }

    return false;
  }

  /**
   * Set a photo
   *
   * @param array photo
   * @return photo object
   */
  public function setPhoto($photo) {
    
    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
    } else if (is_array($photo) && !empty($photo['tmp_name'])) {
      $file = $photo['tmp_name'];
    } else if (is_string($photo) && file_exists($photo)) {
      $file = $photo;
    } else {
      throw new Sitepagemusic_Model_Exception('Invalid argument passed to setPhoto: ' . print_r($photo, 1));
    }

    $name = basename($file);
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => 'sitepagemusic_playlist',
        'parent_id' => $this->getIdentity()
    );

    $storage = Engine_Api::_()->storage();

    // Add autorotation for uploded images. It will work only for SocialEngine-4.8.9 Or more then.
    $usingLessVersion = Engine_Api::_()->seaocore()->usingLessVersion('core', '4.8.9');
    if(!empty($usingLessVersion)) {
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(720, 720)
              ->write($path . '/m_' . $name)
              ->destroy();

      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(200, 400)
              ->write($path . '/p_' . $name)
              ->destroy();

      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(140, 160)
              ->write($path . '/in_' . $name)
              ->destroy();
    }else {
      $image = Engine_Image::factory();
      $image->open($file)
              ->autoRotate()
              ->resize(720, 720)
              ->write($path . '/m_' . $name)
              ->destroy();

      $image = Engine_Image::factory();
      $image->open($file)
              ->autoRotate()
              ->resize(200, 400)
              ->write($path . '/p_' . $name)
              ->destroy();

      $image = Engine_Image::factory();
      $image->open($file)
              ->autoRotate()
              ->resize(140, 160)
              ->write($path . '/in_' . $name)
              ->destroy();
    }

    $image = Engine_Image::factory();
    $image->open($file);

    $size = min($image->height, $image->width);
    $x = ($image->width - $size) / 2;
    $y = ($image->height - $size) / 2;

    $image->resample($x, $y, $size, $size, 48, 48)
            ->write($path . '/is_' . $name)
            ->destroy();

    $iMain = $storage->create($path . '/m_' . $name, $params);
    $iProfile = $storage->create($path . '/p_' . $name, $params);
    $iIconNormal = $storage->create($path . '/in_' . $name, $params);
    $iSquare = $storage->create($path . '/is_' . $name, $params);

    $iMain->bridge($iProfile, 'thumb.profile');
    $iMain->bridge($iIconNormal, 'thumb.normal');
    $iMain->bridge($iSquare, 'thumb.icon');

    @unlink($path . '/p_' . $name);
    @unlink($path . '/m_' . $name);
    @unlink($path . '/in_' . $name);
    @unlink($path . '/is_' . $name);

    $this->modified_date = date('Y-m-d H:i:s');
    $this->photo_id = $iMain->getIdentity();
    $this->save();

    return $this;
  }
  
  public function setProfile()
  {
    $table = Engine_Api::_()->getDbtable('playlists', 'sitepagemusic')->update(array(
      'profile' => 0,
    ), array(
      'owner_id = ?' => $this->owner_id,
			'page_id = ?' => $this->page_id,
      'playlist_id != '.$this->getIdentity(),
    ));
    $this->profile = !$this->profile;
    $this->save();
  }  
  
  protected function _delete() {
    // Delete create activity feed of note before delete note 
    Engine_Api::_()->getApi('subCore', 'sitepage')->deleteCreateActivityOfExtensionsItem($this, array('sitepagemusic_playlist_new', 'sitepagemusic_admin_new'));
    parent::_delete();
  }
 
  /**
   * Gets an absolute Photo URL to the page to view this item
   *
   * @param array $type
   * @return string
   */
  public function getPhotoUrl($type = null) {

    if (empty($this->photo_id)) {
      return null;
    }
    $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->photo_id, $type);
    if (!$file) {
      return null;
    }
    return $file->map();
  }
  
}

?>