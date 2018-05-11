<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Album.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Model_Album extends Core_Model_Item_Collection
{
  protected $_parent_type = 'feedback';

  protected $_owner_type = 'feedback';

  protected $_children_types = array('feedback_image');

  protected $_collectible_type = 'feedback_image';

  /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
   */
 	public function getHref($params = array())
  {
    $params = array_merge(array(
      'route' => 'feedback_profile',
      'reset' => true,
      'id' => $this->getFeedback()->getIdentity(),
    ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
      ->assemble($params, $route, $reset);
  }
}
