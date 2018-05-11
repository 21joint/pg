<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: pageinfo.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Model_Contenttype extends Core_Model_Item_Abstract {
	
	protected $_type = 'siteseo_contenttype';
	protected $_searchTriggers = false;

	public function hasSitemap() {
		$filePath = Engine_Api::_()->getApi('sitemap','siteseo')->getSitemapPath($this->type);
		if(file_exists($filePath))
			return true;
		return false;
	}

	//RETURNS ARRAY OF PATHS OF ALL SITEMAPS FILES OF A PARTICULAR CONTENT
	public function getPublicSitemapPath($compressed = false) {

		$pathArray = array();
		$ext = $compressed ? '.gz' : '';
		$params = array();
		$params['contentType'] = $this->type;
		$params['total'] = $this->sitemap_count;
		$params['index'] = 1;
		$siteseo = Engine_Api::_()->getApi('sitemap','siteseo');
		while($params['index'] <= $this->sitemap_count) {
			$path = $siteseo->getSitemapPath($params);
			$path .= $ext;
			if(file_exists($path)){
				$path = $siteseo->getPublicSitemapPath($params);
				$pathArray[] = $path . $ext;
			}
			$params['index']++;
		}
		return $pathArray;
	}
}