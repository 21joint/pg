<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminHtmlBlockController.php 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_AdminHtmlBlockController extends Core_Controller_Action_Admin {

    public function indexAction() {
        // Make navigation
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('captivate_admin_main', array(), 'captivate_admin_main_htmlblock');
        $this->view->form = $form = new Captivate_Form_Admin_HtmlBlock();

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

                    $page_block_field = "captivate_home_lending_page_block_$key";
                    $page_block_title_field = "captivate_home_lending_page_block_title_$key";
                    if ($total_allowed_languages <= 1) {
                        $page_block_field = "captivate_home_lending_page_block";
                        $page_block_title_field = "captivate_home_lending_page_block_title";
                        $page_block_label = "Description";
                        $page_block_title_label = "Title";
                    } elseif ($label == 'en' && $total_allowed_languages > 1) {
                        $page_block_field = "captivate_home_lending_page_block";
                        $page_block_title_field = "captivate_home_lending_page_block_title";
                    }

                    if (!strstr($key, '_')) {
                        $key = $key . '_default';
                    }

                    $tempLanguageDataArray[$key] = @base64_encode($_POST[$page_block_field]);
                    $tempLanguageTitleDataArray[$key] = @base64_encode($_POST[$page_block_title_field]);
                }

                $coreSettings->setSetting('captivate.home.lending.block.languages', $tempLanguageDataArray);
                $coreSettings->setSetting('captivate.home.lending.block.title.languages', $tempLanguageTitleDataArray);
            }
        }

        if (!$this->getRequest()->isPost())
            return;

        if (!$form->isValid($this->getRequest()->getPost()))
            return;

        $values = $form->getValues();
        if (isset($values['captivate_home_lending_page_block']) && !empty($values['captivate_home_lending_page_block'])) {
            $value = @base64_encode($values['captivate_home_lending_page_block']);
            Engine_Api::_()->getApi('settings', 'core')->setSetting('captivate.home.lending.block', $value);
        }

        $form->addNotice('Successfully Saved.');
    }

}
