<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepage
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitepagemusic_Widget_ProfilePlayerController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    //GET SITEPAGE SUBJECT
    $this->view->sitepage_subject = $sitepage_subject = Engine_Api::_()->core()->getSubject('sitepage_page');

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
    
//    //START MANAGE-ADMIN CHECK
//    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage_subject, 'view');
//    if (empty($isManageAdmin)) {
//      return $this->setNoRender();
//    }
//    //END MANAGE-ADMIN CHECK
    
    //SEARCH PARAMETER
    $params = array();
    $params['page_id'] = $sitepage_subject->page_id;
    $params['limit'] = 1;
    $params['profile_page_widget'] = 1;
    $params['profile'] = 1;
    
    //MAKE PAGINATOR
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('playlists', 'sitepagemusic')->widgetMusicList($params);

    //SET NO RENDER
    if (Count($paginator) <= 0) {
      return $this->setNoRender();
    } else {
  	  $getAllInfo = $paginator->toarray();
  	  if(!empty($getAllInfo))
  	  $this->getElement()->setTitle($getAllInfo[0]['title']);
    }   
  }
  
}

?>