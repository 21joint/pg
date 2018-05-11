<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Widget_SchemaMarkupController extends Engine_Content_Widget_Abstract {

	public function indexAction() {
		$siteseoSchemaMarkup = Zend_Registry::isRegistered('siteseoSchemaMarkup') ? Zend_Registry::get('siteseoSchemaMarkup') : null;
		if (empty($siteseoSchemaMarkup))
			return $this->setNoRender();

		// RENDER WIDGET ONLY ONCE EVEN IF IT IS PLACED MULTIPLE TIMES ON A PAGE
		if (Zend_Registry::isRegistered('isSchemaRendered')) {
			return $this->setNoRender();
		}
		Zend_Registry::set('isSchemaRendered', true);
	}
}
