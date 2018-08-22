<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Widget_ReviewsCreateController
  extends Engine_Content_Widget_Abstract
{

  public function indexAction()
  {
    $headScript = new Zend_View_Helper_HeadScript();
    $headLink = new Zend_View_Helper_HeadLink();
    $headLink->appendStylesheet('/styles/reviews_create.bundle.css');
    $headScript->appendFile('/scripts/reviews_create.bundle.js');
  }
}
