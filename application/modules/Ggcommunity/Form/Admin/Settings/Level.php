<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Blog
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Level.php 10100 2013-10-24 23:09:16Z guido $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Blog
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Ggcommunity_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract
{
  public function init()
  {
    parent::init();

    // My stuff
    $this
      ->setTitle('Member Level Settings')
      ->setDescription("GGCOMMUNITY_FORM_ADMIN_LEVEL_DESCRIPTION");

    // Element: create question
    $this->addElement('Radio', 'view_question', array(
      'label' => 'Can this member level view Question?',
      'description' => 'Do you want to let members view Questions? If set to no, some other settings on this page may not apply.',
      'multiOptions' => array(
        1 => 'Yes, allow viewing of questions.',
        0 => 'No, do not allow viewing of questions.',
      ),
      'value' => 1,
    ));

    // Element: create question
    $this->addElement('Radio', 'create_question', array(
      'label' => 'Can this member level create Question?',
      'description' => 'Do you want to let members create Questions? If set to no, some other settings on this page may not apply.',
      'multiOptions' => array(
        1 => 'Yes, allow creating of questions.',
        0 => 'No, do not allow creating of questions.',
      ),
      'value' => 1,
    ));


    if( !$this->isPublic() ) {

      // Element: comment on question
      $this->addElement('Radio', 'comment_question', array(
        'label' => 'Allow Comment on Questions?',
        'description' => 'Do you want to let members comment on question? If set to no, some other settings on this page may not apply. This is useful if you want members to be able to view questions, but only want certain levels to be able to comment questions.',
        'multiOptions' => array(
          1 => 'Yes, allow comment on question.',
          0 => 'No, do not allow questions to be commented.'
        ),
        'value' => 1,
      ));  

      // Element: Approve question
      $this->addElement('Radio', 'approve_question', array(
        'label' => 'Approve Question?',
        'description' => 'Can this member level approve questions.',
        'multiOptions' => array(
          1 => 'Yes, allow approving question by this member level.',
          0 => 'No, do not allow approving question by this member level.',
        ),
        'value' => 1,
      ));

      // Element: Allow vote on question
      $this->addElement('Radio', 'vote_question', array(
        'label' => 'Vote Question?',
        'description' => 'Can this member up/down vote question.',
        'multiOptions' => array(
          1 => 'Yes, allow up/down vote question by this member level.',
          0 => 'No, do not allow up/down vote question by this member level.',
        ),
        'value' => 1,
      ));

      // Element: Allow edit close date
      $this->addElement('Radio', 'edit_close_date', array(
        'label' => 'Change Close Date?',
        'description' => 'Can this member level change the close date.',
        'multiOptions' => array(
          1 => 'Yes, allow changing close date on question by this member level.',
          0 => 'No, do not allow changing close date on question by this member level.',
        ),
        'value' => 1,
      ));
      

      // Element: edit question
      $this->addElement('Radio', 'edit_question', array(
        'label' => 'Allow Editing of Question?',
        'description' => 'Can this member level edit the question.',
        'multiOptions' => array(
          2 => 'Yes, allow members to edit all questions.',
          1 => 'Yes, allow members to edit their own questions.',
          0 => 'No, do not allow members to edit their question.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->edit->options[2]);
      }
      
      $this->addElement('Radio', 'delete_question', array(
        'label' => 'Allow Deletion of Question?',
        'description' => 'Can this member level delete the question.',
        'multiOptions' => array(
          2 => 'Yes, allow members to delete all questions.',
          1 => 'Yes, allow members to delete their own questions.',
          0 => 'No, do not allow members to delete their question.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->edit->options[2]);
      }

      // Element: featured question
      $this->addElement('Radio', 'featured_question', array(
        'label' => 'Allow Automatically created question to be featured?',
        'description' => 'Are Question created by this member level will be automatically featured?',
        'multiOptions' => array(
          1 => 'Yes, automatically allow created question from this members to be featured.',
          0 => 'No, do not automatically allow created question from this members to be featured.',
        ),
        'value' => 1,
      ));
      

      // Element: sponsored question
      $this->addElement('Radio', 'sponsored_question', array(
        'label' => 'Allow Automatically created question to be sponsored?',
        'description' => 'Are Question created by this member level will be automatically sponsored?',
        'multiOptions' => array(
          1 => 'Yes, automatically allow created question from this members to be sponsored.',
          0 => 'No, do not automatically allow created question from this members to be sponsored.',
        ),
        'value' => 1,
      ));
      
      $this->addElement('Radio', 'flag_question', array(
        'label' => 'Allow Flag Question?',
        'description' => 'Can this member level flag an question?',
        'multiOptions' => array(
          1 => 'Yes, allow members flag Question.',
          0 => 'No, do not allow flag Question.',
        ),
        'value' => 1,
      ));

      $this->addDisplayGroup(array('create_question','comment_question','approve_question', 'vote_question', 'edit_close_date', 'edit_question',  'featured_question', 'sponsored_question','flag_question' ), 'question', array('legend' => 'Question Features'));


      // Element: answer question
      $this->addElement('Radio', 'answer_question', array(
        'label' => 'Allow answering questions?',
        'description' => 'Can this member level answer Question?',
        'multiOptions' => array(
          1 => 'Yes, allow members answering questions.',
          0 => 'No, do not allow members answering questions.',
        ),
        'value' => 1,
      ));
      
      // Element: comment answer
      $this->addElement('Radio', 'comment_answer', array(
        'label' => 'Allow commenting answers?',
        'description' => 'Can this member level comment on Answer?',
        'multiOptions' => array(
          1 => 'Yes, allow members comment on Answers.',
          0 => 'No, do not allow members comment on Answers.',
        ),
        'value' => 1,
      ));

      // Element: up/down vote answer
      $this->addElement('Radio', 'vote_answer', array(
        'label' => 'Vote Answer?',
        'description' => 'Can this member level up/down vote answer?',
        'multiOptions' => array(
          1 => 'Yes, allow members up/down vote on Answers.',
          0 => 'No, do not allow members up/down vote on Answers.',
        ),
        'value' => 1,
      ));

      // Element: declare best answer
      $this->addElement('Radio', 'best_answer', array(
        'label' => 'Declare an answer the best one?',
        'description' => 'Can this member level declare an answer as the best?',
        'multiOptions' => array(
          4 => 'Yes, allow members re-declare all Answers.',
          3 => 'Yes, allow members declare all Answers.',
          2 => 'Yes, allow members re-declare their own Answer.',
          1 => 'Yes, allow members declare their own Answer.',
          0 => 'No, do not allow declare Answer.',
        ),
        'value' => 1,
      ));
      
      
      $this->addElement('Radio', 'flag_answer', array(
        'label' => 'Allow Flag Answer?',
        'description' => 'Can this member level flag an answer?',
        'multiOptions' => array(
          1 => 'Yes, allow members flag Answer.',
          0 => 'No, do not allow flag Answer.',
        ),
        'value' => 1,
      ));

      $this->addDisplayGroup(array('answer_question','comment_answer','vote_answer', 'best_answer','flag_answer' ), 'answer', array('legend' => 'Answer Features'));
      
    }
  }
}