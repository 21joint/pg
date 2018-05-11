<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: LikeReactionsLink.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereaction_View_Helper_LikeReactionsLink extends Sitereaction_View_Helper_ReactionsCore {
  protected $indexType = 'type';

  public function likeReactionsLink($subject = null, $tabs = false, $selected = null) {

    if (!$subject) {
      return $this;
    }

     
    $popularity = Engine_Api::_()->getApi('core', 'sitereaction')->getLikesReactionPopularity($subject);
    $icons = $this->getIcons();
    $popularityIcons = array();
    $counter = 0;
    foreach ($popularity as $row) {
      if (!isset($icons[$row['reaction']]) || empty($row['reaction_count'])) {
        continue;
      }
      $popularityIcons[$row['reaction']] = array_merge($icons[$row['reaction']], array(
        'count' => $row['reaction_count']));
      $counter++;
      if (!$tabs && $counter === 3) {
        break;
      }
    }

    if (empty($popularityIcons)) {
      return;
    }
    $isMobileMode = Engine_Api::_()->seaocore()->isSiteMobileModeEnabled();
    $filePrefix = !$tabs && $isMobileMode  ? 'application/modules/Sitereaction/views/sitemobile/scripts/' : '';
    return $this->view->partial(
        $tabs ? '_likeReactionsTabs.tpl' : $filePrefix.'_likeReactionsLink.tpl', 'sitereaction', array(
        'subject' => $subject,
        'popularity' => $popularityIcons,
        'selected' => $selected,
        'icons' => $icons,
        //    'class' => get_class($subject)
        )
    );
  }

}
