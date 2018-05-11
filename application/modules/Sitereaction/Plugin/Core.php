<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereaction_Plugin_Core extends Zend_Controller_Plugin_Abstract {

    public function onRenderLayoutDefault($event) {
        $view = $event->getPayload();
        $settings = Engine_Api::_()->getApi('settings', 'core');
        
      if ($settings->getSetting('sitereaction.collection.active', 1) && Engine_Api::_()->hasModuleBootstrap('nestedcomment')) {
            $view->headTranslate(array(
                'Post a sticker',
            ));
            $view->headScript()->appendFile($view->layout()->staticBaseUrl . 'application/modules/Sitereaction/externals/scripts/composer_nested_comment_activity_sticker.js');
            $view->headScript()->appendFile($view->layout()->staticBaseUrl . 'application/modules/Sitereaction/externals/scripts/composer_nested_comment_sticker.js');
        }
        if ($settings->getSetting('sitereaction.reaction.active', 1)) {
            $view->headScript()->appendFile($view->layout()->staticBaseUrl . 'application/modules/Sitereaction/externals/scripts/core.js');
            $view->headLink()->appendStylesheet($view->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/style_icon_toolbar.css');
        }
    }

    public function onRenderLayoutMobileDefault($event) {
        return $this->onRenderLayoutDefault($event);
    }

    public function onRenderLayoutDefaultSimple($event) {
        return $this->onRenderLayoutDefault($event);
    }

    public function onCoreLikeDeleteBefore($event) {
        $item = $event->getPayload();
        Engine_Api::_()->getDbtable('actions', 'activity')->delete(array(
            'subject_type = ?' => $item->poster_type,
            'subject_id = ?' => $item->poster_id,
            'object_type = ?' => $item->resource_type,
            'object_id = ?' => $item->resource_id,
            'type = ?' => 'react'
        ));
    }

    public function onActivityLikeDeleteBefore($event) {
        $item = $event->getPayload();
        Engine_Api::_()->getDbtable('actions', 'activity')->delete(array(
            'subject_type = ?' => $item->poster_type,
            'subject_id = ?' => $item->poster_id,
            'object_type = ?' => 'activity_action',
            'object_id = ?' => $item->resource_id,
            'type = ?' => 'react'
        ));
    }

}
