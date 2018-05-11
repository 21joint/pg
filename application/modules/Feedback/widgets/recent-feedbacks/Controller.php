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
class Feedback_Widget_RecentFeedbacksController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
		$recentFeed = Zend_Registry::isRegistered('feedback_recentFeed') ? Zend_Registry::get('feedback_recentFeed') : null;

    $params = array();
    $params['limit'] = $this->_getParam('itemCount', 3);
    $params['orderby'] = $this->_getParam('popularity', 'feedback_id');
    $this->view->truncationLimit = $this->_getParam('truncationLimit', 19);
    
		//GET FEEDBACKS
    $this->view->paginator = Engine_Api::_()->getDbtable('feedbacks', 'feedback')->getWidgetFeedbacks($params);

		//NO RENDER IF NO FEEDBACKS
    if( Count($this->view->paginator) <= 0  || empty($recentFeed)) {
      return $this->setNoRender();
    }
    
		//GENERATE MOST VOTED SEARCH FORM
		$this->view->formmostvoted = new Feedback_Form_Searchmostvoted();    
  }
}
