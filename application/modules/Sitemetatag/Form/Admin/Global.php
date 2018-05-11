<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitemetatag_Form_Admin_Global extends Engine_Form {

    // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
    public $_SHOWELEMENTSBEFOREACTIVATE = array(
    "submit_lsetting", "environment_mode"
    );

    public function init() {

        $productType = 'sitemetatag';
        
        // ELEMENT FOR LICENSE KEY
        $this->addElement('Text', $productType . '_lsettings', array(
            'label' => 'Enter License key',
            'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting($productType . '.lsettings'),
        ));

        if (APPLICATION_ENV == 'production') {
            $this->addElement('Checkbox', 'environment_mode', array(
                'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few stores of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
                'description' => 'System Mode',
                'value' => 1,
            ));
        } else {
            $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
        }

        $this->addElement('Button', 'submit_lsetting', array(
            'label' => 'Activate Your Plugin Now',
            'type' => 'submit',
            'ignore' => true
        )); 
        $this->setTitle('Global Settings');
        $this->setDescription('Below are the general settings related to Social Meta Tags.');
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $click1 = '<a href="http://ogp.me" " target="_blank">Click here</a>';
        $click2 = '<a href="https://developers.facebook.com/tools/debug/sharing" " target="_blank">Click here</a>';
        $description = sprintf("Do you want to enable open graph for your website. To disable open graph for a particular page, go to ‘Manage Meta Tags’ → ‘Pages’ section . [Note: To know about open graph, please %s. If you want to check how open graph will appear for pages of your website, then please %s and paste the URL of desired page.]", $click1, $click2);
        $this->addElement('Radio', 'sitemetatag_opengraph_enable', array(
            'label' => 'Enable Open Graph',
            'description' => $description,
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
                ),
            'value' => $settings->getSetting("sitemetatag.opengraph.enable", 1),
            ));
        $this->getElement('sitemetatag_opengraph_enable')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

        $click1 = '<a href="https://dev.twitter.com/cards/overview" " target="_blank">Click here</a>';
        $click2 = '<a href="https://cards-dev.twitter.com/validator" " target="_blank">Click here</a>';
        $description = sprintf("Do you want to enable twitter cards for your website. To disable twitter cards for a particular page, go to ‘Manage Meta Tags’ → ‘Pages’ section. [Note: To know about twitter cards, please %s. If you want to check how twitter cards will appear for pages of your website, then please $click2 and paste the URL of desired page.]", $click1, $click2);

        $this->addElement('Radio', 'sitemetatag_twittercards_enable', array(
            'label' => 'Enable Twitter Cards',
            'description' => $description,
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
                ),
            'value' => $settings->getSetting("sitemetatag.twittercards.enable", 1),
            'onchange' => 'toggleExtraTwitterCardFields(this.value)',
            ));

        $this->getElement('sitemetatag_twittercards_enable')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

        $this->addElement('Text', 'sitemetatag_twitter_sitename', array(
            'label' => 'Enter Website’s Twitter Username',
            'description' => 'Enter your website’s username of the respective twitter account. [Note: Enter the username without ‘@’ symbol, it will be added automatically.]',
            'allowEmpty' => false,
            'value' => $settings->getSetting("sitemetatag.twitter.sitename",''),
            ));

        $clickHere = '<a href="admin/sitemetatag/settings/faq/target/3" " target="_blank">Click here</a>';
        $generalSettings = '<a href="admin/core/settings/general" " target="_blank">Website Title</a>';
        $this->addElement('Radio', 'sitemetatag_sitetile_append', array(
            'label' => 'Meta Title',
            'description' => "Select how you want to display the meta title for the content (profile page) being shared from your website?. (Note: To have a clear idea about how these options will work, please $clickHere.)",
            'multiOptions' => array(
                'none' => 'None',
                'site' => 'Website Title',
                'page' => 'Page Meta Title',
                'both' => 'Both Website and Page Meta Title',
                ),
            'value' => $settings->getSetting("sitemetatag.sitetile.append", 'none'),
            ));

        $this->getElement('sitemetatag_sitetile_append')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

        $this->addElement('Radio', 'sitemetatag_sitedescription_append', array(
            'label' => 'Meta Description',
            'description' => "Select how you want to display the meta description for the content (profile page) being shared from your website?. (Note: To have a clear idea about how these options will work, please $clickHere.)",
            'multiOptions' => array(
                'none' => 'None',
                'site' => 'Website Description',
                'page' => 'Page Meta Description',
                'both' => 'Both Website and Page Meta Description',
                ),
            'value' => $settings->getSetting("sitemetatag.sitedescription.append", 'none'),
            ));

        $this->getElement('sitemetatag_sitedescription_append')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

        // Get available files (Default image for Open graph and Twitter card).
        $logoOptions = array();
        $logoOptions[''] = 'None'; 
        $imageExtensions = array('gif', 'jpg', 'jpeg', 'png');
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

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

        $URL = $view->baseUrl() . "/admin/files";
        $click = '<a href="' . $URL . '" target="_blank">Click here</a>';
        $customBlocks = sprintf("Select a meta image for your website which will be visible in the image meta tag of open graph and twitter cards. If a page has its own meta image then meta image will be replaced by that image. [Note: To upload the meta image for your website, please %s.]", $click);

        $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_("You have not
           uploaded a meta image for open graph and twitter cards. Please upload an image $click.") . "</span></div>";

        if (!empty($logoOptions)) {
            $this->addElement('Select', 'sitemetatag_default_image', array(
                'label' => 'Default Website’s Image',
                'description' => $customBlocks,
                'multiOptions' => $logoOptions,
                'onchange' => "updateTextFields(this.value)",
                'value' => $settings->getSetting('sitemetatag.default.image', false),
                ));
            $this->getElement('sitemetatag_default_image')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
        }
        $logo_photo = $settings->getSetting('sitemetatag.default.image', false);
        if (!empty($logo_photo)) {
            $photoName = $view->baseUrl() . '/' . $logo_photo;
            $description = "<a href='$photoName' target='_blank'><img src='$photoName' style='max-width:300px;' /></a>";
        }
        //VALUE FOR LOGO PREVIEW.
        $this->addElement('Dummy', 'image_photo_preview', array(
            'label' => 'Meta Image Preview',
            'description' => $description,
            ));
        $this->image_photo_preview->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
            ));
    }
}