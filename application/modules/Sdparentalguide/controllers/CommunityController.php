<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_CommunityController extends Core_Controller_Action_Standard
{
    public function homeAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addCommunityHomePage();
        $this->_helper->content->setEnabled();
    }
    public function leaderboardAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addLeaderboardPage();
        $this->_helper->content->setEnabled();
    }
}
