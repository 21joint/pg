<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Claims.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Model_DbTable_Claims extends Engine_Db_Table {

  protected $_rowClass = "Sitepageoffer_Model_Claim";

  public function getClaimValue($owner_id,$offer_id,$page_id) {

		$claim_value = $this->select()
				->from('engine4_sitepageoffer_claims', 'claim_value')
				->where('owner_id =?', $owner_id)
				->where('offer_id =?', $offer_id)
				->where('page_id =?', $page_id)
				->limit(1)
				->query()
				->fetchColumn(0);
    return $claim_value;
  }
  
  public function deleteClaimOffers($offer_id) {

    $select = $this->select()
                  ->where('offer_id =?', $offer_id);
    $resultOfferClaims = $this->fetchAll($select);
  
    foreach ($resultOfferClaims as $offer) {
      $claim = Engine_Api::_()->getItem('sitepageoffer_claim',$offer->claim_id);
      $claim->delete();
    }
  }
}
?>