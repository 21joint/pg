<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_Widget_BadgeSitepagebadgeController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $getPackageBadge = Engine_Api::_()->sitepage()->getPackageAuthInfo('sitepagebadge');
    if (!Engine_Api::_()->core()->hasSubject() || empty($getPackageBadge)) {
      return $this->setNoRender();
    }
    $badge_info = Engine_Api::_()->sitepagebadge()->badgeInfo();
    $sitepagebadge_badgeType = Zend_Registry::isRegistered('sitepagebadge_badgeType') ? Zend_Registry::get('sitepagebadge_badgeType') : null;

    if (empty($badge_info) || empty($sitepagebadge_badgeType)) {
      return $this->setNoRender();
    }

    //GET SITEPAGE SUBJECT
    $sitepage = Engine_Api::_()->core()->getSubject('sitepage_page');

    if (empty($sitepage->badge_id)) {
      return $this->setNoRender();
    } else {
      $this->view->sitepagebadges_value = Engine_Api::_()->getApi('settings', 'core')->sitepagebadge_badgeprofile_widgets;
      $this->view->sitepagebadge = Engine_Api::_()->getItem('sitepagebadge_badge', $sitepage->badge_id);
    }
  }

}
?>