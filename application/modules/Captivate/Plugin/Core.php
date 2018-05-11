<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Plugin_Core extends Zend_Controller_Plugin_Abstract {

    public function onRenderLayoutDefault($event) {

        $view = $event->getPayload();
        $view->headTranslate(array("Forgot Password?", "Login with Twitter", "Login with Facebook", "Mark as Read", "Mark as Unread"));
        $view->headLink()->appendStylesheet('https://fonts.googleapis.com/css?family=Roboto')
                ->appendStylesheet('https://fonts.googleapis.com/css?family=Source+Sans+Pro');
        $view->headLink()->appendStylesheet($view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/styles/style.css');

        $circularImageTheme = Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.circular.image', 0);
        if ($circularImageTheme) {
            $view->headLink()->appendStylesheet($view->layout()->staticBaseUrl . 'application/themes/captivate/theme_circular.css');
        }

        $floating_header = Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.floating.header', 1);
        $backgroundImage = '';
        if ((Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.theme.customization', 0) == 3) && !Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.theme.choose.website.image.color', 1)) {
            $backgroundImage = Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.theme.website.body.background.image', 0);
        }
        $themeTable = Engine_Api::_()->getDbtable('themes', 'core');
        $active = $themeTable->select()
                ->from($themeTable->info('name'), 'active')
                ->where('name = ?', 'captivate')
                ->where('active = ?', 1)
                ->query()
                ->fetchColumn()
        ;
        if ($active) {

            $includeThemeBasedClass = <<<EOF
                    var floating_header = '$floating_header';
                    var backgroundImage = '$backgroundImage';
        en4.core.runonce.add(function(){
        window.addEvent('domready', function() {
                setTimeout(function () {
                    if (floating_header == 0 && document.getElementsByTagName("BODY")[0]) {
                       document.getElementsByTagName("BODY")[0].addClass('captivate_non_floating_header');
                    }
                    if(backgroundImage)    
                    document.getElementsByTagName("BODY")[0].setStyle('background-image', 'url("$backgroundImage")');
                    if(($$('.layout_siteusercoverphoto_user_cover_photo').length > 0) || ($$('.layout_sitecontentcoverphoto_content_cover_photo').length > 0) || ($$('.layout_captivate_banner_images').length > 0)) {
                       if ($$('.layout_main')) {
                           $$('.layout_main').setStyles({
                            'width' : '1200px',
                            'margin' : '0 auto'
                           });
                       }
                    } 
                }, 100);
          });      
        });
EOF;
            $view->headScript()->appendScript($includeThemeBasedClass);
        }
    }

    public function onRenderLayoutDefaultSimple($event) {
        // Forward
        return $this->onRenderLayoutDefault($event, 'simple');
    }

}
