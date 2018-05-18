<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereviewlistingtype
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminListingtypesController.php 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereviewlistingtype_AdminListingtypesController extends Core_Controller_Action_Admin {

  //ACTION FOR MANAGING THE PROFILE-CATEGORY MAPPING
  public function manageAction() {

    //REMOVE CACHEING OF LISTINGTYPE AND CATEGORIES HIERARCHY
    $cache = Zend_Registry::get('Zend_Cache');
    $cache->remove('listtype_categories');      
    $cache->remove('categories_home_sidebar');
    $cache->remove('listtype_categories_listingtypes');
      
    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sitereview_admin_main', array(), 'sitereview_admin_main_listingtypes');

    $this->view->showListingTypeLink = false;
    $saved = $this->_getParam('saved');
    if (!empty($saved)) {
      $this->view->success = 1;
    }

    //GET LISTING TYPE TABLE
    $listingTypeTable = Engine_Api::_()->getDbTable('listingtypes', 'sitereview');

    //CHECK POST
    if ($this->getRequest()->isPost()) {

      //BEGIN TRANSCATION
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      $values = $_POST;

      try {
        foreach ($values['order'] as $key => $value) {
          $listingTypeTable->update(array('order' => $key + 1), array('listingtype_id = ?' => (int) $value));
        }
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }

    include_once APPLICATION_PATH . '/application/modules/Sitereview/controllers/license/license2.php';
  }

  //ACTION FOR ADDING THE LISTING TYPE
  public function createAction() {

    //INCREASE THE MEMORY ALLOCATION SIZE AND INFINITE SET TIME OUT
    ini_set('memory_limit', '1024M');
    set_time_limit(0);
    $getFormArray = array();

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sitereview_admin_main', array(), 'sitereview_admin_main_listingtypes');

    $listingTypeInfo = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereviewlistingtype.type.info', false);
    $this->view->hasLanguageDirectoryPermissions = $hasLanguageDirectoryPermissions = Engine_Api::_()->getApi('language', 'sitereview')->hasDirectoryPermissions();
    if ((!$hasLanguageDirectoryPermissions)) {
      return;
    }
    //CREATE FORM
    $this->view->form = $form = new Sitereviewlistingtype_Form_Admin_Listingtypes_Create();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $slug_singular = $_POST['slug_singular'];
      $slug_plural = $_POST['slug_plural'];
      $title_singular = $_POST['title_singular'];
      $title_plural = $_POST['title_plural'];

      if (($slug_singular == $slug_plural) || empty($listingTypeInfo)) {
        $error = $this->view->translate("Singular Slug and Plural Slug can't be same.");
        $error = Zend_Registry::get('Zend_Translate')->_($error);

        $form->getDecorator('errors')->setOption('escape', false);
        $form->addError($error);
        return;
      }

      $listingtype_id = Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->checkListingSlug($slug_singular);
      if (!empty($listingtype_id) || empty($listingTypeInfo)) {
        $error = $this->view->translate("Please choose the different 'Singular Slug', you have already created the same slug.");
        $error = Zend_Registry::get('Zend_Translate')->_($error);

        $form->getDecorator('errors')->setOption('escape', false);
        $form->addError($error);
        return;
      }

      $listingtype_id = Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->checkListingSlug($slug_plural);
      if (!empty($listingtype_id) || empty($listingTypeInfo)) {
        $error = $this->view->translate("Please choose the different 'Plural Slug', you have already created the same slug.");
        $error = Zend_Registry::get('Zend_Translate')->_($error);

        $form->getDecorator('errors')->setOption('escape', false);
        $form->addError($error);
        return;
      }
    
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
        $listingModType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereviewlistingtype.mod.type', false);
        $listingType = Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->createRow();
        $values = !empty($listingModType) ? $form->getValues() : $getFormArray;
        
        if(isset ($_POST['package'])) {
          if(!empty($_POST['package']))
          $values['expiry'] = '0';
        }
      
        if ($values['template_type'] != 'default_type') {
          $values['reference'] = $values['template_type'];
        }
        $listingType->setFromArray($values);
        $listingType->save();
        // Set photo
        if (!empty($values['photo'])) {
          $listingType->setPhoto($form->photo);
        }


        // Integrate Sitereviewlisting with Suggestion plugin.
        $isSuggestionEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled("suggestion");
        if (!empty($isSuggestionEnabled)) {
          $notificationLanguage = '';
          $tempReviewTitle = $values["title_singular"];
          $tempReviewTitle = strtolower($tempReviewTitle);
          $getReviewTitle = @ucfirst($tempReviewTitle);
          $suggSettingId = array("default" => 1, "listing_id" => $listingType->listingtype_id);
          $suggNotificationType = "sitereview_" . $listingType->listingtype_id . "_suggestion";
          $suggNotificationBody = '{item:$subject} has suggested to you a {item:$object:' . $tempReviewTitle . '}.';
          $queryObj = Zend_Db_Table_Abstract::getDefaultAdapter();
          $suggestionModuleTable = Engine_Api::_()->getItemTable('suggestion_modinfo');
          $suggestionModuleTableName = $suggestionModuleTable->info('name');

          // Insert Notification Type in notification table.
          $queryObj->query("INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type` , `module` , `body` , `is_request` ,`handler`) VALUES ('$suggNotificationType', 'suggestion', '$suggNotificationBody', 1, 'suggestion.widget.get-notify')");

          // Insert in Mail Template Table.
          $emailtemType = 'notify_' . $suggNotificationType;
          $queryObj->query("INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES ('$emailtemType', 'suggestion', '[suggestion_sender], [suggestion_entity], [email], [link]'
);");

          // Show "Suggest to Friend" link on "Listing Profile Page".
          $queryObj->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name` , `module` , `label` , `plugin` ,`params`, `menu`, `enabled`, `custom`, `order`) VALUES ("sitereview_gutter_suggesttofriend_' . $listingType->listingtype_id . '", "suggestion", "Suggest to Friends", \'Suggestion_Plugin_Menus::showSitereview\', \'{"route":"suggest_to_friend_link","class":"buttonlink icon_review_friend_suggestion smoothbox", "listing_id": "' . $listingType->listingtype_id . '", "type":"popup"}\', "sitereview_gutter_listtype_' . $listingType->listingtype_id . '", 1, 0, 999 )');
          // "sitereview_gutter_listtype_$listingtype_id";
          // Insert in Language Files.
          $language1 = array('You have a ' . $getReviewTitle . ' suggestion' => 'You have a ' . $getReviewTitle . ' suggestion');
          $language2 = array('View all ' . $getReviewTitle . ' suggestions' => 'View all ' . $getReviewTitle . ' suggestions');
          $language3 = array('This ' . $tempReviewTitle . ' was suggested by' => 'This ' . $tempReviewTitle . ' was suggested by');

          $temprequestWidgetLan = "sitereview " . $listingType->listingtype_id . " suggestion";
          $requestTab = array(
              "%s " . $temprequestWidgetLan => array("%s " . strtolower($getReviewTitle) . " suggestion", "%s " . strtolower($getReviewTitle) . " suggestions")
          );

          $languageModTitle = "SITEREVIEW_" . $listingType->listingtype_id;
          $makeEmailArray = array(
              "_EMAIL_NOTIFY_" . $languageModTitle . "_SUGGESTION_TITLE" => $getReviewTitle . " Suggestion",
              "_EMAIL_NOTIFY_" . $languageModTitle . "_SUGGESTION_DESCRIPTION" => "This email is sent to the member when someone suggest a " . $getReviewTitle . '.',
              "_EMAIL_NOTIFY_" . $languageModTitle . "_SUGGESTION_SUBJECT" => $getReviewTitle . " Suggestion",
              "_EMAIL_NOTIFY_" . $languageModTitle . "_SUGGESTION_BODY" => "[header]

      [sender_title] has suggested to you a " . $getReviewTitle . ". To view this suggestion please click on: <a href='http://[host][object_link]'>http://[host][object_link]</a>.

      [footer]"
          );
          $userSettingsNotfication = array("ACTIVITY_TYPE_" . $languageModTitle . "_SUGGESTION" => "When I receive a " . strtolower($getReviewTitle) . " suggestion.");
          $userNotification = array($notificationLanguage => $notificationLanguage);

          $this->addPhraseAction($makeEmailArray);
          $this->addPhraseAction($userSettingsNotfication);
          $this->addPhraseAction($userNotification);

          $this->addPhraseAction($language1);
          $this->addPhraseAction($language2);
          $this->addPhraseAction($language3);
          $this->addPhraseAction($requestTab);

          // Insert in Suggestion modules tables.
          $row = $suggestionModuleTable->createRow();
          $row->module = "sitereview";
          $row->item_type = "sitereview_listing";
          $row->field_name = "listing_id";
          $row->owner_field = "owner_id";
          $row->item_title = $getReviewTitle;
          $row->button_title = "View this " . @ucfirst($tempReviewTitle);
          $row->enabled = "1";
          $row->notification_type = $suggNotificationType;
          $row->quality = "1";
          $row->link = "1";
          $row->popup = "1";
          $row->recommendation = "1";
          $row->default = "1";
          $row->settings = @serialize($suggSettingId);
          $row->save();
        }

        // Integrate Sitereviewlisting with Communityad plugin.
        $isCommunityadEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled("communityad");
        if (!empty($isCommunityadEnabled)) {
          $communityadModuleTable = Engine_Api::_()->getDbTable('modules', 'communityad');
          $communityadModuleTableName = $communityadModuleTable->info('name');

          $row = $communityadModuleTable->createRow();
          $row->module_name = "sitereview";
          $row->module_title = $values["title_singular"];
          $row->table_name = "sitereview_listing_" . $listingType->listingtype_id;
          $row->title_field = "title";
          $row->body_field = "body";
          $row->owner_field = "owner_id";
          $row->displayable = "7";
          $row->is_delete = "1";
          $row->save();
        }

        // Integrate Sitereviewlisting with Sitemenu plugin.
        $isSitemenuEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled("sitemenu");
        if (!empty($isSitemenuEnabled)) {
          $sitemenuModuleTable = Engine_Api::_()->getDbTable('modules', 'sitemenu');
          $row = $sitemenuModuleTable->createRow();
          $row->module_name = "sitereview";
          $row->module_title = $values["title_singular"];
          $row->item_type = "sitereview_listing_" . $listingType->listingtype_id;
          $row->title_field = "title";
          $row->body_field = "body";
          $row->owner_field = "owner_id";
          $row->like_field = "like_count";
          $row->comment_field = "comment_count";
          $row->date_field = "creation_date";
          $row->featured_field = "featured";
          $row->sponsored_field = "sponsored";
          $row->status = "1";
          $row->image_option = "1";
          $row->category_name = "sitereview_category";
          $row->category_title_field = "category_name";
          $row->is_delete = "1";
          $row->save();
        }


        Engine_Api::_()->getApi('listingType', 'sitereview')->defaultCreation($listingType->listingtype_id, $values['main_menu'], $values['pinboard_layout'], $_POST['template_type']);

        if (!empty($values['member_level'])) {
          $this->setLevelSettings($values['member_level'], $listingType->listingtype_id);
        } else {
          Engine_Api::_()->getApi('listingType', 'sitereview')->defaultMemberLevelSettings($listingType->listingtype_id);
        }

        Engine_Api::_()->getApi('listingType', 'sitereview')->locationMenuUpdate($listingType);

        if ($values['language_phrases'] && (!isset($_POST['template_type']) || (isset($_POST['template_type']) && empty($_POST['template_type'])))) {
          $listingTypeApi = Engine_Api::_()->getApi('listingType', 'sitereview');
          $listingTypeApi->updateWidgetParams($listingType, $values['language_phrases']['text_overview'], $values['language_phrases']['text_Where_to_Buy'], $values['language_phrases']['text_tags']);
        }

        //START INTERGRATION EXTENSION WORK
        //START FOR PAGE INRAGRATION WORK.
        $sitepageintegrationEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepageintegration');
        if (!empty($sitepageintegrationEnabled)) {
          $sitepageintegrationMixTable = Engine_Api::_()->getDbtable('mixsettings', 'sitepageintegration');
          $row = $sitepageintegrationMixTable->createRow();
          $row->module = 'sitereview';
          $row->resource_type = 'sitereview_listing_' . $listingType->listingtype_id;
          $row->resource_id = 'listing_id';
          $row->item_title = $listingType->title_singular;
          $row->enabled = '0';
          $row->save();

          //add Menu from page plugin left side.
          $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name` , `module` , `label` , `plugin` ,`params`, `menu`, `enabled`, `custom`, `order`) VALUES ("sitepage_sitereview_gutter_create_' . $listingType->listingtype_id . '", "sitepage", "Post New ' . ucfirst($values['title_singular']) . ' ", \'Sitepage_Plugin_Menus::sitepagesitereviewGutterCreate\', \'{"route":"sitereview_general_listtype_' . $listingType->listingtype_id . '", "action":"create", "listing_id": "' . $listingType->listingtype_id . '", "class":"buttonlink item_icon_sitereview_listtype_' . $listingType->listingtype_id . '"}\', "sitepage_gutter", 1, 0, 999 )');
        }
        //END FOR PAGE INRAGRATION WORK.
        //START FOR BUSINESS INRAGRATION WORK.
        $sitebusinessintegrationEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessintegration');
        if (!empty($sitebusinessintegrationEnabled)) {
          $sitebusinessintegrationMixTable = Engine_Api::_()->getDbtable('mixsettings', 'sitebusinessintegration');
          $row = $sitebusinessintegrationMixTable->createRow();
          $row->module = 'sitereview';
          $row->resource_type = 'sitereview_listing_' . $listingType->listingtype_id;
          $row->resource_id = 'listing_id';
          $row->item_title = $listingType->title_singular;
          $row->enabled = '0';
          $row->save();


          //ADD MENU FROM BUSIENSS PLUGIN LEFT SIDE.
          $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name` , `module` , `label` , `plugin` ,`params`, `menu`, `enabled`, `custom`, `order`) VALUES ("sitebusiness_sitereview_gutter_create_' . $listingType->listingtype_id . '", "sitebusiness", "Post New ' . $values['title_singular'] . ' ", \'Sitebusiness_Plugin_Menus::sitebusinesssitereviewGutterCreate\', \'{"route":"sitereview_general_listtype_' . $listingType->listingtype_id . '", "action":"create", "listing_id": "' . $listingType->listingtype_id . '", "class":"buttonlink item_icon_sitereview_listtype_' . $listingType->listingtype_id . '"}\', "sitebusiness_gutter", 1, 0, 999 )');
        }
        //END FOR BUSINESS INRAGRATION WORK.
        //START FOR STORE INRAGRATION WORK.
        $sitestoreintegrationEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitestoreintegration');
        if (!empty($sitestoreintegrationEnabled)) {
          $sitestoreintegrationMixTable = Engine_Api::_()->getDbtable('mixsettings', 'sitestoreintegration');
          $row = $sitestoreintegrationMixTable->createRow();
          $row->module = 'sitereview';
          $row->resource_type = 'sitereview_listing_' . $listingType->listingtype_id;
          $row->resource_id = 'listing_id';
          $row->item_title = $listingType->title_singular;
          $row->enabled = '0';
          $row->save();


          //ADD MENU FROM STORE PLUGIN LEFT SIDE.
          $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name` , `module` , `label` , `plugin` ,`params`, `menu`, `enabled`, `custom`, `order`) VALUES ("sitestore_sitereview_gutter_create_' . $listingType->listingtype_id . '", "sitestore", "Post New ' . $values['title_singular'] . ' ", \'Sitestore_Plugin_Menus::sitestoresitereviewGutterCreate\', \'{"route":"sitereview_general_listtype_' . $listingType->listingtype_id . '", "action":"create", "listing_id": "' . $listingType->listingtype_id . '", "class":"buttonlink item_icon_sitereview_listtype_' . $listingType->listingtype_id . '"}\', "sitestore_gutter", 1, 0, 999 )');
        }
        //END FOR STORE INRAGRATION WORK.
        //START FOR GROUP INRAGRATION WORK.
        $sitegroupintegrationEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupintegration');
        if (!empty($sitegroupintegrationEnabled)) {
          $sitegroupintegrationMixTable = Engine_Api::_()->getDbtable('mixsettings', 'sitegroupintegration');
          $row = $sitegroupintegrationMixTable->createRow();
          $row->module = 'sitereview';
          $row->resource_type = 'sitereview_listing_' . $listingType->listingtype_id;
          $row->resource_id = 'listing_id';
          $row->item_title = $listingType->title_singular;
          $row->enabled = '0';
          $row->save();


          //ADD MENU FROM GROUP PLUGIN LEFT SIDE.
          $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name` , `module` , `label` , `plugin` ,`params`, `menu`, `enabled`, `custom`, `order`) VALUES ("sitegroup_sitereview_gutter_create_' . $listingType->listingtype_id . '", "sitegroup", "Post New ' . $values['title_singular'] . ' ", \'Sitegroup_Plugin_Menus::sitegroupsitereviewGutterCreate\', \'{"route":"sitereview_general_listtype_' . $listingType->listingtype_id . '", "action":"create", "listing_id": "' . $listingType->listingtype_id . '", "class":"buttonlink item_icon_sitereview_listtype_' . $listingType->listingtype_id . '"}\', "sitegroup_gutter", 1, 0, 999 )');
        }
        //END FOR GROUP INRAGRATION WORK.
        //START INTERGRATION EXTENSION WORK

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      if (isset($_POST['template_type']) && !empty($_POST['template_type']) && $_POST['template_type'] != 'product') {
        $template_type = $_POST['template_type'];
        $this->_redirect("admin/sitereview/settings/categories/listingtype_id/$listingType->listingtype_id/success/1/template_type/$template_type");
      } else {
        $this->_redirect("admin/sitereview/settings/categories/listingtype_id/$listingType->listingtype_id/success/1");
      }
    }
  }

  //ACTION TO SET NEW LISTING TYPES LEVEL SETTINGS
  public function setLevelSettings($listing_type_id = 1, $last_listing_type_id = 0) {

    if (empty($last_listing_type_id)) {
      return;
    }

    $table = Engine_Api::_()->getDbtable('levels', 'authorization');
    $permissionTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $select = $permissionTable->select()->where('name LIKE ?', "%_listtype_$listing_type_id%");
    $parent_permissions = $table->fetchAll($select);

    foreach ($parent_permissions as $parent) {
      $parentArray = $parent->toArray();
      $search = array("_listtype_$listing_type_id");
      $replace = array("_listtype_$last_listing_type_id");
      $parentArray['name'] = str_replace($search, $replace, $parentArray['name']);

      $permissions = $permissionTable->createRow();
      $permissions->setFromArray($parentArray);
      $permissions->save();
    }
  }

  //ACTION FOR ADDING THE LISTING TYPE
  public function editAction() {

    //INCREASE THE MEMORY ALLOCATION SIZE AND INFINITE SET TIME OUT
    ini_set('memory_limit', '1024M');
    set_time_limit(0);

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sitereview_admin_main', array(), 'sitereview_admin_main_listingtypes');

    //CREATE FORM
    $this->view->form = $form = new Sitereviewlistingtype_Form_Admin_Listingtypes_Edit();
    $form->removeElement('member_level');

    //GET LISTING TYPE ID AND SET OBJECT
    $this->view->listingtype_id = $listingtype_id = $this->_getParam('listingtype_id');
    $this->view->listingType = $listingType = Engine_Api::_()->getItem('sitereview_listingtype', $listingtype_id);
    $previousLocationValue = $listingType->location;
    $this->view->claimlink = $listingType->claimlink;

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewpaidlisting')) {
      $this->view->package = $listingType->package;
    } else {
      $this->view->package = 0;
    }

    $form->populate($listingType->toArray());
    if ($listingType->language_phrases)
      $form->populate($listingType->language_phrases);

    $previous_slug_singular = $listingType->slug_singular;
    $previous_slug_plural = $listingType->slug_plural;
    $titleSinUc = ucfirst($listingType->title_singular);
    $titleSinUpper = strtoupper($listingType->title_singular);
    $titleSinLc = strtolower($listingType->title_singular);

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $slug_singular = $_POST['slug_singular'];
      $slug_plural = $_POST['slug_plural'];
      $title_singular = $_POST['title_singular'];
      $title_plural = $_POST['title_plural'];
      $packageValue = 0;
      $isPackageModuleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewpaidlisting');
      if($isPackageModuleEnabled)
      $packageValue = $listingType->package;

      if ($slug_singular == $slug_plural) {
        $error = $this->view->translate("Singular Slug and Plural Slug can't be same.");
        $error = Zend_Registry::get('Zend_Translate')->_($error);

        $form->getDecorator('errors')->setOption('escape', false);
        $form->addError($error);
        return;
      }

      if ($previous_slug_singular != $slug_singular) {
        $listingtype_id = Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->checkListingSlug($slug_singular);
        if (!empty($listingtype_id)) {
          $error = $this->view->translate("Please choose the different 'Singular Slug', you have already created the same slug.");
          $error = Zend_Registry::get('Zend_Translate')->_($error);

          $form->getDecorator('errors')->setOption('escape', false);
          $form->addError($error);
          return;
        }
      }

      if ($previous_slug_plural != $slug_plural) {
        $listingtype_id = Engine_Api::_()->getDbTable('listingtypes', 'sitereview')->checkListingSlug($slug_plural);
        if (!empty($listingtype_id)) {
          $error = $this->view->translate("Please choose the different 'Plural Slug', you have already created the same slug.");
          $error = Zend_Registry::get('Zend_Translate')->_($error);

          $form->getDecorator('errors')->setOption('escape', false);
          $form->addError($error);
          return;
        }
      }
      $values = $form->getValues();
      
      if(isset ($_POST['package'])) {
        if(!empty($_POST['package']))
        $values['expiry'] = '0';
      }
      
      if ($values['translation_file'] || ((isset($_POST['package']) && !empty($_POST['package'])) && ($_POST['package'] != $packageValue))) {
        $hasLanguageDirectoryPermissions = Engine_Api::_()->getApi('language', 'sitereview')->hasDirectoryPermissions();
        if (!$hasLanguageDirectoryPermissions) {
          $error = $this->view->translate("Language file for this listing type could not be overwritten. because you do not have write permission chmod -R 777 recursively to the directory '/application/languages/'. Please login in over your Cpanel or FTP and give the recursively write permission to this directory and try again.");
          $error = Zend_Registry::get('Zend_Translate')->_($error);
          $form->getDecorator('errors')->setOption('escape', false);
          $form->addError($error);
          return;
        }
      }

      $getListingReviewType = Engine_Api::_()->getApi('listingType', 'sitereview')->getListingReviewType();
      if (empty($getListingReviewType)) {
        return;
      }

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      $listingTypeApi = Engine_Api::_()->getApi('listingType', 'sitereview');

      //START CLAIM WORK
      if (isset($_POST['claimlink'])) {
        $menuItemsTable = Engine_Api::_()->getDbTable('MenuItems', 'core');
        $menuItemsTableName = $menuItemsTable->info('name');
        $menuItemsId = $menuItemsTable->select()
                ->from($menuItemsTableName, array('id'))
                ->where('name = ? ', "sitereview_main_claim_listtype_$listingtype_id")
                ->query()
                ->fetchColumn();

        if (empty($menuItemsId)) {
          $menuItemsTable->insert(array(
              'name' => "sitereview_main_claim_listtype_$listingtype_id",
              'module' => 'sitereview',
              'label' => "Claim a $titleSinUc",
              'plugin' => 'Sitereview_Plugin_Menus::canViewClaims',
              'params' => '{"route":"sitereview_claim_listtype_' . $listingtype_id . '","listingtype_id":"' . $listingtype_id . '"}',
              'menu' => "sitereview_main_listtype_$listingtype_id",
              'submenu' => '',
              'order' => 6,
          ));
        }

        $menuItemsId = $menuItemsTable->select()
                ->from($menuItemsTableName, array('id'))
                ->where('name = ? ', "sitereview_gutter_claim_listtype_$listingtype_id")
                ->query()
                ->fetchColumn();

        if (empty($menuItemsId)) {
          $menuItemsTable->insert(array(
              'name' => "sitereview_gutter_claim_listtype_$listingtype_id",
              'module' => 'sitereview',
              'label' => "Claim this $titleSinUc",
              'plugin' => 'Sitereview_Plugin_Menus::sitereviewGutterClaim',
              'params' => '{"listingtype_id":"' . $listingtype_id . '"}',
              'menu' => "sitereview_gutter_listtype_$listingtype_id",
              'submenu' => '',
              'order' => 16,
          ));
        }
        if ($_POST['claim_show_menu'] == 1) {
          $menuItemsTable->update(array('menu' => 'core_footer', 'params' => '{"route":"sitereview_claim_listtype_' . $listingtype_id . '","listingtype_id":"' . $listingtype_id . '"}'), array('name =?' => "sitereview_main_claim_listtype_$listingtype_id"));
        } else if ($_POST['claim_show_menu'] == 2) {
          $menuItemsTable->update(array('menu' => "sitereview_main_listtype_$listingtype_id", 'params' => '{"route":"sitereview_claim_listtype_' . $listingtype_id . '","listingtype_id":"' . $listingtype_id . '"}'), array('name =?' => "sitereview_main_claim_listtype_$listingtype_id"));
        } else if (empty($_POST['claim_show_menu'])) {
          $menuItemsTable->update(array('menu' => '', 'params' => ''), array('name =?' => "sitereview_main_claim_listtype_$listingtype_id"));
        }
      }


      //END CLAIM WORK

      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {

        $titleSingular = $listingType->title_singular;
        $titlePlural = $listingType->title_plural;
        
        // Integrate Sitereviewlisting with Communityad plugin.
        $isCommunityadEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled("communityad");
        if (!empty($isCommunityadEnabled)) {
          $communityadModuleTable = Engine_Api::_()->getDbTable('modules', 'communityad');
          $communityadModuleTableName = $communityadModuleTable->info('name');
          
          $select = $communityadModuleTable->select()->from($communityadModuleTableName, array('module_id'))->where('table_name =?', 'sitereview_listing_' . $listingType->listingtype_id);
          $moduleId = $select->query()->fetchColumn();    
          
          if(empty($moduleId)) {
            $row = $communityadModuleTable->createRow();
            $row->module_name = "sitereview";
            $row->module_title = $listingType->title_singular;
            $row->table_name = "sitereview_listing_" . $listingType->listingtype_id;
            $row->title_field = "title";
            $row->body_field = "body";
            $row->owner_field = "owner_id";
            $row->displayable = "7";
            $row->is_delete = "1";
            $row->save();
          }
        }

        if (!$values['translation_file'] && (!isset($_POST['package']) || (isset($_POST['package']) && empty($_POST['package'])) || ($_POST['package'] == $listingType->package))) {
          unset($values['language_phrases']);
        }
                
        $listingType->setFromArray($values);
        $listingType->save();

        if (isset($_POST['claimlink'])) {
          $listingTypeApi->createClaimPage($listingType);
        }
        
        //START PACKAGE WORK
        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewpaidlisting') && !empty($_POST['package'])) {
          $listingTypeApi->freePackageCreate($listingType);
          $listingTypeApi->createPackageNavigation($listingType->listingtype_id);
        }
        //END PACKAGE WORK
        // Set photo
        if (!empty($values['photo'])) {
          $listingType->setPhoto($form->photo);
        }

        if(isset($values['location']) && $values['location'] != $previousLocationValue) { 
            Engine_Api::_()->getApi('listingType', 'sitereview')->locationMenuUpdate($listingType);
        }
        
        if ($values['pinboard_layout']) {
          $listingTypeApi->setPinBoardLayoutHomePage($listingType);
        }

        //EDIT IF SINGULAR/PLURAL TITLE HAS BEEN CHANGED
        if ($titleSingular != $listingType->title_singular || $titlePlural != $listingType->title_plural) {

          //if (!empty($_POST['pages_navigation']) && in_array('pages', $_POST['pages_navigation'])) {

          $listingTypeApi->widgetizedPagesEdit($listingType, 'home', $titleSingular, $titlePlural);
          $listingTypeApi->widgetizedPagesEdit($listingType, 'index', $titleSingular, $titlePlural);
          $listingTypeApi->widgetizedPagesEdit($listingType, 'top-rated', $titleSingular, $titlePlural);
          $listingTypeApi->widgetizedPagesEdit($listingType, 'view', $titleSingular, $titlePlural);
          $listingTypeApi->widgetizedPagesEdit($listingType, 'map', $titleSingular, $titlePlural);
          //}
          //if (!empty($_POST['pages_navigation']) && in_array('navigations', $_POST['pages_navigation'])) {

          $listingTypeApi->mainNavigationEdit($listingType);
          $listingTypeApi->gutterNavigationEdit($listingType);
          //}

          $listingTypeApi->activityFeedQueryEdit($listingType, $titleSingular, $titlePlural);
          $listingTypeApi->searchFormSettingEdit($listingType, $titleSingular, $titlePlural);

          //START INTERGRATION EXTENSION WORK
          //START FOR PAGE INRAGRATION.
          $sitepageintegrationEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepageintegration');
          if (!empty($sitepageintegrationEnabled)) {
            Engine_Api::_()->sitepageintegration()->pageintergrationTitleEdit($values['title_singular'], $this->_getParam('listingtype_id'), $titlePlural);
          }
          //END FOR PAGE INRAGRATION.
          //START FOR BUSINESS INRAGRATION.
          $sitebusinessintegrationEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessintegration');
          if (!empty($sitebusinessintegrationEnabled)) {
            Engine_Api::_()->sitebusinessintegration()->businessintergrationTitleEdit($values['title_singular'], $this->_getParam('listingtype_id'));
          }
          //END FOR BUSINESS INRAGRATION.
          //START FOR STORE INRAGRATION.
          $sitestoreintegrationEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitestoreintegration');
          if (!empty($sitestoreintegrationEnabled)) {
            Engine_Api::_()->sitestoreintegration()->storeintergrationTitleEdit($values['title_singular'], $this->_getParam('listingtype_id'));
          }
          //END FOR STORE INRAGRATION.
          //START FOR GROUP INRAGRATION.
          $sitegroupintegrationEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroupintegration');
          if (!empty($sitegroupintegrationEnabled)) {
            Engine_Api::_()->sitegroupintegration()->groupintergrationTitleEdit($values['title_singular'], $this->_getParam('listingtype_id'));
          }
          //END FOR GROUP INRAGRATION.
          //START INTERGRATION EXTENSION WORK
        }

        if ($values['translation_file'] || ((isset($_POST['package']) && !empty($_POST['package'])) && ($_POST['package'] != $packageValue))) {
          Engine_Api::_()->getApi('language', 'sitereview')->setTranslateForListType($listingType);
          $listingTypeApi->updateWidgetParams($listingType, $values['language_phrases']['text_overview'], $values['language_phrases']['text_Where_to_Buy'], $values['language_phrases']['text_tags']);
        }
        
        $listingTypeApi->mainMenuEdit($listingType);

        //BANNED PAGE URL WORK.
        $listingTypeApi->addBannedUrls($listingType);

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_redirect("admin/sitereviewlistingtype/listingtypes/manage/saved/1");
    }
  }

  //ACTION FOR DELETE LISTING TYPE
  public function deleteAction() {

    //INCREASE THE MEMORY ALLOCATION SIZE AND INFINITE SET TIME OUT
    ini_set('memory_limit', '1024M');
    set_time_limit(0);

    //LAYOUT
    $this->_helper->layout->setLayout('admin-simple');

    //GET LISTING TYPE ID AND OBJECT
    $this->view->listingtype_id = $listingtype_id = $this->_getParam('listingtype_id');
    $listingType = Engine_Api::_()->getItem('sitereview_listingtype', $listingtype_id);

    //CREATE FORM
    $this->view->form = $form = new Sitereviewlistingtype_Form_Admin_Listingtypes_Mapping();

    $this->view->close_smoothbox = 0;

    //GET LISTING TABLE
    $listingTable = Engine_Api::_()->getDbTable('listings', 'sitereview');

    //GET CATEGORIES TABLE
    $categoriesTable = Engine_Api::_()->getDbTable('categories', 'sitereview');

    if (!$this->getRequest()->isPost()) {
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    //CAN NOT DELETE DEFAULT LISTING TYPE
    if ($listingtype_id <= 1) {
      return;
    }

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {

      if (isset($_POST['new_listingtype_id']) && !empty($_POST['new_listingtype_id'])) {

        $categoriesTable->updateCategoryListingtypes($listingtype_id, $_POST['new_listingtype_id']);
        $listingTable->updateListingsListingtypes($listingtype_id, $_POST['new_listingtype_id']);
        Engine_Api::_()->getDbTable('editors', 'sitereview')->moveListingtypeEditors($listingtype_id, $_POST['new_listingtype_id']);
      }

      // DELETE LISTING FROM COMMUNITYAD
      $isCommunityadEnabled = Engine_Api::_()->getDbtable('modules', 'core')->getModule("communityad");
      if (Engine_Api::_()->hasModuleBootstrap('communityad')) {
        $communityadModuleTable = Engine_Api::_()->getDbTable('modules', 'communityad');
        $communityadModuleTableName = $communityadModuleTable->info('name');

        $selectModuleTable = $communityadModuleTable->select()
                ->from($communityadModuleTableName, array('module_id'))
                ->where('table_name = ?', (string) "sitereview_listing_" . $listingtype_id)
                ->limit(1);
        $fetchCommunityadModuleContent = $selectModuleTable->query()->fetch();
        if (!empty($fetchCommunityadModuleContent['module_id'])) {
          $communityasModuleId = $fetchCommunityadModuleContent['module_id'];
          Engine_Api::_()->getItem("communityad_module", $communityasModuleId)->delete();
          $communityadAdsTable = Engine_Api::_()->getDbTable('userads', 'communityad');
          $communityadAdsTable->update(array('enable' => '0', 'status' => '4'), array('resource_type = ?' => (string) "sitereview_" . $communityasModuleId));
        }
      }

      // DELETE LISTING FROM SITEMENU
      $isSitemenuEnabled = Engine_Api::_()->getDbtable('modules', 'core')->getModule("sitemenu");
      if (Engine_Api::_()->hasModuleBootstrap('sitemenu')) {
        $sitemenuModuleTable = Engine_Api::_()->getDbTable('modules', 'sitemenu');
        $sitemenuModuleTableName = $sitemenuModuleTable->info('name');

        $sitemenuModuleId = $sitemenuModuleTable->select()
                ->from($sitemenuModuleTableName, array('module_id'))
                ->where('item_type = ?', (string) "sitereview_listing_" . $listingtype_id)
                ->limit(1)
                ->query()
                ->fetchColumn();
        if (!empty($sitemenuModuleId)) {
          Engine_Api::_()->getItem("sitemenu_module", $sitemenuModuleId)->delete();
        }
      }
      
      
      // DELETE LISTING PACKAGES FROM SITEREVIEWPAIDLISTING
      $isSitereviewpaidlistingEnabled = Engine_Api::_()->getDbtable('modules', 'core')->getModule("sitereviewpaidlisting");
      if (Engine_Api::_()->hasModuleBootstrap('sitereviewpaidlisting')) {
        $sitereviewpaidlistingModuleTable = Engine_Api::_()->getDbTable('packages', 'sitereviewpaidlisting');
        $sitereviewpaidlistingModuleTableName = $sitereviewpaidlistingModuleTable->info('name');

        $sitereviewpaidlistingModuleId = $sitereviewpaidlistingModuleTable->select()
                ->from($sitereviewpaidlistingModuleTableName, array('package_id'))
                ->where('listingtype_id = ?', $listingtype_id)
                ->query()
                ->fetchColumn();
        if (!empty($sitereviewpaidlistingModuleId)) {
          Engine_Api::_()->getItem("sitereviewpaidlisting_package", $sitereviewpaidlistingModuleId)->delete();
        }
      }


      // DELETE LISTING FROM SUGGESTION PLUGIN
      $isSuggestionEnabled = Engine_Api::_()->getDbtable('modules', 'core')->getModule("suggestion");
      if (!empty($isSuggestionEnabled) && $isSuggestionEnabled->version > '4.2.7') {
        Engine_Api::_()->suggestion()->deleteListingType($listingtype_id);
      }


      //DELETE ENTRY FROM FACEBOOK PLUGIN ALSO.
      $fbmodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('facebookse');
      if (!empty($fbmodule) && !empty($fbmodule->enabled) && $fbmodule->version > '4.2.7p1') {
        Engine_Api::_()->facebookse()->addReviewList($listingType, 'delete');
      }
      
      //DELETE ADVANCED SEARCH PAGE FOR THIS MLT
      $pageTable = Engine_Api::_()->getDbTable('pages', 'core');
      $pageTableName = $pageTable->info('name');

      $page_id = $pageTable->select()
        ->from($pageTableName, 'page_id')
        ->where('name = ?', "siteadvsearch_index_browse-page_listtype_" . $listingtype_id)
        ->query()
        ->fetchColumn();

      if (!empty($page_id)) {
        Engine_Api::_()->getDbTable('content', 'core')->delete(array('page_id = ?' => $page_id));
        $pageTable->delete(array('page_id = ?' => $page_id));
      }


      //DELETE LISTING TYPE OBJECT
      $listingType->delete();

      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    $this->view->close_smoothbox = 1;
  }

  public function removeDefaultIconAction() {
    $this->_helper->layout->setLayout('admin-simple');

    $this->view->id = $id = $this->_getParam('id');


    if ($this->getRequest()->isPost()) {

      $item = Engine_Api::_()->getItem('sitereview_listingtype', $id);
      $item->removePhoto();

      $item->photo_id = 0;
      $item->save();
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 1000,
          'parentRefresh' => 1000,
          'messages' => array('Removed Default Photo Succesfully.')
      ));
    }
  }

  public function visibleAction() {

    $listingtype_id = $this->_getParam('listingtype_id');

    if (!empty($listingtype_id)) {
      $listingtype = Engine_Api::_()->getItem('sitereview_listingtype', $listingtype_id);
      $listingtype->visible = $enabled = !$listingtype->visible;
      $listingtype->save();

      //UPDATE THE VALUE FOR MAIN-MENU
      $menuItemsTable = Engine_Api::_()->getDbTable('MenuItems', 'core');
      $menuItemsTableName = $menuItemsTable->info('name');

      $menuItemsId = $menuItemsTable->select()
              ->from($menuItemsTableName, array('id'))
              ->where('name = ? ', "core_main_sitereview_listtype_$listingtype_id")
              ->query()
              ->fetchColumn();
      if (!empty($menuItemsId)) {
        $menuItemsTable->update(array(
            'enabled' => $enabled,
                ), array(
            'name = ?' => "core_main_sitereview_listtype_$listingtype_id",
        ));
      }

      //START FOR PAGE INRAGRATION.
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepageintegration')) {
        Engine_Api::_()->sitepageintegration()->getEnabled($listingtype_id, $enabled);
      }
      //END FOR PAGE INRAGRATION.
      
      //START FOR Advanvced Search INRAGRATION.
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('siteadvsearch')) {
        Engine_Api::_()->siteadvsearch()->getEnabled($listingtype_id, $enabled);
      }
      //END FOR Advanvced Search INRAGRATION.
    }

    $this->_redirect('admin/sitereviewlistingtype/listingtypes/manage');
  }

  public function addPhraseAction($phrase) {
    if ($phrase) {
      //file path name
      $targetFile = APPLICATION_PATH . '/application/languages/en/custom.csv';
      if (!file_exists($targetFile)) {
        //Sets access of file
        touch($targetFile);
        //changes permissions of the specified file.
        chmod($targetFile, 0777);
      }
      if (file_exists($targetFile)) {
        $writer = new Engine_Translate_Writer_Csv($targetFile);
        $writer->setTranslations($phrase);
        $writer->write();
        //clean the entire cached data manually
        @Zend_Registry::get('Zend_Cache')->clean();
      }
    }
  }

}