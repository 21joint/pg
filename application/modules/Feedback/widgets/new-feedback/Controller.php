<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.tpl 6590 2010-08-11 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Widget_NewFeedbackController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    
  	//GET VIEWER INFORMATION
  	$viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //PUBLIC CAN VIEW FEEDBACK OR NOT
  	if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.public', 1) && empty($viewer_id)) {
			return $this->setNoRender();
  	}      
    
    if(!Engine_Api::_()->feedback()->canCreateFeedback($_SERVER['REMOTE_ADDR'], $viewer_id)) {
      return $this->setNoRender();  
    }
      
  }
}

