<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
class Sdparentalguide_ListingController extends Core_Controller_Action_Standard
{
    protected $_navigation;
    protected $_listingType;

    //COMMON ACTION WHICH CALL BEFORE EVERY ACTION OF THIS CONTROLLER
    public function init() {
        //SET LISTING TYPE ID AND OBJECT
        $listingtype_id = $this->_getParam('listingtype_id', null);
        if ($listingtype_id != -1 && !empty($listingtype_id)) {
            Engine_Api::_()->sitereview()->setListingTypeInRegistry($listingtype_id);
            $this->_listingType = Zend_Registry::get('listingtypeArray' . $listingtype_id);
            Zend_Registry::isRegistered('sitereviewGetListingType') ? $sitereviewGetListingType = true : $this->_setParam('listing_id', 0);

            //AUTHORIZATION CHECK
            if ($this->_getParam('action', null) != 'categories')
                if (!$this->_helper->requireAuth()->setAuthParams('sitereview_listing', null, "view_listtype_$listingtype_id")->isValid())
                    return;
        }

        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewpaidlisting')) {
            //FOR UPDATE EXPIRATION
            if ((Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereviewpaidlisting.task.updateexpiredlistings') + 900) <= time()) {
                Engine_Api::_()->sitereviewpaidlisting()->updateExpiredListings($listingtype_id);
            }
        }
    }
    
    public function createAction() {
        //ONLY LOGGED IN USER CAN CREATE
        if (!$this->_helper->requireUser()->isValid())
            return;

        //GET LISTING TYPE ID
        $package_id = $this->_getParam('id', 0);
        $this->view->listingtype_id = $listingtype_id = $this->_listingType->listingtype_id;
        $this->view->listing_singular_lc = strtolower($this->_listingType->title_singular);
        $this->view->listing_singular_uc = $listing_singular_uc = ucfirst($this->_listingType->title_singular);
        $this->view->listing_plural_lc = strtolower($this->_listingType->title_plural);
        $this->view->show_editor = $this->_listingType->show_editor;

        //SITEMOBILE_MODULE_NOT_SUPPORT_DESC_FOR_SOMEPAGES
        //if(!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
        $this->_helper->content
                ->setContentName("sitereview_index_create_listtype_$listingtype_id")
                //->setNoRender()
                ->setEnabled();

        //}
        //GET VIEWER
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $this->view->level_id = $viewer->level_id;
        global $sitereviewGetCategory;
        global $sitereview_is_approved;
        //CHECK FOR CREATION PRIVACY
        if (!$this->_helper->requireAuth()->setAuthParams('sitereview_listing', null, "create_listtype_$listingtype_id")->isValid())
            return;

        //GET DEFAULT PROFILE TYPE ID
        $this->view->defaultProfileId = $defaultProfileId = Engine_Api::_()->getDbTable('metas', 'sitereview')->defaultProfileId();

        //SEND LISTING TYPE TITLE TO TPL
        $this->view->title = $this->_listingType->title_plural;

        //GET NAVIGATION
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation("sitereview_main_listtype_$listingtype_id");

        //MAKE FORM
        $this->view->form = $form = new Sdparentalguide_Form_Listing_Create(array('defaultProfileId' => $defaultProfileId));

        if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
            //CLEAR CACHE ON FORM DISPLAY, ALL FIELDS SHOULD BE EMPTY.(FOR SITEMOBILE)
            $this->view->clear_cache = true;
            $this->view->noDomCache = true;
        }
        if (Engine_Api::_()->seaocore()->isSitemobileApp()) {
            Zend_Registry::set('setFixedCreationForm', true);
            Zend_Registry::set('setFixedCreationHeaderTitle', "Post New $listing_singular_uc");
            Zend_Registry::set('setFixedCreationHeaderSubmit', 'Save');
            $this->view->form->setAttrib('name', 'sitereviews_create');
            Zend_Registry::set('setFixedCreationFormId', '#sitereviews_create');
            $this->view->form->removeElement('execute');
            $this->view->form->removeElement('cancel');
            $form->setTitle('');
        }

        if (Engine_Api::_()->sitereview()->hasPackageEnable()) {

            $this->view->allow_review = $this->_listingType->reviews;
            $this->view->overview = $this->_listingType->overview;
            $this->view->wishlist = $this->_listingType->wishlist;
            $this->view->location = $this->_listingType->location;
            $this->view->package_description = $this->_listingType->package_description;
            $this->view->viewer = Engine_Api::_()->user()->getViewer();

            //REDIRECT
            $package_id = $this->_getParam('id');
            if (empty($package_id)) {
                return $this->_forwardCustom('notfound', 'error', 'core');
            }
            $this->view->package = $package = Engine_Api::_()->getItemTable('sitereviewpaidlisting_package')->fetchRow(array('package_id = ?' => $package_id, 'listingtype_id = ?' => $listingtype_id, 'enabled = ?' => '1'));
            if (empty($this->view->package)) {
                return $this->_forwardCustom('notfound', 'error', 'core');
            }

            if (!empty($package->level_id) && !in_array($viewer->level_id, explode(",", $package->level_id))) {
                return $this->_forwardCustom('notfound', 'error', 'core');
            }
        } elseif (isset($this->_listingType->package) && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewpaidlisting')) {
            $package_id = Engine_Api::_()->getItemtable('sitereviewpaidlisting_package')->fetchRow(array('listingtype_id = ?' => $listingtype_id, 'defaultpackage = ?' => 1))->package_id;
        }

        //GET VIEWER
        $listValues = array();

