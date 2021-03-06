<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: HtmlImage.php 9747 2012-07-26 02:08:08Z john $
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Engine_View_Helper_HtmlImage extends Zend_View_Helper_HtmlElement
{
  public function htmlImage($src, $alt = "", $attribs = array())
  {
    // Allow passing an array
    if( is_array($src) )
    {
      $route = ( isset($src['route']) ? $src['route'] : 'default' );
      $reset = ( isset($src['reset']) ? $src['reset'] : false );
      unset($src['route']);
      unset($src['reset']);
      $src = $this->view->url($src, $route, $reset);
    }

    // Merge data and type
    $attribs = array_merge(array(
        'src' => $src,
        'alt' => $alt), $attribs);

    $closingBracket = $this->getClosingBracket();


    if (strpos($attribs['class'], 'item_photo_user') !== false) {
      return '<div class="item-photo-guidance position-relative"><div class="statistic '. $attribs['gear'] .' circle-badge position-absolute '.  $attribs['class']  . ' d-flex justify-content-center align-items-center text-white"> ' .  $attribs['count']  .'</div> <img'.$this->_htmlAttribs($attribs).$closingBracket . "</div>";
    } else {

      return '<img class="d-block"' . $this->_htmlAttribs($attribs) . $closingBracket;

    }


  }
}
