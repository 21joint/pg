<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_GuidesController extends Core_Controller_Action_Standard
{
    public function homeAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addGuidesHomePage();
        $this->_helper->content->setEnabled();
    }
    public function createAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addGuidesCreatePage();
        $this->_helper->content->setEnabled();
    }
    public function viewAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addGuidesViewPage();
        $this->_helper->content->setEnabled();
    }
}
