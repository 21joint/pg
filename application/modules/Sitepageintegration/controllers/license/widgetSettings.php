<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    sitepageintergration
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: WidgetSettings.php 6590 2010-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

$sitepageVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule('sitepage')->version;
if ($sitepageVersion >= '4.3.0p1') {
	// START LANGUAGE WORK
	Engine_Api::_()->getApi('language', 'sitepage')->languageChanges();
	// END LANGUAGE WORK
}

?>