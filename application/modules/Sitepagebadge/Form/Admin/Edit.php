<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Edit.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_Form_Admin_Edit extends Sitepagebadge_Form_Admin_Create {

  public function init() {
    parent::init();
    $this->setTitle('Edit Badge Entry')
            ->setDescription('Edit your Badge over here and then click on "Save Changes" to save it.');
    $this->submit->setLabel('Save Changes');
  }

}
?>