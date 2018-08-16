<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */


class Pgservicelayer_Api_V1_Forms extends Sitereview_Api_Siteapi_Core
{
    private function translate($message = '') {
        return Engine_Api::_()->getApi('Core', 'siteapi')->translate($message);
    }
    public function getReviewForm(){
        $form = array();
        $form[] = array(
            'type' => 'Text',
            'name' => 'title',
            'label' => $this->translate('Review Title'),
            'hasValidator' => 'true'
        );
        $form[] = array(
            'type' => 'Select',
            'name' => 'typeID',
            'label' => $this->translate('Review Category'),
            'hasValidator' => 'true'
        );        
        $form[] = array(
            'type' => 'Select',
            'name' => 'categoryID',
            'label' => $this->translate('Review Sub-Category'),
            'hasValidator' => 'true',
        );
        $form[] = array(
            'type' => 'Select',
            'name' => 'subCategoryID',
            'label' => $this->translate('2nd Level Category'),
//            'hasValidator' => 'true',
        );
        $form[] = array(
            'type' => 'Textarea',
            'name' => 'summaryDescription',
            'label' => $this->translate('Description'),
            'hasValidator' => 'true'
        );        
        $form[] = array(
            'type' => 'Text',
            'name' => 'search',
            'label' => 'Search',
        );
        $form[] = array(
            'type' => 'Text',
            'name' => 'ownerRating',
            'label' => $this->translate('Rate this product'),
            'hasValidator' => 'true'
        );
        $form[] = array(
            'type' => 'Text',
            'name' => 'photoID',
            'label' => $this->translate('Cover Photo'),
//            'hasValidator' => 'true'
        );
        $form[] = array(
            'type' => 'Select',
            'name' => 'authView',
            'label' => 'View Privacy',
        );
        $form[] = array(
            'type' => 'Select',
            'name' => 'authComment',
            'label' => 'Comment Privacy',
        );
        $form[] = array(
            'type' => 'Select',
            'name' => 'authTopic',
            'label' => 'Discussion Topic Privacy',
        );
        $form[] = array(
            'type' => 'Select',
            'name' => 'authPhoto',
            'label' => 'Photo Privacy',
        );
        $form[] = array(
            'type' => 'Select',
            'name' => 'authVideo',
            'label' => 'Video Privacy',
        );
        return $form;
    }
    
    public function getQuestionForm(){
        $form = array();
        $form[] = array(
            'type' => 'Text',
            'name' => 'title',
            'label' => $this->translate('Title'),
            'hasValidator' => 'true'
        );
        $form[] = array(
            'type' => 'Text',
            'name' => 'topicID',
            'label' => $this->translate('Topic'),
            'hasValidator' => 'true'
        );
        $form[] = array(
            'type' => 'Textarea',
            'name' => 'body',
            'label' => $this->translate('Description'),
            'hasValidator' => 'true'
        );
        $form[] = array(
            'type' => 'Text',
            'name' => 'photoID',
            'label' => $this->translate('Cover Photo'),
//            'hasValidator' => 'true'
        );        
        return $form;
    }
    
    public function getGuideForm(){
        $form = array();
        $form[] = array(
            'type' => 'Text',
            'name' => 'title',
            'label' => $this->translate('Title'),
            'hasValidator' => 'true'
        );
        $form[] = array(
            'type' => 'Text',
            'name' => 'topicID',
            'label' => $this->translate('Topic'),
            'hasValidator' => 'true'
        );
        $form[] = array(
            'type' => 'Textarea',
            'name' => 'longDescription',
            'label' => $this->translate('Description'),
            'hasValidator' => 'true'
        );
        $form[] = array(
            'type' => 'Text',
            'name' => 'coverPhotoID',
            'label' => $this->translate('Cover Photo'),
//            'hasValidator' => 'true'
        );        
        return $form;
    }
}