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
class Sitereaction_StoreController extends Siteapi_Controller_Action_Standard {

    public function init() {
        if (!$this->_helper->requireUser()->isValid())
            $this->respondWithError('unauthorized');
    }

    public function listAction() {
        $viewer = Engine_Api::_()->user()->getViewer();
        $params['limit'] = 50;
        // Get stickers of a collection
        $collection_id = $this->_getParam('collection_id');
        try {
            $table = Engine_Api::_()->getDbtable('collections', 'sitereaction');
            $paginator = $table->getStoreCollection($params);

            foreach ($paginator as $item) {
                //To List of stickers of a particular collection only
                if (isset($collection_id) && !empty($collection_id) && $collection_id != $item->collection_id)
                    continue;

                $collection = $item->toArray();
                $collection['sticker_count'] = $item->count();

                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($item);
                $collection = array_merge($collection, $getContentImages);

                if (isset($collection_id) && !empty($collection_id))
                    $collection['stickers'] = $this->_getStickers($item);
                $menus = $this->_getCollectionMenu($item);
                $collection['isAdded'] = 0;
                if (isset($menus['isAdded']) && !empty($menus['isAdded'])) {
                    $collection['isAdded'] = 1;
                    unset($menus['isAdded']);
                }
                $collection['menu'] = $menus;
                $collectionArray[] = $collection;
            }
            if (isset($collectionArray) && !empty($collectionArray))
                $response['response'] = $collectionArray;
            $this->respondWithSuccess($response, true);
        } catch (Exception $ex) {
            
        }
    }

    public function addAction() {
        $this->validateRequestMethod('POST');

        $collection_id = $this->_getParam('collection_id');
        if (!isset($collection_id) || empty($collection_id))
            $this->respondWithError('no_record');

        $viewer = Engine_Api::_()->user()->getViewer();
        $table = Engine_Api::_()->getDbtable('userscollections', 'sitereaction');
        $db = $table->getAdapter();
        $db->beginTransaction();

        try {
            $table->add($viewer->getIdentity(), $collection_id);
            $db->commit();
            $this->successResponseNoContent('no_content', true);
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
    }

    public function removeAction() {
        $this->validateRequestMethod('POST');

        $collection_id = $this->_getParam('collection_id');
        if (!isset($collection_id) || empty($collection_id))
            $this->respondWithError('no_record');
        $viewer = Engine_Api::_()->user()->getViewer();
        $table = Engine_Api::_()->getDbtable('userscollections', 'sitereaction');
        $db = $table->getAdapter();
        $db->beginTransaction();
        try {
            $table->remove($viewer->getIdentity(), $collection_id);
            $db->commit();
            $this->successResponseNoContent('no_content', true);
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
    }

    private function _getStickers($item) {
        foreach ($item->getStickers() as $sticker) {
            $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($sticker);
            $stickers[] = $getContentImages;
        }
        return $stickers;
    }

    private function _getCollectionMenu($item) {
        $viewer = Engine_Api::_()->user()->getViewer();
        $collection_ids = Engine_Api::_()->getDbtable('userscollections', 'sitereaction')->getCollectinIds($viewer->getIdentity());
        $tempMenu = array();
        if (!$item->include) {
            $tempMenu[] = array(
                'label' => $this->translate('Preview'),
                'name' => 'preview',
                'url' => 'reactions/store/index',
                'urlParams' => array(
                    'collection_id' => $item->getIdentity()
                )
            );
            if (in_array($item->getIdentity(), $collection_ids)) {
                $tempMenu['isAdded'] = 1;
                $tempMenu[] = array(
                    'label' => $this->translate('Remove'),
                    'name' => 'remove',
                    'url' => 'reactions/store/remove',
                    'urlParams' => array(
                        'collection_id' => $item->getIdentity()
                    )
                );
            } else {
                $tempMenu[] = array(
                    'label' => $this->translate('Add'),
                    'name' => 'add',
                    'url' => 'reactions/store/add',
                    'urlParams' => array(
                        'collection_id' => $item->getIdentity()
                    )
                );
            }
        }
        return $tempMenu;
    }

}
