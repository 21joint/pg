<?php

/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Siteseo
* @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: ItemPhoto.php 2017-03-27 9:40:21Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/

class Siteseo_View_Helper_ItemPhoto extends Core_View_Helper_ItemPhoto
{
  public function itemPhoto($item, $type = 'thumb.profile', $alt = "", $attribs = array())
  {
    if (empty($alt)) {
      $alt = $item->getTitle();
    }
    return parent::itemPhoto($item, $type, $alt, $attribs);
  }

}