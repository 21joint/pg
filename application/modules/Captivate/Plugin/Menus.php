<?php

/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Menus.php 9770 2012-08-30 02:36:05Z richard $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Captivate_Plugin_Menus {

    public function onMenuInitialize_CaptivateCoreMiniAdmin($row) {
        // @todo check perms
        if (Engine_Api::_()->getApi('core', 'authorization')->isAllowed('admin', null, 'view')) {
            return array(
                'label' => $row->label,
                'route' => 'admin_default',
                'class' => 'no-dloader',
            );
        }

        return false;
    }

    public function onMenuInitialize_CaptivateCoreMiniAuth($row) {
        $viewer = Engine_Api::_()->user()->getViewer();
        if ($viewer->getIdentity()) {
            return array(
                'label' => 'Sign Out',
                'route' => 'user_logout',
                'class' => 'no-dloader',
            );
        } else {
            return array(
                'label' => 'Sign In',
                'route' => 'user_login',
                'params' => array(
                    // Nasty hack
                    'return_url' => '64-' . base64_encode($_SERVER['REQUEST_URI']),
                ),
            );
        }
    }

    public function onMenuInitialize_CaptivateCoreMiniSignin($row) {
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer->getIdentity()) {
            return array(
                'label' => 'Sign In',
                'route' => 'user_login',
                'params' => array(
                    // Nasty hack
                    'return_url' => '64-' . base64_encode($_SERVER['REQUEST_URI']),
                ),
            );
        }
    }

    public function onMenuInitialize_CaptivateSiteeventticketMainTicket() {

        //GET VIEWER
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        //RETURN IF VIEWER IS EMPTY
        if (empty($viewer_id)) {
            return false;
        }

        //MUST BE ABLE TO VIEW EVENTS
        if (!Engine_Api::_()->authorization()->isAllowed('siteevent_event', $viewer, "view")) {
            return false;
        }

        if (!Engine_Api::_()->siteevent()->hasTicketEnable()) {
            return false;
        }
        return array(
            'route' => 'siteeventticket_order',
            'params' => array(
                'module' => 'siteeventticket',
                'controller' => 'order',
                'action' => 'my-tickets'
            ),
        );
    }

}
