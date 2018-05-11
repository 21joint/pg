<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegifplayer
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: ItemBackgroundPhoto.php 2017-05-15 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegifplayer_View_Helper_ItemBackgroundPhoto extends Sitegifplayer_View_Helper_ItemPhoto
{

  protected $_classPrefix = 'bg_';
  protected $_backgroundClass = 'bg_item_photo';

  public function ItemBackgroundPhoto($item, $type = 'thumb.profile', $alt = "", $attribs = array())
  {
    $tag = 'span';
    if( !empty($attribs['tag']) ) {
      $tag = $attribs['tag'];
      unset($attribs['tag']);
    }

    $this->setAttributes($item, $type, $attribs);
    if( $alt && empty($this->_attribs['title']) ) {
      $this->_attribs['title'] = $alt;
    }

    if( !empty($this->_attribs['style']) && is_string($this->_attribs['style']) ) {
      $this->_attribs['style'][] = $this->_attribs['style'];
    }

    $this->_attribs['style'][] = 'background-image:url("' . $this->_url . '");';

    $this->_attribs['class'] = $this->_backgroundClass . ' ' . $this->_attribs['class'];
    return '<' . $tag
      . $this->_htmlAttribs($this->_attribs)
      . '>'
      . '</'
      . $tag
      . '>';
  }

}
