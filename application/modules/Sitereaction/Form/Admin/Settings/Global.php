<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitereaction_Form_Admin_Settings_Global extends Engine_Form {

    // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
    public $_SHOWELEMENTSBEFOREACTIVATE = array(
	"submit_lsetting", "environment_mode"
    );

    public function init() {

	$this
		->setTitle('General Settings')
		->setName('sitereaction_global_settings')
		->setDescription('These settings affect all members in your community.');

	$coreSettingsApi = Engine_Api::_()->getApi('settings', 'core');
        
        $this->addElement('Radio', 'sitereaction_collection_active', array(
	    'label' => 'Allow Stickers',
	    'description' => 'Do you want to allow users to post stickers via comments?',
	    'multiOptions' => array(
		1 => 'Yes',
		0 => 'No'
	    ),
	    'value' => $coreSettingsApi->getSetting('sitereaction.collection.active', 1),
	));
    $this->getElement('sitereaction_collection_active')->getDecorator('Description')->setOptions(array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

    $this->addElement('Radio', 'sitereaction_reaction_active', array(
	    'label' => 'Allow Reactions',
	    'description' => 'Do you want to enable reactions for post and content?',
	    'multiOptions' => array(
		1 => 'Yes',
		0 => 'No'
	    ),
	    'value' => $coreSettingsApi->getSetting('sitereaction.reaction.active', 1),
	));
    $this->getElement('sitereaction_reaction_active')->getDecorator('Description')->setOptions(array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('nestedcomment')) {
	    $this->addElement('Radio', 'sitereaction_reaction_withdislike_active', array(
		'label' => 'Allow reactions with Like and Dislike feature',
		'description' => 'Do you want to enable reactions on post and content when you have enabled Like and Dislike feature ? If enabled you will be able to see reactions on Like button.',
		'multiOptions' => array(
		    1 => 'Yes',
		    0 => 'No'
		),
		'value' => $coreSettingsApi->getSetting('sitereaction.reaction.withdislike.active', 1),
	    ));
	}

	$this->addElement('Button', 'save', array(
	    'label' => 'Save Changes',
	    'type' => 'submit',
	    'ignore' => true
	));
    }

}
