<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Add.php 2011-08-026 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Form_Admin_HtmlBlock extends Engine_Form {

    public function init() {

        $coreSettings = Engine_Api::_()->getApi('settings', 'core');

        $captivateLendingBlockValue = $coreSettings->getSetting('captivate.home.lending.block', null);
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $baseURL = $view->baseUrl();
        if (empty($captivateLendingBlockValue) || is_array($captivateLendingBlockValue)) {
            $captivateLendingBlockValue = '<div style="display: inline-block;"><div style="float: left; margin: 10px 0; opacity: 1; text-align: center; width: 33.3%;">
  
      <div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/themes/captivate/images/discover-events.png); display:block;"></div>
        <a href="' . $baseURL . '/events">
          <span style="color: #282828; float: left; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Discover Events</span>
          <span style="color: #707070; float: left; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Find out the best parties and events happening around you.</span>
        </a>
    </div>
    <div style="float: left; margin: 10px 0; opacity: 1; text-align: center; width: 33.3%;">
    <div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/themes/captivate/images/engage-icon.png); display:block;"></div>
        <a href="' . $baseURL . '/groups">
          <span style="color: #282828; float: left; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Engage</span>
          <span style="color: #707070; float: left; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Join our interest based groups and share stuff.</span>
        </a>
   </div>
   <div style="float: left; margin: 10px 0; opacity: 1; text-align: center; width: 33.3%;">
     <div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/themes/captivate/images/meetpeople.png); display:block;"></div>
    <a href="' . $baseURL . '/members">
      <span style="color: #282828; float: left; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Meet New People</span>
      <span style="color: #707070; float: left; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Make new friends with common interests, Get your own party buddies.</span>
    </a>
  </div></div>';
        } else {
            $captivateLendingBlockValue = @base64_decode($captivateLendingBlockValue);
        }

        //WORK FOR MULTILANGUAGES START
        $localeMultiOptions = Engine_Api::_()->captivate()->getLanguageArray();

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

                if (!strstr($key, '_')) {
                    $key = $key . '_default';
                }

                $keyForSettings = str_replace('_', '.', $key);
                $captivateLendingBlockValueMulti = $coreSettings->getSetting('captivate.home.lending.block.languages.' . $keyForSettings, null);
                if (empty($captivateLendingBlockValueMulti)) {
                    $captivateLendingBlockValueMulti = $captivateLendingBlockValue;
                } else {
                    $captivateLendingBlockValueMulti = @base64_decode($captivateLendingBlockValueMulti);
                }

                $captivateLendingBlockTitleValueMulti = $coreSettings->getSetting('captivate.home.lending.block.title.languages.' . $keyForSettings, 'Get Started');
                if (empty($captivateLendingBlockTitleValueMulti)) {
                    $captivateLendingBlockTitleValueMulti = 'Get Started';
                } else {
                    $captivateLendingBlockTitleValueMulti = @base64_decode($captivateLendingBlockTitleValueMulti);
                }

                $page_block_label = sprintf(Zend_Registry::get('Zend_Translate')->_("Captivate HTML Block: Title & Description in %s"), $lang_name);

                if ($total_allowed_languages <= 1) {
                    $page_block_field = "captivate_home_lending_page_block";
                    $page_block_title_field = "captivate_home_lending_page_block_title";
                    $page_block_label = "HTML Block: Title & Description";
                } elseif ($label == 'en' && $total_allowed_languages > 1) {
                    $page_block_field = "captivate_home_lending_page_block";
                    $page_block_title_field = "captivate_home_lending_page_block_title";
                }

                $editorOptions = Engine_Api::_()->seaocore()->tinymceEditorOptions();
                $editorOptions['height'] = '500px';

                $this->addElement('TinyMce', $page_block_field, array(
                    'label' => $page_block_label,
                    'description' => "Configure the HTML title and description from here. It is displayed after placing the 'Captivate HTML Block' widget from layout editor on any widgetized page of your website.",
                    'attribs' => array('rows' => 24, 'cols' => 80, 'style' => 'width:200px; max-width:200px; height:240px;'),
                    'value' => $captivateLendingBlockValueMulti,
                    'filters' => array(
                        new Engine_Filter_Html(),
                        new Engine_Filter_Censor()),
                    'editorOptions' => $editorOptions,
                ));
            }
        }
        //WORK FOR MULTILANGUAGES END

        $this->addElement('Button', 'submit', array(
            'label' => 'Submit',
            'type' => 'submit',
            'decorators' => array(
                'ViewHelper',
            ),
        ));
    }

}
