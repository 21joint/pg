<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegroup
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegroup_Widget_ContactdetailsSitegroupController extends Engine_Content_Widget_Abstract {

  //ACTION FOR SHOWING THE RANDOM ALBUMS AND PHOTOS BY OTHERS 
  public function indexAction() {

    //DON'T RENDER IF SUNJECT IS NOT THERE
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

    //GET SITEGROUP SUBJECT
    $this->view->sitegroup = $sitegroup = Engine_Api::_()->core()->getSubject('sitegroup_group');

    $isManageAdmin = Engine_Api::_()->sitegroup()->isManageAdmin($sitegroup, 'contact');
    if (empty($isManageAdmin)) {
      return $this->setNoRender();
    }

		$this->view->can_edit = Engine_Api::_()->sitegroup()->isManageAdmin($sitegroup, 'edit');
    
    if(empty($sitegroup->phone) && empty($sitegroup->email) && empty($sitegroup->website) && !$this->view->can_edit) {
      return $this->setNoRender();
    }

		//GET SETTINGS
		$pre_field = array("0" => "1", "1" => "2", "2" => "3");
		$contacts = $this->_getParam('contacts', $pre_field);
    $this->view->emailme = $this->_getParam('emailme', 0);
		if(empty($contacts)) {
			$this->setNoRender();
		}
		else {
			//INITIALIZATION
			$this->view->show_phone = $this->view->show_email = $this->view->show_website = 0;
			if(in_array(1, $contacts)) {
				$this->view->show_phone = 1;
			}
			if(in_array(2, $contacts)) {
				$this->view->show_email = 1;
			}
			if(in_array(3, $contacts)) {
				$this->view->show_website = 1;
			}
		}

		$user = Engine_Api::_()->user()->getUser($sitegroup->owner_id);
		$view_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sitegroup_group', $user, 'contact_detail');
		$availableLabels = array('phone' => 'Phone', 'website' => 'Website', 'email' => 'Email');
		$this->view->options_create = array_intersect_key($availableLabels, array_flip($view_options));
  }

}