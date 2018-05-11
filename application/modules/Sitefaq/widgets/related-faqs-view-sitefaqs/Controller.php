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

class Sitefaq_Widget_RelatedFaqsViewSitefaqsController extends Engine_Content_Widget_Abstract
{ 
  public function indexAction()
  {
		//DON'T RENDER IF SUBJECT IS NOT SET
		if(!Engine_Api::_()->core()->hasSubject('sitefaq_faq')) {
			return $this->setNoRender();
		}

		//GET FAQ SUBJECT
		$subject = Engine_Api::_()->core()->getSubject();

		//GET VARIOUS WIDGET SETTINGS
		$this->view->statisticsRating = $this->_getParam('statisticsRating', 1);
		$this->view->statisticsComment = $this->_getParam('statisticsComment', 1);
		$this->view->statisticsView = $this->_getParam('statisticsView', 1);
		$this->view->truncation = $this->_getParam('truncation', 23);
		$related = $this->_getParam('related', 'categories');
		$privacy = $this->_getParam('privacy', 0);

		$params = array();

		If($related == 'tags') {

			//DON'T RENDER IF TAGGING IS OFF FROM GLOBAL SETTINGS
			if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.tag', 1)) {
				$this->setNoRender();
			}

			//GET TAGS
			$sitefaqTags = $subject->tags()->getTagMaps();

			$params['tags'] = array();
			foreach ($sitefaqTags as $tag) {
				$params['tags'][] = $tag->getTag()->tag_id;
			}

			if(empty($params['tags'])) {
				return $this->setNoRender();
			}

		}
		elseif($related == 'categories') {
			$params['categories'] = Zend_Json_Decoder::decode($subject->category_id);
		}
		else {
			return $this->setNoRender();
		}

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
				$sitefaq_api = Engine_Api::_()->sitefaq();
				$params['networks'] = $sitefaq_api->getViewerNetworks();
				$params['profile_types'] = $sitefaq_api->getViewerProfiles();
				$params['member_levels'] = $sitefaq_api->getViewerLevels();
			}
		}

    //FETCH FAQs
		$params['faq_id'] = $subject->faq_id;
    $params['orderby'] ='RAND()';
    $params['limit'] = $this->_getParam('itemCount', 3);
    $this->view->paginator = Engine_Api::_()->getDbtable('faqs', 'sitefaq')->widgetSitefaqsData($params);

    if (Count($this->view->paginator) <= 0) {
      return $this->setNoRender();
    }
  }

}