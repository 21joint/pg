<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content_user.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
return array(
    array(
        'title' => $view->translate('Page Profile Form'),
        'description' => $view->translate('Forms the Form tab of your Page and shows the form created by you from the "Form" section of Apps in your Dashboard. It should be placed in the Tabbed Blocks area of the Page Profile. Results of forms submitted by visitors are emailed to the Admins of your Page.'),
        'category' => $view->translate('Page Profile'),
        'type' => 'widget',
        'name' => 'sitepageform.sitepage-viewform',
        'defaultParams' => array(
            'title' => $view->translate('Form'),
        ),
    ),
)
?>