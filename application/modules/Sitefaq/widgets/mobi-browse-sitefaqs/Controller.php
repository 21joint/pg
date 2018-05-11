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

class Sitefaq_Widget_MobiBrowseSitefaqsController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  { 
		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
		$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

		//CHECK FAQ VIEW PRIVACY
    $can_view = Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'view');
		$sitefaq_view = Zend_Registry::isRegistered('sitefaq_view') ? Zend_Registry::get('sitefaq_view') : null;

		//DON'T RENDER IF NOT VIEWALBE
		if(empty($can_view) || empty($sitefaq_view)) {
			return $this->setNoRender();
		}

		//GET DESCRIPTION TRUNCATION LIMIT
		$this->view->truncation = $this->_getParam('truncation', 0);

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		//HELPFUL PRIVACY
		$this->view->helpful_allow = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'helpful');

		//SHARING IS ALLOWED OR NOT TO THIS MEMBER LEVEL
		$this->view->can_share = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'share');

		//CHECK FAQ CREATION PRIVACY
    $this->view->can_create = Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'create');

		//GET VARIOUS WIDGET SETTINGS
		$this->view->statisticsRating = $this->_getParam('statisticsRating', 1);
		$this->view->statisticsHelpful = $this->_getParam('statisticsHelpful', 1);
		$this->view->statisticsComment = $this->_getParam('statisticsComment', 1);
		$this->view->statisticsView = $this->_getParam('statisticsView', 1);

		//GET FAQ TABLE
		$sitefaqTable = Engine_Api::_()->getDbtable('faqs', 'sitefaq');

		//START BREAD-CRUMB RELATED WORK FOR PERMALINK
		$this->view->url_category_id = $this->view->url_subcategory_id = $this->view->url_subsubcategory_id = 0;
		if(isset($_GET['category_id']) && !empty($_GET['category_id']))  {
			$this->view->url_category_id = $_GET['category_id'];
		}
		elseif(isset($_GET['category']) && !empty($_GET['category']))  {
			$this->view->url_category_id = $_GET['category'];
		}

		if(isset($_GET['subcategory_id']) && !empty($_GET['subcategory_id']))  {
			$this->view->url_subcategory_id = $_GET['subcategory_id'];
		}
		elseif(isset($_GET['subcategory']) && !empty($_GET['subcategory']))  {
			$this->view->url_subcategory_id = $_GET['subcategory'];
		}

		if(isset($_GET['subsubcategory_id']) && !empty($_GET['subsubcategory_id']))  {
			$this->view->url_subsubcategory_id = $_GET['subsubcategory_id'];
		}
		elseif(isset($_GET['subsubcategory']) && !empty($_GET['subsubcategory']))  {
			$this->view->url_subsubcategory_id = $_GET['subsubcategory'];
		}
		//END BREAD-CRUMB RELATED WORK FOR PERMALINK

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

    $this->view->formValues = $values ;

		if(isset($_GET['page'])) {
			$this->view->page = $_GET['page'];
		}
    if(empty($_GET['page'])) {
    	$this->view->page = $values['page'] = 1;
    }

		//FAQ AUTHORIZATION VALUES
    $values['approved'] = "1";
		$values['draft'] = "0";
		$values['searchable'] = "1";

		$this->view->sitefaq_api = $sitefaq_api = Engine_Api::_()->sitefaq();

		if($level_id != 1) {
			$values['networks'] = $sitefaq_api->getViewerNetworks();
			$values['profile_types'] = $sitefaq_api->getViewerProfiles();
			$values['member_levels'] = $sitefaq_api->getViewerLevels();
		}

		if(!isset($_GET['orderby'])) {
			$values['orderby'] = $this->_getParam('orderby', 'weight');
		}
		elseif(isset($_GET['orderby']) && !empty($_GET['orderby'])) {
			$values['orderby'] = $_GET['orderby'];
		}
    $this->view->assign($values);

		$this->view->query_string = http_build_query($values, '', '&');

		//GET CUSTOM VALUES
		$customFieldValues = array_intersect_key($values, $form->getFieldElements());

    //GET PAGINATOR
    $this->view->paginator = $sitefaqTable->getSitefaqsPaginator($values, $customFieldValues);
		$this->view->total_sitefaqs = $total_sitefaqs = $this->_getParam('itemCount', 20);
		$this->view->paginator->setItemCountPerPage($total_sitefaqs);
		$this->view->paginator->setCurrentPageNumber($values['page']);
	}  
		
}