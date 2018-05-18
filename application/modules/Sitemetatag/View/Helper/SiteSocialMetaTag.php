<?php

/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Sitemetatag
* @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: SiteSocialMetaTag.php 2017-03-27 9:40:21Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/
class Sitemetatag_View_Helper_SiteSocialMetaTag extends Zend_View_Helper_Abstract {

    // SET META TAGS FOR WEBSITE
    public function siteSocialMetaTag() {

        $page = Engine_Api::_()->sitemetatag()->getCurrentPageinfo();
        $content = $this->getMetaTagValues($page);
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $siteSocialMetaTag = Zend_Registry::isRegistered('siteSocialMetaTag') ? Zend_Registry::get('siteSocialMetaTag') : null;
        if (empty($siteSocialMetaTag))
            return ;

        // SET DOCTYPE THAT SUPPORTS OPEN GRAPH META TAGS
        $setRdfa = !($this->view->doctype()->isRdfa());
        if ($setRdfa) {
            $currentDoctype = $this->view->doctype()->getDoctype();
            $this->view->doctype('XHTML1_RDFA');
        }

        // SET OPEN GRAPH META TAGS IF ENABLED FOR PAGE
        $opengraph = $settings->getSetting("sitemetatag.opengraph.enable", 1);
        // SETTING BASED ON PARTICULAR PAGE
        $pageOpenGraph = $page ? (is_null($page->enable_opengraph) || $page->enable_opengraph) : true;
        if ($opengraph && $pageOpenGraph) {

            $this->view->headMeta()->setProperty('og:title', $content['title']);
            $this->view->headMeta()->setProperty('og:description', $content['description']);
            $this->view->headMeta()->setProperty('og:url', $content['url']);
            $this->view->headMeta()->setProperty('og:type', $content['type']);
            if($content['locale']) {
                $this->view->headMeta()->setProperty('og:locale', $content['locale']);
            }

            if($content['image']) {
                $this->view->headMeta()->setProperty('og:image', $content['image']);
            }
            $this->view->headMeta()->setProperty('og:site_name', $content['site_name'] );
            if ($content['fb_appid']) {
                $this->view->headMeta()->setProperty('fb:app_id', $content['fb_appid'] );
            }
        }

        // SET TWITTER CARDS META TAG IF ENABLED FOR PAGE
        $twittercards = $settings->getSetting("sitemetatag.twittercards.enable", 1);
        // SETTING BASED ON PARTICULAR PAGE
        $pageTwitterCards = $page ? (is_null($page->enable_opengraph) || $page->enable_opengraph) : true;
        if ($twittercards && $pageTwitterCards) {

            $this->view->headMeta()->setName('twitter:title', $content['title']);
            $this->view->headMeta()->setName('twitter:description', $content['description']);
            $this->view->headMeta()->setName('twitter:card', $content['card_type']);
            // TWITTER CARDS TITLE TAG WILL BE SET IN HEADTITLE HELPER
            // TWITTER CARDS DESCRIPTION TAG WILL BE SET IN HEADMETA HELPER
            $this->view->headMeta()->setName('twitter:url', $content['url']);
            if($content['image'])
                $this->view->headMeta()->setName('twitter:image', $content['image']);
            if($content['twitter_sitename']) 
                $this->view->headMeta()->setName('twitter:site', '@' . $content['twitter_sitename']);
        }

        // SET CURRENT DOCTYPE AFTER SETTING META TAGS
        if ($setRdfa) {
            $this->view->doctype($currentDoctype);
        }
    }

