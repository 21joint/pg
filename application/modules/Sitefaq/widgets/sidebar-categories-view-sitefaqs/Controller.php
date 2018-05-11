<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Widget_SidebarCategoriesViewSitefaqsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

		//DON'T RENDER IF SUBJECT IS NOT SET
		if(!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
			return $this->setNoRender();
		}

		//GET FAQ SUBJECT
		$sitefaq = Engine_Api::_()->core()->getSubject();
		if(empty($sitefaq)) {
			return $this->setNoRender();
		}

		//GET STORAGE API
		$this->view->storage = Engine_Api::_()->storage();

		//GET CATEGORY ID
		$category_ids = Zend_Json_Decoder::decode($sitefaq->category_id);

		//DON'T RENDER IF CATEGORY ID IS EMPTY
		if(empty($category_ids) || (Count($category_ids) == 1 && $category_ids[0] == 0)) {
			return $this->setNoRender();
		}

		//GET TRUNCATION LIMIT
		$this->view->catTruncLimit = 37;
		$this->view->subCatTruncLimit = $this->view->subsubCatTruncLimit = 21;

		$this->view->sitefaq_api = Engine_Api::_()->sitefaq();

		//GET SUB-CATEGORY ID
		$subcategory_ids = Zend_Json_Decoder::decode($sitefaq->subcategory_id);

		//GET 3RD LEVEL CATEGORY ID
		$subsubcategory_ids = Zend_Json_Decoder::decode($sitefaq->subsubcategory_id);

		//GET DOCUMENT CATEGORY TABLE
    $tableCategory = Engine_Api::_()->getDbTable('categories', 'sitefaq');

    $categories = array();
    $category_info = $tableCategory->getCategories($category_ids);
    foreach ($category_info as $value) {
      $sub_cat_array = array();
      $category_info2 = $tableCategory->getAllCategories($value['category_id'], 'subcategory_id', 0, 'subcategory_id', 0, 0, $subcategory_ids, null);
      foreach($category_info2 as $subresults) {
        $treesubarray = array();
        $subcategory_info2 = $tableCategory->getAllCategories($subresults['category_id'], 'subcategory_id', 0, 'subcategory_id', 0, 0, null, $subsubcategory_ids);
        $treesubarrays[$subresults->category_id] = array();
        foreach($subcategory_info2 as $subvalues) {
           $treesubarrays[$subresults->category_id][] = $treesubarray = array('tree_sub_cat_id' => $subvalues->category_id,
            'tree_sub_cat_name' => $subvalues->category_name,
            'order' => $subvalues->cat_order,
						'file_id' => $subvalues->file_id,
            );
        }

        $tmp_array = array('sub_cat_id' => $subresults->category_id,
            'sub_cat_name' => $subresults->category_name,
            'tree_sub_cat' => $treesubarrays[$subresults->category_id],
            'order' => $subresults->cat_order,
						'file_id' => $subresults->file_id,
						);
        $sub_cat_array[] = $tmp_array;
      }

      $categories[] = $category_array = array('category_id' => $value->category_id,
          'category_name' => $value->category_name,
          'order' => $value->cat_order,
          'sub_categories' => $sub_cat_array,
					'file_id' => $value->file_id,
					);
    }
    
    $this->view->categories = $categories;

		$request = Zend_Controller_Front::getInstance()->getRequest();

    //GET CATEGORY TABLE
		$this->view->categoryTable = $categoryTable = Engine_Api::_()->getDbtable('categories', 'sitefaq');

		//CATEGORY DETAILS INITILIZATION
		$this->view->category = 0;
		$this->view->subcategorys = 0;
		$this->view->subsubcategorys = 0;

		//IF CATEGORY ID NOT EMPTY IN URL
		if($request->getParam('category_id')) {

			//GET FIRST CATEGORY ID
			$this->view->category = $request->getParam('category_id');

			//GET SUB-CATEGORY ID FROM URL
			if($request->getParam('subcategory_id')) {
				$this->view->subcategorys = $request->getParam('subcategory_id');

				//GET 3RD LEVEL CATEGORY ID FROM URL
				if(!empty($this->view->subcategorys) && $request->getParam('subsubcategory_id')) {
					$this->view->subsubcategorys = $request->getParam('subsubcategory_id');
				}
			}
		}
		else {
			//GET FIRST CATEGORY ID
			if(!empty($sitefaq->category_id)) {
				$first_category_id_array = Zend_Json_Decoder::decode($sitefaq->category_id);
				$this->view->category = $first_category_id_array[0];

				//RETURN IF CATEGORY ID IS NOT EXIST
				if(!empty($this->view->category)) {

					//GET FIRST SUB-CATEGORY ID
					if(!empty($sitefaq->subcategory_id)) {
						$first_subcategory_id_array = Zend_Json_Decoder::decode($sitefaq->subcategory_id);
						$this->view->subcategorys = $first_subcategory_id_array[0];

						if(!empty($this->view->subcategorys) && !empty($sitefaq->subsubcategory_id)) {
							//GET FIRST 3RD LEVEL CATEGORY ID
							$first_subsubcategory_id_array = Zend_Json_Decoder::decode($sitefaq->subsubcategory_id);
							$this->view->subsubcategorys = $first_subsubcategory_id_array[0];
						}
					}
				}
			}
		}
  }

}