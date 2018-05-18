<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Core.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteiosapp_Plugin_Core extends Zend_Controller_Plugin_Abstract {

    public function onUserLoginBefore($event) {
        $payload = $event->getPayload();
        $user_id = $payload['user_id'];
        $user = Engine_Api::_()->getItem('user', $user_id);
        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteapi')) {
            $siteapimodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('siteapi');
            $siteapiversion = $siteapimodule->version;
            if ($siteapiversion > '4.8.12') {
                $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
                if (!$subscriptionsTable->check($user)) {
                    $isIosSubscriber = Engine_Api::_()->getApi('core', 'siteapi')->hasUserIosSubscription($user);
                    if (isset($isIosSubscriber) && !empty($isIosSubscriber)) {
                        Engine_Api::_()->getApi('core', 'siteapi')->hasUserIosSubscriptionExpire($user, $isIosSubscriber);
                    }
                }
            }
        }
    }

}
