<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 6590 2010-12-31 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Widget_PopularSitepagereviewsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    //SET NO RENDER IF NO SUBJECT
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

		$page_url = Zend_Controller_Front::getInstance()->getRequest()->getParam('page_url', null);
		$page_id = Engine_Api::_()->sitepage()->getPageId($page_url);

    //GET OBJECT
    $sitepage_subject = Engine_Api::_()->getItem('sitepage_page',$page_id);

    // PACKAGE BASE PRIYACY END
    //START MANAGE-ADMIN CHECK
    $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage_subject, 'view');
    if (empty($isManageAdmin)) {
      return $this->setNoRender();
    }
    //END MANAGE-ADMIN CHECK

    $sitepagereview_isReview = Zend_Registry::isRegistered('sitepagereview_isReview') ? Zend_Registry::get('sitepagereview_isReview') : null;
    if (empty($sitepagereview_isReview)) {
      return $this->setNoRender();
    }

		$params = array();
		$params['page_id'] = $page_id;
		$params['orderby'] = 'view_count DESC';
		$params['zero_count'] = 'view_count';
		$params['limit'] = $this->_getParam('itemCount', 3);

    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('reviews', 'sitepagereview')->reviewRatingData($params);

    if (Count($paginator) <= 0) {
      return $this->setNoRender();
    }
  }

}
?>