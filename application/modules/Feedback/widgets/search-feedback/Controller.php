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
class Feedback_Widget_SearchFeedbackController extends Engine_Content_Widget_Abstract {

    public function indexAction() {

        //GET VIEWER INFORMATION
        $viewer = Engine_Api::_()->user()->getViewer();
        $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

        //FORM GENERATION
        $this->view->form = $form = new Feedback_Form_Search();

        //GET FORM VALUES
        if ($form->isValid($this->_getAllParams())) {
            $this->view->formValues = $values = $form->getValues();
        } else {
            $this->view->formValues = $values = array();
        }

//        //PROCESS MOST VOTED SEARCH FORM
//        $values_mostvoted = $formmostvoted->getValues();
//        if (!empty($values_mostvoted['orderby_mostvoted'])) {
//            $values['orderby'] = 'total_votes';
//        }

        $values['feedback_private'] = "public";
        $values['can_vote'] = "1";
        $values['viewer_id'] = $viewer_id;

        //POPULATE FORM
        $this->view->formValues = $values = $_GET;
        $form->populate($values);
        $this->view->assign($values);

        //GET PAGINATION
        $page = 1;
        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $page = $_GET['page'];
        }
    }

}
