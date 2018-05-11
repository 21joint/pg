<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminCreditController.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_AdminCreditController extends Core_Controller_Action_Admin {

    protected $_languagePath;

    public function init() {
        $this->_languagePath = APPLICATION_PATH . '/application/languages';
    }

    public function indexAction() {
        // navigation menu
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_credit');
        $this->view->navigationSubMenu = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main_credit', array(), 'sitecredit_admin_credit_setcredits');
        //activitiest not compatible with credits
        $this->view->activityNotShown = $activityNotShown = array('friends_follow', 'logout', 'siteevent_date_time_extended_parent', 'siteevent_date_time_extended');
        $this->view->activityNotShownDeletion = $activityNotShownDeletion = array('friends', 'login', 'profile_photo_update', 'nestedcomment_album', 'nestedcomment_album_photo', 'nestedcomment_blog', 'nestedcomment_classified', 'nestedcomment_event', 'nestedcomment_group', 'nestedcomment_poll', 'nestedcomment_siteevent_event', 'nestedcomment_siteevent_review', 'nestedcomment_sitestoreproduct_product', 'nestedcomment_sitestoreproduct_review', 'nestedcomment_sitevideo_channel', 'nestedcomment_video', 'siteeventticket_new_status', 'nestedcomment_sitereview_review', 'nestedcomment_sitereview_listing');

        $translate = Zend_Registry::get('Zend_Translate');

        // Prepare language list
        $this->view->languageList = $languageList = $translate->getList();

        $localeObject = Zend_Registry::get('Locale');

        $languages = Zend_Locale::getTranslationList('language', $localeObject);
        $territories = Zend_Locale::getTranslationList('territory', $localeObject);

        $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en');
        if (!in_array($defaultLanguage, $languageList)) {
            if ($defaultLanguage == 'auto' && isset($languageList['en'])) {
                $defaultLanguage = 'en';
            } else {
                $defaultLanguage = null;
            }
        }
        $this->view->defaultLanguage = $defaultLanguage;
        $localeMultiOptions = array();
        foreach ($languageList as $key) {
            $languageName = null;
            if (!empty($languages[$key])) {
                $languageName = $languages[$key];
            } else {
                $tmpLocale = new Zend_Locale($key);
                $region = $tmpLocale->getRegion();
                $language = $tmpLocale->getLanguage();
                if (!empty($languages[$language]) && !empty($territories[$region])) {
                    $languageName = $languages[$language] . ' (' . $territories[$region] . ')';
                }
            }

            if ($languageName) {
                $localeMultiOptions[$key] = $languageName;
            } else {
                $localeMultiOptions[$key] = $this->view->translate('Unknown');
            }
        }
        $localeMultiOptions = array_merge(array($defaultLanguage => $defaultLanguage
                ), $localeMultiOptions);
        $this->view->languageNameList = $localeMultiOptions;

        $activityCreditsTable = Engine_Api::_()->getDbtable('activitycredits', 'sitecredit');
        $activityCreditsName = $activityCreditsTable->info('name');

        $db = $activityCreditsTable->getAdapter();
        //create column in table if not exist in table
        $languagesArray = array();
        include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';

        $language_column = $db->query("SHOW COLUMNS FROM engine4_sitecredit_activitycredits LIKE 'language_%'")->fetchAll();
        //delete column from table if language no more exist
        $activitycreditTableColumn = array();
        foreach ($language_column as $key => $value) {
            if (in_array($value['Field'], $languagesArray)) {
                $activitycreditTableColumn[] = $value['Field'];
                continue;
            }
            $db->query("ALTER TABLE `engine4_sitecredit_activitycredits` DROP `" . $value['Field'] . "`");
        }

        // Get level id
        if (null !== ($id = $this->_getParam('id'))) {
            if (!empty($id)) {
                $level = Engine_Api::_()->getItem('authorization_level', $id);
            } else {
                $level = 0;
            }
        } else {
            $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
        }
        if (!empty($level)) {
            $id = $level->level_id;
            $this->view->level_id = $id;
        } else {
            $id = 0;
            $this->view->level_id = 0;
        }
        if (null !== $this->_getParam('module_id')) {
            $module = $this->_getParam('module_id');
        }
        $this->view->module = $module;
        $levelOptions = array();
        foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level) {
            $levelOptions[$level->level_id] = $level->getTitle();
        }
        $this->view->levelOptions = $levelOptions;
        $table = Engine_Api::_()->getDbtable('actionTypes', 'activity');
        $activityTableName = $table->info('name');
        $moduleOptions = array();

        $coreModuletable = Engine_Api::_()->getDbtable('modules', 'core');
        $coreModuletableName = $coreModuletable->info('name');

        $creditDefaulttable = Engine_Api::_()->getDbtable('labels', 'sitecredit');
        $creditDefaultTableName = $creditDefaulttable->info('name');

        $select = $table->select()->setIntegrityCheck(false);
        $select->from($activityTableName, array("DISTINCT($activityTableName.module)"))
                ->join($coreModuletableName, $coreModuletableName . '.name = ' . $activityTableName . '.module', array("$coreModuletableName.title"));
        $modules = $table->fetchAll($select);
        $this->view->modules = $modules;

        $ActivityOptions = array();
        $select = $table->select()->setIntegrityCheck(false);
        $select->from($activityTableName, array("$activityTableName.type", "$activityTableName.body", "$activityTableName.module"))->join($coreModuletableName, $coreModuletableName . '.name = ' . $activityTableName . '.module', array("$coreModuletableName.title"));
        $select->joinLeft($creditDefaultTableName, '( BINARY ' . $activityTableName . '.type = BINARY ' . $creditDefaultTableName . '.type )', array("$creditDefaultTableName.label"));


        if (null !== $this->_getParam('module_id')) {
            if ($this->_getParam('module_id') !== 'all_modules') {
                $select->where('module=?', $module);
            }
        }
        $select->order('module');
        $values = $table->fetchAll($select);
        $multiOptions = array();
        $this->view->values = $values;
        foreach ($values as $actionType) {
            $multiOptions[$actionType->type] = $actionType->module;
        }

        // Check post
        if (!$this->getRequest()->isPost()) {
            return;
        }

        // store data
        foreach ($multiOptions as $key => $value) {
            if (in_array($key, $activityNotShown))
                continue;
            $param['member_level'] = $_POST['member_level'];
            $param['activity_type'] = $key;
            $param['credit_point_first'] = $_POST[$key . '_first'];
            $param['credit_point_other'] = $_POST[$key . '_other'];
            $param['deduction'] = $_POST[$key . '_delete'];
            $param['limit_per_day'] = $_POST[$key . '_limit'];
            $param['module'] = $value;
            foreach ($localeMultiOptions as $index => $value) {
                $column = "language_" . $index;
                $param[$column] = $_POST[$key . '_' . $column];
            }

            if (empty($param['credit_point_first']) && empty($param['credit_point_other']) && empty($param['deduction']) && empty($param['limit_per_day']))
                continue;
            Engine_Api::_()->getDbtable('activitycredits', 'sitecredit')->insertData($param);
        }
        $values = $table->fetchAll($select);
        $this->view->values = $values;
    }

    public function upgradeLevelAction() {
        // save credits for each level
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_credit');
        $this->view->navigationSubMenu = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main_credit', array(), 'sitecredit_admin_credit_upgradelevel');
        $this->view->success_massage = '';
        // Check post
        $this->view->levels = $levels = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll();
        if (!$this->getRequest()->isPost()) {
            return;
        }

        // Process
        $permissionsTable = Engine_Api::_()->getDbtable('levels', 'sitecredit');
        $db = $permissionsTable->getAdapter();
        $db->beginTransaction();

        try {
            foreach ($levels as $level) {
                if ($level->type == 'public' || ($level->type == 'admin' && $level->flag == 'superadmin'))
                    continue;
                $values['level_id'] = $level->level_id;
                $values['credit_point'] = $_POST['credit_point_' . $level->level_id];
                $select = $permissionsTable->select()->where('level_id =?', $level->level_id);
                $result = $permissionsTable->fetchrow($select);
                if (empty($result)) {
                    $row = $permissionsTable->createrow();
                    $row->setfromarray($values);
                    $row->save();
                } else {
                    $permissionsTable->update(array('credit_point' => $values['credit_point']), array('level_id =?' => $level->level_id));
                }
                ;
                // Commit
                $db->commit();
            }
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';
    }

    public function upgradeRequestAction() {
        // show upgrade request from users
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_upgraderequest');

        $page = $this->_getParam('page', 1);
        $values = array();
        $this->view->formFilter = $formFilter = new Sitecredit_Form_Admin_Filter();
        if ($formFilter->isValid($this->_getAllParams())) {
            $values = $formFilter->getValues();
        }

        foreach ($values as $key => $value) {
            if (null === $value) {
                unset($values[$key]);
            }
        }
        $now = date('Y-m-d h:m:s');
        $now = strtotime($now);
        $raw = date('Y-m-d', $now);

        $viewer = Engine_Api::_()->user()->getViewer()->getIdentity();
        $requestTable = Engine_Api::_()->getItemTable('upgraderequest');
        $requestTableName = $requestTable->info('name');

        $userTable = Engine_Api::_()->getDbTable('users', 'user');
        $userTableName = $userTable->info('name');

        $select = $requestTable->select()->setIntegrityCheck(false);
        $select->from($requestTableName)->join($userTableName, $userTableName . '.user_id = ' . $requestTableName . '.user_id', array("$userTableName.displayname"));

        if (!empty($_POST['username'])) {
            $username = $_POST['username'];
        } elseif (!empty($_GET['username']) && !isset($_POST['post_search'])) {
            $username = $_GET['username'];
        } else {
            $username = '';
        }
        if (!empty($_POST['show_time'])) {
            $show_time = $_POST['show_time'];
        } elseif (!empty($_GET['show_time']) && !isset($_POST['post_search'])) {
            $show_time = $_GET['show_time'];
        } else {
            $show_time = '';
        }

        if (!empty($_POST['status'])) {
            $status = $_POST['status'];
        } elseif (!empty($_GET['status']) && !isset($_POST['post_search'])) {
            $status = $_GET['status'];
        } else {
            $status = '';
        }

        if (!empty($_POST['starttime']) && !empty($_POST['starttime']['date'])) {
            $creation_date_start = $_POST['starttime']['date'];
        } elseif (!empty($_GET['starttime']['date']) && !isset($_POST['post_search'])) {
            $creation_date_start = $_GET['starttime']['date'];
        } else {
            $creation_date_start = '';
        }

        if (!empty($_POST['endtime']) && !empty($_POST['endtime']['date'])) {
            $creation_date_end = $_POST['endtime']['date'];
        } elseif (!empty($_GET['endtime']['date']) && !isset($_POST['post_search'])) {
            $creation_date_end = $_GET['endtime']['date'];
        } else {
            $creation_date_end = '';
        }

        if (!empty($_POST['from'])) {
            $creation_date_start = $_POST['from'];
        } elseif (!empty($_GET['from']) && !isset($_POST['post_search'])) {
            $creation_date_start = $_GET['from'];
        }

        if (!empty($_POST['to'])) {
            $creation_date_end = $_POST['to'];
        } elseif (!empty($_GET['to']) && !isset($_POST['post_search'])) {
            $creation_date_end = $_GET['to'];
        }
        // searching
        $this->view->show_time = $values['show_time'] = $show_time;
        $this->view->username = $values['username'] = $username;
        $this->view->status = $values['status'] = $status;
        $this->view->starttime = $values['from'] = $creation_date_start;
        $this->view->endtime = $values['to'] = $creation_date_end;

        if (!empty($username)) {
            $select->where($userTableName . '.displayname  LIKE ?', '%' . trim($username) . '%');
        }
        if (!empty($show_time)) {
            switch ($show_time) {
                case 'day': $select->where("CAST($requestTableName.creation_date AS DATE)=?", $raw);
                    break;
                case 'weekly': $select->where("$requestTableName.creation_date >= DATE(NOW()) - INTERVAL 7 DAY");
                    break;
                case 'range': if (!empty($creation_date_start)) {
                        $select->where("CAST($requestTableName.creation_date AS DATE) >=?", trim($creation_date_start));
                    }
                    if (!empty($creation_date_end)) {
                        $select->where("CAST($requestTableName.creation_date AS DATE) <=?", trim($creation_date_end));
                    } break;
            }
        }

        if (!empty($status)) {
            $select->where($requestTableName . '.status  LIKE ?', '%' . trim($status) . '%');
        }
        $values = array_merge(array(
            'order' => 'upgraderequest_id',
            'order_direction' => 'DESC',
                ), $values);

        $this->view->assign($values);

        $select->order((!empty($values['order']) ? $values['order'] : 'upgraderequest_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
        $this->view->formValues = array_filter($values);

        include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';
    }

    public function creditOfferAction() {

        // show offers for credit purchase
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_creditoffer');

        $page = $this->_getParam('page', 1);
        $values = array();
        $this->view->formFilter = $formFilter = new Sitecredit_Form_Admin_Filter();
        if ($formFilter->isValid($this->_getAllParams())) {
            $values = $formFilter->getValues();
        }

        foreach ($values as $key => $value) {
            if (null === $value) {
                unset($values[$key]);
            }
        }
        $now = date('Y-m-d h:m:s');
        $now = strtotime($now);
        $raw = date('Y-m-d', $now);

        $viewer = Engine_Api::_()->user()->getViewer()->getIdentity();
        $offerTable = Engine_Api::_()->getItemTable('offer');
        $select = $offerTable->select()->setIntegrityCheck(false);


        if (isset($_POST['order_min_amount']) && $_POST['order_min_amount'] != '') {
            $order_min_amount = $_POST['order_min_amount'];
        } elseif (!empty($_GET['order_min_amount']) && !isset($_POST['post_search'])) {
            $order_min_amount = $_GET['order_min_amount'];
        } else {
            $order_min_amount = '';
        }

        if (isset($_POST['order_max_amount']) && $_POST['order_max_amount'] != '') {
            $order_max_amount = $_POST['order_max_amount'];
        } elseif (!empty($_GET['order_max_amount']) && !isset($_POST['post_search'])) {
            $order_max_amount = $_GET['order_max_amount'];
        } else {
            $order_max_amount = '';
        }

        if (isset($_POST['min_amount']) && $_POST['min_amount'] != '') {
            $min_amount = $_POST['min_amount'];
        } elseif (!empty($_GET['min_amount']) && !isset($_POST['post_search'])) {
            $min_amount = $_GET['min_amount'];
        } else {
            $min_amount = '';
        }

        if (isset($_POST['max_amount']) && $_POST['max_amount'] != '') {
            $max_amount = $_POST['max_amount'];
        } elseif (!empty($_GET['max_amount']) && !isset($_POST['post_search'])) {
            $max_amount = $_GET['max_amount'];
        } else {
            $max_amount = '';
        }

        // searching
        $this->view->min_amount = $values['min_amount'] = $min_amount;
        $this->view->max_amount = $values['max_amount'] = $max_amount;
        $this->view->order_min_amount = $values['order_min_amount'] = $order_min_amount;
        $this->view->order_max_amount = $values['order_max_amount'] = $order_max_amount;

        if (!empty($min_amount)) {
            $select->where("value >=?", trim($min_amount));
        }

        if (!empty($max_amount)) {
            $select->where("value <=?", trim($max_amount));
        }

        if ($order_min_amount != '') {
            $select->where("credit_point >=?", trim($order_min_amount));
        }

        if ($order_max_amount != '') {
            $select->where("credit_point <=?", trim($order_max_amount));
        }
        $values = array_merge(array(
            'order' => 'offer_id',
            'order_direction' => 'DESC',
                ), $values);

        $this->view->assign($values);

        $select->order((!empty($values['order']) ? $values['order'] : 'offer_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));
        $this->view->formValues = array_filter($values);
        include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';
    }

    public function addofferAction() {
        //add offer
        $this->view->form = $form = new Sitecredit_Form_Admin_Creditoffer();

        // Check post
        if (!$this->getRequest()->isPost()) {
            return;
        }
        // Check validitiy
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }
        $table = Engine_Api::_()->getDbtable('offers', 'sitecredit');

        $values = $form->getValues();
        $values['creation_date'] = new Zend_Db_Expr('NOW()');
        $values['modified_date'] = new Zend_Db_Expr('NOW()');
        try {
            $row = $table->createRow();
            $row->setFromArray($values);
            $row->save();
            $form->addNotice('Your offer have been saved');
        } catch (Exception $e) {
            throw $e;
        }
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('')
        ));
    }

    public function manageAction() {

        // manage activities and credit values
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_credit');
        $this->view->navigationSubMenu = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main_credit', array(), 'sitecredit_admin_credit_manage');

        $this->view->activityNotShown = $activityNotShown = array('friends_follow', 'logout', 'siteevent_date_time_extended_parent', 'siteevent_date_time_extended');
        $this->view->activityNotShownDeletion = $activityNotShownDeletion = array('friends', 'login', 'profile_photo_update', 'nestedcomment_album', 'nestedcomment_album_photo', 'nestedcomment_blog', 'nestedcomment_classified', 'nestedcomment_event', 'nestedcomment_group', 'nestedcomment_poll', 'nestedcomment_siteevent_event', 'nestedcomment_siteevent_review', 'nestedcomment_sitestoreproduct_product', 'nestedcomment_sitestoreproduct_review', 'nestedcomment_sitevideo_channel', 'nestedcomment_video', 'siteeventticket_new_status', 'nestedcomment_sitereview_review', 'nestedcomment_sitereview_listing');
        $translate = Zend_Registry::get('Zend_Translate');

        // Prepare language list
        $this->view->languageList = $languageList = $translate->getList();

        $localeObject = Zend_Registry::get('Locale');

        $languages = Zend_Locale::getTranslationList('language', $localeObject);
        $territories = Zend_Locale::getTranslationList('territory', $localeObject);

        $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en');
        if (!in_array($defaultLanguage, $languageList)) {
            if ($defaultLanguage == 'auto' && isset($languageList['en'])) {
                $defaultLanguage = 'en';
            } else {
                $defaultLanguage = null;
            }
        }
        $this->view->defaultLanguage = $defaultLanguage;
        $localeMultiOptions = array();
        foreach ($languageList as $key) {
            $languageName = null;
            if (!empty($languages[$key])) {
                $languageName = $languages[$key];
            } else {
                $tmpLocale = new Zend_Locale($key);
                $region = $tmpLocale->getRegion();
                $language = $tmpLocale->getLanguage();
                if (!empty($languages[$language]) && !empty($territories[$region])) {
                    $languageName = $languages[$language] . ' (' . $territories[$region] . ')';
                }
            }

            if ($languageName) {
                $localeMultiOptions[$key] = $languageName;
            } else {
                $localeMultiOptions[$key] = $this->view->translate('Unknown');
            }
        }
        $localeMultiOptions = array_merge(array($defaultLanguage => $defaultLanguage
                ), $localeMultiOptions);
        $this->view->languageNameList = $localeMultiOptions;

        $activityCreditsTable = Engine_Api::_()->getDbtable('activitycredits', 'sitecredit');
        $activityCreditsName = $activityCreditsTable->info('name');

        $db = $activityCreditsTable->getAdapter();

// add column in table if column doesn't exist in table
        $languagesArray = array();
        foreach ($languageList as $key => $value) {
            $languagesArray[] = "language_" . $key;
            $language_column = $db->query("SHOW COLUMNS FROM engine4_sitecredit_activitycredits LIKE 'language_" . $key . "'")->fetch();
            if (empty($language_column)) {
                $db->query("ALTER TABLE `engine4_sitecredit_activitycredits` ADD `language_" . $key . "` text NULL;");
            }
        }

        $language_column = $db->query("SHOW COLUMNS FROM engine4_sitecredit_activitycredits LIKE 'language_%'")->fetchAll();
        // delete column from table if language no more exist
        $activitycreditTableColumn = array();
        foreach ($language_column as $key => $value) {
            if (in_array($value['Field'], $languagesArray)) {
                $activitycreditTableColumn[] = $value['Field'];
                continue;
            }
            $db->query("ALTER TABLE `engine4_sitecredit_activitycredits` DROP `" . $value['Field'] . "`");
        }
        $this->view->activitycreditTableColumn = $activitycreditTableColumn;


        // Get level id
        if (null !== ($id = $this->_getParam('id'))) {
            $level = Engine_Api::_()->getItem('authorization_level', $id);
        } else {
            $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
        }
        if (!empty($level)) {
            $id = $level->level_id;
            $this->view->level_id = $id;
        } else {
            $id = 0;
            $this->view->level_id = 0;
        }
        if (null !== $this->_getParam('module_id')) {
            $module = $this->_getParam('module_id');
        }
        $this->view->module = $module;

        $levelOptions = array();
        foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level) {
            $levelOptions[$level->level_id] = $level->getTitle();
        }
        $this->view->levelOptions = $levelOptions;

        $table = Engine_Api::_()->getDbtable('activitycredits', 'sitecredit');
        $creditTableName = $table->info('name');
        $coreModuletable = Engine_Api::_()->getDbtable('modules', 'core');
        $coreModuletableName = $coreModuletable->info('name');

        $moduleOptions = array();
        $select = $table->select()->setIntegrityCheck(false);
        $select->from($creditTableName, array("DISTINCT($creditTableName.module)"))
                ->join($coreModuletableName, '(BINARY ' . $coreModuletableName . '.name = ' . 'BINARY ' . $creditTableName . '.module )', array("$coreModuletableName.title"));
        $select->order($creditTableName . '.module', 'ASC');

        if (!empty($id)) {
            $select->where('member_level=?', $id);
        }
        $modules = $table->fetchAll($select);
        $this->view->modules = $modules;

        $ActivityOptions = array();
        $select = $table->select()->setIntegrityCheck(false);

        $select->from($creditTableName)->join($coreModuletableName, '(BINARY ' . $coreModuletableName . '.name = ' . 'BINARY ' . $creditTableName . '.module )', array("$coreModuletableName.title"));
        $select->order($creditTableName . '.module', 'ASC');

        if (!empty($id)) {
            $select->where($creditTableName . ".member_level=?", $id);
        }
        if (null !== $this->_getParam('module_id')) {
            if ($this->_getParam('module_id') !== 'all_modules') {
                $select->where($creditTableName . '.module=?', $module);
            }
        }
        $select->order($creditTableName . '.module');
        $values = $table->fetchAll($select);
        $multiOptions = array();
        $this->view->data = $data = $values->toArray();
        $this->view->values = $values;
        foreach ($values as $actionType) {
            $multiOptions[$actionType->activity_type] = $actionType->activity_type;
        }

        // Check post
        if (!$this->getRequest()->isPost()) {
            return;
        }

        foreach ($multiOptions as $key => $value) {
            if (in_array($key, $activityNotShown))
                continue;
            $param['member_level'] = $_POST['member_level'];
            $param['activity_type'] = $key;
            $param['credit_point_first'] = $_POST[$key . '_first'];
            $param['credit_point_other'] = $_POST[$key . '_other'];
            $param['deduction'] = $_POST[$key . '_delete'];
            $param['limit_per_day'] = $_POST[$key . '_limit'];
            foreach ($localeMultiOptions as $index => $value) {
                $column = "language_" . $index;
                $param[$column] = $_POST[$key . '_' . $column];
            }
            Engine_Api::_()->getDbtable('activitycredits', 'sitecredit')->changeStatus($param);
        }
        include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';
    }

    public function deleteAction() {
        //delete an offer
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('id');
        $this->view->offer_id = $id;
        // Check post
        if ($this->getRequest()->isPost()) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

            try {
                $offer = Engine_Api::_()->getItem('offer', $id);
                // delete the blog entry into the database
                $offer->delete();
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }

            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('')
            ));
        }
        // Output
    }

    public function editAction() {

        // edit an offer

        if (!$this->_helper->requireUser()->isValid())
            return;

        $viewer = Engine_Api::_()->user()->getViewer();
        $offer = Engine_Api::_()->getItem('offer', $this->_getParam('id'));

        $this->view->form = $form = new Sitecredit_Form_Admin_Creditoffer();

        $form->setTitle('Edit Offers')
                ->setDescription('Here, admin can edit offers');
        $form->submit->setOptions(array('label' => 'Edit offer'));
        // Populate form
        $formoffer = $offer->toArray();
        $form->populate($formoffer);

        // Check post/form
        if (!$this->getRequest()->isPost()) {
            return;
        }
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }
        // Process
        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
            $values = $form->getValues();

            $offer->setFromArray($values);
            $offer->modified_date = date('Y-m-d H:i:s');
            $offer->save();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('')
        ));
    }

    public function changeStatusAction() {
        // change status of a upgrade request
        $param = array();
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $this->view->id = $id = $param['id'] = $this->_getParam('id');
        $this->view->status = $param['status'] = $this->_getParam('status');
        // Check post
        if ($this->getRequest()->isPost()) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            $var;
            try {

                $requestTable = Engine_Api::_()->getDbtable('upgraderequests', 'sitecredit');
                $select = $requestTable->select()->where("upgraderequest_id =?", $id);
                $requestRow = $requestTable->fetchRow($select);

                $permissionTable = Engine_Api::_()->getDbtable('levels', 'sitecredit');
                $permissionTableName = $permissionTable->info('name');

                $param['user_id'] = $requestRow->user_id;
                $param['basedon'] = 0;

                $select = $permissionTable->select()->from($permissionTableName, array("$permissionTableName.credit_point"));
                $select->where("$permissionTableName.level_id=?", $requestRow->requested_level);

                $result = $permissionTable->fetchRow($select)->credit_point;

                $currentCredits = Engine_Api::_()->getDbtable('credits', 'sitecredit')->Credits($param)->credit;

                if ($currentCredits < $result) {
                    if ($param['status'] == 'approved') {
                        echo "Not Enough Credits";
                        $var = true;
                    } else {
                        $requestTable->updateStatus($param);
                    }
                } else {
                    $var = false;
                    //update status of request

                    if ($param['status'] == 'approved') {
                        //update member level
                        $userTable = Engine_Api::_()->getDbTable('users', 'user');
                        $userTable->update(array('level_id' => $requestRow->requested_level), array('user_id = ?' => $requestRow->user_id));
                        $param['type'] = 'upgrade_request';
                        $param['type_id'] = $id;
                        $param['reason'] = 'upgrade level';
                        $param['credit_point'] = -$result;

                        //change level of member and deduct credits 
                        Engine_Api::_()->getDbtable('credits', 'sitecredit')->insertCredit($param);
                    }
                    $requestTable->updateStatus($param);
                }

                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            if ($var) {

                $this->_forward('success', 'utility', 'core', array(
                    'smoothboxClose' => 5000,
                    'parentRefresh' => 10,
                    'messages' => array('')
                ));
            } else {

                $this->_forward('success', 'utility', 'core', array(
                    'smoothboxClose' => 10,
                    'parentRefresh' => 10,
                    'messages' => array('')
                ));
            }
        }
        // Output
    }

    public function disableStatusAction() {
        // enable disable activity status
        if (!$this->_helper->requireUser()->isValid())
            return;

        $status = Engine_Api::_()->user()->getViewer();
        $activity_id = $this->_getParam('activity_id');
        $module_id = $this->_getParam('module_id');
        $id = $this->_getParam('id');

        $activityTable = Engine_Api::_()->getDbtable('activitycredits', 'sitecredit');
        if ($this->_getParam('status')) {

            $activityTable->update(array('status' => 'enabled'), array('activitycredit_id = ?' => $activity_id));
        } else {

            $activityTable->update(array('status' => 'disabled'), array('activitycredit_id = ?' => $activity_id));
        }

        return $this->_helper->redirector->gotoRoute(array('module' => 'sitecredit', 'controller' => 'credit', 'action' => 'manage', 'id' => $id, 'module_id' => $module_id), 'admin_default', true);
    }

}
