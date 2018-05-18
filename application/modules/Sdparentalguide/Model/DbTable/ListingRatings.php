<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_DbTable_ListingRatings extends Engine_Db_Table
{
    protected $_rowClass = "Sdparentalguide_Model_ListingRating";
    protected $_name = 'gg_listing_ratings';
    
    public function getRating(Sitereview_Model_Listing $listing, User_Model_User $user){
        return $this->fetchRow($this->select()->where('listing_id = ?',$listing->getIdentity())->where('user_id = ?',$user->getIdentity()));
    }
} 




