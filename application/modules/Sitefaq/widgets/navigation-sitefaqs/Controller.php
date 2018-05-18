<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Widget_NavigationSitefaqsController extends Engine_Content_Widget_Abstract
{ 
  public function indexAction()
  {
		//GET VIEWER DETAILS
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

    //GET LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		//WHO CAN VIEW THE FAQs
		$can_view = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'view');
		if(empty($can_view)) {
			return $this->setNoRender();
		}

		//GET ACTION NAME
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$action = '';
		if (!empty($request)) {
			$action = $request->getActionName();
		}

		//GET NAVIGATION
		if($action == 'home' || $action == 'mobi-home') {
			$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitefaq_main', array(), 'sitefaq_main_home');
		}
		elseif($action == 'manage' || $action == 'mobi-manage') {
			$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitefaq_main', array(), 'sitefaq_main_manage');
		}
		else {
			$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitefaq_main', array(), 'sitefaq_main_browse');
		}
  }

}