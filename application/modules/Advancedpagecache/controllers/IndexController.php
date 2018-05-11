<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 6590 2012-26-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Advancedpagecache_IndexController extends Core_Controller_Action_Standard {

    public function indexAction() {
        if (Engine_Api::_()->user()->getViewer()->getIdentity()) {
            return $this->_helper->redirector->gotoRoute(array('action' => 'home'), 'user_general', true);
        }

        // check public settings
        if (!Engine_Api::_()->getApi('settings', 'core')->core_general_portal &&
                !$this->_helper->requireUser()->isValid()) {
            return;
        }

        // Render
        $this->_helper->content
                ->setNoRender()
                ->setEnabled()
        ;
    }

}
