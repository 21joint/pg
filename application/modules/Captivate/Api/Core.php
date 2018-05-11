<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Api_Core extends Core_Api_Abstract {

    /**
     * This function return the complete path of image, from the photo id.
     *
     * @param id: The photo id.
     * @param type: The type of photo required.
     * @return Image path.
     */
    public function displayPhoto($id, $type = 'thumb.profile') {
        if (empty($id)) {
            return null;
        }
        $file = Engine_Api::_()->getItemTable('storage_file')->getFile($id, $type);
        if (!$file) {
            return null;
        }

        return $file->map();
    }

    /**
     * Plugin which return the error, if Siteadmin not using correct version for the plugin.
     *
     */
    public function isModulesSupport() {
        $isCaptivateActivate = Engine_Api::_()->getApi('settings', 'core')->getSetting('captivate.isActivate', 0);
        if (empty($isCaptivateActivate))
            return array();

        $modArray = array(
            'siteevent' => '4.8.8p3',
            'siteeventticket' => '4.8.8p3',
            'sitecontentcoverphoto' => '4.8.8p5',
            'siteusercoverphoto' => '4.8.8p4',
            'sitereview' => '4.8.8p1',
            'sitereviewlistingtype' => '4.8.8p1',
            'sitealbum' => '4.8.8p1',
            'sitemenu' => '4.8.8p3'
        );
        $finalModules = array();
        foreach ($modArray as $key => $value) {
            $isModEnabled = Engine_Api::_()->hasModuleBootstrap($key);
            if (!empty($isModEnabled)) {
                $getModVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule($key);
                $isModSupport = $this->checkVersion($getModVersion->version, $value);
                if (!$isModSupport) {
                    $finalModules[] = $getModVersion->title;
                }
            }
        }
        return $finalModules;
    }

    function checkVersion($databaseVersion, $checkDependancyVersion) {
        if (strcasecmp($databaseVersion, $checkDependancyVersion) == 0)
            return -1;
        $databaseVersionArr = explode(".", $databaseVersion);
        $checkDependancyVersionArr = explode('.', $checkDependancyVersion);
        $fValueCount = $count = count($databaseVersionArr);
        $sValueCount = count($checkDependancyVersionArr);
        if ($fValueCount > $sValueCount)
            $count = $sValueCount;
        for ($i = 0; $i < $count; $i++) {
            $fValue = $databaseVersionArr[$i];
            $sValue = $checkDependancyVersionArr[$i];
            if (is_numeric($fValue) && is_numeric($sValue)) {
                $result = $this->compareValues($fValue, $sValue);
                if ($result == -1) {
                    if (($i + 1) == $count) {
                        return $this->compareValues($fValueCount, $sValueCount);
                    } else
                        continue;
                }
                return $result;
            }
            elseif (is_string($fValue) && is_numeric($sValue)) {
                $fsArr = explode("p", $fValue);
                $result = $this->compareValues($fsArr[0], $sValue);
                return $result == -1 ? 1 : $result;
            } elseif (is_numeric($fValue) && is_string($sValue)) {
                $ssArr = explode("p", $sValue);
                $result = $this->compareValues($fValue, $ssArr[0]);
                return $result == -1 ? 0 : $result;
            } elseif (is_string($fValue) && is_string($sValue)) {
                $fsArr = explode("p", $fValue);
                $ssArr = explode("p", $sValue);
                $result = $this->compareValues($fsArr[0], $ssArr[0]);
                if ($result != -1)
                    return $result;
                $result = $this->compareValues($fsArr[1], $ssArr[1]);
                return $result;
            }
        }
    }

    public function compareValues($firstVal, $secondVal) {
        $num = $firstVal - $secondVal;
        return ($num > 0) ? 1 : ($num < 0 ? 0 : -1);
    }

    public function checkNavigationWidgetExists() {
        $db = Engine_Db_Table::getDefaultAdapter();
        $page_id = $db->select()
                ->from('engine4_core_pages', 'page_id')
                ->where('name = ?', 'header')
                ->limit(1)
                ->query()
                ->fetchColumn();
        $content_id = '';
        if (!empty($page_id)) {
            $content_id = $db->select()
                    ->from('engine4_core_content', 'content_id')
                    ->where('name = ?', 'captivate.navigation')
                    ->where('page_id = ?', $page_id)
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
        }

        return $content_id;
    }

    /**
     * Get Widgetized PageId
     * @param $params
     */
    public function getWidgetizedPageId($params = array()) {
        //GET CORE CONTENT TABLE
        $tableNamePages = Engine_Api::_()->getDbtable('pages', 'core');
        $page_id = $tableNamePages->select()
                ->from($tableNamePages->info('name'), 'page_id')
                ->where('name =?', $params['name'])
                ->query()
                ->fetchColumn();
        return $page_id;
    }

    /**
     * Check Widget Exist
     * @param $params
     */
    public function checkWidgetExist($params = array()) {
        //GET CORE CONTENT TABLE
        $tableNameContent = Engine_Api::_()->getDbtable('content', 'core');
        $content_id = $tableNameContent->select()
                ->from($tableNameContent->info('name'), 'content_id')
                ->where('page_id =?', $this->getWidgetizedPageId(array('name' => 'core_index_index')))
                ->where('name =?', $params['name'])
                ->query()
                ->fetchColumn();
        return $content_id;
    }

    /**
     * Get Widgetized Page Layout Value
     * @param $params
     */
    public function getWidgetizedPageLayoutValue($params = array()) {
        //GET CORE CONTENT TABLE
        $tableNamePages = Engine_Api::_()->getDbtable('pages', 'core');
        $select = $tableNamePages->select()
                ->from($tableNamePages->info('name'), 'layout');

        if (isset($params['name'])) {
            $select->where('name =?', $params['name']);
        }
        if (isset($params['page_id'])) {
            $select->where('page_id =?', $params['page_id']);
        }
        $layout = $select->query()
                ->fetchColumn();
        return $layout;
    }

    /**
     * Get language array
     *
     * @param string $page_url
     * @return array $localeMultiOptions
     */
    public function getLanguageArray() {

        //PREPARE LANGUAGE LIST
        $languageList = Zend_Registry::get('Zend_Translate')->getList();

        //PREPARE DEFAULT LANGUAGE
        $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en');
        if (!in_array($defaultLanguage, $languageList)) {
            if ($defaultLanguage == 'auto' && isset($languageList['en'])) {
                $defaultLanguage = 'en';
            } else {
                $defaultLanguage = null;
            }
        }
        //INIT DEFAULT LOCAL
        $localeObject = Zend_Registry::get('Locale');
        $languages = Zend_Locale::getTranslationList('language', $localeObject);
        $territories = Zend_Locale::getTranslationList('territory', $localeObject);

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
                $localeMultiOptions[$key] = Zend_Registry::get('Zend_Translate')->_('Unknown');
            }
        }
        $localeMultiOptions = array_merge(array(
            $defaultLanguage => $defaultLanguage
                ), $localeMultiOptions);
        return $localeMultiOptions;
    }

    public function getContentPageId($params = array()) {
        //GET CORE CONTENT TABLE
        $tableNameContent = Engine_Api::_()->getDbtable('content', 'core');
        $page_id = $tableNameContent->select()
                ->from($tableNameContent->info('name'), 'page_id')
                ->where('content_id =?', $params['content_id'])
                ->query()
                ->fetchColumn();
        return $page_id;
    }

    /**
     * Get Widgetized PageId
     * @param $params
     */
    public function getBackupHomePageId($params = array()) {
        //GET CORE CONTENT TABLE
        $tableNamePages = Engine_Api::_()->getDbtable('pages', 'core');
        $page_id = $tableNamePages->select()
                ->from($tableNamePages->info('name'), 'page_id')
                ->where('url =?', 'home')
                ->query()
                ->fetchColumn();
        return $page_id;
    }

    public function getBackupOfHomePage() {

        $page_id = $this->getWidgetizedPageId(array('name' => 'core_index_index'));
        $tableNameContent = Engine_Api::_()->getDbtable('content', 'core');
        $tableNameContentName = $tableNameContent->info('name');
        $db = Engine_Db_Table::getDefaultAdapter();

        //CHECK PAGE EXIST OR NOT
        $home_backup_page_id = $this->getBackupHomePageId();

        //CREATE PAGE
        if (empty($home_backup_page_id)) {
            $db->query("INSERT IGNORE INTO `engine4_core_pages` ( `name`, `displayname`, `url`, `title`, `description`, `keywords`, `custom`, `fragment`, `layout`, `levels`, `provides`, `view_count`, `search`) VALUES (NULL, 'Landing Page - Backup of Landing Page on Installation of Captivate Theme', 'home', 'Backup of Landing Page on Installation of Captivate Theme Plugin', '', '', '0', '0', '', NULL, NULL, '0', '0');");
        }

        //GET EXISTING PAGE ID
        $home_backup_page_id = $this->getBackupHomePageId();

        $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $home_backup_page_id");
        //GET MAIN CONTAINER WORK

        $select = $tableNameContent->select()
                ->from($tableNameContentName, '*')
                ->where('page_id =?', $page_id)
                ->where('name =?', 'main')
                ->where('type =?', 'container');

        $mainRow = $tableNameContent->fetchRow($select);

        if (!empty($mainRow)) {

            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $home_backup_page_id,
                'parent_content_id' => null,
                'order' => $mainRow->order,
                'params' => $mainRow->params ? json_encode($mainRow->params) : ''
            ));
            $content_id = $db->lastInsertId('engine4_core_content');

            $results = $tableNameContent->select()
                    ->from($tableNameContentName, '*')
                    ->where('page_id =?', $page_id)
                    ->where('name in (?)', array('left', 'middle', 'right'))
                    ->where('type =?', 'container')
                    ->where('parent_content_id =?', $mainRow->content_id)
                    ->query()
                    ->fetchAll();

            foreach ($results as $values) {
                $db->insert('engine4_core_content', array(
                    'type' => $values['type'],
                    'name' => $values['name'],
                    'page_id' => $home_backup_page_id,
                    'parent_content_id' => $content_id,
                    'order' => $values['order'],
                    'params' => $values['params']
                ));
            }

            //LEFT CONTAINER WIDGETS
            $select = $tableNameContent->select()
                    ->from($tableNameContentName, '*')
                    ->where('page_id =?', $page_id)
                    ->where('parent_content_id =?', $mainRow->content_id)
                    ->where('name =?', 'left')
                    ->where('type =?', 'container');

            $leftRow = $tableNameContent->fetchRow($select);

            if (!empty($leftRow)) {
                $results = $tableNameContent->select()
                        ->from($tableNameContentName, '*')
                        ->where('page_id =?', $page_id)
                        ->where('parent_content_id =?', $leftRow->content_id)
                        ->where('type =?', 'widget')
                        ->query()
                        ->fetchAll();

                $select = $tableNameContent->select()
                        ->from($tableNameContentName, '*')
                        ->where('page_id =?', $home_backup_page_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'left')
                        ->where('type =?', 'container');

                $row = $tableNameContent->fetchRow($select);

                foreach ($results as $values) {
                    $db->insert('engine4_core_content', array(
                        'type' => $values['type'],
                        'name' => $values['name'],
                        'page_id' => $home_backup_page_id,
                        'parent_content_id' => $row->content_id,
                        'order' => $values['order'],
                        'params' => $values['params']
                    ));
                }
            }
            //END LEFT CONTAINER WIDGET
            //MIDDLE CONTAINER WIDGETS
            $select = $tableNameContent->select()
                    ->from($tableNameContentName, '*')
                    ->where('page_id =?', $page_id)
                    ->where('parent_content_id =?', $mainRow->content_id)
                    ->where('name =?', 'middle')
                    ->where('type =?', 'container');

            $middleRow = $tableNameContent->fetchRow($select);

            if (!empty($middleRow)) {
                $results = $tableNameContent->select()
                        ->from($tableNameContentName, '*')
                        ->where('page_id =?', $page_id)
                        ->where('parent_content_id =?', $middleRow->content_id)
                        ->where('type =?', 'widget')
                        ->query()
                        ->fetchAll();

                $select = $tableNameContent->select()
                        ->from($tableNameContentName, '*')
                        ->where('page_id =?', $home_backup_page_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'middle')
                        ->where('type =?', 'container');

                $row = $tableNameContent->fetchRow($select);

                foreach ($results as $values) {
                    $db->insert('engine4_core_content', array(
                        'type' => $values['type'],
                        'name' => $values['name'],
                        'page_id' => $home_backup_page_id,
                        'parent_content_id' => $row->content_id,
                        'order' => $values['order'],
                        'params' => $values['params']
                    ));
                }
            }
            //END MIDDLE CONTAINER WIDGET
            //RIGHT CONTAINER WIDGETS
            $select = $tableNameContent->select()
                    ->from($tableNameContentName, '*')
                    ->where('page_id =?', $page_id)
                    ->where('parent_content_id =?', $mainRow->content_id)
                    ->where('name =?', 'right')
                    ->where('type =?', 'container');

            $rightRow = $tableNameContent->fetchRow($select);

            if (!empty($rightRow)) {
                $results = $tableNameContent->select()
                        ->from($tableNameContentName, '*')
                        ->where('page_id =?', $page_id)
                        ->where('parent_content_id =?', $rightRow->content_id)
                        ->where('type =?', 'widget')
                        ->query()
                        ->fetchAll();

                $select = $tableNameContent->select()
                        ->from($tableNameContentName, '*')
                        ->where('page_id =?', $home_backup_page_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'right')
                        ->where('type =?', 'container');

                $row = $tableNameContent->fetchRow($select);

                foreach ($results as $values) {
                    $db->insert('engine4_core_content', array(
                        'type' => $values['type'],
                        'name' => $values['name'],
                        'page_id' => $home_backup_page_id,
                        'parent_content_id' => $row->content_id,
                        'order' => $values['order'],
                        'params' => $values['params']
                    ));
                }
            }
            //END RIGHT CONTAINER WIDGET
        }

        //TOP CONTAINER
        $select = $tableNameContent->select()
                ->from($tableNameContentName, '*')
                ->where('page_id =?', $page_id)
                ->where('name =?', 'top')
                ->where('type =?', 'container');

        $topRow = $tableNameContent->fetchRow($select);

        if (!empty($topRow)) {

            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $home_backup_page_id,
                'parent_content_id' => null,
                'order' => $topRow->order,
                //'params' => json_encode($topRow->params)
                'params' => $topRow->params ? json_encode($topRow->params) : ''
            ));
            $content_id = $db->lastInsertId('engine4_core_content');

            $results = $tableNameContent->select()
                    ->from($tableNameContentName, '*')
                    ->where('page_id =?', $page_id)
                    ->where('name in (?)', array('left', 'middle', 'right'))
                    ->where('type =?', 'container')
                    ->where('parent_content_id =?', $topRow->content_id)
                    ->query()
                    ->fetchAll();

            foreach ($results as $values) {
                $db->insert('engine4_core_content', array(
                    'type' => 'container',
                    'name' => $values['name'],
                    'page_id' => $home_backup_page_id,
                    'parent_content_id' => $content_id,
                    'order' => $values['order'],
                    'params' => $values['params']
                ));
            }

            //MIDDLE CONTAINER WIDGETS
            $select = $tableNameContent->select()
                    ->from($tableNameContentName, '*')
                    ->where('page_id =?', $page_id)
                    ->where('parent_content_id =?', $topRow->content_id)
                    ->where('name =?', 'middle')
                    ->where('type =?', 'container');

            $topMiddleRow = $tableNameContent->fetchRow($select);

            if (!empty($topMiddleRow)) {
                $results = $tableNameContent->select()
                        ->from($tableNameContentName, '*')
                        ->where('page_id =?', $page_id)
                        ->where('parent_content_id =?', $topMiddleRow->content_id)
                        ->where('type =?', 'widget')
                        ->query()
                        ->fetchAll();

                $select = $tableNameContent->select()
                        ->from($tableNameContentName, '*')
                        ->where('page_id =?', $home_backup_page_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'middle')
                        ->where('type =?', 'container');

                $row = $tableNameContent->fetchRow($select);

                foreach ($results as $values) {
                    $db->insert('engine4_core_content', array(
                        'type' => $values['type'],
                        'name' => $values['name'],
                        'page_id' => $home_backup_page_id,
                        'parent_content_id' => $row->content_id,
                        'order' => $values['order'],
                        'params' => $values['params']
                    ));
                }
            }
            //END MIDDLE CONTAINER WIDGET
        }


        //GET BOTTOM CONTAINER
        $select = $tableNameContent->select()
                ->from($tableNameContentName, '*')
                ->where('page_id =?', $page_id)
                ->where('name =?', 'bottom')
                ->where('type =?', 'container');

        $bottomRow = $tableNameContent->fetchRow($select);

        if (!empty($bottomRow)) {

            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'bottom',
                'page_id' => $home_backup_page_id,
                'parent_content_id' => null,
                'order' => $bottomRow->order,
                'params' => $bottomRow->params ? json_encode($bottomRow->params) : ''
            ));
            $content_id = $db->lastInsertId('engine4_core_content');

            $results = $tableNameContent->select()
                    ->from($tableNameContentName, '*')
                    ->where('page_id =?', $page_id)
                    ->where('name in (?)', array('left', 'middle', 'right'))
                    ->where('type =?', 'container')
                    ->where('parent_content_id =?', $bottomRow->content_id)
                    ->query()
                    ->fetchAll();

            foreach ($results as $values) {
                $db->insert('engine4_core_content', array(
                    'type' => 'container',
                    'name' => $values['name'],
                    'page_id' => $home_backup_page_id,
                    'parent_content_id' => $content_id,
                    'order' => $values['order'],
                    'params' => $values['params']
                ));
            }

            //MIDDLE CONTAINER WIDGETS
            $select = $tableNameContent->select()
                    ->from($tableNameContentName, '*')
                    ->where('page_id =?', $page_id)
                    ->where('parent_content_id =?', $bottomRow->content_id)
                    ->where('name =?', 'middle')
                    ->where('type =?', 'container');

            $bottomMiddleRow = $tableNameContent->fetchRow($select);

            if (!empty($bottomMiddleRow)) {
                $results = $tableNameContent->select()
                        ->from($tableNameContentName, '*')
                        ->where('page_id =?', $page_id)
                        ->where('parent_content_id =?', $bottomMiddleRow->content_id)
                        ->where('type =?', 'widget')
                        ->query()
                        ->fetchAll();

                $select = $tableNameContent->select()
                        ->from($tableNameContentName, '*')
                        ->where('page_id =?', $home_backup_page_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'middle')
                        ->where('type =?', 'container');

                $row = $tableNameContent->fetchRow($select);

                foreach ($results as $values) {
                    $db->insert('engine4_core_content', array(
                        'type' => $values['type'],
                        'name' => $values['name'],
                        'page_id' => $home_backup_page_id,
                        'parent_content_id' => $row->content_id,
                        'order' => $values['order'],
                        'params' => $values['params']
                    ));
                }
            }
            //END MIDDLE CONTAINER WIDGET
        }
    }

    public function setDefaultLayout($obj) {
        Engine_Api::_()->captivate()->getBackupOfHomePage();
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $db = Engine_Db_Table::getDefaultAdapter();
        $page_id = $db->select()
                ->from('engine4_core_pages', 'page_id')
                ->where('name = ?', 'core_index_index')
                ->limit(1)
                ->query()
                ->fetchColumn();
        if (!empty($page_id) && !empty($obj) && !empty($obj['captivate_landing_page_layout'])) {
            $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");

            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $page_id,
                'order' => 1,
            ));
            $top_id = $db->lastInsertId();

            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'middle',
                'parent_content_id' => $top_id,
                'page_id' => $page_id,
                'order' => 2,
            ));
            $top_middle_id = $db->lastInsertId();

            $isSitehomepagevideoModEnabled = Engine_Api::_()->hasModuleBootstrap('sitehomepagevideo');
            if ($isSitehomepagevideoModEnabled) {
                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'sitehomepagevideo.videos',
                    'page_id' => $page_id,
                    'parent_content_id' => $top_middle_id,
                    'params' => '{"columnHeightWidth":"0","videoScreenHeight":"1","showStartViewing":"1","columnHeight":"600","columnWidth":"","selectedSlides":"1","showNextLink":"1","showLogo":"0","logo":"","sitehomepagevideoSignupLoginLink":"0","sitehomepagevideoBrowseMenus":"0","max":"20","sitehomepagevideoFirstImprotantLink":"0","sitehomepagevideoFirstTitle":"Important Title & Link","sitehomepagevideoFirstUrl":"#","sitehomepagevideoHtmlTitle":"BRING PEOPLE TOGETHER","sitehomepagevideoHtmlDescription":"Watch Videos & Explore Channels","sitehomepagevideoTitleColor":"0","sitehomepagevideoHowItWorks":"0","sitehomepagevideoSignupLoginButton":"0","sitehomepagevideoSearchBox":"4","showSignupFields":"","showTagLine":"1","showLeftRightSignupButton":"0","playVideoSound":"0","nomobile":"","title":"","name":"sitehomepagevideo.videos"}',
                    'order' => 3,
                ));
            } else {
                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'captivate.images',
                    'page_id' => $page_id,
                    'parent_content_id' => $top_middle_id,
                    'params' => '{"showImages":"1","selectedImages":"","width":"","height":"583","speed":"5000","order":"2","showLogo":"1","logo":"","captivateBrowseMenus":"1","max":"20","captivateSignupLoginLink":"1","captivateFirstImprotantLink":"1","captivateFirstTitle":"Important Title & Link","captivateFirstUrl":"#","captivateHtmlTitle":"BRING PEOPLE TOGETHER","captivateHtmlDescription":"Watch Videos, Explore Channels and Create & Share Playlists.","captivateHowItWorks":"1","captivateSignupLoginButton":"1","captivateSearchBox":"2","title":"","nomobile":"0","name":"captivate.images"}',
                    'order' => 3,
                ));
            }

            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => 3,
            ));
            $main_id = $db->lastInsertId();

            // Insert main-middle
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
                'order' => 4,
            ));
            $main_middle_id = $db->lastInsertId();

            if (Engine_Api::_()->hasModuleBootstrap('sitevideo')) {
                // Insert main-middle
                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'sitevideo.html-block-videos',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_middle_id,
                    'params' => '{"title":"","titleCount":true,"nomobile":"0","name":"sitevideo.html-block-videos"}',
                    'order' => 5,
                ));

                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'seaocore.sponsored-categories-with-image',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_middle_id,
                    'params' => '{"contentModuleSponsoredCategories":"sitevideo_video","width":"400","height":"200","title":"","nomobile":"0","name":"seaocore.sponsored-categories-with-image"}',
                    'order' => 6,
                ));

                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'sitevideo.best-videos',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_middle_id,
                    'params' => '{"title":"Best Videos From Our Community","videoType":"","category_id":"0","subcategory_id":null,"hidden_video_category_id":"","hidden_video_subcategory_id":"","hidden_video_subsubcategory_id":"","showVideo":"featured","videoOption":["title","owner","creationDate","view","like","comment","duration","watchlater","favourite","facebook","twitter","linkedin","googleplus"],"showLink":"0","videoHeight":"200","videoWidth":"281","popularType":"random","titleTruncation":"27","nomobile":"0","name":"sitevideo.best-videos"}',
                    'order' => 7,
                ));

                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'sitevideo.video-carousel',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_middle_id,
                    'params' => '{"title":"Popular Videos","videoType":"","category_id":"0","subcategory_id":null,"hidden_video_category_id":"","hidden_video_subcategory_id":"","hidden_video_subsubcategory_id":"","showVideo":"featured","videoOption":["title","owner","creationDate","view","like","comment","duration","watchlater","favourite","facebook","twitter","linkedin","googleplus"],"showPagination":"1","showLink":"0","videoHeight":"150","videoWidth":"218","popularType":"view","interval":"3500","itemCount":"10","itemCountPerPage":"50","titleTruncation":"20","nomobile":"0","name":"sitevideo.video-carousel"}',
                    'order' => 8,
                ));

                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'sitevideo.channel-carousel',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_middle_id,
                    'params' => '{"title":"Popular Channels","category_id":"0","subcategory_id":null,"hidden_category_id":"","hidden_subcategory_id":"","hidden_subsubcategory_id":"","showChannel":"featured","channelOption":["title","owner","like","comment","favourite","numberOfVideos","subscribe","facebook","twitter","linkedin","googleplus"],"showPagination":"1","showLink":"0","channelHeight":"150","channelWidth":"217","popularType":"random","interval":"3500","itemCount":"10","itemCountPerPage":"50","titleTruncation":"20","nomobile":"0","name":"sitevideo.channel-carousel"}',
                    'order' => 9,
                ));
            } else {
                // Insert main-middle
                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'captivate.html-block',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_middle_id,
                    'params' => '{"title":"","titleCount":true,"nomobile":"0","name":"captivate.html-block"}',
                    'order' => 5,
                ));
            }

            if (Engine_Api::_()->hasModuleBootstrap('sitemember')) {
                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'sitemember.recent-popular-random-members',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_middle_id,
                    'params' => '{"title":"Popular Members","titleCount":true,"viewType":"gridview","viewtype":"vertical","columnWidth":"48","fea_spo":"","circularImage":"0","circularImageHeight":"48","has_photo":"1","titlePosition":"1","viewtitletype":"horizontal","columnHeight":"48","orderby":"view_count","interval":"overall","links":"","memberInfo":"","customParams":"5","custom_field_title":"0","custom_field_heading":"0","itemCount":"120","truncation":"16","detactLocation":"0","defaultLocationDistance":"1000","nomobile":"0","name":"sitemember.recent-popular-random-members"}',
                    'order' => 10,
                ));
            }
        }
        $captivate_search_width = 162;
        $max = 4;
        $title = Engine_Api::_()->getApi('settings', 'core')->getSetting('core_general_site_title', $view->translate('_SITE_TITLE'));
        $strLength = strlen($title);
        $isSitemenuModEnabled = Engine_Api::_()->hasModuleBootstrap('sitemenu');
        $isAdvSearchModEnabled = Engine_Api::_()->hasModuleBootstrap('siteadvsearch');
        $page_id = $db->select()
                ->from('engine4_core_pages', 'page_id')
                ->where('name = ?', 'footer')
                ->limit(1)
                ->query()
                ->fetchColumn();
        if (!empty($page_id) && !empty($obj) && !empty($obj['captivate_landing_page_layout'])) {
            $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");

            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => 1,
            ));
            $main_id = $db->lastInsertId();

            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'captivate.menu-footer',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
                'order' => 2,
            ));

            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'core.menu-footer',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
                'order' => 3,
            ));
        }

        $page_id = $db->select()
                ->from('engine4_core_pages', 'page_id')
                ->where('name = ?', 'header')
                ->limit(1)
                ->query()
                ->fetchColumn();
        if (!empty($page_id) && !empty($obj) && !empty($obj['captivate_landing_page_layout'])) {
            $tableNameContent = Engine_Api::_()->getDbtable('content', 'core');
            $header_page_id = $this->getWidgetizedPageId(array('name' => 'header'));

            $main_content_id = $tableNameContent->select()
                    ->from($tableNameContent->info('name'), 'content_id')
                    ->where('name =?', 'main')
                    ->where('page_id =?', $header_page_id)
                    ->query()
                    ->fetchColumn();
            $db = Engine_Db_Table::getDefaultAdapter();
            if (!empty($main_content_id)) {
                $params = $tableNameContent->select()
                        ->from($tableNameContent->info('name'), 'params')
                        ->where('name =?', 'core.menu-logo')
                        ->where('page_id =?', $header_page_id)
                        ->where('params like (?)', '%logo%')->query()
                        ->fetchColumn();
                if ($params) {
                    $encode = json_decode($params);
                    if (empty($encode->logo)) {
                        if ($strLength > 14) {
                            $captivate_search_width = 72;
                            $max = 3;
                        }
                    }
                }
            }
            $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => 1,
            ));
            $main_id = $db->lastInsertId();

            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'core.html-block',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
                'order' => 2,
                'params' => '{"title":"","data":"<script type=\"text\/javascript\"> \r\nif(typeof(window.jQuery) !=  \"undefined\") {\r\njQuery.noConflict();\r\n}\r\n<\/script>","nomobile":"0","name":"core.html-block"}'
            ));

            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'core.menu-logo',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
                'order' => 3,
                'params' => '{"title":"","name":"core.menu-logo","logo":"","nomobile":"0"}'
            ));

            if ($isSitemenuModEnabled) {

                $db->query("INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('captivate_core_mini_admin', 'core', 'Admin', 'Captivate_Plugin_Menus', '', 'user_settings', '', 1, 0, 10),
( 'captivate_core_mini_auth', 'user', 'Sign Out', 'Captivate_Plugin_Menus', '', 'user_settings', '', 1, 0, 11),
( 'captivate_core_mini_signin', 'user', 'Sign In', 'Captivate_Plugin_Menus', '', 'core_mini', '', 1, 0, 12);
");
                $db->query("UPDATE `engine4_core_menuitems` SET `enabled` = '0' WHERE `engine4_core_menuitems`.`name` = 'core_mini_auth';");
                $db->query("UPDATE `engine4_core_menuitems` SET `enabled` = '0' WHERE `engine4_core_menuitems`.`name` = 'core_mini_admin';");

                //CHECK THAT ALBUM PLUGIN IS INSTALLED OR NOT
                $select = new Zend_Db_Select($db);
                $select
                        ->from('engine4_core_modules')
                        ->where('name = ?', 'siteeventticket')
                        ->where('enabled = ?', 1);
                $check_siteeventticket = $select->query()->fetchObject();
                if (!empty($check_siteeventticket)) {
                    $db->query("UPDATE `engine4_core_menuitems` SET `enabled` = '0' WHERE `engine4_core_menuitems`.`name` = 'core_mini_siteeventticketmytickets';");

                    $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
("captivate_siteeventticket_main_ticket", "siteeventticket", "My Tickets", "Captivate_Plugin_Menus", \'{"route":"siteeventticket_order", "action":"my-tickets"}\', "user_settings", "", 1, 0, 9)');
                }

                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'captivate.browse-menu-main',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_id,
                    'order' => 4,
                    'params' => '{"max":"' . $max . '","title":"","nomobile":"0","name":"captivate.browse-menu-main"}'
                ));
                $captivate_search_width = 162;
            } else {

                $db->query("UPDATE `engine4_core_menuitems` SET `enabled` = '1' WHERE `engine4_core_menuitems`.`name` = 'core_mini_auth';");
                $db->query("UPDATE `engine4_core_menuitems` SET `enabled` = '1' WHERE `engine4_core_menuitems`.`name` = 'core_mini_admin';");
                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'captivate.browse-menu-main',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_id,
                    'order' => 4,
                    'params' => '{"max":"2","title":"","nomobile":"0","name":"captivate.browse-menu-main"}'
                ));
            }

            if ($isAdvSearchModEnabled) {
                if ($isSitemenuModEnabled) {
                    $captivate_search_width = 162;
                } else {
                    $captivate_search_width = 96;
                }
                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'siteadvsearch.search-box',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_id,
                    'order' => 5,
                    'params' => '{"title":"","titleCount":true,"advsearch_search_box_width":"' . $captivate_search_width . '","advsearch_search_box_width_for_nonloggedin":"275","nomobile":"0","name":"siteadvsearch.search-box"}'
                ));
            } else {
                if ($isSitemenuModEnabled) {
                    $db->insert('engine4_core_content', array(
                        'type' => 'widget',
                        'name' => 'captivate.search-box',
                        'page_id' => $page_id,
                        'parent_content_id' => $main_id,
                        'order' => 5,
                        'params' => '{"title":"","titleCount":true,"captivate_search_width":"' . $captivate_search_width . '","captivate_search_box_width_for_nonloggedin":"275","nomobile":"0","name":"captivate.search-box"}'
                    ));
                } else {
                    if ($strLength >= 12) {
                        $captivate_search_width = 118;
                    }
                    $db->insert('engine4_core_content', array(
                        'type' => 'widget',
                        'name' => 'captivate.search-box',
                        'page_id' => $page_id,
                        'parent_content_id' => $main_id,
                        'order' => 5,
                        'params' => '{"title":"","titleCount":true,"captivate_search_width":"' . $captivate_search_width . '","captivate_search_box_width_for_nonloggedin":"275","nomobile":"0","name":"captivate.search-box"}'
                    ));
                }
            }

            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'sitevideo.post-new-video',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
                'order' => 6,
                'params' => '{"title":"","titleCount":true,"upload_button":"1","upload_button_title":"Upload","nomobile":"0","name":"sitevideo.post-new-video"}'
            ));

            if ($isSitemenuModEnabled) {
                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'sitemenu.menu-mini',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_id,
                    'order' => 7,
                    'params' => '{"sitemenu_on_logged_out":"1","sitemenu_show_icon":"1","no_of_updates":"10","sitemenu_show_in_mini_options":"0","search_position":"1","changeMyLocation":"0","showLocationBasedContent":"0","sitemenu_mini_search_width":"275","sitemenu_enable_login_lightbox":"1","sitemenu_enable_signup_lightbox":"1","title":"","nomobile":"0","name":"sitemenu.menu-mini"}'
                ));
            } else {
                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'core.menu-mini',
                    'page_id' => $page_id,
                    'parent_content_id' => $main_id,
                    'order' => 7,
                    'params' => ''
                ));
            }

            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'captivate.navigation',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
                'order' => 8,
            ));

            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'seaocore.seaocores-lightbox',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
                'order' => 9,
            ));
        }

        $member_home_page_id = $this->getWidgetizedPageId(array('name' => 'user_index_home'));
        $tableNameContent = Engine_Api::_()->getDbtable('content', 'core');
        $tableNameContentName = $tableNameContent->info('name');
        $top_content_id = $tableNameContent->select()
                ->from($tableNameContentName, 'content_id')
                ->where('page_id =?', $member_home_page_id)
                ->where('name =?', 'top')
                ->query()
                ->fetchColumn();
        if (empty($top_content_id)) {
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $member_home_page_id,
                'parent_content_id' => null,
                'order' => 1,
                'params' => ''
            ));
            $content_id = $db->lastInsertId('engine4_core_content');
            $middle_content_id = $tableNameContent->select()
                    ->from($tableNameContentName, 'content_id')
                    ->where('page_id =?', $member_home_page_id)
                    ->where('parent_content_id =?', $content_id)
                    ->where('name =?', 'middle')
                    ->query()
                    ->fetchColumn();

            if (empty($middle_content_id)) {
                $db->insert('engine4_core_content', array(
                    'type' => 'container',
                    'name' => 'middle',
                    'page_id' => $member_home_page_id,
                    'parent_content_id' => $content_id,
                    'order' => 2,
                    'params' => ''
                ));

                $content_id = $db->lastInsertId('engine4_core_content');

                $middle_banner_id = $tableNameContent->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $member_home_page_id)
                        ->where('parent_content_id =?', $content_id)
                        ->where('name =?', 'captivate.banner-images')
                        ->query()
                        ->fetchColumn();
                if (!$middle_banner_id) {
                    $db->insert('engine4_core_content', array(
                        'type' => 'widget',
                        'name' => 'captivate.banner-images',
                        'page_id' => $member_home_page_id,
                        'parent_content_id' => $content_id,
                        'order' => 1,
                        'params' => '{"showBanners":"1","selectedBanners":"","width":"","height":"280","speed":"5000","order":"2","captivateHtmlTitle":"Albums and Videos that you\'d love","captivateHtmlDescription":"The foremost source to explore albums and watch videos","title":"","nomobile":"0","name":"captivate.banner-images"}'
                    ));
                }
            }
            $db->query("UPDATE `engine4_core_content` SET  `order` =  '2' WHERE  `engine4_core_content`.`page_id` = $member_home_page_id AND `engine4_core_content`.`name` = 'main' LIMIT 1 ;");
        } else {
            $middle_content_id = $tableNameContent->select()
                    ->from($tableNameContentName, 'content_id')
                    ->where('page_id =?', $member_home_page_id)
                    ->where('parent_content_id =?', $top_content_id)
                    ->where('name =?', 'middle')
                    ->query()
                    ->fetchColumn();

            if (!empty($middle_content_id)) {

                $middle_banner_id = $tableNameContent->select()
                        ->from($tableNameContentName, 'content_id')
                        ->where('page_id =?', $member_home_page_id)
                        ->where('parent_content_id =?', $middle_content_id)
                        ->where('name =?', 'captivate.banner-images')
                        ->query()
                        ->fetchColumn();

                if (!$middle_banner_id) {
                    $db->insert('engine4_core_content', array(
                        'type' => 'widget',
                        'name' => 'captivate.banner-images',
                        'page_id' => $member_home_page_id,
                        'parent_content_id' => $middle_content_id,
                        'order' => 1,
                        'params' => '{"showBanners":"1","selectedBanners":"","width":"","height":"280","speed":"5000","order":"2","captivateHtmlTitle":"Albums and Videos that you\'d love","captivateHtmlDescription":"The foremost source to explore albums and watch videos","title":"","nomobile":"0","name":"captivate.banner-images"}'
                    ));
                }
                $db->query("UPDATE `engine4_core_content` SET  `order` =  '2' WHERE  `engine4_core_content`.`page_id` = $member_home_page_id AND `engine4_core_content`.`name` = 'main' LIMIT 1;");

                if (Engine_Api::_()->hasModuleBootstrap('spectacular')) {
                    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $member_home_page_id AND `engine4_core_content`.`name` = 'spectacular.banner-images' LIMIT 1;");
                }
            }
        }
    }

}
