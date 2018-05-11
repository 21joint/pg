<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Level.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Form_Admin_Level extends Authorization_Form_Admin_Level_Abstract
{
  public function init()
  {
    parent::init();

    // My stuff
    $this
    ->setTitle('Member Level Settings')
    ->setDescription("These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.");

      // Buy credits?
    $this->addElement('Radio', 'buy', array(
      'label' => 'Allow Buying of Credits',
      'description' => 'Do you want to let members buy credits?',
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No',
        ),
      'value' => 1,
      ));

      // send credits?
    $this->addElement('Radio', 'send', array(
      'label' => 'Allow Sending of Credits to Friends',
      'description' => 'Do you want to let members to send credits to their friends?',
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No',
        ),
      'value' => 1,
      ));


      // Element: max
    $this->addElement('Text', 'max_perday', array(
      'label' => 'Credit Earning Limit',
      'description' => 'Enter the maximum number of credits that can be earned in a day.[ Note : This field must contain an integer. Enter 0 for unlimited. ]',
      'validators' => array(
        array('Int', true),
        new Engine_Validate_AtLeast(0),
        ),
      ));
    $this->addElement('Text', 'link_credit', array(
      'label' => 'Referral Signups Credit Value',
      'description' => 'Enter the credit value that can be earned when someone signup using Referral Link.',
      'validators' => array(
        array('Int', true),
        new Engine_Validate_AtLeast(0),
        ),
      ));
  }
  
}