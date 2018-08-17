<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Pgservicelayer
 * @author     Stars Developer
 */


class Pgservicelayer_Api_V1_Validators extends Core_Api_Abstract
{
    public function getReviewValidators(){
        $formValidators = array();
        $formValidators['title'] = array(
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(array('NotEmpty', true), array('StringLength', false, array(3, 63)))
        );
        $formValidators['typeID'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        $formValidators['categoryID'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        $formValidators['longDescription'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        $formValidators['authorRating'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        return $formValidators;
    }
    
    public function getQuestionValidators(){
        $formValidators = array();
        $formValidators['title'] = array(
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(array('NotEmpty', true), array('StringLength', false, array(3, 63)))
        );
        $formValidators['topicID'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        $formValidators['body'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        return $formValidators;
    }
    
    public function getGuideValidators(){
        $formValidators = array();
        $formValidators['title'] = array(
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(array('NotEmpty', true), array('StringLength', false, array(3, 63)))
        );
        $formValidators['topicID'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        $formValidators['longDescription'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        return $formValidators;
    }
    
    public function getGuideItemValidators(){
        $formValidators = array();
        $formValidators['contentType'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        $formValidators['contentID'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        $formValidators['description'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        return $formValidators;
    }
}