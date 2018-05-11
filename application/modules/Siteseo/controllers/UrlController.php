<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: UrlController.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_UrlController extends Core_Controller_Action_Standard {

	public function indexAction() {
		$navigation = new Zend_Navigation();
		$menuTable = Engine_Api::_()->getDbtable('menus', 'core');
		$menuApi = Engine_Api::_()->getApi('menus', 'core');
		$select = $menuTable->select();
		$settings = Engine_Api::_()->getApi('settings','core');
		$menuIds = $settings->getSetting('siteseo.sitemap.selectedmenu',"");
		$menuIds = json_decode($menuIds);

		$siteseoUrlControl = Zend_Registry::isRegistered('siteseoUrlControl') ? Zend_Registry::get('siteseoUrlControl') : null;
		if (empty($siteseoUrlControl))
			return;

		if (!empty($menuIds))
			$select->where('id IN ( ? )', $menuIds);
		else
			$select->where("name LIKE '%_main%'");

		$menus = $menuTable->fetchAll($select)->toArray();
		foreach ($menus as $menu) {
			if (strpos($menu['name'], 'dashboard') !== false)
				continue;
			$pages = $menuApi->getMenuParams($menu['name'], array());
			$navigation->addPages($pages);
		}

		$locValidator = new Zend_Validate_Sitemap_Loc();

		$urlArray = array();
		foreach ($navigation as $link) {
			try {
				$url = $this->view->absoluteUrl($link->getHref());
				if ($locValidator->isValid($url))
					$urlArray[] = $url;
			} catch (Exception $e) {
			}
		}
		$urlArray = array_values(array_unique($urlArray));
		return $this->_helper->json($urlArray);
	}

	public function sitemapAction() {
		$sitemapUrl = Engine_Api::_()->getApi('sitemap','siteseo')->getPublicSitemapPath();
		$this->_redirect($sitemapUrl);
	}
}
