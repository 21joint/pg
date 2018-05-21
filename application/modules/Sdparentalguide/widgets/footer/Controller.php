<?php
/**
 * EXTFOX
 *
 * @package    EXTFOX 
 */
class Sdparentalguide_Widget_FooterController extends Engine_Content_Widget_Abstract
{

  public function indexAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();

    //Base url
    $this->view->baseUrl = $baseUrl = $this->view->baseUrl();
    
    $this->view->navigation = $navigation = Engine_Api::_()
    ->getApi('menus', 'core')
    ->getNavigation('custom_138');


  }

  
}