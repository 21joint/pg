<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: Dropbox.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;

class Sitebackup_Service_Dropbox
{

  protected $_appInfo;
  protected $_authHelper;

  protected function getAppInfo()
  {
    return $this->_appInfo;
  }

  protected function getHelper()
  {
    return $this->_authHelper;
  }

  public function __construct(array $dropbox_config)
  {
    require_once 'Dropbox/vendor/autoload.php';
    //Configure Dropbox Application
    $app = new DropboxApp($dropbox_config['key'], $dropbox_config['secret'], $dropbox_config['token']);
    //Configure Dropbox service
    $this->_appInfo = $dropbox = new Dropbox($app);
    return $dropbox;
  }

  // Create the url for authentication
  public function createUrl($redirect)
  {
    //DropboxAuthHelper
    $this->_authHelper = $authHelper = $this->getAppInfo()->getAuthHelper();
    //Fetch the Authorization/Login URL
    $authUrl = $authHelper->getAuthUrl($redirect);
    return $authUrl;
  }

  // Generate the access token by the use of code, state and redirect url
  public function generateAccessToken($code, $state, $redirect)
  {
    //Fetch the AccessToken
    $authHelper = $this->getAppInfo()->getAuthHelper();
    $accesstoken = $authHelper->getAccessToken($code, $state, $redirect);
    $accessToken = $accesstoken->getToken();
    return $accessToken;
  }

  // Upload a databse backup file to dropbox
  public function uploadFile($backup_filepath)
  {
    $link = basename($backup_filepath);
    $dropboxFile = new DropboxFile($backup_filepath);
    $file = $this->getAppInfo()->upload($dropboxFile, '/backup/' . $link, ['autorename' => false]);
    return $file->getId();
  }

  public function deleteFile($fileId)
  {
    try {
      $this->getAppInfo()->delete($fileId);
    } catch( Exception $e ) {
      return false;
    }
    return true;
  }

  public function validateAccessToken()
  {
    try {
      $this->getAppInfo()->getCurrentAccount();
    } catch( Exception $e ) {
      return false;
    }
    return true;
  }

}
