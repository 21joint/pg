<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Customization.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Form_Admin_Footertemplates extends Engine_Form {

    public function init() {

        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $baseURL = $view->baseUrl();
        $template_url_1 = $baseURL . '/application/modules/Captivate/externals/images/screenshots/template-1.png';
        $template_1 = "Template - 1
    " . '<a href="' . $template_url_1 . '" title="View Screenshot" class="seaocore_icon_view" target="_blank"></a>';

        $template_url_2 = $baseURL . '/application/modules/Captivate/externals/images/screenshots/template-2.png';
        $template_2 = "Template - 2
    " . '<a href="' . $template_url_2 . '" title="View Screenshot" class="seaocore_icon_view" target="_blank"></a>';

        $template_url_3 = $baseURL . '/application/modules/Captivate/externals/images/screenshots/template-3.png';
        $template_3 = "Template - 3
    " . '<a href="' . $template_url_3 . '" title="View Screenshot" class="seaocore_icon_view" target="_blank"></a>';

        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        $this->setTitle("Footer Templates");
        $it = new DirectoryIterator(APPLICATION_PATH . '/public/admin/');
        $logoBackgroundOptions = array('' => '');
        $logoOptions = array('' => 'Text-only (No logo)');
        $imageExtensions = array('gif', 'jpg', 'jpeg', 'png');
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        foreach ($it as $file) {
            if ($file->isDot() || !$file->isFile())
                continue;
            $basename = basename($file->getFilename());
            if (!($pos = strrpos($basename, '.')))
                continue;
            $ext = strtolower(ltrim(substr($basename, $pos), '.'));
            if (!in_array($ext, $imageExtensions))
                continue;
            $logoBackgroundOptions['public/admin/' . $basename] = $basename;
            $logoOptions['public/admin/' . $basename] = $basename;
        }
        $this->addElement('Radio', 'captivate_footer_templates', array(
            'label' => 'Select Footer Templates',
            'description' => 'Choose the footer template for your website.',
            'multiOptions' => array(
                1 => $template_1,
                2 => $template_2,
                3 => $template_3,
            ),
            'escape' => false,
            'onclick' => 'displayFooterHtmlBlock(this.value);',
            'value' => $coreSettings->getSetting('captivate.footer.templates', 2),
        ));

        $this->addElement('Radio', 'captivate_footer_background', array(
            'description' => 'Choose the footer background for your website.',
            'label' => 'Footer Background',
            'multiOptions' => array(
                2 => 'Color & Image',
                1 => 'Only Color',
            ),
            'onclick' => 'showFooterBackgroundImage(this.value);',
            'value' => $coreSettings->getSetting('captivate.footer.background', 1),
        ));

        $this->addElement('Select', 'captivate_footer_backgroundimage', array(
            'description' => 'Select the footer background image for your website.',
            'label' => 'Footer Background Image',
            'multiOptions' => $logoBackgroundOptions,
            'value' => $coreSettings->getSetting('captivate.footer.backgroundimage'),
        ));

        $this->addElement('Radio', 'captivate_footer_show_logo', array(
            'description' => 'Do you want to show footer logo on your website?',
            'label' => 'Show Footer Logo',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'onclick' => 'showFooterLogo(this.value);',
            'value' => $coreSettings->getSetting('captivate.footer.show.logo', 1),
        ));


        $this->addElement('Select', 'captivate_footer_select_logo', array(
            'description' => 'Select the footer logo for your website.',
            'label' => 'Select Footer Logo',
            'multiOptions' => $logoOptions,
            'value' => $coreSettings->getSetting('captivate.footer.select.logo'),
        ));

        $captivatefooterLendingBlockValue = $coreSettings->getSetting('captivate.footer.lending.block', null);
        if (empty($captivatefooterLendingBlockValue) || is_array($captivatefooterLendingBlockValue)) {
            $captivatefooterLendingBlockValue = '<p>Explore &amp; Watch videos that you have always dreamed of, and post &amp; share your videos to connect with own community.</p>';
        } else {
            $captivatefooterLendingBlockValue = @base64_decode($captivatefooterLendingBlockValue);
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

                $page_block_field = "captivate_footer_lending_page_block_$key";

                if (!strstr($key, '_')) {
                    $key = $key . '_default';
                }

                $keyForSettings = str_replace('_', '.', $key);
                $captivatefooterLendingBlockValueMulti = $coreSettings->getSetting('captivate.footer.lending.block.languages.' . $keyForSettings, null);
                if (empty($captivatefooterLendingBlockValueMulti)) {
                    $captivatefooterLendingBlockValueMulti = $captivatefooterLendingBlockValue;
                } else {
                    $captivatefooterLendingBlockValueMulti = @base64_decode($captivatefooterLendingBlockValueMulti);
                }

                $page_block_label = sprintf(Zend_Registry::get('Zend_Translate')->_("Footer HTML Block Title & Description in %s"), $lang_name);

                if ($total_allowed_languages <= 1) {
                    $page_block_field = "captivate_footer_lending_page_block";
                    $page_block_label = "Footer HTML Block Title & Description";
                } elseif ($label == 'en' && $total_allowed_languages > 1) {
                    $page_block_field = "captivate_footer_lending_page_block";
                }

                $editorOptions = Engine_Api::_()->seaocore()->tinymceEditorOptions();
                $editorOptions['height'] = '500px';

                $this->addElement('TinyMce', $page_block_field, array(
                    'label' => $page_block_label,
                    'description' => "Configure the HTML title and description that gets shown after placing the 'Footer HTML Block' widget from layout editor on any widgetized page of website.",
                    'attribs' => array('rows' => 24, 'cols' => 80, 'style' => 'width:200px; max-width:200px; height:240px;'),
                    'value' => $captivatefooterLendingBlockValueMulti,
                    'filters' => array(
                        new Engine_Filter_Html(),
                        new Engine_Filter_Censor()),
                    'editorOptions' => $editorOptions,
                ));
            }
        }
        //WORK FOR MULTILANGUAGES END
        $tempLogoOptions = array();
        $imageExtensions = array('gif', 'jpg', 'jpeg', 'png');
        $it = new DirectoryIterator(APPLICATION_PATH . '/public/admin/');
        foreach ($it as $file) {
            if ($file->isDot() || !$file->isFile())
                continue;
            $basename = basename($file->getFilename());
            if (!($pos = strrpos($basename, '.')))
                continue;
            $ext = strtolower(ltrim(substr($basename, $pos), '.'));
            if (!in_array($ext, $imageExtensions))
                continue;
            $tempLogoOptions['public/admin/' . $basename] = $basename;
        }

        $this->addElement('MultiCheckbox', 'captivate_social_links', array(
            'description' => 'Select the social links that you want to be available in this block.',
            'label' => 'Social Links',
            'multiOptions' => array(
                "facebooklink" => "Facebook Link",
                "twitterlink" => "Twitter Link",
                "pininterestlink" => "Pinterest Link",
                "youtubelink" => "YouTube Link",
                "linkedinlink" => "LinkedIn Link"
            ),
            'value' => $coreSettings->getSetting('captivate.social.links', array("facebooklink", "twitterlink", "pininterestlink", "youtubelink", "linkedinlink"))
        ));

        //FOR FACEBOOK SOCIAL LINK
        $this->addElement('Dummy', 'facebook', array(
            'label' => '1. Facebook'
                )
        );

        $this->addElement('Text', 'captivate_facebook_url', array(
            'label' => 'Url',
            'value' => $coreSettings->getSetting('captivate.facebook.url', 'http://www.facebook.com/')
                )
        );

        $defaultLogoOptions = array('application/modules/Captivate/externals/images/facebook.png' => 'Default Facebook Icon');
        $this->addElement('Select', 'captivate_facebook_default_icon', array(
            'label' => 'Default Image Icon',
            'multiOptions' => array_merge($defaultLogoOptions, $tempLogoOptions),
            'value' => $coreSettings->getSetting('captivate.facebook.default.icon', $view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/facebook.png'),
        ));

        $defaultLogoOptions = array('application/modules/Captivate/externals/images/overfacebook.png' => 'Mouse-over Facebook Icon');
        $this->addElement('Select', 'captivate_facebook_hover_icon', array(
            'label' => 'Mouse-over Image Icon',
            'multiOptions' => array_merge($defaultLogoOptions, $tempLogoOptions),
            'value' => $coreSettings->getSetting('captivate.facebook.hover.icon', $view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overfacebook.png')
                )
        );

        $this->addElement('Text', 'captivate_facebook_title', array(
            'label' => 'HTML Title',
            'value' => $coreSettings->getSetting('captivate.facebook.title', 'Like us on Facebook')
                )
        );

        //WORK FOR TWITTER SOCIAL LINK
        $this->addElement('Dummy', 'twitter', array(
            'label' => '2. Twitter'
                )
        );

        $this->addElement('Text', 'captivate_twitter_url', array(
            'label' => 'Url',
            'value' => $coreSettings->getSetting('captivate.twitter.url', 'https://www.twitter.com/')
                )
        );

        $defaultLogoOptions = array('application/modules/Captivate/externals/images/twitter.png' => 'Default Twitter Icon');
        $this->addElement('Select', 'captivate_twitter_default_icon', array(
            'label' => 'Default Image Icon',
            'multiOptions' => array_merge($defaultLogoOptions, $tempLogoOptions),
            'value' => $view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/twitter.png'
                )
        );

        $defaultLogoOptions = array('application/modules/Captivate/externals/images/overtwitter.png' => 'Mouse-over Twitter Icon');
        $this->addElement('Select', 'captivate_twitter_hover_icon', array(
            'label' => 'Mouse-over Image Icon',
            'multiOptions' => array_merge($defaultLogoOptions, $tempLogoOptions),
            'value' => $view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overtwitter.png'
                )
        );

        $this->addElement('Text', 'captivate_twitter_title', array(
            'label' => 'HTML Title',
            'value' => $coreSettings->getSetting('captivate.twitter.title', 'Follow us on Twitter')
                )
        );

        //WORK FOR PININTEREST SOCIAL LINK
        $this->addElement('Dummy', 'pinterest', array(
            'label' => '3. Pinterest'
                )
        );

        $this->addElement('Text', 'captivate_pinterest_url', array(
            'label' => 'Url',
            'value' => $coreSettings->getSetting('captivate.pinterest.url', 'https://www.pinterest.com/')
                )
        );

        $defaultLogoOptions = array('application/modules/Captivate/externals/images/pinterest.png' => 'Default Pinterest Icon');
        $this->addElement('Select', 'captivate_pinterest_default_icon', array(
            'label' => 'Default Image Icon',
            'multiOptions' => array_merge($defaultLogoOptions, $tempLogoOptions),
            'value' => $view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/pinterest.png'
                )
        );

        $defaultLogoOptions = array('application/modules/Captivate/externals/images/overpinterest.png' => 'Mouse-over Pinterest Icon');
        $this->addElement('Select', 'captivate_pinterest_hover_icon', array(
            'label' => 'Mouse-over Image Icon',
            'multiOptions' => array_merge($defaultLogoOptions, $tempLogoOptions),
            'value' => $coreSettings->getSetting('captivate.pinterest.hover.icon', $view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overpinterest.png')
                )
        );

        $this->addElement('Text', 'captivate_pinterest_title', array(
            'label' => 'HTML Title',
            'value' => $coreSettings->getSetting('captivate.pinterest.title', 'Pinterest')
                )
        );

        //WORK FOR YOUTUBE SOCIAL LINK
        $this->addElement('Dummy', 'youtube', array(
            'label' => '4. YouTube'
                )
        );

        $this->addElement('Text', 'captivate_youtube_url', array(
            'label' => 'Url',
            'value' => $coreSettings->getSetting('captivate.youtube.url', 'http://www.youtube.com/')
                )
        );

        $defaultLogoOptions = array('application/modules/Captivate/externals/images/youtube.png' => 'Default YouTube Icon');
        $this->addElement('Select', 'captivate_youtube_default_icon', array(
            'label' => 'Default Image Icon',
            'multiOptions' => array_merge($defaultLogoOptions, $tempLogoOptions),
            'value' => $coreSettings->getSetting('captivate.youtube.default.icon', $view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/youtube.png')
                )
        );

        $defaultLogoOptions = array('application/modules/Captivate/externals/images/overyoutube.png' => 'Mouse-over YouTube Icon');
        $this->addElement('Select', 'captivate_youtube_hover_icon', array(
            'label' => 'Mouse-over Image Icon',
            'multiOptions' => array_merge($defaultLogoOptions, $tempLogoOptions),
            'value' => $coreSettings->getSetting('captivate.youtube.hover.icon', $view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overyoutube.png')
                )
        );

        $this->addElement('Text', 'captivate_youtube_title', array(
            'label' => 'HTML Title',
            'value' => $coreSettings->getSetting('captivate.youtube.title', 'Youtube')
                )
        );

        //WORK FOR LinkedIn SOCIAL LINK
        $this->addElement('Dummy', 'linkedin', array(
            'label' => '5. LinkedIn'
                )
        );

        $this->addElement('Text', 'captivate_linkedin_url', array(
            'label' => 'Url',
            'value' => $coreSettings->getSetting('captivate.linkedin.url', 'https://www.linkedin.com/')
                )
        );

        $defaultLogoOptions = array('application/modules/Captivate/externals/images/linkedin.png' => 'Default LinkedIn Icon');
        $this->addElement('Select', 'captivate_linkedin_default_icon', array(
            'label' => 'Default Image Icon',
            'multiOptions' => array_merge($defaultLogoOptions, $tempLogoOptions),
            'value' => $coreSettings->getSetting('captivate.linkedin.default.icon', $view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/linkedin.png')
                )
        );

        $defaultLogoOptions = array('application/modules/Captivate/externals/images/overlinkedin.png' => 'Mouse-over LinkedIn Icon');
        $this->addElement('Select', 'captivate_linkedin_hover_icon', array(
            'label' => 'Mouse-over Image Icon',
            'multiOptions' => array_merge($defaultLogoOptions, $tempLogoOptions),
            'value' => $coreSettings->getSetting('captivate.linkedin.hover.icon', $view->layout()->staticBaseUrl . 'application/modules/Captivate/externals/images/overlinkedin.png')
                )
        );

        $this->addElement('Text', 'captivate_linkedin_title', array(
            'label' => 'HTML Title',
            'value' => $coreSettings->getSetting('captivate.linkedin.title', 'LinkedIn')
                )
        );

        $this->addElement('Button', 'submit', array(
            'label' => 'Submit',
            'type' => 'submit',
            'decorators' => array(
                'ViewHelper',
            ),
        ));
    }

}
