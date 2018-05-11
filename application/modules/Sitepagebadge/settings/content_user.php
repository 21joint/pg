<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content_user.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
return array(
    array(
        'title' => $view->translate('Page Profile Badge'),
        'description' => $view->translate('Displays the badge assigned to your Page.'),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepagebadge.badge-sitepagebadge',
        'defaultParams' => array(
            'title' => $view->translate('Badge'),
            'titleCount' => true,
        ),
    ),
)
?>