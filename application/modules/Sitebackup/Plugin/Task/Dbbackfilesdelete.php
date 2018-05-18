<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: Dbbackfilesdelete.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
class Sitebackup_Plugin_Task_Dbbackfilesdelete extends Core_Plugin_Task_Abstract
{
  public function execute()
  {
    // fetch that time stamp when the reminder mail was last sent
    $taskstable = Engine_Api::_()->getDbtable('tasks', 'core');
    $rtasksName = $taskstable->info('name');
    $taskstable_result = $taskstable->select()
      ->from($rtasksName, array('started_last'))
      ->where('title = ?', "Background Automatically Delete Files' Backups")
      ->where('plugin = ?', 'Sitebackup_Plugin_Task_Dbbackfilesdelete')
      ->limit(1);

    $value = $taskstable->fetchRow($taskstable_result);
    $old_started_last = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebackupdeletefiles.startedlast', 0);

    $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
    $coreversion = $coremodule->version;
    if( !($coreversion < '4.1.0') ) {
      if( !Engine_Api::_()->sitebackup()->canRunTask("sitebackup", "Sitebackup_Plugin_Task_Dbbackfilesdelete", $old_started_last) ) {
        return;
      }
    }
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sitebackupdeletefiles_startedlast', $value['started_last']);

    if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_deletecodeoptions ) {
      $deletefiles = Engine_Api::_()->sitebackup()->deletebackupfiles();
    }
  }

}
