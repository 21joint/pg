<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_ReviewsController extends Core_Controller_Action_Standard
{
    public function homeAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addReviewsHomePage();
        $this->_helper->content->setEnabled();
    }
    public function createAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addReviewsCreatePage();
        $this->_helper->content->setEnabled();
    }
    public function viewAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addReviewsViewPage();
        $this->_helper->content->setEnabled();
    }
}
