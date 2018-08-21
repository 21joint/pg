<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Form_Admin_Permission_Guide extends Authorization_Form_Admin_Level_Abstract {

    public function init() {
        parent::init();

        $this->setTitle('Guide - Member Level Settings')
                ->setDescription("These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.");

        $this->addElement('Radio', "view", array(
            'label' => 'Allow Viewing of Guides?',
            'description' => 'Do you want to let members view guides? If set to no, some other settings on this page may not apply.',
            'multiOptions' => array(
                2 => 'Yes, allow viewing of all guides, even private ones.',
                1 => 'Yes, allow viewing of guides.',
                0 => 'No, do not allow guides to be viewed.',
            ),
            'value' => ( $this->isModerator() ? 2 : 1 ),
        ));
        if (!$this->isModerator()) {
            unset($this->view->options[2]);
        }
        
        if (!$this->isPublic()) {
            $this->addElement('Radio', "create", array(
                'label' => 'Allow Creation of Guides?',
                'description' => 'Do you want to let members create guides? If set to no, some other settings on this page may not apply.',
                'multiOptions' => array(
                    1 => 'Yes, allow creation of guides.',
                    0 => 'No, do not allow guides to be created.',
                ),
                'value' => 1,
            ));
            
            $this->addElement('Radio', "edit", array(
                'label' => 'Allow Editing of Guides?',
                'description' => 'Do you want to let members edit guides? If set to no, some other settings on this page may not apply.',
                'multiOptions' => array(
                    2 => 'Yes, allow members to edit all guides.',
                    1 => 'Yes, allow members to edit their own guides.',
                    0 => 'No, do not allow members to edit their guides.',
                ),
                'value' => ( $this->isModerator() ? 2 : 1 ),
            ));
            if (!$this->isModerator()) {
                unset($this->edit->options[2]);
            }
            
            $this->addElement('Radio', "delete", array(
                'label' => 'Allow Deletion of Guides?',
                'description' => 'Do you want to let members delete guides? If set to no, some other settings on this page may not apply.',
                'multiOptions' => array(
                    2 => 'Yes, allow members to delete all guides.',
                    1 => 'Yes, allow members to delete their own guides.',
                    0 => 'No, do not allow members to delete their guides.',
                ),
                'value' => ( $this->isModerator() ? 2 : 1 ),
            ));
            if (!$this->isModerator()) {
                unset($this->delete->options[2]);
            }
            
            $this->addElement('Radio', "like", array(
                'label' => 'Allow Like on Guides?',
                'description' => 'Do you want to let members of this level like on guides?',
                'multiOptions' => array(
                    2 => 'Yes, allow members to like on all guides, including private ones.',
                    1 => 'Yes, allow members to like on guides.',
                    0 => 'No, do not allow members to like on guides.',
                ),
                'value' => ( $this->isModerator() ? 2 : 1 ),
            ));
            if (!$this->isModerator()) {
                unset($this->like->options[2]);
            }
            
            $this->addElement('Radio', "rate", array(
                'label' => 'Allow Rating of Guides?',
                'description' => 'Do you want to let members rate guides? If set to no, some other settings on this page may not apply.',
                'multiOptions' => array(
                    1 => 'Yes, allow rating of guides.',
                    0 => 'No, do not allow guides to be rated.',
                ),
                'value' => ( $this->isModerator() ? 2 : 1 ),
            ));
            
            $this->addElement('Radio', "comment", array(
                'label' => 'Allow Commenting on Guides?',
                'description' => 'Do you want to let members of this level comment on guides?',
                'multiOptions' => array(
                    2 => 'Yes, allow members to comment on all guides, including private ones.',
                    1 => 'Yes, allow members to comment on guides.',
                    0 => 'No, do not allow members to comment on guides.',
                ),
                'value' => ( $this->isModerator() ? 2 : 1 ),
            ));
            if (!$this->isModerator()) {
                unset($this->comment->options[2]);
            }
            
            $this->addElement('Radio', "approve", array(
                'label' => 'Guides Approval?',
                'description' => 'Do you want new guides to be automatically approved?',
                'multiOptions' => array(
                    1 => 'Yes, automatically approve Guides.',
                    0 => 'No, site admin approval will be required for all Guides.'
                ),
                'value' => 1,
            ));
            
            $this->addElement('Radio', "flag", array(
                'label' => 'Allow Flag?',
                'description' => 'Do you want to let members to flag guides?',
                'multiOptions' => array(
                    1 => 'Yes, allow members to flag guides.',
                    0 => 'No, do not allow members to flag guides.',
                ),
                'value' => 1,
            ));
        }
    }

}