    public function getMetaTagValues($page) {

        $getMetaTagValues = Zend_Registry::isRegistered('getMetaTagValues') ? Zend_Registry::get('getMetaTagValues') : null;
        if (empty($getMetaTagValues))
            return ;
        
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $content = array('image' => '', 'title' => '', 'description' => '', 'type' => '');

        // GET SITE TITLE AND DESCRIPTION 
        $siteTitle = $settings->core_general_site_title;
        $siteDescription = $settings->core_general_site_description;

        $separator = ' | ';
        $titleArray = array();
        $descriptionArray = array();

        if ($page) {
            // widgetized page

            // GET PAGE TITLE AND DESCRIPTION 
            $pageTitle = $page->getTitle();
            $pageDescription = $page->getDescription();

            $subject = $this->view->subject();
            if ($subject) {
                $titleAppend = $settings->getSetting('sitemetatag_sitetile_append', 'none');
                $descriptionAppend = $settings->getSetting('sitemetatag_sitedescription_append', 'none');

                $subjectTitle = $subject->getTitle();
                $titleArray[] = $subjectTitle;
                switch ($titleAppend) {
                    case 'page': $titleArray[] = $pageTitle;
                    break;
                    case 'site': $titleArray[] = $siteTitle;
                    break;
                    case 'both': $titleArray[] = $pageTitle; $titleArray[] = $siteTitle;
                    break;
                }

                $subjectDescription = $subject->getDescription();
                $descriptionArray[] = $subjectDescription;
                switch ($descriptionAppend) {
                    case 'page': $descriptionArray[] = $pageDescription;
                    break;
                    case 'site': $descriptionArray[] = $siteDescription;
                    break;
                    case 'both': $descriptionArray[] = $pageDescription; $descriptionArray[] = $siteDescription;
                    break;
                }

                $image = $subject->getPhotoUrl();
                $image = $image ? : $page->getPhotoUrl();
            } else {
                $titleArray[] = $pageTitle;
                $titleArray[] = $siteTitle;
                $descriptionArray[] = $pageDescription;
                $descriptionArray[] = $siteDescription;
                $image = $page->getPhotoUrl();
            }

            $content['image'] = $image;
        } else {
            // Non widgetized page
            $titleArray[] = $settings->getSetting('sitemetatag_nonwidgetized_title', '');
            $descriptionArray[] = $settings->getSetting('sitemetatag_nonwidgetized_description', '');
            $titleArray[] = $siteTitle;
            $descriptionArray[] = $siteDescription;

            $image = $settings->getSetting('sitemetatag_nonwidgetized_image', '');
            $content['image'] = $image ? $this->view->baseUrl($image) : '';
        }


        // REMOVE SITE TITLE , PAGE TITLE OR SUBJECT TITLE IF EMPTY
        $titleArray = array_filter($titleArray);
        $descriptionArray = array_filter($descriptionArray);
        $title = implode($separator, $titleArray);
        $description = implode($separator, $descriptionArray);;

        // RMEOVE WHITE TABS AND SPACE
        $content['title'] = trim(preg_replace('/\s+/', ' ', $title));
        $description = strip_tags($description);
        $description = trim(preg_replace('/\s+/', ' ', $description));
        $description = Engine_String::strlen($description) > 255 ? Engine_String::substr($description, 0, 255) . '...' : $description ;
        $content['description'] = $description;
        
        $defaultImage = $settings->getSetting('sitemetatag.default.image', false);
        if (empty($content['image']) && $defaultImage) {
            $content['image'] = $this->view->baseUrl($defaultImage);         
        }
        $content['image'] = $content['image'] ? $this->view->absoluteUrl($content['image']) : false;
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $content['url'] = $this->view->absoluteUrl($request->getRequestUri());
        $content['site_name'] = $settings->core_general_site_title;
        $content['fb_appid'] = $settings->core_facebook_appid;
        $content['type'] = 'website';
        $content['card_type'] = 'summary_large_image';
        $content['twitter_sitename'] = $settings->sitemetatag_twitter_sitename;
        $locale = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en_US');
        $locale = Zend_Locale::findLocale($locale);
        $content['locale'] = strpos($locale, '_') !== false ? $locale : false;
        return $content;
    }
}