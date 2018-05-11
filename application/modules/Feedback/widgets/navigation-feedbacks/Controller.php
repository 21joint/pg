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
class Feedback_Widget_NavigationFeedbacksController extends Engine_Content_Widget_Abstract {

    protected $_navigation;

    public function indexAction() {

        if (Engine_Api::_()->user()->getViewer()->getIdentity()) {
            $tabs = array();

            //CHECK THAT FEEDBACK FORUM TAB SHOULD BE VISIBLE OR NOT
            $show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
            if (!empty($show_browse)) {
                $tabs[] = array(
                    'label' => 'Browse Feedbacks',
                    'route' => 'feedback_browse',
                    'action' => 'browse',
                    'controller' => 'index',
                    'module' => 'feedback'
                );
            }

            $tabs[] = array(
                'label' => 'My Feedbacks',
                'route' => 'feedback_manage',
                'action' => 'manage',
                'controller' => 'index',
                'module' => 'feedback'
            );

            $tabs[] = array(
                'label' => 'Create New Feedback',
                'route' => 'feedback_create',
                'action' => 'create',
                'controller' => 'index',
                'module' => 'feedback',
                'class' => 'smoothbox',
            );

            if (is_null($this->_navigation)) {
                $this->_navigation = new Zend_Navigation();
                $this->_navigation->addPages($tabs);
            }
        }
        $this->view->navigation = $this->_navigation;
    }

}

