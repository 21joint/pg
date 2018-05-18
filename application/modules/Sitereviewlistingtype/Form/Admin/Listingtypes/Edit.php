<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereviewlistingtype
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Edit.php 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereviewlistingtype_Form_Admin_Listingtypes_Edit extends Sitereviewlistingtype_Form_Admin_Listingtypes_Create {

  public function init() {

    parent::init();

    $this->setTitle('Edit Listing Type')
            ->setDescription('Edit your listing type over here, and then click on "Save Changes" button.')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
            ->setAttrib('name', 'sitereviews_create');
  }
}