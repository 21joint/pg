<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: fields.tpl 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $ifSiteLogin = Engine_Api::_()->hasModuleBootstrap('sitelogin'); 
      $formLoaded = false; ?>
<?php if(!$ifSiteLogin): ?>
<?php include APPLICATION_PATH . '/application/modules/Sitequicksignup/views/scripts/_signupFields.tpl'; ?>
<?php else: ?>
<?php 
    $formLoaded = true; 
    include APPLICATION_PATH . '/application/modules/Sitelogin/views/scripts/signup/form/account.tpl'; 
    include APPLICATION_PATH . '/application/modules/Sitequicksignup/views/scripts/_signupFields.tpl'; ?>
<?php endif; ?>