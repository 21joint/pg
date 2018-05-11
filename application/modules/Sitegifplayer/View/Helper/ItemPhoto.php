<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegifplayer
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: ItemPhoto.php 2017-05-15 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegifplayer_View_Helper_ItemPhoto extends Core_View_Helper_ItemPhoto
{
  public function setAttributes($item, $type = 'thumb.profile', $attribs = array())
  {
    if( !($item instanceof Core_Model_Item_Abstract) ) {
      throw new Zend_View_Exception("Item must be a valid item");
    }
    $coreSettings = Engine_Api::_()->getApi('settings', 'core');
    // Get url
    $src = $item->getPhotoUrl($type);
    $safeName = ( $type ? str_replace('.', '_', $type) : 'main' );
    $attribs['class'] = ( isset($attribs['class']) ? $attribs['class'] . ' ' : '' );
    $attribs['class'] .= $this->_classPrefix . $safeName . ' ';
    $attribs['class'] .= $this->_classPrefix . 'item_photo_' . $item->getType() . ' ';

    // Default image
    if( !$src ) {
      $src = $this->getNoPhoto($item, $safeName);
      $attribs['class'] .= $this->_classPrefix . 'item_nophoto ';
    } else {
      $name = basename($src);
      $extension = ltrim(strrchr($name, '.'), '.');
      $gifPlayerLoaded = Zend_Registry::isRegistered('sitegifplayerItemPhoto') ? Zend_Registry::get('sitegifplayerItemPhoto') : 0;
      if( !empty($gifPlayerLoaded) && strtolower($extension) === 'gif' ) {
        $gifType = 'thumb.gif-' . md5(substr($src, -46));
        $normalSrc = $item->getPhotoUrl($gifType);
        $name = basename($src);
        $extension = ltrim(strrchr(basename($normalSrc), '.'), '.');
        if( strtolower($extension) !== 'gif' ) {
          $attribs['data-gif-src'] = $src;
          $attribs['data-src'] = $normalSrc;
          $src = $normalSrc;
          $attribs['class'] .= 'sitegif_player sitegif_player_init' . ' ';
          $attribs['data-duration'] = ($coreSettings->getSetting('sitegifplayer.duration', 60)) * 1000; //in milliseconds
          if( $coreSettings->getSetting('sitegifplayer.allow.action', 1) ) {
            $attribs['data-action'] = 'mousehover';
          } else {
            $attribs['data-action'] = 'click';
          }
        }
      }
    }
    $this->_url = $src;
    $this->_attribs = $attribs;
    return $this;
  }

}
