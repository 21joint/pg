<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminViewMapsListingTypeController.php 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteiosapp_AdminIosSubscriptionController extends Core_Controller_Action_Admin {

    /**
     * To browser members who have purchased subscription via ios-app.
     * 
     */
    public function manageAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteiosapp_admin_main', array(), 'siteiosapp_app_subscribers');
        $this->view->subnavigation = $subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteiosapp_app_subscribers', array(), 'siteiosapp_app_subscribers_manage');


        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        //GET LISTING TYPE TABLE
        $select = new Zend_Db_Select($db);
        $select->from('engine4_siteiosapp_userSubscription');
        $select->order('user_id DESC');
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);

        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    }

    /**
     * To view member purchase details 
     * 
     */
    public function viewAction() {
        $transaction_id = $this->_getParam('transaction_id');
        $iosSubscriptionsTable = Engine_Api::_()->getDbtable('userSubscriptions', 'siteiosapp');

        //DEFAULT LAYOUT
        $this->_helper->layout->setLayout('admin-simple');
        $isIosRowExist = $iosSubscriptionsTable->fetchRow(array(
            'transaction_id = ?' => $transaction_id
        ));
        if ($isIosRowExist) {
            $receipt = Engine_Api::_()->getApi('Core', 'siteapi')->getReceiptData($isIosRowExist->receipt, $transaction_id, $isIosRowExist->isSandbox);
            if (isset($receipt['receipt']) && !empty($receipt['receipt']))
                $this->view->receiptInfo = $receipt['receipt'];

            if (isset($receipt['latestReceipt']) && !empty($receipt['latestReceipt']))
                $this->view->receiptInfo = $receipt['latestReceipt'];

            if (isset($isIosRowExist->device_uuid) && !empty($isIosRowExist->device_uuid))
                $this->view->device_uuid = $isIosRowExist->device_uuid;
        }
    }

    /**
     * To browser packages need to be created in itunes for subscription.
     * 
     */
    public function iosPackagesAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteiosapp_admin_main', array(), 'siteiosapp_app_subscribers');
        $this->view->subnavigation = $subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteiosapp_app_subscribers', array(), 'siteiosapp_admin_subscription_plans');

        // Test curl support
        if (!function_exists('curl_version') ||
                !($info = curl_version())) {
            $this->view->error = $this->view->translate('The PHP extension cURL ' .
                    'does not appear to be installed, which is required ' .
                    'for interaction with payment gateways. Please contact your ' .
                    'hosting provider.');
        }
        // Test curl ssl support
        else if (!($info['features'] & CURL_VERSION_SSL) ||
                !in_array('https', $info['protocols'])) {
            $this->view->error = $this->view->translate('The installed version of ' .
                    'the cURL PHP extension does not support HTTPS, which is required ' .
                    'for interaction with payment gateways. Please contact your ' .
                    'hosting provider.');
        }
        // Check for enabled payment gateways
        else if (Engine_Api::_()->getDbtable('gateways', 'payment')->getEnabledGatewayCount() <= 0) {
            $this->view->error = $this->view->translate('There are currently no ' .
                    'enabled payment gateways. You must %1$sadd one%2$s before this ' .
                    'page is available.', '<a href="' .
                    $this->view->escape($this->view->url(array('controller' => 'gateway'))) .
                    '">', '</a>');
        }

        // Initialize select
        $table = Engine_Api::_()->getDbtable('packages', 'payment');
        $select = $table->select();
        $select->where('enabled = ?', 1);
        $select->where('signup = ?', true);
        $select->where('price > ?', 0);

        if (!empty($filterValues['order'])) {
            if (empty($filterValues['direction'])) {
                $filterValues['direction'] = 'ASC';
            }
            $select->order($filterValues['order'] . ' ' . $filterValues['direction']);
        }
        $packageName = $this->_getAppTitle();

        // Make paginator
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        foreach ($paginator as $package) {
            $values = $package->toArray();
            $values['recurrence'] = $values['recurrence'] . " " . $values['recurrence_type'];
            $values['duration'] = !empty($values['duration']) ? $values['duration'] . " " . $values['duration_type'] : $values['duration_type'];
            $values['package_description'] = $package->getDescription();
            if (isset($packageName) && !empty($packageName))
                $values['iTunesId'] = $packageName . ".plan" . $package->getIdentity();
            else
                $values['iTunesId'] = "-";
            $packages[] = $values;
        }
        $this->view->packagesInfo = $packages;
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    }

    /**
     * View details that need to be filled in itunes while package creation.
     * 
     */
    public function faqAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteiosapp_admin_main', array(), 'siteiosapp_app_subscribers');
        $this->view->subnavigation = $subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteiosapp_app_subscribers', array(), 'siteiosapp_admin_subscription_faq');

        $this->renderScript('admin-ios-subscription/faq.tpl');
    }

    private function _getAppTitle() {
        $getHost = $_SERVER['HTTP_HOST'];
        $this->view->getHost = $getHost = str_replace('www.', '', $getHost);
        $this->view->getHost = $getHost = str_replace(".", "-", $getHost);
        $this->view->parentDirectoryPath = $parentDirectoryPath = 'public/ios-' . $getHost . '-app-builder';
        $this->view->coreDirectoryPath = $coreDirectoryPath = APPLICATION_PATH . '/' . $parentDirectoryPath;
        if (!is_dir($coreDirectoryPath))
            return false;
        include $coreDirectoryPath . '/settings.php';
        return $appBuilderParams['package_name'];
    }

}
