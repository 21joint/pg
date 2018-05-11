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

class Sitefaq_Widget_SitefaqViewSitefaqsController extends Seaocore_Content_Widget_Abstract
{
  public function indexAction()
  {
		$sitefaq_help = Zend_Registry::isRegistered('sitefaq_help') ? Zend_Registry::get('sitefaq_help') : null;
		//DON'T RENDER IF SUBJECT IS NOT SET
		if(empty($sitefaq_help) || !Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
			return $this->setNoRender();
		}

		//GET FAQ SUBJECT
		$this->view->sitefaq = $sitefaq = Engine_Api::_()->core()->getSubject();
		if(empty($sitefaq_help) || empty($sitefaq)) {
			return $this->setNoRender();
		}
                
    //SEND ALL PARAMS FROM WIDGET SETTINGS TO TPL FILE, ADDED FOR SITEMOBILE INFORMATION WIDGET CONTENT.
    $params = $this->_getAllParams();
    $this->view->params = $params; 
    
		//GET VIEWER DETAIL
		$viewer = Engine_Api::_()->user()->getViewer();
		$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //SHOW TH REASON OPTION
    $this->view->options = Engine_Api::_()->getDbtable('options', 'sitefaq')->markSitefaqOption();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		//SEND FAQ ID TO TPL
		$this->view->faq_id = $sitefaq->faq_id;

    //BREADCRUM SETTING
		$this->view->showBreadCrumb = $this->_getParam('breadCrumb', 1); 		

		$request = Zend_Controller_Front::getInstance()->getRequest();

    //GET CATEGORY TABLE
		$this->view->categoryTable = $categoryTable = Engine_Api::_()->getDbtable('categories', 'sitefaq');

		//CATEGORY DETAILS INITILIZATION
		$this->view->first_category_id = 0;
		$this->view->first_subcategory_id = 0;
		$this->view->first_subsubcategory_id = 0;
		$this->view->first_category_name = '';
		$this->view->first_subcategory_name = '';
		$this->view->first_subsubcategory_name = '';

		//IF CATEGORY ID NOT EMPTY IN URL
		if($request->getParam('category_id')) {

			//GET FIRST CATEGORY ID
			$this->view->first_category_id = $request->getParam('category_id');

			//GET CATEGORY NAME
			$first_category = $categoryTable->getCategory($this->view->first_category_id);
			$this->view->first_category_name = $first_category->category_name;

			//GET SUB-CATEGORY ID FROM URL
			if($request->getParam('subcategory_id')) {
				$this->view->first_subcategory_id = $request->getParam('subcategory_id');
			}

			//GET SUB-CATEGORY NAME
			if(!empty($this->view->first_subcategory_id)) {
				$first_subcategory = $categoryTable->getCategory($this->view->first_subcategory_id);
				$this->view->first_subcategory_name = $first_subcategory->category_name;


				//GET 3RD LEVEL CATEGORY ID FROM URL
				if($request->getParam('subsubcategory_id')) {
					$this->view->first_subsubcategory_id = $request->getParam('subsubcategory_id');
				}

				//GET 3RD LEVEL CATEGORY NAME
				if(!empty($this->view->first_subsubcategory_id)) {
					$first_subsubcategory = $categoryTable->getCategory($this->view->first_subsubcategory_id);
					$this->view->first_subsubcategory_name = $first_subsubcategory->category_name;
				}
			}
		}
		else {
			//GET FIRST CATEGORY ID
			if(!empty($sitefaq->category_id)) {
				$first_category_id_array = Zend_Json_Decoder::decode($sitefaq->category_id);
				$this->view->first_category_id = $first_category_id_array[0];

				//RETURN IF CATEGORY ID IS NOT EXIST
				if($this->view->first_category_id != 0) {

					//GET CATEGORY NAME
					$first_category = $categoryTable->getCategory($this->view->first_category_id);
					$this->view->first_category_name = $first_category->category_name;

					//GET FIRST SUB-CATEGORY ID
					if(!empty($sitefaq->subcategory_id)) {
						$first_subcategory_id_array = Zend_Json_Decoder::decode($sitefaq->subcategory_id);
						$this->view->first_subcategory_id = $first_subcategory_id_array[0];

						//GET SUB-CATEGORY NAME
						if(!empty($this->view->first_subcategory_id)) {
							$first_subcategory = $categoryTable->getCategory($this->view->first_subcategory_id);
							$this->view->first_subcategory_name = $first_subcategory->category_name;

							if(!empty($sitefaq->subsubcategory_id)) {
								//GET FIRST 3RD LEVEL CATEGORY ID
								$first_subsubcategory_id_array = Zend_Json_Decoder::decode($sitefaq->subsubcategory_id);
								$this->view->first_subsubcategory_id = $first_subsubcategory_id_array[0];

								//GET 3RD LEVEL CATEGORY NAME
								if(!empty($this->view->first_subsubcategory_id)) {
									$first_subsubcategory = $categoryTable->getCategory($this->view->first_subsubcategory_id);
									$this->view->first_subsubcategory_name = $first_subsubcategory->category_name;
								}
							}
						}
					}
				}
			}
		}

		//GET SLUG
		$this->view->faq_slug = $sitefaq->getSlug();

		//HELPFUL PRIVACY
		$this->view->helpful_allow = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'helpful');

		//GET HELPFUL TABLE
		$tableHelp = Engine_Api::_()->getDbTable('helps', 'sitefaq');

		//CHECK FOR PREVIOUS MARK
		if(!empty($this->view->helpful_allow)) {
			$this->view->previousHelpMark = $tableHelp->getHelpful($sitefaq->faq_id, $viewer_id);
		}

		//TOTAL HELPFUL COUNT
		$this->view->totalHelpCount = $tableHelp->countHelpful($sitefaq->faq_id, 1);

    //TOTAL HELPFUL COUNT
		$totalVoteCount = $tableHelp->countHelpful($sitefaq->faq_id, 0);
    $this->view->totalVoteCount = $totalVoteCount['total_marks'];

		//GET WIDGET SETTING
		$this->view->statisticsHelpful = $this->_getParam('statisticsHelpful', 1);

		//GET ACTION DETAILS
		$front = Zend_Controller_Front::getInstance();
    $this->view->module = $front->getRequest()->getModuleName();
    $this->view->action = $front->getRequest()->getActionName();

		//CUSTOM FIELD WORK
    $this->view->addHelperPath(APPLICATION_PATH . '/application/modules/Fields/View/Helper', 'Fields_View_Helper');
    $this->view->fieldStructure = Engine_Api::_()->fields()->getFieldsStructurePartial($sitefaq);
  }

}
