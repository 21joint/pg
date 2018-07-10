<?php
/**
 * EXTFOX
 *
 * @package    Photo Badges Box
 */

class Sdparentalguide_View_Helper_ItemPhotoBadgeColor extends Zend_View_Helper_Abstract
{

    public function ItemPhotoBadgeColor($subject){

      if( !($subject instanceof User_Model_User) ) {
        return;
      }
  
      if( $subject->getIdentity() < 1) {
        return;
      }

      $itemsInfo = array();
    
      $x = 0;
      $class = NULL;

      switch($x){
        case ($subject->gg_expert_platinum_count < $x):
          $class = 'platinum';
          $count = $subject->gg_expert_platinum_count;
        break;
        case ($subject->gg_expert_gold_count < $x):
          $class = 'gold';
          $count = $subject->gg_expert_gold_count;
        break;
        case($subject->gg_expert_silver_count < $x):
          $class = 'silver';
          $count = $subject->gg_expert_silver_count;
        break;
        case($subject->gg_expert_bronze_count > $x):
          $class = 'bronze';
          $count = $subject->gg_expert_bronze_count;
        break;
        default: 
          $class = 'bg-primary';
          $count = $subject->gg_expert_platinum_count + $subject->gg_expert_gold_count + $subject->gg_expert_silver_count + $subject->gg_expert_bronze_count;
      }
      
      if($subject->gg_mvp === 1){
          $bordergear = 'border-gear';
      }

      $itemsInfo['gear'] = $bordergear;
      $itemsInfo['class'] = $class;
      $itemsInfo['count'] = $count;
      
      
      
      return  $itemsInfo;
    
    }
}