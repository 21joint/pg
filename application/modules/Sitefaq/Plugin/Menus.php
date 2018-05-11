<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Plugin_Menus {

    
    //SHARE PROFILE OPTION FOR SITEMOBILE
    public function canShareSitefaqs() {
        //RETURN FALSE IF SUBJECT IS NOT SET
        if (!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
            return false;
        }

        //GET VIEWER
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        //GET USER LEVEL ID
        if (!empty($viewer_id)) {
            $level_id = $viewer->level_id;
        } else {
            $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
        }

        //SHARING IS ALLOWED OR NOT TO THIS MEMBER LEVEL
        $can_share = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'share');

        //GET SUBJECT
        $sitefaq = Engine_Api::_()->core()->getSubject();

        //AUTHORIZATION CHECK
        if (empty($can_share) || empty($viewer_id) || !empty($sitefaq->draft) || empty($sitefaq->approved) || empty($sitefaq->search)) {
            return false;
        }

        return array(
            'label' => 'Share',
            'route' => 'default',
            'class' => 'ui-btn-action smoothbox',
            'params' => array(
                'module' => 'activity',
                'controller' => 'index',
                'action' => 'share',
                'type' => $sitefaq->getType(),
                'id' => $sitefaq->getIdentity(),
            ),
        );
    }

	//FAQ VIEW PRIVACY CHECK
  public function canViewSitefaqs() {

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();

		//CHECK FAQ VIEW PRIVACY
    if (!Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'view')) {
      return false;
    }
    return true;
  }

	//FAQ CREATION PRIVACY CHECK
  public function canCreateSitefaqs() {

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();

		//CHECK FAQ VIEW PRIVACY
    if (!Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'view')) {
      return false;
    }

		//CHECK FAQ CREATION PRIVACY
    if (!Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'create')) {
      return false;
    }
    return true;
  }

	//FAQ CREATION PRIVACY CHECK
  public function canAskQuestions() {

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();

		//CHECK FAQ VIEW PRIVACY
    if (!Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'view')) {
      return false;
    }

		//CHECK FAQ CREATION PRIVACY
    if (!Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'question')) {
      return false;
    }
    return true;
  }

	//ADD FAQ EDIT LINK
  public function onMenuInitialize_SitefaqGutterAdd($row) {

		//RETURN FALSE IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
      return false;
    }

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

		//CHECK FAQ CREATION PRIVACY
    if (!Engine_Api::_()->authorization()->isAllowed('sitefaq_faq', $viewer, 'create')) {
      return false;
    }

    return array(
        'class' => 'buttonlink icon_sitefaq_add',
        'route' => 'sitefaq_general',
				'action' => 'create',
    );
  }

	//ADD FAQ EDIT LINK
  public function onMenuInitialize_SitefaqGutterEdit($row) {

		//RETURN FALSE IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
      return false;
    }

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

		//GET SUBJECT
    $sitefaq = Engine_Api::_()->core()->getSubject();

		//FAQ EDIT PRIVACY
    $can_edit = $sitefaq->authorization()->isAllowed(null, 'edit');

		//AUTHORIZATION CHECK
		if(empty($can_edit) || empty($viewer_id)) {
			return false;
		}

    return array(
        'class' => 'buttonlink icon_sitefaq_edit',
        'route' => 'sitefaq_specific',
				'action' => 'edit',
        'params' => array(
            'faq_id' => $sitefaq->getIdentity(),
        ),
    );
  }

	//ADD FAQ PRINT LINK
  public function onMenuInitialize_SitefaqGutterPrint() {

		//RETURN FALSE IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
      return false;
    }

		$request = Zend_Controller_Front::getInstance()->getRequest();

		$category_id = $request->getParam('category_id', null);
		$subcategory_id = $request->getParam('subcategory_id', null);
		$subsubcategory_id = $request->getParam('subsubcategory_id', null);

		//GET SUBJECT
    $sitefaq = Engine_Api::_()->core()->getSubject();

    return array(
        'class' => 'buttonlink icon_sitefaqs_print',
        'target' => '_blank',
        'route' => 'sitefaq_specific',
        'params' => array(
            'action' => 'print-view',
            'faq_id' => $sitefaq->getIdentity(),
						'category_id' => $category_id,
						'subcategory_id' => $subcategory_id,
						'subsubcategory_id' => $subsubcategory_id
        ),
				'target' => '_blank'
    );
  }

	//ADD DOCUMENT PUBLISH LINK
  public function onMenuInitialize_SitefaqGutterPublish() {

		//RETURN FALSE IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
      return false;
    }

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

		//GET SUBJECT
    $sitefaq = Engine_Api::_()->core()->getSubject();
    if (empty($sitefaq->draft) || empty($viewer_id)) {
      return false;
    }

    //USER IS ALLOWED FOR PUBLISH OR NOT
    $can_edit = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitefaq_faq', 'edit');
		if(empty($can_edit) || ($can_edit == 1 && $viewer_id != $sitefaq->owner_id)) {
			return false;
		}

    return array(
        'class' => 'buttonlink smoothbox icon_sitefaq_publish',
        'route' => 'sitefaq_specific',
        'params' => array(
						'action' => 'publish',
            'faq_id' => $sitefaq->getIdentity(),
        ),
    );
  }

	//ADD FAQ SHARE LINK
  public function onMenuInitialize_SitefaqGutterShare() {

		//RETURN FALSE IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
      return false;
    }

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		//SHARING IS ALLOWED OR NOT TO THIS MEMBER LEVEL
		$can_share = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'share');

		//GET SUBJECT
    $sitefaq = Engine_Api::_()->core()->getSubject();

		//AUTHORIZATION CHECK
    if (empty($can_share) || empty($viewer_id) || !empty($sitefaq->draft) || empty($sitefaq->approved) || empty($sitefaq->search)) {
      return false;
    }

    return array(
        'class' => 'smoothbox buttonlink icon_sitefaq_share',
        'route' => 'default',
        'params' => array(
            'module' => 'seaocore',
            'controller' => 'activity',
            'action' => 'share',
            'type' => $sitefaq->getType(),
            'id' => $sitefaq->getIdentity(),
            'format' => 'smoothbox',
						'not_parent_refresh' => 1,
        ),
    );
  }

	//ADD FAQ DELETE LINK
  public function onMenuInitialize_SitefaqGutterDelete() {

		//RETURN FALSE IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
      return false;
    }

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

		//GET SUBJECT
    $sitefaq = Engine_Api::_()->core()->getSubject();

		//FAQ DELETE PRIVACY
    $can_delete = $sitefaq->authorization()->isAllowed(null, 'delete');

		//AUTHORIZATION CHECK
		if(empty($can_delete) || empty($viewer_id)) {
			return false;
		}

    return array(
        'class' => 'buttonlink smoothbox icon_sitefaq_delete',
        'route' => 'sitefaq_specific',
				'action' => 'delete',
        'params' => array(
            'faq_id' => $sitefaq->getIdentity(),
        ),
    );
  }

}