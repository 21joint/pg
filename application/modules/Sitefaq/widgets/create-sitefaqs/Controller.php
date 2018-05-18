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

class Sitefaq_Widget_CreateSitefaqsController extends Engine_Content_Widget_Abstract {
	
	public function indexAction() {

		//GET VIEWER
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		//DONT SHOW ADD LINK TO VISITOR
		if(empty($viewer_id)) {
			return $this->setNoRender();
		} 

		//CHECK FAQ CREATION PRIVACY
    if (!Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'create')) {
      return $this->setNoRender();
    }
	}

}