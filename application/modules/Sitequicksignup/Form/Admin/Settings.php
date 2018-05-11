<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Settings.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_Form_Admin_Settings extends Engine_Form {

    public function init() {

        $this
                ->setTitle('Sign Up Form Settings')
                ->setDescription('These settings affect all members in your community.');

        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));
    }

}
