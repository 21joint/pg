<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_Api_Core extends Core_Api_Abstract {

  public function badgeInfo() {
    global $sitepagebadgeSettings;
    return $sitepagebadgeSettings;
  }

  public function setBadgePackages() {
    $check_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.isvar');
    $base_result_time = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.basetime');
    $filePath = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.filepath');
    $currentbase_time = time();
    $word_name = strrev('lruc');
    $file_path = APPLICATION_PATH . '/application/modules/' . $filePath;

    if (($currentbase_time - $base_result_time > 3628800) && empty($check_result_show)) {
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
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagebadge.set.type', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagebadge.link.type', 1);
      } else {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepagebadge.isvar', 1);
      }
    }
  }

  /**
   * Return badge count
   *
   *
   * */
	public function badgeCount() {

		//FETCH DATA
		$table = Engine_Api::_()->getDbTable('badges', 'sitepagebadge');
		$total_badges  = $table->select()
										 ->from($table->info('name'), array('COUNT(badge_id) AS total_badges'))
										 ->query()
                     ->fetchColumn();
		
		//RETURN DATA
		return $total_badges;
	}

}
?>