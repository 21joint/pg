<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Modules.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitecredit_Model_DbTable_Modules extends Engine_Db_Table
{
  protected $_rowClass = "Sitecredit_Model_Module";
  
  public function getManageModulesList($params = array()) {

    //Get list of all modules from sitemobile module table which are enabled in core module table and visible in sitemobile.
    $coreModulesName = Engine_Api::_()->getDbtable('modules', 'core')->info('name');

    $smModulesName = $this->info('name');
    
    $select = $this->select()
    ->from($smModulesName)
    ->setIntegrityCheck(false)
    ->join($coreModulesName, "($smModulesName.name = $coreModulesName.name)", array())
    ->where("$coreModulesName.enabled = ?", 1);
    if (isset($params['integrated'])) {
      $select->where("$smModulesName.integrated = ?", $params['integrated']);
  }

  $modules = $this->fetchAll($select);

  return $modules;
}

}



