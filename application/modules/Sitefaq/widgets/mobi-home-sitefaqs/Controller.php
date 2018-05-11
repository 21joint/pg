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

class Sitefaq_Widget_MobiHomeSitefaqsController extends Engine_Content_Widget_Abstract {

	public function indexAction() {

		//GET VIEWER DETAIL
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

		//GET LEVEL ID
		if(!empty($viewer_id)) {
			$level_id = $viewer->level_id;
		}
		else {
			$level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
		}

    //FETCH FAQS
    $params = array();
		if($level_id != 1) {
			$sitefaq_api = Engine_Api::_()->sitefaq();
			$params['networks'] = $sitefaq_api->getViewerNetworks();
			$params['profile_types'] = $sitefaq_api->getViewerProfiles();
			$params['member_levels'] = $sitefaq_api->getViewerLevels();
		}
    $params['limit'] = 1;
    $paginator = Engine_Api::_()->getDbtable('faqs', 'sitefaq')->widgetSitefaqsData($params);
		$this->view->total_results = Count($paginator);

		//FAQ VIEW PRIVACY
		$can_view = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'view');
		$sitefaq_categories = Zend_Registry::isRegistered('sitefaq_categories') ? Zend_Registry::get('sitefaq_categories') : null;

		//DON'T RENDER IF CAN'T VIEW AND TOTAL RESULTS ARE ZERO
		if(empty($can_view) || empty($sitefaq_categories) || $this->view->total_results <= 0) {
			return $this->setNoRender();
		}

		//SHOW FAQs OR NOT
		$this->view->show_faqs = $this->_getParam('faqs', 1);

		//GET STORAGE API
		$this->view->storage = Engine_Api::_()->storage();

		//GET CATEGORY TABLE
		$this->view->tableCategory = $tableCategory = Engine_Api::_()->getDbtable('categories', 'sitefaq');

		//GET FAQ TABLE
		$this->view->tableFaq = $tableFaq = Engine_Api::_()->getDbtable('faqs', 'sitefaq');

		//ARRAY INITILIZATION
    $this->view->categories = $categories = array();
   
		//GET CATEGORIES
    $category_info = $tableCategory->getCategories(null);

		foreach ($category_info as $value) {

			$sub_cat_array = array();
			$category_info2 = $tableCategory->getAllCategories($value['category_id'], 'subcategory_id', 0, 'subcategory_id', 0, 0, null, null);

			foreach ($category_info2 as $subresults) {

				//GET TOTAL FAQ COUNT
				$subcategory_faq_count = $tableFaq->getFaqsCount($subresults->category_id, 'subcategory_id', 1);

				if($subcategory_faq_count > 0) {
					$sub_cat_array[] = $tmp_array = array('sub_cat_id' => $subresults->category_id,
							'sub_cat_name' => $subresults->category_name,
							'count' => $subcategory_faq_count,
							'file_id' => $subresults->file_id,
							'order' => $subresults->cat_order);
				}
			}

			//GET TOTAL FAQ COUNT
			$category_faq_count = $tableFaq->getFaqsCount($value->category_id, 'category_id', 1);

			if($category_faq_count > 0) {
				$categories[] = $category_array = array('category_id' => $value->category_id,
						'category_name' => $value->category_name,
						'order' => $value->cat_order,
						'count' => $category_faq_count,
						'file_id' => $value->file_id,
						'sub_categories' => $sub_cat_array
				);
			}
		}

    $this->view->categories = $categories;

    //SET NO RENDER
    if (!(count($this->view->categories) > 0)) {
      return $this->setNoRender();
    }
  }

}