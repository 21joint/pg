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
class Sitepagebadge_Widget_PopularSitepagebadgeController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    //NUMBER OF BADGES IN LISTING
    $this->view->sitepagebadges_value = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.badgeprofile.widgets', 2);
    $badge_info = Engine_Api::_()->sitepagebadge()->badgeInfo();
    $sitepagebadge_popularBadge = Zend_Registry::isRegistered('sitepagebadge_popularBadge') ? Zend_Registry::get('sitepagebadge_popularBadge') : null;

    //PROCESS FORM
    $this->view->form = $form = new Sitepagebadge_Form_Searchbadge();

		$params = array();
    $params['totalbadges'] = $this->_getParam('itemCount', 3);

    // GET THE CATEGORYID OF PAGE
    $this->view->category_id = $params['category_id'] = $this->_getParam('category_id',0);

		$params['popular_badges'] = 1;
    $this->view->badgeData = $badgeData = Engine_Api::_()->getDbTable('badges', 'sitepagebadge')->getBadgesData($params);
    $badgeDataCount = Count($badgeData);
    if (empty($badgeDataCount)) {
      return $this->setNoRender();
    }
    if (empty($badge_info) || empty($sitepagebadge_popularBadge)) {
      return $this->setNoRender();
    }
  }

}
?>