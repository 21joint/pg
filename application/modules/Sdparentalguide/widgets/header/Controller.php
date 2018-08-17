<?php

/**
 * EXTFOX
 *
 * @package    EXTFOX
 */
class Sdparentalguide_Widget_HeaderController
  extends Engine_Content_Widget_Abstract
{

  public function indexAction()
  {
    $headLink = new Zend_View_Helper_HeadLink();

    $headLink->appendStylesheet('/styles/header.bundle.css');

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

    // get notifications
    $this->view->notificationCount
      = $notificationCount = (int)Engine_Api::_()->getDbtable(
      'notifications', 'activity'
    )->hasNotifications($viewer);

    $this->view->updateSettings = Engine_Api::_()->getApi('settings', 'core')
      ->getSetting('core.general.notificationupdate');

  }
}
