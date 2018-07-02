<?php
/**
 * EXTFOX
 *
 * @package    Photo Badges Box
 */

class Sdparentalguide_View_Helper_ItemPhotoBadgeColor extends Zend_View_Helper_Abstract
{

    public function ItemPhotoBadgeColor($subject){
        
        $x = 0;
        $class = NULL;
    
        switch($x){
          case ($subject->gg_expert_platinum_count > $x):
            $class = 'platinum';
          break;
          case ($subject->gg_expert_gold_count > $x):
            $class = 'gold';
          break;
          case($subject->gg_expert_silver_count > $x):
            $class = 'silver';
          break;
          case($subject->gg_expert_bronze_count > $x):
            $class = 'bronze';
          break;
          default: 
            $class = 'bg-primary';
        }
       
        return $class;
    
      }
}
