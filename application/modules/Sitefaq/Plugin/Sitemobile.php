<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepage
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitefaq_Plugin_Sitemobile {

    protected $_pagesTable;
    protected $_contentTable;

    public function onRenderLayoutMobileSMDefault($event) {
        $view = $event->getPayload();
        if (!($view instanceof Zend_View_Interface)) {
            return;
        }
        $view->headScriptSM()
                ->appendFile($view->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/scripts/smcore.js');
    }

    public function onIntegrated() {

        $this->_pagesTable = Engine_Api::_()->getApi('modules', 'sitemobile')->_pagesTable;
        $this->_contentTable = Engine_Api::_()->getApi('modules', 'sitemobile')->_contentTable;
        //Faq page
        $this->addSitefaqHomePage();
        $this->addSitefaqBrowsePage();
        $this->addSitefaqViewPage();
    }

    public function addSitefaqHomePage() {
        $db = Engine_Db_Table::getDefaultAdapter();

        $page_id = Engine_Api::_()->getApi('modules', 'sitemobile')->getPageId('sitefaq_index_home');
        // insert if it doesn't exist yet
        if (!$page_id) {
            // Insert page
            $db->insert($this->_pagesTable, array(
                'name' => 'sitefaq_index_home',
                'displayname' => 'FAQ - Home page',
                'title' => 'FAQ Home page',
                'description' => 'This page lists FAQs categories.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            // Insert main
            $db->insert($this->_contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => 1,
            ));
            $main_id = $db->lastInsertId();

            // Insert main-middle
            $db->insert($this->_contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitemobile.sitemobile-navigation',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 1,
            ));

            // Insert Advance search
            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitemobile.sitemobile-advancedsearch',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'params' => '{"search":"2","title":"","nomobile":"0","name":"sitemobile.sitemobile-advancedsearch"}',
                'order' => 2,
                'module' => 'sitemobile'
            ));

            // Insert Top rated / popular widget.
            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitefaq.faqs-sitefaqs',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'params' => '{"title":"Top Rated FAQs","popularity":"rating"}',
                'order' => 3,
                'module' => 'sitefaq'
            ));

             $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitefaq.zero-sitefaqs',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 4,
                'module' => 'sitefaq'
            ));
             
            // Insert content
            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitefaq.categories-faqs-sitefaqs',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 5,
                'module' => 'sitefaq'
            ));
            
        }
    }

    public function addSitefaqBrowsePage() {
        $db = Engine_Db_Table::getDefaultAdapter();

        $page_id = Engine_Api::_()->getApi('modules', 'sitemobile')->getPageId('sitefaq_index_browse');
        // insert if it doesn't exist yet
        if (!$page_id) {
            // Insert page
            $db->insert($this->_pagesTable, array(
                'name' => 'sitefaq_index_browse',
                'displayname' => 'FAQ - Browse Page',
                'title' => 'FAQ Browse Page',
                'description' => 'This page lists FAQs.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            // Insert main
            $db->insert($this->_contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => 1,
            ));
            $main_id = $db->lastInsertId();

            // Insert main-middle
            $db->insert($this->_contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
            ));
            $main_middle_id = $db->lastInsertId();

            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitemobile.sitemobile-navigation',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 1,
            ));

            // Insert Advance search
            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitemobile.sitemobile-advancedsearch',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'params' => '{"search":"2","title":"","nomobile":"0","name":"sitemobile.sitemobile-advancedsearch"}',
                'order' => 2,
                'module' => 'sitemobile'
            ));

            // Insert content
            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitefaq.browse-sitefaqs',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 3,
                'module' => 'sitefaq'
            ));
        }
    }

    public function addSitefaqViewPage() {
        $db = Engine_Db_Table::getDefaultAdapter();

        $page_id = Engine_Api::_()->getApi('modules', 'sitemobile')->getPageId('sitefaq_index_view');
        // insert if it doesn't exist yet
        if (!$page_id) {
            // Insert page
            $db->insert($this->_pagesTable, array(
                'name' => 'sitefaq_index_view',
                'displayname' => 'FAQ - View Page',
                'title' => 'FAQ View Page',
                'description' => 'This is the FAQs view page.',
                'custom' => 0,
            ));
            $page_id = $db->lastInsertId();

            // Insert main
            $db->insert($this->_contentTable, array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $page_id,
                'order' => 1,
            ));
            $main_id = $db->lastInsertId();

            // Insert main-middle
            $db->insert($this->_contentTable, array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $page_id,
                'parent_content_id' => $main_id,
            ));
            $main_middle_id = $db->lastInsertId();
            
            // Insert content
            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitefaq.sitefaq-view-sitefaqs',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 3,
                'module' => 'sitefaq',
                'params' => '{"statisticsHelpful":"1","update":"1","created":"0","tags":"1"}',
            ));
            
            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitefaq.ratings-sitefaqs',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 4,
                'module' => 'sitefaq'
            ));

            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitemobile.comments',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 5,
                'module' => 'sitemobile'
            ));
        }
    }

}
