<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Widget_ReviewsHomeController
  extends Engine_Content_Widget_Abstract
{

  public function indexAction()
  {
    $headLink = new Zend_View_Helper_HeadLink();
    $headLink->appendStylesheet('/styles/reviews.bundle.css');
  }
}
