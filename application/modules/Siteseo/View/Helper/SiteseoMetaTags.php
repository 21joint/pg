<?php

/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Siteseo
* @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: SeoMetaTags.php 2017-03-27 9:40:21Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/
class Siteseo_View_Helper_SiteseoMetaTags extends Zend_View_Helper_Abstract {

    // SET META TAGS FOR WEBSITE
    public function siteseoMetaTags() {

        $page = Engine_Api::_()->siteseo()->getCurrentPageinfo();
        if (empty($page))
            return false;

        $content = $this->getMetaData($page);

        // SET PAGE TITLE AND BASIC META TAGS
        // TITLE TAG WILL BE SET IN HEADTITLE HELPER
        // DESCRIPTION AND KEYWORDS TAGS WILL BE SET IN HEADMETA HELPER
        $this->view->headMeta()->setName('robots', $content['meta_robot']);
        $settings = Engine_Api::_()->getApi('settings', 'core');

        // ADD NEWS KEYWORD TAG FOR NEWS VIEW PAGES
        if (isset($content['news_keywords']) && $content['news_keywords'])
            $this->view->headMeta->setName('news_keywords', $content['news_keywords']);

        // ADD CUSTOM META TAG TO THE PAGE HEADER
        $this->view->layout()->headIncludes .= (string)$page->custom_metatags;
        
        // WORK FOR MULTIPLE VERSION OF WEBPAGE FOR DIFFERENT LANGUAGES
        if ($settings->getSetting('siteseo.hreflang.enable', 1)) {
            $url = $this->view->absoluteUrl($this->view->url());
            $translate = Zend_Registry::get('Zend_Translate');
            $languageList = $translate->getList();

            // SET HREFLANG TAG FOR DEFAULT LANGUAGE
            $params = array('hreflang' => 'x-default', 'title' => null, 'type' => null);
            $this->view->headLink()->appendAlternate($url, null, null, $params);

            // SET HREFLANG TAG FOR ALL LANGUAGES LANGUAGE
            if (count($languageList) > 1) {
                foreach ($languageList as $language) {
                    $params = array('hreflang' => $language, 'title' => null, 'type' => null);
                    $this->view->headLink()->appendAlternate("$url?locale=$language", null, null, $params);
                }
            }
        }

        // WORK FOR OPEN SEARCH DESCRIPTION
        if ($settings->getSetting("siteseo.opensearch.enable", 1) && file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'osdd.xml')) {

            // SET OPEN SEARCH XML FILE PATH
            $params = array('rel' => 'search', 'title' => null);
            $this->view->headLink()->appendAlternate('osdd.xml', 'application/opensearchdescription+xml', null, $params);
        }

        // WORK FOR DISPLAYING CANONICAL TAG
        if ($settings->getSetting("siteseo.canonical.enable", 1)) {
            $url = $this->view->absoluteUrl($this->view->url());

            // DISPLAY CANONICAL TAG
            $params = array('rel' => 'canonical', 'title' => null, 'type' => null);
            $this->view->headLink()->appendAlternate($url, null, null, $params);
        }
    }

    public function getMetaData($page) {
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $content = array();
        $content['meta_robot'] = $this->getRobotTag($page->meta_robot);
        $subject = $this->view->subject();
        if($subject) {
            if($subject->getType() == 'sitenews_news') {
                $keywords = $subject->getKeywords();
                $keywords = preg_replace('/\s+/', ' ', $keywords);
                $content['news_keywords'] = trim($keywords);
            }
        }
        return $content;
    }

    // RETURNS META ROBOT TAG CONTENT
    public function getRobotTag($value) {
        switch ($value) {
            case 1: return 'index, nofollow';
            case 2: return 'noindex, follow';
            case 3: return 'noindex, nofollow';
            case 0:
            default: return 'index, follow';
        }
    }
}