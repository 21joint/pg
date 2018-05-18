<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Playlists.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Model_DbTable_Playlists extends Engine_Db_Table {

  protected $_rowClass = 'Sitepagemusic_Model_Playlist';

  /**
   * Gets playlist data
   *
   * @param array $params
   * @return Zend_Db_Table_Select
   */
  public function widgetMusicList($params = array(),$musicType = null) {

    $tableplaylist = $this->info('name');
    if(isset($params['profile_page_widget']) && !empty($params['profile_page_widget'])) {
      $select = $this->select()
                ->from($tableplaylist, array('playlist_id', 'page_id', 'owner_id', 'title', 'creation_date', 'view_count', 'comment_count', 'like_count', 'creation_date', 'modified_date', 'photo_id', 'play_count', 'profile','description','featured'));
			if (isset($params['page_id']) && !empty($params['page_id'])) {
				$select = $select->where($tableplaylist . '.page_id = ?', $params['page_id']);
			}
			if (isset($params['profile']) && !empty($params['profile'])) {
				$select = $select->where($this->info('name') . '.profile = ?', $params['profile']);
			}
      if (isset($params['zero_count']) && !empty($params['zero_count'])) {
				$select = $select->where($tableplaylist . '.' . $params['zero_count'] . '!= ?', 0);
			}
      $select = $select->order('playlist_id DESC')
								->limit($params['limit']);
    }
    else {
			$tablePage = Engine_Api::_()->getDbtable('pages', 'sitepage');
			$tablePageName = $tablePage->info('name');
			$pagePackagesTable = Engine_Api::_()->getDbtable('packages', 'sitepage');
			$pagePackageTableName = $pagePackagesTable->info('name');
			$select = $this->select()
											->setIntegrityCheck(false)
											->from($tableplaylist, array('playlist_id', 'page_id', 'owner_id', 'title', 'creation_date', 'view_count', 'comment_count', 'like_count', 'creation_date', 'modified_date', 'photo_id', 'play_count', 'profile','description','featured'))
											->joinLeft($tablePageName, "$tablePageName.page_id = $tableplaylist.page_id", array('page_id', 'title AS page_title', 'closed', 'approved', 'declined', 'draft', 'expiration_date', 'owner_id', 'photo_id as page_photo_id'))
											->join($pagePackageTableName, "$pagePackageTableName.package_id = $tablePageName.package_id",array('package_id', 'price'))
											->where($tableplaylist . '.search = ?', '1');

			if (isset($params['zero_count']) && !empty($params['zero_count'])) {
				$select = $select->where($this->info('name') . '.' . $params['zero_count'] . '!= ?', 0);
			}

			if (isset($params['page_id']) && !empty($params['page_id'])) {
				$select = $select->where($this->info('name') . '.page_id = ?', $params['page_id']);
			}

			if (isset($params['profile']) && !empty($params['profile'])) {
				$select = $select->where($this->info('name') . '.profile = ?', $params['profile']);
			}

			if (isset($params['orderby']) && !empty($params['orderby'])) {
				$select = $select->order($this->info('name') . '.' . $params['orderby']);
			}

			if ((isset($params['orderby_second']) && !empty($params['orderby_second']))) {
				$select = $select->order($this->info('name') . '.' . $params['orderby_second']);
			}

			if (isset($params['limit']) && !empty($params['limit'])) {
				if (!isset($params['start_index']))
					$params['start_index'] = 0;
				$select->limit($params['limit'], $params['start_index']);
			}

// 			if (isset($params['recent_musics']) && !empty($params['recent_musics'])) {
// 				$select = $select->where($tablePageName . '.search = ?', '1');
// 			}
// 		
			/*if (isset($params['comment_musics']) && !empty($params['comment_musics'])) {
				$select = $select->where($tablePageName . '.search = ?', '1')
												->where($this->info('name') . '.comment_count != ?', 0);
			} */  

			/*if (isset($params['like_musics']) && !empty($params['like_musics'])) {
				$select = $select->where($tablePageName . '.search = ?', '1')
												->where($this->info('name') . '.like_count != ?', 0);
			} */ 

			if (isset($params['featured']) && !empty($params['featured'])) {
				$select = $select->where($tableplaylist . '.featured = ?', '1');
			}

			if ($musicType == 'sponsored') {
					$select->where($pagePackageTableName . '.price != ?', '0.00');
					$select->order($pagePackageTableName . '.price' . ' DESC');
					$select ->limit($params['limit']);
			}
			
			if (!empty($params['title'])) {

				$select->where($tablePageName . ".title LIKE ? ", '%' . $params['title'] . '%');
			}

			if (!empty($params['search_music'])) {

				$select->where($this->info('name') . ".title LIKE ? ", '%' . $params['search_music'] . '%');
			}

				$viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
				if(isset($params['show']) && $params['show'] == 'my_music') {
					$select->where($tableplaylist . '.owner_id = ?', $viewer_id);
				}
				elseif ((isset($params['show']) && $params['show'] == 'sponsored music') || !empty($params['sponsoredmusic'])) {
						
						$select->where($pagePackageTableName . '.price != ?', '0.00');
						$select->order($pagePackageTableName . '.price' . ' DESC');
				}
				elseif ((isset($params['show']) && $params['show'] == 'featured')) {
						
						$select->where($tableplaylist . '.featured = ?', 1);
						$select->order($tableplaylist . '.creation_date' . ' DESC');
				}
				elseif (isset($params['show']) && $params['show'] == 'Networks') {
						$select = $tablePage->getNetworkBaseSql($select, array('browse_network' => 1));

				}
				elseif (isset($params['show']) && $params['show'] == 'my_like') {
					$likeTableName = Engine_Api::_()->getDbtable('likes', 'core')->info('name');
					$viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
					$select
								->join($likeTableName, "$likeTableName.resource_id = $tablePageName.page_id")
								->where($likeTableName . '.poster_type = ?', 'user')
								->where($likeTableName . '.poster_id = ?', $viewer_id)
								->where($likeTableName . '.resource_type = ?', 'sitepage_page');
				}
		
				if (isset($params['orderby_browse']) && $params['orderby_browse'] == 'view_count') {
					$select = $select
													->order($tableplaylist .'.view_count DESC')
													->order($tableplaylist .'.creation_date DESC');
				} elseif ((isset($params['orderby_browse']) && $params['orderby_browse'] == 'comment_count') || !empty($params['commentedmusic'])) {
					$select = $select
													->order($tableplaylist .'.comment_count DESC')
													->order($tableplaylist .'.creation_date DESC');
				} elseif ((isset($params['orderby_browse']) && $params['orderby_browse'] == 'like_count') || !empty($params['likedmusic'])) {
					$select = $select
													->order($tableplaylist .'.like_count DESC')
													->order($tableplaylist .'.creation_date DESC');
				} 
				elseif ((isset($params['orderby_browse']) && $params['orderby_browse'] == 'play_count') || !empty($params['popularmusic'])) {
					$select = $select
													->order($tableplaylist .'.play_count DESC')
													->order($tableplaylist .'.creation_date DESC');
				} 
			
			if (!empty($params['category'])) {
				$select->where($tablePageName . '.category_id = ?', $params['category']);
			}

			if (!empty($params['category_id'])) {
				$select->where($tablePageName . '.category_id = ?', $params['category_id']);
			}

			if (!empty($params['subcategory'])) {
				$select->where($tablePageName . '.subcategory_id = ?', $params['subcategory']);
			}

			if (!empty($params['subcategory_id'])) {
				$select->where($tablePageName . '.subcategory_id = ?', $params['subcategory_id']);
			}

			if (!empty($params['subsubcategory'])) {
				$select->where($tablePageName . '.subsubcategory_id = ?', $params['subsubcategory']);
			}

			if (!empty($params['subsubcategory_id'])) {
				$select->where($tablePageName . '.subsubcategory_id = ?', $params['subsubcategory_id']);
			}
			
			if (isset($params['feature_musics']) && !empty($params['feature_musics'])) {
				$select = $select->where($tableplaylist . '.featured = ?', '1');
			}

			if(empty($params['orderby_browse'])) {
				$order = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.order', 1);
				switch ($order) {
					case "1":
						$select->order($tableplaylist . '.creation_date DESC');
						break;
					case "2":
						$select->order($tableplaylist . '.title');
						break;
					case "3":
						$select->order($tableplaylist . '.featured' . ' DESC');
						break;
					case "4":
						$select->order($pagePackageTableName . '.price' . ' DESC');
						break;
					case "5":
						$select->order($tableplaylist . '.featured' . ' DESC');
						$select->order($pagePackageTableName . '.price' . ' DESC');
						break;
					case "6":
						$select->order($pagePackageTableName . '.price' . ' DESC');
						$select->order($tableplaylist . '.featured' . ' DESC');
						break;
				}
			}
			$select = $select->order('playlist_id DESC');
			$select = $select
											->where($tablePageName . '.closed = ?', '0')
											->where($tablePageName . '.approved = ?', '1')
											->where($tablePageName . '.declined = ?', '0')
											->where($tablePageName . '.draft = ?', '1');
			if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
				$select->where($tablePageName . '.expiration_date  > ?', date("Y-m-d H:i:s"));
			}

			//Start Network work
			if (!isset($params['page_id']) || empty($params['page_id'])) {
				$select = $tablePage->getNetworkBaseSql($select, array('not_groupBy' => 1, 'extension_group' => $tableplaylist . ".playlist_id"));
			}
			//End Network work
    }
    if(isset($params['music_content']) && !empty($params['music_content'])) {
      return Zend_Paginator::factory($select);
    }
    else {
			return $this->fetchAll($select);
    }
  }

  /**
   * Gets All playlists
   *
   * @param int $page_id
   * @return all the playlists
   */
  public function getPlaylists($page_id) {

    $playlists = $this->select()
                    ->from($this->info('name'), array('playlist_id', 'title'))
                    ->where('owner_id = ?', Engine_Api::_()->user()->getViewer()->getIdentity())
                    ->where('page_id =?', $page_id)
                    ->query()
                    ->fetchAll();

    return $playlists;
  }

  /**
   * Get playlists
   * @param array $params : contain desirable playlists info
   * @return  array of playlists
   */
  public function getPlaylistSelect($params = array()) {

    $tablePlaylistName = $this->info('name');
    $select = $this->select()->setIntegrityCheck(false);
    if (isset($params['show_count']) && $params['show_count'] == 1) {
      $select = $select->from($tablePlaylistName, array('COUNT(*) AS show_count'));
    } else {
      if (!empty($params['orderby'])) {
        if ($params['orderby'] == 'featured') {
        $select
								->where($tablePlaylistName . '.featured = ?', 1)
								->order('creation_date DESC');
				}
        else {
					$select->order($params['orderby'] . ' DESC');
        }
      }
      $select->order('creation_date DESC');
      $select = $select
                      ->from($tablePlaylistName, array('playlist_id', 'page_id', 'owner_id', 'title', 'view_count', 'comment_count', 'play_count', 'creation_date', 'modified_date', 'photo_id', 'search', 'description', 'like_count', 'profile','featured'))
                      ->group("$tablePlaylistName.playlist_id");
    }

    if (isset($params['page_id']) && !empty($params['page_id'])) {
      $select->where('`page_id` = ?', $params['page_id']);
    }
    if (isset($params['profile']) && !empty($params['profile'])) {
      $select->where('`profile` = ?', $params['profile']);
    }
    if (!empty($params['user_id']) && is_numeric($params['user_id'])) {
      $select->where('owner_id = ?', $params['user_id']);
    }

    if (!empty($params['user']) && $params['user'] instanceof User_Model_User) {
      $select->where('owner_id = ?', $params['user_id']->getIdentity());
    }

    if (!empty($params['users'])) {
      $str = (string) ( is_array($params['users']) ? "'" . join("', '", $params['users']) . "'" : $params['users'] );
      $select->where('owner_id in (?)', new Zend_Db_Expr($str));
    }

    if (!empty($params['owner_id']) && is_numeric($params['owner_id'])) {
      $select->where('owner_id = ?', $params['owner_id']);
    }

    if (!empty($params['search'])) {
      $select->where("title LIKE ? OR " . "description LIKE ?", '%' . $params['search'] . '%');
    }

    if (!empty($params['show_pagemusics']) && empty($params['search'])) {
      $select
              ->where($tablePlaylistName . ".search = ?", 1)
              ->orwhere($tablePlaylistName . ".owner_id = ?", $params['music_owner_id']);
    }

    if (!empty($params['show_pagemusics']) && (!empty($params['search']))) {
      $select->where("($tablePlaylistName.search = 1) OR ($tablePlaylistName.owner_id = " . $params['music_owner_id'] . ")");
    }

    if (isset($params['page_id']) && !empty($params['page_id'])) {
      $select->where('`page_id` = ?', $params['page_id']);
    }
    
    if (isset($params['searchable']) && !empty($params['searchable'])) {
      $select->where('`search` = ?', $params['searchable']);
    }    

    return $select;
  }

  /**
   * Get playlist detail
   *
   * @param array $params : contain desirable playlist info
   * @return  object of playlist
   */
  public function getPlaylistPaginator($params = array()) {

    $paginator = Zend_Paginator::factory($this->getPlaylistSelect($params));
    if (!empty($params['page'])) {
      $paginator->setCurrentPageNumber($params['page']);
    }
    if (!empty($params['limit'])) {
      $paginator->setItemCountPerPage($params['limit']);
    }
    return $paginator;
  }

  public function getSpecialPlaylist(Sitepage_Model_Page $page, User_Model_User $user, $type) {
    $allowedTypes = array('profile', 'wall', 'message');
    if (!in_array($type, $allowedTypes)) {
      throw new Album_Model_Exception('Unknown special page album type');
    }
    //$typeIndex = array_search($type, $allowedTypes);

    $select = $this->select()
                    // ->where('owner_type = ?', $page->getType())
                    ->where('page_id = ?', $page->getIdentity())
                  //  ->where('owner_id = ?', $user->getIdentity())
                    ->where('special = ?', $type)
                    ->order('playlist_id ASC')
                    ->limit(1);

    $playlist = $this->fetchRow($select);

    // Create if it doesn't exist yet
    if (null === $playlist) {
      $translate = Zend_Registry::get('Zend_Translate');

      $playlist = $this->createRow();
      $playlist->page_id = $page->getIdentity();
      $playlist->owner_id = $page->owner_id;
      $playlist->special = $type;

      if ($type == 'message') {
        $playlist->title = $translate->_('_SITEPAGEMUSIC_MESSAGE_PLAYLIST');
        $playlist->search = 0;
      } else {
        $playlist->title = $translate->_('_SITEPAGEMUSIC_DEFAULT_PLAYLIST');
        $playlist->search = 1;
      }

      $playlist->save();

      // Authorizations
      if ($type != 'message') {
        $auth = Engine_Api::_()->authorization()->context;
        $auth->setAllowed($playlist, 'everyone', 'view', true);
        $auth->setAllowed($playlist, 'everyone', 'comment', true);
      }
    }

    return $playlist;
  }

  /**
   * Return music of the day
   *
   * @return Zend_Db_Table_Select
   */
  public function musicOfDay() {

    //CURRENT DATE TIME
    $date = date('Y-m-d');

    //GET ITEM OF THE DAY TABLE NAME
    $musicOfTheDayTableName = Engine_Api::_()->getDbtable('itemofthedays', 'sitepage')->info('name');

		//GET PAGE TABLE NAME
		$pageTableName = Engine_Api::_()->getDbtable('pages', 'sitepage')->info('name');

    //GET MUSIC TABLE NAME
    $musicTableName = $this->info('name');

    //MAKE QUERY
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($musicTableName, array('playlist_id', 'title', 'page_id', 'owner_id', 'description','photo_id'))
                    ->join($musicOfTheDayTableName, $musicTableName . '.playlist_id = ' . $musicOfTheDayTableName . '.resource_id')
										->join($pageTableName, $musicTableName . '.page_id = ' . $pageTableName . '.page_id', array(''))
										->where($pageTableName.'.approved = ?', '1')
										->where($pageTableName.'.declined = ?', '0')
										->where($pageTableName.'.draft = ?', '1')
                    ->where('resource_type = ?', 'sitepagemusic_playlist')
                    ->where('start_date <= ?', $date)
                    ->where('end_date >= ?', $date)
                    ->order('Rand()');

		//PAGE SHOULD BE AUTHORIZED
    if (Engine_Api::_()->sitepage()->hasPackageEnable())
      $select->where($pageTableName.'.expiration_date  > ?', date("Y-m-d H:i:s"));

		//PAGE SHOULD BE AUTHORIZED
    $stusShow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.status.show', 1);
    if ($stusShow == 0) {
      $select->where($pageTableName.'.closed = ?', '0');
    }

    //RETURN RESULTS
    return $this->fetchRow($select);
  }
  
  public function topcreatorData($limit = null,$category_id) {

    //MUSIC TABLE NAME
    $musicTableName = $this->info('name');

    //PAGE TABLE
    $pageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $pageTableName = $pageTable->info('name');

    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($pageTableName, array('photo_id', 'title as sitepage_title','page_id'))
                    ->join($musicTableName, "$pageTableName.page_id = $musicTableName.page_id", array('COUNT(engine4_sitepage_pages.page_id) AS item_count'))
                    ->where($pageTableName.'.approved = ?', '1')
										->where($pageTableName.'.declined = ?', '0')
										->where($pageTableName.'.draft = ?', '1')
                    ->group($musicTableName . ".page_id")
                    ->order('item_count DESC')
                    ->limit($limit);
    if (!empty($category_id)) {
      $select->where($pageTableName . '.category_id = ?', $category_id);
    }
    return $select->query()->fetchAll();
  }

}
?>