<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Storage
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Core.php 9747 2012-07-26 02:08:08Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Application_Core
 * @package    Storage
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Storage_Api_Core extends Core_Api_Abstract
{
  const SPACE_LIMIT_REACHED_CODE = 3999;
  
  public function getService($serviceIdentity = null)
  {
    return Engine_Api::_()->getDbtable('middleware', 'storage')
      ->getService($serviceIdentity);
  }

  public function get($id, $relationship = null)
  {
    return Engine_Api::_()->getItemTable('storage_file')
        ->getFile($id, $relationship);
  }

  public function lookup($id, $relationship)
  {
    return Engine_Api::_()->getItemTable('storage_file')
        ->lookupFile($id, $relationship);
  }

  public function create($file, $params)
  {
    return Engine_Api::_()->getItemTable('storage_file')
        ->createFile($file, $params);
  }

  public function getStorageLimits()
  {
    return Engine_Api::_()->getItemTable('storage_file')
        ->getStorageLimits();
  }
}
