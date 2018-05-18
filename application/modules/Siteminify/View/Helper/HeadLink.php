<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: HeadLink.php 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteminify_View_Helper_HeadLink extends Zend_View_Helper_HeadLink
{

  /**
   * @var string
   */
  protected $_minifyLocation = '/siteminify/';
  protected $_perRequest = 3;
  protected $_counter;
  protected $_staticBaseUrl = null;
  protected $_querySeprator = '';
  protected $_styleSheets = array();
  protected $_ignore = array('font', 'static.younetco.com/ynicons');

  public function toString($indent = null)
  {
    $settingApi = Engine_Api::_()->getApi('settings', 'core');
    $this->_perRequest = $settingApi->getSetting('siteminify.css.combine.eachrequest', 5);
    $enabled = APPLICATION_ENV == 'production' && (bool) $this->_perRequest;
    if( !$enabled ) {
      return parent::toString($indent);
    }

    $this->setQuerySeprator();
    $indent = (null !== $indent) ? $this->getWhitespace($indent) : $this->getIndent();
    $items = array();
    $this->getContainer()->ksort();
    foreach( $this as $item ) {
      if( $this->_canMinify($item) ) {
        $this->_addMinifyStyleSheet($item);
        continue;
      }
      $this->_processMinify($items);
      $items [] = $this->itemToString($item);
    }

    $this->_processMinify($items);
    return $indent . implode($this->_escape($this->getSeparator()) . $indent, $items);
  }

  /**
   * Retrieve the minify URL
   *
   * @return string
   */
  public function getMinifiyUrl()
  {
    return $this->getStaticBaseUrl() . $this->_minifyLocation . $this->_querySeprator;
  }

  /**
   * Retrieve the currently set base URL
   *
   * @return string
   */
  public function getBaseUrl()
  {
    return Zend_Controller_Front::getInstance()->getBaseUrl();
  }

  public function getStaticBaseUrl()
  {
    if( $this->_staticBaseUrl == null ) {
      $this->_staticBaseUrl = Zend_Registry::isRegistered('StaticBaseUrl') ? rtrim(Zend_Registry::get('StaticBaseUrl'), '/') : $this->getBaseUrl();
    }
    return $this->_staticBaseUrl;
  }

  protected function _canMinify($item)
  {
    if( !isset($item->type) || $item->type != 'text/css' || $item->conditionalStylesheet === true ) {
      return false;
    }

    $href = $this->_makeCleanUrl($item->href);
    $host = parse_url($href, PHP_URL_HOST);
    if( $host ) {
      return false;
    }

    $isValid = strpos($href, '.css') !== false;
    foreach( $this->_ignore as $content ) {
      if( strpos($href, $content) ) {
        $isValid = false;
        break;
      }
    }
    return $isValid;
  }

  public function getCounter()
  {
    if( $this->_counter == NULL ) {
      return time();
    }
    return $this->_counter;
  }

  public function setCounter($counter)
  {
    $this->_counter = $counter;
    return $this;
  }

  public function setQuerySeprator()
  {
    if( strpos($this->getBaseUrl(), 'index.php') ) {
      $this->_querySeprator = '?';
    }
    return $this;
  }

  protected function _setCounterFromSrc($href)
  {
    if( $this->_counter != NULL ) {
      return $this;
    }
    preg_match("#\?c=(?P<counter>\d+)#", $href, $matches);
    if( empty($matches['counter']) ) {
      return $this;
    }
    return $this->setCounter($matches['counter']);
  }

  protected function _addMinifyStyleSheet($item)
  {
    $href = $item->href;
    $href = $this->_setCounterFromSrc($href)->_makeCleanUrl($href);
    if( !$href ) {
      return;
    }
    $this->_styleSheets[$item->media][$href] = $href;
  }

  public function _makeCleanUrl($url)
  {
    $url = str_replace($this->getStaticBaseUrl() . '/', '/', $url);
    $pattern = array('/(\?.*)/', '/\/\//');
    $replacement = array('', '');
    return ltrim(preg_replace($pattern, $replacement, $url), " /");
  }

  protected function _processMinify(&$items)
  {
    $minifiyUrl = $this->getMinifiyUrl();
    $staticBaseURl = ltrim($this->getStaticBaseUrl(), '/');
    $baseUrl = ltrim($this->getBaseUrl(), '/');
    if( $baseUrl && $baseUrl === $staticBaseURl ) {
      $minifiyUrl .= 'b=' . str_replace('/index.php', '', $baseUrl) . '&';
    }
    foreach( $this->_styleSheets as $media => $styleSheets ) {
      foreach( array_chunk($styleSheets, $this->_perRequest) as $styles ) {
        $minStyles = new stdClass();
        $minStyles->rel = 'stylesheet';
        $minStyles->type = 'text/css';
        $minStyles->href = $minifiyUrl . 'f=' . implode(',', $styles) . '&v=' . $this->getCounter();
        $minStyles->media = $media;
        $minStyles->conditionalStylesheet = false;
        $items[] = $this->itemToString($minStyles);
      }
    }
    $this->_styleSheets = array();
    return $items;
  }
}
