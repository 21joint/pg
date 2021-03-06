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
class Sitepage_Widget_SitemobileLocationSitepageController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    //CHECK LOCATION MAP IS ENABLED OR NOT 
    $check_location = Engine_Api::_()->sitepage()->enableLocation();
    if (!Engine_Api::_()->core()->hasSubject() || !$check_location) {
      return $this->setNoRender();
    }

    $this->view->multiple_location = $multipleLocation = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.multiple.location', 0);

    //GET SUBJECT
    $this->view->sitepage = $sitepage = Engine_Api::_()->core()->getSubject('sitepage_page');
    
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
    if (empty($isManageAdmin)) {
      return $this->setNoRender();
    }

    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'map');
    if (empty($isManageAdmin)) {
      return $this->setNoRender();
    }
    $MainLocationId = 0;
    $this->view->MainLocationObject='';
    if (!empty($sitepage->location)) {
      $MainLocationId = Engine_Api::_()->getDbtable('locations', 'sitepage')->getLocationId($sitepage->page_id, $sitepage->location);
      $this->view->MainLocationObject = Engine_Api::_()->getItem('sitepage_location', $MainLocationId);
    }

    $value['id'] = $sitepage->getIdentity();
    if (!empty($multipleLocation)) {
      $value['mapshow'] = 'Map Tab';
      $value['mainlocationId'] = $MainLocationId;
    }

    //DONT RENDER IF NO LOCATION
    $location = Engine_Api::_()->getDbtable('locations', 'sitepage')->getLocation($value);
    $count = 0;
    if ($multipleLocation) {
      $count = $location->getTotalItemCount();
    } else {
      $count = count($location);
    }

    if (empty($sitepage->location) && empty($count)) {
      return $this->setNoRender();
    }

    //GET PRICE IS ENABLED OR NOT
    $this->view->enablePrice = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.price.field', 1);

    //START MANAGE-ADMIN CHECK
    $this->view->isManageAdmin = $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');

    //END MANAGE-ADMIN CHECK
    $this->view->current_page = $page = $this->_getParam('page', 1);
    $this->view->current_totalpages = $page * 10;

    //DONT RENDER IF NO LOCATION
    $this->view->location = $location;
    if (!empty($multipleLocation)) {
      $location->setItemCountPerPage(10);
      $this->view->location = $location->setCurrentPageNumber($page);
    } else {
      if (empty($location)) {
        return $this->setNoRender();
      }
    }

    //PAGE-RATING IS ENABLED OR NOT
    $this->view->ratngShow = (int) Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepagereview');
  }

}