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
class Captivate_Widget_MenuFooterController extends Engine_Content_Widget_Abstract {

    public function indexAction() {

        $islanguage = $this->view->translate()->getLocale();
        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        if (!strstr($islanguage, '_')) {
            $islanguage = $islanguage . '_default';
        }

        $keyForSettings = str_replace('_', '.', $islanguage);
        $captivatefooterLendingBlockValue = $coreSettings->getSetting('captivate.footer.lending.block.languages.' . $keyForSettings, null);

        $captivatefooterLendingBlockTitleValue = $coreSettings->getSetting('captivate.footer.lending.block.title.languages.' . $keyForSettings, null);
        if (empty($captivatefooterLendingBlockValue)) {
            $captivatefooterLendingBlockValue = $coreSettings->getSetting('captivate.footer.lending.block', null);
        }

        if (!empty($captivatefooterLendingBlockValue))
            $this->view->captivatefooterLendingBlockValue = @base64_decode($captivatefooterLendingBlockValue);
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation("captivate_footer");
        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        $this->view->showCaptivateFooterTemplate = $coreSettings->getSetting('captivate.footer.templates', 2);
        $this->view->selectFooterBackground = $coreSettings->getSetting('captivate.footer.background', 1);
        
        $this->view->showFooterBackgroundImage = $coreSettings->getSetting('captivate.footer.backgroundimage');
        $this->view->showFooterLogo = $coreSettings->getSetting('captivate.footer.show.logo', 1);
        $this->view->selectFooterLogo = $coreSettings->getSetting('captivate.footer.select.logo');
        $captivate_landing_page_footer_menu = Zend_Registry::isRegistered('captivate_landing_page_footer_menu') ? Zend_Registry::get('captivate_landing_page_footer_menu') : null;
        if(empty($captivate_landing_page_footer_menu))
            return $this->setNoRender();

        $this->view->social_links_array = $social_link_array = $coreSettings->getSetting('captivate.social.links', array("facebooklink", "twitterlink", "pininterestlink", "youtubelink", "linkedinlink"));
        if (!empty($social_link_array)) {
            if (in_array('facebooklink', $social_link_array)) {
                $this->view->facebook_url = $coreSettings->getSetting('captivate.facebook.url', 'http://www.facebook.com/');


                $this->view->facebook_default_icon = $temp_facebook_default_icon = $coreSettings->getSetting('captivate.facebook.default.icon', $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/facebook.png');

                if ($temp_facebook_default_icon == 'application/modules/Captivate/externals/images/facebook.png') {
                    $this->view->facebook_default_icon = $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/facebook.png';
                }

                $this->view->facebook_hover_icon = $temp_facebook_hover_icon = $coreSettings->getSetting('captivate.facebook.hover.icon', $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overfacebook.png');

                if ($temp_facebook_hover_icon == 'application/modules/Captivate/externals/images/overfacebook.png') {
                    $this->view->facebook_hover_icon = $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overfacebook.png';
                }

                $this->view->facebook_title = $coreSettings->getSetting('captivate.facebook.title', 'Like us on Facebook');
            }
            if (in_array('pininterestlink', $social_link_array)) {
                $this->view->pinterest_url = $coreSettings->getSetting('captivate.pinterest.url', 'https://www.pinterest.com/');

                $this->view->pinterest_default_icon = $temp_pinterest_default_icon = $coreSettings->getSetting('captivate.pinterest.default.icon', $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/pinterest.png');
                if ($temp_pinterest_default_icon == 'application/modules/Captivate/externals/images/pinterest.png') {
                    $this->view->pinterest_default_icon = $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/pinterest.png';
                }

                $this->view->pinterest_hover_icon = $temp_pinterest_hover_icon = $coreSettings->getSetting('captivate.pinterest.hover.icon', $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overpinterest.png');
                if ($temp_pinterest_hover_icon == 'application/modules/Captivate/externals/images/overpinterest.png') {
                    $this->view->pinterest_hover_icon = $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overpinterest.png';
                }

                $this->view->pinterest_title = $coreSettings->getSetting('captivate.pinterest.title', 'Pinterest');
            }
            if (in_array('twitterlink', $social_link_array)) {
                $this->view->twitter_url = $coreSettings->getSetting('captivate.twitter.url', 'https://www.twitter.com/');

                $this->view->twitter_default_icon = $temp_twitter_default_icon = $coreSettings->getSetting('captivate.twitter.default.icon', $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/twitter.png');
                if ($temp_twitter_default_icon == 'application/modules/Captivate/externals/images/twitter.png') {
                    $this->view->twitter_default_icon = $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/twitter.png';
                }

                $this->view->twitter_hover_icon = $temp_twitter_hover_icon = $coreSettings->getSetting('captivate.twitter.hover.icon', $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overtwitter.png');
                if ($temp_twitter_hover_icon == 'application/modules/Captivate/externals/images/overtwitter.png') {
                    $this->view->twitter_hover_icon = $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overtwitter.png';
                }

                $this->view->twitter_title = $coreSettings->getSetting('captivate.twitter.title', 'Follow us on Twitter');
            }
            if (in_array('youtubelink', $social_link_array)) {
                $this->view->youtube_url = $coreSettings->getSetting('captivate.youtube.url', 'http://www.youtube.com/');

                $this->view->youtube_default_icon = $temp_youtube_default_icon = $coreSettings->getSetting('captivate.youtube.default.icon', $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/youtube.png');
                if ($temp_youtube_default_icon == 'application/modules/Captivate/externals/images/youtube.png') {
                    $this->view->youtube_default_icon = $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/youtube.png';
                }

                $this->view->youtube_hover_icon = $temp_youtube_hover_icon = $coreSettings->getSetting('captivate.youtube.hover.icon', $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overyoutube.png');
                if ($temp_youtube_hover_icon == 'application/modules/Captivate/externals/images/overyoutube.png') {
                    $this->view->youtube_hover_icon = $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overyoutube.png';
                }

                $this->view->youtube_title = $coreSettings->getSetting('captivate.youtube.title', 'Youtube');
            }
            if (in_array('linkedinlink', $social_link_array)) {
                $this->view->linkedin_url = $coreSettings->getSetting('captivate.linkedin.url', 'https://www.linkedin.com/');

                $this->view->linkedin_default_icon = $temp_linkedin_default_icon = $coreSettings->getSetting('captivate.linkedin.default.icon', $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/linkedin.png');
                if ($temp_linkedin_default_icon == 'application/modules/Captivate/externals/images/linkedin.png') {
                    $this->view->linkedin_default_icon = $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/linkedin.png';
                }

                $this->view->linkedin_hover_icon = $temp_linkedin_hover_icon = $coreSettings->getSetting('captivate.linkedin.hover.icon', $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overlinkedin.png');
                if ($temp_linkedin_hover_icon == 'application/modules/Captivate/externals/images/overlinkedin.png') {
                    $this->view->linkedin_hover_icon = $this->view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overlinkedin.png';
                }

                $this->view->linkedin_title = $coreSettings->getSetting('captivate.linkedin.title', 'LinkedIn');
            }
        }
    }

}
