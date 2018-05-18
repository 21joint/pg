<?php

/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Siteseo
* @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: SeoMetaTags.php 2017-03-27 9:40:21Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/
class Siteseo_View_Helper_SchemaMarkup extends Zend_View_Helper_Abstract {

    // SET SCHEMA MARKUP FOR WEBSITE
    public function schemaMarkup() {

        $pageInfo = Engine_Api::_()->siteseo()->getCurrentPageinfo();
        if (empty($pageInfo))
            return ;

        // GET SHCEMA FOR CURRENT PAGE
        $schema = $this->getSchema($pageInfo);

        if (empty($schema))
            return ;

        // PRINT SCHEMA IN JSON-LD FORMAT
        return $this->view->partial('_schema.tpl', 'siteseo', array('schema' => $schema));
    }

    // RETURNS SCHEMA FOR A PAGE
    public function getSchema($pageInfo) {
        $subject = $this->view->subject();
        if(empty($subject) && $pageInfo->schema)
            return $pageInfo->schema;
        elseif ($subject) {
            return $this->getContentSchema($subject);
        }

        $settings = Engine_Api::_()->getApi('settings', 'core');
        $type = $settings->getSetting('siteseo.schema.type', 'Organization');

        if ($type == 'custom') {
            $customSchema = $settings->getSetting('siteseo.schema.custom', '');
            return $customSchema;
        }

        $website = $this->view->absoluteUrl($this->view->layout()->staticBaseUrl);
        $url = $this->view->absoluteUrl($this->view->url());
        $name = $settings->getSetting('core.general.site.title');
        $alternatName = $settings->getSetting('siteseo.schema.alternate.name');

        // GET SOCIAL PROFILE 
        $facebook = $settings->getSetting('siteseo.schema.social.facebook', '');
        $twitter = $settings->getSetting('siteseo.schema.social.twitter', '');
        $instagram = $settings->getSetting('siteseo.schema.social.instagram', '');
        $linkedin = $settings->getSetting('siteseo.schema.social.linkedin', '');
        $googleplus = $settings->getSetting('siteseo.schema.social.googleplus', '');
        $youtube = $settings->getSetting('siteseo.schema.social.youtube', '');
        $others = $settings->getSetting('siteseo.schema.social.others', '');
        $others = explode(',', $others);
        $socialProfiles = array($facebook, $twitter, $instagram, $linkedin, $googleplus, $youtube);
        $socialProfiles = array_merge($socialProfiles, $others);
        $socialProfiles = array_map('trim', $socialProfiles);
        $socialProfiles = array_filter($socialProfiles);

        // GET CONTACTS 
        
        $contactPointArray = array();
        $logo = '';
        // GET CONTACT AND LOGO ONLY IF TYPE IS ORGANIZATION
        if ($type == 'Organization') {
            $logo = $settings->getSetting('siteseo.schema.logo');
            for ($i=1; $i <= 3; $i++) { 
                $contactProperty = $settings->getSetting('siteseo.schema.contactpoint' . $i . '.property', '');
                $contactType = $settings->getSetting('siteseo.schema.contactpoint' . $i . '.type', '');
                $contactValue = $settings->getSetting('siteseo.schema.contactpoint' . $i . '.value', '');
                if($contactProperty && $contactType && $contactValue ) {
                    $contactPointElement = array();
                    $contactPointElement['@type'] = 'ContactPoint';
                    $contactPointElement[$contactProperty] = $contactValue;
                    $contactPointElement['contactType'] = $contactType;
                    $contactPointArray[] = $contactPointElement;
                }
            }
        }

        // GET SEARCH BOX URL
        $showSearch = $settings->getSetting('siteseo.schema.searchbox.show', true);
        $potentialActionArray = array();
        if($showSearch) {
            $potentialActionElement = array();
            $potentialActionElement['@type'] = 'SearchAction';
            $potentialActionElement['target'] = $website . 'search?q={search_term_string}';
            $potentialActionElement['query-input'] = 'required name=search_term_string';
            $potentialActionArray[] = $potentialActionElement;
        }

        // CREATE SCHEMA ARRAY
        $schema = array(
            "@context" => "http://schema.org",
            "@type" => $type,
            "name" => $name,
            "alternateName" => $alternatName,
            "url" => $url,
            "logo" => $logo,
            "contactPoint" => $contactPointArray,
            "potentialAction" => $potentialActionArray,
            "sameAs" => $socialProfiles,
            );

         // REMOVE EMPTY SCHEMAS 
        $schema = array_filter($schema);
        $schema = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return $schema;
    }

