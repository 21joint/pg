<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Import.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Form_Admin_Import_Import extends Engine_Form {

  public function init() {

    $this
            ->setTitle('Import FAQs from Advertisements / Community Ads Plugin')
            ->setDescription("Set the availability of the imported FAQs, then click 'Submit' to start importing FAQs.")
            ->setAttrib('enctype', 'multipart/form-data')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

		$level_id = Engine_Api::_()->user()->getViewer()->level_id;
		$sitefaq_api = Engine_Api::_()->sitefaq();

		if(Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'memberlevels')) {

			$levels_prepared = $sitefaq_api->getMemberLevels();

			$this->addElement('Multiselect', 'member_levels', array(
					'label' => 'Member Levels',
					'description' => 'Select the Member Levels to which the imported FAQs should be available. Use CTRL-click to select or deselect multiple Member Levels.',
					'multiOptions' => $levels_prepared,
					'value' => 0,
			));
		}

		$topLevelOptions = $sitefaq_api->getProfileTypes(0);
		$topLevelOptionsCount = Count($topLevelOptions);

		if(Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'profiletypes') && $topLevelOptionsCount > 2) {

			$this->addElement('Multiselect', 'profile_types', array(
					'label' => 'Profile Types',
					'description' => 'Select the Profile Types to which the imported FAQs should be available. Use CTRL-click to select or deselect multiple Profile Types.',
					'multiOptions' => $topLevelOptions,
					'value' => 0,
			));
		}

		if(Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'networks')) {
		
			$networksOptions = $sitefaq_api->getNetworks();

			$this->addElement('Multiselect', 'networks', array(
					'label' => 'Networks Selection',
					'description' => 'Select the Networks, members of which should be able to see the imported FAQs. Use CTRL-click to select or deselect multiple Networks.',
					'multiOptions' => $networksOptions,
					'value' => 0,
			));
		}

    $this->addElement('Button', 'submit', array(
        'label' => 'Submit',
        'type' => 'submit',
        'decorators' => array(
            'ViewHelper',
        ),
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'prependText' => ' or ',
        'onclick' => "javascript:parent.Smoothbox.close()",
        'decorators' => array(
            'ViewHelper',
        ),
    ));

    $this->addDisplayGroup(array(
        'submit',
        'cancel',
            ), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper'
        ),
    ));
  }

}