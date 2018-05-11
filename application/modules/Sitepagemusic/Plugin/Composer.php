<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: Composer.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitepagemusic_Plugin_Composer extends Core_Plugin_Abstract
{
  public function onAttachSitepagemusic($data)
  {
    if( !is_array($data) || empty($data['song_id']) ) 
      return;

    $song = Engine_Api::_()->getItem('sitepagemusic_playlist_song', $data['song_id']);
    if( !($song instanceof Core_Model_Item_Abstract) || !$song->getIdentity() )
      return;
    
    return $song;
  }
}