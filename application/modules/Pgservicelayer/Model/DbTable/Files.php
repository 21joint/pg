<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_Model_DbTable_Files extends Storage_Model_DbTable_Files
{
  protected $_rowClass = 'Storage_Model_File';
  protected $_name = 'storage_files';


    public function createFile($file, $params)
  {
      $space_limit = (int) Engine_Api::_()->getApi('settings', 'core')
          ->getSetting('core_general_quota', 0);

      $tableName = $this->info('name');

      // fetch user
      if( !empty($params['user_id']) &&
          null != ($user = Engine_Api::_()->getItem('user', $params['user_id'])) ) {
        $user_id = $user->getIdentity();
        $level_id = $user->level_id;
      } else if( null != ($user = Engine_Api::_()->user()->getViewer()) ) {
        $user_id = $user->getIdentity();
        $level_id = !empty($user_id) ? $user->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
      } else {
        $user_id = null;
        $level_id = null;
      }

      // member level quota
      if( null !== $user_id && null !== $level_id ) {
        $space_limit = (int) Engine_Api::_()->authorization()->getPermission($level_id, 'user', 'quota');
        $space_used = (int) $this->select()
          ->from($tableName, new Zend_Db_Expr('SUM(size) AS space_used'))
          ->where("user_id = ?", (int) $user_id)
          ->query()
          ->fetchColumn(0);
        $space_required = (is_array($file) && isset($file['tmp_name'])
          ? filesize($file['tmp_name']) : filesize($file));

        if( $space_limit > 0 && $space_limit < ($space_used + $space_required) ) {
          throw new Engine_Exception("File creation failed. You may be over your " .
            "upload limit. Try uploading a smaller file, or delete some files to " .
            "free up space. ", self::SPACE_LIMIT_REACHED_CODE);
        }
      }

      $row = $this->createRow();
      $row->setFromArray($params);
      $row->store($file);

      return $row;
    }
    public function setPhoto($photo) {

    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
      $fileName = $file;
    } else if ($photo instanceof Storage_Model_File) {
        $file = $photo->temporary();
        $fileName = $photo->name;
    } else if ($photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id)) {
          $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
          $file = $tmpRow->temporary();
          $fileName = $tmpRow->name;
    } else if (is_array($photo) && !empty($photo['tmp_name'])) {
            $file = $photo['tmp_name'];
            $fileName = $photo['name'];
    } else if (is_string($photo) && file_exists($photo)) {
              $file = $photo;
              $fileName = $photo;
            } else {
      throw new User_Model_Exception('invalid argument passed to setPhoto');
    }

    if (!$fileName) {
      $fileName = $file;
    }

    $name = basename($file);
    $extension = ltrim(strrchr($fileName, '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH.DIRECTORY_SEPARATOR.'temporary';
    $viewer = Engine_Api::_()->user()->getViewer();
    $params = array(
      'parent_type' => "user",
      'parent_id'   => (int)$viewer->getIdentity(),
      'user_id'     => (int)$viewer->getIdentity(),
      'name'        => $fileName,
    );

    // Add autorotation for uploded images. It will work only for SocialEngine-4.8.9 Or more then.
    $usingLessVersion = Engine_Api::_()->seaocore()->usingLessVersion('core', '4.8.9');
    if (!empty($usingLessVersion)) {
      $filesTable = $this;
      $mainPath = $path.DIRECTORY_SEPARATOR.$base.'_m.'.$extension;
      $image = Engine_Image::factory();
      $image->open($file)
        ->resize(720, 720)
        ->write($mainPath)
        ->destroy();
      $normalPath = $path.DIRECTORY_SEPARATOR.$base.'_in.'.$extension;
      $image = Engine_Image::factory();
      $image->open($file)
        ->resize(140, 160)
        ->write($normalPath)
        ->destroy();
      $normalLargePath = $path.DIRECTORY_SEPARATOR.$base.'_inl.'.$extension;
      $image = Engine_Image::factory();
      $image->open($file)
        ->resize(250, 250)
        ->write($normalLargePath)
        ->destroy();
    } else {
      $filesTable = $this;
      $mainPath = $path.DIRECTORY_SEPARATOR.$base.'_m.'.$extension;
      $image = Engine_Image::factory();
      $image->open($file)
        ->autoRotate()
        ->resize(720, 720)
        ->write($mainPath)
        ->destroy();
      $normalPath = $path.DIRECTORY_SEPARATOR.$base.'_in.'.$extension;
      $image = Engine_Image::factory();
      $image->open($file)
        ->autoRotate()
        ->resize(140, 160)
        ->write($normalPath)
        ->destroy();
      $normalLargePath = $path.DIRECTORY_SEPARATOR.$base.'_inl.'.$extension;
      $image = Engine_Image::factory();
      $image->open($file)
        ->autoRotate()
        ->resize(250, 250)
        ->write($normalLargePath)
        ->destroy();
    }

    //RESIZE IMAGE (ICON)
    $iSquarePath = $path.DIRECTORY_SEPARATOR.$base.'_is.'.$extension;
    $image = Engine_Image::factory();
    $image->open($file);

    $size = min($image->height, $image->width);
    $x = ($image->width - $size) / 2;
    $y = ($image->height - $size) / 2;

    $image->resample($x, $y, $size, $size, 48, 48)
      ->write($iSquarePath)
      ->destroy();
    try {
      $iMain = $filesTable->createFile($mainPath, $params);
      $iIconNormal = $filesTable->createFile($normalPath, $params);
      $iMain->bridge($iIconNormal, 'thumb.normal');
      $iIconNormalLarge = $filesTable->createFile($normalLargePath, $params);
      $iMain->bridge($iIconNormalLarge, 'thumb.profile');
      $iSquare = $filesTable->createFile($iSquarePath, $params);
      $iMain->bridge($iSquare, 'thumb.icon');
    } catch (Exception $e) {
      @unlink($mainPath);
      @unlink($normalPath);
      @unlink($normalLargePath);
      @unlink($iSquarePath);
      if ($e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE) {
        throw new Album_Model_Exception($e->getMessage(), $e->getCode());
      } else {
        throw $e;
      }
    }
    @unlink($mainPath);
    @unlink($normalPath);
    @unlink($normalLargePath);
    return $iMain;
  }
  public function uploadImage($inputStream){
    $image = imagecreatefromstring($inputStream);
    $info = getimagesizefromstring($inputStream);
    if (!is_resource($image) || empty($info)) {
      return null;
    }
    $randomImageName = rand(1000000, 9999999);;
    $imageLib = Engine_Image::factory();
    $type = $imageLib->image_type_to_extension($info[2], false);
    $type = strtolower($type);
      $file = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary'.DIRECTORY_SEPARATOR.$randomImageName.".".$type;
    $width = $info[0];
    $height = $info[1];
    $function = 'image'.$type;
    $quality = null;
    if ($function == 'imagejpeg' && null !== $quality) {
      $result = $function($image, $file, $quality);
    } elseif ($function == 'imagepng' && null !== $quality) {
        $result = $function($image, $file, round(abs(($quality - 100) / 11.111111)));
    } else {
      $result = $function($image, $file);
    }
    return $file;
  }

  public function updatePhotoParent($file_id, Core_Model_Item_Abstract $parent){
    if (empty($file_id)) {
      return false;
    }
    $fileObject = $this->getFile($file_id);
    if (empty($fileObject)) {
      return false;
    }

    $fileObject->parent_type = $parent->getType();
    $fileObject->parent_id = $parent->getIdentity();
    $user_id = 0;
    if (isset($parent->user_id)) {
      $user_id = $parent->user_id;
    }
    if (isset($parent->owner_id)) {
      $user_id = $parent->owner_id;
    }
    $fileObject->user_id = $user_id;
    $fileObject->save();

      $this->update(array('parent_type' => $parent->getType(),'parent_id' => $parent->getIdentity(),'user_id' => $user_id), array(
          'parent_file_id = ?' => $fileObject->getIdentity()
      ));
    return true;
  }
}
