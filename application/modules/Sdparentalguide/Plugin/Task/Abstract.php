<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Abstract.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
abstract class Sdparentalguide_Plugin_Task_Abstract extends Core_Plugin_Task_Abstract
{
  /**
   * @var Zend_Db_Table_Row_Abstract
   */
  protected $_task;

  /**
   * @var boolean
   */
  protected $_wasIdle = false;

  /**
   * @var Zend_Log
   */
  protected $_log;



  // Main

  /**
   * Constructor
   *
   * @param Zend_Db_Table_Row_Abstract $task
   */
  public function __construct(Zend_Db_Table_Row_Abstract $task)
  {
    $this->_task = $task;
  }
}