<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */

class Pgservicelayer_CommentsController extends Pgservicelayer_Controller_Action_Api
{
    public function init(){
        $timezone = Engine_Api::_()->getApi('settings', 'core')->core_locale_timezone;
        $viewer   = Engine_Api::_()->user()->getViewer();
        $defaultLocale = $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en_US');
        $defaultLocaleObj = new Zend_Locale($defaultLocale);
        Zend_Registry::set('LocaleDefault', $defaultLocaleObj);

        if ($viewer->getIdentity()) {
            $timezone = $viewer->timezone;
        }
        Zend_Registry::set('timezone', $timezone);
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
        Engine_Api::_()->getApi('Core', 'siteapi')->setTranslate();
        Engine_Api::_()->getApi('Core', 'siteapi')->setLocal();
        
        $resourceType = $this->getParam("resourceType");
        $resourceId = $this->getParam("resourceID");
        if(empty($resourceType) || empty($resourceId)){
            $this->respondWithError('no_record');
        }
        
        if(!Engine_Api::_()->hasItemType($resourceType)){
            $this->respondWithError('no_record');
        }
        
        $subject = Engine_Api::_()->getItem($resourceType,$resourceId);
        if(empty($subject) || !$subject->getIdentity()){
            $this->respondWithError('no_record');
        }
        
        if(!Engine_Api::_()->core()->hasSubject()){
            Engine_Api::_()->core()->setSubject($subject);
        }
        
    }
    public function getAction(){
        $subject = null;
        if(Engine_Api::_()->core()->hasSubject()){
            $subject = Engine_Api::_()->core()->getSubject();
        }
        if (!($subject instanceof Core_Model_Item_Abstract) ||
                !$subject->getIdentity() ||
                (!method_exists($subject, 'comments') && !method_exists($subject, 'likes')))
            $this->respondWithError('no_record');
        $viewer = Engine_Api::_()->user()->getViewer();
        $canComment = $subject->authorization()->isAllowed($viewer, 'comment');
        $canDelete = $subject->authorization()->isAllowed($viewer, 'edit');
        if (!$canComment && !$canDelete)
                $this->respondWithError('unauthorized');
        
        if (strpos($subject->getType(), "sitegroup") !== false) {
            if ($subject->getType() == 'sitegroup_group') {
                $groupSubject = $subject;
            } elseif ($subject->getType() == 'sitegroupmusic_playlist') {
                $groupSubject = $subject->getParentType();
            } elseif ($subject->getType() == 'sitegroupnote_photo') {
                $groupSubject = $subject->getParent()->getParent()->getParent();
            } elseif ($subject->getType() == 'sitegroupevent_photo') {
                $groupSubject = $subject->getEvent()->getParentPage();
            } else {
                $groupSubject = $subject->getParent();
            }
            $groupApi = Engine_Api::_()->sitegroup();
            $canComment = $groupApi->isManageAdmin($groupSubject, 'comment');
            $canDelete = $groupApi->isManageAdmin($groupSubject, 'edit');
        }
        
        $page = $this->getParam("page",1);
        $limit = $this->getParam("limit",10);
        if (null !== $page) {
            $commentSelect = $subject->comments()->getCommentSelect();
            $commentSelect->order('comment_id ' . $this->getRequestParam('order', 'ASC'));
            $comments = Zend_Paginator::factory($commentSelect);
            $comments->setCurrentPageNumber($page);
            $comments->setItemCountPerPage($limit);
        } else {
            // If not has a page, show the
            $commentSelect = $subject->comments()->getCommentSelect();
            $commentSelect->order('comment_id DESC');
            $comments = Zend_Paginator::factory($commentSelect);
            $comments->setCurrentPageNumber(1);
            $comments->setItemCountPerPage(4);
        }
        
        if (!$canComment && !$canDelete)
                $this->respondWithError('unauthorized');
    }
}
