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
class Feedback_Widget_SimilarFeedbacksController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
		
    //DON'T RENDER IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject('feedback')) {
        return $this->setNoRender();
    }

    //GET FEEDBACK OWNER
    $this->view->feedback = $feedback = Engine_Api::_()->core()->getSubject();      
      
    $params = array();
    $this->view->itemCount = $params['limit'] = $this->_getParam('itemCount', 3);
    $this->view->truncationLimit = $this->_getParam('truncationLimit', 19);
    $params['category_id'] = $feedback->category_id;
    $params['feedback_id'] = $feedback->feedback_id;
    $params['popularity'] = $this->_getParam('popularity', 'feedback_id');
    
		$this->view->feedbacks = Engine_Api::_()->getDbtable('feedbacks', 'feedback')->similarFeedbacks($params);
		$this->view->feedbacksTotal = Count($this->view->feedbacks);    

		//NO RENDER IF NO FEEDBACKS
    if( $this->view->feedbacksTotal <= 0) {
      return $this->setNoRender();
    }
  
  }
}