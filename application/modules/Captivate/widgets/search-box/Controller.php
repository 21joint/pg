<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Widget_SearchBoxController extends Engine_Content_Widget_Abstract {

    public function indexAction() {

        $this->view->searchbox_width = $this->_getParam('captivate_search_width', 240);

        $this->view->captivate_search_box_width_for_nonloggedin = $this->_getParam('captivate_search_box_width_for_nonloggedin', 275);
    }

}
