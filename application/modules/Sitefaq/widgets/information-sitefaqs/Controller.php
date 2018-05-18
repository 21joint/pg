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
class Sitefaq_Widget_InformationSitefaqsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

		//DON'T RENDER IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
      return $this->setNoRender();
    }

		//DON'T RENDER IF SUBJECT IS NOT SET FOR FAQ
		$this->view->sitefaq = $sitefaq = Engine_Api::_()->core()->getSubject('sitefaq_faq');
		if(empty($sitefaq)) {
      return $this->setNoRender();
		}

		//GET VIEWER ID
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

		//GET CATEGORY TABLE
		$this->view->tableCategory = $tableCategory = Engine_Api::_()->getDbTable('categories', 'sitefaq');

		//GET VARIOUS WIDGET SETTINGS
		$this->view->posted = $this->_getParam('posted', 1);
		$this->view->owner_photo = $this->_getParam('owner_photo', 0);
		$this->view->statisticsView = $this->_getParam('statisticsView', 1);
		$this->view->modified_date = $this->_getParam('update', 1);
		$this->view->creation_date = $this->_getParam('created', 0);
		$this->view->tags = $this->_getParam('tags', 1);

			//GET TAGS
		if(!empty($this->view->tags)) {
			$this->view->sitefaqTags = $sitefaq->tags()->getTagMaps();
		}
  }

}