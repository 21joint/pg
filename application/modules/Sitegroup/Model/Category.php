<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegroup
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Category.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegroup_Model_Category extends Core_Model_Item_Abstract {

  protected $_searchTriggers = false;

  /**
   * Gets Category Table
   *
   * @return table
   */
  public function getTable() {

    if (is_null($this->_table)) {
      $this->_table = Engine_Api::_()->getDbtable('categories', 'sitegroup');
    }

    return $this->_table;
  }

  public function getTitle($inflect = false) {
    if ($inflect) {
      return ucwords($this->category_name);
    } else {
      return $this->category_name;
    }
  }

  public function getHref($params = array()) {

    if ($this->subcat_dependency) {
      $type = 'subsubcategory';
      $params['subsubcategory_id'] = $this->category_id;
      $params['subsubcategoryname'] = $this->getCategorySlug();
      $cat = $this->getTable()->getCategory($this->cat_dependency);
      $params['subcategory_id'] = $cat->category_id;
      $params['subcategoryname'] = $cat->getCategorySlug();
      $cat = $this->getTable()->getCategory($cat->cat_dependency);
      $params['category_id'] = $cat->category_id;
      $params['categoryname'] = $cat->getCategorySlug();
    } else if ($this->cat_dependency) {
      $type = 'subcategory';
      $params['subcategory_id'] = $this->category_id;
      $params['subcategoryname'] = $this->getCategorySlug();
      $cat = $this->getTable()->getCategory($this->cat_dependency);
      $params['category_id'] = $cat->category_id;
      $params['categoryname'] = $cat->getCategorySlug();
    } else {
      $type = 'category';
      $params['category_id'] = $this->category_id;
      $params['categoryname'] = $this->getCategorySlug();
    }
    $params = array_merge(array(
        'route' => "sitegroup_general_" . $type,
        'reset' => true,
            ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
  }

  /**
   * Return slug corrosponding to category name
   *
   * @return categoryname
   */
  public function getCategorySlug() {

return Engine_Api::_()->seaocore()->getSlug($this->category_name, 225);;  
//    $slug = $this->category_name;
//    setlocale(LC_CTYPE, 'pl_PL.utf8');
//    $slug = @iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
//    $slug = strtolower($slug);
//    $slug = strtr($slug, array('&' => '-', '"' => '-', '&' . '#039;' => '-', '<' => '-', '>' => '-', '\'' => '-'));
//    $slug = preg_replace('/^[^a-z0-9]{0,}(.*?)[^a-z0-9]{0,}$/si', '\\1', $slug);
//    $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
//    $slug = preg_replace('/[\-]{2,}/', '-', $slug);
//
//
//    return ucfirst($slug);
  }

    /**
   * Set category icon
   *
   */
  public function setPhoto($photo) {

		//GET PHOTO DETAILS
		$name = basename($photo['tmp_name']);
		$path = dirname($photo['tmp_name']);
		$mainName  = $path.'/'.$photo['name'];

		//GET VIEWER ID
		$viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
		$photo_params = array(
			'parent_id'  => $this->category_id, 
			'parent_type'=> "sitegroup_category",
		);

		//RESIZE IMAGE WORK
		$image = Engine_Image::factory();
		$image->open($photo['tmp_name']);
		$image->open($photo['tmp_name'])
						->resample(0, 0, $image->width, $image->height, $image->width, $image->height)
						->write($mainName)
						->destroy();

		try {
			$photoFile = Engine_Api::_()->storage()->create($mainName,  $photo_params); }
		catch (Exception $e) { 
			if ($e->getCode() == Storage_Api_Storage::SPACE_LIMIT_REACHED_CODE)
			{
				echo $e->getMessage();
				exit();
			}
		}

		return $photoFile;
	}
}

?>