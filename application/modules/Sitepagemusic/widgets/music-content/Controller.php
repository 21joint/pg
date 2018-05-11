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
class Sitepagemusic_Widget_MusicContentController extends Seaocore_Content_Widget_Abstract {

  public function indexAction() {

     //GET PLAYLIST ID AND OBJECT
    $playlist_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('playlist_id', $this->_getParam('playlist_id', null));
    $playlist = Engine_Api::_()->getItem('sitepagemusic_playlist', $playlist_id);
 //SET LAYOUT
    if ($this->_getParam('popout')) {
      $this->view->popout = true;
      $this->_helper->layout->setLayout('default-simple');
    }

//     //CHECK SUBJECT
//     if (!$this->_helper->requireSubject()->isValid()) {
//       return;
//     }

    //GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $this->view->viewer_id = $viewer_id;
    //GET PLAYLIST
    $this->view->playlist = $playlist;

    //SEND TAB ID TO TPL FILE
    $this->view->tab_selected_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('tab');

    //IF THERE IS NO PLAYLIST THEN NO RENDER
    if (empty($playlist)) {
      return $this->setNoRender();
    }

    //GET SITEPAGE ITEM
    $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $playlist->page_id);

    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'smcreate');
    if (empty($isManageAdmin)) {
      $this->view->can_create = 0;
    } else {
      $this->view->can_create = 1;
    }

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
    if (empty($isManageAdmin)) {
      return $this->setNoRender();
    }

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'comment');
    if (empty($isManageAdmin)) {
      $this->view->can_comment = 0;
    } else {
      $this->view->can_comment = 1;
    }

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
    if (empty($isManageAdmin)) {
      $this->view->can_edit = $can_edit = 0;
    } else {
      $this->view->can_edit = $can_edit = 1;
    }

    $this->view->allowView = false;
    if (!empty($viewer_id) && $viewer->level_id == 1) {
      $auth = Engine_Api::_()->authorization()->context;
      $this->view->allowView = $auth->isAllowed($sitepage, 'everyone', 'view') === 1 ? true : false ||$auth->isAllowed($sitepage, 'registered', 'view') === 1 ? true : false;
    } 

    //END MANAGE-ADMIN CHECK
    //CHECKING THE USER HAVE THE PERMISSION TO VIEW THE MUSIC OR NOT
    if ($viewer->getIdentity() != $playlist->owner_id && $can_edit != 1 && ($playlist->search != 1)) {
      return $this->setNoRender();
    }

    //INCREMENT VIEW COUNT  
    if (!$playlist->getOwner()->isSelf($viewer)) {
      $playlist->view_count++;
      $playlist->save();
    }

    // START: "SUGGEST TO FRIENDS" LINK WORK.
    $page_flag = 0;
    $is_suggestion_enabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('suggestion');
    $is_moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepage');
    $isModuleInfo = Engine_Api::_()->getDbtable('modules', 'core')->getModule('suggestion');
    $isSupport = Engine_Api::_()->getApi('suggestion', 'sitepage')->isSupport();
		if( !empty($is_suggestion_enabled) ) {
			// HERE WE ARE DELETE THIS POLL SUGGESTION IF VIEWER HAVE.
			if (!empty($is_moduleEnabled)) {
				Engine_Api::_()->getApi('suggestion', 'sitepage')->deleteSuggestion($viewer->getIdentity(), 'page_music', $playlist->playlist_id, 'page_music_suggestion');
			}

			$SuggVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule('suggestion')->version;
			$versionStatus = strcasecmp($SuggVersion, '4.1.7p1');
			if( $versionStatus >= 0 ){ 
				$modContentObj = Engine_Api::_()->suggestion()->getSuggestedFriend('sitepagemusic', $playlist->playlist_id, 1,null);
				if (!empty($modContentObj)) {
					$contentCreatePopup = @COUNT($modContentObj);
				}
			}

			if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.package.enable', 1)) {
				if ($sitepage->expiration_date <= date("Y-m-d H:i:s")) {
					$page_flag = 1;
				}
			}

			if (!empty($viewer_id) && !empty($contentCreatePopup) && !empty($isSupport) && empty($sitepage->closed) && !empty($sitepage->approved) && empty($sitepage->declined) && !empty($sitepage->draft) && empty($page_flag) && !empty($is_suggestion_enabled) && ($isModuleInfo->version >= '4.1.6p1')) {
				$this->view->musicSuggLink = Engine_Api::_()->suggestion()->getModSettings('sitepage', 'music_sugg_link');
			}
			// END: "SUGGEST TO FRIENDS" LINE WORK.
		}
  }

}
?>