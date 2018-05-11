<?php
/**
 * EXTFOX
 *
 * @category   Earn Points Widget
 * @package    Ggcommunity
 * *
 */
class Ggcommunity_Widget_EarnPointsController extends Engine_Content_Widget_Abstract
{
 
  public function indexAction()
  {
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

    //get params from admin widget form 
    $this->view->widgetParams = $params = $this->_getAllParams();

  }

  
  

}