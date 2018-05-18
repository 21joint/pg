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

class Sitefaq_Widget_FeaturedViewSitefaqsController extends Engine_Content_Widget_Abstract {
	
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

		//GET WIDGET SETTING
		$this->view->featured_color = $this->_getParam('featured_color', '#55c9f5');
	}

}