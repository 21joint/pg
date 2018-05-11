<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: StickerCollections.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_View_Helper_StickerCollections extends Zend_View_Helper_Abstract {
  protected $_collections;

  protected $_storeItem;

  protected $_searchList;

  public function stickerCollections() {
    
    if($this->_collections) {
        return $this;
    }

    $table = Engine_Api::_()->getDbtable('collections', 'sitereaction');
    $viewer = Engine_Api::_()->user()->getViewer();
    $colleaction_ids = Engine_Api::_()->getDbtable('userscollections', 'sitereaction')->getCollectinIds($viewer->getIdentity());
    $colleaction_ids = array_merge($colleaction_ids, $table->getCollectinIds($viewer->getIdentity()));
    $this->_collections = $table->getCollectinos($colleaction_ids);
    $this->_searchList = Engine_Api::_()->getDbtable('stickersearch', 'sitereaction')->getList();
    $this->_storeItem = $table->getStoreCollection(); 
    return $this;
  }

  public function render() {
    
    $isMobileMode = Engine_Api::_()->seaocore()->isSiteMobileModeEnabled();
    $filePrefix = $isMobileMode  ? 'application/modules/Sitereaction/views/sitemobile/scripts/' : '';
    return $this->view->partial($filePrefix.'_stickerCollections.tpl', 'sitereaction', array(
        'collections' => $this->_collections,
        'searchList' => $this->_searchList,
        'hasStore' => $this->_storeItem->getTotalItemCount()
    ));
  }

}
