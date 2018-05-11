<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Level.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract
{
  public function init()
  {
		parent::init();

		$this
			->setTitle('Member Level Settings')
			->setDescription("These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.");

		if( !$this->isPublic() ) {

			$this->addElement( 'Dummy' , 'dummy_sitefaq_creation' , array ('label' => 'Creation Settings'));

			$this->addElement('Radio', 'create', array(
				'label' => 'Allow Creation of FAQs',
				'description' => 'Should users of this Member Level be able to create FAQs?',
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'value' => ( $this->isModerator() ? 1 : 0 ),
			));

      $this->addElement('Radio', 'edit', array(
        'label' => 'Allow Editing of FAQs?',
        'description' => 'Should users of this Member Level be able to edit FAQs?',
        'multiOptions' => array(
          2 => 'Yes, allow members to edit everyone\'s FAQs.',
          1 => 'Yes, allow  members to edit their own FAQs.',
          0 => 'No, do not allow FAQs to be edited.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->edit->options[2]);
      }

      $this->addElement('Radio', 'delete', array(
        'label' => 'Allow Deletion of FAQs?',
        'description' => 'Should users of this Member Level be able to delete FAQs?',
        'multiOptions' => array(
          2 => 'Yes, allow members to delete everyone\'s FAQs.',
          1 => 'Yes, allow  members to delete their own FAQs.',
          0 => 'No, do not allow FAQs to be deleted.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->delete->options[2]);
      }

			$this->addElement('Radio', 'memberlevels', array(
				'label' => 'Member Level Options',
				'description' => "Should users of this Member Level be able to choose for each of their FAQs, the Member Levels to whom their FAQs should be visible? If enabled, the option for this will appear on the 'Create New FAQ’ and ‘Edit FAQ’ pages. If you select ‘No’ over here, then users of all Member Levels will be able to view FAQs from this Member Level.",
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'value' => ( $this->isModerator() ? 1 : 0 ),
			));

			$this->addElement('Radio', 'profiletypes', array(
				'label' => 'Profile Type Options',
				'description' => "Should users of this Member Level be able to choose for each of their FAQs, the Profile Types to whom their FAQs should be visible? If enabled, the option for this will appear on the 'Create New FAQ’ and ‘Edit FAQ’ pages. If you select ‘No’ over here, then users of all Profile Types will be able to view FAQs from this Member Level.",
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'value' => ( $this->isModerator() ? 1 : 0 ),
			));

			$this->addElement('Radio', 'networks', array(
				'label' => 'Network Options',
				'description' => "Should users of this Member Level be able to choose for each of their FAQs, the Networks to whom their FAQs should be visible? If enabled, the option for this will appear on the 'Create New FAQ’ and ‘Edit FAQ’ pages. If you select ‘No’ over here, then users of all Networks will be able to view FAQs from this Member Level.",
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'value' => 1,
			));

			$this->addElement('Radio', 'approved', array(
				'label' => 'Auto-Approve FAQs',
				'description' => 'Auto-Approve FAQs created by users of this Member Level? These FAQs will not need admin moderation approval before going live.',
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'value' => ( $this->isModerator() ? 1 : 0 ),
			));
		}

    $this->addElement( 'Dummy' , 'dummy_sitefaq_general' , array ('label' => 'General Settings'));

		$this->addElement('Radio', 'view', array(
			'label' => 'Allow Viewing of FAQs?',
			'description' => 'Should users of this Member Level be able to view FAQs?',
			'multiOptions' => array(
				2 => 'Yes, allow viewing of all FAQs, even private ones.',
				1 => 'Yes, allow viewing of FAQs.',
				0 => 'No, do not allow FAQs to be viewed.',
			),
			'value' => ( $this->isModerator() ? 2 : 1 ),
		));
		if( !$this->isModerator() ) {
			unset($this->view->options[2]);
		}

		$this->addElement('Radio', 'question', array(
			'label' => 'Allow Asking Questions',
			'description' => 'Should users of this Member Level be able to Ask Questions from you?',
			'multiOptions' => array(
				1 => 'Yes',
				0 => 'No'
			),
			'value' => 1,
		));

		if($this->isPublic()) {
			$this->addElement('Radio', 'helpful', array(
				'label' => 'Display Voting Options for FAQs',
				'description' => 'Should users of this Member Level be able to view the voting options for FAQs? (If you select ‘Yes’ over here, then users of this Member Level will be able to mark FAQs as Helpful / Not Helpful. In case of Public Members, they will be redirected to sign-in page, when they try to vote on an FAQ.)',
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'value' => 1,
			));
		}

		if( !$this->isPublic() ) {

			$this->addElement('Radio', 'rating', array(
				'label' => 'Allow Rating of FAQs',
				'description' => 'Should users of this Member Level be able to give rating to FAQs?',
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'value' => 1,
			));

			$this->addElement('Radio', 'helpful', array(
				'label' => 'Allow Marking FAQs Helpful',
				'description' => 'Should users of this Member Level be able to mark FAQs as helpful?',
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'value' => 1,
			));

			$this->addElement('Radio', 'comment', array(
				'label' => 'Allow Commenting on FAQs',
				'description' => 'Should users of this Member Level be able to comment on FAQs?',
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'value' => 1,
			));

			$this->addElement('Radio', 'share', array(
				'label' => 'Allow Sharing of FAQs',
				'description' => 'Should users of this Member Level be able to share FAQs?',
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'value' => 1,
			));
		}
  }

}