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
class Feedback_Widget_OptionsFeedbackController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        
        //DON'T RENDER IF SUBJECT IS NOT SET
        if (!Engine_Api::_()->core()->hasSubject('feedback')) {
            return $this->setNoRender();
        }

        //GET FEEDBACK OWNER
        $this->view->feedback = $feedback = Engine_Api::_()->core()->getSubject();
        
        $viewer = Engine_Api::_()->user()->getViewer();
        $this->view->viewer_id = $viewer_id = $viewer->getIdentity(); 
        
        if (!empty($viewer_id)) {
          $this->view->user_level = $viewer->level_id;
        } else {
          $this->view->user_level = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        }          
        
        $this->view->countUserPublicFeedbacks = 0;
        if(!empty($feedback->owner_id)) {
          $this->view->countUserPublicFeedbacks = Engine_Api::_()->getDbTable('feedbacks', 'feedback')->countUserPublicFeedbacks($feedback->owner_id);
        }
        
        if($this->view->user_level != 1 && $feedback->owner_id != $viewer_id && $this->view->countUserPublicFeedbacks == 0) {
            return $this->setNoRender();
        }
        
        //IMAGE UPLOAD IS ALLOW OR NOT
        $this->view->allow_upload = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.allow.image', 1);      
        
    }
}
