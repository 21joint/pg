<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Widget_SitepageOfferController extends Seaocore_Content_Widget_Abstract {

  public function indexAction() {
    
    //ADDED REMOVE DECORATOR FOR APPS PAGE AUTOLOAD.
    if (Engine_Api::_()->seaocore()->isSitemobileApp() && $this->_getParam('ajax', false)) {
      if ($this->_getParam('page', 1) > 1)
        $this->getElement()->removeDecorator('Title');
      $this->getElement()->removeDecorator('Container');
    }

    $values = array();
    $category = Zend_Controller_Front::getInstance()->getRequest()->getParam('category_id', null);
    $category_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('category', null);
    $subcategory = Zend_Controller_Front::getInstance()->getRequest()->getParam('subcategory_id', null);
    $subcategory_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('subcategory', null);
    $categoryname = Zend_Controller_Front::getInstance()->getRequest()->getParam('categoryname', null);
    $subcategoryname = Zend_Controller_Front::getInstance()->getRequest()->getParam('subcategoryname', null);
    $subsubcategory = Zend_Controller_Front::getInstance()->getRequest()->getParam('subsubcategory_id', null);
    $subsubcategory_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('subsubcategory', null);
    $subsubcategoryname = Zend_Controller_Front::getInstance()->getRequest()->getParam('subsubcategoryname', null);

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();

    if ( $category )
      $_GET['category'] = $category;
    if ( $subcategory )
      $_GET['subcategory'] = $subcategory;
    if ( $categoryname )
      $_GET['categoryname'] = $categoryname;
    if ( $subcategoryname )
      $_GET['subcategoryname'] = $subcategoryname;

    if ( $subsubcategory )
      $_GET['subsubcategory'] = $subsubcategory;
    if ( $subcategoryname )
      $_GET['subsubcategoryname'] = $subsubcategoryname;

    if ( $category_id )
      $_GET['category'] = $values['category'] = $category_id;
    if ( $subcategory_id )
      $_GET['subcategory'] = $values['subcategory'] = $subcategory_id;
    if ( $subsubcategory_id )
      $_GET['subsubcategory'] = $values['subsubcategory'] = $subsubcategory_id;

    //GET VALUE BY POST TO GET DESIRED SITEPAGES
    if ( !empty($_GET) ) {
      $values = $_GET;
    }

    if ( ($category) != null ) {
      $this->view->category = $values['category'] = $category;
      $this->view->subcategory = $values['subcategory'] = $subcategory;
      $this->view->subsubcategory = $values['subsubcategory'] = $subsubcategory;
    }
    else {
      $values['category'] = 0;
      $values['subcategory'] = 0;
      $values['subsubcategory'] = 0;
    }

    if ( ($category_id) != null ) {
      $this->view->category_id = $values['category'] = $category_id;
      $this->view->subcategory_id = $values['subcategory'] = $subcategory_id;
      $this->view->subsubcategory_id = $values['subsubcategory'] = $subsubcategory_id;
    }
    else {
      $values['category'] = 0;
      $values['subcategory'] = 0;
      $values['subsubcategory'] = 0;
    }

    $form = new Sitepageoffer_Form_Search(array('type' => 'sitepageoffer_offer'));
    $hotOffer = Zend_Controller_Front::getInstance()->getRequest()->getParam('hotoffer', null);
    if(empty($hotOffer) && $hotOffer !=null) {
     $values['orderby'] = 'creation_date';
    }
    elseif(isset($_GET['orderby'])) {
			$values['orderby'] = $_GET['orderby'];
    }
    $this->view->assign($values);

    if ( empty($categoryname) ) {
      $_GET['category'] = $this->view->category_id = 0;
      $_GET['subcategory'] = $this->view->subcategory_id = 0;
      $_GET['subsubcategory'] = $this->view->subsubcategory_id = 0;
      $_GET['categoryname'] = $categoryname;
      $_GET['subcategoryname'] = $subcategoryname;
      $_GET['subsubcategoryname'] = $subsubcategoryname;
    }

    $totalOffers = $this->_getParam('itemCount', 20);
    
    $customFieldValues = array_intersect_key($values, $form->getFieldElements());
    
    //DON'T SEND CUSTOM FIELDS ARRAY IF EMPTY
		$has_value = 0;
		foreach($customFieldValues as $customFieldValue) {
			if(!empty($customFieldValue)) {
				$has_value = 1;
				break;
			}
		}

		if(empty($has_value)) {
			$customFieldValues = null;
		}
    //GET OFFERS DATA
    $sponsoredOffer = Zend_Controller_Front::getInstance()->getRequest()->getParam('sponsoredoffer', null);
    $hotOffer = Zend_Controller_Front::getInstance()->getRequest()->getParam('hotoffer', null);
    $Offer = Zend_Controller_Front::getInstance()->getRequest()->getParam('orderby', null);
    $values['offer'] = $Offer;

    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('offers', 'sitepageoffer')->getOffers($hotOffer, $values,$sponsoredOffer,$customFieldValues);

    $paginator->setItemCountPerPage($totalOffers);
    //$this->view->paginator = $paginator->setCurrentPageNumber($values['page']);
    $this->view->paginator = $paginator->setCurrentPageNumber(Zend_Controller_Front::getInstance()->getRequest()->getParam('page'));
    
     //SCROLLING PARAMETERS SEND
    if(Engine_Api::_()->seaocore()->isSitemobileApp()) {  
      //SET SCROLLING PARAMETTER FOR AUTO LOADING.
      if (!Zend_Registry::isRegistered('scrollAutoloading')) {      
        Zend_Registry::set('scrollAutoloading', array('scrollingType' => 'up'));
      }
    }
    $this->view->page = $this->_getParam('page', 1);
    $this->view->autoContentLoad = $isappajax = $this->_getParam('isappajax', false);
    $this->view->totalCount = $paginator->getTotalItemCount();
    $this->view->totalPages = ceil(($this->view->totalCount) /$totalOffers);
    //END - SCROLLING WORK
    $view = $this->view;
        $view->addHelperPath(APPLICATION_PATH . '/application/modules/Fields/View/Helper', 'Fields_View_Helper');
        
        
                $this->view->message = 'There are no search results to display.';
        if ((isset($values['search']) && !empty($values['search'])) || (isset($values['category_id']) && !empty($values['category_id'])) || (isset($values['subcategory_id']) && !empty($values['subcategory_id'])) || (isset($values['tag_id']) && !empty($values['tag_id'])) || (isset($values['sitepage_location']) && !empty($values['sitepage_location'])) || (isset($values['search_offer']) && !empty($values['search_offer'])))
            $this->view->message = 'There is no such offer matching the entered criteria.';
  }

}

?>