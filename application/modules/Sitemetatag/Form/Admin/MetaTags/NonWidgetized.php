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

class Sitemetatag_Form_Admin_MetaTags_NonWidgetized extends Engine_Form {

    public function init() {

        $this->setDescription('You can use below form to set meta title, meta description, meta keywords and meta image for all the non-widgetized pages of your website.');
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $this->addElement('Text', 'sitemetatag_nonwidgetized_title', array(
            'label' => 'Meta Title',
            'description' => 'Enter the meta title you want to display for the non widgetized pages.',
            'allowEmpty' => false,
            'value' => $settings->getSetting("sitemetatag.nonwidgetized.title",''),
            ));

        $this->addElement('Textarea', 'sitemetatag_nonwidgetized_description', array(
            'label' => 'Meta Description',
            'description' => 'Enter the meta description you want to display for the non widgetized pages.',
            'allowEmpty' => false,
            'value' => $settings->getSetting("sitemetatag.nonwidgetized.description",''),
            ));

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
        $customBlocks = sprintf("Select a meta image for non-widgetized pages of your website which will be visible in the image meta tag of open graph and twitter cards. [Note: To upload the meta image for your website, please %s.]", $click);

        $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_("You have not
         uploaded a meta image for open graph and twitter cards for non widgetized pages. Please upload an image $click.") . "</span></div>";

        if (!empty($logoOptions)) {
            $this->addElement('Select', 'sitemetatag_nonwidgetized_image', array(
                'label' => 'Meta Image',
                'description' => $customBlocks,
                'multiOptions' => $logoOptions,
                'onchange' => "updateTextFields(this.value)",
                'value' => $settings->getSetting('sitemetatag.nonwidgetized.image', false),
                ));
            $this->getElement('sitemetatag_nonwidgetized_image')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
        }
        $logo_photo = $settings->getSetting('sitemetatag.nonwidgetized.image', false);
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