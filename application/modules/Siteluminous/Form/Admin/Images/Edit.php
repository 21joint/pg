<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Edit.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteluminous_Form_Admin_Images_Edit extends Siteluminous_Form_Admin_Images_Add {

    public function init() {
        parent::init();

        $this
                ->setTitle('Edit Image')
                ->setDescription('Upload an image for Landing Page. (Note: The recommended size for the image is: 750px x 470px.)')
                ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
    }

}
