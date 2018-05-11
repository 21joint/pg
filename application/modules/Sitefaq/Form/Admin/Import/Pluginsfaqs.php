<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Pluginsfaqs.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Form_Admin_Import_Pluginsfaqs extends Engine_Form {

  public function init() {

    $this
            ->setTitle('Import a File')
            ->setDescription("Add a CSV file to import FAQs corresponding to the entries in it, then click 'Submit'. Below, you can also set the availability of those FAQs to users based on various criteria.")
            ->setAttrib('enctype', 'multipart/form-data')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

		$sitefaq_api = Engine_Api::_()->sitefaq();

    $this->addElement('File', 'filename', array(
        'label' => 'Import File',
        'required' => true,
    ));
    $this->filename->getDecorator('Description')->setOption('placement', 'append');

		$level_id = Engine_Api::_()->user()->getViewer()->level_id;

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

    $this->addElement('Radio', 'import_seperate', array(
            'label' => 'File Columns Separator',
            'description' => 'Select a separator from below which you are using for the columns of the CSV file.',
						'required' => true,
						'allowEmpty' => false,
            'multiOptions' => array(
                    1 => "Pipe ('|')",
                    0 => "Comma (',')"
            ),
    ));

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