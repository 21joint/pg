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

class Sitefaq_Widget_SearchBoxSitefaqsController extends Engine_Content_Widget_Abstract
{ 
  public function indexAction()
  {
		//GET VIEWER DETAIL
		$viewer = Engine_Api::_()->user()->getViewer();
		$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

    //FETCH FAQS
    $params = array();
		if($level_id != 1) {
			$sitefaq_api = Engine_Api::_()->sitefaq();
			$params['networks'] = $sitefaq_api->getViewerNetworks();
			$params['profile_types'] = $sitefaq_api->getViewerProfiles();
			$params['member_levels'] = $sitefaq_api->getViewerLevels();
		}
    $params['limit'] = 1;
    $paginator = Engine_Api::_()->getDbtable('faqs', 'sitefaq')->widgetSitefaqsData($params);
		$this->view->total_results = Count($paginator);

		//DON'T RENDER IF CAN'T VIEW AND TOTAL RESULTS ARE ZERO
		if($this->view->total_results <= 0) {
			return $this->setNoRender();
		}

		//GET WIDGET SETTINGS
		$this->view->heading = $this->_getParam('heading', 1);
		$this->view->blur_text = $this->_getParam('blur_text', '');
		$this->view->privacy = $this->_getParam('privacy', 1); 

		if($this->view->heading) {
			//GET VIEWER DETAIL
			$viewer = Engine_Api::_()->user()->getViewer();
			if($viewer->getIdentity()) {
				$this->view->display_name = $viewer->getTitle();
			}
			else {
				$this->view->display_name = Zend_Registry::get('Zend_Translate')->_('Guest');
			}
		}
  }

}