<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Plugin_Core extends Zend_Controller_Plugin_Abstract 
{
	//MOBILE PAGES WORK
  public function routeShutdown(Zend_Controller_Request_Abstract $request) {

		//IF MOBILE MODULE IS NOT ENABLED THEN RETURN
    if (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('mobi'))
      return;

    //CHECK IF ADMIN
    if (substr($request->getPathInfo(), 1, 5) == "admin") {
      return;
    }

    $mobile = $request->getParam("mobile");
    $session = new Zend_Session_Namespace('mobile');

    if ($mobile == "1") {
      $mobile = true;
      $session->mobile = true;
    } elseif ($mobile == "0") {
      $mobile = false;
      $session->mobile = false;
    } else {
      if (isset($session->mobile)) {
        $mobile = $session->mobile;
      } else {
        //CHECK TO SEE IF MOBILE
        if (Engine_Api::_()->mobi()->isMobile()) {
          $mobile = true;
          $session->mobile = true;
        } else {
          $mobile = false;
          $session->mobile = false;
        }
      }
    }

    if (!$mobile) {
      return;
    }
    $module = $request->getModuleName();
    $controller = $request->getControllerName();
    $action = $request->getActionName();

		//MOBILE ACTIONS
		if ($module == "sitefaq" && $controller == "index" &&   in_array($action , array('browse', 'view', 'home', 'manage')) ) {
			$actionName = "mobi-$action";
			$request->setActionName($actionName);
		}

    //CREATE LAYOUT
    $layout = Zend_Layout::startMvc();

    //SET OPTIONS
    $layout->setViewBasePath(APPLICATION_PATH . "/application/modules/Mobi/layouts", 'Core_Layout_View')
            ->setViewSuffix('tpl')
            ->setLayout(null);
  }

  //DELETE USERS BELONGINGS BEFORE THAT USER DELETION
  public function onUserDeleteBefore($event)
  {
    $payload = $event->getPayload();
    
    if( $payload instanceof User_Model_User ) {

			//FETCH OWNER FAQs
			$owner_id = $payload->getIdentity();
			$sitefaqs = Engine_Api::_()->getDbtable('faqs', 'sitefaq')->getOwnersFaqs($owner_id);

      foreach($sitefaqs as $sitefaq ) {

				//DELETE FAQ OBJECT
				$sitefaq->delete();
      }

			//MAKE QUERY
			$questionTable = Engine_Api::_()->getDbtable('questions', 'sitefaq');
			$select = $questionTable->select()
										->from($questionTable->info('name'), 'question_id')
										->where('user_id = ?', $owner_id);

			//RETURN RESULTS
			$questions =  $questionTable->fetchAll($select);

      foreach($questions as $question ) {

				//GET THE QUESTION OBJECT
				$question = Engine_Api::_()->getItem('sitefaq_question', $question->question_id);

				//DELETE QUESTION AND OTHER BELONGINGS
				$question->delete();

      }
    }
  }

	//USING THIS HOOK FOR ADDING META TAGS
  public function onRenderLayoutDefault($event) {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $front = Zend_Controller_Front::getInstance();
    $module = $front->getRequest()->getModuleName();
    $controller = $front->getRequest()->getControllerName();
    $action = $front->getRequest()->getActionName();
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$siteinfo = $view->layout()->siteinfo;
		
		if($module == "sitefaq" && $controller == "index" && ($action == 'view' || $action == "mobi-view")) {
			$faq_id = $request->getParam('faq_id', null);
			$sitefaq = Engine_Api::_()->getItem('sitefaq_faq', $faq_id);

			//ADD CATEGORIES
			$categories = Zend_Json_Decoder::decode($sitefaq->category_id);
			$subcategories = Zend_Json_Decoder::decode($sitefaq->subcategory_id);
			$subsubcategories = Zend_Json_Decoder::decode($sitefaq->subsubcategory_id);

			$category_ids = array_merge($categories, $subcategories, $subsubcategories);
			$category_ids = array_unique($category_ids);
			$tableCategory = Engine_Api::_()->getDbTable('categories', 'sitefaq');
			foreach($category_ids as $value) {
				if(!empty($value)) {
					$category_name = $tableCategory->getCategory($value)->category_name;
					$siteinfo['keywords'] .= ',' . $category_name;
				}
			}
		}
		elseif($module == "sitefaq" && $controller == "index" && ($action == "browse" || $action == "mobi-browse")) {

			//ADD TAGS
			if(isset($_GET['tag']) && !empty($_GET['tag'])) {
				$siteinfo['keywords'] .= ',' . $_GET['tag'];
			}

			//ADD CATEGORIES
			if(isset($_GET['categoryname']) && !empty($_GET['categoryname'])) {
				$siteinfo['keywords'] .= ',' . $_GET['categoryname'];
			}
			elseif($request->getParam('categoryname', null)) {
				$siteinfo['keywords'] .= ',' . $request->getParam('categoryname', null);
			}

			if(isset($_GET['subcategoryname']) && !empty($_GET['subcategoryname'])) {
				$siteinfo['keywords'] .= ',' . $_GET['subcategoryname'];
			}
			elseif($request->getParam('subcategoryname', null)) {
				$siteinfo['keywords'] .= ',' . $request->getParam('subcategoryname', null);
			}

			if(isset($_GET['subsubcategoryname']) && !empty($_GET['subsubcategoryname'])) {
				$siteinfo['keywords'] .= ',' . $_GET['subsubcategoryname'];
			}
			elseif($request->getParam('subsubcategoryname', null)) {
				$siteinfo['keywords'] .= ',' . $request->getParam('subsubcategoryname', null);
			}
		}

		$view->layout()->siteinfo = $siteinfo;
	}

}