        //GET TINYMCE SETTINGS
        $this->view->upload_url = "";
        $albumEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album');
        if (Engine_Api::_()->authorization()->isAllowed('album', $viewer, 'create') && $albumEnabled) {
            $this->view->upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'upload-photo'), 'sitereview_general_listtype_' . $this->_listingType->listingtype_id, true);
        }

        $orientation = $this->view->layout()->orientation;
        if ($orientation == 'right-to-left') {
            $this->view->directionality = 'rtl';
        } else {
            $this->view->directionality = 'ltr';
        }

        $local_language = $this->view->locale()->getLocale()->__toString();
        $local_language = explode('_', $local_language);
        $this->view->language = $local_language[0];

        //COUNT SITEREVIEW CREATED BY THIS USER AND GET ALLOWED COUNT SETTINGS
        $values['user_id'] = $viewer_id;
        $values['listingtype_id'] = $listingtype_id;
        $paginator = Engine_Api::_()->getDbTable('listings', 'sitereview')->getSitereviewsPaginator($values);
        $this->view->current_count = $paginator->getTotalItemCount();
        $this->view->quota = $quota = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitereview_listing', "max_listtype_$listingtype_id");

        $sitereviewLsettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.lsettings', false);
        $sitereviewGetAttemptType = Zend_Registry::isRegistered('sitereviewGetAttemptType') ? Zend_Registry::get('sitereviewGetAttemptType') : null;
        $sitereviewListingTypeOrder = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.listingtype.order', false);
        $sitereviewProfileOrder = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.profile.order', false);
        $sitereviewViewAttempt = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.view.attempt', false);
        $sitereviewViewType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.show.viewtype', false);
        $sitereviewViewAttempt = !empty($sitereviewGetAttemptType) ? $sitereviewGetAttemptType : @convert_uudecode($sitereviewViewAttempt);
        $this->view->category_count = Engine_Api::_()->getDbTable('categories', 'sitereview')->getCategories(null, 1, $listingtype_id, 0, 1, 0, 'cat_order', 0, array('category_id'));
        $sitereviewCategoryType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.category.type', false);

        $sitereview_host = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));

        $this->view->sitereview_render = 'sitereview_form';
        $this->view->expiry_setting = $expiry_setting = Engine_Api::_()->sitereview()->expirySettings($listingtype_id);
        $tempGetFinalNumber = $sitereviewSponsoredOrder = $sitereviewFeaturedOrder = 0;
        for ($tempFlag = 0; $tempFlag < strlen($sitereviewLsettings); $tempFlag++) {
            $sitereviewFeaturedOrder += @ord($sitereviewLsettings[$tempFlag]);
        }

        for ($tempFlag = 0; $tempFlag < strlen($sitereviewViewAttempt); $tempFlag++) {
            $sitereviewSponsoredOrder += @ord($sitereviewViewAttempt[$tempFlag]);
        }
        $sitereviewListingTypeOrder += $sitereviewFeaturedOrder + $sitereviewSponsoredOrder;

        // Check method/data validitiy
        if (!$this->getRequest()->isPost()) {
            return;
        }

        $photoOrder = array_search('photo', array_keys($form->getElements())) - 1;

        $tempPost = $this->getRequest()->getPost();
        if (isset($form->photo)) {
            $tempForm = $form;
            $photoEl = $form->photo;

            if (isset($tempPost['photo'])) {
                unset($tempPost['photo']);
                $form->removeElement('photo');
            }
            if (!$form->isValid($tempPost)) {
                $form->addElement($photoEl->setOrder($photoOrder));
                return;
            }
        } else {
            if (!$form->isValid($tempPost)) {
                return;
            }
        }


        if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.duplicatetitle', 1)) {
            $isListingExists = Engine_Api::_()->getDbTable('listings', 'sitereview')->getListingColumn(array('listingtype_id' => $listingtype_id, 'title' => $_POST['title']));

            if ($isListingExists) {
                $error = $this->view->translate("Please choose the different listing title as listing with same title already exists.");
                $error = Zend_Registry::get('Zend_Translate')->_($error);

                $form->getDecorator('errors')->setOption('escape', false);
                $form->addError($error);
                return;
            }
        }

        //CATEGORY IS REQUIRED FIELD
        if (empty($_POST['category_id']) || empty($sitereviewGetCategory)) {
            $error = $this->view->translate('Please complete Category field - it is required.');
            $error = Zend_Registry::get('Zend_Translate')->_($error);

            $form->getDecorator('errors')->setOption('escape', false);
            $form->addError($error);
            return;
        }
        $getFieldsType = Engine_Api::_()->sitereview()->getFieldsType('sitereviewlistingtype');
        $getListingRevType = Engine_Api::_()->getApi('listingType', 'sitereview')->getListingReviewType();
        $table = Engine_Api::_()->getItemTable('sitereview_listing');
        $db = $table->getAdapter();
        $db->beginTransaction();
        $user_level = $viewer->level_id;
        try {
            //Create sitereview
            if (!Engine_Api::_()->sitereview()->hasPackageEnable()) {
                $values = array_merge($form->getValues(), array(
                    'listingtype_id' => $listingtype_id,
                    'owner_type' => $viewer->getType(),
                    'owner_id' => $viewer_id,
                    'featured' => Engine_Api::_()->authorization()->getPermission($user_level, 'sitereview_listing', "featured_listtype_$listingtype_id"),
                    'sponsored' => Engine_Api::_()->authorization()->getPermission($user_level, 'sitereview_listing', "sponsored_listtype_$listingtype_id"),
                    'approved' => Engine_Api::_()->authorization()->getPermission($user_level, 'sitereview_listing', "approved_listtype_$listingtype_id")
                ));
            } else {
                $values = array_merge($form->getValues(), array(
                    'listingtype_id' => $listingtype_id,
                    'owner_type' => $viewer->getType(),
                    'owner_id' => $viewer_id,
                    'featured' => $package->featured,
                    'sponsored' => $package->sponsored
                ));

                if ($package->isFree()) {
                    $values['approved'] = $package->approved;
                } else
                    $values['approved'] = 0;
            }

            if (empty($values['listing_info'])) {
                $values = $listValues;
            } else {
                unset($values['listing_info']);
            }

            if (empty($values['subcategory_id'])) {
                $values['subcategory_id'] = 0;
            }

            if (empty($values['subsubcategory_id'])) {
                $values['subsubcategory_id'] = 0;
            }

            if (!empty($values['search']) && (empty($sitereviewViewType))) {
                if (!empty($sitereviewProfileOrder) && !empty($sitereviewListingTypeOrder) && ($sitereviewListingTypeOrder != $sitereviewProfileOrder)) {
                    $getHostTypeArray = array();
                    $requestListType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.request.listtype', false);
                    if (!empty($requestListType)) {
                        $getHostTypeArray = @unserialize($requestListType);
                    }
                    $getHostTypeArray[] = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
                    $getHostTypeArray = @serialize($getHostTypeArray);
                    Engine_Api::_()->getApi('settings', 'core')->setSetting('sitereview.request.listtype', $getHostTypeArray);

                    $getReviewType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.get.ltype', false);
                    if (empty($getReviewType)) {
                        $TempLtype[] = '2';
                        $TempLtype[] = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
                        $TempLtype[] = date("Y-m-d H:i:s");
                        $TempLtype[] = $_SERVER['REQUEST_URI'];
                        $TempLtype = @serialize($TempLtype);
                        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitereview.get.ltype', $TempLtype);
                    }
                    $values['search'] = 0;
                }
            }

            if (empty($sitereviewCategoryType)) {
                return;
            }

            if ($expiry_setting == 1 && $values['end_date_enable'] == 1) {
                // Convert times
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $end = strtotime($values['end_date']);
                date_default_timezone_set($oldTz);
                $values['end_date'] = date('Y-m-d H:i:s', $end);
            } elseif (isset($values['end_date'])) {
                unset($values['end_date']);
            }

            if (Engine_Api::_()->sitereview()->listBaseNetworkEnable()) {
                if (isset($values['networks_privacy']) && !empty($values['networks_privacy'])) {
                    if (in_array(0, $values['networks_privacy'])) {
                        unset($values['networks_privacy']);
                    }
                }
            }
            $values['gg_author_product_rating'] = (int)$values['owner_rating'];
            $sitereview = $table->createRow();
            $sitereview->setFromArray($values);

            if ($sitereview->approved) {
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $time = time();
                date_default_timezone_set($oldTz);

                $sitereview->approved_date = date('Y-m-d H:i:s', $time);
            }

            //START PACKAGE WORK
            if (!empty($sitereview->approved)) {
                if (isset($sitereview->pending))
                    $sitereview->pending = 0;

                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $time = time();
                date_default_timezone_set($oldTz);

                $sitereview->approved_date = date('Y-m-d H:i:s', $time);
                if (Engine_Api::_()->sitereview()->hasPackageEnable()) {
//            $sitereview->pending = 0;
                    $expirationDate = $package->getExpirationDate();
                    if (!empty($expirationDate))
                        $sitereview->expiration_date = date('Y-m-d H:i:s', $expirationDate);
                    else
                        $sitereview->expiration_date = '2250-01-01 00:00:00';
                }
            }
            //END PACKAGE WORK

            $sitereview->save();
            if (isset($sitereview->package_id))
                $sitereview->package_id = $package_id;
            $listing_id = $sitereview->listing_id;

            if ($this->_listingType->edit_creationdate && !$sitereview->draft) {
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $creation = strtotime($values['creation_date']);
                date_default_timezone_set($oldTz);
                $sitereview->creation_date = date('Y-m-d H:i:s', $creation);
                $sitereview->save();
            }
                $hasparent = false;
                $object = null;
            //START INTERGRATION EXTENSION WORK
            //START PAGE INTEGRATION WORK
            $page_id = $this->_getParam('page_id');
            if (!empty($page_id)) {
                $moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepageintegration');
                if (!empty($moduleEnabled)) {
                    $contentsTable = Engine_Api::_()->getDbtable('contents', 'sitepageintegration');
                    $row = $contentsTable->createRow();
                    $row->owner_id = $viewer_id;
                    $row->resource_owner_id = $sitereview->owner_id;
                    $row->page_id = $page_id;
                    $row->resource_type = 'sitereview_listing';
                    $row->resource_id = $sitereview->listing_id;
                    $row->save();
                }
                $hasparent = true;
                $object = Engine_Api::_()->getItem('sitepage_page', $page_id);
                if (Engine_Api::_()->sitepage()->isPageOwner($object) && Engine_Api::_()->sitepage()->isFeedTypePageEnable()) {
                    $activityFeedType = 'sitereview_admin_new_module_listtype_' . $listingtype_id;
                } elseif ($object->all_post || Engine_Api::_()->sitepage()->isPageOwner($object)) {
                    $activityFeedType = 'sitereview_new_module_listtype_' . $listingtype_id;
                }
            }
            //END PAGE INTEGRATION WORK
            //START BUSINESS INTEGRATION WORK
            $business_id = $this->_getParam('business_id');
            if (!empty($business_id)) {
                $moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessintegration');
                if (!empty($moduleEnabled)) {
                    $contentsTable = Engine_Api::_()->getDbtable('contents', 'sitebusinessintegration');
                    $row = $contentsTable->createRow();
                    $row->owner_id = $viewer_id;
                    $row->resource_owner_id = $sitereview->owner_id;
                    $row->business_id = $business_id;
                    $row->resource_type = 'sitereview_listing';
                    $row->resource_id = $sitereview->listing_id;
                    $row->save();
                }
                $hasparent = true;
                $object = Engine_Api::_()->getItem('sitebusiness_business', $business_id);
                if (Engine_Api::_()->sitebusiness()->isBusinessOwner($object) && Engine_Api::_()->sitebusiness()->isFeedTypeBusinessEnable()) {
                    $activityFeedType = 'sitereview_admin_new_module_listtype_' . $listingtype_id;
                } elseif ($object->all_post || Engine_Api::_()->sitebusiness()->isBusinessOwner($object)) {
                    $activityFeedType = 'sitereview_new_module_listtype_' . $listingtype_id;
                }
            }
            //END BUSINESS INTEGRATION WORK
            //START GROUP INTEGRATION WORK
            $group_id = $this->_getParam('group_id');
            if (!empty($group_id)) {
                $moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupintegration');
                if (!empty($moduleEnabled)) {
                    $contentsTable = Engine_Api::_()->getDbtable('contents', 'sitegroupintegration');
                    $row = $contentsTable->createRow();
                    $row->owner_id = $viewer_id;
                    $row->resource_owner_id = $sitereview->owner_id;
                    $row->group_id = $group_id;
                    $row->resource_type = 'sitereview_listing';
                    $row->resource_id = $sitereview->listing_id;
                    $row->save();
                }
                $hasparent = true;
                $object = Engine_Api::_()->getItem('sitegroup_group', $group_id);
                if (Engine_Api::_()->sitegroup()->isGroupOwner($object) && Engine_Api::_()->sitegroup()->isFeedTypeGroupEnable()) {
                    $activityFeedType = 'sitereview_admin_new_module_listtype_' . $listingtype_id;
                } elseif ($object->all_post || Engine_Api::_()->sitegroup()->isGroupOwner($object)) {
                    $activityFeedType = 'sitereview_new_module_listtype_' . $listingtype_id;
                }
            }
            //END GROUP INTEGRATION WORK
            //START STORE INTEGRATION WORK
            $store_id = $this->_getParam('store_id');
            if (!empty($store_id)) {
                $moduleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitestoreintegration');
                if (!empty($moduleEnabled)) {
                    $contentsTable = Engine_Api::_()->getDbtable('contents', 'sitestoreintegration');
                    $row = $contentsTable->createRow();
                    $row->owner_id = $viewer_id;
                    $row->resource_owner_id = $sitereview->owner_id;
                    $row->store_id = $store_id;
                    $row->resource_type = 'sitereview_listing';
                    $row->resource_id = $sitereview->listing_id;
                    $row->save();
                }
                $hasparent = true;
                $object = Engine_Api::_()->getItem('sitestore_store', $store_id);
            }
            //END STORE INTEGRATION WORK
            //END INTERGRATION EXTENSION WORK
            //SET PHOTO
            if (!empty($values['photo'])) {
                $sitereview->setPhoto($form->photo);
                $albumTable = Engine_Api::_()->getDbtable('albums', 'sitereview');
                $album_id = $albumTable->update(array('photo_id' => $sitereview->photo_id), array('listing_id = ?' => $sitereview->listing_id));
            }

            //ADDING TAGS
            $keywords = '';
            if (isset($values['tags']) && !empty($values['tags'])) {
                $tags = preg_split('/[,]+/', $values['tags']);
                $tags = array_filter(array_map("trim", $tags));
                $sitereview->tags()->addTagMaps($viewer, $tags);

                foreach ($tags as $tag) {
                    $keywords .= " $tag";
                }
            }

            //SAVE CUSTOM VALUES AND PROFILE TYPE VALUE
            $customfieldform = $form->getSubForm('fields');
            $customfieldform->setItem($sitereview);
            $customfieldform->saveValues();

            $categoryIds = array();
            $categoryIds[] = $sitereview->category_id;
            $categoryIds[] = $sitereview->subcategory_id;
            $categoryIds[] = $sitereview->subsubcategory_id;
            $sitereview->profile_type = Engine_Api::_()->getDbTable('categories', 'sitereview')->getProfileType($categoryIds, 0, 'profile_type');

            //NOT SEARCHABLE IF SAVED IN DRAFT MODE
            if (!empty($sitereview->draft)) {
                $sitereview->search = 0;
            }

            $sitereview->save();

            //PRIVACY WORK
            $sitereview_flag_info = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.flag.info', 0);
            if (empty($sitereview_flag_info)) {
                $sitereview_host = convert_uuencode($sitereview_host);
                Engine_Api::_()->getApi('settings', 'core')->setSetting('sitereview.view.attempt', $sitereview_host);
            }

            $auth = Engine_Api::_()->authorization()->context;

            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

            if (empty($values['auth_view'])) {
                $values['auth_view'] = "everyone";
            }

            if (empty($values['auth_comment'])) {
                $values['auth_comment'] = "everyone";
            }

            $viewMax = array_search($values['auth_view'], $roles);
            $commentMax = array_search($values['auth_comment'], $roles);

            foreach ($roles as $i => $role) {
                $auth->setAllowed($sitereview, $role, "view_listtype_$listingtype_id", ($i <= $viewMax));
                $auth->setAllowed($sitereview, $role, "view", ($i <= $viewMax));
                $auth->setAllowed($sitereview, $role, "comment_listtype_$listingtype_id", ($i <= $commentMax));
                $auth->setAllowed($sitereview, $role, "comment", ($i <= $commentMax));
            }

            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered');

            if (empty($values['auth_topic'])) {
                $values['auth_topic'] = "registered";
            }

            if (empty($values['auth_photo'])) {
                $values['auth_photo'] = "registered";
            }

            if (!isset($values['auth_video']) && empty($values['auth_video'])) {
                $values['auth_video'] = "registered";
            }

            if (isset($values['auth_event']) && empty($values['auth_event'])) {
                $values['auth_event'] = "registered";
            }

            if (isset($values['auth_event']) && !empty($values['auth_event'])) {
                $eventMax = array_search($values['auth_event'], $roles);
                foreach ($roles as $i => $roles) {
                    $auth->setAllowed($sitereview, $roles, "event_listtype_$listingtype_id", ($i <= $eventMax));
                }
            }

            if (isset($values['auth_sprcreate']) && empty($values['auth_sprcreate'])) {
                $values['auth_sprcreate'] = "registered";
            }

            if (isset($values['auth_sprcreate']) && !empty($values['auth_sprcreate'])) {
                $projectMax = array_search($values['auth_sprcreate'], $roles);
                foreach ($roles as $i => $roles) {
                    $auth->setAllowed($sitereview, $roles, "sprcreate_listtype_$listingtype_id", ($i <= $projectMax));
                }
            }

            $topicMax = array_search($values['auth_topic'], $roles);
            $photoMax = array_search($values['auth_photo'], $roles);
            $videoMax = array_search($values['auth_video'], $roles);
            foreach ($roles as $i => $roles) {
                $auth->setAllowed($sitereview, $roles, "topic_listtype_$listingtype_id", ($i <= $topicMax));
                $auth->setAllowed($sitereview, $roles, "photo_listtype_$listingtype_id", ($i <= $photoMax));
                $auth->setAllowed($sitereview, $roles, "video_listtype_$listingtype_id", ($i <= $videoMax));
            }

            //COMMIT
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }

        $tableOtherinfo = Engine_Api::_()->getDbTable('otherinfo', 'sitereview');
        $db->beginTransaction();
        try {
            $row = $tableOtherinfo->getOtherinfo($listing_id);
            $overview = '';
            if (isset($values['overview'])) {
                $overview = $values['overview'];
            }
            if (empty($row))
                Engine_Api::_()->getDbTable('otherinfo', 'sitereview')->insert(array(
                    'listing_id' => $listing_id,
                    'overview' => $overview
                )); //COMMIT
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }

        if (!empty($listing_id)) {
            $sitereview->setLocation();
        }

        $db->beginTransaction();
        try {

            if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewpaidlisting'))
                $sitereview_pending = $sitereview->pending;
            else
                $sitereview_pending = 0;

            if ($sitereview->draft == 0 && $sitereview->search && time() >= strtotime($sitereview->creation_date) && empty($sitereview_pending) && $sitereview->approved) {
                if(!empty($hasparent) && !empty($object)){
                    $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $object, $activityFeedType);
                } else {
                    $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $sitereview, 'sitereview_new_listtype_' . $listingtype_id);
                }
                
            if ($action != null) {
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sitereview);
                }
            }

            $users = Engine_Api::_()->getDbtable('editors', 'sitereview')->getAllEditors($listingtype_id, 0, 1);

            foreach ($users as $user_ids) {

                $subjectOwner = Engine_Api::_()->getItem('user', $user_ids->user_id);

                if (!($subjectOwner instanceof User_Model_User)) {
                    continue;
                }

                $host = $_SERVER['HTTP_HOST'];
                $newVar = _ENGINE_SSL ? 'https://' : 'http://';
                $object_link = $newVar . $host . $sitereview->getHref();

                Engine_Api::_()->getApi('mail', 'core')->sendSystem($subjectOwner->email, 'SITEREVIEW_LISTING_CREATION_EDITOR', array(
                    'listing_type' => strtolower($this->_listingType->title_singular),
                    'object_link' => $object_link,
                    'object_title' => $sitereview->getTitle(),
                    'object_description' => $sitereview->getDescription(),
                    'queue' => true
                ));
            }

            //SEND NOTIFICATIONS FOR SUBSCRIBERS
            if ($this->_listingType->subscription)
                Engine_Api::_()->getDbtable('subscriptions', 'sitereview')->sendNotifications($sitereview);

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }

        //UPDATE KEYWORDS IN SEARCH TABLE
        if (!empty($keywords)) {
            Engine_Api::_()->getDbTable('search', 'core')->update(array('keywords' => $keywords), array('type = ?' => 'sitereview_listing', 'id = ?' => $sitereview->listing_id));
        }

        //OVERVIEW IS ENABLED OR NOT
        $allowOverview = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitereview_listing', "overview_listtype_$listingtype_id");

        //EDIT IS ENABLED OR NOT
        $alloweEdit = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitereview_listing', "edit_listtype_$listingtype_id");


        //CHECK FOR LEVEL SETTING
        if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
            //REDIRECTION TO DASHBOARD PAGES - CONDITIONS
            if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.create.redirection', 0) && $alloweEdit) {
                if (Engine_Api::_()->sitereview()->hasPackageEnable()) {
                    if (Engine_Api::_()->sitereviewpaidlisting()->allowPackageContent($sitereview->package_id, "overview") && $allowOverview && !empty($this->_listingType->overview) && $alloweEdit) {
                        return $this->_helper->redirector->gotoRoute(array('action' => 'overview', 'listing_id' => $sitereview->listing_id, 'saved' => '1'), "sitereview_specific_listtype_$listingtype_id", true);
                    } else if (Engine_Api::_()->sitereviewpaidlisting()->allowPackageContent($sitereview->package_id, "photo") && Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitereview_listing', "photo_listtype_$listingtype_id")) {
                        return $this->_helper->redirector->gotoRoute(array('listing_id' => $sitereview->listing_id, 'saved' => '1'), "sitereview_albumspecific_listtype_$listingtype_id", true);
                    } else if (Engine_Api::_()->sitereviewpaidlisting()->allowPackageContent($sitereview->package_id, "video") && Engine_Api::_()->sitereview()->allowVideo($sitereview, $viewer)) {
                        return $this->_helper->redirector->gotoRoute(array('listing_id' => $sitereview->listing_id, 'saved' => '1'), "sitereview_videospecific_listtype_$listingtype_id", true);
                    } else {
                        return $this->_helper->redirector->gotoRoute(array('listing_id' => $sitereview->listing_id, 'slug' => $sitereview->getSlug()), "sitereview_entry_view_listtype_$listingtype_id", true);
                    }
                } else {
                    if ($allowOverview && !empty($this->_listingType->overview) && $alloweEdit) {
                        return $this->_helper->redirector->gotoRoute(array('action' => 'overview', 'listing_id' => $sitereview->listing_id, 'saved' => '1'), "sitereview_specific_listtype_$listingtype_id", true);
                    } else if (Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sitereview_listing', "photo_listtype_$listingtype_id")) {
                        return $this->_helper->redirector->gotoRoute(array('listing_id' => $sitereview->listing_id, 'saved' => '1'), "sitereview_albumspecific_listtype_$listingtype_id", true);
                    } else if (Engine_Api::_()->sitereview()->allowVideo($sitereview, $viewer)) {
                        return $this->_helper->redirector->gotoRoute(array('listing_id' => $sitereview->listing_id, 'saved' => '1'), "sitereview_videospecific_listtype_$listingtype_id", true);
                    } else {
                        return $this->_helper->redirector->gotoRoute(array('listing_id' => $sitereview->listing_id, 'slug' => $sitereview->getSlug()), "sitereview_entry_view_listtype_$listingtype_id", true);
                    }
                }
            } else {//REDIRECTION TO PROFILE PAGE
                return $this->_helper->redirector->gotoRoute(array('listing_id' => $sitereview->listing_id, 'slug' => $sitereview->getSlug()), "sitereview_entry_view_listtype_$listingtype_id", true);
            }
        } else {
            //REDIRECTION TO DASHBOARD.
            if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.create.redirection', 0) && $alloweEdit) {
                return $this->_forwardCustom('success', 'utility', 'core', array(
                            'redirect' => $this->_helper->url->url(array('action' => 'edit', 'listing_id' => $sitereview->listing_id), "sitereview_specific_listtype_$listingtype_id", true),
                            'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your Listing has been created successfully.')),
                ));
            } else {//REDIRECTION TO PROFILE PAGE OF LISTING.
                return $this->_forwardCustom('success', 'utility', 'core', array(
                            'redirect' => $this->_helper->url->url(array('listing_id' => $sitereview->listing_id, 'slug' => $sitereview->getSlug()), "sitereview_entry_view_listtype_$listingtype_id", true),
                            'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your Listing has been created successfully.')),
                ));
            }
        }
    }
    
    public function editAction() {

        if (!$this->_helper->requireUser()->isValid())
            return;

        $this->view->TabActive = "edit";
        $listValues = array();
        $this->view->listing_id = $listing_id = $this->_getParam('listing_id');
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $this->view->sitereview = $sitereview = Engine_Api::_()->getItem('sitereview_listing', $listing_id);
        if (empty($sitereview)) {
            return $this->_forwardCustom('notfound', 'error', 'core');
        }
        // $previous_location = $sitereview->location;
        //GET TINYMCE SETTINGS
        $this->view->upload_url = "";
        $albumEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album');
        if (Engine_Api::_()->authorization()->isAllowed('album', $viewer, 'create') && $albumEnabled) {
            $this->view->upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'upload-photo'), 'sitereview_general_listtype_' . $this->_listingType->listingtype_id, true);
        }

        //GET LISTING TYPE ID
        $this->view->listingtype_id = $listingtype_id = $this->_listingType->listingtype_id;

        $this->view->listing_singular_uc = ucfirst($this->_listingType->title_singular);
        $this->view->show_editor = $this->_listingType->show_editor;

        $this->view->category_edit = $this->_listingType->category_edit;

        //SITEMOBILE_MODULE_NOT_SUPPORT_DESC_FOR_SOMEPAGES
