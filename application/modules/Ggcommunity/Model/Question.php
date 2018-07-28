<?php
/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Question
 */

class Ggcommunity_Model_Question extends Core_Model_Item_Abstract
{
  // Properties
  protected $_owner_type = 'user';
  protected $_searchTriggers = array('title', 'body', 'search');

  /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
  */
  public function getHref($params = array())
  {
    $params = array_merge(array(
      'route' => 'question_profile',
      'reset' => true,
      'question_id' => $this->getIdentity(),
    ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()->assemble($params, $route, $reset);
  }

  // Set Photo for this question
  public function setPhoto($photo)
  {


    if( $photo instanceof Zend_Form_Element_File ) {
      $file = $photo->getFileName();
      $fileName = $file;
    } else if( $photo instanceof Storage_Model_File ) {
      $file = $photo->temporary();
      $fileName = $photo->name;
    } else if( $photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id) ) {
      $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
      $file = $tmpRow->temporary();
      $fileName = $tmpRow->name;
    } else if( is_array($photo) && !empty($photo['tmp_name']) ) {
      $file = $photo['tmp_name'];
      $fileName = $photo['name'];
    } else if( is_string($photo) && file_exists($photo) ) {
      $file = $photo;
      $fileName = $photo;
    } else {
      throw new Classified_Model_Exception('invalid argument passed to setPhoto');
    }

    if( !$fileName ) {
      $fileName = basename($file);
    }

    $extension = ltrim(strrchr(basename($fileName), '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    
    $params = array(
      'parent_type' => $this->getType(),
      'parent_id' => $this->getIdentity(),
      'user_id' => $this->user_id,
      'name' => $fileName,
    );


    $filesTable = Engine_Api::_()->getDbtable('files', 'storage');

    // Resize image (main)
    $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
      ->resize(300, 300)
      ->write($mainPath)
      ->destroy()
    ;

    // Resize image (normal)
    $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
      ->resize(320, 240)
      ->write($normalPath)
      ->destroy()
    ;

    // Resize image (question)
    $iQuestionPath = $path . DIRECTORY_SEPARATOR . $base . '_iq.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
      ->resize(300,300)
      ->write($iQuestionPath)
      ->destroy()
    ;


    //Store
    try {
      $iMain = $filesTable->createFile($mainPath, $params);
      $iMain->bridge($iMain, 'thumb.main');
      $iIconNormal = $filesTable->createFile($normalPath, $params);
      $iMain->bridge($iIconNormal, 'thumb.normal');
      $iQuestion = Engine_Api::_()->storage()->create($iQuestionPath, $params);
      $iMain->bridge($iQuestion, 'thumb.question');
    } catch (Exception $e) {
      @unlink($mainPath);
      @unlink($normalPath);
      @unlink($iQuestionPath);
      if ($e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE) {
        throw new Album_Model_Exception($e->getMessage(), $e->getCode());
      } else {
        throw $e;
      }
    }
    

    // Update row
    $this->modified_date = date('Y-m-d H:i:s');
    $this->photo_id = $iMain->file_id;
    $this->save();
    
    return $this;
  }
  
  public function likes()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
  }
  
  public function comments()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
  }
  
  public function getChoosenAnswer(){
      $table = Engine_Api::_()->getDbTable('answers', 'ggcommunity');
      return $table->fetchRow($table->select()->where('accepted = ?',1));
  }
  public function getTopic(){
      return Engine_Api::_()->getItem('sdparentalguide_topic', $this->topic_id);
  }
}
