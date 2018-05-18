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
class Sitepagemusic_Widget_SitepageSponsoredmusicController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $getPackageMusic = Engine_Api::_()->sitepage()->getPackageAuthInfo('sitepagemusic');

    //NUMBER OF MUSICS IN LISTING
    $totalMusics = $this->_getParam('itemCount', 3);

    //GET MUSIC DATAS
    $params = array();
    $params['category_id'] = $this->_getParam('category_id',0);
    $params['limit'] = $totalMusics;
    $musicType = 'sponsored';
    $this->view->paginator = $row = Engine_Api::_()->getDbTable('playlists', 'sitepagemusic')->widgetMusicList($params,$musicType);
    $sitepagePackageEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.package.enable', 1);
    if ( ( Count($row) <= 0 ) || empty($getPackageMusic) || empty($sitepagePackageEnable) ) {
      return $this->setNoRender();
    }
  }

}
?>