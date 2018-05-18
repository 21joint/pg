<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Offers.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Model_DbTable_Offers extends Engine_Db_Table
{
	protected $_rowClass = "Sitecredit_Model_Offer";

	function getValidoffers()  {
		$now = date('Y-m-d h:m:s');
		$select =$this->select();
		$select->where("end_date = 1 OR (end_date=0 AND expiry_date > ?)",$now)->order('credit_point ASC');
		return $this->fetchAll($select); 
	}

}



