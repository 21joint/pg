<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    SiteSeo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: SearchConsoleKey.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_Form_Admin_SearchConsoleKey extends Engine_Form {

    public function init() {

        $this->setTitle('Upload Search Console Key');
        $this->setDescription('Here you can upload the json key file that you got by creating a service account.');

        $this->addElement('File', 'jsonkey', array(
            'label' => 'Service Account Key',
            'description' => 'Upload the service account json key file. This key will be required for the submission / automatic submission of sitemap files to google search console.',
        ));

        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
            ));
    }
}