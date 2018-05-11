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
class Sitefaq_Widget_BrowseSitefaqsController extends Seaocore_Content_Widget_Abstract {

  public function indexAction() {

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $params = $request->getParams();
    //GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //CHECK FAQ VIEW PRIVACY
    $can_view = Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'view');
    $faqItemType = Engine_Api::_()->getApi('settings', 'core')->getSetting('faq.item.type', 1);
    $sitefaq_view = Zend_Registry::isRegistered('sitefaq_view') ? Zend_Registry::get('sitefaq_view') : null;

    $this->view->is_ajax = $isAjax = $this->_getParam('isajax', 0);
    $this->view->showContent = $this->_getParam('show_content', 2);

    //START VIEW MORE LINK AND AUTOSCROLL CONTENT WORK
    if (!empty($isAjax)) {
      $this->getElement()->removeDecorator('Title');
      $this->getElement()->removeDecorator('Container');
    }
    //END VIEW MORE LINK AND AUTOSCROLL CONTENT WORK
    //DON'T RENDER IF NOT VIEWALBE
    if (empty($can_view) || empty($sitefaq_view) || empty($faqItemType)) {
      return $this->setNoRender();
    }

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

    //SHOW TH REASON OPTION
    $this->view->options = Engine_Api::_()->getDbtable('options', 'sitefaq')->markSitefaqOption();

