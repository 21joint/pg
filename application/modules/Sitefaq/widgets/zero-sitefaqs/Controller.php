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

class Sitefaq_Widget_ZeroSitefaqsController extends Seaocore_Content_Widget_Abstract {

  public function indexAction() {

		//GET VIEWER DETAIL
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

		//GET LEVEL ID
		if(!empty($viewer_id)) {
			$level_id = $viewer->level_id;
		}
		else {
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

		//FAQ VIEW PRIVACY
		$can_view = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'view');

		//DON'T RENDER IF CAN'T VIEW AND TOTAL RESULTS ARE ZERO
		if(empty($can_view) || $this->view->total_results >= 1) {
			return $this->setNoRender();
		}

		//FAQ CREATION PRIVACY
		$this->view->can_create = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'create');
  }

}