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

class Siteseo_Form_Admin_Schema extends Engine_Form {

    public function init() {

        $this->setDescription('Here you can enter the information needed to set schema markup tags. These schema markups will help to create rich content for your website in Search Engine Result Pages (SERP).');
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $schemaTypes = array(
            'WebSite' => 'WebSite', 
            'Organization' => 'Organization',
            'custom' => 'Custom Schema',
            );
        $this->addElement('Select', 'siteseo_schema_type', array(
          'label' => 'Schema Type',
          'description' => 'Enter the type of schema for your website (Note: Logo and Corporate Contacts markups do not work in website schema type.)',
          'multiOptions' => $schemaTypes,
          'value' => $settings->getSetting("siteseo.schema.type", 'Organization'),
        ));

        $this->addElement('Text', 'site_title', array(
          'label' => 'Site Title',
          'description' => 'CORE_FORM_ADMIN_SETTINGS_GENERAL_SITETITLE_DESCRIPTION',
          'value' => $settings->getSetting("core.general.site.title", ''),
        ));

        $this->addElement('Text', 'siteseo_schema_alternate_name', array(
            'label' => 'Website Alternate Name',
            'description' => 'Enter the alternate name of your website.',
            'value' => $settings->getSetting("siteseo.schema.alternate.name", ''),
            ));

        $this->addElement('Text', 'siteseo_schema_logo', array(
            'label' => 'Website Logo Path',
            'description' => 'Enter the full path of your website logo.',
            'value' => $settings->getSetting("siteseo.schema.logo", ''),
            ));

        $description = 'Below you have to enter the social media profiles of your website / organization. These details will be directly reflected on the knowledge graph of Search engine result page.';
        $this->addElement('Dummy', 'siteseo_schema_social_text', array(
            'label' => 'Social Media Profiles',
            'description' => $description,
            ));
        $this->siteseo_schema_social_text->addDecorator('Description', array('escape' => false));

        $this->addElement('Text', 'siteseo_schema_social_facebook', array(
            'label' => 'Facebook Profile',
            'description' => 'Enter the Facebook url of your website.',
            'value' => $settings->getSetting("siteseo.schema.social.facebook", ''),
            ));

        $this->addElement('Text', 'siteseo_schema_social_twitter', array(
            'label' => 'Twitter Profile',
            'description' => 'Enter the Twitter url of your website.',
            'value' => $settings->getSetting("siteseo.schema.social.twitter", ''),
            ));

        $this->addElement('Text', 'siteseo_schema_social_instagram', array(
            'label' => 'Instagram Profile',
            'description' => 'Enter the Instagram url of your website.',
            'value' => $settings->getSetting("siteseo.schema.social.instagram", ''),
            ));

        $this->addElement('Text', 'siteseo_schema_social_linkedin', array(
            'label' => 'Linkedin Profile',
            'description' => 'Enter the LinkedIn url of your website.',
            'value' => $settings->getSetting("siteseo.schema.social.linkedin", ''),
            ));

        $this->addElement('Text', 'siteseo_schema_social_googleplus', array(
            'label' => 'Googleplus Profile',
            'description' => 'Enter the GooglePlus url of your website.',
            'value' => $settings->getSetting("siteseo.schema.social.googleplus", ''),
            ));

        $this->addElement('Text', 'siteseo_schema_social_youtube', array(
            'label' => 'youtube Profile',
            'description' => 'Enter the Youtube url of your website.',
            'value' => $settings->getSetting("siteseo.schema.social.youtube", ''),
            ));

        $this->addElement('Textarea', 'siteseo_schema_social_others', array(
            'label' => 'Other Social Media Profiles',
            'description' => 'Enter the URL of other social media profiles of your website/Organization.Note: Use comma to separate for more than one URL.',
            'value' => $settings->getSetting("siteseo.schema.social.others", ''),
            ));

        $this->addDisplayGroup(array('siteseo_schema_social_text', 'siteseo_schema_social_facebook', 'siteseo_schema_social_twitter', 'siteseo_schema_social_instagram', 'siteseo_schema_social_linkedin', 'siteseo_schema_social_googleplus', 'siteseo_schema_social_youtube', 'siteseo_schema_social_others'), 'social_profiles');

        $contactTypeProperties = array(
            'telephone' => 'telephone',
            );

        $contactTypeOptions = array(
            "customer support" => "customer support",
            "technical support" => "technical support",
            "billing support" => "billing support",
            "bill payment" => "bill payment",
            "sales" => "sales",
            "reservations" => "reservations",
            "credit card support" => "credit card support",
            "emergency" => "emergency",
            "baggage tracking" => "baggage tracking",
            "roadside assistance" => "roadside assistance",
            "package tracking" => "package tracking",
            );

        $description = 'Below you can give the details about the corporate contact information of your Organization. These information will be directly reflected on the knowledge graph of Search Engine result page. ';
        $this->addElement('Dummy', 'siteseo_schema_contact_text', array(
            'label' => 'Corporate Contacts',
            'description' => $description,
            ));
        $this->siteseo_schema_contact_text->addDecorator('Description', array('escape' => false));

        for ($i=1; $i <= 3; $i++) { 
            $this->addElement('Select', 'siteseo_schema_contactpoint' . $i . '_property', array(
                'label' => 'Contact Point Property',
                'description' => 'Choose the Contact Point Property',
                'multiOptions' => $contactTypeProperties,
                'value' => $settings->getSetting('siteseo.schema.contactpoint' . $i . '.property', ''),
                ));

            $this->addElement('Select', 'siteseo_schema_contactpoint' . $i . '_type', array(
                'label' => 'Contact Point Type',
                'description' => 'Choose the  Contact Point Type.',
                'multiOptions' => $contactTypeOptions,
                'value' => $settings->getSetting('siteseo.schema.contactpoint' . $i . '.type', ''),
                ));

            $this->addElement('Text', 'siteseo_schema_contactpoint' . $i . '_value', array(
                'label' => 'Contact Point Value',
                'description' => 'Enter the Contact Point Value for your Contact point.',
                'value' => $settings->getSetting('siteseo.schema.contactpoint' . $i . '.value', ''),
                ));
        }

        $this->addDisplayGroup(array('siteseo_schema_contact_text', 'siteseo_schema_contactpoint1_property', 'siteseo_schema_contactpoint1_type', 'siteseo_schema_contactpoint1_value', 'siteseo_schema_contactpoint2_property', 'siteseo_schema_contactpoint2_type', 'siteseo_schema_contactpoint2_value','siteseo_schema_contactpoint3_property', 'siteseo_schema_contactpoint3_type', 'siteseo_schema_contactpoint3_value'), 'corporate_contacts');

        $this->addElement('Radio', 'siteseo_schema_searchbox_show', array(
            'label' => 'Shown Sitelink Search box',
            'description' => 'Do you want to show sitelink search box on Search Result Page.',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
                ),
            'value' => $settings->getSetting("siteseo.schema.searchbox.show", 1),
            ));

        $this->addElement('Textarea', 'siteseo_schema_custom', array(
            'label' => 'Custom Schema Markup',
            'description' => 'Enter the Custom Schema Markup you want to enter for your website in json-ld format. [Note: You need not to include script tags, you can just add the json code.]',
            'value' => $settings->getSetting("siteseo.schema.custom", ''),
            ));

        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
            ));
    }
}