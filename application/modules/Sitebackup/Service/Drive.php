<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: Drive.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
class Sitebackup_Service_Drive
{

  protected $_client;

  protected function getClient()
  {
    return $this->_client;
  }

  protected $scope = array('https://www.googleapis.com/auth/drive');

  public function __construct(array $config)
  {
    require_once dirname(__FILE__) . '/Google/autoload.php';

    $this->_client = $client = new Google_Client();

    $client->setClientId($config['clientid']);
    $client->setClientSecret($config['clientsecret']);
    $client->setRedirectUri($config['url']);
    $client->setScopes(array('https://www.googleapis.com/auth/drive'));
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
  }

  //Create url for authentication  
  public function authpermission()
  {
    $authUrl = $this->getClient()->createAuthUrl();
    return $authUrl;
  }

  // Generate the access token array and return the refresh_token
  public function generateAccessToken($code)
  {
    $authCode = trim($code);
    $this->getClient()->authenticate($authCode);
    $token = json_decode($this->getClient()->getAccessToken());
    $tokens = $this->getClient()->getAccessToken();
    $this->getClient()->setAccessToken($tokens);
    return $token->refresh_token;
  }

  // Generate the access token array by using the refresh_token
  public function generateRefreshToken($refreshToken)
  {
    if( $refreshToken ) {
      $this->getClient()->refreshToken($refreshToken);
      $tokens = $this->getClient()->getAccessToken();
      $this->getClient()->setAccessToken($tokens);
    }
    return $tokens;
  }

  // Upload a database backup file to drive 
  public function uploadFile($backupfile)
  {
    $service = new Google_Service_Drive($this->getClient());
    try {
      //Insert a file
      $file = new Google_Service_Drive_DriveFile();
      $file->setTitle(basename($backupfile));
      $file->setMimeType('application/gzip');

      $data = file_get_contents($backupfile);

      $createdFile = $service->files->insert($file, array(
        'data' => $data,
        'mimeType' => 'application/gzip',
        'uploadType' => 'media'
      ));
    } catch( Exception $e ) {
      print $e->getMessage();
    }
    return $createdFile->id;
  }

  public function deleteFile($fileId)
  {
    try {
      $service = new Google_Service_Drive($this->getClient());
      $delete = $service->files->delete($fileId);
    } catch( Exception $e ) {
      return "Error: " . $e->getMessage();
    }
    return true;
  }

}
