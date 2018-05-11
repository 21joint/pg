<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Content.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Captivate_Form_Admin_Widget_Content extends Engine_Form {

    public function init() {

        $this
                ->setAttrib('id', 'form-upload');
        $this->addElement('Radio', 'showImages', array(
            'label' => "Select the images that you want to show in the image rotator on landing page? [You can upload new images and manage existing ones from the 'Images' tab available in the admin panel of Responsive Captivate Theme.]",
            'multiOptions' => array(
                1 => 'Show All Images.',
                0 => 'Select the images.'
            ),
            'value' => 1,
            'onclick' => 'showMultiCheckboxImageOptions()'
        ));

        $listImage = Engine_Api::_()->getItemTable('captivate_image')->getImages(array('enabled' => 1));
        $listArray = array();
        foreach ($listImage->toArray() as $images) {
            $listArray[$images['image_id']] = $images['title'];
        }

        $this->addElement('MultiCheckbox', 'selectedImages', array(
            'multiOptions' => $listArray,
            'label' => 'Please select the images.',
                //'value' => 1,
        ));

        $this->addElement('Text', 'width', array(
            'label' => 'Enter the width for the images. [Left blank for 100% width]',
            'value' => '',
        ));

        $this->addElement('Text', 'height', array(
            'label' => 'Enter the height for the images.',
            'value' => 583,
        ));

        $this->addElement('Text', 'speed', array(
            'label' => 'Enter the delay for the images to rotate in image rotator in milliseconds (ms).',
            'value' => 5000,
        ));

        $this->addElement('Radio', 'order', array(
            'label' => 'How do you want to rotate the images?',
            'multiOptions' => array(
                2 => 'Random',
                1 => 'Descending',
                0 => 'Ascending'
            ),
            'value' => 2,
        ));

        // Get available files
        $logoOptions = array('' => 'Text-only (No logo)');
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
            $logoOptions['public/admin/' . $basename] = $basename;
        }

        $this->addElement('Radio', 'showLogo', array(
            'label' => "Do you want to display your website's logo on the top-left side on images rotator?",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => 1,
            'onclick' => 'showLogoOptions()'
        ));

        $this->addElement('Select', 'logo', array(
            'label' => 'Select the site logo for your website. [You can upload a new file from: "Layout" > "File & Media Manager".]',
            'multiOptions' => $logoOptions,
        ));


        $this->addElement('Radio', 'captivateBrowseMenus', array(
            // 'label' => 'Browse Menus',
            'label' => "Do you want to enable main menu with more dropdown option in the header? [If enabled, all the main menu links of your website will show up with more dropdown option.]",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => 1,
            'onclick' => 'showBrowseMenusOptions()'
        ));

        $this->addElement('Text', 'max', array(
            'label' => "How many menus do you want to show in the header?",
            'value' => 20
        ));

        $this->addElement('Radio', 'captivateSignupLoginLink', array(
            // 'label' => 'Sign Up / Sign In Link',
            'label' => "Do you want to show Sign Up / Sign In links in the top header area?",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => 1,
        ));

        $this->addElement('Radio', 'captivateFirstImprotantLink', array(
            //'label' => 'First Important link',
            'label' => "Do you want to show a button in the header area to display an important link of your website? [Configure the Title and URL for this button below.]",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => 1,
            'onclick' => 'showFirstLinksOptions()'
        ));

        $this->addElement('Text', 'captivateFirstTitle', array(
            'label' => 'Header Button Title',
            'value' => 'Important Title & Link',
            'style' => 'width:350px;',
        ));

        $this->addElement('Text', 'captivateFirstUrl', array(
            'label' => 'Header Button URL',
            'value' => '#',
            'style' => 'width:350px;',
        ));

        $this->addElement('Text', 'captivateHtmlTitle', array(
            'label' => 'Enter the title that you want to display on this image rotator.',
            'value' => 'BRING PEOPLE TOGETHER',
            'style' => 'width:350px;',
        ));

        $this->addElement('Text', 'captivateHtmlDescription', array(
            'label' => 'Enter the description that you want to display on this image rotator.',
            'value' => 'Watch Videos, Explore Channels and Create & Share Playlists.',
            'style' => 'width:350px;',
        ));

        $this->addElement('Radio', 'captivateHowItWorks', array(
            'label' => "Do you want to display an action button like 'Get Started', 'How It Works', etc on the image rotator? (You can configure this button from the administration of Captivate Theme, and can also configure the slide-down content that comes after clicking of this button.)",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => 1
        ));

        $this->addElement('Radio', 'captivateSignupLoginButton', array(
            'label' => "Do you want to show the Sign In and Sign Up buttons on this image rotator? [If enabled, they will get displayed at the bottom of the rotator.]",
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
            ),
            'value' => 1,
        ));
        $isEventModuleEnabled = Engine_Api::_()->hasModuleBootstrap('siteevent');
        $isVideoModuleEnabled = Engine_Api::_()->hasModuleBootstrap('sitevideo');
        $options = array();
        $value = 1;
        if ($isEventModuleEnabled) {
            $options[2] = 'Advanced Events Search (from "<a target=\'_blank\' href=\'http://www.socialengineaddons.com/socialengine-advanced-events-plugin\'>Advanced Events Plugin</a>")';
            $value = 2;
        }
        if ($isVideoModuleEnabled) {
            $options[3] = 'Advanced Videos Search (from "<a target=\'_blank\' href=\'http://www.socialengineaddons.com/socialengine-advanced-videos-plugin\'>Advanced Videos Plugin</a>")';
            $value = 3;
        }
        $options[1] = 'Advanced Search [Dependent on "<a target=\'_blank\' href=\'http://www.socialengineaddons.com/socialengine-advanced-search-plugin\'>Advanced Search Plugin</a>"] / Global Search';
        $options[0] = 'None';
        $this->addElement('Radio', 'captivateSearchBox', array(
            'label' => 'Select the Search Box that you want to display in the bottom part of this widget.',
            'multiOptions' => $options,
            'escape' => false,
            'value' => $value,
        ));
        if (Engine_Api::_()->hasModuleBootstrap('sitecitycontent') && Engine_Api::_()->hasModuleBootstrap('siteadvsearch')) {

            $this->addElement('Radio', 'showLocationBasedContent', array(
                'label' => 'Show results based on the location, saved in user’s browser cookie.',
                'multiOptions' => array(
                    1 => 'Yes',
                    0 => 'No'
                ),
                'value' => 0,
            ));

            $this->addElement('Radio', 'showLocationSearch', array(
                'label' => 'Do you want to enable location based searching?',
                'multiOptions' => array(
                    1 => 'Yes',
                    0 => 'No'
                ),
                'value' => 0,
            ));
        }
    }

}
?>

