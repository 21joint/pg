<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Images.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteluminous_Model_DbTable_Images extends Engine_Db_Table {

  protected $_name = 'siteluminous_images';
  protected $_rowClass = "Siteluminous_Model_Image";

  public function getImages($params = array(), $columns = array()) {  
    $tableName = $this->info('name');
    $select = $this->select();
    
    if(!empty($columns))
      $select->from($tableName, $columns);
    
    if (isset($params['enabled'])) {
      $select->where('enabled = ?', $params['enabled']);
    }

    $select->order("order ASC");
    
    return $this->fetchAll($select);
  }

}
