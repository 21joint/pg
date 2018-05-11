<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_Installer extends Engine_Package_Installer_Module
{
  function onInstall()
  {
    $this->addReactionColumn();
    $db = $this->getDb();
    parent::onInstall();
  }

  private function addReactionColumn()
  {
    $db = $this->getDb();
    $hasReactionColumn = $db->query("SHOW COLUMNS FROM engine4_activity_likes LIKE 'reaction'")->fetch();
    if( empty($hasReactionColumn) ) {
      $db->query("ALTER TABLE `engine4_activity_likes` ADD `reaction` VARCHAR(64) NULL DEFAULT 'like' , ADD INDEX (`reaction`)");
    }
    $hasReactionColumn = $db->query("SHOW COLUMNS FROM engine4_core_likes LIKE 'reaction'")->fetch();
    if( empty($hasReactionColumn) ) {
      $db->query("ALTER TABLE `engine4_core_likes` ADD `reaction` VARCHAR(64) NULL DEFAULT 'like' , ADD INDEX (`reaction`)");
    }
  }
}
