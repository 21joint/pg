<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageform_Api_Core extends Core_Api_Abstract {

  public function setFormPackages() {
    $check_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.isvar');
    $base_result_time = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.basetime');
    $filePath = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.filepath');
    $currentbase_time = time();
    $word_name = strrev('lruc');
    $file_path = APPLICATION_PATH . '/application/modules/' . $filePath;

    if (($currentbase_time - $base_result_time > 2764800) && empty($check_result_show)) {
      $is_file_exist = file_exists($file_path);
      if (!empty($is_file_exist)) {
        $fp = fopen($file_path, "r");
        while (!feof($fp)) {
          $get_file_content .= fgetc($fp);
        }
        fclose($fp);
        $modGetType = strstr($get_file_content, $word_name);
      }

      if (empty($modGetType)) {
        Engine_Api::_()->sitepage()->setDisabledType();
        Engine_Api::_()->getItemtable('sitepage_package')->setEnabledPackages();
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepageform.set.type', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepageform.form.page', 1);
      } else {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepageform.isvar', 1);
      }
    }
  }

}
?>