    public function getContentSchema($subject) {

        $contentTypeTable = Engine_Api::_()->getDbtable('contenttypes','siteseo');
        $params = array('type' => $subject->getType());
        $contentType = $contentTypeTable->getContentType($params);

        //RETURN IF SCHEMA IS DISABLED FOR THIS CONTENT TYPE
        if (empty($contentType->schema))
            return false;
        //RETURN IF SCHEMA IS TYPE IS NOT MAPPED FOR THIS CONTENT TYPE
        if (empty($contentType->schematype))
            return false;

        // CREATE SCHEMA ARRAY
        $type = $contentType->schematype;
        $specificType = !empty($contentType->specific_schematype) ? $contentType->specific_schematype : $type;
        $schema = array();
        $schema['@context'] = 'http://schema.org';
        $schema['@type'] = $specificType;
        $schema['name'] = $subject->getTitle();
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $mainSchemaType = $settings->getSetting('siteseo.schema.type', 'Organization');
        $mainSchemaLogo = $settings->getSetting('siteseo.schema.logo', '');
        $mainSchemaLogo = $settings->getSetting('siteseo.schema.logo', '');
        $siteTitle = $settings->getSetting("core.general.site.title", '');
        switch ($type) {

            case 'Article':
                $author = $image = $publisher = array();
                $author['@type'] = 'Person';
                $author['name'] = $subject->getOwner()->getTitle();
                $schema['author'] = $author;

                $date = isset($subject->creation_date) ? $subject->creation_date : date();
                $schema['datePublished'] = date('c', strtotime($date));
                
                $date = isset($subject->modified_date) ? $subject->modified_date : $date;
                $schema['dateModified'] = date('c', strtotime($date));

                $schema['image'] = $this->getImageObject($subject);

                $schema['url'] = $this->view->absoluteUrl($subject->getHref());
                $schema['headline'] = $subject->getTitle();

                if ($mainSchemaType == 'Organization') {
                    $publisher['@type'] = 'Organization';
                    $publisher['name'] = $siteTitle;

                    $image['@type'] = 'ImageObject';
                    $image['url'] = $mainSchemaLogo;
                    $image['width'] = '320';
                    $image['height'] = '320';
                    $publisher['logo'] = $image;
                }
                $schema['publisher'] = $publisher;
                // aggregate rating
                break;

            // case 'Book':
            //     $author = array();
            //     $author['@type'] = 'Person';
            //     $author['name'] = $subject->getOwner()->getTitle();
            //     $schema['author'] = $author;
            //     if (isset($subject->creation_date))
            //         $schema['datePublished'] = date('c', strtotime($subject->creation_date));
            //     $schema['image'] = $this->view->absoluteUrl($subject->getPhotoUrl());
            //     $schema['url'] = $this->view->absoluteUrl($subject->getHref());
            //     // workExample
            //     break;

            // case 'Course':
            //     $schema['description'] = $subject->getDescription();
            //     break;

            // case 'Dataset':
            //     $schema['description'] = $subject->getDescription();
            //     $schema['keywords'] = $subject->getKeywords();
            //     break;

            case 'Event':
                $schema['description'] = $subject->getDescription();
                $schema['image'] = $this->getImageObject($subject);
                if (isset($subject->starttime))
                    $schema['startDate'] = date('c', strtotime($subject->starttime));
                if (isset($subject->endtime))
                    $schema['endDate'] = date('c', strtotime($subject->endtime));
                // location
                $schema['location'] = $this->getLocation($subject);
                break;

            // case 'JobPosting':
            //     $schema['description'] = $subject->getDescription();
            //     if (isset($subject->creation_date))
            //         $schema['datePosted'] = date('c', strtotime($subject->creation_date));

            //     // validthrough
            //     // jobLocation
            //     // baseSalary
            //     // hiringOrganization
            //     break;

            // case 'LocalBusiness':
            //     $schema['description'] = $subject->getDescription();
            //     $schema['image'] = $this->view->absoluteUrl($subject->getPhotoUrl());
            //     $schema['url'] = $this->view->absoluteUrl($subject->getHref());
            //     break;

            case 'Product':
                $schema['description'] = $subject->getDescription();
                $schema['image'] = $this->getImageObject($subject);
                if (isset($subject->review_count) && $subject->review_count) {
                    $rating = array();
                    $rating['@type'] = 'AggregateRating';
                    $rating['ratingValue'] = isset($subject->rating_avg) ? $subject->rating_avg : '' ;
                    $rating['reviewCount'] = isset($subject->review_count) ? $subject->review_count : '' ;
                    $schema['aggregateRating'] = $rating;
                }
                // brand
                // review
                // aggregateRating
                // offers
                break;

            case 'Recipe':
                $schema['description'] = $subject->getDescription();
                $schema['image'] = $this->getImageObject($subject);

                $author = array();
                $author['@type'] = 'Person';
                $author['name'] = $subject->getOwner()->getTitle();
                $schema['author'] = $author;
                if (isset($subject->creation_date))
                    $schema['datePublished'] = date('c', strtotime($subject->creation_date));
                break;

            case 'Review':
                $schema['reviewBody'] = $subject->getDescription();
                $schema['image'] = $this->getImageObject($subject);

                // $itemReviewed = array();
                // $itemReviewed['@type'] = 'Person';
                // $itemReviewed['name'] = $subject->getOwner()->getTitle();
                // $schema['itemReviewed'] = $itemReviewed;

                // itemReviewed
                // reviewRating

                break;

            case 'VideoObject':
                $schema['description'] = $subject->getDescription();
                $schema['thumbnailUrl'] = $this->getImageObject($subject, 100, 100);
                $date = isset($subject->creation_date) ? $subject->creation_date : date();
                $schema['uploadDate'] = date('c', strtotime($date));
                $schema['duration'] = isset($subject->duration) ? $subject->duration : '';
                $schema['contentUrl'] = $this->view->absoluteUrl($subject->getHref());
                // publisher
                break;

            default:
                break;
        }
        $schema = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return $schema;
    }

    public function getLocation($subject) {
        $location = $address = array();
        $location['@type'] = 'Place';
        $location['name'] = isset($subject->location) ? $subject->location : '';
        $address['@type'] = 'PostalAddress';
        $address['streetAddress'] = '';
        $location['address'] = $address;
        return $location;
    }

    public function getImageObject($subject, $height = 720, $width = 720) {
        $image = array();
        $image['@type'] = 'ImageObject';
        $image['url'] = $this->view->absoluteUrl($subject->getPhotoUrl());
        $image['width'] = '720';
        $image['height'] = '720';
        return $image;
    }
}
