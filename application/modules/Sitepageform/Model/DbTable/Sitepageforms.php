<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sitepageforms.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageform_Model_DbTable_Sitepageforms extends Engine_Db_Table {

  protected $_rowClass = "Sitepageform_Model_Sitepageform";

  function getFormData($page_id) {
    $formSelect = $this->select()->where('page_id = ?', $page_id);
    return $formSelectData = $this->fetchRow($formSelect);
  }

}
?>