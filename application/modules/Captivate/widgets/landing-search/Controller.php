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
class Captivate_Widget_LandingSearchController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        $this->view->captivateSearchBox = $this->_getParam('captivateSearchBox');
        $this->view->isSiteadvsearchEnabled = Engine_Api::_()->hasModuleBootstrap('siteadvsearch');
        $this->view->showLocationSearch = $this->_getParam('showLocationSearch', 0);
        $this->view->showLocationBasedContent = $this->_getParam('showLocationBasedContent', 0);
        $captivate_landing_page_search = Zend_Registry::isRegistered('captivate_landing_page_search') ? Zend_Registry::get('captivate_landing_page_search') : null;
        if (empty($captivate_landing_page_search)) {
            return $this->setNoRender();
        }
    }

}
