<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Mixsettings.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_Model_DbTable_Mixsettings extends Engine_Db_Table {

  protected $_name = 'sitepageintegration_mixsettings';
  protected $_rowClass = 'Sitepageintegration_Model_Mixsetting';

  public function getIntegrationItems($moduleName = null) {

    $mixSettings = array();
    $tableName = $this->info('name');

    $coreTableName = Engine_Api::_()->getDbtable('modules', 'core')->info('name');

    $select = $this->select()
            ->setIntegrityCheck(false)
            ->from($tableName, array('resource_type', 'item_title', 'module'))
            ->join($coreTableName, "$coreTableName . name = $tableName . module", array('enabled'))
            ->where($tableName . '.enabled = ?', 1)
            ->where($coreTableName . '.enabled = ?', 1); 
    $row = $select->query()->fetchAll();

    if (!empty($row)) {
      if (empty($moduleName)) {
        foreach ($row as $modName) {
					$pieces = explode("_", $modName['resource_type']);
					if($modName['resource_type'] == 'document_0' || $modName['resource_type'] == 'folder_0' || $modName['resource_type'] == 'quiz_0') {
						$tempArray["listingtype_id"] = $listingTypeId = $pieces[1];
						$tempArray["resource_type"] = $resource_type = $pieces[0];
					} else {
						$tempArray["listingtype_id"] = $listingTypeId = $pieces[2];
						$tempArray["resource_type"] = $resource_type = $pieces[0] . '_' . $pieces[1];
					}

					$tempArray["item_title"] = $modName['item_title'];
					$mixSettings[] = $tempArray;
				}
      } else {
        foreach ($row as $modName) {
          $mixSettings[$modName['module']] = $modName['module'];
        }
      }
    }
    return $mixSettings;
  }

  public function getItemsTitle($resource_type, $listingTypeId) {
  
		$tableName = $this->info('name');
		$select = $this->select()
							->setIntegrityCheck(false)
							->from($tableName, array('item_title'))
							->where($tableName . '.resource_type = ?', $resource_type . '_' . $listingTypeId);
		$row = $select->query()->fetchColumn();
		return $row;
  }
  
  public function getItemsEnabled($resource_type, $listingTypeId) {
  
		$tableName = $this->info('name');
		$select = $this->select()
							->setIntegrityCheck(false)
							->from($tableName, array('enabled'))
							->where($tableName . '.resource_type = ?', $resource_type . '_' . $listingTypeId); 
		$row = $select->query()->fetchColumn();
		return $row;
  }
}