<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_Widget_SignupController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        // Do not show if logged in
        if (Engine_Api::_()->user()->getViewer()->getIdentity()) {
            return $this->setNoRender();
        }


        $this->view->form = $form = new Sitequicksignup_Form_Signup_Fields();
    }

    public function getCacheKey() {
        return false;
    }

}
