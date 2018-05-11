<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: AdminBackupsettingsController.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
class Sitebackup_Service_S3
{

  /**
   * @var Zend_Service_Amazon_S3
   */
  protected $_internalService;
  protected $_bucket;
  protected $_streamWrapperName;

  public function __construct(array $config)
  {
    if( empty($config['bucket']) ) {
      throw new Storage_Service_Exception('No bucket specified');
    }

    $this->_bucket = $config['bucket'];
    $this->_internalService = new Zend_Service_Amazon_S3(
      $config['accesskey'], $config['secretKey'], $config['region']
    );

    // Should we register the stream wrapper?
    $this->_streamWrapperName = 's3' . (int) @$config['destination_id'];
    $this->_internalService->registerStreamWrapper($this->_streamWrapperName);
  }

  /**
   * Creates a new file from data rather than an existing file
   *
   * @param string $path full path with which the file will be saved in s3 bucket
   * @param string $data
   */
  // public function write(Storage_Model_File $model, $data)
  public function write($path, $data)
  {
    // Copy file
    try {

      $return = $this->_internalService->putObject($this->_bucket . '/' . $path, $data, array(
        Zend_Service_Amazon_S3::S3_ACL_HEADER => Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ,
        'Cache-Control' => 'max-age=864000, public',
      ));
      if( !$return ) {
        throw new Storage_Service_Exception('Unable to write file.');
      }
    } catch( Exception $e ) {
      throw $e;
    }

    return $path;
  }

  public function remove($path, $dir)
  {
    // Delete file
    try {
      $return = $this->_internalService->removeObject($this->_bucket . '/' . $dir . '/' . $path);
      if( !$return ) {
        throw new Storage_Service_Exception('Unable to delete file.');
      }
    } catch( Exception $e ) {
      throw $e;
    }

    return $return;
  }

}
