<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_Plugin_Core extends Zend_Controller_Plugin_Abstract {

    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        $quickSignupAllowed = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitequicksignup.allow.quick.signup', 1);
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if (substr($request->getPathInfo(), 1, 5) != "admin" && $quickSignupAllowed) {
            if ($module == 'user' && $controller == 'signup' && $action == 'index') {
                $module = $request->setModuleName('sitequicksignup');
                $controller = $request->setControllerName('signup');
                $action = $request->setActionName('index');
            }
        }else if($quickSignupAllowed && (substr($request->getPathInfo(), 1, 5) == "admin")){
            if ($module == 'user' && $controller == 'admin-signup') {
                $module = $request->setModuleName('sitequicksignup');
                $controller = $request->setControllerName('admin-settings');
            }
        }
    }
    
     public function onUserSignupAfter($event) {
        
        $ifNotEnabledSiteLogin = !Engine_Api::_()->hasModuleBootstrap('sitelogin');
        $redirectlink = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitelogin.redirectlink', 2);
        if ($ifNotEnabledSiteLogin || (!$ifNotEnabledSiteLogin && $redirectlink == 2)){
            $session = new Zend_Session_Namespace('quick_signup');
            $session->quicksignup = 1;
        }
     }
     
     public function onRenderLayoutDefault($event, $mode = null) {
        $view = $event->getPayload();
        if (!($view instanceof Zend_View_Interface)) {
            return;
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        $isWelcomePopup = $view->settings('sitequicksignup.welcome.popup.enabled',1) && $view->settings('sitequicksignup.allow.quick.signup',1);
        $session = new Zend_Session_Namespace('quick_signup');
        if (!empty($session->quicksignup) && !empty($viewer->getIdentity()) && $isWelcomePopup) {
            unset($session->quicksignup);
            $urlEditProfile = $view->baseUrl() . '/members/edit/profile';
            $urlViewProfile = $view->baseUrl() . '/profile/'.$viewer->getIdentity();
            $userTitle = addslashes(ucwords($viewer->getTitle()));
            $editYourProfile = $view->translate('Edit Your Profile');
            $viewYourProfile = $view->translate('Go to Your Profile');
            $content = $view->translate('Welcome'). ' <strong>' .$userTitle.'</strong>, '.$view->translate('You have successfully signed up').'.<br>'.$view->translate('Where do you want to go now?');
            $script = <<<EOF
                en4.core.runonce.add(function(){
                           Smoothbox.open('<div class = "sitequicksignup_welcome_message_wrapper"><div class="sitequicksignup_welcome_popup_close_icon" onclick = "parent.Smoothbox.close()">X</div> <div class ="sitequicksignup_welcome_message" > $content </div> <div class = "sitequicksignup_welcome_options" > <button onclick = "document.location = \'$urlEditProfile\'"> $editYourProfile</button> <button onclick = "document.location = \'$urlViewProfile\'"> $viewYourProfile </button></div></div>');
                });
EOF;
        $view->headScript()->appendScript($script);
        } 

       
     
    }

}
