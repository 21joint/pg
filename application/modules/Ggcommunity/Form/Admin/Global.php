<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Blog
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Global.php 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Blog
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Ggcommunity_Form_Admin_Global extends Engine_Form
{
  public function init()
  {
    
    $this
      ->setTitle('Global Settings')
      ->setDescription('These settings affect all members in your community.')
    ;

    $this->addElement('Text', 'ggcommunity_automatically_close', array(
      'label' => 'GGCOMMUNITY_AUTOMATICALLY_CLOSE_QUESTION',
      'description' => 'After how many days question will be closed',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.automatically.close', 80),
    ));

    $this->addElement('Text', 'ggcommunity_question_page', array(
      'label' => 'GGCOMMUNITY_QUESTION_PAGE',
      'description' => 'How many questions will be shown per page? (Enter a number between 1 and 999)',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.question.page', 10),
    ));
    $this->addElement('Text', 'ggcommunity_answer_page', array(
      'label' => 'GGCOMMUNITY_ANSWER_PAGE' ,
      'description' => 'How many answers will be shown per page? (Enter a number between 1 and 999)',
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ggcommunity.answer.page', 10),
    ));


    // Add submit button
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true
    ));
  }
}