<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    SiteSeo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_Form_Admin_Global extends Engine_Form {
    
    // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
    public $_SHOWELEMENTSBEFOREACTIVATE = array(
	"submit_lsetting", "environment_mode"
    );

    public function init() {
        
        $productType = 'siteseo';
        
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
        $this->setDescription('This page contains the general settings for SEO and Sitemap plugin.');
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $this->addElement('Text', 'siteseo_sitemap_filename', array(
            'label' => 'Sitemap File Name',
            'description' => 'Enter the filename of XML Sitemap file. (Note: Please enter the name without extension. The extension would be attached to the file automatically.)',
            'allowEmpty' => false,
            'required' => true,
            'value' => $settings->getSetting("siteseo.sitemap.filename", 'sitemap'),
            ));

        $this->addElement('Select', 'siteseo_sitemap_url_limit', array(
            'label' => 'Sitemap URL Limit',
            'description' => 'Select the maximum number of URL you want to be added in a single sitemap file. Once the file reaches the selected URL limit then a new sitemap file will be created.',
            'multiOptions' => array(
                500 => 500,
                1000 => 1000,
                2000 => 2000,
                5000 => 5000,
                10000 => 10000,
                20000 => 20000,
                50000 => 50000,
                ),
            'value' => $settings->getSetting("siteseo.sitemap.url.limit", 1000),
            ));

        $this->addElement('Radio', 'siteseo_sitemapindex_url_compressed', array(
            'label' => 'Add Compressed Sitemap links to Sitemap Index File',
            'description' => 'Do you want to add link of compressed file of content sitemaps to sitemap index file.',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
                ),
            'value' => $settings->getSetting("siteseo.sitemapindex.url.compressed", 1),
            ));

        $this->addElement('Radio', 'siteseo_hreflang_enable', array(
            'label' => 'Enable hreflang',
            'description' => 'Do you want to display hreflang tag for language and regional urls.',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
                ),
            'value' => $settings->getSetting("siteseo.hreflang.enable", 1),
            ));

        $this->addElement('Radio', 'siteseo_opensearch_enable', array(
            'label' => 'Enable Open Search Description',
            'description' => 'Do you want to enable Open Search Description for your website. An OpenSearch description document can be used to describe the web interface of a search engine, eg. website search in Google chrome\'s Omnibox.',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
                ),
            'value' => $settings->getSetting("siteseo.opensearch.enable", 1),
            ));

        $this->addElement('Radio', 'siteseo_canonical_enable', array(
            'label' => 'Enable Canonical Tag',
            'description' => 'Do you want to enable Canonical Tag for your website.',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
                ),
            'value' => $settings->getSetting("siteseo.canonical.enable", 1),
            ));

        $this->addElement('Radio', 'siteseo_metatags_overwrite', array(
            'label' => 'Overwrite Meta Title and Description',
            'description' => 'Do you want to overwrite the meta title and description for a content profile page that has been set using other plugins.',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
                ),
            'value' => $settings->getSetting("siteseo.metatags.overwrite", 1),
            'onchange' => 'toggleOverwriteFields(this.value)',
            ));

        $clickHere = '<a href="admin/siteseo/settings/faq/target/6" " target="_blank">Click here</a>';
        $generalSettings = '<a href="admin/core/settings/general" " target="_blank">Website Title</a>';
        $this->addElement('Radio', 'siteseo_sitetile_append', array(
            'label' => 'Meta Title',
            'description' => "Select how you want to display the meta title for the content (profile page) being shared from your website?. (Note: To have a clear idea about how these options will work, please $clickHere.)",
            'multiOptions' => array(
                'none' => 'None',
                'site' => 'Website Title',
                'page' => 'Page Meta Title',
                'both' => 'Both Website and Page Meta Title',
                ),
            'value' => $settings->getSetting("siteseo.sitetile.append", 'none'),
            ));

        $this->getElement('siteseo_sitetile_append')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

        $this->addElement('Radio', 'siteseo_sitedescription_append', array(
            'label' => 'Meta Description',
            'description' => "Select how you want to display the meta description for the content (profile page) being shared from your website?. (Note: To have a clear idea about how these options will work, please $clickHere.)",
            'multiOptions' => array(
                'none' => 'None',
                'site' => 'Website Description',
                'page' => 'Page Meta Description',
                'both' => 'Both Website and Page Meta Description',
                ),
            'value' => $settings->getSetting("siteseo.sitedescription.append", 'none'),
            ));

        $this->getElement('siteseo_sitedescription_append')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

        $this->addDisplayGroup(array('siteseo_sitetile_append', 'siteseo_sitedescription_append'), 'overwrite_fields');

        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
            ));
    }
}