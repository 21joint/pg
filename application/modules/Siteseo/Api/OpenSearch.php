<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: SitemapIndex.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_Api_OpenSearch {

    protected $_view;

    protected function getView() {
        if (isset($this->_view)) {
            return $this->_view;
        }
        $this->_view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        return $this->_view;
    }

    public function getElementsArray() {

        $view = $this->getView();
        $content = array();
        $siteShortName = $_SERVER['HTTP_HOST'];
        $siteLongName  = $siteShortName . ' Search Suggestions';
        $siteInputEncoding  = 'UTF-8';
        $imageUrl  = '';
        $urlTemplate = $view->absoluteUrl($view->baseUrl('search'));
        $urlTemplate2 = $view->absoluteUrl($view->baseUrl('s'));

        $openSearchElements = array();
        $tag = array('tag' => 'ShortName', 'text' => $siteShortName);
        $openSearchElements[] = $tag;

        $tag = array('tag' => 'LongName', 'text' => $siteLongName);
        $openSearchElements[] = $tag;

        $tag = array('tag' => 'InputEncoding', 'text' => $siteInputEncoding);
        $openSearchElements[] = $tag;

        $attrib = array('height' => '16', 'width' => '16', 'type' => 'image/vnd.microsoft.icon');
        $tag = array('tag' => 'Image', 'text' => '', 'attrib' => $attrib);
        $openSearchElements[] = $tag;

        $subElements = array();
        $attrib = array('name' => 'query', 'value' => '{searchTerms}');
        $subTag = array('tag' => 'Param', 'text' => '', 'attrib' => $attrib);
        $subElements[] = $subTag;

        $attrib = array('name' => 'otracker', 'value' => '{start}');
        $subTag = array('tag' => 'Param', 'text' => '', 'attrib' => $attrib);
        $subElements[] = $subTag;

        $attrib = array('type' => 'text/html', 'rel' => 'results', 'method' => 'GET', 'template' => $urlTemplate);
        $tag = array('tag' => 'Url', 'text' => '', 'attrib' => $attrib, 'elements' => $subElements);
        $openSearchElements[] = $tag;

        $subElements = array();
        $attrib = array('name' => 'query', 'value' => '{searchTerms}');
        $subTag = array('tag' => 'Param', 'text' => '', 'attrib' => $attrib);
        $subElements[] = $subTag;

        $attrib = array('name' => 'from', 'value' => '{openSearch}');
        $subTag = array('tag' => 'Param', 'text' => '', 'attrib' => $attrib);
        $subElements[] = $subTag;

        $attrib = array('type' => 'application/x-suggestions+json', 'rel' => 'results', 'method' => 'GET', 'template' => $urlTemplate2);
        $tag = array('tag' => 'Url', 'text' => '', 'attrib' => $attrib, 'elements' => $subElements);
        $openSearchElements[] = $tag;
        return $openSearchElements;
    }
    
    public function getXMLString() {
        $elementsArray = $this->getElementsArray();
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        $osd = $dom->createElementNS('http://a9.com/-/spec/opensearch/1.1/', 'OpenSearchDescription');
        $dom->appendChild($osd);
        $xml = $this->convert($dom, $elementsArray, $osd)->saveXML();
        return $xml;
    }

    public function convert($dom, $elementsArray, $appendTo) {
        foreach ($elementsArray as $element) {
            $el = $dom->createElement($element['tag'], $element['text']);
            $appendTo->appendChild($el);
            if (isset($element['attrib']) && $element['attrib']) {
                foreach ($element['attrib'] as $key => $value) {
                    $attrib = $dom->createAttribute($key);
                    $attrib->value = $value;
                    $el->appendChild($attrib);
                }
            }
            if (isset($element['elements']) && $element['elements'] && is_array($element['elements'])) {
                $this->convert($dom, $element['elements'], $el);
            }
        }
        return $dom;
    }

    public function write($filename = 'osdd.xml') {
        $content = $this->getXMLString();
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($path, $content);
        @chmod($path, 0777);        
    }
}
