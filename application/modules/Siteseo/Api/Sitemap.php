<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sitemap.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_Api_Sitemap extends Core_Api_Abstract {

	protected $_view;

	protected $_skipContentTypes;

	protected function getView() {
		if (isset($this->_view)) {
			return $this->_view;
		}
		$this->_view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		return $this->_view;
	}

	//CREATE CONTENT WISE SITEMAP FILE 
	public function buildContentSitemap($contentType, $update = true) {

		$siteseobuildContentSitemap = Zend_Registry::isRegistered('siteseobuildContentSitemap') ? Zend_Registry::get('siteseobuildContentSitemap') : null;
		if (empty($siteseobuildContentSitemap))
			return false;

		if(empty($contentType))
			return false;

		//GET ALL SITEMAP ELEMENTS IN REQUIRED ARRAY FORMAT
		$sitemapElements = $this->getSitemapElements($contentType);
		// RETURN FALSE IF THERE ARE NO SITEMAP ELEMENTS
		if (empty($sitemapElements)) {
			return false;
		}

		$setting = Engine_Api::_()->getApi('settings', 'core');
		$maxItems = $setting->getSetting('siteseo.sitemap.url.limit', 1000);
		$sitemapChunks = array_chunk($sitemapElements, $maxItems);
		$sitemapCount = count($sitemapChunks);

		//CREATE SITEMAP OF SITEMAP ELEMENTS THROUGH ZEND HELPER
		$view = $this->getView();
		$params = array();
		$params['total'] = $sitemapCount;
		$params['contentType'] = $contentType->type;
		foreach ($sitemapChunks as $index => $sitemapElements) {
			$container = new Zend_Navigation($sitemapElements);
			$sitemap = $view->sitemap($container)->setUseXslStylesheet(true)->render();
			$params['data'] = $view->sitemap($container)->setUseXslStylesheet(false)->render();
			$params['index'] = $index + 1;
			$filePath = $this->getSitemapPath($params);
			$status = file_put_contents($filePath, $sitemap);
			@chmod($filePath, 0777);
			$this->compressSitemap($params);
		}
		$contentType->sitemap_count = $sitemapCount;
		$contentType->lastmod = gmdate('Y-m-d H:i:s');
		$contentType->save();
		if($update)
			$this->buildIndexSitemap();
		return true;
	}

	// CREATE SITEMAP INDEX FILE
	public function buildIndexSitemap($params = array()) {

		$contentTypeTable = Engine_Api::_()->getDbtable('contenttypes','siteseo');
		$contentTypes = $contentTypeTable->getContentTypes(array('enabled' => 1));
		$view = $this->getView();
		$sitemapIndexElements = array();
		$setting = Engine_Api::_()->getApi('settings', 'core');
		$compressed = $setting->getSetting("siteseo.sitemapindex.url.compressed", true);
		foreach ($contentTypes as $type) {
			if(isset($params['contentSitemaps']) && $params['contentSitemaps'])
				$this->buildContentSitemap($type, false);
			foreach ($type->getPublicSitemapPath($compressed) as $path) {
				$fullPath = $view->baseUrl($path) ;
				$sitemapIndexElements[] = array(
					'uri' => $fullPath,
					'lastmod'   => $type->lastmod,
				);			
			}
		}
		$container = new Zend_Navigation($sitemapIndexElements);
		$sitemapIndex = $view->sitemapIndex($container)->setUseXslStylesheet(true)->render();
		$nonXslSitemapIndex = $view->sitemapIndex($container)->setUseXslStylesheet(false)->render();

		if(isset($params['download']) && $params['download'])
			return $nonXslSitemapIndex;
		$currentDate = gmdate('Y-m-d H:i:s');
		$filePath = $this->getSitemapPath();
		$status = file_put_contents($filePath, $sitemapIndex);
		@chmod($filePath, 0777);

		$params = array();
		$params['data'] = $nonXslSitemapIndex;
		$this->compressSitemap($params);
		if($status) {
			$setting->setSetting("siteseo.sitemap.modified.date", $currentDate);
		}
		return $status;
	}

	//RETURNS ARRAY OF ALL LINKS OF A PARTICULAR CONTENT TO BE LISTED IN SITEMAP 
	public function getSitemapElements($contentType) {
		switch ($contentType->type) {
			case 'menu_urls':
			return $this->getMenuSitemap($contentType->changefreq, $contentType->priority);
			case 'custom_pages':
			return $this->getCustomPagesSitemap($contentType->changefreq, $contentType->priority);
			default: return $this->getContentItemLinks($contentType);
		}
	}

	//RETURNS MENU URLS ARRAY FOR SITEMAP
	public function getMenuSitemap($changefreq, $priority) {

		$view = $this->getView();
		$url = $view->absoluteUrl($view->baseUrl('siteseo/url'));

        $httpClient = new Zend_Http_Client();
        $httpClient->setUri($url);
        $response = $httpClient->request('GET');
        $responseData = $response->getBody();
        $menuUrls = json_decode($responseData);

		$sitemapArray = array();
		$currentDate = gmdate('Y-m-d H:i:s');
		foreach ($menuUrls as $url) {
			$sitemapArray[] = array(
				'uri' => $url,
				'lastmod'   => $currentDate,
                'changefreq'    =>  $changefreq,
                'priority'  => $priority,
			);			
		}
		return $sitemapArray;
	}

	//RETURNS CUSTOM PAGES ARRAY FOR SITEMAP
	public function getCustomPagesSitemap($changefreq, $priority) {
		$pageArray = array();
		$params = array('paginator' => 0, 'plugin' => 'custom');
		$currentDate = gmdate('Y-m-d H:i:s');
		$pageInfoTable = Engine_Api::_()->getDbtable('pageinfo','siteseo');
		$corePages = $pageInfoTable->getCorePages($params);
		foreach ($corePages as $page) {
            $pageArray[] = array(
                'uri' => $page->getHref(),
                'lastmod'   => $currentDate,
                'changefreq'    =>  $changefreq,
                'priority'  => $priority,
            );
        }
        return $pageArray;
	}

	//RETURNS ARRAY OF CONTENT ITEMS OF A PARTICULAR TYPE FOR SITEMAP 
	public function getContentItemLinks($contentType) {

		$siteseogetContentItemLinks = Zend_Registry::isRegistered('siteseogetContentItemLinks') ? Zend_Registry::get('siteseogetContentItemLinks') : null;
		if (empty($siteseogetContentItemLinks))
			return false;

		$pages = array();
		if (!Engine_Api::_()->hasItemType($contentType->type)) {
			return $pages;
		}

		if ($this->skipContentSitemap($contentType->type)) {
			return $pages;
		}

		$coreSearchTable = Engine_Api::_()->getDbtable('search','core');
		$coreSearchTableName = $coreSearchTable->info('name');

		$select = $coreSearchTable->select()->where("type = ?", $contentType->type)->order('id DESC');
		if(!empty($contentType->max_items))
			$select->limit($contentType->max_items);
		$searchItems = $coreSearchTable->fetchAll($select); 

		$frequency = $contentType->changefreq;
		$priority = $contentType->priority;
		$currentDate = gmdate('Y-m-d H:i:s');

		foreach ($searchItems as $searchItem) {
			$item = Engine_Api::_()->getItem($searchItem->type, $searchItem->id);
			$isPublic = $item && Engine_Api::_()->authorization()->isAllowed($item, 'everyone', 'view');
			try {
				if ($isPublic && $item->getHref()) {
					$pages[] = array(
						'uri' => $item->getHref(),
						'lastmod'   => isset($item->modified_date) ? $item->modified_date : (isset($item->creation_date) ? $item->creation_date : $currentDate),
						'changefreq'    =>  $frequency,
						'priority'  => $priority,
						);
				}
			} catch (Exception $e) {
				continue;
			}
		}
		return $pages;
	}

	public function skipContentSitemap($contentType) {
		if (isset($this->_skipContentTypes)) {
			return in_array($contentType, $this->_skipContentTypes);
		}
		$this->_skipContentTypes = array('classified_photo', 'classified_album');
		return in_array($contentType, $this->_skipContentTypes);
	}

	//RETURNS CONTENT WISE SITEMAP FILE NAME
	public function getSitemapFileName($params = array()) {
		$contentType = isset($params['contentType']) ? $params['contentType'] : null;
		$total = isset($params['total']) ? $params['total'] : 1;
		$index = isset($params['index']) ? $params['index'] : 1;
		$setting = Engine_Api::_()->getApi('settings', 'core');
		$filename = $setting->getSetting("siteseo.sitemap.filename", 'sitemap');
		if(empty($contentType))
			return "$filename.xml";
		if($total == 1)
			return $filename . '_' . $contentType . '.xml';
		return $filename . '_' . $contentType . '_' . $index . '.xml';
	}

	//CHECK IF WEBSITE CONTAINS GLOBAL SITEMAP FILE OR NOT 
	public function hasGlobalSitemap ($contentType = null) {
		return file_exists($this->getSitemapPath());
	}

	//CHECK IF WEBSITE CONTAINS GLOBAL SITEMAP FILE OR NOT 
	public function hasGlobalCompressedSitemap ($contentType = null) {
		$filePath = $this->getSitemapPath();
		$filePath .='.gz';
		return file_exists($filePath);
	}

	//RETURNS CONTENT WISE SITEMAP PATH FOR WRITING
	public function getSitemapPath ($params = array()) {
		$filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public'. DIRECTORY_SEPARATOR .'siteseo';
    	if (!file_exists($filePath)) {
    		@mkdir($filePath);
    		@chmod($filePath, 0777);
    	}
		$filename = $this->getSitemapFileName($params);
        $fullFilePath = $filePath . DIRECTORY_SEPARATOR . $filename;
        return $fullFilePath;
	}

	//RETURNS CONTENT WISE SITEMAP PATH FOR DISPLAYING FILE
	public function getPublicSitemapPath ($params = array()) {
		$filename = $this->getSitemapFileName($params);
		return 'public/siteseo/' . $filename;
	}

	//COMPRESS GLOBAL SITEMAP FILE 
	public function compressSitemap ($params) {
		$filePath = $this->getSitemapPath($params);
		$data = isset($params['data']) ? $params['data'] : implode("", file($filePath));
		$filePath .= '.gz';
		$gzdata = gzencode($data, 9);
		file_put_contents($filePath, $gzdata);
		@chmod($filePath, 0777);
		return true;
	}

	//SUBMIT SITEMAP TO SEARCH ENGINES
	public function submitSitemap ($searchEngines, $regenerate =  false) {

		set_time_limit(999999);
		if ($regenerate || empty($this->hasGlobalSitemap())) {
			$this->buildIndexSitemap(array('contentSitemaps' => true));
		}
		$view = $this->getView();
		$sitemapUrl = $view->absoluteUrl($view->baseUrl($this->getPublicSitemapPath()));

		$statusArray = array();
		foreach ($searchEngines as $searchEngine) {
			switch ($searchEngine) {
				case 'google':
					$googleApi = Engine_Api::_()->getApi('google','siteseo');
					if ($googleApi->validate()) {
						$googleApi->submitSitemap();
					} else {
						$url = "http://www.google.com/webmasters/sitemaps/ping?sitemap=".$sitemapUrl;
						$statusArray[$searchEngine] = $this->curlGetResult($url);
					}
					break;
				case 'bing':
					$url = "http://www.bing.com/webmaster/ping.aspx?siteMap=".$sitemapUrl;
					$statusArray[$searchEngine] = $this->curlGetResult($url);
				default:
					break;
			}
		}
		$setting = Engine_Api::_()->getApi('settings', 'core');
		$setting->setSetting("siteseo.sitemap.submit.date", gmdate('Y-m-d H:i:s'));
		return $statusArray;
	}

	// CURL HANDLER TO PING THE SITEMAP SUBMISSION URLS FOR SEARCH ENGINES
	// RETURNS A RESULT FORM URL 
	public function curlGetResult($url){
        $httpClient = new Zend_Http_Client();
        $httpClient->setUri($url);
        $response = $httpClient->request('GET');
        $responseStatus = $response->getStatus();
        // $responseData = $response->getBody();
        return $responseStatus;
	}
}