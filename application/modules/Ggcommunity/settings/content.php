<?php
/**
 * EXTFOX
 *
 * @category   Application_Core
 * @package    Ggcommunity
 * @copyright  Copyright 2017 EXTFOX
 * @license    http://www.extfox.com/license/
 * @version    $Id: content.php 9868 2013-02-12 21:50:45Z EXTFOX $
 * @author     EXTFOX
 */

$logoOptions = array('' => 'Text-only (No logo)');
$imageExtensions = array('gif', 'jpg', 'jpeg', 'png','svg');

$it = new DirectoryIterator(APPLICATION_PATH . '/public/admin/');

foreach( $it as $file ) {
  if( $file->isDot() || !$file->isFile() ) continue;
  $basename = basename($file->getFilename());
  if( !($pos = strrpos($basename, '.')) ) continue;
  $ext = strtolower(ltrim(substr($basename, $pos), '.'));
  if( !in_array($ext, $imageExtensions) ) continue;
  $logoOptions['public/admin/' . $basename] = $basename;
}

return array(
  
  array(
    'title' => 'Ggcommunity Answer',
    'description' => 'Answer.',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.ggcommunity-answer',
  ),

  array(
    'title' => 'Ggcommunity Related',
    'description' => 'Related Questions.',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.ggcommunity-related',
    'defaultParams' => array(
      'title' => 'Related Struggles'
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Text',
          'title',
          array(
            'label' => 'Title',
            'value' => 'Related Struggles'
          )
        ),
      )
    ),
  ),

  array(
    'title' => 'Draft Question',
    'description' => 'Draft Questions.',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.draft-question',
    'defaultParams' => array(
      'title' => 'Draft',
      'content' => 'You arre currently in <b>draft</b> mode. Click on the button bellow to publish.'
    ),
    'autoEdit' => true,
    'adminForm' => 'Ggcommunity_Form_Admin_Widget_DraftQuestion',
  ),

  array(
    'title' => 'Featured Topics',
    'description' => 'Featured Topics.',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.featured-topic',
    'defaultParams' => array(
      'title' => 'Featured Topics'
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Text',
          'title',
          array(
            'label' => 'Title',
            'value' => 'Featured Topics'
          )
        ),
      )
    ),
  ),

  array(
    'title' => 'Topic Question Profile',
    'description' => 'Display question profile',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.topic-question-profile',
  ),

  array(
    'title' => 'Answer Content',
    'description' => 'Display Answers Content',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.answer-content',
  ),

  array(
    'title' => 'Question Comment Conntent',
    'description' => 'Display Comments for specific Question',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.question-comment-content',
  ),

  array(
    'title' => 'Search Struggle',
    'description' => 'Browse Struggles',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.search-struggle',
    'defaultParams' => array(
      'title' => 'PARENTAL GUIDANCE',
      'description' => 'Share Your Struggle, Provide Your Theories, and Gain Advice'
    ),
    'autoEdit' => true,
    'adminForm' => 'Ggcommunity_Form_Admin_Widget_SearchStruggle',
  ),

  array(
    'title' => 'Featured Topics',
    'description' => 'View All Featured Topics with Picture',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.featured-topics',
    'defaultParams' => array(
      'title' => 'Featured Topics'
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Text',
          'title',
          array(
            'label' => 'Title',
            'value' => 'Featured Topics'
          )
        ),
      )
    ),
  ),

  array(
    'title' => 'How it Works',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.how-to-ask',
    'defaultParams' => array(
      'title' => 'How It Works',
      'description' => 'We prefer to discuss struggles in a question and answer format',
      'body' => '<ul>
      <li>Phrase your struggle like a question</li>
      <li>Check for grammar and spelling errors</li>
      <li>Other parents can propose theories</li>
      <li>Everyone can vote and comment on the theories</li>
      <li>The Author or Select Experts can select their favorite theory</li>
      </ul>',
    ),
    'autoEdit' => true,
    'adminForm' => 'Ggcommunity_Form_Admin_Widget_HowToAsk',
  ),

  array(
    'title' => 'Earn Points',
    'description' => 'Earn Points',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.earn-points',
    'defaultParams' => array(
      'title' => 'How Can I Earn Points',
      'rule_one' => 'Awarded a Best Answer',
      'points_rule_one' => '10',
      'rule_two' => 'Answer question',
      'points_rule_two' => '2',
      'rule_three' => 'Choose a Best Answer',
      'points_rule_three' => '3',
    ),
    'autoEdit' => true,
    'adminForm' => 'Ggcommunity_Form_Admin_Widget_EarnPoints',
  ),

  array(
    'title' => 'Trending Struggle',
    'description' => 'View All trending Struggles',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.trending-struggles',
    'defaultParams' => array(
      'title' => 'Trending Struggles'
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Text',
          'title',
          array(
            'label' => 'Title',
            'value' => 'Trending Struggles'
          )
        ),
      )
    ),
  ),

  array(
    'title' => 'Latest Struggle',
    'description' => 'View All Latest Struggles',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.latest-struggles',
    'defaultParams' => array(
      'title' => 'Latest Struggles'
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Text',
          'title',
          array(
            'label' => 'Title',
            'value' => 'Latest Struggles'
          )
        ),
      )
    ),
  ),

  array(
    'title' => 'Unanswered Struggle',
    'description' => 'View All Unanswered Struggles',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.unanswered-struggles',
    'defaultParams' => array(
      'title' => 'Unanswered Struggles'
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Text',
          'title',
          array(
            'label' => 'Title',
            'value' => 'Unanswered Struggles'
          )
        ),
        array(
          'Select',
          'show_votes',
          array(
            'label' => 'Show upvotes/comments',
            'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
            ),
            'value' => 0,
          )
        ),
      )
    ),
  ),

  array(
    'title' => 'Top Theorist',
    'description' => 'View All Top Theorists',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.top-theorist',
    'defaultParams' => array(
      'title' => 'Top Theorist',
      'more' => 0
    ),
    'autoEdit' => true,
    'adminForm' => 'Ggcommunity_Form_Admin_Widget_TopTheorist',
  ),

  array(
    'title' => 'New Struggle',
    'description' => 'Create New Struggle',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.new-struggle',
    'defaultParams' => array(
      'title' => 'New Struggle',
      'new_struggle' => 1
    ),
    'autoEdit' => true,
    'adminForm' => 'Ggcommunity_Form_Admin_Widget_NewStruggle',
  ),

  array(
    'title' => 'Review product',
    'description' => 'Display Product',
    'category' => 'Ggcommunity',
    'type' => 'widget',
    'name' => 'ggcommunity.review-product',
    'defaultParams' => array(
      'title' => '"I have finally have all my diaper bag essential in one place and organized"',
      'select_image' => 0
    ),
    'autoEdit' => true,
    'adminForm' => 'Ggcommunity_Form_Admin_Widget_ReviewProduct',
  ),

  

) ?>
