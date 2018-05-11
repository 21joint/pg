<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: Maintenance.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
class Sitebackup_Plugin_Task_Maintenance extends Core_Plugin_Task_Abstract
{
  public function execute()
  {
    $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
    $coreversion = $coremodule->version;
    if( !($coreversion < '4.1.0') ) {
      return;
    }
    $tasksTable = Engine_Api::_()->getDbtable('tasks', 'core');

    $select = $tasksTable->select()
      ->where('plugin =?', 'Sitebackup_Plugin_Task_Sitebackup')
      ->where('state =?', 'active')
      ->where('enabled =?', 1)
      ->where('module IN(?)', (array) Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames());

    $task = $tasksTable->fetchRow($select);

    if( !empty($task) ) {

      $max_timeout = $task->timeout * 1.5;
      $interval = time() - $task->started_last;

      if( $interval >= $max_timeout ) {

        $tasksTable->update(array(
          'state' => 'dormant',
          'executing' => 0,
          'executing_id' => 0,
          ), array('plugin =?' => 'Sitebackup_Plugin_Task_Sitebackup'));
      }
    }
  }

}
