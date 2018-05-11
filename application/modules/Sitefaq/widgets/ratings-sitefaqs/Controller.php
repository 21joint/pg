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
class Sitefaq_Widget_RatingsSitefaqsController extends Seaocore_Content_Widget_Abstract {

  public function indexAction() {
 
		//DON'T RENDER IF SUBJECT IS NOT SET
		if(!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
			return $this->setNoRender();
		}

		//GET FAQ SUBJECT
		$this->view->sitefaq = $sitefaq = Engine_Api::_()->core()->getSubject();
		if(empty($sitefaq)) {
			return $this->setNoRender();
		}

		//GET VIEWER DETAIL
		$viewer = Engine_Api::_()->user()->getViewer();
		$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		//RATING PRIVACY
		$this->view->can_rate = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'rating');

		if(empty($this->view->can_rate) && $sitefaq->rating <= 0) {
			return $this->setNoRender();
		}

		//GET RATING TABLE
		$tableRating = Engine_Api::_()->getDbTable('ratings', 'sitefaq');
    $this->view->rating_count = $tableRating->countRating($sitefaq->getIdentity());
    $this->view->sitefaq_rated = $tableRating->isRated($sitefaq->getIdentity(), $viewer_id);
  }

}