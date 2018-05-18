<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: StoreController.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_StoreController extends Core_Controller_Action_Standard
{

  public function init()
  {
    if (!$this->_helper->requireUser()->isValid())
      return;
  }

  public function listAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $params['limit'] = 50;
    $table = Engine_Api::_()->getDbtable('collections', 'sitereaction');
    $this->view->paginator = $table->getStoreCollection($params);
    $this->view->collectionIds = $colleaction_ids = Engine_Api::_()->getDbtable('userscollections', 'sitereaction')->getCollectinIds($viewer->getIdentity());
  }

  public function indexAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->page = $params['page'] = $this->_getParam('page', 1);
    $params['limit'] = 1;
    $table = Engine_Api::_()->getDbtable('collections', 'sitereaction');
    $this->view->paginator = $table->getStoreCollection($params);

    $table = Engine_Api::_()->getDbtable('userscollections', 'sitereaction');

    foreach ($this->view->paginator as $item) {
      $this->view->isAdded = $table->fetchUserCollection($viewer->getIdentity(), $item->getIdentity());
    }
  }

  public function addAction()
  {
    $collection_id = $this->_getParam('collection_id');
    if (!$this->getRequest()->isPost() || !$collection_id) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid request method");
      ;
      return;
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $table = Engine_Api::_()->getDbtable('userscollections', 'sitereaction');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try {

      $table->add($viewer->getIdentity(), $collection_id);
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->view->status = true;
    $this->view->message = 'Stickers add in your list';
    $this->view->collectionBody = $this->view->stickerCollections()->render();
    $this->view->body = $this->view->action('index', 'store', 'sitereaction', array(
        'page' => $this->_getParam('page'),
        'format' => 'html',
    ));
    $this->_helper->contextSwitch->initContext();
  }

  public function removeAction()
  {
    $collection_id = $this->_getParam('collection_id');
    if (!$this->getRequest()->isPost() || !$collection_id) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Invalid request method");
      ;
      return;
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $table = Engine_Api::_()->getDbtable('userscollections', 'sitereaction');
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      $table->remove($viewer->getIdentity(), $collection_id);
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->view->status = true;
    $this->view->message = 'Stickers remove from your list';
    $this->view->collectionBody = $this->view->stickerCollections()->render();
    $this->view->body = $this->view->action('index', 'store', 'sitereaction', array(
        'page' => $this->_getParam('page'),
        'format' => 'html',
    ));
    $this->_helper->contextSwitch->initContext();
  }

}
