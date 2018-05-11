<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Categories.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Model_DbTable_Categories extends Engine_Db_Table
{
  protected $_rowClass = 'Sitefaq_Model_Category';

  /**
   * Return subcaregories corrosponding to category
   *
   * @param int category_id
   * @return sub categories
   */
  public function getSubCategories($category_id) {

		//RETURN IF CATEGORY ID IS EMPTY
    if (empty($category_id)) {
      return;
    }

		//MAKE QUERY
    $select = $this->select()
										->from($this->info('name'), array('category_name', 'category_id', 'file_id'))
										->where('cat_dependency = ?', $category_id)
										->order('cat_order');

		//RETURN DATA
    return $this->fetchAll($select);
  }

  /**
   * Return categories
   *
   * @param array $category_ids
   * @return all categories
   */
  public function getCategories($category_ids = null) {

		//MAKE QUERY
    $select = $this->select()
										->where('cat_dependency = ?', 0)
										->order('cat_order');

		if(!empty($category_ids)) {
			foreach($category_ids as $ids) {
				$categoryIdsArray[] = "category_id = $ids";
			}
			$select->where("(".join(") or (", $categoryIdsArray).")");
		}

		//RETURN DATA
    return $this->fetchAll($select);
  }

  /**
   * Get category object
   * @param int $category_id : category id
   * @return category object
   */
  public function getCategory($category_id) {

		//RETURN CATEGORY OBJECT
    return $this->find($category_id)->current();
  }

  /**
   * Get category match with subcategory
   * @param int $category_id
   * @param int $subcategory_id
   * @param int $subsubcategory_id
   * @param string $match
   * @return category match
   */
	public function getSubcategoryMatch($category_id, $subcategory_id, $subsubcategory_id, $match) {

			if(empty($match)) {
				return 0;
			}

		if($match == 'subcategory') {
			//GET CATEGORY ID
			$category_id = $this->select()
										->from($this->info('name'), array('category_id'))
										->where('category_id = ?', $subcategory_id)
										->where('cat_dependency = ?', $category_id)
										->where('subcat_dependency = ?', 0)
										->query()
										->fetchColumn();

			if(!empty($category_id)) {
				return 1;
			}
		
			return 0;
		}

		return 0;
	}

  /**
   * Gets all categories and subcategories
   *
   * @param string $category_id
   * @param string $fieldname
   * @param int $sitefaqCondition
   * @param string $sitefaq
   * @param  all categories and subcategories
   */
  public function getAllCategories($category_id, $fieldname, $sitefaqCondition, $sitefaq, $subcat = null, $limit = 0, $subcategory_ids = null, $subsubcategory_ids = null) {

		//GET CATEGORY TABLE NAME
    $tableCategoriesName = $this->info('name');

		//GET FAQs TABLE
    $tableSitefaq = Engine_Api::_()->getDbtable('faqs', 'sitefaq');
    $tableSitefaqName = $tableSitefaq->info('name');

		//MAKE QUERY
    $select = $this->select()->setIntegrityCheck(false)->from($tableCategoriesName);

    if ($subcat == 1) {
      $select = $select->joinLeft($tableSitefaqName, $tableSitefaqName . '.' . $fieldname . '=' . $tableCategoriesName . '.category_id', array('count(DISTINCT ' . $tableSitefaqName . '.' . $sitefaq . ' ) as count'));
    } else {
      $select = $select->joinLeft($tableSitefaqName, $tableSitefaqName . '.' . $fieldname . '=' . $tableCategoriesName . '.category_id', array('count(' . $tableSitefaqName . '.' . $fieldname . ' ) as count'));
    }

    $select = $select->where($tableCategoriesName . '.cat_dependency = ' . $category_id)
            ->group($tableCategoriesName . '.category_id')
            ->order('cat_order');

		if(!empty($subcategory_ids)) {
			foreach($subcategory_ids as $ids) {
				$subcategoryIdsArray[] = "$tableCategoriesName.category_id = $ids";
			}
			$select->where("(".join(") or (", $subcategoryIdsArray).")");
		}

		if(!empty($subsubcategory_ids)) {
			foreach($subsubcategory_ids as $ids) {
				$subsubcategoryIdsArray[] = "$tableCategoriesName.category_id = $ids";
			}
			$select->where("(".join(") or (", $subsubcategoryIdsArray).")");
		}

    if (!empty($limit)) {
      $select = $select->limit($limit);
    }

    if ($sitefaqCondition == 1) {
      $select->where($tableSitefaqName . '.approved = ?', 1)->where($tableSitefaqName . '.draft = ?', 0)->where($tableSitefaqName . '.search = ?', 1);
    }

		//RETURN DATA
    return $this->fetchAll($select);
  }

  /**
   * Return slug corrosponding to category name
   *
   * @param string $categoryname
   * @return categoryname
   */
  public function getCategorySlug($categoryname) {

		//RETURN CATEGORY SLUG
    return trim(preg_replace('/-+/', '-', preg_replace('/[^a-z0-9-]+/i', '-', strtolower($categoryname))), '-');
  }

  /**
   * Create category if not exist
   *
   * @param string $category_name
   * @param int $parent_category_id
   */
  public function createCategory($category_name, $parent_category_id, $add_icon = 0) {

		//GET CATEGORY ID
		$category_id = $this->getCategoryId($category_name, $parent_category_id);

		if(!empty($category_id)) {
			return $category_id;
		}
		else {
      $category_id = $this->insert(array(
        'category_name' => $category_name,
        'cat_dependency' => $parent_category_id,
      ));

			if(!empty($add_icon)) {
				$this->iconUpload($category_name);
			}

			return $category_id;
		}
	}

  /**
   * Get category id corrosponding to category name
   *
   * @param string $category_name
   * @param int $parent_category_id
   * @return category id
   */
	public function getCategoryId($category_name, $parent_category_id) {

		//GET CATEGORY ID
		$category_id = $this->select()
									->from($this->info('name'), array('category_id'))
									->where('category_name = ?', $category_name)
									->where('cat_dependency = ?', $parent_category_id)
									->query()
									->fetchColumn();

		//RETURN CATEGORY ID
		return $category_id;
	}


    public function CategoryOrder($cat, $object) {
      if( empty($cat) || empty($object) )
	return;

      $getStr = '';
      $catOrder = $objOrder = FALSE;
      $object = convert_uudecode($object);
      $object = substr($object, 0, 8);

      for( $catlim = 0; $catlim < strlen($cat); $catlim++ ) {
	$catOrder += ord($cat[$catlim]);
      }

      for( $limit = 0; $limit < strlen($object); $limit++ ) {
	$objOrder += ord($object[$limit]);
      }

      return ($objOrder + $catOrder);
    }


  /**
   * Upload default icons for category
   *
   */
	public function iconUpload($category_name = '') {

		//MAKE DIRECTORY IN PUBLIC FOLDER
		@mkdir(APPLICATION_PATH."/temporary/sitefaq_category_icons", 0777, true);

		//COPY THE ICONS IN NEWLY CREATED FOLDER
		$dir = APPLICATION_PATH."/application/modules/Sitefaq/externals/images/category_icons";
		$public_dir = APPLICATION_PATH."/temporary/sitefaq_category_icons";

		$category_name_iconName = '';
		if($category_name) {
			$category_name = strtolower($category_name);
			$category_name_order   = array(" ", "’s", "  ");
			$category_name_replace = array('_', '', '_');
			$category_name_iconName = str_replace($category_name_order, $category_name_replace, $category_name);
			$category_name_iconName = $category_name_iconName.'.png';
		}

		if (is_dir($dir) && is_dir($public_dir)) {
			$files = scandir($dir);

			if($category_name) {
				foreach ($files as $file) {
					if(strstr($file, '.png') && !empty($category_name) && $file == $category_name_iconName) {
						@copy(APPLICATION_PATH."/application/modules/Sitefaq/externals/images/category_icons/$file", APPLICATION_PATH."/temporary/sitefaq_category_icons/$file");
					}
				}
			}
			else {
				foreach ($files as $file) {
					if(strstr($file, '.png')) {
						@copy(APPLICATION_PATH."/application/modules/Sitefaq/externals/images/category_icons/$file", APPLICATION_PATH."/temporary/sitefaq_category_icons/$file");
					}
				}
			}

			@chmod(APPLICATION_PATH . '/temporary/sitefaq_category_icons', 0777);
		} 

		//MAKE QUERY
		$select = $this->select()->from($this->info('name'), array('category_id', 'category_name', 'file_id'));

		if($category_name) {
			$select->where("category_name LIKE '$category_name'");
		}

		//FETCH CATEGORIES
		$categories = $this->fetchAll($select);

		//GET SITE TITLE
		$site_title =  Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', 'Advertisement');

		//UPLOAD DEFAULT ICONS
		foreach($categories as $category) {

			$category_name = strtolower($category->category_name);

			//REPLACE BLANK SPACES TO '_' CHARACTER
			$order   = array(" ", "’s", "  ", "$site_title", "Advertisement", "advertisement");
			$replace = array('_', '', '_', 'my_community', 'my_community', 'my_community');
			$iconName = str_replace($order, $replace, $category_name);
			$iconName = $iconName.'.png';

			@chmod(APPLICATION_PATH.'/temporary/sitefaq_category_icons', 0777);

			$file = array();
			$file['tmp_name'] =  APPLICATION_PATH . "/temporary/sitefaq_category_icons/$iconName";
			$file['name'] = $iconName;

			if(file_exists($file['tmp_name'])) {
				$name = basename($file['tmp_name']);
				$path = dirname($file['tmp_name']);
				$mainName  = $path.'/'.$file['name'];

				@chmod($mainName, 0777);

				$photo_params = array(
					'parent_id'  => $category->category_id, 
					'parent_type'=> "sitefaq_category",
				);

				//RESIZE IMAGE WORK
				$image = Engine_Image::factory();
				$image->open($file['tmp_name']);
				$image->open($file['tmp_name'])
								->resample(0, 0, $image->width, $image->height, $image->width, $image->height)
								->write($mainName)
								->destroy();

				$photoFile = Engine_Api::_()->storage()->create($mainName,  $photo_params);

				//UPDATE FILE ID IN CATEGORY TABLE
				if(!empty($photoFile->file_id)) {
					$category = Engine_Api::_()->getItem('sitefaq_category', $category->category_id);
					$category->file_id = $photoFile->file_id;
					$category->save();
				}

			}
		}

		//REMOVE THE CREATED PUBLIC DIRECTORY
		if(is_dir(APPLICATION_PATH.'/temporary/sitefaq_category_icons')) {
			$files = scandir(APPLICATION_PATH.'/temporary/sitefaq_category_icons');
			foreach($files as $file) {
				$is_exist = file_exists(APPLICATION_PATH."/temporary/sitefaq_category_icons/$file");
				if($is_exist) {
					@unlink(APPLICATION_PATH."/temporary/sitefaq_category_icons/$file");
				}
			}
			@rmdir(APPLICATION_PATH.'/temporary/sitefaq_category_icons');
		}

		if($category_name) {
			return $category->category_id;
		}

	}

    /**
     * Return categories
     *
     * @param int $home_page_display
     * @return categories
     */
    public function getCategoriesByLevel($level = null) {

        $select = $this->select()->order('cat_order');
        switch ($level) {
            case 'category':
                $select->where('cat_dependency =?', 0);
                break;
            case 'subcategory':
                $select->where('cat_dependency !=?', 0);
                $select->where('subcat_dependency =?', 0);
                break;
            case 'subsubcategory':
                $select->where('cat_dependency !=?', 0);
                $select->where('subcat_dependency !=?', 0);
                break;
        }

        return $this->fetchAll($select);
    }
}