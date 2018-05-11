<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Widget_ListOffersTabsViewController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        $this->view->is_ajax = $is_ajax = $this->_getParam('isajax', '');
        $this->view->showViewMore = $this->_getParam('showViewMore', 1);
        $this->view->category_id = $category_id = $this->_getParam('category_id', 0);
        $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
        $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
        if (empty($is_ajax)) {
            $this->view->tabs = $tabs = Engine_Api::_()->getItemTable('seaocore_tab')->getTabs(array('module' => 'sitepageoffer', 'type' => 'offers', 'enabled' => 1));
            $count_tabs = count($tabs);
            if (empty($count_tabs)) {
                return $this->setNoRender();
            }
            $activeTabName = $tabs[0]['name'];
        }


        if (!empty($viewer_id)) {
            // Convert times
            $oldTz = date_default_timezone_get();
            date_default_timezone_set($viewer->timezone);
        }

        $currentTime = date("Y-m-d H:i:s");
        $this->view->marginPhoto = $this->_getParam('margin_photo', 12);
        $table = Engine_Api::_()->getItemTable('sitepageoffer_offer');
        $tableName = $table->info('name');
        $tablePage = Engine_Api::_()->getDbtable('pages', 'sitepage');
        $tablePageName = $tablePage->info('name');
        $select = $table->select()
                ->setIntegrityCheck(false)
                ->from($tableName)
                ->joinLeft($tablePageName, "$tablePageName.page_id = $tableName.page_id", array('title AS page_title', 'photo_id as page_photo_id', 'offer'))
                ->where("($tableName.end_settings = 1 AND $tableName.end_time >= '$currentTime' OR $tableName.end_settings = 0)");

        $select = $select
                ->where($tablePageName . '.closed = ?', '0')
                ->where($tablePageName . '.approved = ?', '1')
                ->where($tablePageName . '.declined = ?', '0')
                ->where($tablePageName . '.draft = ?', '1');

        if (!empty($category_id)) {
            $select = $select->where($tablePageName . '.	category_id =?', $category_id);
        }
        if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
            $select->where($tablePageName . '.expiration_date  > ?', date("Y-m-d H:i:s"));
        }

        $paramTabName = $this->_getParam('tabName', '');

        if (!empty($paramTabName))
            $activeTabName = $paramTabName;

        $activeTab = Engine_Api::_()->getItemTable('seaocore_tab')->getTabs(array('module' => 'sitepageoffer', 'type' => 'offers', 'enabled' => 1, 'name' => $activeTabName));
        $this->view->activTab = $activTab = $activeTab['0'];
        switch ($activTab->name) {
            case 'recent_pageoffers':
                break;
            case 'liked_pageoffers':
                $select->order($tableName . '.like_count DESC');
                break;
            case 'viewed_pageoffers':
                $select->order($tableName . '.view_count DESC');
                break;
            case 'commented_pageoffers':
                $select->order($tableName . '.comment_count DESC');
                break;
            case 'featured_pageoffers':
                $select->where($tableName . '.sticky = ?', 1);
                break;
            case 'hot_pageoffers':
                $select->where($tableName . '.hotoffer = ?', 1);
                $select->order('Rand()');
                break;
            case 'popular_pageoffers':
                $select->where($tableName . '.claimed != ?', 0);
                break;
            case 'random_pageoffers':
                $select->order('Rand()');
                break;
        }

        if ($activTab->name != 'featured_pageoffers' && $activTab->name != 'random_pageoffers') {
            $select->order('creation_date DESC');
        }

        if (!empty($viewer_id)) {
            date_default_timezone_set($oldTz);
        }

        $this->view->paginator = $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($activTab->limit);
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->count = $paginator->getTotalItemCount();
        $view = $this->view;
        $view->addHelperPath(APPLICATION_PATH . '/application/modules/Fields/View/Helper', 'Fields_View_Helper');
    }

}

?>
