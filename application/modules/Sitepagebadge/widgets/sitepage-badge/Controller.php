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
class Sitepagebadge_Widget_SitepageBadgeController extends Seaocore_Content_Widget_Abstract {

  public function indexAction() {

   //GENERATE BADGE HIDDEN FROM
    $this->view->form = $form = new Sitepagebadge_Form_Searchbadge();

    //GET THE LIMIT OF BADGE
    $totalBadges = $this->_getParam('itemCount', 10);
    
    // GET THE CATEGORYID OF PAGE
    $category_id = $this->_getParam('category_id',0);

    //FETCH BADGES
		$this->view->badgeData = $badgeData = Engine_Api::_()->getDbTable('badges', 'sitepagebadge')->getBadgesData(array('category_id' => $category_id),$totalBadges);
  
    $this->view->sitepagebadges_value = Engine_Api::_()->getApi('settings', 'core')->sitepagebadge_badgeprofile_widgets;
  }

}

?>