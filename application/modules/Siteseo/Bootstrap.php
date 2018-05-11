<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: core.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
	public function __construct($application)
	{
		parent::__construct($application);
		include APPLICATION_PATH . '/application/modules/Siteseo/controllers/license/license.php';
		// Add view helper and action helper paths
		$this->initViewHelperPath();
		$this->initActionHelperPath();
		//Initialize Eventdocuments helper
		Zend_Controller_Action_HelperBroker::addHelper(new Siteseo_Controller_Action_Helper_MetatagPage());
		// SITESEO MULTI LANGUAGE WORK
		$this->setHrefLanguage();
	}

	protected function setHrefLanguage() {
        // GET ALL LANGUAGES
        $translate = Zend_Registry::isRegistered('Zend_Translate') ? Zend_Registry::get('Zend_Translate') : null;
        $locale = isset($_GET['locale']) ? $_GET['locale'] : false;

        // SET LOCALE FROM GET PARAMETER
        if ($translate && $locale && $translate->isAvailable($locale) ) {
            $localeObject = new Zend_Locale($locale);
            Zend_Registry::set('Locale', $localeObject);
            $translate->setLocale($locale);

            setcookie('en4_language', $translate->getLocale(), time() + (86400*365), '/');
            setcookie('en4_locale', $locale, time() + (86400*365), '/');
            $_COOKIE['en4_locale'] = $translate->getLocale();
            $_COOKIE['en4_language'] = $locale;
        }
    }
}