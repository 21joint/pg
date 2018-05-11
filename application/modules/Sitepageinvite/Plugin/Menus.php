<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageinvite
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageinvite_Plugin_Menus {

  public function onMenuInitialize_SitepageinviteGutterPageinvite($row) {
    if (!Engine_Api::_()->core()->hasSubject()) {
      return false;
    }

    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    if (empty($viewer_id)) {
      return false;
    }

    $sitepage = Engine_Api::_()->core()->getSubject('sitepage_page');

    if (!empty($sitepage) && !empty($viewer_id)) {
      //START MANAGE-ADMIN CHECK
      $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'invite');
      if (empty($isManageAdmin)) {
        $can_invite = 0;
      } else {
        $can_invite = 1;
      }
      //END MANAGE-ADMIN CHECK

      $manageadminTable = Engine_Api::_()->getDbtable('manageadmins', 'sitepage');
      $manageadminTableName = $manageadminTable->info('name');
      $select = $manageadminTable->select()
              ->from($manageadminTableName, 'manageadmin_id')
              ->where('user_id = ?', $viewer_id)
              ->where('page_id = ?', $sitepage->page_id)
              ->limit(1);
      $rowData = $manageadminTable->fetchAll($select)->toArray();
      if (!empty($rowData[0]['manageadmin_id'])) {
        $ismanageadmin = 1;
      } else {
        $ismanageadmin = 0;
      }

      if (($can_invite != 1) || (Engine_Api::_()->user()->getViewer()->level_id != 1 && $viewer_id != $sitepage->owner_id && $ismanageadmin != 1)) {
        return false;
      }
    }

    // Modify params
    $params = $row->params;
    $params['params']['sitepage_id'] = $sitepage->getIdentity();
    $params['params']['user_id'] = $sitepage->owner_id;
    return $params;
  }

}

?>