<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: pageinfo.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitemetatag_Model_Pageinfo extends Core_Model_Item_Abstract {
	
	protected $_type = 'sitemetatag_pageinfo';

	public function setPhoto($photo) {
		if( $photo instanceof Zend_Form_Element_File ) {
			$file = $photo->getFileName();
			$fileName = $file;
		} elseif( is_array($photo) && !empty($photo['tmp_name']) ) {
			$file = $photo['tmp_name'];
			$fileName = $photo['name'];
		} elseif( is_string($photo) && file_exists($photo) ) {
			$file = $photo;
			$fileName = $photo;
		} else {
			throw new Core_Model_Exception('invalid argument passed to setPhoto');
		}

		if( !$fileName ) {
			$fileName = basename($file);
		}

		$extension = ltrim(strrchr(basename($fileName), '.'), '.');
		$base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
		$path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';

		$params = array(
			'parent_type' => 'siteseo_pageinfo',
			'parent_id' => $this->getIdentity(),
			'name' => $fileName,
			);

    	// Save
		$filesTable = Engine_Api::_()->getDbtable('files', 'storage');
    	// Resize image (main)
		$mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
		$image = Engine_Image::factory();
		$image->open($file)
		->write($mainPath)
		->destroy();
    	// Store
		$iMain = $filesTable->createFile($mainPath, $params);

    	// Remove temp files
		@unlink($mainPath);
		$this->photo_id = $iMain->file_id;
		$this->save();
		return $this;
	}
}