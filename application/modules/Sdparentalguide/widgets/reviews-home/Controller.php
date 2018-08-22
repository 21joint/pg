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
    $headScript = new Zend_View_Helper_HeadScript();
    $headLink->prependStylesheet('/styles/reviews_home.bundle.css');
    $headScript->prependFile('/scripts/reviews_home.bundle.js');
  }
}
