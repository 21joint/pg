<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_AdminFooterTemplatesController extends Core_Controller_Action_Admin {

    public function indexAction() {

        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('captivate_admin_main', array(), 'captivate_admin_footer_templates');

        $tempLanguageDataArray = array();
        $tempLanguageTitleDataArray = array();
        if ($this->getRequest()->isPost()) {
            $localeMultiOptions = Engine_Api::_()->captivate()->getLanguageArray();
            $coreSettings = Engine_Api::_()->getApi('settings', 'core');
            $defaultLanguage = $coreSettings->getSetting('core.locale.locale', 'en');
            $total_allowed_languages = Count($localeMultiOptions);

            if (!empty($localeMultiOptions)) {
                foreach ($localeMultiOptions as $key => $label) {
                    $lang_name = $label;
                    if (isset($localeMultiOptions[$label])) {
                        $lang_name = $localeMultiOptions[$label];
                    }

                    $page_block_field = "captivate_footer_lending_page_block_$key";
                    $page_block_title_field = "captivate_footer_lending_page_block_title_$key";
                    if ($total_allowed_languages <= 1) {
                        $page_block_field = "captivate_footer_lending_page_block";
                        $page_block_title_field = "captivate_footer_lending_page_block_title";
                        $page_block_label = "Description";
                        $page_block_title_label = "Title";
                    } elseif ($label == 'en' && $total_allowed_languages > 1) {
                        $page_block_field = "captivate_footer_lending_page_block";
                        $page_block_title_field = "captivate_footer_lending_page_block_title";
                    }

                    if (!strstr($key, '_')) {
                        $key = $key . '_default';
                    }

                    $tempLanguageDataArray[$key] = @base64_encode($_POST[$page_block_field]);
                    $tempLanguageTitleDataArray[$key] = @base64_encode($_POST[$page_block_title_field]);
                }

                $coreSettings->setSetting('captivate.footer.lending.block.languages', $tempLanguageDataArray);
                $coreSettings->setSetting('captivate.footer.lending.block.title.languages', $tempLanguageTitleDataArray);
            }
        }

        $this->view->form = $form = new Captivate_Form_Admin_Footertemplates();

        if (!$this->getRequest()->isPost())
            return;

        if (!$form->isValid($this->getRequest()->getPost()))
            return;

        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        $values = $form->getValues();

        if (array_key_exists('youtube', $values))
            unset($values['youtube']);

        if (array_key_exists('twitter', $values))
            unset($values['twitter']);

        if (array_key_exists('pinterest', $values))
            unset($values['pinterest']);

        if (array_key_exists('facebook', $values))
            unset($values['facebook']);

        if (array_key_exists('linkedin', $values))
            unset($values['linkedin']);

        $db = Engine_Db_Table::getDefaultAdapter();
        $footerPageId = $this->getFooterPageid();
        $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = 'core.menu-footer' AND page_id ='$footerPageId'");
        $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = 'captivate.menu-footer' AND page_id ='$footerPageId'");
        if (Engine_Api::_()->hasModuleBootstrap('sitemenu')) {
            $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = 'sitemenu.menu-footer' AND page_id ='$footerPageId'");
        }
        if ($values['captivate_footer_templates'] == 2) {
            $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = 'captivate.homepage-footertext' AND page_id ='$footerPageId'");
        } else if ($values['captivate_footer_templates'] == 1) {
            $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = 'captivate.homepage-footertext' AND page_id ='$footerPageId'");
        } else {
            $this->checkWidgets('captivate.homepage-footertext', 1);
        }
        $this->checkWidgets('captivate.menu-footer', 2);
        $this->checkWidgets('core.menu-footer', 3);
        foreach ($values as $key => $value) {

            if ($key == 'captivate_social_links' && $coreSettings->hasSetting($key, $value)) {
                $coreSettings->removeSetting($key);
            }

            $coreSettings->setSetting($key, $value);
        }

        if ($values['captivate_footer_templates'] == 1 && isset($values['captivate_footer_lending_page_block']) && !empty($values['captivate_footer_lending_page_block'])) {
            $value = @base64_encode($values['captivate_footer_lending_page_block']);
            Engine_Api::_()->getApi('settings', 'core')->setSetting('captivate.footer.lending.block', $value);
        }

        $form->addNotice('Your changes have been saved.');
    }

    public function getFooterPageid() {
        //GET PAGE OBJECT
        $pageTable = Engine_Api::_()->getDbtable('pages', 'core');
        $pageSelect = $pageTable->select()->where('name = ?', 'footer');
        $page_id = $pageTable->fetchRow($pageSelect)->page_id;
        return $page_id;
    }

    public function checkWidgets($name, $order) {
        $db = Engine_Db_Table::getDefaultAdapter();
        $page_id = $this->getFooterPageid();
        $contentTable = Engine_Api::_()->getDbtable('content', 'core');
        $contentSelect = $contentTable->select()->where('name = ?', $name)->where('page_id = ?', $page_id);

        $contentMainSelect = $contentTable->select()->where('name = ?', 'main')->where('page_id = ?', $page_id);
        $content_main_id = $contentTable->fetchRow($contentMainSelect)->content_id;
        $content_id = $contentTable->fetchRow($contentSelect)->content_id;

        if (!$content_id) {
            $db->query("INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES ('$page_id', 'widget', '$name', '$content_main_id', '$order', NULL, NULL);");
        }

        return $page_id;
    }

}
