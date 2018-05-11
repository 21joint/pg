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
class Feedback_Plugin_Sitemobile {

    protected $_pagesTable;
    protected $_contentTable;

    public function onIntegrated() {

        $this->_pagesTable = Engine_Api::_()->getApi('modules', 'sitemobile')->_pagesTable;
        $this->_contentTable = Engine_Api::_()->getApi('modules', 'sitemobile')->_contentTable;
        //Feedback create page
        $this->addFeedbackCreatePage();
        $this->addFeedbackViewPage();
    }

    public function addFeedbackCreatePage() {
        $db = Engine_Db_Table::getDefaultAdapter();

        $page_id = Engine_Api::_()->getApi('modules', 'sitemobile')->getPageId('feedback_index_create');
        // insert if it doesn't exist yet
        if (!$page_id) {
            // Insert page
            $db->insert($this->_pagesTable, array(
                'name' => 'feedback_index_create',
                'displayname' => 'Feedback - Create Page',
                'title' => 'Feedback Create page',
                'description' => 'This page is the feedback create page.',
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
                'name' => 'core.content',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 2,
                'module' => 'sitemobile'
            ));
        }
    }
    
    public function addFeedbackViewPage() {
        $db = Engine_Db_Table::getDefaultAdapter();

        $page_id = Engine_Api::_()->getApi('modules', 'sitemobile')->getPageId('feedback_index_view');
        // insert if it doesn't exist yet
        if (!$page_id) {
            // Insert page
            $db->insert($this->_pagesTable, array(
                'name' => 'feedback_index_view',
                'displayname' => 'Feedback - View Page',
                'title' => 'Feedback View page',
                'description' => 'This page is the feedback view page.',
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
                'name' => 'core.content',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 2,
                'module' => 'sitemobile'
            ));
            
            $db->insert($this->_contentTable, array(
                'type' => 'widget',
                'name' => 'sitemobile.comments',
                'page_id' => $page_id,
                'parent_content_id' => $main_middle_id,
                'order' => 3,
                'module' => 'sitemobile'
            ));
        }
    }
}