//    if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode') && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewpaidlisting') && Engine_Api::_()->sitereview()->hasPackageEnable()) {
//      Engine_API::_()->sitemobile()->setupRequestError();
//    }
        //if(!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
        $this->_helper->content
                ->setContentName("sitereview_index_edit_listtype_$listingtype_id")
                //->setNoRender()
                ->setEnabled();

        //}

        $sitereviewinfo = $sitereview->toarray();
        $this->view->category_id = $previous_category_id = $sitereview->category_id;
        $this->view->subcategory_id = $subcategory_id = $sitereview->subcategory_id;
        $this->view->subsubcategory_id = $subsubcategory_id = $sitereview->subsubcategory_id;

        $row = Engine_Api::_()->getDbtable('categories', 'sitereview')->getCategory($subcategory_id);
        $this->view->subcategory_name = "";
        if (!empty($row)) {
            $this->view->subcategory_name = $row->category_name;
        }

        if (!Engine_Api::_()->core()->hasSubject('sitereview_listing')) {
            Engine_Api::_()->core()->setSubject($sitereview);
        }

        if (!$this->_helper->requireSubject()->isValid())
            return;

        if (!$this->_helper->requireAuth()->setAuthParams($sitereview, $viewer, "edit_listtype_$listingtype_id")->isValid()) {
            return;
        }

        //GET DEFAULT PROFILE TYPE ID
        $this->view->defaultProfileId = $defaultProfileId = Engine_Api::_()->getDbTable('metas', 'sitereview')->defaultProfileId();

        //GET PROFILE MAPPING ID
        $categoryIds = array();
        $categoryIds[] = $sitereview->category_id;
        $categoryIds[] = $sitereview->subcategory_id;
        $categoryIds[] = $sitereview->subsubcategory_id;
        $this->view->profileType = $previous_profile_type = Engine_Api::_()->getDbtable('categories', 'sitereview')->getProfileType($categoryIds, 0, 'profile_type');

        if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
            $categoryIds = array();
            $categoryIds[] = $_POST['category_id'];
            if (isset($_POST['subcategory_id']) && !empty($_POST['subcategory_id'])) {
                $categoryIds[] = $_POST['subcategory_id'];
            }
            if (isset($_POST['subsubcategory_id']) && !empty($_POST['subsubcategory_id'])) {
                $categoryIds[] = $_POST['subsubcategory_id'];
            }
            $this->view->profileType = $previous_profile_type = Engine_Api::_()->getDbtable('categories', 'sitereview')->getProfileType($categoryIds, 0, 'profile_type');
        }

        //MAKE FORM
        $this->view->form = $form = new Sdparentalguide_Form_Listing_Edit(array('item' => $sitereview, 'defaultProfileId' => $defaultProfileId));

        $inDraft = 1;
        if (empty($sitereview->draft)) {
            $inDraft = 0;
            $form->removeElement('draft');
        }

        $form->removeElement('photo');
        $this->view->expiry_setting = $expiry_setting = Engine_Api::_()->sitereview()->expirySettings($listingtype_id);

        //SAVE SITEREVIEW ENTRY
        if (!$this->getRequest()->isPost()) {

            if (isset($this->_listingType->show_tag) && $this->_listingType->show_tag) {
                //prepare tags
                $sitereviewTags = $sitereview->tags()->getTagMaps();
                $tagString = '';

                foreach ($sitereviewTags as $tagmap) {

                    if ($tagString != '')
                        $tagString .= ', ';
                    $tagString .= $tagmap->getTag()->getTitle();
                }

                $this->view->tagNamePrepared = $tagString;
                $form->tags->setValue($tagString);
            }

            
            $form->populate($sitereview->toArray());            
            $form->populate(array('owner_rating' => $sitereview->gg_author_product_rating));

            if ($this->_listingType->edit_creationdate && $sitereview->creation_date && ($sitereview->draft || (!$sitereview->draft && (time() < strtotime($sitereview->creation_date))))) {

                $creation_date = strtotime($sitereview->creation_date);
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $creation_date = date('Y-m-d H:i:s', $creation_date);
                date_default_timezone_set($oldTz);

                $form->populate(array(
                    'creation_date' => $creation_date,
                ));
            }

            if ($sitereview->end_date && $sitereview->end_date != '0000-00-00 00:00:00') {
                $form->end_date_enable->setValue(1);
                // Convert and re-populate times
                $end = strtotime($sitereview->end_date);
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $end = date('Y-m-d H:i:s', $end);
                date_default_timezone_set($oldTz);

                $form->populate(array(
                    'end_date' => $end,
                ));
            } else if (empty($sitereview->end_date) || $sitereview->end_date == '0000-00-00 00:00:00') {
                $date = (string) date('Y-m-d');
                $form->end_date->setValue($date . ' 00:00:00');
            }

            $auth = Engine_Api::_()->authorization()->context;
            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

            foreach ($roles as $role) {
                if ($form->auth_view) {
                    if (1 == $auth->isAllowed($sitereview, $role, "view_listtype_$listingtype_id")) {
                        $form->auth_view->setValue($role);
                    }
                }

                if ($form->auth_comment) {
                    if (1 == $auth->isAllowed($sitereview, $role, "comment_listtype_$listingtype_id")) {
                        $form->auth_comment->setValue($role);
                    }
                }
            }

            $roles_photo = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered');

            foreach ($roles_photo as $role_topic) {
                if ($form->auth_topic) {
                    if (1 == $auth->isAllowed($sitereview, $role_topic, "topic_listtype_$listingtype_id")) {
                        $form->auth_topic->setValue($role_topic);
                    }
                }
            }

            foreach ($roles_photo as $role_photo) {
                if ($form->auth_photo) {
                    if (1 == $auth->isAllowed($sitereview, $role_photo, "photo_listtype_$listingtype_id")) {
                        $form->auth_photo->setValue($role_photo);
                    }
                }
            }

            foreach ($roles_photo as $role_photo) {
                if (isset($form->auth_event) && $form->auth_event) {
                    if (1 == $auth->isAllowed($sitereview, $role_photo, "event_listtype_$listingtype_id")) {
                        $form->auth_event->setValue($role_photo);
                    }
                }
            }

            foreach ($roles_photo as $role_photo) {
                if (isset($form->auth_sprcreate) && $form->auth_sprcreate) {
                    if (1 == $auth->isAllowed($sitereview, $role_photo, "sprcreate_listtype_$listingtype_id")) {
                        $form->auth_sprcreate->setValue($role_photo);
                    }
                }
            }

            $videoEnable = Engine_Api::_()->sitereview()->enableVideoPlugin();
            if ($videoEnable) {
                $roles_video = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered');
                foreach ($roles_video as $role_video) {
                    if ($form->auth_video) {
                        if (1 == $auth->isAllowed($sitereview, $role_video, "video_listtype_$listingtype_id")) {
                            $form->auth_video->setValue($role_video);
                        }
                    }
                }
            }

            if (Engine_Api::_()->sitereview()->listBaseNetworkEnable()) {
                if (empty($sitereview->networks_privacy)) {
                    $form->networks_privacy->setValue(array(0));
                }
            }
            return;
        }

        //FORM VALIDATION
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereview.duplicatetitle', 1) && $sitereview->title != $_POST['title']) {
            $isListingExists = Engine_Api::_()->getDbTable('listings', 'sitereview')->getListingColumn(array('listingtype_id' => $listingtype_id, 'title' => $_POST['title']));
            if ($isListingExists) {
                $error = $this->view->translate("Please choose the different listing title as listing with same title already exists.");
                $error = Zend_Registry::get('Zend_Translate')->_($error);

                $form->getDecorator('errors')->setOption('escape', false);
                $form->addError($error);
                return;
            }
        }

        //CATEGORY IS REQUIRED FIELD
        if (isset($_POST['category_id']) && empty($_POST['category_id'])) {
            $error = $this->view->translate('Please complete Category field - it is required.');
            $error = Zend_Registry::get('Zend_Translate')->_($error);

            $form->getDecorator('errors')->setOption('escape', false);
            $form->addError($error);
            return;
        }

        //GET FORM VALUES
        $values = $form->getValues();

        if (empty($values['listing_info'])) {
            $values = $listValues;
        } else {
            unset($values['listing_info']);
        }

        $tags = preg_split('/[,]+/', $values['tags']);
        $tags = array_filter(array_map("trim", $tags));

        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {

            if (Engine_Api::_()->sitereview()->listBaseNetworkEnable() && isset($values['networks_privacy']) && !empty($values['networks_privacy']) && in_array(0, $values['networks_privacy'])) {
                $values['networks_privacy'] = new Zend_Db_Expr('NULL');
                $form->networks_privacy->setValue(array(0));
            }
            if ($expiry_setting == 1 && $values['end_date_enable'] == 1) {
                // Convert times
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $end = strtotime($values['end_date']);
                date_default_timezone_set($oldTz);
                $values['end_date'] = date('Y-m-d H:i:s', $end);
            } elseif ($expiry_setting == 1 && isset($values['end_date'])) {
                $values['end_date'] = NULL;
            } elseif (isset($values['end_date'])) {
                unset($values['end_date']);
            }

            if ($this->_listingType->edit_creationdate && $sitereview->creation_date && ($sitereview->draft || (!$sitereview->draft && (time() < strtotime($sitereview->creation_date))))) {
                $oldTz = date_default_timezone_get();
                date_default_timezone_set($viewer->timezone);
                $creation = strtotime($values['creation_date']);
                date_default_timezone_set($oldTz);
                $values['creation_date'] = date('Y-m-d H:i:s', $creation);
            }
            $values['gg_author_product_rating'] = $values['owner_rating'];
            $sitereview->setFromArray($values);
            $sitereview->modified_date = date('Y-m-d H:i:s');
            $sitereview->tags()->setTagMaps($viewer, $tags);
            $sitereview->save();

//       if (empty($sitereview->location)) {
//         Engine_Api::_()->getDbtable('locations', 'sitereview')->delete(array('listing_id =?' => $sitereview->listing_id));
//       } elseif (!empty($sitereview->location) && ($sitereview->location != $previous_location)) {
//         $sitereview->setLocation();
//       }
            //SAVE CUSTOM FIELDS
            $getListingRevType = Engine_Api::_()->getApi('listingType', 'sitereview')->getListingReviewType();
            $customfieldform = $form->getSubForm('fields');
            $customfieldform->setItem($sitereview);
            $customfieldform->saveValues();
            if ($customfieldform->getElement('submit')) {
                $customfieldform->removeElement('submit');
            }

            if (isset($values['category_id']) && !empty($values['category_id'])) {
                $categoryIds = array();
                $categoryIds[] = $sitereview->category_id;
                $categoryIds[] = $sitereview->subcategory_id;
                $categoryIds[] = $sitereview->subsubcategory_id;
                $sitereview->profile_type = Engine_Api::_()->getDbtable('categories', 'sitereview')->getProfileType($categoryIds, 0, 'profile_type');
                if ($sitereview->profile_type != $previous_profile_type) {

                    $fieldvalueTable = Engine_Api::_()->fields()->getTable('sitereview_listing', 'values');
                    $fieldvalueTable->delete(array('item_id = ?' => $sitereview->listing_id));

                    Engine_Api::_()->fields()->getTable('sitereview_listing', 'search')->delete(array(
                        'item_id = ?' => $sitereview->listing_id,
                    ));

                    if (!empty($sitereview->profile_type) && !empty($previous_profile_type)) {
                        //PUT NEW PROFILE TYPE
                        $fieldvalueTable->insert(array(
                            'item_id' => $sitereview->listing_id,
                            'field_id' => $defaultProfileId,
                            'index' => 0,
                            'value' => $sitereview->profile_type,
                        ));
                    }
                }
                $sitereview->save();
            }

            //NOT SEARCHABLE IF SAVED IN DRAFT MODE
            if (!empty($sitereview->draft)) {
                $sitereview->search = 0;
                $sitereview->save();
            }

            if ($sitereview->draft == 0 && $sitereview->search && $inDraft && time() >= strtotime($sitereview->creation_date)) {
                $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($sitereview->getOwner(), $sitereview, 'sitereview_new_listtype_' . $listingtype_id);

                if ($action != null) {
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sitereview);
                }
            }

            //CREATE AUTH STUFF HERE
            $auth = Engine_Api::_()->authorization()->context;

            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

            if (empty($values['auth_view'])) {
                $values['auth_view'] = "everyone";
            }

            if (empty($values['auth_comment'])) {
                $values['auth_comment'] = "everyone";
            }

            $viewMax = array_search($values['auth_view'], $roles);
            $commentMax = array_search($values['auth_comment'], $roles);

            foreach ($roles as $i => $role) {
                $auth->setAllowed($sitereview, $role, "view_listtype_$listingtype_id", ($i <= $viewMax));
                $auth->setAllowed($sitereview, $role, "view", ($i <= $viewMax));
                $auth->setAllowed($sitereview, $role, "comment_listtype_$listingtype_id", ($i <= $commentMax));
                $auth->setAllowed($sitereview, $role, "comment", ($i <= $commentMax));
            }

            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered');

            if ($values['auth_topic'])
                $auth_topic = $values['auth_topic'];
            else
                $auth_topic = "registered";
            $topicMax = array_search($auth_topic, $roles);

            foreach ($roles as $i => $role) {
                $auth->setAllowed($sitereview, $role, "topic_listtype_$listingtype_id", ($i <= $topicMax));
            }

            if ($values['auth_photo'])
                $auth_photo = $values['auth_photo'];
            else
                $auth_photo = "registered";
            $photoMax = array_search($auth_photo, $roles);

            foreach ($roles as $i => $role) {
                $auth->setAllowed($sitereview, $role, "photo_listtype_$listingtype_id", ($i <= $photoMax));
            }

            $roles_video = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered');
            if (!isset($values['auth_video']) && empty($values['auth_video'])) {
                $values['auth_video'] = "registered";
            }

            $videoMax = array_search($values['auth_video'], $roles_video);
            foreach ($roles_video as $i => $role_video) {
                $auth->setAllowed($sitereview, $role_video, "video_listtype_$listingtype_id", ($i <= $videoMax));
            }

            if (isset($values['auth_event'])) {
                if ($values['auth_event'])
                    $auth_event = $values['auth_event'];
                else
                    $auth_event = "registered";
                $eventMax = array_search($auth_event, $roles);

                foreach ($roles as $i => $role) {
                    $auth->setAllowed($sitereview, $role, "event_listtype_$listingtype_id", ($i <= $eventMax));
                }
            }

            if (isset($values['auth_sprcreate'])) {
                if ($values['auth_sprcreate'])
                    $auth_sprcreate = $values['auth_sprcreate'];
                else
                    $auth_sprcreate = "registered";
                $projectMax = array_search($auth_sprcreate, $roles);

                foreach ($roles as $i => $role) {
                    $auth->setAllowed($sitereview, $role, "sprcreate_listtype_$listingtype_id", ($i <= $projectMax));
                }
            }

            if ($previous_category_id != $sitereview->category_id) {
                Engine_Api::_()->getDbtable('ratings', 'sitereview')->editListingCategory($sitereview->listing_id, $previous_category_id, $sitereview->category_id, $sitereview->getType());
            }

            //SEND NOTIFICATIONS FOR SUBSCRIBERS
            if ($this->_listingType->subscription)
                Engine_Api::_()->getDbtable('subscriptions', 'sitereview')->sendNotifications($sitereview);

            $db->commit();
            $this->view->form = $form->addNotice(Zend_Registry::get('Zend_Translate')->_('Your changes have been saved successfully.'));
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        $sitereview->setLocation();
        $db->beginTransaction();
        try {
            $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
            foreach ($actionTable->getActionsByObject($sitereview) as $action) {
                $actionTable->resetActivityBindings($action);
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
