<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: HeadScript.php 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteminify_View_Helper_HeadScript extends Zend_View_Helper_HeadScript
{

  /**
   * @var string
   */
  protected $_minifyLocation = '/siteminify/';
  protected $_counter;
  protected $_staticBaseUrl = null;
  protected $_minifyScripts = array();
  protected $_perRequest = 10;
  protected $_ignore = array('externals/tinymce', 'jquery', '/recaptcha/api');
  protected $_querySeprator = '';

  public function toString($indent = null)
  {
    $settingApi = Engine_Api::_()->getApi('settings', 'core');
    $this->_perRequest = $settingApi->getSetting('siteminify.js.combine.eachrequest', 5);
    $enabled = APPLICATION_ENV == 'production' && (bool) $this->_perRequest;
    if( !$enabled ) {
      return parent::toString($indent);
    }
    $ignoreStr = $settingApi->getSetting('siteminify.js.ignore', '');
    if( trim($ignoreStr) ) {
      $this->_ignore = array_merge($this->_ignore, explode(',', $ignoreStr));
    }

    if( $this->view ) {
      $useCdata = $this->view->doctype()->isXhtml() ? true : false;
    } else {
      $useCdata = $this->useCdata ? true : false;
    }

    $escapeStart = ($useCdata) ? '//<![CDATA[' : '//<!--';
    $escapeEnd = ($useCdata) ? '//]]>' : '//-->';
    $this->setQuerySeprator();
    $this->getContainer()->ksort();
    $indent = (null !== $indent) ? $this->getWhitespace($indent) : $this->getIndent();
    $items = array();
    foreach( $this as $item ) {
      if( $this->_canMinify($item) ) {
        $this->_addMinifySrc($item->attributes['src']);
        continue;
      }
      $this->_processMinify($items);
      $items [] = $this->itemToString($item, $indent, $escapeStart, $escapeEnd); // add this item
    }
    $this->_processMinify($items);
    $xhtml = $indent . implode($this->_escape($this->getSeparator()) . $indent, $items);
    return $xhtml;
  }

  /**
   * Retrieve the minify url
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

  protected function _makeCleanUrl($url)
  {

    $url = str_replace($this->getStaticBaseUrl() . '/', '/', $url);
    $url = preg_replace("#\?.*$#", '', $url);
    return str_replace(array('\\'), '/', $url);
  }

  protected function _setCounterFromSrc($src)
  {
    if( $this->_counter != NULL ) {
      return $this;
    }
    preg_match("#\?c=(?P<counter>\d+)#", $src, $matches);
    if( empty($matches['counter']) ) {
      return $this;
    }

    return $this->setCounter($matches['counter']);
  }

  protected function _addMinifySrc($src)
  {
    $src = $this->_setCounterFromSrc($src)->_makeCleanUrl($src);
    if( !$src ) {
      return;
    }
    $this->_minifyScripts[$src] = $src;
  }

  protected function _canMinify($item)
  {
    if( empty($item->attributes['src']) ) {
      return false;
    }

    $src = $this->_makeCleanUrl($item->attributes['src']);
    $host = parse_url($src, PHP_URL_HOST);
    if( $host || !strpos($src, '.js') ) {
      return false;
    }
    $isValid = true;
    foreach( $this->_ignore as $content ) {
      $content = trim($content);
      if( strpos($src, $content) ) {
        $isValid = false;
        break;
      }
    }
    return $isValid;
  }

  protected function _processMinify(&$items)
  {
    $minifiyUrl = $this->getMinifiyUrl();
    $staticBaseURl = ltrim($this->getStaticBaseUrl(), '/');
    $baseUrl = ltrim($this->getBaseUrl(), '/');
    if( $baseUrl && $baseUrl === $staticBaseURl ) {
      $minifiyUrl .= 'b=' . str_replace('/index.php', '', $baseUrl) . '&';
    }
    while( count($this->_minifyScripts) ) {
      $minScript = new stdClass();
      $minScript->type = 'text/javascript';
      $scripts = array_splice($this->_minifyScripts, 0, $this->_perRequest);
      $minScript->attributes['src'] = $minifiyUrl . 'f=' . implode(',', $scripts) . '&v=' . $this->getCounter();
      $items [] = $this->itemToString($minScript, '', '', '');
    }
    return $items;
  }
}
