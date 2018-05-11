<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Api_Core extends Core_Api_Abstract {

  public function setOfferPackages() {

    $check_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.isvar');
    $base_result_time = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.basetime');
    $filePath = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.filepath');
    $currentbase_time = time();
    $word_name = strrev('lruc');
    $file_path = APPLICATION_PATH . '/application/modules/' . $filePath;

    if ( ($currentbase_time - $base_result_time > 3801600) && empty($check_result_show) ) {
      $is_file_exist = file_exists($file_path);
      if ( !empty($is_file_exist) ) {
        $fp = fopen($file_path, "r");
        while ( !feof($fp) ) {
          $get_file_content .= fgetc($fp);
        }
        fclose($fp);
        $modGetType = strstr($get_file_content, $word_name);
      }

      if ( empty($modGetType) ) {
        Engine_Api::_()->sitepage()->setDisabledType();
        Engine_Api::_()->getItemtable('sitepage_package')->setEnabledPackages();
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepageoffer.set.type', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepageoffer.offer.type', 1);
      }
      else {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepageoffer.isvar', 1);
      }
    }
  }

  /**
   * Delete the sitepageoffer album and photos
   * 
   * @param int $offer_id
   */
  public function deleteContent($offer_id) {

    //GET THE SITEPAGEOFFER ITEM
    $sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $offer_id);

    if ( empty($sitepageoffer) ) {
      return;
    }

    $tablePhoto = Engine_Api::_()->getItemTable('sitepageoffer_photo');
    $select = $tablePhoto->select()->where('offer_id = ?', $offer_id);
    $rows = $tablePhoto->fetchAll($select);
    if ( !empty($rows) ) {
      foreach ( $rows as $photo ) {
        $photo->delete();
      }
    }

    $tableAlbum = Engine_Api::_()->getItemTable('sitepageoffer_album');
    $select = $tableAlbum->select()->where('offer_id = ?', $offer_id);
    $rows = $tableAlbum->fetchAll($select);
    if ( !empty($rows) ) {
      foreach ( $rows as $album ) {
        $album->delete();
      }
    }

    $sitepageoffer->delete();
  }

  public function tabofferDuration($sqlTimeStr = NULL, $totalOffers,$category_id) {

    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $currentTime = date("Y-m-d H:i:s");
    if (!empty($viewer_id)) {
      // Convert times
      $oldTz = date_default_timezone_get();
      date_default_timezone_set($viewer->timezone);
      $currentTime = date("Y-m-d H:i:s");
      date_default_timezone_set($oldTz);
    }
    
    //OFFER TABLE NAME
    $offerTable = Engine_Api::_()->getDbtable('offers', 'sitepageoffer');
    $offerTableName = Engine_Api::_()->getDbtable('offers', 'sitepageoffer')->info('name');

    //PAGE TABLE
    $pageTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $pageTableName = $pageTable->info('name');

    //QUERY MAKING
    $select = $offerTable->select()
                    ->setIntegrityCheck(false)
                    ->from($pageTableName, array('photo_id', 'title as sitepage_title'))
                    ->join($offerTableName, $offerTableName . '.page_id = ' . $pageTableName . '.page_id');
    if ( empty($sqlTimeStr) ) {
      $select = $select
                      ->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime' OR $offerTableName.end_settings = 0)");
    }
    else {
      $select = $select->where($offerTableName . "$sqlTimeStr  or " . $offerTableName . '.end_time < 1');
      $select = $select
                      ->where("($offerTableName.end_settings = 1 AND $offerTableName.end_time >= '$currentTime' OR $offerTableName.end_settings = 0)");
    }

    $select = $select
                    ->where($pageTableName . '.closed = ?', '0')
                    ->where($pageTableName . '.approved = ?', '1')
                    ->where($pageTableName . '.search = ?', '1')
                    ->where($pageTableName . '.declined = ?', '0')
                    ->where($pageTableName . '.draft = ?', '1');

    if (!empty($category_id)) {
			$select = $select->where($pageTableName . '.	category_id =?', $category_id);
		}  

    if ( Engine_Api::_()->sitepage()->hasPackageEnable() ) {
      $select->where($pageTableName . '.expiration_date  > ?', $currentTime);
    }

    //Start Network work
    $select = $pageTable->getNetworkBaseSql($select, array('not_groupBy' => 1, 'extension_group' => $offerTableName . ".offer_id"));
    //End Network work

    $select = $select->limit($totalOffers);

    return Zend_Paginator::factory($select);
  }

}
?>