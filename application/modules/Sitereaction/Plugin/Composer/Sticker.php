<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sticker.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereaction_Plugin_Composer_Sticker extends Core_Plugin_Composer
{
  public function onAttachSticker($data)
  {
    
    $attachment = Engine_Api::_()->getItemByGuid($data['stikcer_guid']);
    return $attachment;
  }

}
