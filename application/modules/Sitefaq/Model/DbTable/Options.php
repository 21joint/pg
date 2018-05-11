<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Options.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Model_DbTable_Options extends Engine_Db_Table
{
  protected $_rowClass = "Sitefaq_Model_Option";

  /**
   * Make Get Sitefaq option
   * @param int $option_id : sitefaq id
   */
  public function markSitefaqOption($option_id = null) {

    //FETCH DATA
    $slect_option_table = $this->select()
																->from($this->info('name'))
																->where('enable = ?', 1);
    if(!empty($option_id)) {
			$slect_option_table->where('option_id = ?', $option_id); 
		  $resultOptionTable = $this->fetchRow($slect_option_table); 
      return $option_label = $resultOptionTable->reason;
    }
    else {
			return $resultOptionTable = $this->fetchAll($slect_option_table);           
    }
  }

}