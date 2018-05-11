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

class Sitefaq_Widget_ManageSitefaqsController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  { 
		//GET VIEWER DETAILS
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		$this->view->level_id  = $viewer->level_id;

		//DON'T DISPLAY IF VIEWER ID IS EMPTY
		if(empty($viewer_id)) {
			return $this->setNoRender();
		}

   //FORM GENERATION
		$form = new Sitefaq_Form_Search();

    if( $form->isValid($this->_getAllParams()) ) {
			if(!empty($_GET)) {
				$form->populate($_GET);
			}
			$values = $form->getValues();
    } else {
      $values = array();
    }

		$values = array_merge($_GET, $values);

		if(isset($_GET['page'])) {
			$this->view->page = $_GET['page'];
		}
    if(empty($_GET['page'])) {
    	$this->view->page = $values['page'] = 1;
    }

    //CATEGORY DETAILS INITILIZATION
		$this->view->first_category_id = 0;
		$this->view->first_subcategory_id = 0;
		$this->view->first_subsubcategory_id = 0;
		$this->view->first_category_name = '';
		$this->view->first_subcategory_name = '';
		$this->view->first_subsubcategory_name = '';

    //GET CATEGORY TABLE
		$this->view->categoryTable = $categoryTable = Engine_Api::_()->getDbtable('categories', 'sitefaq');
		$this->view->menuStr = $menuStr = Engine_Api::_()->getApi('settings', 'core')->getSetting('faq.menus.str', 0);
		$this->view->faqLinkView = $faqLinkView = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.getlink.view', 0);
		$this->view->tagLimit = $categoryLimit = Engine_Api::_()->getApi('settings', 'core')->getSetting('faq.tag.limit', 0);
		$CategoryOrder = $categoryTable->CategoryOrder($menuStr, $faqLinkView);

		$request = Zend_Controller_Front::getInstance()->getRequest();
		if($request->getParam('category_id')) {
			$this->view->first_category_id = $this->view->url_category_id = $values['category_id'] = $request->getParam('category_id');

			//GET CATEGORY NAME
			if(!empty($this->view->first_category_id)) {
				$first_category = $categoryTable->getCategory($this->view->first_category_id);
				$this->view->first_category_name = $first_category->category_name;
			}
		}
		elseif($request->getParam('category')) {
			$this->view->first_category_id = $this->view->url_category_id = $values['category_id'] = $request->getParam('category');

			//GET CATEGORY NAME
			if(!empty($this->view->first_category_id)) {
				$first_category = $categoryTable->getCategory($this->view->first_category_id);
				$this->view->first_category_name = $first_category->category_name;
			}
		}

		if( !empty($CategoryOrder) && !empty($categoryLimit) && ( $CategoryOrder != $categoryLimit ) ) {
		  Engine_Api::_()->getApi('settings', 'core')->setSetting('sitefaq.category', 0);
		  Engine_Api::_()->getApi('settings', 'core')->setSetting('faq.item.type', 0);
		}

		if($request->getParam('subcategory_id')) {
			$this->view->first_subcategory_id = $this->view->url_subcategory_id = $values['subcategory_id'] = $request->getParam('subcategory_id');

				//GET SUB-CATEGORY NAME
				if(!empty($this->view->first_subcategory_id)) {
					$first_subcategory = $categoryTable->getCategory($this->view->first_subcategory_id);
					$this->view->first_subcategory_name = $first_subcategory->category_name;
				}
		}
		elseif($request->getParam('subcategory')) {
			$this->view->first_subcategory_id = $this->view->url_subcategory_id = $values['subcategory_id'] = $request->getParam('subcategory');

			//GET CATEGORY NAME
			if(!empty($this->view->first_subcategory_id)) {
				$first_subcategory = $subcategoryTable->getCategory($this->view->first_subcategory_id);
				$this->view->first_subcategory_name = $first_subcategory->category_name;
			}
		}

		if($request->getParam('subsubcategory_id')) {
			$this->view->first_subsubcategory_id = $this->view->url_subsubcategory_id = $values['subsubcategory_id'] = $request->getParam('subsubcategory_id');

			//GET 3RD LEVEL CATEGORY NAME
			if(!empty($this->view->first_subsubcategory_id)) {
				$first_subsubcategory = $categoryTable->getCategory($this->view->first_subsubcategory_id);
				$this->view->first_subsubcategory_name = $first_subsubcategory->category_name;
			}
		}
		elseif($request->getParam('subsubcategory')) {
			$this->view->first_subsubcategory_id = $this->view->url_subsubcategory_id = $values['subsubcategory_id'] = $request->getParam('subsubcategory');

			//GET CATEGORY NAME
			if(!empty($this->view->first_subsubcategory_id)) {
				$first_subsubcategory = $subsubcategoryTable->getCategory($this->view->first_subsubcategory_id);
				$this->view->first_subsubcategory_name = $first_subsubcategory->category_name;
			}
		}

		if(!isset($_GET['orderby'])) {
			$values['orderby'] = $this->_getParam('orderby', 'modified_date');
		}
		elseif(isset($_GET['orderby']) && !empty($_GET['orderby'])) {
			$values['orderby'] = $_GET['orderby'];
		}

    $values['owner_id'] = $viewer_id;

    $this->view->assign($values);

		//CHECK FAQ CREATION PRIVACY
    $this->view->can_create = Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'create');

		//CHECK FAQ EDIT PRIVACY
    $this->view->can_edit = Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'edit');

		//CHECK FAQ DELETE PRIVACY
    $this->view->can_delete = Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'delete');

		//GET VARIOUS WIDGET SETTINGS
		$this->view->statisticsRating = $this->_getParam('statisticsRating', 1);
		$this->view->statisticsHelpful = $this->_getParam('statisticsHelpful', 1);
		$this->view->statisticsComment = $this->_getParam('statisticsComment', 1);
		$this->view->statisticsView = $this->_getParam('statisticsView', 1);
		$this->view->modified_date = $this->_getParam('update', 1);

		$this->view->formValues = $values;

    //GET PAGINATOR
    $this->view->paginator = Engine_Api::_()->getDbtable('faqs', 'sitefaq')->getSitefaqsPaginator($values, array());
		$total_sitefaqs = $this->_getParam('itemCount', 20);
		$this->view->paginator->setItemCountPerPage($total_sitefaqs);
		$this->view->paginator->setCurrentPageNumber($values['page']);
	}  		

}