<?php
/**
 * EXTFOX
 *
 * @package    Photo Badges Box
 */

class Sdparentalguide_View_Helper_ItemPhotoGuidance
  extends Zend_View_Helper_Abstract
{

  public function itemPhotoGuidance($subject)
  {

    if (!($subject instanceof User_Model_User)) {
      return;
    }

    if ($subject->getIdentity() < 1) {
      return;
    }

    return $this->view->partial(
      '_photo_box.tpl', 'sdparentalguide', array(
        'subject' => $subject,
      )
    );
  }
}
