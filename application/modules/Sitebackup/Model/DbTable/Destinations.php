<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: Destinations.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
class Sitebackup_Model_DbTable_Destinations extends Engine_Db_Table
{

  protected $_rowClass = "Sitebackup_Model_Destinations";

  public function getConfigData($id)
  {
    $select = $this->select()
      ->where('destinations_id = ?', $id);
    $row = $this->fetchRow($select);
    $config = array();
    if( $row['destination_mode'] == 4 ) {
      $config['accesskey'] = $row['accesskey'];
      $config['secretKey'] = $row['secretkey'];
      $config['bucket'] = $row['bucket'];
      $config['region'] = $row['region'];
      $config['destination_id'] = $row['destinations_id'];
    } else if( $row['destination_mode'] == 5 ) {
      $config['clientid'] = $row['clientid'];
      $config['clientsecret'] = $row['clientsecret'];
      $config['refresh_token'] = $row['refresh_token'];
      $base = Zend_Controller_Front::getInstance()->getBaseUrl();
      $url = ((!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $base . '/admin/sitebackup/destinationsettings/verify';
      $config['url'] = $url;
    } else if( $row['destination_mode'] == 6 ) {
      $config['key'] = $row['appkey'];
      $config['secret'] = $row['appsecret'];
      $config['token'] = $row['access_token'];
    }
    return $config;
  }

}
