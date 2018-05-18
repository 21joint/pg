<?php
/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Siteseo
* @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: Google.php 2017-04-28 9:40:21Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/

class Siteseo_Api_Google extends Core_Api_Abstract {

    protected $_key;

    protected $_client;

    public function __construct() {
        require_once APPLICATION_PATH_MOD . '/Siteseo/libraries/google-api-php-client-2.1.3/vendor/autoload.php';
        require_once APPLICATION_PATH_MOD . '/Siteseo/libraries/KeyWordPositions/GoogleRankChecker.php';
    }

    // GET GOOGLE SEARCH CONSOLE SERVICE ACCOUNT KEY FILE
    public function getKey() {
        if( null === $this->_key ) {
            $this->_initialize();
        }
        return $this->_key;
    }

    // SET GOOGLE SEARCH CONSOLE SERVICE ACCOUNT KEY FOR THE FIRST TIME
    protected function _initialize() {

        $path = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteseo.google.service.account.key', false);
        if($path && file_exists($path)) {
            $this->_key = $path;
        } else {
            $this->_key = false;
        }
    }

  public function findKeywordRanking($keywords = array(), $domain = '')
  {
    $checker = new GoogleRankChecker();
    $foundKeywords = array();
    foreach( $keywords as $index => $keyword ) {
      $keyword = trim($keyword);
      if (empty($keyword)) {
        continue;
      }
      $cacheId = 'Siteseo_Google_Keyword_Rank_' . md5($keyword);
      $cache = Zend_Registry::get('Zend_Cache');
      $cached = false;
      $results = array();
      if( $cache instanceof Zend_Cache_Core ) {
        $data = $cache->load($cacheId);
        if( $data ) {
          $cached = true;
          $results = unserialize($data);
        }
      }
      if( !$cached ) {
        $results = $checker->find($keyword);
        if( $cache instanceof Zend_Cache_Core ) {
          $cache->save(serialize($results), $cacheId, array(), 3600);
        }
      }
      $foundKeywords[$keyword] = $results;
      if( $domain ) {
        $data = array();
        foreach( $results as $row ) {
          if( stripos($row['url'], $domain) !== false ) {
            $data[] = $row;
          }
        }
        $foundKeywords[$keyword] = $data;
      }
      if( !$cached && (count($keywords) != $index + 1) ) {
        sleep(rand(20, 25));
      }
    }
    return $foundKeywords;
  }

  // VALIDATE GOOGLE CLIENT OBJECT IF SITE IS ADDED
    public function validate() {

        if (empty(class_exists('Google_Client'))) 
            return false;

        if($this->getKey() === false)
            return false;

        $this->_client = $client = new Google_Client();
        // $client->setApplicationName("YourAppName");
        $client->setAuthConfig($this->getKey());
        $client->setScopes(array("https://www.googleapis.com/auth/webmasters", "https://www.googleapis.com/auth/webmasters.readonly"));
        // $client->setSubject('<YOUR EMAIL HERE >');

        try {
            // GET WEBSITE ADDRESS
            $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
            $website = $view->absoluteUrl($view->baseUrl());
            $service = new Google_Service_Webmasters($client);
            // $results = $service->sites->listSites();
            $results = $service->sites->get($website);
            $siteAdded = $results && $results->siteUrl && $results->permissionLevel;
            return $siteAdded;

        } catch (Exception $e) {
            return false;
        }
    }

    // SUBMIT SITEMAP TO THE SET OF THE USER'S SITES IN SEARCH CONSOLE
    public function submitSitemap() {

        // GET WEBSITE ADDRESS
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $website = $view->absoluteUrl($view->layout()->staticBaseUrl);
        // $website = 'http://devaddons1.socialengineaddons.com/sdeo-sitemap';

        try {

            $siteseo = Engine_Api::_()->getApi('sitemap','siteseo');
            // GET A LIST OF ALREADY SUBMITTED SITEMAPS
            // $sitemaps = $service->sitemaps->listSitemaps($website);
            $service = new Google_Service_Webmasters($this->_client);

            if ($siteseo->hasGlobalSitemap()) {
                $sitemapIndex = $siteseo->getPublicSitemapPath();
                $sitemapIndex = $view->absoluteUrl($view->layout()->staticBaseUrl . $sitemapIndex);
                $service->sitemaps->submit($website, $sitemapIndex);
            }
            
        } catch (Exception $e) {
            if($e instanceof Google_Service_Exception) {
                $message = function_exists('array_column') ? implode(array_column($e->getErrors(), 'message'), '  ') : reset($e->getErrors())['message'];
            }
            $tip = "Please follow the guidelines to for autosubmitting the sitemap directly to google search console.";
            $message = is_string($message) ? $message . $tip : $tip;
            return $message;
        }
        return true;
    }
}