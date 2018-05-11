<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Controller.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitehashtag_Widget_SearchFormController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $width = $this->_getParam('textWidth', 600);
        $search = $request->getParam('search');
        $this->view->search = urldecode($search);
        $this->view->width = $width;
        $this->view->limit = $this->_getParam('limit', 20);
        $this->view->search_criteria = $this->_getParam('search_criteria','tag_count');

    }

}
