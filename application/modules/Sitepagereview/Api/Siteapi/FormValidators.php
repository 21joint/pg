<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagealbum
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */


class Sitepagereview_Api_Siteapi_FormValidators extends Siteapi_Api_Validators {

    /**
     * Review form validators
     * 
     * @param type $widgetSettingsReviews
     * @return array
     */
    public function getReviewCreateFormValidators($widgetSettingsReviews) {

        $getItemPage = $widgetSettingsReviews['item'];
        $sitepagereview_proscons = $widgetSettingsReviews['settingsReview']['sitepagereview_proscons'];
        $sitepagereview_limit_proscons = $widgetSettingsReviews['settingsReview']['sitepagereview_limit_proscons'];
        $sitepagereview_recommend = $widgetSettingsReviews['settingsReview']['sitepagereview_recommend'];
        if ($sitepagereview_proscons) {
            if ($sitepagereview_limit_proscons) {
                $formValidators['pros'] = array(
                    'allowEmpty' => false,
                    'maxlength' => $widgetSettingsReviews['sitepagereview_limit_proscons'],
                    'required' => true,
                    'filters' => array(
                        'StripTags',
                        new Engine_Filter_Censor(),
                        new Engine_Filter_HtmlSpecialChars(),
                        new Engine_Filter_EnableLinks(),
                    ),
                );
            } else {
                $formValidators['pros'] = array(
                    'allowEmpty' => false,
                    'required' => true,
                    'filters' => array(
                        'StripTags',
                        new Engine_Filter_Censor(),
                        new Engine_Filter_HtmlSpecialChars(),
                        new Engine_Filter_EnableLinks(),
                    ),
                );
            }
            if ($sitepagereview_limit_proscons) {
                $formValidators['cons'] = array(
                    'allowEmpty' => false,
                    'maxlength' => $widgetSettingsReviews['sitepagereview_limit_proscons'],
                    'required' => true,
                    'filters' => array(
                        'StripTags',
                        new Engine_Filter_Censor(),
                        new Engine_Filter_HtmlSpecialChars(),
                        new Engine_Filter_EnableLinks(),
                    ),
                );
            } else {
                $formValidators['cons'] = array(
                    'allowEmpty' => false,
                    'required' => true,
                    'filters' => array(
                        'StripTags',
                        new Engine_Filter_Censor(),
                        new Engine_Filter_HtmlSpecialChars(),
                        new Engine_Filter_EnableLinks(),
                    ),
                );
            }
        }
        $formValidators['title'] = array(
            'required' => true,
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
                new Engine_Filter_HtmlSpecialChars(),
                new Engine_Filter_EnableLinks(),
            ),
        );
        
        // $formValidators['review_rate_0'] = array(
        //     'required' => true,
        // );
        
        return $formValidators;
    }
    
    /*
    * Comment validation form
    *
    * @return array
    */
    public function getcommentValidation()
    {
        $formValidators['body'] = array(
            'required' => true,
        );
        
        return $formValidators;
    }

}
