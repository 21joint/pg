<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSitemapController.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_AdminFileEditorController extends Core_Controller_Action_Admin {

    //ACTION TO CREATE OR EDIT ROBOTS.TXT FILE AND HTACCESS FILE
    public function indexAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteseo_admin_main', array(), 'siteseo_admin_main_fileeditor');

        // GET CONTENT OF ROBOTS.TXT FILE
        $filename = 'robots.txt';
        include_once APPLICATION_PATH . '/application/modules/Siteseo/controllers/license/license2.php';

        // GET CONTENT OF HTACCESS FILE
        $filename = '.htaccess';
        $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . $filename;
        if(!file_exists($filePath)) {
            file_put_contents($filePath, '');
            @chmod($filePath, 0777);
        }
        $this->view->writeableHtaccessFile = is_writable($filePath); 
        $this->view->htaccessFileContent = file_get_contents($filePath);

        // GET CONTENT OF OPEN SEARCH DESCRIPTION DOCUMENT FILE
        $filename = 'osdd.xml';
        $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . $filename;
        if(!file_exists($filePath)) {
            $openSearch = Engine_Api::_()->getApi('openSearch','siteseo');
            $openSearch->write();
        }
        $this->view->writeableOpenSearchFile = is_writable($filePath);
        $this->view->OpenSearchFileContent = file_get_contents($filePath);
    }

    //ACTION TO SAVE ROBOTS.TXT FILE
    public function saveRobotsAction() {

        $body = $this->_getParam('body');
        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_("Bad method");
            return;
        }

        $filename = 'robots.txt';
        $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . $filename;

        // CHECK WHETHER THE FILE IS WRITABLE OR NOT
        if(!is_writable($filePath)) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_("Not writeable");
            return;
        }

        // SAVE THE ROBOTS.TXT FILE
        if( !file_put_contents($filePath, $body) ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Could not save contents');
            return;
        }
        $this->view->status = true;
    }

    //ACTION TO SAVE HTACCESS FILE
    public function saveHtaccessAction() {

        $body = $this->_getParam('body');
        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_("Bad method");
            return;
        }

        $filename = '.htaccess';
        $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . $filename;

        // CHECK WHETHER THE FILE IS WRITABLE OR NOT
        if(!is_writable($filePath)) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_("Not writeable");
            return;
        }

        // SAVE THE HTACCESS FILE
        if( !file_put_contents($filePath, $body) ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Could not save contents');
            return;
        }
        $this->view->status = true;
    }

    //ACTION TO SAVE OPEN SEARCH FILE
    public function saveOpenSearchAction() {

        $body = $this->_getParam('body');
        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_("Bad method");
            return;
        }

        $filename = 'osdd.xml';
        $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . $filename;

        // CHECK WHETHER THE FILE IS WRITABLE OR NOT
        if(!is_writable($filePath)) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_("Not writeable");
            return;
        }

        // SAVE THE HTACCESS FILE
        if( !file_put_contents($filePath, $body) ) {
            $this->view->status = false;
            $this->view->message = Zend_Registry::get('Zend_Translate')->_('Could not save contents');
            return;
        }
        $this->view->status = true;
    }
}
