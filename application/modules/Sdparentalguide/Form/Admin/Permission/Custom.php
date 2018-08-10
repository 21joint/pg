<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Form_Admin_Permission_Custom extends Authorization_Form_Admin_Level_Abstract {

    public function init() {
        parent::init();

        $this->setTitle('Custom - Member Level Settings')
                ->setDescription("These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.");

        if (!$this->isPublic()) {
            $this->addElement('Radio', "comment_comment", array(
                'label' => 'Allow Comment on Comments?',
                'description' => 'Do you want to let members to comment on comments?',
                'multiOptions' => array(
                    1 => 'Yes, allow comment on comments.',
                    0 => 'No, do not allow comment on comments.',
                ),
                'value' => 1,
            ));
            
            $this->addElement('Radio', "like_comment", array(
                'label' => 'Allow Comment Likes?',
                'description' => 'Do you want to let members to like comments?',
                'multiOptions' => array(
                    1 => 'Yes, allow comment likes.',
                    0 => 'No, do not allow comment likes.',
                ),
                'value' => 1,
            ));
        
        }
    }

}
