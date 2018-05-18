<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Core.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_Plugin_Core extends Zend_Controller_Plugin_Abstract {

    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        // CHECK IF ADMIN
        if (substr($request->getPathInfo(), 1, 5) == "admin") {
            return;
        }

        $query = $request->getParam("query");
        $type = $request->getParam("type");
        if (empty($query) || strlen($query) < 2 || strpos($query, '#') !== 0) {
            return;
        }
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if ($action == 'index' && (($module == "core" && $controller == 'search' && !$type) || ($module == "siteadvsearch" && $controller == 'index' && (!$type || $type == 'all')))) {
            $request->setModuleName('sitehashtag');
            $request->setControllerName('index');
            $request->setParam("search", $query);
            $request->setParam("module", 'sitehashtag');
            $request->setParam("controller", 'index');
        }
    }

    public function onActivityActionCreateAfter($event) {

       
        $payload = $event->getPayload();
        if ($payload->body) {
            $contentTable = Engine_Api::_()->getDbtable('contents', 'sitehashtag');
            if ($contentTable->getEnable(strtolower($payload->getModuleName()))) {
                $hashtags = Engine_Api::_()->sitehashtag()->getHashTags($payload->body);
                $hashtagMap = Engine_Api::_()->getDbtable('tags', 'sitehashtag');
                $hashtagMap->addHashTagMaps($payload->getIdentity(), $hashtags);
            }
        }
    }

    public function onActivityActionDeleteBefore($event) {
        $forumGlobalView = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.global.view', 0);
        $forumLSettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.lsettings', 0);
        $forumInfoType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.info.type', 0);
        $forumGlobalType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.global.type', 0);
        $payload = $event->getPayload();
        $tempSitemenuLtype=0;
        if (empty($forumGlobalType)) {
            for ($check = 0; $check < strlen($forumLSettings); $check++) {
                $tempSitemenuLtype += @ord($forumLSettings[$check]);
            }
            $tempSitemenuLtype = $tempSitemenuLtype + $forumGlobalView;
        }
        
        if(!empty($tempSitemenuLtype) && !empty($forumInfoType) && ($tempSitemenuLtype != $forumInfoType))
            Engine_Api::_()->getApi('settings', 'core')->setSetting('sitehashtag.viewtypeinfo.settings', 0);
        $hashtags = Engine_Api::_()->sitehashtag()->getHashTags($payload->body);
        $hashtagMap = Engine_Api::_()->getDbtable('tags', 'sitehashtag');
        $hashtagMap->deleteHashTagMaps($payload->getIdentity(), $hashtags);
    }

    public function onItemDeleteBefore($event) {

        $payload = $event->getPayload();
        $ids = array();
        $actionsTable = Engine_Api::_()->getDbtable('actions', 'activity');
        $actionName = $actionsTable->info('name');

        $db = $actionsTable->getAdapter();
        $union = new Zend_Db_Select($db);

        $select = $actionsTable->select();

        $selectSubject = clone $select;

        $selectSubject
                ->where($actionName . '.subject_type = ?', $payload->getType())
                ->where($actionName . '.subject_id = ?', $payload->getIdentity());
        $union->union(array('(' . $selectSubject->__toString() . ')'));

        $selectObject = clone $select;

        $selectObject
                ->where($actionName . '.object_type = ?', $payload->getType())
                ->where($actionName . '.object_id = ?', $payload->getIdentity());
        $union->union(array('(' . $selectObject->__toString() . ')'));
        $actions = $db->fetchAll($union);

        if (empty($actions)) {
            $union = '';
            return;
        }

        foreach ($actions as $data) {
            $ids[] = $data['action_id'];
        }

        $hashtagMapTable = Engine_Api::_()->getDbtable('tagmaps', 'sitehashtag');
        $hashtagMapTable->delete(array('action_id IN (?)' => $ids));
    }

    public function onActivityActionUpdateAfter($event) {
        $payload = $event->getPayload();
        if (!method_exists($payload, 'getModifiedFieldsName')) {
            return;
        }

        if (in_array('body', $payload->getModifiedFieldsName())) {
            $this->onUpdateActivityActionBody($payload);
        }

        if (!in_array('attachment_count', $payload->getModifiedFieldsName()) || $payload->attachment_count != 1) {
            return;
        }

        $attachment = $payload->getFirstAttachment();
        if (empty($attachment) || !($attachment->item instanceof Core_Model_Item_Abstract)) {
            return;
        }
        $tempSitemenuLtype=0;
        $forumGlobalView = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.global.view', 0);
        $forumLSettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.lsettings', 0);
        $forumInfoType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.info.type', 0);
        $forumGlobalType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.global.type', 0);
        if (empty($forumGlobalType)) {
            for ($check = 0; $check < strlen($forumLSettings); $check++) {
                $tempSitemenuLtype += @ord($forumLSettings[$check]);
            }
            $tempSitemenuLtype = $tempSitemenuLtype + $forumGlobalView;
        }
        
        if(!empty($tempSitemenuLtype) && !empty($forumInfoType) && ($tempSitemenuLtype != $forumInfoType))
            Engine_Api::_()->getApi('settings', 'core')->setSetting('sitehashtag.viewtypeinfo.type', 0);

        $object = $attachment->item;
        $actionObject = $payload;

        if ($object instanceof Activity_Model_Action) {
            $content = $object->body;
        } else {
            $content = $object->getTitle();
            $separator = ', #';
            $keywordSeprator = 'HASHTAG_SEPARATOR';
            $keyword = $object->getKeywords($keywordSeprator);
            if (!empty($keyword)) {
              $keyword = str_replace(' ', '_', $keyword);
              $keyword = str_replace($keywordSeprator, $separator, $keyword);
              $content .= $separator . $keyword;
            }
        }
        $hashtags = Engine_Api::_()->sitehashtag()->getHashTags($content);
        if (empty($hashtags) || empty($hashtags[0])) {
            return;
        }
        $contentTable = Engine_Api::_()->getDbtable('contents', 'sitehashtag');

        $action_id = $actionObject->getIdentity();
        //check if enabled by admin
        if ($contentTable->getEnable($object->getModuleName())) {
            $hashtagMap = Engine_Api::_()->getDbtable('tags', 'sitehashtag');
            $hashtagMap->addHashTagMaps($action_id, $hashtags);
        }
    }

    public function onItemUpdateAfter($event) {
        $item = $event->getPayload();
        if ($item instanceof Activity_Model_Action || !method_exists($item, 'getModifiedFieldsName')) {
            return;
        }
        if (!in_array('title', $item->getModifiedFieldsName())) {
            return;
        }
        $content = $item->getTitle();
        $separator = ', #';
        $keywordSeprator = 'HASHTAG_SEPARATOR';
        $keyword = $item->getKeywords($keywordSeprator);
        if (!empty($keyword)) {
          $keyword = str_replace(' ', '_', $keyword);
          $keyword = str_replace($keywordSeprator, $separator, $keyword);
          $content .= $separator . $keyword;
        }
        $hashtags = Engine_Api::_()->sitehashtag()->getHashTags($content);
        $cleanData = $item->getCleanData();
        $oldTitle = $cleanData['title'];
        $oldHashtags = Engine_Api::_()->sitehashtag()->getHashTags($oldTitle);
        if (empty($hashtags[0]) && empty($oldHashtags[0])) {
            return;
        }

        $moduleName = $item->getModuleName();
        $contentTable = Engine_Api::_()->getDbtable('contents', 'sitehashtag');
        if (!$contentTable->getEnable($moduleName)) {
            return;
        }

        $actionsIds = array();
        $table = Engine_Api::_()->getDbtable('attachments', 'activity');
        $select = $table->select()
                ->where('type = ? ', $item->getType())
                ->where('id = ? ', $item->getIdentity());
        $attachments = $table->fetchAll($select);
        $hashtags = !empty($hashtags[0]) ? $hashtags[0] : array();
        $actionHashTag = array();
        foreach ($attachments as $attachment) {
            $action = Engine_Api::_()->getItem('activity_action', $attachment->action_id);
            if(!$action){
                continue;
            }
            if ($action->body) {
                $actionHashtags = Engine_Api::_()->sitehashtag()->getHashTags($action->body);
                if (!empty($actionHashtags[0])) {
                    $actionHashTag[$attachment->action_id] = $actionHashtags[0];
                }
            }
            $actionsIds[] = $attachment->action_id;
        }
        $tagMap = Engine_Api::_()->getDbtable('tags', 'sitehashtag');
        foreach (array_unique($actionsIds) as $action_id) {
            $tags = !empty($actionHashTag[$action_id]) ? array_merge($actionHashTag[$action_id], $hashtags) : $hashtags;
            $tagMap->editHashTagMaps($action_id, array($tags));
        }
    }

    private function onUpdateActivityActionBody($action) {
        $contentTable = Engine_Api::_()->getDbtable('contents', 'sitehashtag');
        if (!$contentTable->getEnable($action->getModuleName())) {
            return;
        }
        $tempSitemenuLtype=0;
        $forumGlobalView = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.global.view', 0);
        $forumLSettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.lsettings', 0);
        $forumInfoType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.info.type', 0);
        $forumGlobalType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitehashtag.global.type', 0);
        
        
        if (empty($forumGlobalType)) {
            for ($check = 0; $check < strlen($forumLSettings); $check++) {
                $tempSitemenuLtype += @ord($forumLSettings[$check]);
            }
            $tempSitemenuLtype = $tempSitemenuLtype + $forumGlobalView;
        }
        
        if(!empty($tempSitemenuLtype) && !empty($forumInfoType) && ($tempSitemenuLtype != $forumInfoType))
            Engine_Api::_()->getApi('settings', 'core')->setSetting('sitehashtag.viewtypeinfo.type', 0);

        $hashtags = Engine_Api::_()->sitehashtag()->getHashTags($action->body);
        $hashtags = !empty($hashtags[0]) ? $hashtags[0] : array();
        $cleanData = $action->getCleanData();
        $oldBody = $cleanData['body'];
        $deleteHashTag = array();
        $oldHashtags = Engine_Api::_()->sitehashtag()->getHashTags($oldBody);
        $hashtagMap = Engine_Api::_()->getDbtable('tags', 'sitehashtag');
        if (!empty($oldHashtags[0])) {
            $deleteHashTag = array_diff($oldHashtags[0], $hashtags);
            $hashtags = array_diff($hashtags, $oldHashtags[0]);
        }
        $action_id = $action->getIdentity();
        $hashtagMap->deleteHashTagMaps($action_id, array($deleteHashTag));
        $hashtagMap->addHashTagMaps($action_id, array($hashtags));
    }

}