    //HELPFUL PRIVACY
    $this->view->helpful_allow = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'helpful');

    //SHARING IS ALLOWED OR NOT TO THIS MEMBER LEVEL
    $this->view->can_share = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'share');

    //CHECK FAQ CREATION PRIVACY
    $this->view->can_create = Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'create');

    //PRINT IS ALLOWED OR NOT
    $this->view->can_print = $params['print'] = $this->_getParam('print', 1);

    //GET FAQ TABLE
    $sitefaqTable = Engine_Api::_()->getDbtable('faqs', 'sitefaq');

    //START BREAD-CRUMB RELATED WORK FOR PERMALINK
    $this->view->url_category_id = $this->view->url_subcategory_id = $this->view->url_subsubcategory_id = 0;
    if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
      $this->view->url_category_id = $_GET['category_id'];
    } elseif (isset($_GET['category']) && !empty($_GET['category'])) {
      $this->view->url_category_id = $_GET['category'];
    }

    if (isset($_GET['subcategory_id']) && !empty($_GET['subcategory_id'])) {
      $this->view->url_subcategory_id = $_GET['subcategory_id'];
    } elseif (isset($_GET['subcategory']) && !empty($_GET['subcategory'])) {
      $this->view->url_subcategory_id = $_GET['subcategory'];
    }

    if (isset($_GET['subsubcategory_id']) && !empty($_GET['subsubcategory_id'])) {
      $this->view->url_subsubcategory_id = $_GET['subsubcategory_id'];
    } elseif (isset($_GET['subsubcategory']) && !empty($_GET['subsubcategory'])) {
      $this->view->url_subsubcategory_id = $_GET['subsubcategory'];
    }
    //END BREAD-CRUMB RELATED WORK FOR PERMALINK
    //FORM GENERATION
    $form = new Sitefaq_Form_Search();

    if ($form->isValid($this->_getAllParams())) {
      if (!empty($_GET)) {
        $form->populate($_GET);
      }
      $values = $form->getValues();
    } else {
      $values = array();
    }

    $values = array_merge($_GET, $values);

    if (isset($params['page']) && !empty($params['page']))
      $this->view->page = $values['page'] = $params['page'];
    else
      $this->view->page = $values['page'] = 1;

    //FAQ AUTHORIZATION VALUES
    $values['approved'] = "1";
    $values['draft'] = "0";
    $values['searchable'] = "1";

    $this->view->sitefaq_api = $sitefaq_api = Engine_Api::_()->sitefaq();

    if ($level_id != 1) {
      $values['networks'] = $sitefaq_api->getViewerNetworks();
      $values['profile_types'] = $sitefaq_api->getViewerProfiles();
      $values['member_levels'] = $sitefaq_api->getViewerLevels();
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

    if ($request->getParam('category_id')) {
      $this->view->first_category_id = $this->view->url_category_id = $values['category_id'] = $params['category_id'] = $request->getParam('category_id');

      //GET CATEGORY NAME
      if (!empty($this->view->first_category_id)) {
        $first_category = $categoryTable->getCategory($this->view->first_category_id);
        $this->view->first_category_name = $first_category->category_name;
      }
    } elseif ($request->getParam('category')) {
      $this->view->first_category_id = $this->view->url_category_id = $values['category_id'] = $params['category'] = $request->getParam('category');

      //GET CATEGORY NAME
      if (!empty($this->view->first_category_id)) {
        $first_category = $categoryTable->getCategory($this->view->first_category_id);
        $this->view->first_category_name = $first_category->category_name;
      }
    }

    if ($request->getParam('subcategory_id')) {
      $this->view->first_subcategory_id = $this->view->url_subcategory_id = $values['subcategory_id'] = $params['subcategory_id'] = $request->getParam('subcategory_id');

      //GET SUB-CATEGORY NAME
      if (!empty($this->view->first_subcategory_id)) {
        $first_subcategory = $categoryTable->getCategory($this->view->first_subcategory_id);
        $this->view->first_subcategory_name = $first_subcategory->category_name;
      }
    } elseif ($request->getParam('subcategory')) {
      $this->view->first_subcategory_id = $this->view->url_subcategory_id = $values['subcategory_id'] = $params['subcategory'] = $request->getParam('subcategory');

      //GET CATEGORY NAME
      if (!empty($this->view->first_subcategory_id)) {
        $first_subcategory = $categoryTable->getCategory($this->view->first_subcategory_id);
        $this->view->first_subcategory_name = $first_subcategory->category_name;
      }
    }
    $susubcategory_id= $params['subsubcategory_id'] = $request->getParam('subsubcategory_id');
    $subsubcategory = $params['subsubcategory'] = $request->getParam('subsubcategory');
    if ($susubcategory_id) {
      $this->view->first_subsubcategory_id = $this->view->url_subsubcategory_id = $values['subsubcategory_id'] = $params['subsubcategory_id'] = $request->getParam('subsubcategory_id');

      //GET 3RD LEVEL CATEGORY NAME
      if (!empty($this->view->first_subsubcategory_id)) {
        $first_subsubcategory = $categoryTable->getCategory($this->view->first_subsubcategory_id);
        $this->view->first_subsubcategory_name = $first_subsubcategory->category_name;
      }
    } elseif ($subsubcategory) {
      $this->view->first_subsubcategory_id = $this->view->url_subsubcategory_id = $values['subsubcategory_id'] = $params['subsubcategory'] = $request->getParam('subsubcategory');

      //GET CATEGORY NAME
      if (!empty($this->view->first_subsubcategory_id)) {
        $first_subsubcategory = $categoryTable->getCategory($this->view->first_subsubcategory_id);
        $this->view->first_subsubcategory_name = $first_subsubcategory->category_name;
      }
    }

    if (!isset($_GET['orderby'])) {
      $values['orderby'] = 'weight';
    } elseif (isset($_GET['orderby']) && !empty($_GET['orderby'])) {
      $values['orderby'] = $_GET['orderby'];
    }
    $this->view->assign($values);

    //GET WIDGET SETTINGS
    $values['total_sitefaqs'] = $this->view->total_sitefaqs = $total_sitefaqs = $params['itemCount'] = $this->_getParam('itemCount', 20);
    $values['truncation'] = $this->view->truncation = $params['truncation'] = $this->_getParam('truncation', 0);
    $this->view->linked = $params['linked'] = $this->_getParam('linked', 0);
    $values['statisticsRating'] = $this->view->statisticsRating = $params['statisticsRating'] = $this->_getParam('statisticsRating', 1);
    $values['statisticsHelpful'] = $this->view->statisticsHelpful = $params['statisticsHelpful'] = $this->_getParam('statisticsHelpful', 1);
    $values['statisticsComment'] = $this->view->statisticsComment = $params['statisticsComment'] = $this->_getParam('statisticsComment', 1);
    $values['statisticsView'] = $this->view->statisticsView = $params['statisticsView'] = $this->_getParam('statisticsView', 1);
    $this->view->scrollButton = $params['scrollButton'] = $this->_getParam('scrollButton', 1);

    //SEND FORM VALUES TO TPL
    $this->view->formValues = $values;

    if (((isset($values['tag']) && !empty($values['tag']) && isset($values['tag_id']) && !empty($values['tag_id'])))) {
      $current_url = $request->getRequestUri();
      $current_url = explode("?", $current_url);
      if (isset($current_url[1])) {
        $current_url1 = explode("&", $current_url[1]);
        foreach ($current_url1 as $key => $value) {
          if (strstr($value, "tag=") || strstr($value, "tag_id=")) {
            unset($current_url1[$key]);
          }
        }
        $this->view->current_url2 = implode("&", $current_url1);
      }
    }

    //SEND QUERY STRING TO TPL FILE FOR PRINT ACTION
    $this->view->query_string = http_build_query($values, '', '&');

    //GET CUSTOM VALUES
    $customFieldValues = array_intersect_key($values, $form->getFieldElements());

    //GET PAGINATOR
    $this->view->paginator = $paginator = $sitefaqTable->getSitefaqsPaginator($values, $customFieldValues);
    $this->view->totalCount = $paginator->getTotalItemCount();
    $this->view->paginator->setItemCountPerPage($params['itemCount']);
    $this->view->paginator->setCurrentPageNumber($values['page']);
    $this->view->params = $params;
  }

}
