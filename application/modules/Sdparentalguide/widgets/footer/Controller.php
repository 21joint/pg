<?php
/**
 * EXTFOX
 *
 * @package    EXTFOX
 */
class Sdparentalguide_Widget_FooterController extends Engine_Content_Widget_Abstract
{

  public function indexAction(){
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
  }

}
