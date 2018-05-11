<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteapi
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Core.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Core_Api_Siteapi_Core extends Core_Api_Abstract {

    protected $_noPhotos;

    public function getEditForm($subject) {

        $fieldsArray = array();

        $fieldsArray[] = array(
            'type' => 'text',
            'name' => 'title',
            'label' => $this->translate('Title of the video'),
        );

        $fieldsArray = array(
            'type' => 'text',
            'name' => 'tags',
            'label' => $this->translate('Tags (Keywords)'),
            'description' => $this->translate('Separate tags with commas.'),
        );

        $fieldsArray[] = array(
            'type' => 'textarea',
            'name' => 'description',
            'label' => $this->translate('Description of the video'),
        );

        $fieldsArray[] = array(
            'type' => 'checkbox',
            'name' => 'search',
            'label' => $this->translate('Show this video in search results.'),
            'value' => 1,
        );

        $fieldsArray[] = array(
            'type' => 'button',
            'name' => 'cancel',
            'label' => $this->translate("Canel"),
            'description' => $this->translate("Cancels the video edition"),
        );

        $fieldsArray[] = array(
            'type' => 'submit',
            'name' => 'submit',
            'label' => $this->translate("Submit"),
            'description' => $this->translate("Submits the form"),
        );

        return $fieldsArray;
    }

    /*
     * Contact Form
     */

    public function getContactForm() {
        $contactForm = array();

        $contactForm[] = array(
            'type' => 'Text',
            'name' => 'name',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Name'),
            'hasValidator' => true
        );

        $contactForm[] = array(
            'type' => 'Text',
            'name' => 'email',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Email Address'),
            'hasValidator' => true
        );

        $contactForm[] = array(
            'type' => 'Textarea',
            'name' => 'body',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Message'),
            'hasValidator' => true
        );

        $contactForm[] = array(
            'type' => 'Submit',
            'name' => 'submit',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Send Message')
        );

        return $contactForm;
    }

    /*
     * Report Form
     */

    public function getReportForm() {
        $searchForm = array();
        $viewer = Engine_Api::_()->user()->getViewer();

        $reportCategories = array(
            '' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('(select)'),
            'spam' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Spam'),
            'abuse' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Abuse'),
            'inappropriate' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Inappropriate Content'),
            'licensed' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Licensed Material'),
            'other' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Other'),
        );

        $searchForm[] = array(
            'type' => 'Select',
            'name' => 'category',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Type'),
            'multiOptions' => $reportCategories,
            'hasValidator' => true
        );

        $searchForm[] = array(
            'type' => 'Textarea',
            'name' => 'description',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Description'),
            'hasValidator' => true
        );

        $searchForm[] = array(
            'type' => 'Submit',
            'name' => 'submit',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Submit Report')
        );

        return $searchForm;
    }

    /*
     * Get no photo urls
     */

    public function getNoPhoto($item, $type) {
        $type = ( $type ? str_replace('.', '_', $type) : 'main' );

        if (($item instanceof Core_Model_Item_Abstract)) {
            $item = $item->getType();
        } else if (!is_string($item)) {
            return '';
        }

        if (!Engine_Api::_()->hasItemType($item)) {
            return '';
        }

        // Load from registry
        if (null === $this->_noPhotos) {
            // Process active themes
//      $themesInfo = Zend_Registry::get('Themes');
//      foreach( $themesInfo as $themeName => $themeInfo ) {
//        if( !empty($themeInfo['nophoto']) ) {
//          foreach( (array)@$themeInfo['nophoto'] as $itemType => $moreInfo ) {
//            if( !is_array($moreInfo) ) continue;
//            $this->_noPhotos[$itemType] = array_merge((array)@$this->_noPhotos[$itemType], $moreInfo);
//          }
//        }
//      }
//    }
//    echo '42543';die;    
        }
        // Use default    
        $getHosts = Engine_Api::_()->getApi('Core', 'siteapi')->getHost();
        if (!isset($this->_noPhotos[$item][$type])) {
            $shortType = $item;
            if (strpos($shortType, '_') !== false) {
                list($null, $shortType) = explode('_', $shortType, 2);
            }

            $module = Engine_Api::_()->inflect(Engine_Api::_()->getItemModule($item));
            $this->_noPhotos[$item][$type] = //$this->view->baseUrl() . '/' .
                    'application/modules/' .
                    $module .
                    '/externals/images/nophoto_' .
                    $shortType . '_'
                    . $type . '.png';

//        $this->view->layout()->staticBaseUrl . 'application/modules/' .
//        $module .
//        '/externals/images/nophoto_' .
//        $shortType . '_'
//        . $type . '.png';
        }

        return $this->_noPhotos[$item][$type];
    }

}