<script type="text/javascript">
    var form = document.getElementById("form-upload");
    window.addEvent('domready', function () {
        showFirstLinksOptions();
        showLogoOptions();
        showBrowseMenusOptions();
        showMultiCheckboxImageOptions();
    });

    function showMultiCheckboxImageOptions() {
        if (form.elements["showImages"].value == 1) {
            $('selectedImages-wrapper').style.display = 'none';
        } else {
            $('selectedImages-wrapper').style.display = 'block';
        }
    }
    function showBrowseMenusOptions() {
        if (form.elements["captivateBrowseMenus"].value == 1) {
            $('max-wrapper').style.display = 'block';
        } else {
            $('max-wrapper').style.display = 'none';
        }
    }

    function showLogoOptions() {
        if (form.elements["showLogo"].value == 1) {
            $('logo-wrapper').style.display = 'block';
        } else {
            $('logo-wrapper').style.display = 'none';
        }
    }
    function showFirstLinksOptions() {
        if (form.elements["captivateFirstImprotantLink"].value == 1) {
            $('captivateFirstTitle-wrapper').style.display = 'block';
            $('captivateFirstUrl-wrapper').style.display = 'block';
        } else {
            $('captivateFirstTitle-wrapper').style.display = 'none';
            $('captivateFirstUrl-wrapper').style.display = 'none';
        }
    }

</script>