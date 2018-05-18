<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_Plugin_Menus {

  public function canViewBadges() {

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.badge.show.menu', 1)) {
      return false;
    }

    $table = Engine_Api::_()->getDbtable('badges', 'sitepagebadge');
    $select = $table->select()->order('creation_date DESC');
    $row = $table->fetchAll($select);
    $count = count($row);
    if (empty($count)) {
      return false;
    }
    return true;
  }

}
?>