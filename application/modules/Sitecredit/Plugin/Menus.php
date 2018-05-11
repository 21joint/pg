<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Plugin_Menus {
	public function allowedToViewCredits()
	{
    // Must be logged in
		$viewer = Engine_Api::_()->user()->getViewer();
		if( !$viewer || !$viewer->getIdentity() ) {
			return false;
		}

		return true;
	}
}