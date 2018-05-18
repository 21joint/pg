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
class Captivate_Widget_ImagesController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        $this->getElement()->removeDecorator('Title');
        $this->view->defaultDuration = $this->_getParam("speed", 5000);
        $this->view->slideWidth = $this->_getParam("width", null);
        $this->view->slideHeight = $this->_getParam("height", 583);
        $this->getElement()->removeDecorator('Title');
        $this->view->showLogo = $this->_getParam('showLogo');
        $this->view->logo = $this->_getParam('logo');
        $this->view->isSitemenuExist = $isSitemenuExist = Engine_Api::_()->hasModuleBootstrap('sitemenu');
        $tempSitemenuLtype = $tempHostType = 8756;
        $this->view->captivateSignupLoginLink = $this->_getParam("captivateSignupLoginLink", 1);
        $this->view->captivateBrowseMenus = $this->_getParam("captivateBrowseMenus", 1);
        $this->view->captivateFirstImprotantLink = $this->_getParam("captivateFirstImprotantLink", 1);
        $this->view->captivateFirstTitle = $this->_getParam("captivateFirstTitle", 'Important Title & Link');
        $this->view->coreSettings = $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        $captivateGlobalType = $coreSettings->getSetting('captivate.global.type', 0);
        $hostType = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
        $captivateManageType = $coreSettings->getSetting('captivate.manage.type', 1);
        $this->view->captivateFirstUrl = $this->_getParam("captivateFirstUrl", '#');
        $this->view->captivateHtmlTitle = $this->_getParam("captivateHtmlTitle", 'BRING PEOPLE TOGETHER');
        $this->view->getImageSrcPoint = false;
        $this->view->captivateHtmlDescription = $this->_getParam("captivateHtmlDescription", 'Watch Videos, Explore Channels and Create & Share Playlists.');
        $this->view->captivateSignupLoginButton = $this->_getParam("captivateSignupLoginButton", 0);
        $this->view->captivateSearchBox = $this->_getParam("captivateSearchBox", Engine_Api::_()->hasModuleBootstrap('siteevent') ? '2' : '1');

        $this->view->showLocationSearch = $this->_getParam('showLocationSearch', 0);
        $this->view->showLocationBasedContent = $this->_getParam('showLocationBasedContent', 0);
        $this->view->showNextLink = $this->_getParam('showNextLink', 1);

        $this->view->showImages = $this->_getParam('showImages', 1);
        $selectedImages = array();
        if (!$this->view->showImages) {
            $selectedImages = $this->_getParam('selectedImages');

            if (empty($selectedImages)) {
                return $this->setNoRender();
            }

            $this->view->list = $getImages = Engine_Api::_()->getItemTable('captivate_image')->getImages(array('enabled' => 1, 'selectedImages' => $selectedImages), array('file_id'));
        } else {
            $this->view->list = $getImages = Engine_Api::_()->getItemTable('captivate_image')->getImages(array('enabled' => 1), array('file_id'));
        }
        $order = $this->_getParam("order", 2);
        if (!COUNT($getImages)) {
            $this->view->list = array("1.jpg", "2.jpg", "3.jpg");
        } else {
            $getImagesArray = $getImages->toArray();
            if (!empty($order) && $order == 1) {
                $getImagesArray = @array_reverse($getImagesArray);
            } else if (!empty($order) && $order == 2) {
                @shuffle($getImagesArray);
            }
            $this->view->list = $getImagesArray;
        }

        $captivate_landing_page_images = Zend_Registry::isRegistered('captivate_landing_page_images') ? Zend_Registry::get('captivate_landing_page_images') : null;
        $captivateInfoType = $coreSettings->getSetting('captivate.info.type', 1);
        $captivateLtype = $coreSettings->getSetting('captivate.lsettings', 0);

        if (!count($this->view->list)) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $page_id = Engine_Api::_()->captivate()->getWidgetizedPageId(array('name' => 'core_index_index'));
            $db->query("UPDATE `engine4_core_pages` SET  `layout` =  '' WHERE  `engine4_core_pages`.`page_id` = $page_id LIMIT 1 ;");
            return $this->setNoRender();
        }

        if (empty($captivateGlobalType)) {
            for ($check = 0; $check < strlen($hostType); $check++) {
                $tempHostType += @ord($hostType[$check]);
            }

            for ($check = 0; $check < strlen($captivateLtype); $check++) {
                $tempSitemenuLtype += @ord($captivateLtype[$check]);
            }
        }

        $this->view->max = $this->_getParam('max', 4);
        $this->view->captivateHowItWorks = $this->_getParam('captivateHowItWorks', 1);

        $islanguage = $this->view->translate()->getLocale();

        if (!strstr($islanguage, '_')) {
            $islanguage = $islanguage . '_default';
        }

        $keyForSettings = str_replace('_', '.', $islanguage);
        $captivateLendingBlockValue = $coreSettings->getSetting('captivate.lending.block.languages.' . $keyForSettings, null);

        $captivateLendingBlockTitleValue = $coreSettings->getSetting('captivate.lending.block.title.languages.' . $keyForSettings, null);
        if (empty($captivateLendingBlockValue)) {
            $captivateLendingBlockValue = $coreSettings->getSetting('captivate.lending.block', null);
        }
        if (empty($captivateLendingBlockTitleValue)) {
            $captivateLendingBlockTitleValue = $coreSettings->getSetting('captivate.lending.block.title', null);
        }

        if ((empty($captivateGlobalType)) && (($captivateManageType != $tempHostType) || ($captivateInfoType != $tempSitemenuLtype))) {
            $this->view->getImageSrcPoint = true;
            // return $this->setNoRender();
        }

        if (!empty($captivateLendingBlockValue))
            $this->view->captivateLendingBlockValue = @base64_decode($captivateLendingBlockValue);
        if (!empty($captivateLendingBlockTitleValue))
            $this->view->captivateLendingBlockTitleValue = @base64_decode($captivateLendingBlockTitleValue);
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

        $this->view->removePadding = false;

        //GET CONTENT ID
        $content_id = $this->view->identity;
        $content_page_id = Engine_Api::_()->captivate()->getContentPageId(array('content_id' => $content_id));
        $layoutValue = Engine_Api::_()->captivate()->getWidgetizedPageLayoutValue(array('page_id' => $content_page_id));
        $this->view->headerAlreadyPlaced = false;
        if ($layoutValue == 'default-simple') {
            Zend_Layout::startMvc()->setViewBasePath(APPLICATION_PATH . "/application/modules/Captivate/layouts", 'Core_Layout_View');
            $this->view->removePadding = true;
        } else {
            $this->view->headerAlreadyPlaced = true;
        }
        $this->view->isPost = Zend_Controller_Front::getInstance()->getRequest()->isPost();

        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $isSubscriptionEnabled = false;
        $this->view->show_signup_popup = true;
        if (empty($viewer_id)) {
            $tempClassArray = array(
                'Payment_Plugin_Signup_Subscription',
                'Sladvsubscription_Plugin_Signup_Subscription'
            );
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
            $subscriptionObj = $db->query('SELECT `class` FROM `engine4_user_signup` WHERE  `enable` = 1 ORDER BY `engine4_user_signup`.`order` ASC LIMIT 1')->fetch();
            if (!empty($subscriptionObj) && isset($subscriptionObj['class']) && !empty($subscriptionObj['class']) && in_array($subscriptionObj['class'], $tempClassArray)) {
                $isSubscriptionEnabled = true;
            }
        }
        if (!empty($isSubscriptionEnabled)) {
            $this->view->show_signup_popup = false;
        }

        if ($this->view->headerAlreadyPlaced) {
            $this->view->captivateSignupLoginLink = 0;
            $this->view->captivateBrowseMenus = 0;
            $this->view->captivateFirstImprotantLink = 0;
            $this->view->showLogo = false;
        }

        if (empty($captivate_landing_page_images)) {
            return $this->setNoRender();
        }
    }

}
