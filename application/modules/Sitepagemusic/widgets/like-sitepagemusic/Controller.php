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
class Sitepagemusic_Widget_LikeSitepagemusicController extends Engine_Content_Widget_Abstract {

  //ACTION FOR SHOWING THE MOST LIKED MUSIC ON PAGE PROFILE PAGE 
  public function indexAction() {

    //GET SITEPAGE SUBJECT
    $this->view->sitepage_subject = $sitepage_subject = Engine_Api::_()->core()->getSubject('sitepage_page');
		$like_music = Zend_Registry::isRegistered('sitepagemusic_like') ? Zend_Registry::get('sitepagemusic_like') : null;

    //PACKAGE BASE PRIYACY START
    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage_subject->package_id, "modules", "sitepagemusic")) {
        return $this->setNoRender();
      }
    } else {
      $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage_subject, 'smcreate');
      if (empty($isPageOwnerAllow)) {
        return $this->setNoRender();
      }
    }
    //PACKAGE BASE PRIYACY END
    
    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage_subject, 'view');
    if (empty($isManageAdmin) || empty($like_music)) {
      return $this->setNoRender();
    }
    //END MANAGE-ADMIN CHECK
    
    //SEARCH PARAMETER
    $params = array();
    $params['page_id'] = $sitepage_subject->page_id;
    $params['profile_page_widget'] = 1;
    $params['orderby'] = 'like_count DESC';
    $params['zero_count'] = 'like_count';
    $params['limit'] = $this->_getParam('itemCount', 3);

    //MAKE PAGINATOR
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('playlists', 'sitepagemusic')->widgetMusicList($params);

    //SET NO RENDER
    if (Count($paginator) <= 0) {
      return $this->setNoRender();
    }
  }

}

?>