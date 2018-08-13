<?php
/**
 * EXTFOX
 *
 * @package    Photo Badges Box
 */

class Sdparentalguide_View_Helper_ItemPhotoBadgeColor
  extends Zend_View_Helper_Abstract
{

  public function ItemPhotoBadgeColor($subject)
  {

    if (!($subject instanceof User_Model_User)) {
      return;
    }

    if ($subject->getIdentity() < 1) {
      return;
    }

    $itemsInfo = array();

    $x = 0;
    $class = null;


    if ($subject->gg_expert_platinum_count > 0) {
      $class = 'platinum';
      $count = $subject->gg_expert_platinum_count;
    } else {
      if ($subject->gg_expert_gold_count > 0) {
        $class = 'gold';
        $count = $subject->gg_expert_gold_count;
      } else {
        if ($subject->gg_expert_silver_count > 0) {
          $class = 'silver';
          $count = $subject->gg_expert_silver_count;
        } else {
          if ($subject->gg_expert_bronze_count > 0) {
            $class = 'bronze';
            $count = $subject->gg_expert_bronze_count;
          } else {
            if ($subject->gg_expert_platinum_count < 1
              || $subject->gg_expert_gold_count < 1
              || $subject->gg_expert_silver_count < 1
              || $subject->gg_expert_bronze_count < 1
            ) {
              $class = 'default';
              $count = $subject->gg_contribution_level;
            }
          }
        }
      }
    }
    $bordergear = ''; //Define before using in conditions
    if ($subject->gg_mvp === 1) {
      $bordergear = 'mvp-border';
    }

    $itemsInfo['gear'] = $bordergear;
    $itemsInfo['class'] = $class;
    $itemsInfo['count'] = $count;

    return $itemsInfo;

  }
}
