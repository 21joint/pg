<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_Api_Core extends Core_Api_Abstract {
  /**
   *  Gets the popularity count on the basis of reactions
   *
   *
   */
  public function getLikesReactionPopularity($subject = null) {
    if ($subject->getType() == 'activity_action') {
      return $popularity = $subject->getLikesReactionPopularity();
    }
    $table = Engine_Api::_()->getDbTable('likes', 'core');
    $select = new Zend_Db_Select($table->getAdapter());
    $select->from($table->info('name'), array('reaction', 'count(*) as reaction_count'))
      ->group('reaction')
      ->order('reaction_count desc')
      ->order('like_id desc');
    $select->where('resource_type = ?', $subject->getType());
    $select->where('resource_id = ?', $subject->getIdentity());
    return $select->query()->fetchAll();
  }

  public function addReactionFeed() {

    }

}
