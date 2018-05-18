<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Integration.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_Form_Admin_Sitemobile_Integration extends Sitepageintegration_Form_Admin_Integration {

  public function init() {
    parent::init();
    if ($this->getElement('title_truncation')) {
      $this->removeElement('title_truncation');
    }
  }

}