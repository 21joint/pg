<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: MobiController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_MobiController extends Core_Controller_Action_Standard {

  public function init() {
  	
    //GET VIEWER INFORMATION
    $this->view->viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    //GET PLAYLIST SUBJECT
    if (null !== ($playlist_id = $this->_getParam('playlist_id')) &&
            null !== ($playlist = Engine_Api::_()->getItem('sitepagemusic_playlist', $playlist_id)) &&
            $playlist instanceof Sitepagemusic_Model_Playlist &&
            !Engine_Api::_()->core()->hasSubject()) {
      Engine_Api::_()->core()->setSubject($playlist);
    }

    //GET PAGE ID
    $page_id = $this->_getParam('page_id');

    //PACKAGE BASE PRIYACY START
    if (!empty($page_id)) {
      $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
      if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
        if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage->package_id, "modules", "sitepagemusic")) {
          return $this->_forward('requireauth', 'error', 'core');
        }
      } else {
        $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage, 'smcreate');
        if (empty($isPageOwnerAllow)) {
          return $this->_forward('requireauth', 'error', 'core');
        }
      }
    }
    //PACKAGE BASE PRIYACY END     
  }

  //ACTION FOR VIEW THE MUSIC
  public function viewAction() {
    
		//CHECK THE VERSION OF THE CORE MODULE
		$coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
		$coreversion = $coremodule->version;
		if ($coreversion < '4.1.0') {
			$this->_helper->content->render();
		} else {
			$this->_helper->content
							->setNoRender()
							->setEnabled()
			;
		}
  }
}

?>