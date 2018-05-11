<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Review.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Model_Review extends Core_Model_Item_Abstract {

  /**
   * Return page object
   *
   * @return page object
   * */
  public function getParent($recurseType = null) {
    
    if($recurseType == null) $recurseType = 'sitepage_page';
    
    return Engine_Api::_()->getItem($recurseType, $this->page_id);
  }
  
	public function getMediaType() {
		return 'review';
	}
	
  /**
   * Gets an absolute URL to the page to view this item
   *
	 * @params array $params
   * @return string
   */
  public function getHref($params = array()) {
    if (Zend_Controller_Front::getInstance()->getRequest()->getParam('review_id')) {
      $review_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('review_id');
      $table = Engine_Api::_()->getDbtable('reviews', 'sitepagereview');
      $select = $table->select()
                      ->where('review_id = ?', $review_id)
                      ->limit(1);

      $row = $table->fetchRow($select);
      if ($row !== null) {
        $pageid = $row->page_id;
      }
    } else {
      $pageid = $this->page_id;
    }
    $slug = trim(preg_replace('/-+/', '-', preg_replace('/[^a-z0-9-]+/i', '-', strtolower($this->getTitle()))), '-');
    $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
    $tab_id = '';
		if(!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
			$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.sitemobile-profile-sitepagereviews', $pageid, $layout);
		} else {
			$tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepagereview.profile-sitepagereviews', $pageid, $layout);
		}
    $params = array_merge(array(
                'route' => 'sitepagereview_detail_view',
                'reset' => true,
                'owner_id' => $this->owner_id,
                'review_id' => $this->review_id,
                'slug' => $slug,
                'tab' => $tab_id
                    ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
            ->assemble($params, $route, $reset);
  }

  /**
   * Make format for activity feed
   *
   * @return activity feed content
   */
  public function getRichContent() {
    $view = Zend_Registry::get('Zend_View');
    $view = clone $view;
    $view->clearVars();
    $view->addScriptPath('application/modules/Sitepagereview/views/scripts/');
    $review_description = Engine_Api::_()->sitepagereview()->truncateText($this->body, 250);
    $review_title = Engine_Api::_()->sitepagereview()->truncateText($this->title, 100);

		//GET RATING
    $view->rating = Engine_Api::_()->getDbtable('ratings', 'sitepagereview')->getRating($this->page_id, $this->review_id);

    $content = '';
    $content .= '
      <div class="feed_sitepagereview_rich_content">
        <div class="feed_item_link_title" style="float:left;line-height: 21px;margin-right:10px;">
          ' . $view->htmlLink($this->getHref(), $review_title) . '
        </div>' . $view->render('_sitepagereview.tpl') . '
        <br/><div class="feed_item_link_desc">
          ' . $view->viewMore($review_description) . '
        </div>
    ';

    $content .= '
      </div>
    ';
    return $content;
  }

  /**
   * Truncation of page description
   *
   * @return truncate description
   */
  public function getDescription() {

    return Engine_Api::_()->sitepagereview()->truncateText($this->body, 255);;
  }

  /**
   * Delete create activity feed of review before delete review 
   *
   */
  protected function _delete() {
    
    Engine_Api::_()->getApi('subCore', 'sitepage')->deleteCreateActivityOfExtensionsItem($this, array('sitepagereview_new'));
    parent::_delete();
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

}
?>