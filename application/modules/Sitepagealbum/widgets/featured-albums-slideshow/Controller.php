<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagealbum
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagealbum_Widget_FeaturedAlbumsSlideshowController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $category_id = $this->_getParam('category_id',0);
    // List of featured album
    $table = Engine_Api::_()->getItemTable('sitepage_album');
    $tableName = $table->info('name');
    $tablePage = Engine_Api::_()->getDbtable('pages', 'sitepage');
    $tablePageName = $tablePage->info('name');
    $select = $table->select()
                    ->setIntegrityCheck(false)
                    ->from($tableName)
                    ->joinLeft($tablePageName, "$tablePageName.page_id = $tableName.page_id", array('title AS page_title', 'photo_id as page_photo_id'))
                    ->where($tableName.".featured = ?", 1);
    $select = $select
              ->where($tablePageName . '.closed = ?', '0')
              ->where($tablePageName . '.approved = ?', '1')
              ->where($tablePageName . '.declined = ?', '0')
              ->where($tablePageName . '.draft = ?', '1');
    if(!empty($category_id)) {
      $select->where($tablePageName . '.	category_id =?', $category_id);
    }
    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $select->where($tablePageName . '.expiration_date  > ?', date("Y-m-d H:i:s"));
    }  

    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagealbum.hide.autogenerated', 1) ) {
			$select->where($tableName. '.default_value'.'= ?', 0);
			$select->where($tableName . ".type is Null");
    } 

    $limit = $this->_getParam('itemCountPerPage', 10);
    $select->limit($limit);
    $this->view->show_slideshow_object = $this->view->featuredAlbums = $featuredAlbums = $table->fetchAll($select);
    // Count Featured Albums
    $this->view->num_of_slideshow = count($featuredAlbums);
    // Number of the result.
    if (empty($this->view->num_of_slideshow)) {
      return $this->setNoRender();
    }
  }

}
?>