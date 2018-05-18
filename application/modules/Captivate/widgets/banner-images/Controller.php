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
class Captivate_Widget_BannerImagesController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        $this->getElement()->removeDecorator('Title');
        $this->view->defaultDuration = $this->_getParam("speed", 5000);
        $this->view->slideWidth = $this->_getParam("width", null);
        $this->view->slideHeight = $this->_getParam("height", 583);
        $this->view->showBanners = $this->_getParam('showBanners', 1);
        $selectedBanners = array();
        if (!$this->view->showBanners) {
            $selectedBanners = $this->_getParam('selectedBanners');

            if (empty($selectedBanners)) {
                return $this->setNoRender();
            }

            $this->view->list = $getBanners = Engine_Api::_()->getItemTable('captivate_banner')->getBanners(array('enabled' => 1, 'selectedBanners' => $selectedBanners), array('file_id'));
        } else {
            $this->view->list = $getBanners = Engine_Api::_()->getItemTable('captivate_banner')->getBanners(array('enabled' => 1), array('file_id'));
        }
        $order = $this->_getParam("order", 2);
        $captivate_landing_page_banner_image = Zend_Registry::isRegistered('captivate_landing_page_banner_image') ? Zend_Registry::get('captivate_landing_page_banner_image') : null;
        if (!COUNT($getBanners)) {
            $front = Zend_Controller_Front::getInstance();
            $module = $front->getRequest()->getModuleName();
            $action = $front->getRequest()->getActionName();
            $controller = $front->getRequest()->getControllerName();
            switch (true) {
                case $module == "core" && $controller == 'help' && $action == 'terms':
                    $this->view->list = array("terms_banner.png");
                    break;

                case $module == "core" && $controller == 'help' && $action == 'contact':
                    $this->view->list = array("contact_banner.png");
                    break;
                default :
                    $this->view->list = array("banner.png", "banner2.png", "banner3.png");
            }
        } else {
            $getBannersArray = $getBanners->toArray();
            if (!empty($order) && $order == 1) {
                $getBannersArray = @array_reverse($getBannersArray);
            } else if (!empty($order) && $order == 2) {
                @shuffle($getBannersArray);
            }
            $this->view->list = $getBannersArray;
        }

        $this->view->captivateHtmlTitle = $this->_getParam("captivateHtmlTitle", "Videos that you'd love");
        $this->view->captivateHtmlDescription = $this->_getParam("captivateHtmlDescription", "The foremost source to explore and watch videos.");

        if (empty($captivate_landing_page_banner_image)) {
            return $this->setNoRender();
        }
    }

}
