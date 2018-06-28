<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Pgservicelayer_Api_Validators extends Core_Api_Abstract
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
        $formValidators['summaryDescription'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        $formValidators['ownerRating'] = array(
            'required' => true,
            'allowEmpty' => false,
        );
        return $formValidators;
    }
}