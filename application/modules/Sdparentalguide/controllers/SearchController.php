<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_SearchController extends Core_Controller_Action_Standard
{
    public function indexAction(){
        Engine_Api::_()->getApi("install","sdparentalguide")->addSearchPage();
        $this->_helper->content->setEnabled();
    }
}
