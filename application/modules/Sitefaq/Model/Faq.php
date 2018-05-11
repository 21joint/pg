<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sitefaq.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitefaq_Model_Faq extends Core_Model_Item_Abstract {

  protected $_searchTriggers = false;
  protected $_parent_type = 'user';

  public function getMediaType() {
    return 'faq';
  }

  /**
   * This function will execute in every call of FAQ object model
   *
   * */
  public function init() {
    //MAKE ENTRY IN CORE SEARCH TABLE IF ALLOWED BY ADMIN
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.search', 1)) {
      $this->_searchTriggers = array('title', 'body', 'search', 'draft', 'approved');
    }
  }

  public function isSearchable() {

    return ( (!isset($this->search) || $this->search) && !empty($this->_searchTriggers) && is_array($this->_searchTriggers) && $this->approved && empty($this->draft));
  }

  /**
   * Return user object
   *
   * @return user object
   * */
  public function getParent($recurseType = 'user') {
    //RETURN THE PARENT
    return Engine_Api::_()->getItem($recurseType, $this->owner_id);
  }

  /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
   */
  public function getHref($params = array()) {

    //GET CATEGORY ID
    $first_category_id_array = Zend_Json_Decoder::decode($this->category_id);
    $first_category_id = $first_category_id_array[0];

    //GET SUB-CATEGORY ID
    $first_subcategory_id_array = Zend_Json_Decoder::decode($this->subcategory_id);
    $first_subcategory_id = $first_subcategory_id_array[0];

    //GET 3RD LEVEL CATEGORY ID
    $first_subsubcategory_id_array = Zend_Json_Decoder::decode($this->subsubcategory_id);
    $first_subsubcategory_id = $first_subsubcategory_id_array[0];

    $params = array_merge(array(
        'route' => 'sitefaq_view',
        'reset' => true,
        'faq_id' => $this->faq_id,
        'category_id' => $first_category_id,
        'subcategory_id' => $first_subcategory_id,
        'subsubcategory_id' => $first_subsubcategory_id,
        'slug' => $this->getSlug(),
            ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
  }

  /**
   * Gets a url slug for this item, based on it's title
   *
   * @return string The slug
   */
  public function getSlug($str = null, $limit = 64) {
    if (null === $str) {
      $str = $this->getTitle();
    }
    if (strlen($str) > $limit) {
      $str = Engine_String::substr($str, 0, $limit) . '...';
    }

    return Engine_Api::_()->seaocore()->getSlug($str, $limit);
  }

  /**
   * Return keywords
   *
   * @param char separator 
   * @return keywords
   * */
  public function getKeywords($separator = ' ') {
    $keywords = array();
    foreach ($this->tags()->getTagMaps() as $tagmap) {
      $tag = $tagmap->getTag();
      $keywords[] = $tag->getTitle();
    }

    if (null === $separator) {
      return $keywords;
    }

    return join($separator, $keywords);
  }

  /**
   * Convert title text in to current selected language
   *
   */
  public function getTitle() {

    $title = Engine_Api::_()->sitefaq()->getLanguageColumn('title');

    if (empty($this->$title)) {
      return $this->title;
    }

    //RETURN VALUE
    return $this->$title;
  }

  /**
   * Convert description text in to current selected language
   *
   */
  public function getDescription() {

    //RETURN VALUE
    $body = $this->getFullDescription();
    $tmpBody = strip_tags($body);
    return ( Engine_String::strlen($tmpBody) > 255 ? Engine_String::substr($tmpBody, 0, 252) . '...' : $tmpBody );
  }

  /**
   * Convert description text in to current selected language
   *
   */
  public function getFullDescription() {

    $body = Engine_Api::_()->sitefaq()->getLanguageColumn('body');

    if (empty($this->$body)) {
      return $this->body;
    }

    //RETURN VALUE
    return $this->$body;
  }

  /**
   * Delete the sitefaq and belongings
   * 
   */
  public function _delete() {

    //DELETE ALL MAPPING VALUES FROM FIELD TABLES
    Engine_Api::_()->fields()->getTable('sitefaq_faq', 'values')->delete(array('item_id = ?' => $this->faq_id));
    Engine_Api::_()->fields()->getTable('sitefaq_faq', 'search')->delete(array('item_id = ?' => $this->faq_id));

    //DELETE RATING VALUES
    Engine_Api::_()->getDbtable('ratings', 'sitefaq')->delete(array('faq_id = ?' => $this->faq_id));

    //DELETE HELPFUL VALUES
    Engine_Api::_()->getDbtable('helps', 'sitefaq')->delete(array('faq_id = ?' => $this->faq_id));

    //DELETE FAQ
    parent::_delete();
  }

  /**
   * Gets a proxy object for the fields handler
   *
   * @return Engine_ProxyObject
   */
  public function fields() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getApi('core', 'fields'));
  }

  /**
   * Gets a proxy object for the comment handler
   *
   * @return Engine_ProxyObject
   * */
  public function comments() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
  }

  /**
   * Gets a proxy object for the like handler
   *
   * @return Engine_ProxyObject
   * */
  public function likes() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
  }

  /**
   * Gets a proxy object for the tags handler
   *
   * @return Engine_ProxyObject
   * */
  public function tags() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('tags', 'core'));
  }

}