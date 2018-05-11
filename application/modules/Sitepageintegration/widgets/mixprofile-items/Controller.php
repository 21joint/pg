<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitepageintegration_Widget_MixprofileItemsController extends Seaocore_Content_Widget_Abstract {

  protected $_childCount;

  public function indexAction() {

    //DON'T RENDER IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

    $subject = Engine_Api::_()->core()->getSubject();
    $this->view->resource_type = $resource_type = $subject->getType();
    $primary_id = $subject->getIdentity();

    //GET THE RESOURCE TYPE.
    $this->view->title_truncation = $this->_getParam('title_truncation', 50);
    $this->view->showContent = $this->_getParam('showContent', array("postedDate", "postedBy", "viewCount", "likeCount", "commentCount","reviewCreate"));

    $sitepageintegrationMixSettings = Zend_Registry::isRegistered('sitepageintegrationMixSettings') ? Zend_Registry::get('sitepageintegrationMixSettings') : null;
    if (empty($sitepageintegrationMixSettings)) {
      return $this->setNoRender();
    }

    $contentsTable = Engine_Api::_()->getDbtable('contents', 'sitepageintegration');
    $contentsTableName = $contentsTable->info('name');

    $pagesTable = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $pagesTableName = $pagesTable->info('name');
    $select = $contentsTable->select()
            ->setIntegrityCheck(false)
            ->from($contentsTableName)
	          ->join($pagesTableName, $pagesTableName . '.page_id = ' . $contentsTableName . '.page_id')
	          ->where($contentsTableName . '.resource_id = ?', $primary_id)
	          ->where($pagesTableName . '.closed = ?', '0')
            ->where($pagesTableName . '.declined = ?', '0')
            ->where($pagesTableName . '.approved = ?', '1')
            ->where($pagesTableName . '.draft = ?', '1')
	          ->where($contentsTableName . '.resource_type = ?', $resource_type);
	  $this->view->paginator = $paginator = Zend_Paginator::factory($select);

    // Set item count per page and current page number
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));

		if($this->_getParam('is_ajax', 0)) {
			$this->getElement()->removeDecorator('Title');
			$this->getElement()->removeDecorator('Container');
		}

    // Do not render if nothing to show
    if( $paginator->getTotalItemCount() <= 0 ) {
      return $this->setNoRender();
    }

    // Add count to title if configured
    if($paginator->getTotalItemCount() > 0 ) {
      $this->_childCount = $paginator->getTotalItemCount();
    }
  }

  public function getChildCount() {
    return $this->_childCount;
  }
}