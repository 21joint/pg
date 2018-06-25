<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_TopicsController extends Core_Controller_Action_Standard
{
    public function homeAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addTopicsHomePage();
        $this->_helper->content->setEnabled();
    }
}
