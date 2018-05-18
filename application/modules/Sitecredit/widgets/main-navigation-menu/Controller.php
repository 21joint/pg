<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Widget_MainNavigationMenuController extends Engine_Content_Widget_Abstract
{

    public function indexAction() {
    
    	$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitecredit_main');
    	if ( count($this->view->navigation) == 1 ) {
      		$this->view->navigation = null;
    	}
  	}

}
