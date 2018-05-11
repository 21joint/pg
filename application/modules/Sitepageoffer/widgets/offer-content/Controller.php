<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Widget_OfferContentController extends Seaocore_Content_Widget_Abstract {

    public function indexAction() {

        //GET OFFER ID AND OBJECT
        $this->view->offer_id = $offer_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('offer_id', $this->_getParam('offer_id', null));

        $this->view->share_offer = Zend_Controller_Front::getInstance()->getRequest()->getParam('share_offer', $this->_getParam('share_offer', null));

        $this->view->showLinks = $this->_getParam('showLinks', array('add', 'edit', 'delete', 'featured', 'dayOffer', 'print', 'share', 'report'));
        $this->view->showContent = $this->_getParam('showContent', array('postedBy', 'viewCount', 'likeCount', 'commentCount'));
        $this->view->commentEnabled = $this->_getParam('commentEnabled', 1);

        $sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $offer_id);

        if (empty($sitepageoffer)) {
            return $this->setNoRender();
        }

        $sitepage = Engine_Api::_()->getItem('sitepage_page', $sitepageoffer->page_id);

        //GET TAB ID
        $this->view->tab_selected_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('tab');

        $getPackageofferView = Engine_Api::_()->sitepage()->getPackageAuthInfo('sitepageoffer');

        //GET VIEWER INFO
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
        $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

        $this->view->allowView = false;
        if (!empty($viewer_id) && $viewer->level_id == 1) {
            $auth = Engine_Api::_()->authorization()->context;
            $this->view->allowView = $auth->isAllowed($sitepage, 'everyone', 'view') === 1 ? true : false || $auth->isAllowed($sitepage, 'registered', 'view') === 1 ? true : false;
        }

        //IF THIS IS SENDING A MESSAGE ID, THE USER IS BEING DIRECTED FROM A CONVERSATION
        //CHECK IF MEMBER IS PART OF THE CONVERSATION
        $message_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('message');
        $message_view = false;
        if ($message_id) {
            $conversation = Engine_Api::_()->getItem('messages_conversation', $message_id);
            if ($conversation->hasRecipient(Engine_Api::_()->user()->getViewer()))
                $message_view = true;
        }
        $this->view->message_view = $message_view;

        //SET SITEPAGE SUBJECT
        $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $sitepageoffer->page_id);

        //PACKAGE BASE PRIYACY START
//     if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
//       if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage->package_id, "modules", "sitepageoffer")) {
//         return $this->setNoRender();
//       }
//     } else {
//       $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage, 'svcreate');
//       if (empty($isPageOwnerAllow)) {
//         return $this->setNoRender();
//       }
//     }
        //PACKAGE BASE PRIYACY END
        //START MANAGE-ADMIN CHECK
        $can_offer = 1;
        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'offer');
        if (empty($isManageAdmin)) {
            $can_offer = 0;
        }

        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
        if (empty($isManageAdmin)) {
            $can_edit = $this->view->can_edit = 0;
        } else {
            $can_edit = $this->view->can_edit = 1;
        }

        $can_create_offer = '';
        //OFFER CREATION AUTHENTICATION CHECK
        if ($can_edit == 1 && $can_offer == 1) {
            $this->view->can_create_offer = $can_create_offer = 1;
        }

        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
        if (empty($isManageAdmin)) {
            return $this->setNoRender();
        }

        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'comment');
        if (empty($isManageAdmin)) {
            $this->view->can_comment = 0;
        } else {
            $this->view->can_comment = 1;
        }


//     if ($viewer_id != $sitepageoffer->owner_id && $can_edit != 1 && ($sitepageoffer->status != 1 || $sitepageoffer->search != 1) || empty($getPackageofferView)) {
//       return $this->setNoRender();
//     }
        //END MANAGE-ADMIN CHECK
        //INCREMENT IN NUMBER OF VIEWS
        $owner = $sitepageoffer->getOwner();
        if (!$owner->isSelf($viewer)) {
            $sitepageoffer->view_count++;
        }
        $sitepageoffer->save();

        //SET PAGE-OFFER SUBJECT
        if (Engine_Api::_()->core()->hasSubject()) {
            Engine_Api::_()->core()->clearSubject();
        }
        Engine_Api::_()->core()->setSubject($sitepageoffer);

        $this->view->offer = empty($getPackageofferView) ? null : $sitepageoffer;

        //OFFER TABLE
        $offerTable = Engine_Api::_()->getDbtable('offers', 'sitepageoffer');

        //TOTAL OFFER COUNT FOR THIS PAGE
        $this->view->count_offer = $offerTable->getPageOfferCount($sitepageoffer->page_id);

        // START: "SUGGEST TO FRIENDS" LINK WORK.
        $page_flag = 0;
        $is_suggestion_enabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('suggestion');
        $this->view->is_moduleEnabled = $is_moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepage');
        $isModuleInfo = Engine_Api::_()->getDbtable('modules', 'core')->getModule('suggestion');
        $isSupport = Engine_Api::_()->getApi('suggestion', 'sitepage')->isSupport();

        // HERE WE ARE DELETE THIS POLL SUGGESTION IF VIEWER HAVE.

        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.package.enable', 1)) {
            if ($sitepage->expiration_date <= date("Y-m-d H:i:s")) {
                $page_flag = 1;
            }
        }

        if (!empty($viewer_id) && !empty($isSupport) && empty($sitepage->closed) && !empty($sitepage->approved) && empty($sitepage->declined) && !empty($sitepage->draft) && empty($page_flag) && !empty($is_suggestion_enabled) && ($isModuleInfo->version >= '4.1.7p2')) {
            $this->view->offerSuggLink = Engine_Api::_()->suggestion()->getModSettings('sitepage', 'offer_sugg_link');
        }
        // END: "SUGGEST TO FRIENDS" LINE WORK.
        $view = $this->view;
        $view->addHelperPath(APPLICATION_PATH . '/application/modules/Fields/View/Helper', 'Fields_View_Helper');
        $this->view->fieldStructure = $fieldStructure = Engine_Api::_()->fields()->getFieldsStructurePartial($sitepageoffer);
    }

}

?>