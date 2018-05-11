<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Severities.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Model_DbTable_Severities extends Engine_Db_Table
{
  protected $_rowClass = 'Feedback_Model_Severity';

  /**
   * Get severities
   * @return severities
  */ 
	public function getSeverities() {

		//MAKE QUERY
		$select = $this->select()->order('order ASC');
		
		//RETURN RESULTS
		return $this->fetchAll($select);
	}
  
}
