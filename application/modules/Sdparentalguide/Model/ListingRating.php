<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_ListingRating extends Core_Model_Item_Abstract
{
    protected $_searchTriggers = false;
    public function getRatingClass($ratingValue){
        $rating_value = "";
        switch ($ratingValue) {
            case 0:
              $rating_value = '';
              break;
            case 1:
              $rating_value = 'onestar';
              break;
            case 2:
              $rating_value = 'twostar';
              break;
            case 3:
              $rating_value = 'threestar';
              break;
            case 4:
              $rating_value = 'fourstar';
              break;
            case 5:
              $rating_value = 'fivestar';
              break;
        }
        return $rating_value;
    }
} 




