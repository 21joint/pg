<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Widget_SitemobileProfileSitepagemusicController extends Engine_Content_Widget_Abstract {

  protected $_childCount;

  //ACTION FOR SHOWING MUSIC ON PAGE PROFILE PAGE
  public function indexAction() {

    //GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();

    //SET NO RENDER IF NO SUBJECT
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

    //GET VIEWER INFORMATION
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //Getting Level
    if (!empty($viewer_id)) {
      $level_id = $this->view->level_id = $viewer->level_id;
    } else {
      $level_id = $this->view->level_id = 0;
    }

    //GET SUBJECT AND PAGE ID AND PAGE OWNER ID
    $this->view->sitepageSubject = $sitepageSubject = Engine_Api::_()->core()->getSubject('sitepage_page');
		$profile_music = Zend_Registry::isRegistered('sitepagemusic_profile') ? Zend_Registry::get('sitepagemusic_profile') : null;
    $this->view->page_id = $page_id = $sitepageSubject->page_id;

    //PACKAGE BASE PRIYACY START
    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepageSubject->package_id, "modules", "sitepagemusic")) {
        return $this->setNoRender();
      }
    } else {
      $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepageSubject, 'smcreate');
      if (empty($isPageOwnerAllow)) {
        return $this->setNoRender();
      }
    }
    //PACKAGE BASE PRIYACY END
    
    //TOTAL MUSIC
    $musicCount = Engine_Api::_()->sitepage()->getTotalCount($page_id, 'sitepagemusic', 'playlists');   
    $musicCreate = Engine_Api::_()->sitepage()->isManageAdmin($sitepageSubject, 'smcreate');
       
    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepageSubject, 'view');
    if (empty($isManageAdmin)) {
      return $this->setNoRender();
    }

		if( empty($profile_music) ) {
			return $this->setNoRender();
		}

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepageSubject, 'edit');
    if (empty($isManageAdmin)) {
      $this->view->can_edit = $can_edit = 0;
    } else {
      $this->view->can_edit = $can_edit = 1;
    }

    if (empty($musicCreate) && empty($musicCount) && empty($can_edit) && !(Engine_Api::_()->sitepage()->showTabsWithoutContent())) {
      return $this->setNoRender();
    } 
    //END MANAGE-ADMIN CHECK
    

      //GET SEARCHING PARAMETERS
      $this->view->page = $page = $this->_getParam('page', 1);

      //MAKING THE SEACHING PARAMATER ARRAY
      $values = array();

        $values['orderby'] = 'creation_date';
      

      //START MANAGE-ADMIN CHECK
      $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepageSubject, 'smcreate');
      if (empty($isManageAdmin) && empty($can_edit)) {
        $this->view->can_create = 0;
      } else {
        $this->view->can_create = 1;
      }
      //END MANAGE-ADMIN CHECK
     
      $this->view->allowView = false;
			if (!empty($viewer_id) && $viewer->level_id == 1) {
				$this->view->allowView = true;
			} 

    //MAKE FEATURED OR NOT
    $this->view->canMakeFeatured = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.featured', 1);

      //FETCH MUSIC
      $values['page_id'] = $page_id;

      if ($can_edit == 1) {
        $values['show_pagemusics'] = 0;
        $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('playlists', 'sitepagemusic')->getPlaylistPaginator($values);
      } else {
        $values['show_pagemusics'] = 1;
        $values['music_owner_id'] = $viewer_id;
        $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('playlists', 'sitepagemusic')->getPlaylistPaginator($values);
      }

      $paginator->setItemCountPerPage(10)->setCurrentPageNumber($page);
      //ADD COUNT TO TITLE IF CONFIGURED
      if ($paginator->getTotalItemCount() > 0) {
        $this->_childCount = $paginator->getTotalItemCount();
      }
  }

  //RETURN THE COUNT OF THE MUSIC
  public function getChildCount() {
    return $this->_childCount;
  }

}

?>