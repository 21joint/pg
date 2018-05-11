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

class Sitefaq_Widget_FaqsSitefaqsController extends Seaocore_Content_Widget_Abstract {

  public function indexAction() {  	

		//GET WIDGET SETTINGS
    $current_time = date("Y-m-d H:i:s");
 		$this->view->popularity = $popularity = $this->_getParam('popularity', 'view_count');
		$interval = $this->_getParam('interval', 'overall');
		$totalPages = $this->_getParam('itemCount', 3);
		$this->view->featured = $featured = $this->_getParam('featured', 0);
		$privacy = $this->_getParam('privacy', 0);
		$this->view->rating = $this->_getParam('rating', 1);
		$this->view->column_type = $column_type = $this->_getParam('column', 1);
 		$this->view->title_truncation = $this->_getParam('truncation', 23);
		$this->view->statisticsRating = $this->_getParam('statisticsRating', 1);
		$this->view->statisticsHelpful = $this->_getParam('statisticsHelpful', 1);
		$this->view->statisticsComment = $this->_getParam('statisticsComment', 1);
		$this->view->statisticsView = $this->_getParam('statisticsView', 1);
		$this->view->viewAll = $this->_getParam('viewAll', 1);
		$category_id = $this->_getParam('category_id', 0);
		if(!empty($category_id)) {
			$category = Engine_Api::_()->getDbtable('categories', 'sitefaq')->getCategory($category_id);
			if(empty($category)) {
				$category_id = 0;
			}
		}

		//MAKE TIMING STRING
		if($interval == 'week') {
			$time_duration = date('Y-m-d H:i:s', strtotime('-7 days'));
			$sqlTimeStr = ".creation_date BETWEEN " . "'" . $time_duration . "'" . " AND " . "'" . $current_time . "'" ;
		}
		elseif($interval == 'month') {
			$time_duration = date('Y-m-d H:i:s', strtotime('-1 months'));
			$sqlTimeStr = ".creation_date BETWEEN " . "'" . $time_duration . "'" . " AND " . "'" . $current_time . "'" . "";
		}
		else {
			$sqlTimeStr = '';
		}

		$this->view->sitefaq_api = $sitefaq_api = Engine_Api::_()->sitefaq();
		$values = array();
		if($privacy) {
			//GET VIEWER
			$viewer = Engine_Api::_()->user()->getViewer();
			$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

			//GET USER LEVEL ID
			if (!empty($viewer_id)) {
				$level_id = $viewer->level_id;
			} else {
				$level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
			}

			if($level_id != 1) {
				$values['networks'] = $sitefaq_api->getViewerNetworks();
				$values['profile_types'] = $sitefaq_api->getViewerProfiles();
				$values['member_levels'] = $sitefaq_api->getViewerLevels();
			}
		}

		//GET PAGE RESULTS
		$this->view->faqDatas = Engine_Api::_()->getDbtable('faqs', 'sitefaq')->faqsBySettings($category_id, $popularity, $interval, $sqlTimeStr, $totalPages, $featured, $values);
		$sitefaq_dates = Zend_Registry::isRegistered('sitefaq_dates') ? Zend_Registry::get('sitefaq_dates') : null;

    //SET NO RENDER
    if (empty($sitefaq_dates) || !(Count($this->view->faqDatas) > 0)) {
      return $this->setNoRender();
    }
  }

}
