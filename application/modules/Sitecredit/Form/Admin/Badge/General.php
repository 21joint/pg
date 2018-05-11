<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitecredit_Form_Admin_Badge_General extends Engine_Form {

    public function init() {

        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        $this  ->setTitle('Badge Settings')
        ->setDescription('These settings affect all users in your community.');
        
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

        $this->addElement('Radio', 'sitecredit_badge_enable', array(
            'label' => 'Allow Badges',
            'description' => 'Do you want to enable Badges for your site users?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
                ),
            'onchange' => 'onBadgeSettingChange(this)',
            'value' => $coreSettings->getSetting('sitecredit.badge.enable', 0),
            ));

        $this->addElement('Radio', 'sitecredit_ranking', array(
            'label' => 'Assign Badges',
            'description' => 'When to give badges to users?',
            'multiOptions' => array(
                0 => 'Basis of current credit balance',
                1 => 'Basis of credits achieved'
                ),
            'value' => $coreSettings->getSetting('sitecredit.ranking', 0),
            ));
        

        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
            ));

    }

}
