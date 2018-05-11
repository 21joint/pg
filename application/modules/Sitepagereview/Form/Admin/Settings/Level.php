<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Level.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitepagereview_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract
{
  public function init()
  {
    parent::init();

    $this
      ->setTitle('Member Level Settings')
      ->setDescription('These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.');

    if( !$this->isPublic() ) {

      $this->addElement('Radio', 'create', array(
        'label' => 'Allow Review Writing on Pages?',
        'description' => 'Do you want to let members write reviews on pages?',
        'multiOptions' => array(
          1 => 'Yes, allow write reviews on pages.',
          0 => 'No, do not allow write reviews on pages.',
        ),
        'value' => 1,
      ));

    }
  }
}
?>