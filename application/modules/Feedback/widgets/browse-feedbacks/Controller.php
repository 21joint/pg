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
class Feedback_Widget_BrowseFeedbacksController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $params = $request->getParams();
    //GET VIEWER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $this->view->is_ajax = $isAjax = $this->_getParam('isajax', 0);
    $this->view->showContent = $this->_getParam('show_content', 2);

    //START VIEW MORE LINK AND AUTOSCROLL CONTENT WORK
    if(!empty($isAjax)) {
      $this->getElement()->removeDecorator('Title');
      $this->getElement()->removeDecorator('Container');
    }
    //END VIEW MORE LINK AND AUTOSCROLL CONTENT WORK

    //PUBLIC CAN VIEW FEEDBACK OR NOT
    $feedback_public = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.public', 1);
    if ($feedback_public == 0 && empty($viewer_id)) {
      return $this->setNoRender();
    }

    //SEND MESSAGE VARIABLE TO TPL
    $this->view->is_msg = $params['success_msg'] = (int) $this->_getParam('success_msg');

    //CHECK IF IP BLOCK BY ADMIN FOR POSTING FEEDBACK
    $this->view->can_create = Engine_Api::_()->feedback()->canCreateFeedback($_SERVER['REMOTE_ADDR'], $viewer_id);

    //FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Search();

    //GET FORM VALUES
    if ($form->isValid($this->_getAllParams())) {
      $this->view->formValues = $values = $form->getValues();
    } else {
      $this->view->formValues = $values = array();
    }

//    //PROCESS MOST VOTED SEARCH FORM
//    $values_mostvoted = $formmostvoted->getValues();
//    if(!empty($values_mostvoted['orderby_mostvoted'])) {
//    	$values['orderby'] = 'total_votes';
//    }

    $values['feedback_private'] = "public";
    $values['can_vote'] = "1";
    $values['viewer_id'] = $viewer_id;

    //POPULATE FORM
    $this->view->formValues = $values = array_merge($values, $_GET);
    $this->view->assign($values);

    $customFieldValues = array_intersect_key($values, $form->getFieldElements());

    $feedbackTable = Engine_Api::_()->getDbtable('feedbacks', 'feedback');

    //GET PAGINATION
    $page = 1;
    if (isset($_GET['page']) && !empty($_GET['page'])) {
      $page = $_GET['page'];
    }

    //GET PAGINATOR
    $this->view->paginator = $paginator = $feedbackTable->getFeedbacksPaginator($values, $customFieldValues);
    $this->view->totalCount = $paginator->getTotalItemCount();
    $items_per_page = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.page', 10);
    $paginator->setItemCountPerPage($items_per_page);
    $paginator->setCurrentPageNumber($page);
  }

}

