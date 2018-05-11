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

class Sitefaq_Widget_SearchSitefaqsController extends Engine_Content_Widget_Abstract
{ 
  public function indexAction()
  {
		//GET VIEWER ID
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		//WHO CAN VIEW THE FAQs
		$can_view = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'view');
		if(empty($can_view)) {
			return $this->setNoRender();
		}

  	//GENERATE SEARCH FORM
		$this->view->form = $form = new Sitefaq_Form_Search(array('type' => 'sitefaq_faq'));

    if (empty($viewer_id)) {
      $form->removeElement('show');
    }

		$request = Zend_Controller_Front::getInstance()->getRequest();

    $category = $request->getParam('category_id', null);
    $subcategory = $request->getParam('subcategory_id', null);
    $subsubcategory = $request->getParam('subsubcategory_id', null);

    $categoryname = $request->getParam('categoryname', null);
    $subcategoryname = $request->getParam('subcategoryname', null);
    $subsubcategoryname = $request->getParam('subsubcategoryname', null);

    $cattemp = $request->getParam('category', null);

		//GET CATEGORY TABLE
		$tableCategory = Engine_Api::_()->getDbTable('categories', 'sitefaq');

    if(!empty($cattemp)) 
    {
    	$this->view->category_id = $_GET['category'] = $request->getParam('category');
    	$row = $tableCategory->getCategory($this->view->category_id);
	    if (!empty($row->category_name)) {
	      $categoryname = $this->view->category_name = $_GET['categoryname'] = $row->category_name;
	    }
	    
	    $categorynametemp = $request->getParam('categoryname', null);
	    $subcategorynametemp = $request->getParam('subcategoryname', null);
	    if (!empty($categorynametemp)) {
		    $categoryname = $this->view->category_name = $_GET['categoryname'] = $categorynametemp;
	    }
			if (!empty($subcategorynametemp)) {
		    $subcategoryname = $this->view->subcategory_name = $_GET['subcategoryname'] = $subcategorynametemp;
	    }
	  } else {
      if($categoryname)
				$this->view->category_name = $_GET['categoryname'] = $categoryname;      
	    if($category) {
	      $this->view->category_id = $_GET['category_id'] = $category;
        $row = $tableCategory->getCategory($this->view->category_id);
        if (!empty($row->category_name)) {
          $this->view->category_name = $_GET['categoryname'] = $categoryname = $row->category_name;
        }
      }	    
    }
    
    $subcattemp = $request->getParam('subcategory', null);

    if(!empty($subcattemp)) 
    {
    	$this->view->subcategory_id = $_GET['subcategory_id'] = $request->getParam('subcategory');
	    $row = $tableCategory->getCategory($this->view->subcategory_id);
	    if (!empty($row->category_name)) {
	      $this->view->subcategory_name = $row->category_name;
	    }
    } else {
        if($subcategoryname)
				  $this->view->subcategory_name = $_GET['subcategoryname'] = $subcategoryname;        
        if($subcategory) {
          $this->view->subcategory_id = $_GET['subcategory_id'] = $subcategory;
          $row = $tableCategory->getCategory($this->view->subcategory_id);
          if (!empty($row->category_name)) {
            $this->view->subcategory_name = $_GET['subcategoryname'] = $subcategoryname = $row->category_name;
          }
       }		   
    }

    $subsubcattemp = $request->getParam('subsubcategory', null);

    if(!empty($subsubcattemp))
    {
    	$this->view->subsubcategory_id = $_GET['subsubcategory_id'] = $request->getParam('subsubcategory');
	    $row = $tableCategory->getCategory($this->view->subsubcategory_id);
	    if (!empty($row->category_name)) {
	      $this->view->subsubcategory_name = $row->category_name;
	    }
    } else {
        if($subsubcategoryname)
				  $this->view->subsubcategory_name = $_GET['subsubcategoryname'] = $subsubcategoryname;

        if($subsubcategory) {
          $this->view->subsubcategory_id = $_GET['subsubcategory_id'] = $subsubcategory;
          $row = $tableCategory->getCategory($this->view->subsubcategory_id);
          if (!empty($row->category_name)) {
            $this->view->subsubcategory_name = $_GET['subsubcategoryname'] = $subsubcategoryname = $row->category_name;
          }
       }
    }

    if(empty($categoryname)) {
      $_GET['category'] = $this->view->category_id = 0;
			$_GET['subcategory'] = $this->view->subcategory_id = 0;
      $_GET['subsubcategory'] = $this->view->subsubcategory_id = 0;
			$_GET['categoryname'] = $categoryname;
			$_GET['subcategoryname'] = $subcategoryname;
      $_GET['subsubcategoryname'] = $subsubcategoryname;
    }

		$prefield_data = array_merge($_GET, $_POST);

		//POPULATE SEARCH FORM
		$form->populate($prefield_data);
  }

}