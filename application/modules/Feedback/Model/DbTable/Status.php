<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Status.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Model_DbTable_Status extends Engine_Db_Table
{
  protected $_rowClass = 'Feedback_Model_Stat';
  
  /**
   * Get status
   * @return status
  */ 
  public function getStatus()
  {
    return $this->fetchAll();
  }  
  
}
