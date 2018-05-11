<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_IndexController extends Seaocore_Controller_Action_Standard {

    protected $_session;

    //ACTION FOR MANAGING OFFERS
    public function indexAction() {

        // CHECK USER VALIDATION
        if (!$this->_helper->requireUser()->isValid())
            return;

        //GET NAVIAGATION
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitepage_main');

        $this->view->viewer = Engine_Api::_()->user()->getViewer();

        //GET PAGE ID AND PAGE OBJECT
        $page_id = $this->_getParam('page_id');
        $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);

        // PACKAGE BASE PRIYACY START
        if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
            if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage->package_id, "modules", "sitepageoffer")) {
                return $this->_forwardCustom('requireauth', 'error', 'core');
            }
        } else {
            $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage, 'offer');
            if (empty($isPageOwnerAllow)) {
                return $this->_forwardCustom('requireauth', 'error', 'core');
            }
        }
        // PACKAGE BASE PRIYACY END
        //START MANAGE-ADMIN CHECK
        $can_edit = 1;
        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
        if (empty($isManageAdmin)) {
            $can_edit = 0;
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }

        $can_offer = 1;
        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'offer');
        if (empty($isManageAdmin)) {
            $can_offer = 0;
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }
        //END MANAGE-ADMIN CHECK
        //OFFER CREATION AUTHENTICATION CHECK
        if ($can_edit == 1 && $can_offer == 1) {
            $this->view->can_create_offer = 1;
        }

        //SEND TAB ID TO TPL
        $this->view->tab_selected_id = $this->_getParam('tab', null);

        //MAKE PAGINATOR
        $currentPageNumber = $this->_getParam('page', 1);
        $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('sitepageoffer_offer')->getSitepageoffersPaginator($page_id);
        if (!empty($paginator)) {
            $paginator->setItemCountPerPage(50)->setCurrentPageNumber($currentPageNumber);
        }
        $this->view->count = count($paginator);
    }

    //ACTION FOR CREATE OFFER
    public function createAction() {

        // CHECK USER VALIDATION
        if (!$this->_helper->requireUser()->isValid())
            return;

        $session = new Zend_Session_Namespace();
        if (isset($session->image_path)) {
            unset($session->image_path);
            // IF ANY IMAGE IS CREATE, IT WILL REMOVE THERE
            if (isset($session->photoName_Temp)) {
                unset($session->photoName_Temp);
            }
        }

        //GET LOGGED IN USER INFORMATION
        $viewer = Engine_Api::_()->user()->getViewer();

        //GET NAVIGATION
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitepage_main');

        //GET PAGE ID AND PAGE OBJECT
        $page_id = $this->_getParam('page_id');
        $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
        $this->view->offer_page = $offer_page = $this->_getParam('page_offer');
        $this->view->page_offer = $offer_page = $this->_getParam('page_offer');

        // PACKAGE BASE PRIYACY START
        if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
            if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage->package_id, "modules", "sitepageoffer")) {
                return $this->_forwardCustom('requireauth', 'error', 'core');
            }
        } else {
            $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage, 'offer');
            if (empty($isPageOwnerAllow)) {
                return $this->_forwardCustom('requireauth', 'error', 'core');
            }
        }
        // PACKAGE BASE PRIYACY END
        //START MANAGE-ADMIN CHECK
        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
        if (empty($isManageAdmin)) {
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }

        if (empty($isManageAdmin)) {
            $this->view->can_edit = $can_edit = 0;
        } else {
            $this->view->can_edit = $can_edit = 1;
        }

        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'offer');
        if (empty($isManageAdmin)) {
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }
        //END MANAGE-ADMIN CHECK

        $sitepageoffer_getInfo = Zend_Registry::isRegistered('sitepageoffer_getInfo') ? Zend_Registry::get('sitepageoffer_getInfo') : null;
        if (empty($sitepageoffer_getInfo)) {
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }

        $sitepageModHostName = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));

        $getPackageOffer = Engine_Api::_()->sitepage()->getPackageAuthInfo('sitepageoffer');
        $page_offer = Engine_Api::_()->getItemtable('sitepageoffer_offer')->getOfferList();
        $this->view->tab_selected_id = $this->_getParam('tab');

        //FORM GENERATION
        if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
            $this->view->form = $form = new Sitepageoffer_Form_Create();
        } else {
            $this->view->form = $form = new Sitepageoffer_Form_SitemobileCreate();
        }

        if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
            $form->addElement("dummy", "dummy", array('label' => 'Offer Picture', 'description' => 'Sorry, the browser you are using does not support Photo uploading. We recommend you to create an Offer from your mobile / tablet without uploading a main photo for it. You can later upload the offer picture from your Desktop.', 'order' => 4, 'style' => 'display:none;'));

            if (isset($form->photo))
                $form->photo->setAttrib('accept', "image/*");
        }
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            if (empty($page_offer)) {
                return;
            }
            $isModType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.set.type', 0);
            if (empty($isModType)) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting('sitepageoffer.offer.type', convert_uuencode($sitepageModHostName));
            }

            $sitepageoffersTable = Engine_Api::_()->getDbtable('offers', 'sitepageoffer');
            $db = $sitepageoffersTable->getAdapter();
            $db->beginTransaction();

            //GET POSTED VALUES FROM CREATE FORM
            $values = empty($getPackageOffer) ? null : $form->getValues();
            if (empty($values)) {
                return;
            }

            if ($values['claim_count'] == 0) {
                $values['claim_count'] = -1;
            }

            if ($values['end_settings'] == 0) {
                $values['end_time'] = '0000-00-00 00:00:00';
            }
            try {

                //CREATE OFFER
                $sitepageofferRow = $sitepageoffersTable->createRow();
                $sitepageofferRow->setFromArray($values);
                $sitepageofferRow->claim_count = $values['claim_count'];
                $sitepageofferRow->page_id = $page_id;
                $sitepageofferRow->owner_id = $viewer->getIdentity();
                $sitepageofferRow->save();
                $db->commit();

                //ADD PHOTO
                if (!empty($values['photo'])) {
                    $sitepageofferRow->setPhoto($form->photo);
                }

                $customfieldform = $form->getSubForm('fields');
                $customfieldform->setItem($sitepageofferRow);
                $customfieldform->saveValues();
                $activityFeedType = null;
                if (Engine_Api::_()->sitepage()->isFeedTypePageEnable())
                    $activityFeedType = 'sitepageoffer_admin_new';
                else
                    $activityFeedType = 'sitepageoffer_new';
                if ($activityFeedType) {
                    $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $sitepage, $activityFeedType);
                    Engine_Api::_()->getApi('subCore', 'sitepage')->deleteFeedStream($action);
                }

                if ($action != null) {
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sitepageofferRow);
                }


                //COMMENT PRIVACY
                $auth = Engine_Api::_()->authorization()->context;
                $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
                $auth_comment = "everyone";
                $commentMax = array_search($auth_comment, $roles);
                foreach ($roles as $i => $role) {
                    $auth->setAllowed($sitepageofferRow, $role, 'comment', ($i <= $commentMax));
                }

                //SENDING ACTIVITY FEED TO FACEBOOK.
                $enable_Facebooksefeed = $enable_fboldversion = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('facebooksefeed');
                if (!empty($enable_Facebooksefeed)) {

                    $offer_array = array();
                    $offer_array['type'] = 'sitepageoffer_new';
                    $offer_array['object'] = $sitepageofferRow;

                    Engine_Api::_()->facebooksefeed()->sendFacebookFeed($offer_array);
                }

                //PAGE OFFER CREATE NOTIFICATION AND EMAIL WORK
                $sitepageVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule('sitepage')->version;
                if (!empty($action)) {
                    if ($sitepageVersion >= '4.3.0p1') {
                        Engine_Api::_()->sitepage()->sendNotificationEmail($sitepageofferRow, $action, 'sitepageoffer_create', 'SITEPAGEOFFER_CREATENOTIFICATION_EMAIL', 'Pageevent Invite');

                        $isPageAdmins = Engine_Api::_()->getDbtable('manageadmins', 'sitepage')->isPageAdmins($viewer->getIdentity(), $page_id);
                        if (!empty($isPageAdmins)) {
                            //NOTIFICATION FOR ALL FOLLWERS.
                            Engine_Api::_()->sitepage()->sendNotificationToFollowers($sitepageofferRow, $action, 'sitepageoffer_create');
                        }
                    }
                }
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }

            if (!empty($offer_page)) {
                return $this->_gotoRouteCustom(array('action' => 'index'));
            } else {
                return $this->_redirectCustom($sitepageofferRow->getHref(), array('prependBase' => false));
            }
        }
    }

    //ACTION FOR EDIT OFFER
    public function editAction() {

        // CHECK USER VALIDATION
        if (!$this->_helper->requireUser()->isValid())
            return;

        $this->view->offer_page = $offer_page = $this->_getParam('offer_page');
        $this->view->tab_selected_id = $this->_getParam('tab');

        //GET OFFER OBJECT
        $this->view->sitepageoffer = $sitepageoffers = Engine_Api::_()->getItem('sitepageoffer_offer', $this->_getParam('offer_id'));

        //GET PAGE OBJECT
        $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $sitepageoffers->page_id);

        //GET NAVIGATION
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitepage_main');

        //PACKAGE BASE PRIYACY START
        if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
            if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage->package_id, "modules", "sitepageoffer")) {
                return $this->_forwardCustom('requireauth', 'error', 'core');
            }
        } else {
            $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage, 'offer');
            if (empty($isPageOwnerAllow)) {
                return $this->_forwardCustom('requireauth', 'error', 'core');
            }
        }
        //PACKAGE BASE PRIYACY END
        //START MANAGE-ADMIN CHECK
        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
        if (empty($isManageAdmin)) {
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }

        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'offer');
        if (empty($isManageAdmin)) {
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }
        //END MANAGE-ADMIN CHECK
        //FORM GENERATION
        $this->view->form = $form = new Sitepageoffer_Form_Edit(array('item' => $sitepageoffers));

        if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
            $form->addElement("dummy", "dummy", array('label' => 'Offer Picture', 'description' => 'Sorry, the browser you are using does not support Photo uploading. We recommend you to edit an Offer from your mobile / tablet without uploading a main photo for it. You can later upload the offer picture from your Desktop.', 'order' => 4, 'style' => 'display:none;'));

            if (preg_match('/' . 'iPad' . '/i', $_SERVER['HTTP_USER_AGENT'])) {
                if (isset($form->photo)) {
                    $form->removeElement('photo');
                }
            } else {
                if (isset($form->photo)) {
                    $form->photo->setAttrib('accept', "image/*");
                }
            }
        }

        $date = (string) date('Y-m-d');
        $sitepageoffers->end_time = $sitepageoffers->end_time;

        if ($sitepageoffers->claim_count == 0) {
            $form->getElement('claim_count')
                    ->setIgnore(true)
                    ->setAttrib('disable', true)
                    ->clearValidators()
                    ->setRequired(false)
                    ->setAllowEmpty(true)
            ;
        }

        //SHOW PRE-FIELD FORM
        if ($sitepageoffers->claim_count == -1) {
            $sitepageoffers->claim_count = 0;
        }

        if ($sitepageoffers->end_settings == 0) {
            $date = (string) date('Y-m-d');
            $sitepageoffers->end_time = $date . ' 00:00:00';
        }
        $form->populate($sitepageoffers->toArray());

        //IF NOT POST OR FORM NOT VALID THAN RETURN
        if (!$this->getRequest()->isPost()) {
            $form->populate($sitepageoffers->toArray());
            return;
        }

        //IF NOT POST OR FORM NOT VALID THAN RETURN
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        //GET FORM VALUES
        $values = $form->getValues();

        if (isset($values['claim_count']) && $values['claim_count'] == 0) {
            $values['claim_count'] = -1;
        }

        if ($values['end_settings'] == 0) {
            $values['end_time'] = '0000-00-00 00:00:00';
        }
        Engine_Api::_()->sitepageoffer()->setOfferPackages();

        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {
            $sitepageoffers->setFromArray($values);
            $sitepageoffers->save();
            if (!empty($values['photo'])) {
                $sitepageoffers->setPhoto($form->photo);
            }
            // Save custom fields
            $customfieldform = $form->getSubForm('fields');
            $customfieldform->setItem($sitepageoffers);
            $customfieldform->saveValues();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        if (!empty($offer_page)) {
            $this->_forwardCustom('success', 'utility', 'core', array(
                'smoothboxClose' => true,
                'parentRefresh' => true,
                'format' => 'smoothbox',
                'messages' => Zend_Registry::get('Zend_Translate')->_('Your offer has been edit successfully.')
            ));
        } else {
            return $this->_redirectCustom($sitepageoffers->getHref(), array('prependBase' => false));
        }
    }

    //ACTION FOR DELETE OFFER
    public function deleteAction() {

        // CHECK USER VALIDATION
        if (!$this->_helper->requireUser()->isValid())
            return;
        $this->view->offer_page = $offer_page = $this->_getParam('offer_page');
        $this->view->tab_selected_id = $tab_selected_id = $this->_getParam('tab');

        //GET OFFER ID AND OFFER OBJECT
        $this->view->offer_id = $offer_id = $this->_getParam('offer_id');
        $sitepageoffers = Engine_Api::_()->getItem('sitepageoffer_offer', $offer_id);

        //GET PAGE OBJECT
        $this->view->sitepage = $sitepage = Engine_Api::_()->getItem('sitepage_page', $sitepageoffers->page_id);

        //GET NAVIGATION
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitepage_main');

        // PACKAGE BASE PRIYACY START
        if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
            if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage->package_id, "modules", "sitepageoffer")) {
                return $this->_forwardCustom('requireauth', 'error', 'core');
            }
        } else {
            $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage, 'offer');
            if (empty($isPageOwnerAllow)) {
                return $this->_forwardCustom('requireauth', 'error', 'core');
            }
        }
        // PACKAGE BASE PRIYACY END
        //START MANAGE-ADMIN CHECK
        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
        if (empty($isManageAdmin)) {
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }

        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'offer');
        if (empty($isManageAdmin)) {
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }
        //END MANAGE-ADMIN CHECK

        if ($this->getRequest()->isPost()) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {

                Engine_Api::_()->sitepageoffer()->deleteContent($sitepageoffers->offer_id);
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            if (!empty($offer_page)) {
                $this->_forwardCustom('success', 'utility', 'core', array(
                    'smoothboxClose' => 300,
                    'parentRefresh' => 300,
                    'messages' => array('Your offer has been deleted successfully.')
                ));
            } else {
                return $this->_gotoRouteCustom(array('page_url' => Engine_Api::_()->sitepage()->getPageUrl($sitepageoffers->page_id), 'tab' => $tab_selected_id), 'sitepage_entry_view', true);
            }
        }
    }

    public function stickyAction() {

        // CHECK USER VALIDATION
        if (!$this->_helper->requireUser()->isValid())
            return;

        //GET PAGE ID AND PAGE OBJECT
        $page_id = $this->_getParam('page_id');
        $offer_id = $this->_getParam('offer_id');
        $offer_page = $this->_getParam('offer_page');
        $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
        $sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $offer_id);
        $this->view->sticky = $sitepageoffer->sticky;
        //PACKAGE BASE PRIYACY START
        if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
            if (!Engine_Api::_()->sitepage()->allowPackageContent($sitepage->package_id, "modules", "sitepageoffer")) {
                return $this->_forwardCustom('requireauth', 'error', 'core');
            }
        } else {
            $isPageOwnerAllow = Engine_Api::_()->sitepage()->isPageOwnerAllow($sitepage, 'offer');
            if (empty($isPageOwnerAllow)) {
                return $this->_forwardCustom('requireauth', 'error', 'core');
            }
        }
        //PACKAGE BASE PRIYACY END
        //START MANAGE-ADMIN CHECK
        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
        if (empty($isManageAdmin)) {
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }

        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'offer');
        if (empty($isManageAdmin)) {
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }
        //END MANAGE-ADMIN CHECK
        //POST DATA
        if ($this->getRequest()->isPost()) {

            Engine_Api::_()->getDbtable('offers', 'sitepageoffer')->makeSticky($offer_id, $page_id);
            if (!empty($offer_page)) {
                $this->_forwardCustom('success', 'utility', 'core', array(
                    'smoothboxClose' => 10,
                    'parentRefresh' => 10,
                    'messages' => array('')
                ));
            } else {
                $this->_forwardCustom('success', 'utility', 'core', array(
                    'smoothboxClose' => 10,
                    'parentRedirect' => $this->_helper->url->url(array('page_url' => Engine_Api::_()->sitepage()->getPageUrl($page_id), 'tab' => $this->_getParam('tab')), 'sitepage_entry_view', true),
                    'parentRedirectTime' => '2',
                    'messages' => array('')
                ));
            }
        }
    }

    public function browseAction() {

        //CHECK VIEW PRIVACY
        if (!$this->_helper->requireAuth()->setAuthParams('sitepage_page', null, 'view')->isValid())
            return;

        //CHECK THE VERSION OF THE CORE MODULE
        $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
        $coreversion = $coremodule->version;
        if ($coreversion < '4.1.0') {
            $this->_helper->content->render();
        } else {
            $this->_helper->content
                    ->setNoRender()
                    ->setEnabled()
            ;
        }
    }

    //ACTION FOR PRINTING THE OFFER
    public function printAction() {

        $this->_helper->layout->setLayout('default-simple');

        //GET OFFER ID AND OFFER OBJECT
        $offer_id = $this->_getParam('offer_id', null);
        $this->view->sitepage = Engine_Api::_()->getItem('sitepage_page', $this->_getParam('page_id', null));
        $this->view->sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $offer_id);
        if (empty($offer_id))
            return $this->_forwardCustom('notfound', 'error', 'core');
    }

    //ACTION FOR PRINTING THE OFFER
    public function previewAction() {

        $session = new Zend_Session_Namespace();
        if (isset($session->image_path)) {
            $this->view->image_path = $session->image_path;
        } else {
            $this->view->image_path = Zend_Registry::get('Zend_View')->layout()->staticBaseUrl . 'application/modules/Sitepageoffer/externals/images/offer_thumb.png';
        }
    }

    public function uploadAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $file = $_FILES["photo"]["tmp_name"];
        $file1 = $_FILES["photo"]["name"];

        $name = basename($file1);

        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/sitepageoffer_offer';

        $storage = Engine_Api::_()->storage();

        //RESIZE IMAGE (MAIN)
        $image = Engine_Image::factory();
        $image->open($file);
        //IMAGE WIDTH
        $dstW = $image->width;
        // IMAGE HIGHT
        $dstH = $image->height;

        $size = min($image->height, $image->width);
        $x = ($image->width - $size) / 2;
        $y = ($image->height - $size) / 2;

        $image->resample($x, $y, $size, $size, 48, 48)
                ->write($path . '/' . $name)
                ->destroy();

        $photoName = $this->view->baseUrl() . '/public/sitepageoffer_offer/' . $name;

        $session = new Zend_Session_Namespace();
        $session->image_path = $photoName;
        $session->photoName_Temp = $path . '/' . $name;

        @chmod($this->_session->photoName_Temp, 0777);
    }

    //ACTION FOR VIEW OFFER
    public function viewAction() {

        //IF SITEPAGEOFFER SUBJECT IS NOT THEN RETURN
//     if (!$this->_helper->requireSubject('sitepageoffer_offer')->isValid())
//       return;

        $sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $this->getRequest()->getParam('offer_id'));
        if ($sitepageoffer) {
            Engine_Api::_()->core()->setSubject($sitepageoffer);
        }

        //GET LOGGED IN USER INFORMATION
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        //GET OFFER ITEM
        $sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $this->getRequest()->getParam('offer_id'));

        //GET SITEPAGE ITEM
        $sitepage = Engine_Api::_()->getItem('sitepage_page', $sitepageoffer->page_id);


        //START MANAGE-ADMIN CHECK
        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'view');
        if (empty($isManageAdmin)) {
            return $this->_forwardCustom('requireauth', 'error', 'core');
        }
        //END MANAGE-ADMIN CHECK

        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'edit');
        if (empty($isManageAdmin)) {
            $can_edit = 0;
        } else {
            $can_edit = 1;
        }

        $can_offer = 1;
        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepage, 'offer');
        if (empty($isManageAdmin)) {
            $can_offer = 0;
        }

        $can_create_offer = '';
        //OFFER CREATION AUTHENTICATION CHECK
        if ($can_edit == 1 && $can_offer == 1) {
            $this->view->can_create_offer = $can_create_offer = 1;
        }

        //END MANAGE-ADMIN CHECK
        //CHECKING THE USER HAVE THE PERMISSION TO VIEW THE OFFER OR NOT
