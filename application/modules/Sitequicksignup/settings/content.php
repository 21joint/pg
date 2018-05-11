<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
    array(
        'title' => 'Quick Signup Form',
        'description' => 'This widget is to display quick signup form.',
        'category' => 'Quick Sign Up',
        'type' => 'widget',
        'name' => 'sitequicksignup.signup',
        'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
        'adminForm' => array(
        ),
    ),
    array(
        'title' => 'Quick-Signup : Popup for Login and Signup',
        'description' => 'Uses a popup for the login and sign up forms when a user clicks to them via the Mini Menu.',
        'category' => 'Quick Sign Up',
        'type' => 'widget',
        'name' => 'sitequicksignup.login-or-signup-popup',
    ),
);
?>