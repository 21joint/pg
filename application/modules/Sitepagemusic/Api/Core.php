<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Api_Core extends Core_Api_Abstract {

  /**
   * Return a truncate text
   *
   * @param text text 
   * @return truncate text
   * */
  public function truncation($string) {
    $length = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.truncation.limit', 13);
    $string = strip_tags($string);
    return Engine_String::strlen($string) > $length ? Engine_String::substr($string, 0, ($length - 3)) . '...' : $string;
  }
  
  public function enableComposer() {
    $subject = '';
    if (Engine_Api::_()->core()->hasSubject()) {
      $subject = Engine_Api::_()->core()->getSubject();
    }
    if ($subject && in_array($subject->getType(), array('sitepage_page', 'sitepageevent_event'))):


      if (in_array($subject->getType(), array('sitepageevent_event'))):
        $subject = Engine_Api::_()->getItem('sitepage_page', $subject->page_id);
      endif;
      //PACKAGE BASE PRIYACY START
      if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
        if (!Engine_Api::_()->sitepage()->allowPackageContent($subject->package_id, "modules", "sitepagemusic")) {
          return false;
        }
      } else {
        $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($subject, 'smcreate');
        if (empty($isPageOwnerAllow)) {
          return false;
        }
      }
      if (!Engine_Api::_()->sitepage()->isManageAdmin($subject, 'edit') && !Engine_Api::_()->sitepage()->isManageAdmin($subject, 'smcreate')):
        return false;
      endif;
      return true;
    endif;
    return false;
  }
 
}

?>