//     if ($viewer_id != $sitepageoffer->owner_id && $can_edit != 1 && ($sitepageoffer->search != 1 || $sitepageoffer->status != 1)) {
//       return $this->_forwardCustom('requireauth', 'error', 'core');
//     }
        //NAVIGATION WORK FOR FOOTER.(DO NOT DISPLAY NAVIGATION IN FOOTER ON VIEW PAGE.)
        if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
            if (!Zend_Registry::isRegistered('sitemobileNavigationName')) {
                Zend_Registry::set('sitemobileNavigationName', 'setNoRender');
            }
        }
        if (Engine_Api::_()->seaocore()->isSitemobileApp()) {
            Zend_Registry::set('setFixedCreationFormBack', 'Back');
        }
        //CHECK THE VERSION OF THE CORE MODULE
        $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
        $coreversion = $coremodule->version;
        if ($coreversion < '4.1.0') {
            $this->_helper->content->render();
        } else {
            $this->_helper->content
                    ->setNoRender()
                    ->setEnabled()
            ;
        }
    }

    //ACTION FOR SEND CLAIM OFFER MAIL
    public function getofferAction() {

        $param = $this->_getParam('param');
        $request_url = $this->_getParam('request_url');
        $return_url = $this->_getParam('return_url');
        $front = Zend_Controller_Front::getInstance();
        $base_url = $front->getBaseUrl();

        // CHECK USER VALIDATION
        if (!$this->_helper->requireUser()->isValid()) {
            $host = (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) ? "https://" : "http://";
            if ($base_url == '') {
                $URL_Home = $host . $_SERVER['HTTP_HOST'] . '/login';
            } else {
                $URL_Home = $host . $_SERVER['HTTP_HOST'] . '/' . $request_url . '/login';
            }
            if (empty($param)) {
                return $this->_helper->redirector->gotoUrl($URL_Home, array('prependBase' => false));
            } else {
                return $this->_helper->redirector->gotoUrl($URL_Home . '?return_url=' . urlencode($return_url), array('prependBase' => false));
            }
        }

        $offer_id = $this->_getParam('id');
        //GET OFFER OBJECT
        $sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $offer_id);
        $sitepageObject = Engine_Api::_()->getItem('sitepage_page', $sitepageoffer->page_id);

        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepageObject, 'view');
        if (empty($isManageAdmin)) {
            $this->view->private_message = 1;
        } else {
            //GET LOGGED IN USER INFORMATION
            $viewer = Engine_Api::_()->user()->getViewer();

            $claim_value = Engine_Api::_()->getDbTable('claims', 'sitepageoffer')->getClaimValue($viewer->getIdentity(), $offer_id, $sitepageoffer->page_id);

            $this->view->offer_id = $offer_id;

            if (!empty($claim_value)) {
                $this->renderScript('index/resendoffer.tpl');
            } else {

                //GET THE TAB ID OF OFFER ON PAGE PROFILE
                $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
                $offer_tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $sitepageoffer->page_id, $layout);

                //PAGE URL
                $page_url = Engine_Api::_()->sitepage()->getPageUrl($sitepageObject->page_id);

                $data['pagehome_offer'] = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                        Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'sitepageoffer_home', true), $this->view->translate('View More Offers'), array('style' => 'color:#3b5998;text-decoration:none;margin-left:10px;', 'target' => '_blank'));
                if ($sitepageObject->photo_id) {
                    $data['page_photo_path'] = $sitepageObject->getPhotoUrl('thumb.icon');
                } else {
                    $data['page_photo_path'] = 'application/modules/Sitepage/externals/images/nophoto_sitepage_thumb_icon.png';
                }

                $data['share_offer'] = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                        Zend_Controller_Front::getInstance()->getRouter()->assemble(array('user_id' => $sitepageoffer->owner_id, 'offer_id' => $sitepageoffer->offer_id, 'tab' => $offer_tab_id, 'slug' => $sitepageoffer->getOfferSlug($sitepageoffer->title), 'share_offer' => 1), 'sitepageoffer_view', true), $this->view->translate('Share Offer'), array('style' => 'text-decoration:none;font-weight:bold;color:#fff;font-size:11px;', 'target' => '_blank'));

                $data['like_page'] = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                        Zend_Controller_Front::getInstance()->getRouter()->assemble(array('page_url' => $page_url), 'sitepage_entry_view', true), $this->view->translate('Like') . ' ' . $sitepageObject->getTitle(), array('style' => 'color:#3b5998;text-decoration:none;margin-right:10px;margin-left:10px;', 'target' => '_blank'));

                $this->view->page_title = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                        Zend_Controller_Front::getInstance()->getRouter()->assemble(array('page_url' => $page_url), 'sitepage_entry_view', true), $sitepageObject->getTitle(), array('target' => '_blank'));

                $data['page_title'] = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                        Zend_Controller_Front::getInstance()->getRouter()->assemble(array('page_url' => $page_url), 'sitepage_entry_view', true), $sitepageObject->getTitle(), array('target' => '_blank', 'style' => 'color:#3b5998;text-decoration:none;'));

                if ($sitepageoffer->photo_id) {
                    $data['offer_photo_path'] = $sitepageoffer->getPhotoUrl('thumb.icon');
                } else {
                    $data['offer_photo_path'] = 'application/modules/Sitepageoffer/externals/images/offer_thumb.png';
                }

                $data['site_title'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', 1);

                $data['offer_title'] = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                        Zend_Controller_Front::getInstance()->getRouter()->assemble(array('user_id' => $sitepageoffer->owner_id, 'offer_id' => $sitepageoffer->offer_id, 'tab' => $offer_tab_id, 'slug' => $sitepageoffer->getOfferSlug($sitepageoffer->title)), 'sitepageoffer_view', true), $sitepageoffer->title, array('style' => 'color:#3b5998;text-decoration:none;'));

                $data['coupon_code'] = $sitepageoffer->coupon_code;
                $data['offer_url'] = $sitepageoffer->url;
                $data['offer_description'] = $sitepageoffer->description;
                $data['offer_time'] = gmdate('M d, Y', strtotime($sitepageoffer->end_time));
                $data['offer_time_setting'] = $sitepageoffer->end_settings;
                $data['claim_owner_name'] = Engine_Api::_()->user()->getViewer()->username;
                $data['enable_mailtemplate'] = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemailtemplates');

                // INITIALIZE THE STRING TO BE SEND IN THE CLAIM MAIL
                $template_header = "";
                $template_footer = "";
                $string = '';
                $string = $this->view->pageoffermail($data);

                $sitepageofferClaimTable = Engine_Api::_()->getDbTable('claims', 'sitepageoffer');

                $db = Engine_Db_Table::getDefaultAdapter();
                $db->beginTransaction();
                try {

                    //CREATE CLAIM FOR OFFER
                    $sitepageofferRow = $sitepageofferClaimTable->createRow();
                    $sitepageofferRow->owner_id = $viewer->getIdentity();
                    $sitepageofferRow->page_id = $sitepageoffer->page_id;
                    $sitepageofferRow->offer_id = $sitepageoffer->offer_id;
                    $sitepageofferRow->claim_value = 1;
                    $sitepageofferRow->save();

                    $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $sitepageoffer, 'sitepageoffer_home');

                    if ($action != null) {
                        Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sitepageoffer);
                    }

                    $db->commit();
                } catch (Exception $e) {
                    $db->rollBack();
                    throw $e;
                }

                $subject = $this->view->translate('Your ') . $data['site_title'] . $this->view->translate(' offer from ') . $sitepageObject->title;

                // SEND MAIL CLAIM OFFER
                $email = Engine_Api::_()->user()->getViewer()->email;
                $email_admin = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
                Engine_Api::_()->getApi('mail', 'core')->sendSystem($email, 'offer_claim', array(
                    'subject' => $subject,
                    'template_header' => $template_header,
                    'message' => $string,
                    'template_footer' => $template_footer,
                    'email' => $email_admin,
                    'queue' => false));

                $today = date("Y-m-d H:i:s");

                if ($sitepageoffer->claim_count > 0) {
                    $sitepageoffer->claim_count--;
                }
                $sitepageoffer->claimed++;
                $sitepageoffer->save();
                $claim_count = $sitepageoffer->claim_count;
                $offer_id = $sitepageoffer->offer_id;

                if (($claim_count == 0) && $sitepageoffer->end_settings == 1 && $sitepageoffer->end_time < $today) {
                    $sitepageofferClaimTable->deleteClaimOffers($offer_id);
                }
            }
        }
    }

    //ACTION FOR RESEND OFFER CLAIM MAIL
    public function resendofferAction() {

        $this->view->offer_id = $offer_id = $this->_getParam('id');
        //GET OFFER OBJECT
        $sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $offer_id);

        $sitepageObject = Engine_Api::_()->getItem('sitepage_page', $sitepageoffer->page_id);

        $isManageAdmin = Engine_Api::_()->sitepage()->isManageAdmin($sitepageObject, 'view');
        if (empty($isManageAdmin)) {
            $this->view->private_message = 1;
        }
    }

    public function sendofferAction() {

        $this->_helper->layout->setLayout('default-simple');
        $this->view->offer_id = $offer_id = $this->_getParam('id');

        $data = array();

        //GET OFFER OBJECT
        $sitepageoffer = Engine_Api::_()->getItem('sitepageoffer_offer', $offer_id);

        //GET THE TAB ID OF OFFER FOR PAGE PROFILE
        $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
        $offer_tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $sitepageoffer->page_id, $layout);

        $sitepageObject = Engine_Api::_()->getItem('sitepage_page', $sitepageoffer->page_id);

        //PAGE URL
        $page_url = Engine_Api::_()->sitepage()->getPageUrl($sitepageObject->page_id);

        $data['pagehome_offer'] = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'sitepageoffer_home', true), $this->view->translate('View More Offers'), array('style' => 'color:#3b5998;text-decoration:none;margin-left:10px; ', 'target' => '_blank'));

        $data['share_offer'] = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array('user_id' => $sitepageoffer->owner_id, 'offer_id' => $sitepageoffer->offer_id, 'tab' => $offer_tab_id, 'slug' => $sitepageoffer->getOfferSlug($sitepageoffer->title), 'share_offer' => 1), 'sitepageoffer_view', true), $this->view->translate('Share Offer'), array('style' => 'text-decoration:none;font-weight:bold;color:#fff;font-size:11px;', 'target' => '_blank'));

        $data['like_page'] = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array('page_url' => $page_url), 'sitepage_entry_view', true), $this->view->translate('Like') . ' ' . $sitepageObject->getTitle(), array('style' => 'color:#3b5998;text-decoration:none;margin-right:10px;margin-left:10px;', 'target' => '_blank'));

        if ($sitepageObject->photo_id) {
            $data['page_photo_path'] = $sitepageObject->getPhotoUrl('thumb.icon');
        } else {
            $data['page_photo_path'] = 'application/modules/Sitepage/externals/images/nophoto_sitepage_thumb_icon.png';
        }

        $data['page_title'] = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array('page_url' => $page_url), 'sitepage_entry_view', true), $sitepageObject->getTitle(), array('style' => 'color:#3b5998;text-decoration:none;'));

        if ($sitepageoffer->photo_id) {
            $data['offer_photo_path'] = $sitepageoffer->getPhotoUrl('thumb.icon');
        } else {
            $data['offer_photo_path'] = 'application/modules/Sitepageoffer/externals/images/offer_thumb.png';
        }

        $data['site_title'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title', 1);

        $data['offer_title'] = $this->view->htmlLink('http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array('user_id' => $sitepageoffer->owner_id, 'offer_id' => $sitepageoffer->offer_id, 'tab' => $offer_tab_id, 'slug' => $sitepageoffer->getOfferSlug($sitepageoffer->title)), 'sitepageoffer_view', true), $sitepageoffer->title, array('style' => 'color:#3b5998;text-decoration:none;'));
        $data['coupon_code'] = $sitepageoffer->coupon_code;
        $data['offer_url'] = $sitepageoffer->url;
        $data['offer_description'] = $sitepageoffer->description;
        $data['offer_time'] = gmdate('M d, Y', strtotime($sitepageoffer->end_time));
        $data['offer_time_setting'] = $sitepageoffer->end_settings;
        $data['claim_owner_name'] = Engine_Api::_()->user()->getViewer()->username;
        $data['enable_mailtemplate'] = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemailtemplates');

        //INITIALIZE THE STRING TO BE SEND IN THE CLAIM MAIL
        $template_header = "";
        $template_footer = "";
        $string = '';
        $string = $this->view->pageoffermail($data);

        $this->view->page_title = $sitepageObject->title;

        $subject = $this->view->translate('Your ') . $data['site_title'] . $this->view->translate(' offer from ') . $sitepageObject->title;

        // SEND MAIL CLAIM OFFER
        $email = Engine_Api::_()->user()->getViewer()->email;
        $email_admin = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($email, 'offer_claim', array(
            'subject' => $subject,
            'template_header' => $template_header,
            'message' => $string,
            'template_footer' => $template_footer,
            'email' => $email_admin,
            'queue' => false));
    }

    // ACTION FOR FEATURED NOTES CAROUSEL AFTER CLICK ON BUTTON 
    public function hotOffersCarouselAction() {
        //RETRIVE THE VALUE OF ITEM VISIBLE
        $this->view->itemsVisible = $limit = (int) $_GET['itemsVisible'];

        $this->view->viewer = Engine_Api::_()->user()->getViewer();
        $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

        //RETRIVE THE VALUE OF NUMBER OF ROW
        $this->view->noOfRow = (int) $_GET['noOfRow'];
        //RETRIVE THE VALUE OF ITEM VISIBLE IN ONE ROW
        $this->view->inOneRow = (int) $_GET['inOneRow'];

        // Total Count Featured Photos
        $totalCount = (int) $_GET['totalItem'];

        //RETRIVE THE VALUE OF START INDEX
        $startindex = $_GET['startindex'] * $limit;

        if ($startindex > $totalCount) {
            $startindex = $totalCount - $limit;
        }
        if ($startindex < 0)
            $startindex = 0;

        $params = array();
        $params['category_id'] = $_GET['category_id'];
        $hotOffer = 1;
        $params['offertype'] = 'hotoffer';

        //RETRIVE THE VALUE OF BUTTON DIRECTION
        $direction = $_GET['direction'];
        $this->view->offset = $params['start_index'] = $startindex;

        //GET Featured Photos with limit * 2
        $this->view->totalItemsInSlide = $params['limit'] = $limit * 2;
        $this->view->hotOffers = $this->view->hotOffers = $hotOffers = Engine_Api::_()->getDbTable('offers', 'sitepageoffer')->getOffers($hotOffer, $params);

        //Pass the total number of result in tpl file
        $this->view->count = count($hotOffers);

        //Pass the direction of button in tpl file
        $this->view->direction = $direction;
    }

    public function homeAction() {

        //CHECK VIEW PRIVACY
        if (!$this->_helper->requireAuth()->setAuthParams('sitepage_page', null, 'view')->isValid())
            return;

        //CHECK THE VERSION OF THE CORE MODULE
        $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
        $coreversion = $coremodule->version;
        if ($coreversion < '4.1.0') {
            $this->_helper->content->render();
        } else {
            $this->_helper->content
                    ->setNoRender()
                    ->setEnabled()
            ;
        }
    }

    //ACTION FOR ADDING OFFER OF THE DAY
    public function addOfferOfDayAction() {
        //FORM GENERATION
        $form = $this->view->form = new Sitepageoffer_Form_ItemOfDayday();
        $offer_id = $this->_getParam('offer_id');
        // $form->setAction($this->getFrontController()->getRouter()->assemble(array()));
        //CHECK POST
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

            //GET FORM VALUES
            $values = $form->getValues();

            //BEGIN TRANSACTION
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {

                //GET ITEM OF THE DAY TABLE
                $dayItemTime = Engine_Api::_()->getDbtable('itemofthedays', 'sitepage');

                //FETCH RESULT FOR resource_id
                $select = $dayItemTime->select()->where('resource_id = ?', $offer_id)->where('resource_type = ?', 'sitepageoffer_offer');
                $row = $dayItemTime->fetchRow($select);

                if (empty($row)) {
                    $row = $dayItemTime->createRow();
                    $row->resource_id = $offer_id;
                }
                $row->start_date = $values["starttime"];
                $row->end_date = $values["endtime"];
                $row->resource_type = 'sitepageoffer_offer';
                $row->save();

                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            return $this->_forwardCustom('success', 'utility', 'core', array(
                        'smoothboxClose' => 10,
                        'messages' => array(Zend_Registry::get('Zend_Translate')->_('The Offer of the Day has been added successfully.'))
            ));
        }
    }

}

?>